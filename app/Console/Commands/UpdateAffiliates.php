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

            // map the CGAGENT to a real number
            $id = (strpos($affiliate->curie, 'CGAGENT:') === 0 ? substr($affiliate->curie, 8) : $affiliate->curie);
            $alt_id = null;

            if (intval($id) > 10000 && intval($id) < 19999)
            {
                $alt_id = $id;
                $id = intval($id) + 30000;
            }

            // genegraph is not authorative for affliate information, but include any new ones encountered
            $panel = Panel::firstOrNew(['alternate_id' => $alt_id],
                                    ['summary' => $affiliate->description ?? null,
                                     'name' => $affiliate->label,
                                     'affiliate_id' => $id,
                                     'title' => "",
                                     'title_short' => $affiliate->label,
                                     'affiliate_type' => 'gcep',
                                     'type' => Panel::TYPE_GCEP ]);

            if (!isset($panel->id) || $panel->id < 1)
                $panel->save();
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
                $pid = Panel::gg_map_to_panel($record->attributed_to->curie);

                $panel = Panel::affiliate($pid)->first();

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
                if (isset($variant->gene['NCBI_id']))
                {
                    $eid = $variant->gene['NCBI_id'];

                    $gene = Gene::where('entrez_id', $eid)->first();
                }
                else
                {
                    $gene = Gene::name($variant->gene['label'])->first();
                }

                // TODO:  lot of repetition here - caching the lookups will speed things up.
                foreach ($guideline['agents'] as $agent)
                {
                    // erepo is not authorative, but deal with new ones
                    $id = Panel::erepo_map_to_panel($agent['@id']);


                    $panel = Panel::firstOrNew(['affiliate_id' => $id],
                                    ['summary' => $agent['affiliation'] ?? null,
                                     'name' => $agent['label'],
                                     'alternate_id' => null,
                                     'title' => $agent['label'],
                                     'title_short' => $agent['label'],
                                     'affiliate_type' => 'vcep',
                                     'type' => Panel::TYPE_VCEP ]);

                    if (!isset($panel->id) || $panel->id < 1)
                        $panel->save();


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
