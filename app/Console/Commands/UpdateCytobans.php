<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Location;
use App\Gene;

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

                $gene = Gene::cytoband($value[3])->first();

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

    }
}
