<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Acmg59;

class UpdateAcmg59 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:acmg59';

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
        echo "Reading ACMG curation file ...\n";
            
        $handle = fopen(base_path() . '/data/acmg56_curation.ini', "r");
        if ($handle)
        {
            while (($line = fgets($handle)) !== false)
            {
                // process the line read.
               // echo "Processing " . $line . "\n";

                $value = explode("\t", $line);

                // the ini file has a funky structure that inherits certain fields
                if (!empty(trim($value[0])))
                    $pheno = trim($value[0]);
                
                if (!empty(trim($value[1])))
                    $omims = trim($value[1]);

                if (!empty(trim($value[2])))
                    $pmids = trim($value[2]);

                if (!empty(trim($value[3])))
                    $age = trim($value[3]);


                $subsect = 4;           // first field of gene loop

                //while (isset($value[$subsect]))
                //{
                    $issue = Acmg59::updateOrCreate(['pheno' => $pheno, 'omims' => $omims, 
                                                 'pmids' => $pmids, 'age' => $age,
                                                 'gene' => trim($value[$subsect])], [
                                                 'omimgene' => trim($value[$subsect + 1]),
                                                 'gain' => trim($value[$subsect + 2]),
                                                 'loss' => trim($value[$subsect + 3]),
                                                 'type' => 1,
                                                 'status' => 1
                                                ] );

                    //$subsect += 4;  
                //}
            }

            fclose($handle);
        }
        else
        {
            echo "(E001) Cannot access ACMG curation file\n";
            exit;
        } 

        echo "ACMG curation update complete\n";

    }
}
