<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Disease;
use App\Term;

class UpdateOrphanet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:orpha';

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

        echo "Updating Orphanet Disease Library from Orphanet ...";

        //$data = file_get_contents('http://purl.obolibrary.org/obo/mondo/mondo-with-equivalents.json');

        $data = file_get_contents(base_path() . '/data/en_product1.json');

        $json = json_decode($data);

        $nodes = $json->JDBOR[0]->DisorderList[0]->Disorder;

        foreach ($nodes as $node)
        {
            $term = $node->OrphaCode;
            $label = $node->Name[0]->label;

            //echo "updating $term \n";
//dd($node);
            $records = Disease::orpha($term)->get();

            foreach ($records as $record)
            {
                $record->orpha_label = $label;

                $record->save();

                if (empty($record->curation_activities))
                {
                    $stat = Term::updateOrCreate(['name' => $label, 'value' => $record->curie],
                                        ['alias' => $record->label,
                                        'type' => Term::TYPE_DISEASE_ORPHA, 'status -> 1']);
                }
                else
                {
                    $stat = Term::updateOrCreate(['name' => $label, 'value' => $record->curie],
                                        ['alias' => $record->label, 'curated' => 1, 'weight' => 2,
                                        'type' => Term::TYPE_DISEASE_ORPHA, 'status -> 1']);
                }
            }

            //mappings
            /*foreach ($node->ExternalReferenceList as $erl)
            {
                foreach ($erl->ExternalReference as $xref)
                {
                    if ($xref->Source == 'OMIM')
                    {
                        $omimid = $xref->Reference;
                    }
                }
            }*/

        }

        echo "DONE\n";


    }
}
