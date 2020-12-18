<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

use App\Disease;
use App\GeneLib;

class UpdateDisease extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:disease';

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
        echo "Importing disease data from genegraph ...\n";
        
        $results = GeneLib::conditionList([	'page' =>  0,
										'pagesize' => "null",
										'sort' => 'GENE_LABEL',
                                        'direction' => 'ASC',
                                        'search' => null,
                                        'curated' => false ]);
                                        
        if ($results === null)
            die( GeneLib::getError() );

        foreach($results->collection as $disease)
        {
            echo "Updating  " . $disease->curie . "\n";

            $flags = ['actionability' => $disease->has_actionability,
                        'validity' => $disease->has_validity,
                        'dosage' => $disease->has_dosage
                    ];

            $record = Disease::updateOrCreate(['curie' => $disease->curie], [
                                        'label' => $disease->label,
                                        'description' => $disease->description,
                                        'synonyms' => $disease->synonyms,
                                        'last_curated_date' => $disease->last_curated_date,
                                        'curation_activities' => $flags,
                                        'type' => 1, 'status' => 1]);
        }

    }
}
