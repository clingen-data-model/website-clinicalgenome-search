<?php

namespace App\DataExchange\Commands;

use App\Curation;
use App\Gci\GciMessage;
use App\IncomingStreamMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use App\Contracts\GeneValidityCurationUpdateJob;
use App\Jobs\ReplayGciEventsForCuration;

class ReplayGciEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gci:replay {curation_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Replay gene_validity_events messages for a pre-curation';

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
     * @return mixed
     */
    public function handle()
    {
        $curations = collect();
        if ($this->argument('curation_id') == 'all') {
            $curations = Curation::whereNotNull('gdm_uuid')->get();
        } else {
            $curation = Curation::findOrFail((int)$this->argument('curation_id'));
            if (!$curation->gdm_uuid) {
                $this->error('Curation '.$curation->id.' is not linked to a GDM.');
                return 1;
            }
            $curations->push($curation);
        }

        $bar = $this->output->createProgressBar($curations->count());
        foreach ($curations as $curation) {
            Bus::dispatchNow(new ReplayGciEventsForCuration($curation));
            $bar->advance();
        }
        $bar->finish();
        echo "\n";
    }
}
