<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\Issue\Comment;
use JiraRestApi\Issue\IssueField;
use JiraRestApi\User\UserService;
use JiraRestApi\JiraException;

use DB;
use Mail;

use Carbon\Carbon;

use App\Gene;
use App\Disease;
use App\Curation;
use App\GeneLib;
use App\Panel;
use App\Validity;
use App\Nodal;

class RunBlof extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:blof {report=none}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Erins BLOF report for dosage';

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
        $report = $this->argument('report');

        switch ($report)
        {
            
            default:
                echo "Running BLOF Report\n";
                $this->blof_spreadsheet();
                //echo "Nothing to do, exiting\n";
                break;
        }
    }

    public function blof_spreadsheet()
    {
        // initialize results
        $results = [];

        // read in blof spreadsheet.
        //$handle = fopen(base_path() . '/data/Established_Biallelic_LOF.csv', "r");
        $handle = fopen(base_path() . '/data/Biallelic_LOF_Genes_with_GenCC_Established_Score.csv', "r");

        if ($handle)
        {
            // read the header
            $line = fgetcsv($handle);

            // read the data
            while (($line = fgetcsv($handle)) !== false)
            {

                $this->info("Processing " . $line[1] . "...");
               
                // first get the gene
                $gene = Gene::where('name', $line[1])->first();

                if ($gene === null)
                {
                    // check previous sympols and aliases
                    $gene = Gene::previous($line[1])->first();

                    if ($gene === null)
                        $gene = Gene::alias($line[1])->first();
                        
                }

                if ($gene === null)
                {
                    echo "GENE " . $line[1] . " NOT FOUND...SKIPPING\n";
                    continue;
                }

                // now get the disease
                $disease = Disease::where('label', $line[2])->where('status', 1)->first();

                if ($disease === null)
                {
                    echo "DISEASE " . $line[2] . " NOT FOUND...SKIPPING\n";
                    continue;
                }

                $this->info("Processing " . $line[1] . ',' . $line[2] . "...");

                // see if there is a validity curation matching gene and disease
                $curation = Curation::type(Curation::TYPE_GENE_VALIDITY)->where('gene_id', $gene->id)->where('disease_id', $disease->id)->active()->first();
                if ($curation === null)
                {
                    echo "CURATION " . $line[1] . ", ". $line[2] . " NOT FOUND...SKIPPING\n";
                    continue;
                }

                // determine number of alternate splices
                if ($line[7] == 'N/A')
                    $splices = 0;
                else
                {
                    $splices = 0;
                    $str = preg_replace('/[^0-9\-,]/', '', $line[7]);
                    $str_array = explode(',', $str);
                    foreach ($str_array as $item)
                    {
                        $count = 1;
                        if (strpos($item, '-') !== false)
                        {
                            $ranges = explode('-', $item);
                            $sums = range($ranges[0], $ranges[1]);
                            $item = array_sum($sums);
                            $count = count($sums);
                        }
                        $splices += $count;
                    }
                }


                // query genegraph for the curation details
                $source = $curation->source_uuid;

                $summary = GeneLib::validityDetail([
                    'page' => 0,
                    'pagesize' => 20,
                    'perm' => $source
                ]);

                $record =  GeneLib::newValidityDetail([
                    'page' => 0,
                    'pagesize' => 20,
                    'perm' => $source
                ]);

                if ($record !== null) {

                    $segregation = [];
                    $casecontrol = [];
                    $caselevel = [];
                    $nonscorable = [];
                    $pmids = [];
                    $propoints = [];
                    $temp = [];
                    $ep = null;
                    $apmids = [];

                    foreach ($record->contributions as $contributor)
                        if ($contributor->realizes->curie == "CG:PublisherRole")
                            $ep = $contributor->attributed_to;

                    $genev = collect($record->genetic_evidence);
                    //dd($extrecord);
                    $genev->each(function ($item) use (&$temp, &$segregation, &$casecontrol, &$caselevel, &$propoints, &$pmids, &$clfs, &$clfswopb) {
                        if ($item->type[0]->curie == "SEPIO:0004012"  && !empty($item->evidence)) {
                            foreach ($item->evidence as $e) {
                                if (!isset($e->proband))
                                    continue;
                                if ($e->proband !== null) // && $e->proband->label !== null && ($e->estimated_lod_score !== null || $e->published_lod_score !== null))
                                    $clfs = true;
                                else if ($e->proband === null) // || $e->proband->label === null || ($e->estimated_lod_score === null && $e->published_lod_score === null))
                                    $clfswopb = true;
                            }
        
                            $segregation[] = $item;
                        } else if ($item->type[0]->curie == "SEPIO:0004021" || $item->type[0]->curie == "SEPIO:0004020")
                            $casecontrol[] = $item;
                        else if ($item->type[0]->curie == "SEPIO:0004174"
                        ) // the separate proband counted points records
                        {
                            // This thing is a hot mess and totally unusable as is.  Only choice it to completely restructure.
                            // First, seperate out the two statement records from the proband
        
                            $statements = [];
                            $proband = null;
                            $n = 0;
        
                            foreach ($item->evidence as $evidence) {
                                if ($evidence->__typename == "Statement") {
                                    $variant = new Nodal([
                                        'description' => $evidence->description ?? '',
                                        'type' => $evidence->type ?? '',
                                        'score_status' => $evidence->score_status->label ?? '',
                                        'score' => $evidence->score ?? '',
                                        'calculated_score' => $evidence->calculated_score ?? '',
                                        'proband_counted_score' => $item->score ?? '',
                                    ]);
        
                                    foreach ($evidence->nested_variant as $nest) {
                                        if ($nest->__typename == "VariantEvidence") {
                                            $variant->variant = $nest;
                                        } else if ($nest->__typename == "GenericResource") {
                                            $variant->function = $nest;
                                        }
                                    }
        
                                    $variants[] = $variant;
                                }
                            }
        
                            if (isset($variants)) {
                                $item->altvariants = $variants;
                                $caselevel[] = $item;
                            }
                        } else
                            $caselevel[] = $item;

                        if (!empty($item->evidence))
                            foreach ($item->evidence as $evidence)
                                if ($evidence->source !== null)
                                    $pmids[$evidence->source->curie] = $evidence->source;
                    });
        
                    $nosev = collect($record->direct_evidence);
        
                    $nosev->each(function ($item) use (&$nonscorable, &$pmids) {
                        if ($item->type[0]->curie == "SEPIO:0004127")
                            $nonscorable[] = $item;
        
                        if (!empty($item->evidence))
                            foreach ($item->evidence as $evidence)
                                if ($evidence->source !== null)
                                    $pmids[$evidence->source->curie] = $evidence->source;
                    });
        
                    $expev = collect($record->experimental_evidence);
        
                    $expev->each(function ($item) use (&$pmids) {
                        if (!empty($item->evidence))
                            foreach ($item->evidence as $evidence)
                                if ($evidence->source !== null)
                                    $pmids[$evidence->source->curie] = $evidence->source;
                    });
        
                    // build a quick list of the eariest earliest_articles
                    $eas = [];
                    if (isset($extrecord->earliest_articles)) {
                        foreach ($extrecord->earliest_articles as $article)
                            $eas[] = $article->curie;
                    }
        
                    $record->segregation = $segregation;
                    $record->casecontrol = $casecontrol;
                    $record->caselevel = $caselevel;
                    $record->nonscorable = $nonscorable;
                    $record->eas = $eas;
        
                    // sort the pmid array by numerical pmid value
                    $sortedpmids = [];
                    foreach ($pmids as $key => $value) {
                        $t = substr($key, 5);
                        $sortedpmids[intval($t)] = $value;
                    }
                    ksort($sortedpmids);
                    $record->pmids = $sortedpmids;
                }

                //if ($gene->name == "ABCG5")
                                       //dd($record->caselevel);

                foreach($record->caselevel as $key => $subrecord)
                {
                        $evidence = null;
                        $function = null;
                        $nodenovo = false;
                        $dual = false;
                        foreach ($subrecord->evidence as $ev)
                        {
                            if ($ev->__typename == "VariantEvidence")
                            {
                                $evidence = $ev;
                            }
                            else if ($ev->__typename == 'ProbandEvidence')
                            {
                                if ($subrecord->type[0]->curie == "SEPIO:0004174")
                                    $dual = true;

                                $evidence = $ev;

                                // ugly hack to account for different structures for essentially the same evidence model in genegraph
                                $evidence->proband = $ev;

                                $nodenovo = true;
                            }
                            else if ($ev->__typename == "GenericResource")
                            {
                                $function = $ev;
                            }
                        }
                        if ($evidence === null)
                        {
                            //echo "ABORTING NO EVIDENCE FOUND $gene->name \n";
                            continue;
                        }
                        if ($dual)
                        {
                            foreach($subrecord->altvariants as $v)
                            {

                                // ignore ones not scored
                                if ($v->score <= 0)
                                    continue;

                                if (($v->type[0]->curie ?? '') == 'SEPIO:0004017' ||
                                ($v->type[0]->curie ?? '') == 'SEPIO:0004117' ||
                                ($v->type[0]->curie ?? '') == 'SEPIO:0004120' ||
                                ($v->type[0]->curie ?? '') == 'SEPIO:0004018'
                                )
                                {
                                    if (isset($v->variant->source->curie))
                                    {
                                        if (isset($apmids[$v->variant->source->curie]))
                                        {
                                            $apmids[$v->variant->source->curie]['variants'][] = $v->variant->variant->label;
                                        }
                                        else
                                        {
                                            $apmids[$v->variant->source->curie] = ['variants' => [$v->variant->variant->label]];
                                        }
                                    }
                                    else
                                    {
                                        //if ($gene->name == "ABCG5")
                                        //dd($v);
                                    }
                                }
                            }
                        }
                        else
                        {
                            // ignore ones with no score
                            if ($subrecord->score <= 0)
                                continue;
                            
                            if(!isset($evidence->variant->label) && isset($evidence->variants) && is_array($evidence->variants))
                            {
                                foreach($evidence->variants as $v)
                                {
                                    if (($subrecord->type[0]->curie ?? '') == 'SEPIO:0004017' ||
                                        ($subrecord->type[0]->curie ?? '') == 'SEPIO:0004117' ||
                                        ($subrecord->type[0]->curie ?? '') == 'SEPIO:0004120' ||
                                        ($subrecord->type[0]->curie ?? '') == 'SEPIO:0004018'
                                        )
                                    {
                                        if (isset($subrecord->variant->source->curie))
                                        {
                                            //if ($gene->name == "ABCG5")
                                        //dd($v);
                                            if (isset($apmids[$subrecord->variant->source->curie]))
                                            {
                                                $apmids[$subrecord->variant->source->curie]['variants'][] = $v->label;
                                            }
                                            else
                                            {
                                                $apmids[$subrecord->variant->source->curie] = ['variants' => [$v->label]];
                                            }
                                        }
                                        else if (isset($subrecord->evidence[0]->source->curie))
                                        {
                                        //if ($gene->name == "ABCG5")
                                        //dd($subrecord->evidence[0]->source->curie);
                                            if (isset($apmids[$subrecord->evidence[0]->source->curie]))
                                            {
                                                $apmids[$subrecord->evidence[0]->source->curie]['variants'][] = $v->label;
                                            }
                                            else
                                            {
                                                $apmids[$subrecord->evidence[0]->source->curie] = ['variants' => [$v->label]];
                                            }
                                        }
                                    }
                                }
                            }
                        }


                }

                foreach ($apmids as $pmid => $variants)
                {
                    $pmid_comment_include = '';
                    foreach ($variants['variants'] as $variant)
                    {
                        $pmid_comment_include .= " + " . $variant . ', observed in [#] affected homozygous probands and [#] affected compound heterozygous probands.  This variant segregated in [#] additional family members across [#] presumably unrelated families.' . "\n\n";

                    }

                    $results[] = [
                        'gene_name' => "\"$gene->name\"",
                        'gene_hgnc' => "\"$gene->hgnc_id\"",
                        'disease_name' => "\"$disease->label\"",
                        'disease_mondo' => "\"$disease->curie\"",
                        'variant_name' => "\"" . implode(', ', $variants['variants']) . "\"",
                        //'variant_type' => $v->variant->variant_type,
                        'gcep_name' => "\"$ep->label\"",
                        'gcep_id' => "\"$ep->curie\"",
                        'pmid' => "\"$pmid\"",
                        'pmid_comment' => '"The ClinGen ' . $ep->label . ' GCEP scored ' . count($variants['variants']) . ' unique predicted or proven null variants from this paper, including:' . "\n\n" . $pmid_comment_include . '"',
                        'loss_comment' => '"The ClinGen ' . $ep->label . ' gene curation expert panel (GCEP) identified ' . $summary->classification->label . ' evidence supporting the role of ' . $gene->name . ' in ' . $disease->label . ', an autosomal recessive condition, on ' . $summary->displayDate($summary->report_date) . ".\n\n"
                                        . 'As part of their evaluation, the GCEP scored at least ' . $line[15] . ' unique predicted or proven null variants; these variants are documented in the PMID sections above.  In addition, at the time of this evaluation, there were ' . $line[8] . ' total likely pathogenic/pathogenic (LP/P) variants submitted to ClinVar with review statuses of 1 star or higher; ' . $line[9] . ' of these (' . $line[10] . '), are predicted/proven null variants.  These LP/P variants are observed in ' . $line[11] . ' distinct exons.' . "\n\n"
                                        . ($line[18] == "0" ? '* There are no observations of homozygous loss of function variants in gnomAD v4.1.' . "\n\n" : '')
                                        . ($line[17] != "0" ? '* Supportive experimental evidence includes a knockout model organism with consistent phenotype([INCLUDE PMID FROM GENE CURATION]).' . "\n\n" : '')
                                        . ($line[6] == "Yes" ? '* Of note, per GTEx v8, ' . $line[7] . ' appears to be alternatively spliced and therefore has relatively low expression across multiple tissue types.' . "\n\n" : '')
                                        . 'In summary, biallelic loss of function is an ESTABLISHED mechanism of disease for ' . $gene->name . ' and autosomal recessive ' . $disease->label . '."'
                    ];
                }





            }

           //dd($results);

            $header = [   "Gene Name",
                        "Gene HGNC ID",
                        "Disease Name",
                        "Disease MONDO",
                        "Variant Name",
                        'GCEP Name',
                        "GCEP ID",
                        "PMID",
                        'PMID COmment',
                        'Loss Phenotype Comment'
                    ];

            $handle = fopen(base_path() . '/data/BLOF Output Draft 3.tsv', "w");
            fwrite($handle, implode("\t", $header) . PHP_EOL);

            foreach($results as $result)
            {
                fwrite($handle, implode("\t", $result) . PHP_EOL);

            }

            fclose($handle);
        }
    
    }


    public function report2()
    {
        $con = new \App\Http\Controllers\ExcelController();

        $results = [];

        $lines = 0;

        $handle = fopen(base_path() . '/data/ADMI_CNV_Molly.csv', "r");
        if ($handle)
        {

            while (($line = fgetcsv($handle)) !== false)
            {
                $row = array_values($line);

                // remove any unprintables
                $row[0] = preg_replace('/[[:^print:]]/', '', $row[0]);
                $row[13] = preg_replace('/[[:^print:]]/', '', $row[13]);
                $row[14] = preg_replace('/[[:^print:]]/', '', $row[14]);

                if (empty($row[0]))
                    break;

                $region = $row[0] . ':' . $row[13] . '-' . $row[14];
                $type = 'GRCh37';

                dd($region);

                //echo "$region \n";

                 $genes = Gene::searchList(['type' => $type,
                        "region" => $region,
                        'option' => 1 ]);

                $row[] = implode(', ', $genes->collection->pluck('name')->toArray());

                $results[] = $row;

                $lines++;
            }
        }

        echo "\n $lines processed \n";
        $con->output($results);
    }


    public function report3()
    {
        $panels = Panel::all();

        $ofd = fopen("/tmp/panelreport.tsv", "w");

        foreach ($panels as $panel)
        {
            $line = [];

            $title = $panel->title;
            $status = $panel->affiliate_status;

            if ($status === null)
                continue;

            // change epochs to dates
            foreach (["gene_date_step_1", "gene_date_step_2", "variant_date_step_1", "variant_date_step_2", "variant_date_step_3", "variant_date_step_4"] as $key)
            {
                if (!empty($status[$key]))
                    $status[$key] = date("Y-m-d H:i:s", $status[$key]);
            }

            if ($status["gene"] === null)
                $status["gene"] = "";

            if ($status["variant"] === null)
                $status["variant"] = "";

            echo "$title\n";

            fwrite($ofd, $title . "\t" . implode("\t", $status) . PHP_EOL);

        }

        fclose($ofd);
    }


    public function report4()
    {
        $threeyears = Carbon::now()->subYears(3);

        /*$curations = Sensitivity::select('*', DB::raw('MAX(id) AS maxversion'))
                    ->where(function($query) {
                        $query->where('haplo_classification', '<', 3);
                        $query->orWhere('triplo_classification', '<', 3);
                    })->where('report_date', '<=', $threeyears)->groupBy('gene_label')->take(10)->get();

                    //        ->where('course_id', $courseId)
        //        ->where('user_id', Auth::id())
        //        ->groupby('gene_label')
          //      ->get();

         /* $messages = Message::select(DB::raw('t.*'))
            ->from(DB::raw('(SELECT * FROM messages ORDER BY created_at DESC) t'))
            ->groupBy('t.from')
            ->get();*/

        $curations = Sensitivity::select()->latest('id')->get()
                        ->unique('gene_label')->sortBy('gene_label');

        // apply the filters

        $curations = $curations->filter(function($item) use ($threeyears) {
                    return ($item->haplo_classification < 3 || $item->triplo_classification < 3)
                            && $item->report_date <= $threeyears;
        });

        /*$curations = Sensitivity::where(function($query) {
                        $query->where('haplo_classification', '<', 3);
                        $query->orWhere('triplo_classification', '<', 3);
                    })->where('report_date', '<=', $threeyears)->latest('id')->get()
                    ->unique('gene_label')->sortBy('gene_label');*/

//dd($curations);
       /* $curations = Sensitivity::where(function($query) {
            $query->where('haplo_classification', '<', 3);
            $query->orWhere('triplo_classification', '<', 3);
        })->where('report_date', '<=', $threeyears)->groupBy('gene_label')->max('version')->get();
*/
        $header = [   "ISCA",
                        "Gene Symbol",
                        "Haplo_Classification",
                        "Triplo Classification",
                        "Dosage Report",
                        'AD/XL',
                        "LOF Score",
                        "Validity Classification",
                        "Validity Report",
                        "Website Link",
                        "GCI Link"
                    ];

        $handle = fopen(base_path() . '/data/dosage_recuration_report.tsv', "w");
        fwrite($handle, implode("\t", $header) . PHP_EOL);

        $records = [];

        foreach($curations as $curation)
        {
            //dd($curation);
            // strip ISCA out of curie
            $isca = substr($curation->curie, 9);
            $stop = strpos($isca, '-', 5);
            $isca = substr($isca, 0, $stop);

            // get the latest validity report
            $validity = Validity::hgnc($curation->gene_hgnc_id)->orderBy('version', 'desc')->first();

            if ($validity === null)
                continue;

            // if the validity report is older than the dosage report, skip it
            if ($validity->report_date <= $curation->report_date)
                continue;

            $properties = json_decode($validity->properties);

            // Proband w/ predicted or proven null variant must be > 0

            if (isset($properties->jsonMessageVersion))
            {
                switch ($properties->jsonMessageVersion)
                {
                    case "GCILite.5":
                        $sop = "SOP5";
                        $proband_w_proven = $properties->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithPredictedOrProvenNullVariant->Value ?? 0;
                        break;
                    case "GCI.6":
                    case "GCI.7":
                        $proband_w_proven = $properties->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithPredictedOrProvenNullVariant->TotalPoints ?? 0;
                        break;
                    case "GCI.8.1":
                        $sop = "SOP8";
                        $proband_w_proven = $properties->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithPredictedOrProvenNull->TotalPoints ?? 0;
                        break;
                    default:
                        echo "unrecognized message " . $properties->jsonMessageVersion . "\n";
                        $porband_w_proven = 0;
                }
            }
            else
            {
                //old style SOP 4, SOP 5 or SOP6
                $proband_w_proven = $properties->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantDisease->ProbandWithLOF->Value ??
                                    ( $properties->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithPredictedOrProvenNullVariant->Value ??
                                    ( $properties->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithPredictedOrProvenNullVariant->TotalPoints ??
                                    0));

            }

            if ($proband_w_proven <= 0)
                continue;

            // TODO - Pick up AR one too

            // strip GCI ID out of curie
            if (strpos($validity->curie, 'CGGCIEX:') === 0)
                $gci = "";
            else
            {
                // if record_id, use that.  Otherwise look it up
                if (isset($properties->report_id))
                    $gci = $properties->report_id;
                else
                {
                    $gci = substr($validity->curie, 15, 36);
                    $map = Gdmmap::gg($gci)->first();
                    $gci = ($map === null ? '' : $map->gdm_uuid);
                }
            }


            //CGGV:assertion_a3ada757-6500-46a1-a89e-42668bbdb934-2021-01-26T170000.000Z

            $list = [   $isca,
                        $curation->gene_label,
                        $curation->haplo_classification,
                        $curation->triplo_classification,
                        $curation->report_date,
                        'AD/XL',
                        $proband_w_proven,
                        $validity->classification,
                        $validity->report_date,
                        '=HYPERLINK("https://search.clinicalgenome.org/kb/genes/' . $curation->gene_hgnc_id . '")',
                        ($gci == "" ? '' : '=HYPERLINK("https://curation.clinicalgenome.org/curation-central/' . $gci . '")')
                    ];

            //echo implode("\t", $list) . "\n";
            fwrite($handle, implode("\t", $list) . PHP_EOL);

            $records[] = [  $isca,
                            $curation->gene_label,
                            $curation->haplo_classification,
                            $curation->triplo_classification,
                            $curation->report_date,
                            'AD/XL',
                            $proband_w_proven,
                            $validity->classification,
                            $validity->report_date,
                            'https://search.clinicalgenome.org/kb/genes/' . $curation->gene_hgnc_id,
                            'https://curation.clinicalgenome.org/curation-central/' . $gci
        ];

        }

        fclose($handle);

        //update jira
        foreach ($records as $record)
        {
            echo "Updating Jira Record for " . $record[0] . "\n";

            $comment = new Comment();

            $body = '
This issue was selected for recuration based on the results of the Recuration Report.

Recuration Report Run Date:  ' . Carbon::now()->format('m/d/Y') . '

   LOF Score:  ' . $record[6] . '
   Validity Classification:  ' . $record[7] . '
   Validity Report Date:  ' . $record[8] . '
   ClinGen Report:  ' . $record[9] . '
   GCI Report:  ' . $record[10] . '
            ';

            $comment->setBody($body);
            $issueService = new IssueService();
            $ret = $issueService->addComment($record[0], $comment);

            $ret = $issueService->updateLabels($record[0],
                        ['RecurationReport'],
                        [],
                        $notifyUsers = false
                    );
        }

        // attach and send email
        $data["email"] = "phillip.weller3@gmail.com";
        $data["title"] = "ClinGen Dosage Sensitivity Re-Curation Report";
        $data["body"] = "This is Demo";

        $files = [
            base_path() . '/data/dosage_recuration_report.tsv'
        ];

        Mail::send('mail.reports.dosage-recuration', $data, function($message)use($data, $files) {
            $message->to($data["email"], $data["email"])
                    ->subject($data["title"]);

            foreach ($files as $file){
                $message->attach($file);
            }

        });

        echo "DONE\n";
    }


    public function report5()
    {

        $curations = GeneLib::validityList([	'page' =>  0,
                                                'pagesize' => "null",
                                                'sort' => 'GENE_LABEL',
                                                'direction' => 'ASC',
                                                'search' => null,
                                                'forcegg' => true,
                                                'curated' => true ]);

        $header = [
                        "Gene Symbol",
                        "HGNC ID",
                        "Validity Classification",
                        "Validity Report Date",
                        "Expert Panel",
                        "Validity Summary Report"
                    ];

        $handle = fopen(base_path() . '/data/gcexpress_report.tsv', "w");
        fwrite($handle, implode("\t", $header) . PHP_EOL);

        $records = [];

        foreach($curations->collection as $curation)
        {

            if (strpos($curation->curie, 'CGGCIEX:assertion_') !== 0)
                continue;

            $list = [   $curation->gene->label,
                        $curation->gene->hgnc_id,
                        $curation->classification->label,
                        $curation->report_date,
                        $curation->attributed_to->label,
                        '=HYPERLINK("https://search.clinicalgenome.org/kb/gene-validity/' . $curation->curie . '")'
                    ];

            fwrite($handle, implode("\t", $list) . PHP_EOL);

        }

        fclose($handle);

        // attach and send email
        $data["email"] = "pweller1@geisinger.edu";
        $data["title"] = "GC Express Report";
        $data["body"] = "This is Demo";

        $files = [
            base_path() . '/data/gcexpress_report.tsv'
        ];

        Mail::send('mail.reports.gcexpress', $data, function($message)use($data, $files) {
            $message->to($data["email"], $data["email"])
                    ->subject($data["title"]);

            foreach ($files as $file){
                $message->attach($file);
            }

        });

        echo "DONE\n";
    }


    /**
     * Create the ACMG .BED file
     */
    public function report6()
    {

        $curations = Gene::acmg59()->where('date_last_curated', '!=', null)->orderBy('name', 'asc')->get();

        $banner = "track name='ClinGen ACMG SF 3.2 Curated Genes' db=hg19\n";

        $collection = collect();

        foreach($curations as $curation)
        {

            // chromosome	start	stop	gene_symbol	haplo_score
            $node = new Nodal(['chromosome' => $curation->chr, 'start' => $curation->start37 - 1, 'stop' => $curation->stop37,
                                'label' => $curation->name,
                                'hs' => 1
                            ]);

            $collection->push($node);
        }

        // sort by chromosome, start
        $sorted = $collection->sortBy([['chromosome', 'asc'], ['start', 'asc']]);

        $handle = fopen(base_path() . '/data/acmg_sf3.bed', "w");

        fwrite($handle, $banner);

        //content
        foreach ($sorted as $item)
        {
            $item = $item->toArray();
            unset($item['symbol']);
            fwrite($handle, implode("\t", $item) . PHP_EOL);
        }

        fclose($handle);

        echo "DONE\n";
    }


    public function report7()
    {

        $records = Gene::whereNotNull('omim_id')->where('morbid', 1)->orWhere(function ($query) {
            $query->whereJsonContains('activity->validity', true);
        })->get();

       //dd($records);

        $fd = fopen("/tmp/omimreport.tsv", "w");

        $header = "Gene\tHGNC\tOMIM\tClinGen\tGCEPS";

        fwrite($fd, $header . PHP_EOL);

        $clingen_count = 0;
        $omim_count = 0;
        $clingen_exc = 0;
        $omim_exc = 0;
        $scope_count = 0;

        $exclude_groups = ['Illumina curations', 'UNC Biocuration Core', 'Counsyl curations', 'Broad-Geisinger Biocuration Core'];

        foreach($records as $record)
        {
            $clingen = false;
            $omim = false;
            $groups = [];

            if (isset($record->activity['validity']) && $record->activity['validity'] == true)
            {
                $clingen = true;
                $clingen_count++;
            }

            if ($record->omim_id !== null && $record->morbid == 1)
            {
                $omim_count++;
                $omim = true;
            }

            if ($clingen && !$omim)
                $clingen_exc++;

            if ($omim && !$clingen)
            {
                $omim_exc++;
            }

            // get list of inscope gceps

            $precurations = Precuration::hgnc($record->hgnc_id)->whereNull('date_retired')->get();

            foreach ($precurations as $precuration)
            {
                // exclude $these
                if (in_array($precuration->group_detail['name'], $exclude_groups))
                    continue;

                if (!in_array($precuration->group_detail['name'], $groups))
                    $groups[] = $precuration->group_detail['name'];
            }

            if ($omim && !$clingen && !empty($groups))
            {
                $scope_count++;
            }

            $data = [ $record->name, $record->hgnc_id, ($omim ? 'X' : ''), ($clingen ? 'X' : ''), implode(', ', $groups)];


            // get all the omim phenotypes for this gene
            $oids = $record->omim_id;
            if ($oids !== null)
            {
                    $phenotypes = Morbid::whereIn('mim', $oids)->get();
                    foreach ($phenotypes as $phenotype)
                    {
                        $name = $phenotype->original_phenotype;
                        if (!empty($phenotype->pheno_omim))
                            $name .= '  ' . $phenotype->pheno_omim;

                        if (!empty($phenotype->mapkey))
                            $name .= '  (' . $phenotype->mapkey . ')';

                        $data[] = $name;
                    }
            }

            fwrite($fd, implode("\t", $data) . PHP_EOL);
        }

        fclose($fd);

        echo "Number of genes in either:  " . $records->count() . "\n";
        echo "Number of Clingen genes:  $clingen_count \n";
        echo "Number of Omim genes:  $omim_count \n";
        echo "Number of Exclusive Clingen genes:  $clingen_exc \n";
        echo "Number of Exclusive Omim genes:  $omim_exc \n";
        echo "Percent overlap:  " . ($clingen_count / $records->count() * 100) . "\n";
        echo "Number of Exclusive Omim Genes in GCEP scope:  $scope_count \n";
    }


    public function report9()
    {

        $lines = 1;

        $handle = fopen(base_path() . '/data/leuko.csv', "r");
        $ohandle = fopen(base_path() . '/data/newleuko.tsv', "w");

        $header = "Original\tCorrected\tCurated Panels\tPrecurated Panels\n";

        if ($handle)
        {

            // skip over header
            $line = fgetcsv($handle);

            fwrite($ohandle, $header);


            while (($line = fgetcsv($handle)) !== false)
            {
                $lines++;

                $row = array_values($line);

                // remove any unprintables
                $row[0] = preg_replace('/[[:^print:]]/', '', $row[0]);

                if (empty($row[0]))
                    break;

                $gene = Gene::name($row[0])->first();

                if ($gene === null)
                {
                    $gene = Gene::alias($row[0])->first();

                    if ($gene == null)
                        $gene = Gene::previous($row[0])->first();
                }

                if ($gene === null)
                {
                    echo "Lookup of gene $row[0] failed...skipping\n";
                    continue;
                }

                $groups = [];

                // gather curated panels
                foreach ($gene->panels as $panel)
                {
                    $groups[] = $panel->title_abbreviated;
                }

                // gather precurated $panels
                $pgroups = [];

                $pcurs = Precuration::hgnc($gene->hgnc_id)->get();

                // gather curated panels
                foreach ($pcurs as $pcur)
                {
                    if ($pcur->date_retired !== null)
                        continue;

                    if (empty($pcur->group_id))
                        continue;

                    $pgroups[] = $pcur->group_id;
                }

                $ppgroups = Panel::whereIn('affiliate_id', $pgroups)->get();

                $pppgroups = [];
                foreach ($ppgroups as $t)
                    $pppgroups[] = $t->title_abbreviated;


                fwrite($ohandle, $row[0] . "\t" . $gene->name . "\t" . implode(", ", $groups) . "\t" . implode(", ", $pppgroups) . PHP_EOL);

            }

            fclose($handle);
            fclose($ohandle);
        }
    }


    public function actionability()
    {
        $curations = Curation::whereIn('status', [1, 6])->where('type', 4)->orderBy('document')->orderBy('context')->with('gene')->get();

        foreach ($curations as $curation)
        {
            echo "$curation->document --- $curation->context --- " . $curation->gene->name . " --- " . $curation->conditions[0] . " \n";
        }
    }


    public function report10()
    {
        $genes = Gene::whereIn('curation_status')->get();

        foreach ($genes as $gene)
        {
            foreach ($gene->curation_status as $prec)
            {
                echo $prec['status'] . "\n";
            }
        }
    }


    public function report12()
    {
        $start = Metric::where('created_at', 'like', '2021-01-01%')->first();
        $stop = Metric::where('created_at', 'like', '2024-01-01%')->first();

        $results = [];

        foreach ($start->values['expert_panels'] as $key => $panel)
        {
            $pid = str_replace('CGAGENT:', '', $key);
            $results[$pid] = ['label' => $panel['label'], 'begin_count' => $panel['count'], 'end_count' => null];
        }

        foreach ($stop->values['expert_panels'] as $key => $panel)
        {
            $pid = str_replace('CGAGENT:', '', $key);

            if (isset($results[$pid]))
            {
                $a = $results[$pid];
                $a['end_count'] = $panel['count'];
                $results[$pid] = $a;
            }
            else{
                $results[$pid] = ['label' => $panel['label'], 'begin_count' => 0, 'end_count' => $panel['count']];
            }

        }

        // vceps
        foreach ($stop->values['pathogenicity_expert_panels'] as $key => $panel)
        {
                $results[$panel['pid']] = ['label' => $panel['label'], 'begin_count' => 0, 'end_count' => $panel['count']];
        }


        $ohandle = fopen(base_path() . '/data/panelreport.tsv', "w");

        $header = "Affiliate ID\tAffiliate Name\tBegin Count\tEnd Count\n";

        fwrite($ohandle, $header);

        foreach($results as $key => $value)
        {
            fwrite($ohandle, $key . "\t" . $value['label']. "\t" . $value['begin_count'] . "\t" . $value['end_count'] . PHP_EOL);
        }

        fclose($ohandle);
        
    }


    public function sf()
    {

        $curations = Acmg::with('disease')->get();

        $header = [
                        "Gene Symbol",
                        "Gene MIM",
                        "Disease Name",
                        "Disease MIM",
                        "Disease MONDO"
                    ];

        $handle = fopen(base_path() . '/data/acmgsf.tsv', "w");
        fwrite($handle, implode("\t", $header) . PHP_EOL);

        $records = [];

        foreach($curations as $curation)
        {

            $list = [   $curation->gene_symbol,
                        $curation->gene_mim,
                        $curation->disease->label,
                        implode(', ', $curation->disease_mims),
                        $curation->disease->curie,
                    ];

            fwrite($handle, implode("\t", $list) . PHP_EOL);

        }

        fclose($handle);

        echo "DONE\n";
    }

}
