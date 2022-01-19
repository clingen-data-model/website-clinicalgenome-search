<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Curation;

class UpdateGCExpress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:gcexpress';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the system with gcexpress data';

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
        echo "Updating GC Express Data ...";


		try {

            $results = file_get_contents(base_path() . "/data/ClinGen-Gene-Expess-Data-01092020.json");

		} catch (\Exception $e) {

			echo "\n(E001) Error retrieving map data\n";
			return 0;

		}

		$data = json_decode($results);

		foreach ($data as $key => $record)
		{
            $curation = new Curation([
                                'type' => Curation::TYPE_GENE_VALIDITY,
                                'type_string' => 'Gene-Disease Validity',
                                'subtype' => 0,
                                'subtype_string' => null,
                                'group_id' => 0,
                                'sop_version' => empty($record->scoreJsonSerializedSop5) ? 4 : 5,
                                'source' => 'ClinGen-Gene-Express',
                                'source_uuid' => $key,
                                'assertion_uuid' => $record->report_id ?? null,
                                'alternate_uuid' => null,
                                'affiliate_id' => $record->affiliation->gcep_id ?? $record->affiliation->id,
                                'affiliate_details' => $record->affiliation,
                                'gene_hgnc_id' => reset($record->genes)->curie,
                                'gene_details' => $record->genes,
                                'title' => $record->title,
                                'summary' => null,
                                'description' => $record->label,
                                'comments' => null,
                                'conditions' => basename($record->conditions->MONDO->iri),
                                'condition_details' => $record->conditions,
                                'evidence' => null,
                                'evidence_details' => null,
                                'scores' => ['FinalClassification' => reset($record->scores)->label],
                                'score_details' => empty($record->scoreJsonSerializedSop5) ?
                                                        $record->scoreJsonSerialized :
                                                        $record->scoreJsonSerializedSop5,
                                'curators' => null,
                                'published' => true,
                                'animal_model_only' => false,
                                'contributors' => null,
                                'events' => ['PublishedDate' => $record->dateISO8601],
                                'version' => 1,
                                'status' => Curation::STATUS_ACTIVE
                            ]);

            $curation->save();

        }

        echo "DONE\n";

    }
}
