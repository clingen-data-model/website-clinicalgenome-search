<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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
        //if (is_int($page)) // don't forget to check the parms

		/* build cqching of these values with cross-section updates 
		 * total counts for gene and diseases on relevant pages 
		 * category would be for setting default select of dropdown */
		$display_tabs = collect([
			'active' => "drug",
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

		$display_tabs = collect([
			'active' => "drug",
			'query' => " ",
			'counts' => [
				'dosage' => "1434",
				'gene_disease' => "500",
				'actionability' => "270",
				'variant_path' => "300"
				]
			]);

		$record = GeneLib::drugDetail([ 'page' => 0,
										'pagesize' => 200,
										'drug' => $id,
										'curations' => true,
										'action_scores' => true,
										'validity' => true,
										'dosage' => true
									]);

		if ($record === null)
		{
			//GeneLib::errorDetail();
			// do something
			// return view
			die("thow an error");
		}
		
        return view('drug.show', compact('display_tabs', 'record'));
    }
}
