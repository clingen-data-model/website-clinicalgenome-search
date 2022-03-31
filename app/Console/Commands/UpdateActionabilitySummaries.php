<?php

namespace App\Console\Commands;

use App\ActionabilitySummary;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

use App\ActionabilityAssertion;

class UpdateActionabilitySummaries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:actionability-summaries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will grab the TSV files from the ACI and rebuild the actionability_summaries table which is used for stats, etc.';

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

        $this->line('Starting Update Actionability Summaries');

        // GO GRAB THE ADULT DATA
        $results_adult = fopen('https://actionability.clinicalgenome.org/ac/Adult/api/summ?format=tsv', 'r');
        if (
            $results_adult === false
        ) {
            $this->line('(E001) Error retreiving Actionability Adult Summary data');
        }
        $this->line('Downloaded Actionability Adult Summary data');

        // GO GRAB THE PEDS DATA
        $results_peds = fopen('https://actionability.clinicalgenome.org/ac/Pediatric/api/summ?format=tsv', 'r');
        if (
            $results_peds === false
        ) {
            $this->line('(E001) Error retreiving Actionability Pediatric Summary data');
        }
        $this->line('Downloaded Actionability Pediatric Summary data');

        // GO GRAB THE ADULT Assertion DATA
        $results_adult_assertion = fopen('https://actionability.clinicalgenome.org/ac/Adult/api/summ/assertion?format=tsv', 'r');
        if (
            $results_adult_assertion === false
        ) {
            $this->line('(E001) Error retreiving Actionability Adult Assertion data');
        }
        $this->line('Downloaded Actionability Adult Assertion data');

        // GO GRAB THE ADULT Assertion DATA
        $results_peds_assertion = fopen('https://actionability.clinicalgenome.org/ac/Pediatric/api/summ/assertion?format=tsv', 'r');
        if (
            $results_peds_assertion === false
        ) {
            $this->line('(E001) Error retreiving Actionability Pediatric Assertion data');
        }
        $this->line('Downloaded Actionability Pediatric Assertion data');


        // clear the table since the import has no remove facility
        ActionabilitySummary::query()->forceDelete();


        // DO THIS FOR ADULT - PEDS AFTER (CLEANUP IN FUTURE)
        unset($row);
        unset($all);
        $header = true;
        $count = 0;
        while ($row = fgetcsv($results_adult, 0, "\t")) {
            if ($header) {
                $header = false;
                $heading = $row;
            } else {
                $row;
            }
            if ($count) {
                $all[] = array_combine($heading, $row);
            }
            $count++;
        }

        $this->line('Building Adult Summary data rows');
        foreach ($all as $row) {
            $data = new ActionabilitySummary(['uuid' => Str::uuid()]);
            $data->docId                             = $row['# docId'];
            $data->iri                               = $row['topicIri'];
            $data->latestSearchDate                  = $row['latestSearchDate'];
            $data->lastUpdated                       = $row['lastUpdated'];
            $data->lastAuthor                        = $row['lastAuthor'];
            $data->context                           = $row['context'];
            $data->contextIri                        = $row['contextIri'];
            $data->release                           = $row['release'];
            $data->releaseDate                       = $row['releaseDate'];
            $data->gene                              = $row['gene'];
            $data->geneOmim                          = $row['geneOmim'];
            $data->disease                           = $row['disease'];
            $data->omim                              = $row['omim'];
            $data->status_overall                    = $row['status-overall'];
            $data->status_stg1                       = $row['status-stg1'];
            $data->status_stg2                       = $row['status-stg2'];
            $data->status_scoring                    = $row['status-scoring'];
            $data->outcome                           = $row['outcome'];
            $data->outcomeScoringGroup               = $row['outcomeScoringGroup'];
            $data->intervention                      = $row['intervention'];
            $data->interventionScoringGroup          = $row['interventionScoringGroup'];
            $data->severity                          = $row['severity'];
            $data->likelihood                        = $row['likelihood'];
            $data->natureOfIntervention              = $row['natureOfIntervention'];
            $data->effectiveness                     = $row['effectiveness'];
            $data->overall                           = $row['overall'];

            //dd($data);
            $data->save();
        }

        // DO THIS FOR PEDS - ADULT ABOVE (CLEANUP IN FUTURE)
        unset($row);
        unset($all);
        $header = true;
        $count = 0;
        while ($row = fgetcsv($results_peds, 0, "\t")) {
            if ($header) {
                $header = false;
                $heading = $row;
            } else {
                $row;
            }
            if ($count) {
                //var_dump($heading);
                //var_dump($row);
                $all[] = array_combine($heading, $row);
            }
            $count++;
        }

        $this->line('Building Peds Summary data rows');
        foreach ($all as $row) {
            $data = new ActionabilitySummary(['uuid' => Str::uuid()]);
            $data->docId                             = $row['# docId'];
            $data->iri                               = $row['topicIri'];
            $data->latestSearchDate                  = $row['latestSearchDate'];
            $data->lastUpdated                       = $row['lastUpdated'];
            $data->lastAuthor                        = $row['lastAuthor'];
            $data->context                           = $row['context'];
            $data->contextIri                        = $row['contextIri'];
            $data->release                           = $row['release'];
            $data->releaseDate                       = $row['releaseDate'];
            $data->gene                              = $row['gene'];
            $data->geneOmim                          = $row['geneOmim'];
            $data->disease                           = $row['disease'];
            $data->omim                              = $row['omim'];
            $data->status_overall                    = $row['status-overall'];
            $data->status_stg1                       = $row['status-stg1'];
            $data->status_stg2                       = $row['status-stg2'];
            $data->status_scoring                    = $row['status-scoring'];
            $data->outcome                           = $row['outcome'];
            $data->outcomeScoringGroup               = $row['outcomeScoringGroup'];
            $data->intervention                      = $row['intervention'];
            $data->interventionScoringGroup          = $row['interventionScoringGroup'];
            $data->severity                          = $row['severity'];
            $data->likelihood                        = $row['likelihood'];
            $data->natureOfIntervention              = $row['natureOfIntervention'];
            $data->effectiveness                     = $row['effectiveness'];
            $data->overall                           = $row['overall'];

            //dd($data);
            $data->save();
        }

        // DO THIS FOR ADULT Assertion- PEDS AFTER (CLEANUP IN FUTURE)
         // clear the table since the import has no remove facility
         ActionabilityAssertion::query()->forceDelete();
        unset($row);
        unset($all);
        $header = true;
        $count = 0;
        while ($row = fgetcsv($results_adult_assertion, 0, "\t")) {
            if ($header) {
                $header = false;
                $heading = $row;
            } else {
                $row;
            }
            if ($count) {
                $all[] = array_combine($heading, $row);
            }
            $count++;
        }

        //dd($heading);
        $this->line('Building Adult Assertion data rows');
        foreach ($all as $row)
        {
            $data = new ActionabilityAssertion([
                    'type' => ActionabilityAssertion::TYPE_ADULT,
                    'docid' => $row['# docId'],
                    'iri' => $row['iri'],
                    'latest_search_date' => $row['latestSearchDate'],
                    'last_updated' => $row['lastUpdated'],
                    'last_author' => $row['lastAuthor'],
                    'context' => $row['context'],
                    'contextiri' => $row['contextIri'],
                    'release' => $row['release'],
                    'gene' => $row['gene'],
                    'gene_omim' => $row['geneOmim'],
                    'disease' => $row['disease'],
                    'omim' => $row['omim'],
                    'mondo' => $row['mondo'],
                    'consensus_assertion' => $row['consensusAssertion'],
                    'status_assertion' => $row['status-assertion'],
                    'status_overall' => $row['status-overall'],
                    'status_stg1' => $row['status-stg1'],
                    'status' => 1
            ]);
            $data->save();

            //dd($row);
            if (stripos($row['consensusAssertion'], 'N/A - ') === 0)
            {
                $data = ActionabilitySummary::where([
                        ['contextIri', '=', $row['contextIri']],
                        ['geneOmim', '=', $row['geneOmim']],
                        ['disease', '=', $row['disease']]
                    ])->first();
            }
            else
            {
                $data = ActionabilitySummary::where([
                        ['contextIri', '=', $row['contextIri']],
                        ['geneOmim', '=', $row['geneOmim']],
                        ['disease', '=', $row['disease']],
                        ['omim', '=', $row['omim']]
                    ])->first();
            }
            if($data){
                $data->consensusAssertion                = $row['consensusAssertion'] ?? "";
                $data->save();
            }
            //dd($data);
        }

        // DO THIS FOR ADULT Assertion- PEDS AFTER (CLEANUP IN FUTURE)
        unset($row);
        unset($all);
        $header = true;
        $count = 0;
        while ($row = fgetcsv($results_peds_assertion, 0, "\t")) {
            if ($header) {
                $header = false;
                $heading = $row;
            } else {
                $row;
            }
            if ($count) {
                $all[] = array_combine($heading, $row);
            }
            $count++;
        }

        //dd($heading);
        $this->line('Building Peds Assertion data rows');
        foreach ($all as $row)
        {
            $data = new ActionabilityAssertion([
                    'type' => ActionabilityAssertion::TYPE_PEDIATRIC,
                    'docid' => $row['# docId'],
                    'iri' => $row['iri'],
                    'latest_search_date' => $row['latestSearchDate'],
                    'last_updated' => $row['lastUpdated'],
                    'last_author' => $row['lastAuthor'],
                    'context' => $row['context'],
                    'contextiri' => $row['contextIri'],
                    'release' => $row['release'],
                    'gene' => $row['gene'],
                    'gene_omim' => $row['geneOmim'],
                    'disease' => $row['disease'],
                    'omim' => $row['omim'],
                    'mondo' => $row['mondo'],
                    'consensus_assertion' => $row['consensusAssertion'],
                    'status_assertion' => $row['status-assertion'],
                    'status_overall' => $row['status-overall'],
                    'status_stg1' => $row['status-stg1'],
                    'status' => 1
            ]);
            $data->save();
            //echo implode(',', $row) . "\n";
            if (stripos($row['consensusAssertion'], 'N/A - ') === 0)
            {
                $data = ActionabilitySummary::where([
                    ['contextIri', '=', $row['contextIri']],
                    ['geneOmim', '=', $row['geneOmim']],
                    ['disease', '=', $row['disease']]
                    ])->first();
            }
            else
            {
                $data = ActionabilitySummary::where([
                    ['contextIri', '=', $row['contextIri']],
                    ['geneOmim', '=', $row['geneOmim']],
                    ['disease', '=', $row['disease']],
                    ['omim', '=', $row['omim']]
                    ])->first();
            }
            if ($data) {
                $data->consensusAssertion                = $row['consensusAssertion'] ?? "";
                $data->save();
            }
            else{
                //echo implode(',', $row) . "\n";
            }
        }





        return 0;
    }
}
