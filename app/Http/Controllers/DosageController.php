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
class DosageController extends Controller
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
     * Display a listing of curated genes with a dosage sensitivity.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $page = 1, $psize = 100)
    {
        
        //if (is_int($page)) // don't forget to check the parms

		$display_tabs = collect([
			'active' => "dosage",
			'query' => "",
			'counts' => [
				'dosage' => "1434",
				'gene_disease' => "500",
				'actionability' => "270",
				'variant_path' => "300"
			]
		]);
		
		$records = GeneLib::dosageList(['page' => $page - 1,
										'pagesize' => $psize,
										'sort' => $sort ?? 'symbol',
										'direction' => $direction ?? 'asc',
										'curated' => true ]);

		if ($records === null)
			die(print_r(GeneLib::getError()));

		// customize the pagination.
		//$records = new LengthAwarePaginator($records, 1500, $psize, $page);
		//$records->withPath('genes');

		return view('gene-dosage.index', compact('display_tabs', 'records'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id = '')
    {
		$display_tabs = collect([
				'active'                            => "gene",
				'query'                             => "BRCA2",
				'counts'    => [
					'dosage'                        => "1434",
					'gene_disease'                  => "500",
					'actionability'                 => "270",
					'variant_path'                  => "300"
				]
		]);
    
        return view('gene-dosage.show', compact('display_tabs'));
    }

}
