<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

use App\Drug;
use App\GeneLib;

class UpdateDrug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:drug';

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
        echo "Importing drug data from genegraph ...\n";
        
        $results = GeneLib::drugList([	'page' =>  0,
										'pagesize' => "null",
										'sort' => 'GENE_LABEL',
                                        'direction' => 'ASC',
                                        'search' => null,
                                        'curated' => false ]);
                                        
        if ($results === null)
            die( GeneLib::getError() );

        foreach($results->collection as $drug)
        {
            echo "Updating  " . $drug->curie . "\n";

            $flags = ['actionability' => $drug->has_actionability,
                        'validity' => $drug->has_validity,
                        'dosage' => $drug->has_dosage
                    ];

            $record = drug::updateOrCreate(['curie' => $drug->curie], [
                                        'label' => $drug->label,
                                        'last_curated_date' => $drug->last_curated_date,
                                        'curation_activities' => $flags,
                                        'type' => 1, 'status' => 1]);
        }

    }
}
