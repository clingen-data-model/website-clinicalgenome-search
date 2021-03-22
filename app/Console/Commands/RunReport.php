<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

use App\Gene;
use App\GeneLib;
use App\Morbid;

class RunReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Erins omim report';

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
        //echo "Running Erin Report ...";
        
        // first check and update genes table

        $results = GeneLib::validityList([	'page' =>  0,
										'pagesize' => "null",
										'sort' => 'GENE_LABEL',
                                        'direction' => 'ASC',
                                        'search' => null,
                                        'forcegg' => true,
                                        'curated' => true ]);
                                        
        //TODO:  this will likely hang on a refresh, need to time out
        if ($results === null)
        {
            echo "(E001) Genegraph failed\n";
            exit;
        }

        // remove redundat genes
        $collection = $results->collection->unique(function ($item) {
            return $item->gene->label;
        });


        echo "Gene\tHGNCID\tValidity\tPhenotype\tPheno Omim\tDisputing\tNonDisease\tMutations\tMap Key\n";

        foreach ($collection as $assertion)
        {
            switch ($assertion->classification->label)
            {
                case 'limited evidence':
                case 'disputing':
                case 'refuting evidence':
                    // look up the gene in morbid file
                    $records = Morbid::whereJsonContains('genes', $assertion->gene->label)->get();
                    if ($records->isEmpty())
                        break;
                    foreach ($records as $record)
                    {
                        echo $assertion->gene->label . "\t"
                            . $assertion->gene->hgnc_id . "\t"
                            . $assertion->classification->label . "\t"
                            . $record->phenotype . "\t"
                            . $record->pheno_omim . "\t"
                            . $record->disputing . "\t"
                            . $record->nondisease . "\t"
                            . $record->mutations . "\t"
                            . $record->mapkey . "\n";
                    }
                default: 
                    break;
            }
        }

        //echo "DONE\n";

    }
}
