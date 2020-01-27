<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Ahsan\Neo4j\Facade\Cypher;

class GeneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    $display_tabs = collect([
            'active'                            => "gene",
            'query'                             => "",
            'counts'    => [
                'dosage'                        => "1434",
            'gene_disease'          => "500",
            'actionability'         => "270",
            'variant_path'          => "300"]
    ]);
        return view('gene.index', compact('display_tabs'));
    }

    /**
     * Show all of the curations related to genes
     * @return [type] [description]
     */
    public function curated()
    {

    // MATCH (g:Gene)
    // WHERE (g)<-[:has_subject]-(:Assertion)
    // RETURN g {.symbol, .hgnc_id,
    //   actionability: [(g)<-[:has_subject]-(a:ActionabilityAssertion) | a.uuid],
    //   actionability_link: [(g)<-[:has_subject]-(a:ActionabilityAssertion) | a.file],
    //   validity: [(g)<-[:has_subject]-(a:GeneDiseaseAssertion)-[:has_predicate]->(i:Interpretation) | i.label],
    //   validity_link: [(g)<-[:has_subject]-(a:GeneDiseaseAssertion) | a.perm_id],
    //   dosage: [(g)<-[:has_subject]-(a:GeneDosageAssertion)-[:has_predicate]->(i:Interpretation) | i {.iri, .short_label}]}
    // ORDER BY           g.symbol
    // LIMIT 2000';
    //       
     $query = '
         MATCH (g:Gene)
         WHERE (g)<-[:has_subject]-(:Assertion)
         RETURN g {.symbol, .hgnc_id,
           actionability: [(g)<-[:has_subject]-(a:ActionabilityAssertion) | a.uuid],
           validity: [(g)<-[:has_subject]-(a:GeneDiseaseAssertion)-[:has_predicate]->(i:Interpretation) | i.label],
           dosage: [(g)<-[:has_subject]-(a:GeneDosageAssertion)-[:has_predicate]->(i:Interpretation) | i {.iri, .short_label}]
            }
         ORDER BY           g.symbol
         LIMIT 2000';

    $items = Cypher::run($query);

    //echo "<pre>";
    //print_r($items->records());
    //die();
    $collection = collect();
    
    //echo "<pre>";
     foreach($items->records() as $item) {

         //print_r($item->value('g')['dosage'][0]);
         //echo "<br/>";
         //die();
         
         $collect = (object)[
                 'label'            => $item->value('g')['symbol'],
                 'href'             => $item->value('g')['hgnc_id'],
                 'actionability'    => $item->value('g')['actionability'],
                 'validity'         => $item->value('g')['validity'],
                 'dosage'           => $item->value('g')['dosage'],
         ];

         //$collect = $item
         //$collect = collect($collect);
         $collection->push($collect);
         //print_r($items);
         //print_r($item);
         //echo "<br/>";
         //die();
      }
      //die();
      $collection->all();
      //dd($collection);
      

    $display_tabs = collect([
            'active'                            => "gene",
            'query'                             => "",
            'counts'    => [
                'dosage'                        => "1434",
            'gene_disease'          => "500",
            'actionability'         => "270",
            'variant_path'          => "300"]
    ]);
        return view('gene.curated', compact('display_tabs', 'collection'));

    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    $display_tabs = collect([
            'active'                            => "gene",
            'query'                             => "BRCA2",
            'counts'    => [
                'dosage'                        => "434",
                'gene_disease'                  => "500",
                'actionability'                 => "270",
                'variant_path'                  => "300"
            ]
    ]);
        return view('gene.show', compact('display_tabs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
}
