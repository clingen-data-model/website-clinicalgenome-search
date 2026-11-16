<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Stream;
use App\Packet;
use App\Panel;

class QueryGPM extends Command
{
    /**
     * Usage examples:
     *  php artisan query:gpm gpm-general-events --assign
     *  php artisan query:gpm gpm-general-events --assign --from-offset=100
     *  php artisan query:gpm gpm-general-events --assign --partition=0
     *
     *  Replay a single offset, or a range (both inclusive):
     *  php artisan query:gpm gpm-general-events --assign --range-offset=23026
     *  php artisan query:gpm gpm-general-events --assign --range-offset=23026-23027
     *  php artisan query:gpm gpm-general-events --assign --from-offset=23026 --to-offset=23027
     *
     *  A bounded run is a replay: it does NOT move streams.offset, so re-reading
     *  old messages cannot rewind the live consumer position.
     *
     *  Rebuild ONE panel / working group / CDWG: read from an offset and ignore
     *  every message that is not about that affiliation_id:
     *  php artisan query:gpm gpm-general-events --from-offset=23026 --affiliation-id=50118
     *  php artisan query:gpm gpm-general-events --from-offset=23026 --affiliation-id=50118 --dry-run
     *
     *  An --affiliation-id run never moves streams.offset either (it skipped
     *  everyone else's messages, so the live position must not jump past them).
     */
    protected $signature = 'query:gpm
        {topic}
        {--assign : Use manual partition assignment (no consumer-group commits)}
        {--from-offset= : Absolute offset to start from (overrides DB offset)}
        {--to-offset= : Last offset to process, inclusive. Makes this a replay: the stored offset is left alone}
        {--range-offset= : Shorthand for --from-offset/--to-offset, as "A-B" or a single "A"}
        {--partition= : Partition to read in assign mode (defaults to 0)}
        {--affiliation-id= : Only process messages for the panel / group with this affiliation_id (e.g. 50118). All others are ignored}
        {--dry-run : With --affiliation-id, list the matching messages without saving or parsing them}';

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
        $toOffsetOpt   = $this->option('to-offset');

        // --range-offset=A-B (or a single A) is shorthand for the two above.
        $rangeOpt = $this->option('range-offset');
        if ($rangeOpt !== null && trim($rangeOpt) !== '') {
            if (!preg_match('/^\s*(\d+)\s*(?:[-:]\s*(\d+)\s*)?$/', $rangeOpt, $m)) {
                $this->error("Invalid --range-offset='{$rangeOpt}'. Expected \"A\" or \"A-B\" (e.g. 23026 or 23026-23027).");
                return 1;
            }
            $fromOffsetOpt = $m[1];
            $toOffsetOpt   = (isset($m[2]) && $m[2] !== '') ? $m[2] : $m[1];
        }

        $startOffset = ($fromOffsetOpt !== null) ? (int) $fromOffsetOpt : $dbOffset;

        // A bounded run replays a known window. Persisting streams.offset here
        // would rewind the live position to the end of the window, causing
        // everything after it to be re-consumed on the next normal run.
        $stopAtOffset = ($toOffsetOpt !== null && trim((string) $toOffsetOpt) !== '')
            ? (int) $toOffsetOpt
            : null;
        $isReplay = ($stopAtOffset !== null);

        // --affiliation-id: process only that panel's / group's messages.
        // Matched on the message's own affiliation_id (and the panel's gpm uuid,
        // when known), so it works even for a group not in the panels table yet.
        $groupFilter = $this->resolveGroupFilter((string) $this->option('affiliation-id'));
        $groupUuid = $groupFilter ? $groupFilter['label'] : null;

        $dryRun = (bool) $this->option('dry-run');
        if ($dryRun && !$groupUuid) {
            $this->error('--dry-run is only supported together with --affiliation-id.');
            return 1;
        }

        // streams.offset is the live consumer position. Only a normal, unfiltered,
        // unbounded run may move it.
        $persistOffset = !$isReplay && !$groupUuid;
        $matched = 0;
        $skipped = 0;

