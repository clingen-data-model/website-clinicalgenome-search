<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

use App\Gene;
use App\GeneLib;
use App\Panel;
use App\Variant;

class UpdateAffiliates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:affiliates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updating Affiliate associations';

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
        echo "Updating Affiliate Information from Genegraph ...";

        $results = GeneLib::affiliateList([	'page' =>  0,
										'pagesize' => "null",
										'sort' => 'GENE_LABEL',
                                        'direction' => 'ASC',
                                        'search' => null,
                                        'curated' => true ]);

        if ($results === null)
        {
            echo "\n(E001) Error fetching affiliate data.\n";
            exit;
        }

        foreach($results->collection as $affiliate)
        {

            // Update names and desciptions, or add new panels
            $panel = Panel::updateOrCreate(['curie' => $affiliate->curie],
                                    ['description' => $affiliate->description ?? null,
                                     'name' => $affiliate->label,
                                     'type' => Panel::TYPE_GCEP ]);
        }

        // TODO:  see if we can merge this with previous listing
        $results = GeneLib::validityList([	'page' =>  0,
                                'pagesize' =>  "null",
                                'sort' =>  'GENE_LABEL',
                                'search' =>  null,
                                'direction' => 'ASC',
                                'curated' => false
        ]);

        foreach($results->collection as $record)
        {
            $gene = Gene::hgnc($record->gene->hgnc_id)->first();

            if ($gene !== null)
            {
                $panel = Panel::curie($record->attributed_to->curie)->first();

                if ($panel !== null)
                {
                    $gene->panels()->syncWithoutDetaching([$panel->id]);
                }
            }
        }


        // as long as this update scritpt follows the erepo updats, we can capture vceps
        foreach (Variant::all() as $variant)
        {
            foreach ($variant->guidelines as $guideline)
            {

                // while we are, update the gene associations
                $eid = $variant->gene['NCBI_id'];

                $gene = Gene::where('entrez_id', $eid)->first();

                foreach ($guideline['agents'] as $agent)
                {
                    // Update names and desciptions, or add new panels
                    $panel = Panel::updateOrCreate(['curie' => $agent['@id']],
                                        ['description' => $agent['affiliation'] ?? null,
                                        'name' => $agent['label'],
                                        'type' => Panel::TYPE_VCEP ]);

                    if ($gene !== null)
                    {
                        $gene->panels()->syncWithoutDetaching([$panel->id]);

                        $gene->update(['vcep' => $panel->href]);
                    }
                }

            }
        }

        // what about WGs like Dosage?

        echo "DONE\n";

    }
}
