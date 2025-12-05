<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunGpmKafkaProcessors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gpm:run-kafka';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the GPM Kafka processors for general and person events.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Running GPM General Events Processor...');
        $this->call('process:kafka', ['topic' => 'gpm-general-events']);

        $this->info('Running GPM Person Events Processor...');
        $this->call('process:kafka', ['topic' => 'gpm-person-events']);

        $this->info('All GPM Kafka processors have been executed successfully.');

        return Command::SUCCESS;
    }
}

