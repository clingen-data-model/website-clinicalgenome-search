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
				'active' => "validity",
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

		//dd($records);
		if ($records === null)
			die("thow an error");

        return view('gene-validity.index', compact('display_tabs', 'records'));
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

		//dd($record->score_data->ExperimentalEvidence->ModelsRescue->PointsCounted);
		//$assertion = $record->n;
		//dd($record->score_data->GeneticEvidence);
		// if (empty($assertion->score_string_gci)){
		// 	$score_json = json_decode($assertion->score_string_gci);
		// 	if ($assertion->jsonMessageVersion == "GCI.7") {
		// 	} elseif ($assertion->jsonMessageVersion == "GCI.6") {
		// 		$score_sop = "SOP6";
		// 	} elseif ($assertion->jsonMessageVersion == "GCI.5") {
		// 		$score_sop = "SOP5";
		// 	}
		// } elseif (empty($assertion->score_string_sop5)) {
		// 	$score_json = json_decode($assertion->score_string_sop5);
		// 	$score_sop = "SOP5-sop5";
		// } else {
		// 	$score_json = json_decode($assertion->score_string);
		// 	$score_sop = "SOP4";
		// }


		// $geneSymbol = $record->symbol->value('result_genes')->value('symbol');
		// $geneCurie = $record->symbol->value('result_genes')->value('symbol');

		// $diseaseName = $record->disease_name->value('result_diseases')->value('label');

		// //$geneCurie = $record->gene_curie->value('result_genes')->value('curie');

		// $diseaseCurie = $record->disease_curie->value('result_diseases')->value('curie');

		//$animalmode = false;

		//dd($score_string_sop5->condition);


		if ($record === null)
			die("thow an error");



		 //dd($record['score_data_array']['GeneticEvidence']['CaseLevelData']['VariantEvidence']['AutosomalDominantDisease']['ProbandWithNon-LOF']['pmid']);
		 //dd($score_json);
		 //dd($score_sop);
        return view('gene-validity.show', compact('display_tabs', 'record'));
    }
}
