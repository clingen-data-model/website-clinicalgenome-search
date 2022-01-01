<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Disease;

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

        $data = file_get_contents('http://purl.obolibrary.org/obo/mondo/mondo-with-equivalents.json');

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
                    if (($n = strpos($property->val, '/omim/')) > 0)
                    {
                        $disease->omim = substr($property->val, $n + 6);
                    }
                }
            }

            $disease->save();
        }

        echo "DONE\n";


    }
}
