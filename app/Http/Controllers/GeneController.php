<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

//use Ahsan\Neo4j\Facade\Cypher;
use App\GeneLib;
use App\Nodal;

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
	* Display a listing of all genes.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index(Request $request, $page = 1, $psize = 100)
	{
		// process request args
		foreach ($request->only(['page', 'sort', 'search', 'direction']) as $key => $value)
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
/*
		$results = GeneLib::geneList([	'page' => $page - 1,
										'pagesize' => $psize,
										'sort' => $sort ?? 'GENE_LABEL',
										'direction' => $direction ?? 'ASC',
										'search' => $search ?? null,
										'curated' => false ]);

		if ($results === null)
			die(print_r(GeneLib::getError()));*/

		// customize the pagination.
		//$records = new LengthAwarePaginator($results->collection, $results->count, $psize, $page);
		//$records->withPath('genes');

		return view('gene.index', compact('display_tabs'))
		//				->with('records', $results->collection)
		//				->with('count', $results->count)
						->with('pagesize', $psize)
						->with('page', $page);
	}


	/**
	* Show all of the curated genes
	*
	* @return \Illuminate\Http\Response
	*/
	public function curated(Request $request, $page = 1, $psize = 200) //$psize = 2000)
	{
		// process request args
		foreach ($request->only(['page', 'sort', 'direction']) as $key => $value)
			$$key = $value;

		/* build caching of these values with cross-section updates
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

		$results = GeneLib::geneList([	'page' => $page - 1,
										'pagesize' => $psize,
										'sort' => $sort ?? 'GENE_LABEL',
										'direction' => $direction ?? 'ASC',
										'curated' => true ]);
		if ($results === null)
			die(print_r(GeneLib::getError()));

		// customize the pagination.
		$records = new LengthAwarePaginator($results->collection, $results->count, $psize, $page);
		$records->withPath('curations');

		return view('gene.curated', compact('display_tabs', 'records'))
						->with('count', $results->count);
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

			$record = GeneLib::geneDetail([ 
											'gene' => $id,
											'curations' => true,
											'action_scores' => true,
											'validity' => true,
											'dosage' => true
										]);

			if ($record === null)
			{
				die(print_r(GeneLib::getError()));
			}

			return view('gene.show', compact('display_tabs', 'record'));
		}


	/**
	 * Display the external resources section.  Since this is mostly static
	 * it wuld just get displayed in a tab with the gene.
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

		if ($record === null) {
			die(print_r(GeneLib::getError()));
		}

		return view('gene.show-external-resources', compact('display_tabs', 'record'));
	}
}
