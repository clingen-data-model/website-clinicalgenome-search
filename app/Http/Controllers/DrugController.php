<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
class DrugController extends Controller
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
	* Display a listing of drugs.
	*
	* @return \Illuminate\Http\Response
	*/
    public function index(Request $request, $page = 0, $size = 50, $search="")
    {
		// process request args
		foreach ($request->only(['page', 'size', 'order', 'sort', 'search']) as $key => $value)
			$$key = $value;

        // set display context for view
        $display_tabs = collect([
            'active' => "drug",
            'title' => "Drugs"
        ]);

		return view('drug.index', compact('display_tabs'))
						->with('apiurl', '/api/drugs')
						->with('pagesize', $size)
						->with('page', $page)
						->with('search', $search);
    }


    /**
     * Display a specified drug.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id = null)
    {
		if ($id === null)
			return view('error.message-standard')
				->with('title', 'Error retrieving Drug details')
				->with('message', 'The system was not able to retrieve details for this Drug. Please return to the previous page and try again.')
				->with('back', url()->previous());

		$record = GeneLib::drugDetail([ 'drug' => $id ]);

		if ($record === null)
			return view('error.message-standard')
						->with('title', 'Error retrieving Drug details')
						->with('message', 'The system was not able to retrieve details for this Drug.  Error message was: ' . GeneLib::getError() . '. Please return to the previous page and try again.')
						->with('back', url()->previous());

		// set display context for view
		$display_tabs = collect([
			'active' => "drug",
			'title' => $record->label . " drug information"
		]);
		
        return view('drug.show', compact('display_tabs', 'record'));
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

		// the way layouts is set up, everything is named search.  Drug is the third
		
		return redirect()->route('drug-index', ['page' => 1, 'size' => 50, 'search' => $search[2] ]);
	}
}
