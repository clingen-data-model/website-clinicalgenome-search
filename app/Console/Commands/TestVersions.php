<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Gene;
use App\Activity;

class TestVersions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:Versions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the latest version file from Tristan';

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
        echo "Updating Activity from test file ...";

		try {

            $handle = fopen(base_path() . '/data/curation-events-sample.ndjson', "r");


		} catch (\Exception $e) {

			echo "\n(E001) Error retrieving search data\n";
			return 0;

		}

        while (($line = fgets($handle)) !== false)
        {
            $object = (object) ['payload' => $line];
            Activity::parser($object);
        }

		fclose($handle);

        echo "DONE\n";

    }
}
