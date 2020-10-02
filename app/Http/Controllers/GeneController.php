<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\GeneListRequest;

use App\GeneLib;

/**
*
* @category   Web
* @package    Search
* @author     P. Weller <pweller1@geisinger.edu>
* @author     S. Goehringer <scottg@creationproject.com>
* @copyright  2020 ClinGen
* @license    http://www.php.net/license/3_01.txt  PHP License 3.01
* @version    Release: @package_version@
* @link       http://pear.php.net/package/PackageName
* @see        NetOther, Net_Sample::Net_Sample()
* @since      Class available since Release 1.2.0
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

		// set display context for view
        $display_tabs = collect([
            'active' => "gene"
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

		// set display context for view
        $display_tabs = collect([
            'active' => "gene-curations"
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

		// set display context for view
		$display_tabs = collect([
			'active' => "gene"
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

		// set display context for view
		$display_tabs = collect([
			'active' => "gene"
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
