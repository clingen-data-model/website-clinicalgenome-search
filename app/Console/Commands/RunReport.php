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
use App\GeneLib;
use App\Morbid;
use App\Panel;
use App\Sensitivity;
use App\Validity;
use App\Nodal;
use App\Gdmmap;

class RunReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:report {report=none}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Erins omim report';

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
            case 'gcexpress':
                echo "Creating GC Express Report\n";
                $this->report5();
                echo "Update Complete\n";
                break;
            case 'acmg':
                echo "Creating ACMG BED Report\n";
                $this->report6();
                echo "Update Complete\n";
                break;
            default:
                echo "Nothing to do, exiting\n";
                break;
        }
    }

    public function report1()
    {
        $results = GeneLib::validityList([	'page' =>  0,
										'pagesize' => "null",
										'sort' => 'GENE_LABEL',
                                        'direction' => 'ASC',
                                        'search' => null,
                                        'forcegg' => true,
                                        'curated' => true ]);

        //TODO:  this will likely hang on a refresh, need to time out
        if ($results === null)
        {
            echo "(E001) Genegraph failed\n";
            exit;
        }

        // remove redundat genes
        $collection = $results->collection->unique(function ($item) {
            return $item->gene->label;
        });


        echo "Gene\tHGNCID\tValidity\tPhenotype\tPheno Omim\tDisputing\tNonDisease\tMutations\tMap Key\n";

        foreach ($collection as $assertion)
        {
            switch ($assertion->classification->label)
            {
                case 'limited evidence':
                case 'disputing':
                case 'refuting evidence':
                    // look up the gene in morbid file
                    $records = Morbid::whereJsonContains('genes', $assertion->gene->label)->get();
                    if ($records->isEmpty())
                        break;
                    foreach ($records as $record)
                    {
                        echo $assertion->gene->label . "\t"
                            . $assertion->gene->hgnc_id . "\t"
                            . $assertion->classification->label . "\t"
                            . $record->phenotype . "\t"
                            . $record->pheno_omim . "\t"
                            . $record->disputing . "\t"
                            . $record->nondisease . "\t"
                            . $record->mutations . "\t"
                            . $record->mapkey . "\n";
                    }
                default:
                    break;
            }
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

        $banner = "track name='ClinGen ACMG SF 3.0 Curated Genes' db=hg19\n";

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

}
