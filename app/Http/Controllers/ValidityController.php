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
    public function index(Request $request, $page = 0, $psize = 20)
    {
		//if (is_int($page)) // don't forget to check the parms
			
		$display_tabs = collect([
				'active' => "gene",
				'query' => "",
				'counts' => [
					'dosage' => "1434",
					'gene_disease' => "500",
					'actionability' => "270",
					'variant_path' => "300"
				]
		]);
		
		$records = GeneLib::validityList([	'page' => $page, 
											'pagesize' => $psize
										]);
		
		dd($records);
		if ($records === null)
			die("thow an error");
								
        return view('validity.index', compact('display_tabs', 'records'));
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
				'active' => "gene",
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
		
		// what the old views use, just trying to understand whats needed for now
		if (empty($record->n->score_string))
			$score_string_sop5 = json_decode($record->n->score_string_sop5);
		else
			$score_string = json_decode($record->n->score_string);
					
		$assertion = $record->n;
		
		$geneSymbol = $record->gene_symbol->value('result_genes')->value('symbol');
				
		$diseaseName = $record->disease_name->value('result_diseases')->value('label');
		
		//$geneCurie = $record->gene_curie->value('result_genes')->value('curie');
		
		$diseaseCurie = $record->disease_curie->value('result_diseases')->value('curie');
		
		$animalmode = false;
		
		//dd($score_string_sop5->condition);
		
		
		if ($record === null)
			die("thow an error");
			
		
			
		// dd($record);
        return view('validity.show', compact('display_tabs', 'record',
			'score_string_sop5', 'score_string', 'assertion', 'geneSymbol',
			'diseaseName', 'geneCurie', 'diseaseCurie', 'animalmode' ));
    }
}
