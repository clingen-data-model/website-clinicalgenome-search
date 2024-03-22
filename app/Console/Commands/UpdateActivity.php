<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

use App\Gene;
use App\GeneLib;
use App\Term;

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
            if ($gene->label == 'A12M1')
                dd($gene);

            $record = Gene::hgnc($gene->hgnc_id)->first();

            if ($record === null)
            {
                echo "\n(W001) WARN: Gene " . $gene->symbol . "not in local table\n";
                continue;
            }

            if ($record->activity == null)
                        $record->activity = ['pharma' => false, 'varpath' => false, 'dosage' => false, 'actionability' => false, 'validity' => false];

            // deal with any new genes that might not have an activity object yest
            if ($record->activity !== null)
                $record->update(['date_last_curated' => $gene->last_curated_date,
                                'genegraph' => ['present' => true, 'updated' => Carbon::now()]]);

            $activity = $record->activity;
            $activity['actionability'] = $gene->has_actionability;
            $activity['validity'] = $gene->has_validity;
            $activity['dosage'] = $gene->has_dosage;
            $record->activity = $activity;
            $record->date_last_curated = $gene->last_curated_date;
            $record->genegraph = ['present' => true, 'updated' => Carbon::now()];
            $record->save();

            // update search terms
            $terms = Term::where('value',$record->hgnc_id)->get();

            foreach ($terms as $term)
            {
                if ($term->alias === null)
                    $term->update(['curated' => 1, 'weight' => 2]);     // one for parent, one for curated
                else
                    $term->update(['curated' => 1, 'weight' => 1]);     // one for curated
            }
        }

        echo "DONE\n";

    }
}
