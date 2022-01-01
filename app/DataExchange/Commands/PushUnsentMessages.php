<?php

namespace App\DataExchange\Commands;

use App\StreamMessage;
use Illuminate\Console\Command;
use App\DataExchange\Jobs\PushMessage;

class PushUnsentMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dx:push-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pushes all StreamMessages that do not have a sent_at date';

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
        $query = StreamMessage::unsent();
        
        $progress = $this->output->createProgressBar($query->count());

        $query->get()->each(function ($message) use ($progress) {
            PushMessage::dispatchNow($message);
            $progress->advance();
        });
        $progress->finish();
        echo "\n";
    }
}
