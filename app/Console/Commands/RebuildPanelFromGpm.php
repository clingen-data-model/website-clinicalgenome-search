<?php

namespace App\Console\Commands;

use App\Packet;
use App\Panel;
use App\Services\PanelIncrementalService;
use App\Stream;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

/**
 * Rebuild one panel / working group / CDWG from the packets already stored:
 * apply its latest checkpoint, then replay every gpm-general-event for that
 * group dated at or after the checkpoint, in offset order.
 *
 * Usage:
 *   php artisan gpm:rebuild-panel 6bae7a14-07d0-4523-ae75-eaf51369da5d --dry-run
 *   php artisan gpm:rebuild-panel 50118
 *   php artisan gpm:rebuild-panel 50118 --checkpoint-offset=1234
 *   php artisan gpm:rebuild-panel 50118 --since="2026-10-01"      (no checkpoint, replay only)
 */
class RebuildPanelFromGpm extends Command
{
    protected $signature = 'gpm:rebuild-panel
        {group : GPM group uuid, or the panel affiliate_id}
        {--checkpoint-offset= : Use this checkpoint packet offset instead of the latest one}
        {--since= : Skip the checkpoint and replay general events from this date}
        {--dry-run : List what would be applied without changing anything}
        {--no-push : Do not push the panel to ProcessWire at the end}';

    protected $description = 'Re-apply a group\'s latest GPM checkpoint, then replay its general events since then';

    public function handle(PanelIncrementalService $service)
    {
        $uuid = $this->resolveUuid($this->argument('group'));
        if (!$uuid) {
            $this->error('No panel found for "' . $this->argument('group') . '" (expected a GPM uuid or an affiliate_id).');
            return 1;
        }

        $generalTopic = $this->topicFor('gpm-general-events');
        if (!$generalTopic) {
            $this->error('Stream gpm-general-events not found.');
            return 1;
        }

        // ---- 1. checkpoint ----
        $checkpoint = null;
        $since = null;

        if ($sinceOpt = $this->option('since')) {
            $since = Carbon::parse($sinceOpt);
            $this->info("No checkpoint: replaying general events from {$since}.");
        } else {
            $checkpoint = $this->findCheckpoint($uuid);

            if (!$checkpoint) {
                $this->error("No checkpoint packet found for {$uuid}. Use --since=DATE to replay general events only.");
                return 1;
            }

            $since = $this->eventDate($checkpoint);
            $this->info(sprintf('Checkpoint: topic %s, offset %d, date %s',
                $checkpoint->topic, $checkpoint->offset, $since ?: 'unknown'));

            if (!$since) {
                $this->error('Checkpoint has no usable date; cannot tell which general events follow it.');
                return 1;
            }
        }

        // ---- 2. general events since then ----
        // Events at the same second as the checkpoint are included: replaying
        // something the snapshot already reflects is harmless, missing one isn't.
        $events = Packet::where('topic', $generalTopic)
            ->where('payload->data->group->uuid', $uuid)
            ->orderBy('offset')
            ->get()
            ->filter(function ($packet) use ($since) {
                $date = $this->eventDate($packet);
                return $date && $date->gte($since);
            })
            ->values();

        $this->info(count($events) . ' general event(s) to replay.');

        $rows = $events->map(function ($p) {
            return [$p->offset, data_get($p->payload, 'date'), data_get($p->payload, 'event_type')];
        })->all();

        if (count($rows)) {
            $this->table(['offset', 'date', 'event_type'], $rows);
        }

        if ($this->option('dry-run')) {
            $this->comment('Dry run: nothing applied.');
            return 0;
        }

        // ---- 3. apply ----
        $panel = null;

        if ($checkpoint) {
            $panel = $service->syncFromKafka($this->payloadArray($checkpoint)) ?: $panel;
        }

        foreach ($events as $packet) {
            $result = $service->syncFromKafka($this->payloadArray($packet));
            if ($result) {
                $panel = $result;
            } else {
                $this->warn("Offset {$packet->offset} (" . data_get($packet->payload, 'event_type') . ') was skipped by the parser.');
            }
        }

        if (!$panel) {
            $this->error('Nothing was applied.');
            return 1;
        }

        $panel = $panel->fresh();
        $this->info(sprintf('Rebuilt panel #%d "%s": status %s, %d member(s).',
            $panel->id, $panel->name, $panel->wg_status ?? '-', $panel->members()->count()));

        // ---- 4. push once, instead of after every event like Panel::parser ----
        if (!$this->option('no-push')) {
            Artisan::call('processwire:panels', ['panel_id' => $panel->id]);
            $this->info('Pushed to ProcessWire.');
        }

        return 0;
    }

    /**
     * Accept a GPM uuid directly, or look the panel up by affiliate_id.
     */
    protected function resolveUuid($group)
    {
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $group)) {
            return strtolower($group);
        }

        return optional(Panel::where('affiliate_id', $group)->whereNotNull('gpm_id')->first())->gpm_id;
    }

    /**
     * The latest checkpoint for this group. Checkpoints normally arrive on
     * gpm-checkpoint-events, but are looked for on gpm-general-events too.
     */
    protected function findCheckpoint($uuid)
    {
        $topics = array_values(array_filter([
            $this->topicFor('gpm-checkpoint-events'),
            $this->topicFor('gpm-general-events'),
        ]));

        $query = Packet::whereIn('topic', $topics)
            ->where('payload->event_type', 'group_checkpoint_event')
            ->where('payload->data->uuid', $uuid);

        if ($offset = $this->option('checkpoint-offset')) {
            return $query->where('offset', (int) $offset)->first();
        }

        // Offsets are per topic, so pick the latest by the event's own date.
        return $query->get()
            ->sortBy(function ($p) {
                $date = $this->eventDate($p);
                return $date ? $date->timestamp : 0;
            })
            ->last();
    }

    /**
     * The Kafka topic a stream reads (packets store the topic, not the stream name).
     */
    protected function topicFor($streamName)
    {
        return optional(Stream::name($streamName)->first())->topic;
    }

    protected function eventDate($packet)
    {
        $date = data_get($packet->payload, 'date');

        if (!$date) {
            return null;
        }

        try {
            return Carbon::parse($date);
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Packet casts payload to an object; the service expects nested arrays.
     */
    protected function payloadArray($packet)
    {
        return json_decode(json_encode($packet->payload), true);
    }
}
