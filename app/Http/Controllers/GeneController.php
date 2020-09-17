<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\GeneListRequest;

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
	public function index(GeneListRequest $request, $page = 1, $size = 100)
	{
		// process request args
		foreach ($request->only(['page', 'size', 'order', 'sort', 'search']) as $key => $value)
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

		return view('gene.index', compact('display_tabs'))
						->with('apiurl', '/api/genes')
						->with('pagesize', $size)
						->with('page', $page);
	}


	/**
	* Show all of the curated genes
	*
	* @return \Illuminate\Http\Response
	*/
	public function curated(GeneListRequest $request, $page = 1, $size = 200)
	{
		// process request args
		foreach ($request->only(['page', 'size', 'order', 'sort', 'search']) as $key => $value)
			$$key = $value;

		/* build caching of these values with cross-section updates
		 * total counts for gene and diseases on relevant pages
		 * category would be for setting default select of dropdown */
		$display_tabs = collect([
			'active' => "gene-curations",
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

		return view('gene.curated', compact('display_tabs'))
						->with('apiurl', '/api/curations')
						->with('pagesize', $size)
						->with('page', $page);
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
