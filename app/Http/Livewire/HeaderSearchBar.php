<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Ahsan\Neo4j\Facade\Cypher;

use App\GeneLib;

class HeaderSearchBar extends Component
{
	public $searchBarQuery = null; 
	public $searchBarType = 'gene';
	public $queryResults = null; 


	public function render()
    {
        $type = $this->searchBarType;
        $term = $this->searchBarQuery;
        $query_results = $this->queryResults;
        $item = null;
        $collection = collect();

        // Sets how many characters needed before returning results
        $minQueryChars		= 2;
        
        if(strlen($term) >= $minQueryChars) {
	        switch($type) {
	          case 'gene':
				  $href_prefix = "/genes/";
				  $items = GeneLib::geneLook([	'page' =>  0,
				  								'pagesize' =>  "null",
												'sort' => 'GENE_LABEL',
												'direction' =>  'ASC',
												'search' =>  $term,
												'curated' => false ]);
					//dd($items);
	            break;
	          case 'disease':
				  $href_prefix = "/conditions/";
				  $items = GeneLib::conditionLook([	'page' =>  0,
				  								'pagesize' =>  "null",
												'sort' => 'GENE_LABEL',
												'direction' =>  'ASC',
												'search' =>  $term,
												'curated' => false ]);
	            break;
	          case 'drug':
				  $href_prefix = "/drugs/";
				  $items = GeneLib::drugLook([	'page' =>  0,
				  								'pagesize' =>  "null",
												'sort' => 'GENE_LABEL',
												'direction' =>  'ASC',
												'search' =>  $term,
												'curated' => false ]);
	            break;
	        }

	        if ($items !== null)
	        {
		    	$collection = $items->collection;
		      } else {
		      	$collection = collect();
		      }
		}
		else
			$collection = collect();
        //$collection->all();

        //$unique = $collection->unique('href');

				//$unique->values()->all();

		$this->queryResults  = $collection->toArray(); //  $unique->toArray();
		
        //dd($collection);
        return view('livewire.header-search-bar');
    }
}
