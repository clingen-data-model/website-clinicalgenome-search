<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel as Gexcel;

use App\Imports\Excel;
use App\Exports\ValidityExport;

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
 * @since      Class available since Release 1.0.0
 *
 * */
class ValidityController extends Controller
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
     * Display a listing of all gene validity assertions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $page = 1, $size = 100)
    {
		// process request args
		foreach ($request->only(['page', 'size', 'order', 'sort', 'search']) as $key => $value)
			$$key = $value;
			
		// set display context for view
        $display_tabs = collect([
            'active' => "validity"
        ]);

		return view('gene-validity.index', compact('display_tabs'))
						->with('apiurl', '/api/validity')
						->with('pagesize', $size)
						->with('page', $page);
    }


    /**
     * Display the specific gene validity report.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id = null)
    {
		if ($id === null)
			die("display some error about needing an id");

		// set display context for view
        $display_tabs = collect([
            'active' => "validity"
        ]);

		$record = GeneLib::validityDetail(['page' => 0,
										'pagesize' => 20,
										'perm' => $id
										 ]);

		if ($record === null)
			die("thow an error");

        return view('gene-validity.show', compact('display_tabs', 'record'));
	}
	

	/**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request)
    {
		return Gexcel::download(new ValidityExport, 'Clingen-Gene-Disease-Summary.csv');
    }
}
