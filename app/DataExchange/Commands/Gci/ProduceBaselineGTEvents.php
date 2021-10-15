<?php

namespace App\DataExchange\Commands\Gci;

use App\Curation;
use Ramsey\Uuid\Uuid;
use App\StreamMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;
use App\Jobs\Curations\CreateStreamMessage;
use Illuminate\Database\Eloquent\Collection;

class ProduceBaselineGTEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gci:produce-baseline {--limit= : number of messags to produce} {--topic=test} {--print : print the ouput} {--truncate : truncate the stream_messages table before}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Produce events that provide baseline state of pre-curations for gci';

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
        $this->truncateStreamMessages();

        $curations = $this->getCurations();

        $this->info('Create messages...');
        $bar = $this->output->createProgressBar($curations->count());
        $curations->map(function ($curation) use ($bar) {
            $bar->advance();
            if (!$curation->currentStatus) {
                return;
            }

            Bus::dispatchNow(
                new CreateStreamMessage(
                    config('dx.topics.outgoing.gt-gci-sync'), 
                    $curation, 
                    'precuration_completed'
                )
            );
        });
    }

    private function truncateStreamMessages()
    {
        if ($this->option('truncate')) {
            $this->info('Truncating stream_messages table...');
            DB::table('stream_messages')->truncate();
        }
    }
    

    private function getCurations(): Collection
    {
        $this->info('Getting curations...');
        $query = Curation::query()
                    ->with(['currentStatus', 'expertPanel', 'expertPanel.affiliation', 'expertPanel.affiliation.parent', 'modeOfInheritance', 'curationType', 'phenotypes']);

        if ($this->getLimit()) {
            $query->limit($this->getLimit());
        }

        $query->whereNotNull('mondo_id')
            ->whereNotNull('moi_id')
            ->whereIn('curation_status_id', [4,5,6,7,9]);

        return $query->get();
    }
    

    private function getLimit()
    {
        if ($this->hasOption('limit')) {
            return $this->option('limit');
        }

        return null;
    }
}
