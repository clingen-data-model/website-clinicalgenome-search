<?php

namespace App\Console\Commands;

use App\Panel;
use App\Services\PanelExporter;
use Illuminate\Console\Command;

class ExportPanelsToProcessWire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'processwire:panels
        {panel_id?}
        {affiliate_id?}
        {--type=vcep}
        {--debug : Dump response instead of continuing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export panels to ProcessWire';

    public function handle()
    {
        $type  = $this->option('type');
        $debug = (bool) $this->option('debug');

        $query = Panel::query();

        if ($affiliateId = $this->argument('affiliate_id')) {
            $type = null;
            $query->where('affiliate_id', $affiliateId);
        }

        if ($panelId = $this->argument('panel_id')) {
            $type = null;
            $query->where('id', $panelId);
        }

        if ($type) {
            $query->where('affiliate_type', $type);
        }

        $panels = $query->get();

        foreach ($panels as $panel) {
            $this->info('Sending affiliation: ' . $panel->name);

            $exporter = new PanelExporter($panel);
            $result   = $exporter->pushToProcessWire();

            if ($debug) {
                dd([
                    'panel_id'   => $panel->id,
                    'panel_name' => $panel->name,
                    'result'     => $result,
                ]);

                return Command\::SUCCESS;
            }
        }

        return Command::SUCCESS;
    }
}
