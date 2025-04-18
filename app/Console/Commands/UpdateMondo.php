<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Disease;
use App\Term;

class UpdateMondo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:mondo';

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

        /*
        +"id": "http://purl.obolibrary.org/obo/MONDO_0002974"
  +"meta": {#2072
    +"definition": {#2060
      +"val": "A primary or metastatic malignant neoplasm involving the cervix."
      +"xrefs": array:1 [
        0 => "NCIT:C9311"
      ]
    }
    +"xrefs": array:10 [
      0 => {#2073
        +"val": "ICD9:180.8"
      }
      1 => {#2074
        +"val": "ICD9:180"
      }
      2 => {#2075
        +"val": "COHD:198984"
      }
      ]
    +"synonyms": array:28 [
      0 => {#2083
        +"pred": "hasExactSynonym"
        +"val": "malignant neoplasm of cervix uteri"
        +"xrefs": array:1 [
          0 => "NCIT:C9311"
        ]
      }
      1 => {#2084
        +"pred": "hasExactSynonym"
        +"val": "cervix uteri cancer"
        +"xrefs": array:1 [
          0 => "DOID:4362"
        ]
      }
      2 => {#2085
        +"pred": "hasExactSynonym"
        +"val": "malignant cervix uteri tumor"
        +"xrefs": array:1 [
          0 => "NCIT:C9311"
        ]
      }
      +"basicPropertyValues": array:13 [
      0 => {#2111
        +"pred": "http://www.w3.org/2004/02/skos/core#closeMatch"
        +"val": "http://identifiers.org/snomedct/188186008"
      }
      1 => {#2112
        +"pred": "http://www.w3.org/2004/02/skos/core#exactMatch"
        +"val": "http://purl.obolibrary.org/obo/NCIT_C9311"
      }
      2 => {#2113
        +"pred": "http://www.w3.org/2004/02/skos/core#closeMatch"
        +"val": "http://identifiers.org/snomedct/123841004"
      }



        */

        echo "Updating MONDO Disease Library from Monarch ...";

        try {

          //$data = file_get_contents('http://purl.obolibrary.org/obo/mondo/mondo-with-equivalents.json');
          $data = file_get_contents('http://purl.obolibrary.org/obo/mondo/mondo.json');


        } catch (\Exception $e) {
      
          echo "\n(E001) Error accessing MONDO file\n";
          return;
        }

        $json = json_decode($data);

        $nodes = $json->graphs[0]->nodes;

        //Disease::truncate();

        foreach ($nodes as $node)
        {
            $term = basename($node->id);
            $term = str_replace('_', ':', $term);

            //echo "$term \n";
            if (strpos($term, 'MONDO') !== 0)
                continue;

            //if ($term == "MONDO:0008380")
             //   dd($node);

            $disease = Disease::curie($term)->first();

            if ($disease === null)
            {
                // create new record
                $disease = new Disease(['curie' => $term, 'type' => 1, 'status' => 1]);
            }

            // check if deprecated
            if (isset($node->meta->deprecated) &&  $node->meta->deprecated === true)
            {
                if (!isset($node->lbl))
                    continue;

                if ($disease->status != Disease::STATUS_GG_DEPRECATED)
                    $disease->status = Disease::STATUS_DEPRECATED;

                if (strpos($node->lbl, 'obsolete ') === 0)
                    $disease->label = substr($node->lbl, 9);
                else
                    $disease->label = $node->lbl ?? '';
            }
            else
            {
                $disease->label = $node->lbl ?? '';
            }

            $disease->description = $node->meta->definition->val ?? '';

            // we need to clear out all the quick refs before assigning new ones
            foreach (['omim', 'omim_label', 'orpha_id', 'orpha_label', 'do_id', 'medgen_id', 'gard_id', 'umls_id'] as $field)
            {
              $disease->$field = null;
            }

            $synonyms = [];
            if (isset($node->meta->synonyms))
            {
                foreach ($node->meta->synonyms as $synonym)
                {
                    $synonyms[] = $synonym->val;
                }
            }
            $disease->synonyms = $synonyms;

            // see if there is an omim equivalent
            if (isset($node->meta->basicPropertyValues))
            {
                foreach ($node->meta->basicPropertyValues as $property)
                {
                    if (($n = strpos($property->val, '/omim.org/entry/')) > 0)
                    {
                        $disease->omim = substr($property->val, $n + 16);
                    }
                }
            }

            // get all cross references
            if (isset($node->meta->xrefs))
            {
                foreach ($node->meta->xrefs as $property)
                {
                    $val = explode(':', $property->val);

                    switch ($val[0])
                    {
                        case 'DOID':
                            $disease->do_id = $val[1];
                            break;
                        case 'OMIMPS':
                        case 'OMIM':
                            $disease->omim = $val[1];
                            break;
                        case 'Orphanet':
                            $disease->orpha_id = $val[1];
                            break;
                        case 'GARD':
                            $disease->gard_id = $val[1];
                            break;
                        case 'UMLS':
                            $disease->umls_id = $val[1];
                            break;
                    }
                }
            }

            $disease->save();

           // echo "adding $disease->curie to term \n";

            // update the term Library
            $stat = Term::updateOrCreate(['name' => $disease->label, 'value' => $disease->curie],
                                        ['type' => Term::TYPE_DISEASE_NAME, 'status' => 0]);

            // retrieve current list of terms so obsolete ones can be removed
            $current_terms = Term::where('value', $disease->curie)->pluck('name')->toArray();
            $new_terms = [];

            foreach ($synonyms as $synonym)
            {
                $stat = Term::updateOrCreate(['name' => $synonym, 'value' => $disease->curie],
                                        ['alias' => $disease->label, 'type' => Term::TYPE_DISEASE_SYN, 'status' => 0]);

                $new_terms[] = $synonym;
            }

            // remove obsolete tems
            foreach (array_diff($current_terms, $new_terms) as $term)
            {
                Term::name($term)->where('value', $disease->curie)->delete();
            }

        }

        echo "DONE\n";


    }
}
