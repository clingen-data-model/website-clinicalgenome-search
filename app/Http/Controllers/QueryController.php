<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Ahsan\Neo4j\Facade\Cypher;

class QueryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function typeahead($type = null, Request $term)
    {
      
      $type       = $type;
      $term       = $term->q;
      
        //return $gene;
      try {
        if(!$type OR !$term) {
          abort(403, 'Sorry, you didn\'t send enough information.');
        }
      } catch (Exception $e) {
            //report($e);
        return false;
      }
      //dd($term);
      //
      switch($type) {

        // Query all of the gene nodes
        // The FIRST match/where finds the symbols that match the term in the beginning
        // The SECOND match/where finds the symbols that match the term anywhere
        // Using (?i) makes sure it is case intensive
        // The UNION makes sure the duplicates are removed.
        case 'gene':
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
          ORDER BY            n.label
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
        case 'disease':
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
      $collection = collect();
      
        //echo "<pre>";
      foreach($items->records() as $item) {

        $curated = ($item->value('assertionUuid') === null ? false : true);

        $collect = (object)[
                     //'iri'              => $item->value('iri'),
          'label'             => $item->value('label'),
          'ref'               => $item->value('href'),
          'curated'           => $curated
        ];

        $collection->push($collect);
      }

      $collection->all();
          //dd($collection);
      return response($collection);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

  }
