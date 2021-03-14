<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

use App\Drug;
use App\GeneLib;

class UpdateRxnorm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:rxnorm';

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
        echo "Updating RXNORM data from NIH ...";

        
        $handle = fopen(base_path() . '/data/RXNORM.csv', "r");
        if ($handle)
        {
            // delete the old table
            //Drug::query()->forceDelete();

            // discard the header
            $line = fgets($handle);

            while (($line = fgets($handle)) !== false)
            {
                // process the line read.
                //echo "Processing " . $line . "\n";

                $value = explode(",", $line);

                //echo $value[38] . " " . $value[1] . "\n";

                //$issue = Iscamap::updateOrCreate(['symbol' => trim($value[0])], ['issue' => trim($value[1])] );
                $flags = ['actionability' => null,
                        'validity' => null,
                        'dosage' => null,
                        'pharma' => null,
                        'varpath' => null
                    ];

                $record = drug::updateOrCreate(['curie' => basename($value[0])], [
                                        'iri' => $value[0],
                                        'label' => $value[1],
                                        'curation_activities' => $flags,
                                        'type' => 1, 'status' => 1]);
            }

            fclose($handle);
        }
        else
        {
            echo "\n(E001) Cannot access gene_isca file\n";
            exit;
        } 

        echo "DONE\n";
    }
}
