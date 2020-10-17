<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Region;

class UpdateCytoBands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:cytobands';

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
            
        $handle = fopen(base_path() . '/data/hg19/cytoBand.txt', "r");
        if ($handle)
        {
            while (($line = fgets($handle)) !== false)
            {
                // process the line read.
                echo "Processing " . $line . "\n";

                $value = explode("\t", $line);

                if (strpos($value[0], 'chr') == 0)   // strip out the chr
                    $value[0] = substr($value[0], 3);

                $gene = Gene::location($value[3])->first();

                $issue = Location::updateOrCreate(['cytoband' => trim($value[3]), 'type' => 1],
                                                ['gene_id' => $gene->id ?? null,
                                                 'chromosome' => $value[0],
                                                 'start' => $value[1],
                                                 'stop' => $value[2],
                                                 'stain' => $value[4]
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

                // break out the location and clean it up
                $location = preg_split('/[:-]/', trim($value[0]), 3);

                $chr = strtoupper($location[0]);
                
                if (strpos($chr, 'CHR') == 0)   // strip out the chr
                    $chr = substr($chr, 3);

                $start = (isset($location[1]) ? str_replace(',', '', $location[1]) : null);
                $stop = (isset($location[2]) ? str_replace(',', '', $location[2]) : null);

                $issue = Region::updateOrCreate(['location' => trim($value[0]), 'type' => 2],
                                                ['issue' => trim($value[1]), 
                                                 'chr' => $chr,
                                                 'start' => $start,
                                                 'stop' => $stop,
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
