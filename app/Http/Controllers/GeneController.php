<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\GeneListRequest;

use App\GeneLib;
use App\Gene;

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
            'active' => "gene",
            'title' => "Genes"
        ]);

		return view('gene.index', compact('display_tabs'))
						->with('apiurl', '/api/genes')
						->with('pagesize', $size)
						->with('page', $page);
	}


	/**
	 * Display a listing of all genes via HGNC Database.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function all()
	{
		// set display context for view
		$display_tabs = collect([
			'active' => "gene",
			'title' => "Listing of HGNC Genes"
		]);

		$all = Gene::Paginate(1000);

		return view('gene.all', compact('display_tabs', 'all'));
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
            'active' => "gene-curations",
            'title' => "ClinGen Curatated Genes"
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
	public function show_by_disease(Request $request, $id = null)
	{
		if ($id === null)
			return view('error.message-standard')
				->with('title', 'Error retrieving Gene details')
				->with('message', 'The system was not able to retrieve details for this Gene. Please return to the previous page and try again.')
				->with('back', url()->previous());


		$record = GeneLib::geneDetail([
										'gene' => $id,
										'curations' => true,
										'action_scores' => true,
										'validity' => true,
										'dosage' => true
									]);

		if ($record === null)
			return view('error.message-standard')
						->with('title', 'Error retrieving Gene details')
						->with('message', 'The system was not able to retrieve details for this Gene.  Error message was: ' . GeneLib::getError() . '. Please return to the previous page and try again.')
						->with('back', url()->previous());

		// set display context for view
		$display_tabs = collect([
			'active' => "gene",
			'title' => $record->label . " curation results"
		]);

		return view('gene.by-disease', compact('display_tabs', 'record'));
	}

	public function show_by_activity(Request $request, $id = null)
	{
		if ($id === null)
			return view('error.message-standard')
			->with('title', 'Error retrieving Gene details')
			->with('message', 'The system was not able to retrieve details for this Gene. Please return to the previous page and try again.')
			->with('back', url()->previous());


		$record = GeneLib::geneDetail([
			'gene' => $id,
			'curations' => true,
			'action_scores' => true,
			'validity' => true,
			'dosage' => true,
			'pharma' => true
		]);

		if ($record === null)
			return view('error.message-standard')
			->with('title', 'Error retrieving Gene details')
			->with('message', 'The system was not able to retrieve details for this Gene.  Error message was: ' . GeneLib::getError() . '. Please return to the previous page and try again.')
			->with('back', url()->previous());

		// set display context for view
		$display_tabs = collect([
			'active' => "gene",
			'title' => $record->label . " curation results"
		]);
			//dd($record);
		return view('gene.by-activity', compact('display_tabs', 'record'));
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
			return view('error.message-standard')
				->with('title', 'Error retrieving Gene details')
				->with('message', 'The system was not able to retrieve details for this Gene. Please return to the previous page and try again.')
				->with('back', url()->previous());


		$record = GeneLib::geneDetail([
										'page' => 0,
										'pagesize' => 200,
										'gene' => $id,
										'curations' => true,
										'action_scores' => true,
										'validity' => true,
										'dosage' => true
									]);

		if ($record === null)
			return view('error.message-standard')
						->with('title', 'Error retrieving Gene details')
						->with('message', 'The system was not able to retrieve details for this Gene.  Error message was: ' . GeneLib::getError() . '. Please return to the previous page and try again.')
						->with('back', url()->previous());

		// set display context for view
		$display_tabs = collect([
			'active' => "gene",
			'title' => $record->label . " external resources"
		]);
		return view('gene.show-external-resources', compact('display_tabs', 'record'));
	}


	/**
	* Display a listing of all genes.
	*
	* @return \Illuminate\Http\Response
	*/
	public function search(Request $request)
	{

		// process request args
		foreach ($request->only(['search']) as $key => $value)
			$$key = $value;

		// the way layouts is set up, everything is named search.  Gene is the first

		return redirect()->route('gene-index', ['page' => 1, 'size' => 50, 'search' => $search[0] ]);
	}
}
