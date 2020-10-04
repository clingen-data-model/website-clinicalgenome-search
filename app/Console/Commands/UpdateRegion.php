<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Region;

class UpdateRegion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:region';

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
        echo "Reading idx file ...\n";
            
        $handle = fopen(base_path() . '/data/region.idx', "r");
        if ($handle)
        {
            while (($line = fgets($handle)) !== false)
            {
                // process the line read.
                echo "Processing " . $line . "\n";

                $value = explode("\t", $line);

                $issue = Region::updateOrCreate(['location' => trim($value[0]), 'type' => 1],
                                                ['issue' => trim($value[1]), 
                                                 'curation' => trim($value[2]),
                                                 'workflow' => trim($value[3]),
                                                 'name' => trim($value[4]),
                                                 'gain' => trim($value[5]),
                                                 'loss' => trim($value[6]),
                                                 'pli' => trim($value[7]),
                                                 'omim' => trim($value[8])
                                                ] );
            }

            fclose($handle);
        }
        else
        {
            echo "Cannot access IDX file\n";
        } 

        $handle = fopen(base_path() . '/data/region38.idx', "r");
        if ($handle)
        {
            while (($line = fgets($handle)) !== false)
            {
                // process the line read.
                echo "Processing " . $line . "\n";

                $value = explode("\t", $line);

                $issue = Region::updateOrCreate(['location' => trim($value[0]), 'type' => 2],
                                                ['issue' => trim($value[1]), 
                                                 'curation' => trim($value[2]),
                                                 'workflow' => trim($value[3]),
                                                 'name' => trim($value[4]),
                                                 'gain' => trim($value[5]),
                                                 'loss' => trim($value[6]),
                                                 'pli' => trim($value[7]),
                                                 'omim' => trim($value[8])
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
