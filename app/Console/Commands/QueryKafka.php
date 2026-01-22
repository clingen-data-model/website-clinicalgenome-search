<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Stream;
use App\Packet;

class QueryKafka extends Command
{
    protected $signature = 'query:kafka {topic} {--debug-offsets : Dump low/high watermarks and current/committed offsets then exit}';
    protected $description = 'Consume a Kafka topic and persist packets';

    public function handle()
    {
        $topicName = $this->argument('topic');

        if (!$topicName) {
            $this->error("Topic not found");
            return 1;
        }

        $stream = Stream::name($topicName)->first();
        if (!$stream) {
            $this->error("Topic not found");
            return 1;
        }

        $storedOffset = (int) ($stream->offset ?? 0);

        $conf = new \RdKafka\Conf();

        // group.id selection
        if ($topicName === 'all-curation-events') {
            $conf->set('group.id', 'web_stage');
        } else {
            $conf->set('group.id', 'web_prod');
        }

        // Confluent Cloud / SASL
        $conf->set('security.protocol', 'sasl_ssl');
        $conf->set('sasl.mechanism', 'PLAIN');
        $conf->set('sasl.username', $stream->username);
        $conf->set('sasl.password', $stream->password);

        $conf->set('metadata.broker.list', $stream->endpoint);
        $conf->set('auto.offset.reset', 'earliest');

        // IMPORTANT: do not auto-commit while you’re debugging offsets
        // (you can set it back later)
        $conf->set('enable.auto.commit', 'false');

        // --- Rebalance callback (kept, but safer) ---
        $conf->setRebalanceCb(function (\RdKafka\KafkaConsumer $kafka, $err, array $partitions = null) use ($storedOffset) {
            switch ($err) {
                case RD_KAFKA_RESP_ERR__ASSIGN_PARTITIONS:
                    // If you want to force a specific offset, do it PER partition *before* assign
                    if ($partitions) {
                        foreach ($partitions as $tp) {
                            // Only force if storedOffset is > 0, otherwise let RD_KAFKA_OFFSET_STORED work
                            if ($storedOffset > 0) {
                                $tp->setOffset($storedOffset);
                            } else {
                                $tp->setOffset(RD_KAFKA_OFFSET_STORED);
                            }
                        }
                    }
                    $kafka->assign($partitions);
                    break;

                case RD_KAFKA_RESP_ERR__REVOKE_PARTITIONS:
                    $kafka->assign(NULL);
                    break;

                default:
                    throw new \Exception((string) $err);
            }
        });

        $consumer = new \RdKafka\KafkaConsumer($conf);

        // If user only wants offsets dump, do it and exit
        if ($this->option('debug-offsets')) {
            $this->dumpOffsetsAndExit($consumer, $stream->topic, $conf->get('group.id'), $storedOffset);
            return 0;
        }

        // Subscribe (group-managed)
        $consumer->subscribe([$stream->topic]);

        while (true) {
            $message = $consumer->consume(45 * 1000);

            if ($message === null) {
                $this->line("Nothing here, bye");
                return 0;
            }

            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                case 0:
                    $m = new Packet([
                        'topic' => $stream->topic,
                        'type' => Packet::TYPE_KAFKA,
                        'uuid' => $message->key,
                        'offset' => $message->offset,
                        'timestamp' => $message->timestamp,
                        'payload' => $message->payload,
                        'status' => Packet::STATUS_ACTIVE
                    ]);
                    $m->save();

                    // Your existing parsing behavior
                    if ($topicName == "dosage" || $topicName == "actionability" || $topicName == 'gene-validity' || $topicName == 'variant_interpretation') {
                        $payload = json_decode($message->payload);
                        $a = $stream->parser;
                        $a($message, $m);

                        $stream->update(['offset' => $message->offset + 1]);

                    } else if (in_array($topicName, ['gpm-general-events','gpm-person-events','gpm-gene-events','gpm-checkpoint-events'], true)) {
                        $payload = json_decode($message->payload, true);

                        $a = $stream->parser;
                        $a($payload, $message->timestamp);

                        $stream->offset = $message->offset + 1;
                        $stream->save();

                    } else {
                        $payload = json_decode($message->payload);
                        $a = $stream->parser;
                        $a($payload);

                        $stream->update(['offset' => $message->offset + 1]);
                    }

                    // commit the message we just processed (per partition)
                    // (commit AFTER work completes)
                    $consumer->commit($message);

                    break;

                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    $this->line("No more messages; will wait for more");
                    return 0;

                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    $this->line("Timed out");
                    return 0;

                default:
                    throw new \Exception($message->errstr(), $message->err);
            }
        }
    }

    private function dumpOffsetsAndExit(\RdKafka\KafkaConsumer $consumer, string $topic, string $groupId, int $storedOffset): void
    {
        $this->line("=== Kafka Offset Debug ===");
        $this->line("Topic: {$topic}");
        $this->line("Group: {$groupId}");
        $this->line("Stored offset (DB streams.offset): {$storedOffset}");
        $this->line("");

        // Discover partitions from metadata
        $md = $consumer->getMetadata(true, null, 60 * 1000);
        $partitions = [];

        foreach ($md->getTopics() as $t) {
            if ($t->getTopic() !== $topic) continue;
            foreach ($t->getPartitions() as $p) {
                $partitions[] = $p->getId();
            }
        }

        if (!$partitions) {
            $this->error("No partitions found for topic (or topic missing in metadata).");
            return;
        }

        // Committed offsets for this group
        $tps = array_map(function ($pid) use ($topic) {
            return new \RdKafka\TopicPartition($topic, $pid);
        }, $partitions);

        $committed = [];
        try {
            $committedTps = $consumer->getCommittedOffsets($tps, 60 * 1000);
            foreach ($committedTps as $tp) {
                $committed[$tp->getPartition()] = $tp->getOffset();
            }
        } catch (\Throwable $e) {
            $this->warn("Could not fetch committed offsets for group (yet). Error: " . $e->getMessage());
        }

        // Print per-partition low/high + committed + lag + stored range check
        foreach ($partitions as $pid) {
            $low = 0;
            $high = 0;

            try {
                $consumer->queryWatermarkOffsets($topic, $pid, $low, $high, 60 * 1000);
            } catch (\Throwable $e) {
                $this->error("Partition {$pid}: watermark query failed: " . $e->getMessage());
                continue;
            }

            $comm = $committed[$pid] ?? null;

            // librdkafka uses special negatives when unknown
            $commLabel = ($comm === null) ? 'N/A' : (string) $comm;

            $lag = null;
            if ($comm !== null && $comm >= 0) {
                $lag = $high - $comm;
            }

            $inRange = ($storedOffset === 0) ? 'N/A' : (($storedOffset >= $low && $storedOffset <= $high) ? 'yes' : 'NO (out of range)');

            $this->line("Partition {$pid}:");
            $this->line("  start(low):  {$low}");
            $this->line("  end(high):   {$high}  (next offset, so message count ~= high-low)");
            $this->line("  committed:   {$commLabel}");
            if ($lag !== null) {
                $this->line("  lag:         {$lag}");
            }
            $this->line("  storedOffset in range?: {$inRange}");
            $this->line("");
        }

        $this->line("=== End Offset Debug ===");
    }
}
