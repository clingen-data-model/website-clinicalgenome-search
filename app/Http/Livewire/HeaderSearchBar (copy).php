<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Ahsan\Neo4j\Facade\Cypher;

class HeaderSearchBar extends Component
{
		public $searchBarQuery 		= null; 
		public $searchBarType 		= 'gene';
		public $queryResults 			= null; 


		public function render()
    {
        $type       			= $this->searchBarType;
        $term       			= $this->searchBarQuery;
        $query_results		= $this->queryResults;
        $item 						= null;
        $collection 			= collect();

        // Sets how many characters needed before returning results
        $minQueryChars		= 2;
        
        //dd($type);
          //return $gene;
        // try {
        //   if(!$type OR !$term) {
        //     abort(403, 'Sorry, you didn\'t send enough information.');
        //   }
        // } catch (Exception $e) {
        //       //report($e);
        //   return false;
        // }
        //dd($term);
        //
        //
        
        if(strlen($term) >= $minQueryChars) {
	        switch($type) {

	          // Query all of the gene nodes
	          // The FIRST match/where finds the symbols that match the term in the beginning
	          // The SECOND match/where finds the symbols that match the term anywhere
	          // Using (?i) makes sure it is case intensive
	          // The UNION makes sure the duplicates are removed.
	          case 'gene':
	          	$href_prefix = "/genes/";
	            $query = "
	            MATCH               (n:Gene) 
	            WHERE               n.symbol =~ '(?i){$term}.*'
	            OPTIONAL MATCH      (n)<-[:has_subject]-(a:Assertion)
	            RETURN              n,
	            n.iri               AS iri,
	            n.symbol            AS label,
	            n.symbol            AS symbol,
	            n.definition        AS definition,
	            n.hgnc_id           AS curie,
	            n.hgnc_id           AS ref,
	            n.hgnc_id           AS href,
	            a.uuid              AS assertionUuid
	            ORDER BY            n.label, a.uuid
	            SKIP                0
	            LIMIT               25

	            UNION

	            MATCH               (n:Gene) 
	            WHERE               n.symbol =~ '(?i).*{$term}.*'
	            OPTIONAL MATCH      (n)<-[:has_subject]-(a:Assertion)
	            RETURN              n,
	            n.iri               AS iri,
	            n.symbol            AS label,
	            n.symbol            AS symbol,
	            n.definition        AS definition,
	            n.hgnc_id           AS curie,
	            n.hgnc_id           AS ref,
	            n.hgnc_id           AS href,
	            a.uuid              AS assertionUuid
	            ORDER BY            n.label, a.uuid
	            SKIP                0
	            LIMIT               25
	            ";
	            break;

	          // Query all of the disease nodes
	          // The FIRST match/where finds the labels that match the term in the beginning
	          // The SECOND match/where finds the labels that match the term anywhere
	          // Using (?i) makes sure it is case intensive
	          // The UNION makes sure the duplicates are removed.
	          case 'disease':
	          	$href_prefix = "/disease/show/";
	            $query = "
	            MATCH               (n:Disease) 
	            WHERE               n.label =~ '(?i){$term}.*'
	            OPTIONAL MATCH      (n)-[:has_object]-(a:Assertion)
	            RETURN              n,
	            n.iri               AS iri,
	            n.label             AS label,
	            n.label             AS symbol,
	            n.definition        AS definition,
	            n.curie             AS curie,
	            n.id                AS ref,
	            n.id                AS href,
	            a.uuid              AS assertionUuid
	            ORDER BY            n.label
	            SKIP                0
	            LIMIT               25

	            UNION

	            MATCH               (n:Disease) 
	            WHERE               n.label =~ '(?i).*{$term}.*'
	            OPTIONAL MATCH      (n)-[:has_object]-(a:Assertion)
	            RETURN              n,
	            n.iri               AS iri,
	            n.label             AS label,
	            n.label             AS symbol,
	            n.definition        AS definition,
	            n.curie             AS curie,
	            n.id                AS ref,
	            n.id                AS href,
	            a.uuid              AS assertionUuid
	            ORDER BY            n.label
	            SKIP                0
	            LIMIT               25
	            ";
	            break;

	          // Query all of the disease nodes
	          // The FIRST match/where finds the labels that match the term in the beginning
	          // The SECOND match/where finds the labels that match the term anywhere
	          // Using (?i) makes sure it is case intensive
	          // The UNION makes sure the duplicates are removed.
	          case 'drug':
	          	$href_prefix = "/drug/show/";
	            $query = "
	            MATCH               (n:Drug) 
	            WHERE               n.label =~ '(?i){$term}.*'
	            OPTIONAL MATCH      (n)<-[:has_object]-(a:Assertion)
	            RETURN              n,
	            n.iri               AS iri,
	            n.label             AS label,
	            n.label             AS symbol,
	            n.definition        AS definition,
	            n.curie             AS curie,
	            n.curie             AS ref,
	            n.curie             AS href,
	            a.uuid              AS assertionUuid
	            ORDER BY            n.label
	            SKIP                0
	            LIMIT               25

	            UNION

	            MATCH               (n:Drug) 
	            WHERE               n.label =~ '(?i).*{$term}.*'
	            OPTIONAL MATCH      (n)<-[:has_object]-(a:Assertion)
	            RETURN              n,
	            n.iri               AS iri,
	            n.label             AS label,
	            n.label             AS symbol,
	            n.definition        AS definition,
	            n.curie             AS curie,
	            n.curie             AS ref,
	            n.curie             AS href,
	            a.uuid              AS assertionUuid
	            ORDER BY            n.label
	            SKIP                0
	            LIMIT               25
	            ";
	            break;

	        }

	        $items = Cypher::run($query);

	        //dd($items);
	        
	        
	          //echo "<pre>";
	        if($items) {
		        foreach($items->records() as $item) {

		          $curated = ($item->value('assertionUuid') === null ? false : true);

		          $collect = (object)[
		                       //'iri'              => $item->value('iri'),
		            'label'             => $item->value('label'),
		            'href'              => $href_prefix."".$item->value('href'),
		            'curated'           => $curated
		          ];

		          $collection->push($collect);
		        }
		      } else {
		      	$collection = false;
		      }
	      }
        $collection->all();

        $unique = $collection->unique('href');

				$unique->values()->all();

        $this->queryResults  = $unique->toArray();

        //dd($collection);
        return view('livewire.header-search-bar');
    }
}