        if ($isReplay && $stopAtOffset < $startOffset) {
            $this->error("--to-offset={$stopAtOffset} is before --from-offset={$startOffset}.");
            return 1;
        }

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
            if ($persistOffset) {
                // Still set DB offset so next run doesn’t keep trying the same invalid value
                $stream->offset = $high; // next offset boundary
                $stream->save();
            }
            return 0;
        }

        $this->line("MODE: assign()");
        $this->line("Endpoint: {$stream->endpoint}");
        $this->line("Topic: {$stream->topic}");
        $this->line("Partition: {$partition}");
        $this->line("Watermarks: low={$low}, high={$high} (last message offset at start={$lastOffsetAtStart})");
        $this->line("Starting from offset: {$startOffset}");
        if ($isReplay) {
            $this->line("Stopping after offset: {$stopAtOffset}  (REPLAY -- streams.offset will not be updated)");
        }
        if ($groupUuid) {
            $this->line("Filter: {$groupUuid}  (all other groups ignored -- streams.offset will not be updated)" . ($dryRun ? '  DRY RUN' : ''));
        }

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
                $this->groupSummary($groupUuid, $matched, $skipped, $dryRun);
                return 0;
            }

            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                case 0:
                    // --affiliation-id: ignore every message that is not about this group.
                    // Nothing is saved or parsed for them.
                    $process = true;
                    if ($groupUuid) {
                        $decoded = json_decode($message->payload, true);
                        $process = (is_array($decoded) && $this->messageMatchesGroup($decoded, $groupFilter));

                        if ($process) {
                            $matched++;
                            $this->line(sprintf('  match  offset %d  %s  %s',
                                $message->offset, data_get($decoded, 'date', '-'), data_get($decoded, 'event_type', '-')));
                        } else {
                            $skipped++;
                        }

                        if ($dryRun) {
                            $process = false;
                        }
                    }

                    if ($process) {
                        // Persist packet.
                        //
                        // On a replay the row already exists from the original run, so
                        // reuse it instead of inserting a duplicate. It is reused
                        // rather than skipped because some parsers are handed $packet
                        // (see the dosage/actionability branch below) and would break
                        // on a null.
                        //
                        // If a replayed offset has no row -- it was never consumed --
                        // inserting is correct, since that is not a duplicate.
                        $packet = null;

                        if ($isReplay || $groupUuid) {
                            $packet = Packet::where('topic', $stream->topic)
                                ->where('offset', (int) $message->offset)
                                ->first();
                        }

                        if (!$packet) {
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
                        }

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
                    }

                    // Update DB offset so next run continues after this message.
                    // Skipped on a replay or an --affiliation-id run -- see $persistOffset.
                    if ($persistOffset) {
                        $stream->offset = (int) $message->offset + 1;
                        $stream->save();
                    }

                    // Stop at the requested bound, or at the last message that
                    // existed when this run started, whichever comes first.
                    $stopHere = $isReplay
                        ? min($stopAtOffset, (int) $lastOffsetAtStart)
                        : (int) $lastOffsetAtStart;

                    if ((int) $message->offset >= $stopHere) {
                        $this->line("Reached offset {$stopHere}. Exiting.");
                        $this->groupSummary($groupUuid, $matched, $skipped, $dryRun);
                        return 0;
                    }

                    break;

                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    // We hit end-of-partition (at the time we consumed). Exit.
                    $this->line("Partition EOF reached. Exiting.");
                    $this->groupSummary($groupUuid, $matched, $skipped, $dryRun);
                    return 0;

                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    $this->line("Timed out waiting for messages. Exiting.");
                    $this->groupSummary($groupUuid, $matched, $skipped, $dryRun);
                    return 0;

                default:
                    throw new \Exception($message->errstr(), $message->err);
            }
        }
    }

    /**
     * Build the --affiliation-id filter: the affiliation_id itself, plus the
     * panel's gpm uuid if we have one (some messages may only carry the uuid).
     */
    private function resolveGroupFilter(string $affiliationId): ?array
    {
        $affiliationId = trim($affiliationId);
        if ($affiliationId === '') {
            return null;
        }

        $gpmId = optional(Panel::where('affiliate_id', $affiliationId)->whereNotNull('gpm_id')->first())->gpm_id;

        return [
            'uuid'           => $gpmId ? strtolower($gpmId) : null,
            'affiliation_id' => $affiliationId,
            'label'          => "affiliation_id {$affiliationId}" . ($gpmId ? " (gpm {$gpmId})" : ''),
        ];
    }

    /**
     * Is this GPM message about the filtered group? General events carry the
     * group under data.group; checkpoints carry it directly under data.
     */
    private function messageMatchesGroup(array $payload, array $filter): bool
    {
        $group = data_get($payload, 'data.group');
        if (!is_array($group) && data_get($payload, 'event_type') === 'group_checkpoint_event') {
            $group = data_get($payload, 'data');
        }
        if (!is_array($group)) {
            return false;
        }

        if ($filter['uuid'] && strtolower((string) data_get($group, 'uuid')) === $filter['uuid']) {
            return true;
        }

        if ($filter['affiliation_id'] !== null) {
            $affiliationId = data_get($group, 'affiliation_id') ?? data_get($group, 'expert_panel.affiliation_id');
            if ((string) $affiliationId === $filter['affiliation_id']) {
                return true;
            }
        }

        return false;
    }

    private function groupSummary(?string $groupUuid, int $matched, int $skipped, bool $dryRun): void
    {
        if ($groupUuid) {
            $this->line(sprintf('%s: %d message(s) %s, %d other message(s) ignored.',
                $groupUuid, $matched, $dryRun ? 'matched (dry run, nothing applied)' : 'processed', $skipped));
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
