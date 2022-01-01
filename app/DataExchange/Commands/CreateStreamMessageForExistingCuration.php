<?php

namespace App\DataExchange\Commands;

use App\Curation;
use App\StreamMessage;
use Illuminate\Console\Command;
use App\Jobs\Curations\CreatePrecurationStreamMessage;

class CreateStreamMessageForExistingCuration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'curations:fill-stream';

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
     * @return mixed
     */
    public function handle()
    {
        $curations = Curation::all();
        $bar = $this->output->createProgressBar($curations->count());

        $curations->each(function ($curation) use ($bar) {
            \Bus::dispatch(new CreatePrecurationStreamMessage($curation, 'created'));
            $bar->advance();
        });
        echo "\n";
    }
}
