<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\GeneLib;
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
class ConditionController extends Controller
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
    public function index(Request $request, $page = 0, $psize = 20)
    {
		//if (is_int($page)) // don't forget to check the parms

		$display_tabs = collect([
				'active' => "condition",
				'query' => "",
				'counts' => [
					'dosage' => "1434",
					'gene_disease' => "500",
					'actionability' => "270",
					'variant_path' => "300"
				]
		]);

		$records = GeneLib::conditionList([	'page' => $page,
											'pagesize' => $psize
										]);

		//dd($records);
		if ($records === null)
			die("thow an error");

        return view('condition.index', compact('display_tabs', 'records'));
    }
    
    
    /**
	* Display the specified condition.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function show(Request $request, $id = null)
	{
		if ($id === null)
		die("display some error about needing a gene");

		$display_tabs = collect([
			'active' => "condition",
			'query' => "BRCA2",
			'counts' => [
				'dosage' => "1434",
				'gene_disease' => "500",
				'actionability' => "270",
				'variant_path' => "300"
				]
			]);

		$record = GeneLib::conditionDetail([ 'page' => 0,
										'pagesize' => 200,
										'condition' => $id,
										'curations' => true,
										'action_scores' => true,
										'validity' => true,
										'dosage' => true
										]);

		dd($record);
		if ($record === null)
		{
			GeneLib::errorDetail();
			// do something
			// return view
			die("thow an error");
		}
		
		return view('condition.show', compact('display_tabs', 'record'));
	}
}
