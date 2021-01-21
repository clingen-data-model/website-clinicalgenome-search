<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        echo "Importing omim titles ...\n";
        
        // https://data.omim.org/downloads/gnEYXJE_RtCzjSCNEOWFHg/mimTitles.txt

		$handle = fopen(base_path() . '/data/mimTitles.txt', "r");
        if ($handle)
        {
            while (($line = fgets($handle)) !== false)
            {
                // process the line read.
                echo "Processing " . $line . "\n";

                $value = explode("\t", $line);

                if (strpos($value[0], '#') === 0)
                    continue;

                $issue = Omim::updateOrCreate(['omimid' => trim($value[1])], 
                                ['prefix' => trim($value[0]),
                                'titles' => trim($value[2]),
                                'alt_titles' => trim($value[3]),
                                'inc_titles' => trim($value[4]),
                                'status' => 1,
                                'type' => 1
                                ] );
            }

            fclose($handle);
        }
        else
        {
            echo "Cannot access IDX file\n";
        }
    }
}
