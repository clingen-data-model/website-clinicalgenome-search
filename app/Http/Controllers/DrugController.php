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
    public function index(Request $request, $page = 0, $size = 6000)
    {
		// process request args
		foreach ($request->only(['page', 'size', 'order', 'sort', 'search']) as $key => $value)
			$$key = $value;
			
        // set display context for view
        $display_tabs = collect([
            'active' => "drug"
        ]);

		return view('drug.index', compact('display_tabs'))
						->with('apiurl', '/api/drugs')
						->with('pagesize', $size)
						->with('page', $page);
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
			die("display some error about needing a drug");

		// set display context for view
        $display_tabs = collect([
            'active' => "drug"
        ]);

		$record = GeneLib::drugDetail([ 'drug' => $id ]);

		if ($record === null)
		{
			die(print_r(GeneLib::getError()));
		}
		
        return view('drug.show', compact('display_tabs', 'record'));
    }
}
