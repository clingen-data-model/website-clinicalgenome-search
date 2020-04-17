<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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
	public function index(Request $request, $page = 1, $psize = 250)
	{
		// process request args
		foreach ($request->only(['page', 'sort', 'direction']) as $key => $value)
			$$key = $value;

		/* build cqching of these values with cross-section updates
		 * total counts for gene and diseases on relevant pages
		 * category would be for setting default select of dropdown */
		$display_tabs = collect([
			'active' => "gene",
			'query' => "",
			'category' => "",
			'counts' => [
				'total' => 'something',
				'dosage' => "1434",
				'gene_disease' => "500",
				'actionability' => "270",
				'variant_path' => "300"
			]
		]);

		$records = GeneLib::geneList([	'page' => $page - 1,
										'pagesize' => $psize,
										'sort' => $sort ?? 'symbol',
										'direction' => $direction ?? 'asc',
										'curated' => false ]);

		//dd($records);
		if ($records === null)
			die("thow an error");

		// customize the pagination.
		$records = new LengthAwarePaginator($records, 1500, $psize, $page);
		$records->withPath('genes');

		return view('gene.index', compact('display_tabs', 'records'));
	}

	/**
	* Show all of the curations related to genes
	* @return [type] [description]
	*/
	public function curated()
	{
		//dd("dfsdfsdfdsf");
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
				'hgnc_id'          => $item->value('g')['hgnc_id'],
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
			'active'                            => "gene-curations",
			'query'                             => "",
			'counts'    => [
				'dosage'                        => "1434",
				'gene_disease'          => "500",
				'actionability'         => "270",
				'variant_path'          => "300"
			]
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
			'pagesize' => 200,
			'gene' => $id,
			'curations' => true,
			'action_scores' => true,
			'validity' => true,
			'dosage' => true
			]);

			//dd($record);
			if ($record === null)
			{
				GeneLib::errorDetail();
				// do something
				// return view
				die("thow an error");
			}

			//dd($record);
			return view('gene.show', compact('display_tabs', 'record'));
		}

	/**
	 * External resource show
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function external(Request $request, $id = null)
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

		$record = GeneLib::geneDetail([
			'page' => 0,
			'pagesize' => 200,
			'gene' => $id,
			'curations' => true,
			'action_scores' => true,
			'validity' => true,
			'dosage' => true
		]);

		//dd($record);
		if ($record === null) {
			GeneLib::errorDetail();
			// do something
			// return view
			die("thow an error");
		}

		//dd($record);
		return view('gene.show-external-resources', compact('display_tabs', 'record'));
	}
	}
