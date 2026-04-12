<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Stream;
use App\Packet;

class QueryGPM extends Command
{
    /**
     * Usage examples:
     *  php artisan query:kafka gpm-general-events --assign
     *  php artisan query:kafka gpm-general-events --assign --from-offset=100
     *  php artisan query:kafka gpm-general-events --assign --partition=0
     */
    protected $signature = 'query:gpm
        {topic}
        {--assign : Use manual partition assignment (no consumer-group commits)}
        {--from-offset= : Absolute offset to start from (overrides DB offset)}
        {--partition= : Partition to read in assign mode (defaults to 0)}';

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

        //$useAssign = (bool) $this->option('assign');
        $useAssign = true;

        if (!$useAssign) {
            $this->error("This rewritten version is intended for --assign mode. Run with --assign.");
            return 1;
        }

        // -----------------------------
        // Determine start offset
        // -----------------------------
        $dbOffset = (int) ($stream->offset ?? 0);

        $fromOffsetOpt = $this->option('from-offset');
        $startOffset = ($fromOffsetOpt !== null) ? (int) $fromOffsetOpt : $dbOffset;

        // Default to partition 0 unless specified
        $partition = $this->option('partition');
        $partition = ($partition === null) ? 0 : (int) $partition;

        // -----------------------------
        // Kafka config
        // -----------------------------
        $conf = new \RdKafka\Conf();

        // group.id is still required by KafkaConsumer in many setups,
        // but in assign mode we will NOT commit offsets to Kafka.
        $groupId = ($topicName === 'all-curation-events') ? 'web_stage' : 'web_prod';
        $conf->set('group.id', $groupId);

        // Confluent Cloud / SASL
        $conf->set('security.protocol', 'sasl_ssl');
        $conf->set('sasl.mechanism', 'PLAIN');
        $conf->set('sasl.username', $stream->username);
        $conf->set('sasl.password', $stream->password);

        // Brokers
        $conf->set('metadata.broker.list', $stream->endpoint);

        // No auto-commit: DB offset is source of truth
        $conf->set('enable.auto.commit', 'false');

        // Emit PARTITION_EOF events when we hit end
        $conf->set('enable.partition.eof', 'true');

        // If offset is out of range and we ever fall back, prefer earliest
        $conf->set('auto.offset.reset', 'earliest');

        $consumer = new \RdKafka\KafkaConsumer($conf);

        // -----------------------------
        // Validate partition exists
        // -----------------------------
        $availablePartitions = $this->getTopicPartitions($consumer, $stream->topic);
        if (!in_array($partition, $availablePartitions, true)) {
            $this->error("Partition {$partition} not found in topic {$stream->topic}. Available: " . implode(',', $availablePartitions));
            return 1;
        }

        // -----------------------------
        // Determine watermarks and last offset at START of run
        // last message offset = high - 1 (e.g. high=284 => last=283)
        // -----------------------------
        $low = 0;
        $high = 0;
        $consumer->queryWatermarkOffsets($stream->topic, $partition, $low, $high, 60 * 1000);

        // If topic is empty
        if ($high <= $low) {
            $this->line("No messages available (low={$low}, high={$high}). Exiting.");
            return 0;
        }

        $lastOffsetAtStart = $high - 1;

        // Clamp requested start offset into valid range when possible
        if ($startOffset < $low) {
            $this->warn("Requested startOffset={$startOffset} is below low watermark={$low}. Clamping to {$low}.");
            $startOffset = $low;
        }

        // If start offset is already beyond the end, nothing to do
        if ($startOffset >= $high) {
            $this->line("StartOffset={$startOffset} is >= high watermark={$high}. Nothing to read. Exiting.");
            // Still set DB offset so next run doesn’t keep trying the same invalid value
            $stream->offset = $high; // next offset boundary
            $stream->save();
            return 0;
        }

        $this->line("MODE: assign()");
        $this->line("Endpoint: {$stream->endpoint}");
        $this->line("Topic: {$stream->topic}");
        $this->line("Partition: {$partition}");
        $this->line("Watermarks: low={$low}, high={$high} (last message offset at start={$lastOffsetAtStart})");
        $this->line("Starting from offset: {$startOffset}");

        // -----------------------------
        // Assign partition + starting offset (absolute)
        // -----------------------------
        $tp = new \RdKafka\TopicPartition($stream->topic, $partition);
        $tp->setOffset($startOffset);
        $consumer->assign([$tp]);

        // -----------------------------
        // Consume loop
        // Exit when we have processed lastOffsetAtStart
        // Update streams.offset as we go (store NEXT offset to resume cleanly)
        // -----------------------------
        while (true) {
            $message = $consumer->consume(45 * 1000);

            if ($message === null) {
                $this->line("No message returned. Exiting.");
                return 0;
            }

            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                case 0:
                    // Persist packet
                    $packet = new Packet([
                        'topic'     => $stream->topic,
                        'type'      => Packet::TYPE_KAFKA,
                        'uuid'      => $message->key,
                        'offset'    => $message->offset,
                        'timestamp' => $message->timestamp,
                        'payload'   => $message->payload,
                        'status'    => Packet::STATUS_ACTIVE,
                    ]);
                    $packet->save();

                    // Run parser (keeping your existing behavior)
                    if ($topicName === "dosage" || $topicName === "actionability" || $topicName === 'gene-validity' || $topicName === 'variant_interpretation') {
                        $payload = json_decode($message->payload);
                        $a = $stream->parser;
                        $a($message, $packet);

                    } elseif (in_array($topicName, ['gpm-general-events','gpm-person-events','gpm-gene-events','gpm-checkpoint-events'], true)) {
                        $payload = json_decode($message->payload, true);
                        $a = $stream->parser;
                        $a($payload, $message->timestamp);

                    } else {
                        $payload = json_decode($message->payload);
                        $a = $stream->parser;
                        $a($payload);
                    }

                    // ✅ Update DB offset so next run continues after this message
                    // Store NEXT offset to resume cleanly.
                    $stream->offset = (int) $message->offset + 1;
                    $stream->save();

                    // ✅ Exit when we processed the last message that existed at the start of this run
                    if ((int) $message->offset >= (int) $lastOffsetAtStart) {
                        $this->line("Reached last offset at start ({$lastOffsetAtStart}). Exiting.");
                        return 0;
                    }

                    break;

                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    // We hit end-of-partition (at the time we consumed). Exit.
                    $this->line("Partition EOF reached. Exiting.");
                    return 0;

                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    $this->line("Timed out waiting for messages. Exiting.");
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
}
