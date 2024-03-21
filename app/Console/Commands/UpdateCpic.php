<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Maatwebsite\Excel\Facades\Excel as Gexcel;

use App\Imports\Excel;
use App\Imports\ExcelGKB;

use App\Cpic;
use App\GeneLib;
use App\Gene;
use App\Drug;

class UpdateCpic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:cpic';

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
    
        echo "Updating CPIC data from CPIC ...";

        try {
                    
			$results = file_get_contents("https://api.cpicpgx.org/v1/pair_view?order=genesymbol,provisional,guidelineurl,cpiclevel,drugname&select=*,gene(hgncid),drug(rxnormid)");

		} catch (\Exception $e) {
		
			echo "\n(E001) Error retreiving CPIC data\n";
			exit;
		}

        $dd = json_decode($results);
      
        Cpic::query()->forceDelete();

        // Type 1 is cpic row
        foreach($dd as $row)
        {
            $record = new Cpic([ 'gene' => $row->genesymbol,
                                'hgnc_id' => $row->gene->hgncid,
                                'drug' => $row->drugname,
                                'rxnorm' => $row->drug->rxnormid,
                                'guideline' => $row->guidelineurl,
                                'cpic_level' => $row->cpiclevel,
                                'cpic_level_status' => $row->provisional ? "Provisional" : "Final",
                                'pharmgkb_level_of_evidence' => $row->pgkbcalevel,
                                'pa_id' => null,
                                'pa_id_drug' => null,
                                'is_vip' => $row->usedforrecommendation,
                                'has_va' => null,
                                'had_cpic_gudeline' => null,
                                'pgx_on_fda_label' => $row->pgxtesting,
                                'cpic_publications_pmid' => !empty($row->pmids) ? implode(':', $row->pmids) : null,
                                'notes' => $row->guidelinename,
                                'type' => 1,
                                'status' => 1

            ]);

            $record->save();

            // update main drug table with activity
            if ($record->rxnorm !== null)
            {
                $drug = Drug::curie($record->rxnorm)->first();
                if ($drug !== null)
                {
                    $activity = $drug->curation_activities;
                    $activity['pharma'] = true;
                    $drug->curation_activities = $activity;
                    $drug->save();
                }
            }
        }

        echo "DONE\n";

        echo "Updating PharmGKB data from PharmGKB ...";

        try {
                    
            $results = file_get_contents("https://api.pharmgkb.org/v1/collaborator/clingen/pair");

        } catch (\Exception $e) {
        
            echo "\n(E001) Error retreiving PharmKGB data\n";
            exit;
        }
    
        $dd = json_decode($results);
       
        // Type 2 is pharmGKB row
        foreach($dd->data as $row)
        {
            $record = new Cpic([ 'gene' => $row->gene->name,
                                'hgnc_id' => null,
                                'drug' => $row->drug->name,
                                'guideline' => $row->url,
                                'cpic_level' => null,
                                'cpic_level_status' => null,
                                'pharmgkb_level_of_evidence' => $row->level,
                                'pa_id' => $row->gene->id,
                                'pa_id_drug' => $row->drug->id,
                                'is_vip' => null,
                                'has_va' => null,
                                'had_cpic_gudeline' => null,
                                'pgx_on_fda_label' => $row->drug->url,
                                'cpic_publications_pmid' => $row->gene->url,
                                'notes' => $row->lastModified,
                                'type' => 2,
                                'status' => 1

            ]);

            $record->save();
        }
        
        // for entries where we don't yet have an hgnc_id, populate it
        $list = Cpic::whereNull('hgnc_id')->get();

        foreach ($list as $record)
        {
            $gene = Gene::name($record->gene)->first();

            if ($gene === null)
            {
                echo "Could not find an hgncid for " . $record->gene . "\n";
                continue;
            }

            $record->update(['hgnc_id' => $gene->hgnc_id]);
        }

        // update the main gene table
        $list = Cpic::select('hgnc_id')->distinct('hgnc_id')->get();
        
        foreach($list as $record)
        {
            $gene = Gene::hgnc($record->hgnc_id)->first();

            if ($gene === null)
                continue;

            if ($gene->activity == null)
                        $gene->activity = ['pharma' => false, 'varpath' => false, 'dosage' => false, 'actionability' => false, 'validity' => false];

            $activity = $gene->activity;
            $activity['pharma'] = true;
            $gene->activity = $activity;
            
            $gene->save();
        }

        echo "DONE\n";

    }
}
