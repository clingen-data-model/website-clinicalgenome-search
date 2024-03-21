<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

use App\Gene;
use App\GeneLib;
use App\Health;
use App\Validity;
use App\Actionability;
use App\Dosage;
use App\Region;
use App\Variantpath;

class RunCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if genegraph is responding';

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
        echo "RUNNING CHECK\n";
        echo "   BEGIN: " . `date`;

        echo "      Genegraph Updates...";

        // first check and update genes table

        $results = GeneLib::geneList([	'page' =>  0,
										'pagesize' => "null",
										'sort' => 'GENE_LABEL',
                                        'direction' => 'ASC',
                                        'search' => null,
                                        'forcegg' => true,
                                        'curated' => true ]);

        //TODO:  this will likely hang on a refresh, need to time out
        if ($results === null || $results->count < 1500)
        {
            echo "(E001) Genegraph failed\n";
            $stat = Health::where('service', 'GeneSearch')->update(['genegraph' => 0]);
            exit;
        }

        $query_string = serialize($results->collection);
        $hash = md5($query_string);

        //$health = Health::where('service', 'GeneSearch')->first();
        echo "Retrieved $results->count \n";
        //if ($health->genegraph != $hash)
        //{
            //update gene table
            echo "Updating Genes table \n";
            foreach ($results->collection as $record)
            {
                //echo "updating " . $record->label ." " . $record->hgnc_id . "\n";
                $gene = Gene::hgnc($record->hgnc_id)->first();

                if ($gene !== null)
                {
                    $gene->date_last_curated = $record->last_curated_date;

                    // new genes may need to prime the activity 
                    if ($gene->activity == null)
                        $gene->activity = ['pharma' => false, 'varpath' => false, 'dosage' => false, 'actionability' => false, 'validity' => false];

                    if ($record->curation_activities !== null)
                    {
                        $activity = $gene->activity;
                        $activity['dosage'] = in_array('GENE_DOSAGE',$record->curation_activities);
                        $activity['actionability'] = in_array('ACTIONABILITY',$record->curation_activities);
                        $activity['validity'] = in_array('GENE_VALIDITY',$record->curation_activities);
                        $gene->activity = $activity;
                    }
                    $gene->haplo = $record->dosage_curation->haploinsufficiency_assertion->dosage_classification->ordinal ?? null;
                    $gene->triplo = $record->dosage_curation->triplosensitivity_assertion->dosage_classification->ordinal ?? null;
                    $disease = $gene->disease;
                    if ($disease === null)
                        $disease = [];
                    $disease['loss_disease'] = $record->dosage_curation->haploinsufficiency_assertion->disease->label ?? null;
                    $disease['loss_omim'] = null;
                    $disease['loss_mondo'] = $record->dosage_curation->haploinsufficiency_assertion->disease->curie ?? null;
                    $disease['gain_disease'] = $record->dosage_curation->triplosensitivity_assertion->disease->label ?? null;
                    $disease['gain_omim'] = null;
                    $disease['gain_mondo'] = $record->dosage_curation->triplosensitivity_assertion->disease->curie ?? null;
                    $gene->disease = $disease;
                    $gene->save();
                }
                else
                {
                    echo "NOT FOUND: " . $record->label ." " . $record->hgnc_id . "\n";
                }
            }

            $stat = Health::where('service', 'GeneSearch')->update(['genegraph' => $hash]);

        //}

        echo "DONE\n";

       // echo "Genegraph OK ($hash)\n";

        echo "      Validity Updates...";
        // update validy table
        $model = new Validity();
        $model->preload();
        echo "DONE\n";

        echo "      Actionability Updates...";
        // update  actionability
        $model = new Actionability();
        //$model->preload();
        $this->call('query:kafka', ['topic' =>  'actionability']);
        echo "DONE\n";

        echo "      Dosage Gene Updates...";
        // update  dosage sensitivity
        $model = new Dosage();
        //$model->preload();
        $model->parser();
        echo "DONE\n";

        echo "      Dosage Region Updates...";
        // update  dosage sensitivity
        $model = new Region();
        //$model->preload();
        $model->parser();
        echo "DONE\n";

        //echo "Checking for variant changes...";
        // update  variant
        //$model = new Variantpath();
        //$model->assertions();
        //echo "DONE\n";*/

        echo "   END: " . `date`;
        echo "CHECK COMPLETE\n";
    }


    protected function init()
    {
        // purge regions from curations
        // Region::preload();
    }
}
