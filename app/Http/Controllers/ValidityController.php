<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

use Maatwebsite\Excel\Facades\Excel as Gexcel;

use App\Imports\Excel;
use App\Exports\ValidityExport;

use App\GeneLib;
use App\Nodal;
use App\Helper;

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
		//if (is_int($page)) // don't forget to check the parms

		$display_tabs = collect([
				'active' => "validity",
				'query' => "",
				'counts' => [
					'dosage' => "1434",
					'gene_disease' => "500",
					'actionability' => "270",
					'variant_path' => "300"
				]
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

		$display_tabs = collect([
				'active' => "validity",
				'query' => "BRCA2",
				'counts' => [
					'dosage' => "1434",
					'gene_disease' => "500",
					'actionability' => "270",
					'variant_path' => "300"
				]
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
