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
    protected $signature = 'processwire:panels {panel_id?} {--type=vcep}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $type = $this->option('type');
        $query = Panel::query();
        if ($panelId = $this->argument('panel_id')) {
            $type = null;
            $query->where('id', $panelId);
        }

        if ($type) {
            $query->where('affiliate_type', $type);
        }

        $panels = $query->get();

        foreach ($panels as $panel) {
            $this->info('sending affliation id ' . $panel->name);
            $exporter = new PanelExporter($panel);
            $result = $exporter->pushToProcessWire();
        }
    }
}
