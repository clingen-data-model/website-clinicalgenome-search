<?php

namespace App\Http\Controllers;

use App\Panel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

/**
 * Preview / Rebuild buttons on /panels: rebuild one group from its latest GPM
 * checkpoint plus the general events since (gpm:rebuild-panel).
 */
class GpmRebuildController extends Controller
{
    /**
     * Dry run: show the checkpoint and the events that would be replayed.
     */
    public function preview(Panel $panel)
    {
        return $this->run($panel, true);
    }

    public function rebuild(Panel $panel)
    {
        return $this->run($panel, false);
    }

    protected function run(Panel $panel, bool $dryRun)
    {
        if (empty($panel->gpm_id)) {
            return redirect()->route('panels.index')
                ->with('rebuild_error', "{$panel->name} has no GPM uuid, so it cannot be rebuilt.");
        }

        // Scanning packets and pushing to ProcessWire can outlast the default
        // web request time limit.
        @set_time_limit(0);

        $options = ['group' => $panel->gpm_id];
        if ($dryRun) {
            $options['--dry-run'] = true;
        }

        try {
            $exitCode = Artisan::call('gpm:rebuild-panel', $options);
            $output = Artisan::output();
        } catch (\Throwable $e) {
            $exitCode = 1;
            $output = get_class($e) . ': ' . $e->getMessage();
        }

        return redirect()->route('panels.index')->with('rebuild_result', [
            'panel_id' => $panel->id,
            'name'     => $panel->name,
            'dry_run'  => $dryRun,
            'ok'       => $exitCode === 0,
            'output'   => $output,
        ]);
    }
}
