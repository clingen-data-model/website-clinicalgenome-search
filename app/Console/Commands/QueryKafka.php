<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Stream;
use App\Packet;

class QueryKafka extends Command
{
    protected $signature = 'query:kafka
        {topic}
        {--assign : Use manual partition assignment (no consumer group offset control, no commits)}
        {--from-offset= : Absolute offset to start from (e.g. 100). Overrides DB offset when set}
        {--partition= : Only read this partition (e.g. 0). Default: all partitions}
        {--debug-read : Consume exactly 1 message and dump topic/partition/offset then exit}
        {--debug-offsets : Dump start/end offsets (watermarks) + committed offsets then exit}';

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
        $fromOffsetOpt = $this->option('from-offset');
        $startOffset = ($fromOffsetOpt !== null) ? (int) $fromOffsetOpt : $storedOffset;

        $useAssign = (bool) $this->option('assign');

        // --- Kafka config ---
        $conf = new \RdKafka\Conf();

        // group.id is still required by KafkaConsumer in many setups,
        // but in --assign mode we won't use group offset tracking.
        $groupId = ($topicName === 'all-curation-events') ? 'web_stage' : 'web_prod';
        $conf->set('group.id', $groupId);

        // Confluent Cloud / SASL
        $conf->set('security.protocol', 'sasl_ssl');
        $conf->set('sasl.mechanism', 'PLAIN');
        $conf->set('sasl.username', $stream->username);
        $conf->set('sasl.password', $stream->password);

        $conf->set('metadata.broker.list', $stream->endpoint);
        $conf->set('auto.offset.reset', 'earliest');

        // Always disable auto commit; we will commit manually only in subscribe mode
        $conf->set('enable.auto.commit', 'false');

        // IMPORTANT:
        // Only set rebalance callback when using subscribe().
        // In --assign mode, rebalance callbacks are not used and can confuse things.
        if (!$useAssign) {
            $conf->setRebalanceCb(function (\RdKafka\KafkaConsumer $kafka, $err, array $partitions = null) {
                switch ($err) {
                    case RD_KAFKA_RESP_ERR__ASSIGN_PARTITIONS:
                        $kafka->assign($partitions);
                        break;
                    case RD_KAFKA_RESP_ERR__REVOKE_PARTITIONS:
                        $kafka->assign(NULL);
                        break;
                    default:
                        throw new \Exception("Rebalance error: " . (string)$err);
                }
            });
        }

        $consumer = new \RdKafka\KafkaConsumer($conf);

        // Debug offsets (watermarks + committed)
        if ($this->option('debug-offsets')) {
            $this->dumpOffsetsAndExit($consumer, $stream->topic, $groupId);
            return 0;
        }

        // --- Start consuming: assign vs subscribe ---
        if ($useAssign) {
            $this->line("MODE: assign()");
            $this->line("Topic: {$stream->topic}");
            $this->line("Starting offset: {$startOffset}");

            $partitionOpt = $this->option('partition');
            $partitions = $this->getTopicPartitions($consumer, $stream->topic);

            if ($partitionOpt !== null) {
                $pid = (int) $partitionOpt;
                if (!in_array($pid, $partitions, true)) {
                    $this->error("Partition {$pid} not found in topic {$stream->topic}");
                    return 1;
                }
                $partitions = [$pid];
            }

            $tps = [];
            foreach ($partitions as $pid) {
                $tp = new \RdKafka\TopicPartition($stream->topic, $pid);
                $tp->setOffset($startOffset); // absolute offset
                $tps[] = $tp;
            }

            $consumer->assign($tps);

        } else {
            $this->line("MODE: subscribe()");
            $this->line("Topic: {$stream->topic} (group.id={$groupId})");
            $consumer->subscribe([$stream->topic]);
        }

        // --- Consume loop ---
        while (true) {
            $message = $consumer->consume(45 * 1000);

            if ($message === null) {
                $this->line("Nothing here, bye");
                return 0;
            }

            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                case 0:
                    if ($this->option('debug-read')) {
                        dump([
                            'endpoint'  => $stream->endpoint,
                            'topic'     => $message->topic_name,
                            'partition' => $message->partition,
                            'offset'    => $message->offset,
                            'timestamp' => $message->timestamp,
                            'key'       => $message->key,
                            'payload_preview' => substr((string)$message->payload, 0, 300),
                            'mode'      => $useAssign ? 'assign' : 'subscribe',
                            'db_offset' => $storedOffset,
                            'startOffsetRequested' => $startOffset,
                        ]);
                        return 0;
                    }

                    // Save packet
                    $m = new Packet([
                        'topic'     => $stream->topic,
                        'type'      => Packet::TYPE_KAFKA,
                        'uuid'      => $message->key,
                        'offset'    => $message->offset,
                        'timestamp' => $message->timestamp,
                        'payload'   => $message->payload,
                        'status'    => Packet::STATUS_ACTIVE
                    ]);
                    $m->save();

                    // Your parsing logic (kept)
                    if ($topicName == "dosage" || $topicName == "actionability" || $topicName == 'gene-validity' || $topicName == 'variant_interpretation') {
                        $payload = json_decode($message->payload);
                        $a = $stream->parser;
                        $a($message, $m);

                        // advance DB offset
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

                    // ✅ Only commit when in subscribe() mode
                    // In assign() mode, YOU control offsets; committing can put you right back where Kafka wants.
                    if (!$useAssign) {
                        $consumer->commit($message);
                    }

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

    private function getTopicPartitions(\RdKafka\KafkaConsumer $consumer, string $topic): array
    {
        $md = $consumer->getMetadata(true, null, 60 * 1000);

        $partitions = [];
        foreach ($md->getTopics() as $t) {
            if ($t->getTopic() !== $topic) continue;
            foreach ($t->getPartitions() as $p) {
                $partitions[] = $p->getId();
            }
        }

        return $partitions;
    }

    private function dumpOffsetsAndExit(\RdKafka\KafkaConsumer $consumer, string $topic, string $groupId): void
    {
        $this->line("=== Kafka Offset Debug ===");
        $this->line("Topic: {$topic}");
        $this->line("Group: {$groupId}");
        $this->line("");

        $partitions = $this->getTopicPartitions($consumer, $topic);
        if (!$partitions) {
            $this->error("No partitions found for topic {$topic}");
            return;
        }

        // committed offsets (group)
        $tps = array_map(fn($pid) => new \RdKafka\TopicPartition($topic, $pid), $partitions);

        $committed = [];
        try {
            $committedTps = $consumer->getCommittedOffsets($tps, 60 * 1000);
            foreach ($committedTps as $tp) {
                $committed[$tp->getPartition()] = $tp->getOffset();
            }
        } catch (\Throwable $e) {
            $this->warn("Committed offset fetch failed: " . $e->getMessage());
        }

        foreach ($partitions as $pid) {
            $low = 0; $high = 0;
            $consumer->queryWatermarkOffsets($topic, $pid, $low, $high, 60 * 1000);

            $comm = $committed[$pid] ?? null;
            $lag = ($comm !== null && $comm >= 0) ? ($high - $comm) : null;

            $this->line("Partition {$pid}:");
            $this->line("  start(low): {$low}");
            $this->line("  end(high):  {$high}");
            $this->line("  committed:  " . ($comm === null ? 'N/A' : (string)$comm));
            if ($lag !== null) $this->line("  lag:        {$lag}");
            $this->line("");
        }
    }
}
