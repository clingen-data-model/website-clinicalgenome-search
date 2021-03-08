<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

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
    protected $description = 'Updating GDA Activity from Genegraph';

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
        echo "Updating GDA Activity from Genegraph ...";
        
        $results = GeneLib::geneList([	'page' =>  0,
										'pagesize' => "null",
										'sort' => 'GENE_LABEL',
                                        'direction' => 'ASC',
                                        'search' => null,
                                        'forcegg' => true,
                                        'curated' => true ]);
                                        
        if ($results === null)
        {
            echo "\n(E001) Error fetching activity data.\n";
            exit;
        }

        // clear out the genegraph related fields
        Gene::query()->update(['genegraph' => null, 'activity' => null, 'date_last_curated' => null]);

        foreach($results->collection as $gene)
        {
            //echo "Updating  " . $gene->hgnc_id . "\n";

            $flags = ['actionability' => $gene->has_actionability,
                        'validity' => $gene->has_validity,
                        'dosage' => $gene->has_dosage,
                        'pharma' => false,
                        'varpath' => false,
                    ];

            $record = Gene::hgnc($gene->hgnc_id)->first();

            if ($record !== null)
                $record->update(['activity' => $flags, 'date_last_curated' => $gene->last_curated_date,
                                'genegraph' => ['present' => true, 'updated' => Carbon::now()]]);
            else
                echo "\n(W001) WARN: Gene " . $gene->symbol . "not in local table\n";
        }

        echo "DONE\n";

    }
}
