<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Setting;

use App\Omim;

class UpdateOmim extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:omim';

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
        echo "Updating OMIM Titles from OMIM ...";

        $key = Setting::get('omim', false);

        if (!$key)
        {
            echo "\n(E002) Error retreiving Omim key\n";
            exit;
        }

        try {
            $results = file_get_contents("https://data.omim.org/downloads/" . $key . "/mimTitles.txt");

		} catch (\Exception $e) {

			echo "\n(E001) Error retreiving Omim Titles data\n";

		}

        $line = strtok($results, "\n");

        while ($line !== false)
        {
                // process the line read.
                //echo "Processing " . $line . "\n";

                $value = explode("\t", $line);

                if (strpos($value[0], '#') !== 0)
                {

                    $issue = Omim::updateOrCreate(['omimid' => trim($value[1])],
                                    ['prefix' => trim($value[0]),
                                    'titles' => trim($value[2]),
                                    'alt_titles' => trim($value[3]),
                                    'inc_titles' => trim($value[4]),
                                    'status' => 1,
                                    'type' => 1
                                    ] );
                }

            $line = strtok("\n");
        }

        echo "DONE\n";

    }
}
