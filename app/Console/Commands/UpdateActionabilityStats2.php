<?php

namespace App\Console\Commands;

use App\ActionabilitySummary;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

use App\ActionabilityAssertion;
use App\Curation;

class UpdateActionabilityStats2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:actionability-stats2 {json?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This uses the Actionability Summary data in the local store and builds the stats for actionability show on the site';

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
     * @return int
     */
    public function handle()
    {
        $output_json = $this->argument('json');


        // Total Actionability Topics
        //$data = ActionabilitySummary::where("status_overall", "=", "Released")->get();
        $data = Curation::where('status', Curation::STATUS_ACTIVE)->orWhere('status', Curation::STATUS_ACTIVE_REVIEW)->get();
        $report = array();
        foreach ($data as $item) {
            //dd($item);
            $report[] = $item->alternate_uuid;
        }
        $total_topics                  = array_values(array_unique($report));
        $total_topics                   = count($total_topics);

        // Total Topics
        //$data = ActionabilitySummary::where("status_overall", "=", "Released")->get();
        $data = Curation::where('status', Curation::STATUS_ACTIVE)->orWhere('status', Curation::STATUS_ACTIVE_REVIEW)->get();
        $report = array();
        foreach ($data as $item) {
            $release_number = explode(".", $item->curation_version);
            if ($release_number[0] >= 2) {
                $report[] = $item->alternate_uuid;
            }
        }
        $total_updated_topics                   = array_values(array_unique($report));
        $total_updated_topics                   = count($total_updated_topics);


        // Total Genes
        $data = Curation::where('status', Curation::STATUS_ACTIVE)->orWhere('status', Curation::STATUS_ACTIVE_REVIEW)->get();
        //data = ActionabilityAssertion::where("status_overall", "=", "Released")->orWhere("status_overall", "=", "Released - Under Revision")->get();
        $report = array();
        foreach ($data as $item) {
            $report[] = $item->gene_hgnc_id;
            if ($item->affiliate_id == "Pediatric AWG") {
                $reportPediatric[] = $item->gene_hgnc_id;
            }
            if ($item->affiliate_id == "Adult AWG") {
                $reportAdult[] = $item->gene_hgnc_id;
            }
        }
        $total_genes                   = array_values(array_unique($report));
        $total_genes                   = count($total_genes);
        $total_genes_adult                   = array_values(array_unique($reportAdult));
        $total_genes_adult                   = count($total_genes_adult);
        $total_genes_peds                   = array_values(array_unique($reportPediatric));
        $total_genes_peds                   = count($total_genes_peds);


        // Total Genes Pairs & Unique Pairs
        /*$data = ActionabilityAssertion::Where([
            ["omim", "!=", "(No paired disease(s) for gene)"],
            ["status_stg1", "!=", "Failed"],
            ["status_overall", "!=", "Retracted"]
        ])->get();*/
        $data = Curation::whereNotIn('status', [Curation::STATUS_ARCHIVE, Curation::STATUS_RETRACTED, Curation::STATUS_PRELIMINARY])
                    ->whereNotNull('disease_id')
                    ->where('scores->earlyRuleOutStatus', '!=', "Failed")
                    ->get();
        $report = array();
        $reportPediatric = array();
        $reportAdult = array();
        foreach ($data as $item) {
            $report[$item->id] = $item->document . "-" . $item->gene_hgnc_id . "-" . $item->disease_id . "-" . $item->alternate_uuid;
            if ($item->affiliate_id == "Pediatric AWG") {
                $reportPediatric[] = $report[$item->id];
            }
            if ($item->affiliate_id == "Adult AWG") {
                $reportAdult[] = $report[$item->id];
            }
        }
        //$total_genes_pairs_unique            = array_values(array_unique($report));
        $total_genes_pairs_unique            = array_unique($report);
        sort($total_genes_pairs_unique);
        dd($total_genes_pairs_unique);
        $total_genes_pairs_unique            = count($total_genes_pairs_unique);
        $total_genes_pairs_unique_adult      = array_values(array_unique($reportAdult));
        $total_genes_pairs_unique_adult      = count($total_genes_pairs_unique_adult);
        $total_genes_pairs_unique_peds       = array_values(array_unique($reportPediatric));
        $total_genes_pairs_unique_peds       = count($total_genes_pairs_unique_peds);


        // Total Not Failed
        $data = ActionabilitySummary::Where([
            ["status_overall", "=", "Released"],
            ["status_stg1", "=", "Complete"],
        ])->orWhere([
            ["status_overall", "=", "Released"],
            ["status_stg1", "=", "Incomplete"],
        ])->get();
        $report = array();
        $reportPediatric = array();
        $reportAdult = array();
        $reportTopic = array();
        foreach ($data as $item) {
            //dd($item);
            $report[] = $item->docId . "-" . $item->gene . "-" . $item->omim . "-" . $item->context;
            if ($item->context == "Pediatric") {
                $reportPediatric[] = $item->docId . "-" . $item->context;
            }
            if ($item->context == "Adult") {
                $reportAdult[] = $item->docId . "-" . $item->context;
            }
            $reportTopic[] = $item->docId . "-" . $item->context;
        }
        $total_complete_io_pairs                   = array_values(array_unique($report));
        $total_complete_io_pairs                   = count($total_complete_io_pairs);
        $total_complete_topic                      = array_values(array_unique($reportTopic));
        $total_complete_topic                      = count($total_complete_topic);
        $total_complete_topic_adult                = array_values(array_unique($reportAdult));
        $total_complete_topic_adult                = count($total_complete_topic_adult);
        $total_complete_topic_peds                 = array_values(array_unique($reportPediatric));
        $total_complete_topic_peds                 = count($total_complete_topic_peds);


        // Total Failed
        $data = ActionabilitySummary::Where([
            ["status_overall", "=", "Released"],
            ["status_stg1", "=", "Failed"],
        ])->get();
        $report = array();
        $reportPediatric = array();
        $reportAdult = array();
        $reportTopic = array();
        foreach ($data as $item) {
            //dd($item);
            $report[] = $item->docId . "-" . $item->gene . "-" . $item->omim . "-" . $item->context;
            if($item->context == "Pediatric"){
                $reportPediatric[] = $item->docId . "-" . $item->context;
            }
            if ($item->context == "Adult") {
                $reportAdult[] = $item->docId . "-" . $item->context;
            }
            $reportTopic[] = $item->docId . "-" . $item->context;
        }
        //dd(array_unique($reportTopic));
        $total_failed_io_pairs                   = array_values(array_unique($report));
        $total_failed_io_pairs                   = count($total_failed_io_pairs);
        $total_failed_topic                      = array_values(array_unique($reportTopic));
        $total_failed_topic                      = count($total_failed_topic);
        $total_failed_topic_adult                = array_values(array_unique($reportAdult));
        $total_failed_topic_adult                = count($total_failed_topic_adult);
        $total_failed_topic_peds                 = array_values(array_unique($reportPediatric));
        $total_failed_topic_peds                 = count($total_failed_topic_peds);


        // Total IO  Pairs & + Counts
        $data = ActionabilitySummary::Where([
            //["context", "=", "Adult"],
            //["status_overall", "=", "Released"],
            ["omim", "!=", "(No paired disease(s) for gene)"],
            ["overall", "!=", "IN"],
        ])->orWhere([
            //["context", "=", "Adult"],
            //["status_overall", "=", "Released - Under Revision"],
            ["omim", "!=", "(No paired disease(s) for gene)"],
            ["overall", "!=", "IN"],
        ])->get();
        $report = array();
        foreach ($data as $item) {
            //dd($item);
            $report[$item->id] = $item->docId . "-" . $item->context . "-" . Str::of($item->outcome)->slug('') . "-" . Str::of($item->intervention)->slug('') . "-" . Str::of($item->outcomeScoringGroup)->slug('') . "-" . Str::of($item->interventionScoringGroup)->slug('');

            // $report[$item->id] = [
            //     ['item' => $item->docId . "-" . $item->omim . "-" . $item->context . "-" . Str::of($item->outcome)->slug('') . "-" . Str::of($item->intervention)->slug('')],
            //     ['pair' => Str::of($item->outcome)->slug('') . "-" . Str::of($item->intervention)->slug('')],
            //     ['match' => $item->docId . "-" . Str::of($item->outcomeScoringGroup)->slug('') . "-" . Str::of($item->interventionScoringGroup)->slug('')]
            // ];
        }
        //dd($report);
        $total_io_pairs                   = count($report);
        $total_io_pairs_unique            = array_unique($report);
        //dd($total_io_pairs_unique);
        $total_io_pairs_unique            = count($total_io_pairs_unique);


        // Total IO Adult Pairs & + Counts
        $data = ActionabilitySummary::Where([
            ["context", "=", "Adult"],
            //["status_overall", "!=", "Released"],
            ["omim", "!=", "(No paired disease(s) for gene)"],
            ["overall", "!=", "IN"],
        ])->orWhere([
            ["context", "=", "Adult"],
            //["status_overall", "=", "Released - Under Revision"],
            ["omim", "!=", "(No paired disease(s) for gene)"],
            ["overall", "!=", "IN"],
        ])->get();
        $total_adult_io_pairs_12    = 0;
        $total_adult_io_pairs_11    = 0;
        $total_adult_io_pairs_10    = 0;
        $total_adult_io_pairs_9    = 0;
        $total_adult_io_pairs_8    = 0;
        $total_adult_io_pairs_7    = 0;
        $total_adult_io_pairs_6    = 0;
        $total_adult_io_pairs_5less    = 0;
        $total_adult_io_pairs = 0;
        $find = array();
        $report = array();
        foreach ($data as $item) {
            $report[$item->id] = $item->docId . "-" . $item->context . "-" . Str::of($item->outcome)->slug('') . "-" . Str::of($item->intervention)->slug('') . "-" . Str::of($item->outcomeScoringGroup)->slug('') . "-" . Str::of($item->interventionScoringGroup)->slug('');
        }

        //dd($overall);
        $total_adult_io_pairs                   = count($report);
        $total_adult_io_pairs_unique_array      = array_unique($report);
        //dd($total_adult_io_pairs_unique_array);
        $total_adult_io_pairs_unique            = count($total_adult_io_pairs_unique_array);

        foreach ($total_adult_io_pairs_unique_array as $key => $item) {
            $find[] = $key;
        }

        $results = ActionabilitySummary::findMany($find);
        foreach ($results as $item) {
            $overall = (int) preg_replace('/[^0-9]/', '', $item->overall);
            $total_adult_io_pairs++;
            //dd($overall);
            switch ($overall) {
                case 12:
                    $total_adult_io_pairs_12++;
                    break;
                case 11:
                    $total_adult_io_pairs_11++;
                    break;
                case 10:
                    $total_adult_io_pairs_10++;
                    break;
                case 9:
                    $total_adult_io_pairs_9++;
                    break;
                case 8:
                    $total_adult_io_pairs_8++;
                    break;
                case 7:
                    $total_adult_io_pairs_7++;
                    break;
                case 6:
                    $total_adult_io_pairs_6++;
                    break;
                default:
                    $total_adult_io_pairs_5less++;
            }
        }




        // Total IO Pediatric Pairs & + Counts
        $data = ActionabilitySummary::Where([
            ["context", "=", "Pediatric"],
            //["status_overall", "!=", "Released"],
            ["omim", "!=", "(No paired disease(s) for gene)"],
            ["overall", "!=", "IN"],
        ])->orWhere([
            ["context", "=", "Pediatric"],
            //["status_overall", "=", "Released - Under Revision"],
            ["omim", "!=", "(No paired disease(s) for gene)"],
            ["overall", "!=", "IN"],
        ])->get();
        $total_peds_io_pairs_12     = 0;
        $total_peds_io_pairs_11     = 0;
        $total_peds_io_pairs_10     = 0;
        $total_peds_io_pairs_9      = 0;
        $total_peds_io_pairs_8      = 0;
        $total_peds_io_pairs_7      = 0;
        $total_peds_io_pairs_6      = 0;
        $total_peds_io_pairs_5less  = 0;
        $total_peds_io_pairs        = 0;
        $find = array();
        $report = array();
        //dd($data->count());
        foreach ($data as $item) {
            $report[$item->id] = $item->docId . "-" . $item->context . "-" . Str::of($item->outcome)->slug('') . "-" . Str::of($item->intervention)->slug('') . "-" . Str::of($item->outcomeScoringGroup)->slug('') . "-" . Str::of($item->interventionScoringGroup)->slug('');
        }

        //dd($overall);
        $total_peds_io_pairs                   = count($report);
        $total_peds_io_pairs_unique_array      = array_unique($report);
        $total_peds_io_pairs_unique            = count($total_peds_io_pairs_unique_array);

        foreach ($total_peds_io_pairs_unique_array as $key => $item) {
            $find[] = $key;
        }

        $results = ActionabilitySummary::findMany($find);
        foreach ($results as $item) {
            $overall = (int) preg_replace('/[^0-9]/', '', $item->overall);
            $total_peds_io_pairs++;
            //dd($overall);
            switch ($overall) {
                case 12:
                    $total_peds_io_pairs_12++;
                    break;
                case 11:
                    $total_peds_io_pairs_11++;
                    break;
                case 10:
                    $total_peds_io_pairs_10++;
                    break;
                case 9:
                    $total_peds_io_pairs_9++;
                    break;
                case 8:
                    $total_peds_io_pairs_8++;
                    break;
                case 7:
                    $total_peds_io_pairs_7++;
                    break;
                case 6:
                    $total_peds_io_pairs_6++;
                    break;
                default:
                    $total_peds_io_pairs_5less++;
            }
        }


        // Total IO Pediatric Assertions
        $data = ActionabilityAssertion::Where([
            ["context", "=", "Pediatric"],
            //["status_overall", "!=", "Released"],
            //["omim", "!=", "(No paired disease(s) for gene)"],
            ["consensus_assertion", "!=", ""]
        ])->get();
        $total_peds_assertion_definitive            = 0;
        $total_peds_assertion_strong                = 0;
        $total_peds_assertion_moderate              = 0;
        $total_peds_assertion_limited               = 0;
        $total_peds_assertion_na_expert_review      = 0;
        $total_peds_assertion_na_early_rule_out     = 0;
        $total_peds_assertion_assertion_pending     = 0;
        $total_peds_assertion_unknown               = 0;
        $find = array();
        $report = array();
        //dd($data->count());
        // foreach ($data as $item) {
        //     $report[$item->id] = $item->docId . "-" . $item->context . "-" . Str::of($item->outcome)->slug('') . "-" . Str::of($item->intervention)->slug('') . "-" . Str::of($item->outcomeScoringGroup)->slug('') . "-" . Str::of($item->interventionScoringGroup)->slug('');
        // }

        //dd($overall);
        // $total_peds_io_pairs                   = count($report);
        // $total_peds_io_pairs_unique_array      = array_unique($report);
        // $total_peds_io_pairs_unique            = count($total_peds_io_pairs_unique_array);

        // foreach ($total_peds_io_pairs_unique_array as $key => $item) {
        //     $find[] = $key;
        // }
    //dd($data);
        //$results = ActionabilitySummary::findMany($find);
        foreach ($data as $item) {
            //$overall = (int) preg_replace('/[^0-9]/', '', $item->overall);
            //$total_peds_io_pairs++;
            //dd($overall);
            switch ($item->consensus_assertion) {
                case "Definitive Actionability":
                    $total_peds_assertion_definitive++;
                    break;
                case 'Strong Actionability':
                    $total_peds_assertion_strong++;
                    break;
                case "Moderate Actionability":
                    $total_peds_assertion_moderate++;
                    break;
                case "Limited Actionability":
                    $total_peds_assertion_limited++;
                    break;
                case "N/A - Insufficient evidence: expert review":
                    $total_peds_assertion_na_expert_review++;
                    break;
                case "N/A - Insufficient evidence: early rule-out":
                    $total_peds_assertion_na_early_rule_out++;
                    break;
                case "Assertion Pending":
                    $total_peds_assertion_assertion_pending++;
                    break;
                default:
                    $total_peds_assertion_unknown++;
            }
        }

        // Total IO Adult Assertions
        $data = ActionabilityAssertion::Where([
            ["context", "=", "Adult"],
            //["status_overall", "!=", "Released"],
            //["omim", "!=", "(No paired disease(s) for gene)"],
            ["consensus_assertion", "!=", ""]
        ])->get();
        $total_adult_assertion_definitive            = 0;
        $total_adult_assertion_strong                = 0;
        $total_adult_assertion_moderate              = 0;
        $total_adult_assertion_limited               = 0;
        $total_adult_assertion_na_expert_review      = 0;
        $total_adult_assertion_na_early_rule_out     = 0;
        $total_adult_assertion_assertion_pending     = 0;
        $total_adult_assertion_unknown               = 0;
        $find = array();
        $report = array();
        //dd($data->count());
        // foreach ($data as $item) {
        //     $report[$item->id] = $item->docId . "-" . $item->context . "-" . Str::of($item->outcome)->slug('') . "-" . Str::of($item->intervention)->slug('') . "-" . Str::of($item->outcomeScoringGroup)->slug('') . "-" . Str::of($item->interventionScoringGroup)->slug('');
        // }

        //dd($overall);
        // $total_peds_io_pairs                   = count($report);
        // $total_peds_io_pairs_unique_array      = array_unique($report);
        // $total_peds_io_pairs_unique            = count($total_peds_io_pairs_unique_array);

        // foreach ($total_peds_io_pairs_unique_array as $key => $item) {
        //     $find[] = $key;
        // }

        //$results = ActionabilitySummary::findMany($find);
        foreach ($data as $item) {
            //$overall = (int) preg_replace('/[^0-9]/', '', $item->overall);
            //$total_peds_io_pairs++;
            //dd($overall);
            switch ($item->consensus_assertion) {
                case "Definitive Actionability":
                    $total_adult_assertion_definitive++;
                    break;
                case 'Strong Actionability':
                    $total_adult_assertion_strong++;
                    break;
                case "Moderate Actionability":
                    $total_adult_assertion_moderate++;
                    break;
                case "Limited Actionability":
                    $total_adult_assertion_limited++;
                    break;
                case "N/A - Insufficient evidence: expert review":
                    $total_adult_assertion_na_expert_review++;
                    break;
                case "N/A - Insufficient evidence: early rule-out":
                    $total_adult_assertion_na_early_rule_out++;
                    break;
                case "Assertion Pending":
                    $total_adult_assertion_assertion_pending++;
                    break;
                default:
                    $total_adult_assertion_unknown++;
            }
        }

        $total_assertion_definitive           = ((int)$total_adult_assertion_definitive + (int)$total_peds_assertion_definitive);
        $total_assertion_strong                = ((int)$total_adult_assertion_strong + (int)$total_peds_assertion_strong);
        $total_assertion_moderate              = ((int)$total_adult_assertion_moderate + (int)$total_peds_assertion_moderate);
        $total_assertion_limited              = ((int)$total_adult_assertion_limited + (int)$total_peds_assertion_limited);
        $total_assertion_na_expert_review     = ((int)$total_adult_assertion_na_expert_review + (int)$total_peds_assertion_na_expert_review);
        $total_assertion_na_early_rule_out    = ((int)$total_adult_assertion_na_early_rule_out + (int)$total_peds_assertion_na_early_rule_out);
        $total_assertion_assertion_pending    = ((int)$total_adult_assertion_assertion_pending + (int)$total_peds_assertion_assertion_pending);
        $total_assertion_unknown              = ((int)$total_adult_assertion_unknown + (int)$total_peds_assertion_unknown);

        $total_adult_assertion = ((int)$total_adult_assertion_definitive + (int)$total_adult_assertion_strong + (int)$total_adult_assertion_moderate + (int)$total_adult_assertion_limited + (int)$total_adult_assertion_na_expert_review + (int)$total_adult_assertion_na_early_rule_out + (int)$total_adult_assertion_assertion_pending + (int)$total_adult_assertion_unknown);

        $total_peds_assertion = ((int)$total_peds_assertion_definitive + (int)$total_peds_assertion_strong + (int)$total_peds_assertion_moderate + (int)$total_peds_assertion_limited + (int)$total_peds_assertion_na_expert_review + (int)$total_peds_assertion_na_early_rule_out + (int)$total_peds_assertion_assertion_pending + (int)$total_peds_assertion_unknown);

        $total_assertion = ((int)$total_adult_assertion + (int)$total_peds_assertion);

    if($output_json) {
            $array = array();
            $array['total_topics']                  = $total_topics;
            $array['total_updated_topics']          = $total_updated_topics;
            $array['total_genes']                   = $total_genes;
            $array['total_genes_adult']                   = $total_genes_adult;
            $array['total_genes_peds']                   = $total_genes_peds;

            $array['total_genes_pairs_unique']              = $total_genes_pairs_unique;
            $array['total_genes_pairs_unique_adult']        = $total_genes_pairs_unique_adult;
            $array['total_genes_pairs_unique_peds']         = $total_genes_pairs_unique_peds;

            $array['total_complete_io_pairs']      = $total_complete_io_pairs;
            $array['total_complete_topic']          = $total_complete_topic;
            $array['total_complete_topic_adult']    = $total_complete_topic_adult;
            $array['total_complete_topic_peds']     = $total_complete_topic_peds;

            $array['total_failed_io_pairs']         = $total_failed_io_pairs;
            $array['total_failed_topic']            = $total_failed_topic;
            $array['total_failed_topic_adult']      = $total_failed_topic_adult;
            $array['total_failed_topic_peds']       = $total_failed_topic_peds;

            $array['total_io_pairs_unique']         = $total_io_pairs_unique;
            $array['total_adult_io_pairs_unique']   = $total_adult_io_pairs_unique;
            $array['total_adult_io_pairs_12']       = $total_adult_io_pairs_12;
            $array['total_adult_io_pairs_11']       = $total_adult_io_pairs_11;
            $array['total_adult_io_pairs_10']       = $total_adult_io_pairs_10;
            $array['total_adult_io_pairs_9']        = $total_adult_io_pairs_9;
            $array['total_adult_io_pairs_8']        = $total_adult_io_pairs_8;
            $array['total_adult_io_pairs_7']        = $total_adult_io_pairs_7;
            $array['total_adult_io_pairs_6']        = $total_adult_io_pairs_6;
            $array['total_adult_io_pairs_5less']    = $total_adult_io_pairs_5less;
            $array['total_peds_io_pairs_unique']    = $total_peds_io_pairs_unique;
            $array['total_peds_io_pairs_12']        = $total_peds_io_pairs_12;
            $array['total_peds_io_pairs_11']        = $total_peds_io_pairs_11;
            $array['total_peds_io_pairs_10']        = $total_peds_io_pairs_10;
            $array['total_peds_io_pairs_9']         = $total_peds_io_pairs_9;
            $array['total_peds_io_pairs_8']         = $total_peds_io_pairs_8;
            $array['total_peds_io_pairs_7']         = $total_peds_io_pairs_7;
            $array['total_peds_io_pairs_6']         = $total_peds_io_pairs_6;
            $array['total_peds_io_pairs_5less']     = $total_peds_io_pairs_5less;


            $array['total_assertion']                       = $total_assertion;
            $array['total_assertion_definitive']            = $total_assertion_definitive;
            $array['total_assertion_strong']                = $total_assertion_strong;
            $array['total_assertion_moderate']              = $total_assertion_moderate;
            $array['total_assertion_limited']               = $total_assertion_limited;
            $array['total_assertion_na_expert_review']      = $total_assertion_na_expert_review;
            $array['total_assertion_na_early_rule_out']     = $total_assertion_na_early_rule_out;
            $array['total_assertion_assertion_pending']     = $total_assertion_assertion_pending;
            $array['total_assertion_unknown']               = $total_assertion_unknown;

            $array['total_adult_assertion']                     = $total_adult_assertion;
            $array['total_adult_assertion_definitive']          = $total_adult_assertion_definitive;
            $array['total_adult_assertion_strong']              = $total_adult_assertion_strong;
            $array['total_adult_assertion_moderate']            = $total_adult_assertion_moderate;
            $array['total_adult_assertion_limited']             = $total_adult_assertion_limited;
            $array['total_adult_assertion_na_expert_review']    = $total_adult_assertion_na_expert_review;
            $array['total_adult_assertion_na_early_rule_out']   = $total_adult_assertion_na_early_rule_out;
            $array['total_adult_assertion_assertion_pending']   = $total_adult_assertion_assertion_pending;
            $array['total_adult_assertion_unknown']             = $total_adult_assertion_unknown;

            $array['total_peds_assertion']                      = $total_peds_assertion;
            $array['total_peds_assertion_definitive']           = $total_peds_assertion_definitive;
            $array['total_peds_assertion_strong']               = $total_peds_assertion_strong;
            $array['total_peds_assertion_moderate']             = $total_peds_assertion_moderate;
            $array['total_peds_assertion_limited']              = $total_peds_assertion_limited;
            $array['total_peds_assertion_na_expert_review']     = $total_peds_assertion_na_expert_review;
            $array['total_peds_assertion_na_early_rule_out']    = $total_peds_assertion_na_early_rule_out;
            $array['total_peds_assertion_assertion_pending']    = $total_peds_assertion_assertion_pending;
            $array['total_peds_assertion_unknown']              = $total_peds_assertion_unknown;

            $this->line(json_encode($array));
            //dd($array);
    } else {
        $this->line("Total Actionability Topics ----------------------------- " . $total_topics);
        $this->line("Total Updated Topics ----------------------------------- " . $total_updated_topics);
        $this->line("Total Genes -------------------------------------------- " . $total_genes);
            $this->line("Total Genes Adult ---------------------------------------- " . $total_genes_adult);
            $this->line("Total Genes Peds ---------------------------------------- " . $total_genes_peds);

        $this->line("Total Unique Genes Pairs ------------------------------- " . $total_genes_pairs_unique);
        $this->line("Total Unique Genes Pairs Adult ------------------------- " . $total_genes_pairs_unique_adult);
        $this->line("Total Unique Genes Pairs Peds -------------------------- " . $total_genes_pairs_unique_peds);

        $this->line("Total Complete IO Pairs -------------------------------- " . $total_complete_io_pairs);
        $this->line("Total Complete Topic ----------------------------------- " . $total_complete_topic);
        $this->line("Total Complete Topic Adult ----------------------------- " . $total_complete_topic_adult);
        $this->line("Total Complete Topic Peds ------------------------------ " . $total_complete_topic_peds);

        $this->line("Total Failed IO Pairs ---------------------------------- " . $total_failed_io_pairs);
        $this->line("Total Failed Topic ------------------------------------- " . $total_failed_topic);
        $this->line("Total Failed Topic Adult ------------------------------- " . $total_failed_topic_adult);
        $this->line("Total Failed Topic Peds -------------------------------- " . $total_failed_topic_peds);

        $this->line("Total Unique I/O Pairs --------------------------------- " . $total_io_pairs_unique);

        $this->line("Total Adult Unique I/O Pairs --------------------------- " . $total_adult_io_pairs_unique);
        $this->line("Adult Score 12 ----------------------------------------- " . $total_adult_io_pairs_12);
        $this->line("Adult Score 11 ----------------------------------------- " . $total_adult_io_pairs_11);
        $this->line("Adult Score 10 ----------------------------------------- " . $total_adult_io_pairs_10);
        $this->line("Adult Score 9 ------------------------------------------ " . $total_adult_io_pairs_9);
        $this->line("Adult Score 8 ------------------------------------------ " . $total_adult_io_pairs_8);
        $this->line("Adult Score 7 ------------------------------------------ " . $total_adult_io_pairs_7);
        $this->line("Adult Score 6 ------------------------------------------ " . $total_adult_io_pairs_6);
        $this->line("Adult Score 5< ----------------------------------------- " . $total_adult_io_pairs_5less);

        $this->line("Total Peds Unique I/O Pairs ---------------------------- " . $total_peds_io_pairs_unique);
        $this->line("Peds Score 12 ------------------------------------------ " . "$total_peds_io_pairs_12");
        $this->line("Peds Score 11 ------------------------------------------ " . $total_peds_io_pairs_11);
        $this->line("Peds Score 10 ------------------------------------------ " . $total_peds_io_pairs_10);
        $this->line("Peds Score 9 ------------------------------------------- " . $total_peds_io_pairs_9);
        $this->line("Peds Score 8 ------------------------------------------- " . $total_peds_io_pairs_8);
        $this->line("Peds Score 7 ------------------------------------------- " . $total_peds_io_pairs_7);
        $this->line("Peds Score 6 ------------------------------------------- " . $total_peds_io_pairs_6);
        $this->line("Peds Score 5< ------------------------------------------ " . $total_peds_io_pairs_5less);

        $this->line("Assertion Total  --------------------------------------- " . $total_assertion);
        $this->line("Assertion Total Definitive ----------------------------- " . $total_assertion_definitive);
        $this->line("Assertion Total Strong --------------------------------- " . $total_assertion_strong);
        $this->line("Assertion Total Moderate ------------------------------- " . $total_assertion_moderate);
        $this->line("Assertion Total Limited -------------------------------- " . $total_assertion_limited);
        $this->line("Assertion Total NA Expert Review ----------------------- " . $total_assertion_na_expert_review);
        $this->line("Assertion Total NA Early Rule-out ---------------------- " . $total_assertion_na_early_rule_out);
        $this->line("Assertion Total Pending -------------------------------- " . $total_assertion_assertion_pending);
        $this->line("Assertion Total unknown (just testing) ----------------- " . $total_assertion_unknown);

        $this->line("Assertion Adult Total ---------------------------------- " . $total_adult_assertion);
        $this->line("Assertion Adult Definitive ----------------------------- " . $total_adult_assertion_definitive);
        $this->line("Assertion Adult Strong --------------------------------- " . $total_adult_assertion_strong);
        $this->line("Assertion Adult Moderate ------------------------------- " . $total_adult_assertion_moderate);
        $this->line("Assertion Adult Limited -------------------------------- " . $total_adult_assertion_limited);
        $this->line("Assertion Adult NA Expert Review ----------------------- " . $total_adult_assertion_na_expert_review);
        $this->line("Assertion Adult NA Early Rule-out ---------------------- " . $total_adult_assertion_na_early_rule_out);
        $this->line("Assertion Adult Pending -------------------------------- " . $total_adult_assertion_assertion_pending);
        $this->line("Assertion Adult unknown (just testing) ----------------- " . $total_adult_assertion_unknown);

        $this->line("Assertion Peds Total ----------------------------------- " . $total_peds_assertion);
        $this->line("Assertion Peds Definitive ------------------------------ " . $total_peds_assertion_definitive);
        $this->line("Assertion Peds Strong ---------------------------------- " . $total_peds_assertion_strong);
        $this->line("Assertion Peds Moderate -------------------------------- " . $total_peds_assertion_moderate);
        $this->line("Assertion Peds Limited --------------------------------- " . $total_peds_assertion_limited);
        $this->line("Assertion Peds NA Expert Review ------------------------ " . $total_peds_assertion_na_expert_review);
        $this->line("Assertion Peds NA Early Rule-out ----------------------- " . $total_peds_assertion_na_early_rule_out);
        $this->line("Assertion Peds Pending --------------------------------- " . $total_peds_assertion_assertion_pending);
        $this->line("Assertion Peds unknown (just testing) ------------------ " . $total_peds_assertion_unknown);

        }
        return 0;
    }
}
