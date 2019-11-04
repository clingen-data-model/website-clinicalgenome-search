<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Collection;
use Ahsan\Neo4j\Facade\Cypher;

use App\GeneLib;

/**
 * 
 * @category   Web
 * @package    Search
 * @author     P. Weller <pweller1@geisinger.edu>
 * @author     S. Goehringer <scottg@creationproject.com>
 * @copyright  2019 ClinGen
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      Class available since Release 1.2.0
 * @deprecated 
 * 
 * */
class GeneController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {		
        //$this->middleware('auth');
    }
    
    
    /**
     * Display a listing of all curated genes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $page = 0, $psize = 20)
    {
		//if (is_int($page)) // don't forget to check the parms
			
		$display_tabs = collect([
				'active' => "gene",
				'query' => "",
				'counts' => [
					'dosage' => "1434",
					'gene_disease' => "500",
					'actionability' => "270",
					'variant_path' => "300"
				]
		]);
		
		$records = GeneLib::geneList([	'page' => $page, 
										'pagesize' => $psize,
										'curated' => false ]);
		
		//dd($records);
		if ($records === null)
			die("thow an error");
								
        return view('gene.index', compact('display_tabs', 'records'));
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
     * Display the specified gene.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id = null)
    {
		if ($id === null)
			die("display some error about needing a gene");
					
		$display_tabs = collect([
				'active' => "gene",
				'query' => "BRCA2",
				'counts' => [
					'dosage' => "1434",
					'gene_disease' => "500",
					'actionability' => "270",
					'variant_path' => "300"
				]
		]);
    
		$record = GeneLib::geneDetail(['page' => 0, 
										'pagesize' => 20,
										'gene' => $id,
										'curations' => true,
										'action_scores' => true,
										'validity' => true,
										'dosage' => true ]);
							
		//dd($record);
		if ($record === null)
			die("thow an error");
			
        return view('gene.show', compact('display_tabs', 'record'));
    }
}
