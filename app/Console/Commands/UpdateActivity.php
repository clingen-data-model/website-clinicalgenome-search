<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Gene;
use App\GeneLib;

class UpdateActivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:activity';

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
        echo "Importing curation activity from genegraph ...\n";
        
        $results = GeneLib::geneList([	'page' =>  0,
										'pagesize' => "null",
										'sort' => 'GENE_LABEL',
                                        'direction' => 'ASC',
                                        'search' => null,
                                        'curated' => true ]);
                                        
        if ($results === null)
            die( GeneLib::getError() );

        foreach($results->collection as $gene)
        {
            echo "Updating  " . $gene->hgnc_id . "\n";

            $flags = ['actionability' => $gene->has_actionability,
                        'validity' => $gene->has_validity,
                        'dosage' => $gene->has_dosage
                    ];

            $record = Gene::hgnc($gene->hgnc_id)->first();

            if ($record !== null)
                $record->update(['activity' => $flags, 'date_last_curated' => $gene->last_curated_date]);
        }

    }
}
