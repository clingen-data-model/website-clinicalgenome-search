<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel as Gexcel;

use App\Imports\Excel;
use App\Exports\DosageExport;

use App\GeneLib;
use App\Jira;

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
    public function index(Request $request, $page = 1, $size = 100)
    {
        // process request args
		foreach ($request->only(['page', 'size', 'sort', 'search', 'direction']) as $key => $value)
			$$key = $value;

		// set display context for view
        $display_tabs = collect([
            'active' => "dosage",
            'title' => "Dosage Sensitivity Curations"
        ]);

		return view('gene-dosage.index', compact('display_tabs'))
		//				->with('count', $results->count)
						->with('apiurl', '/api/dosage')
						->with('pagesize', $size)
						->with('page', $page);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id = '')
    {

		$record = GeneLib::dosageDetail([ 'gene' => $id,
										'curations' => true,
										'action_scores' => true,
										'validity' => true,
										'dosage' => true
									]);

		if ($record === null)
			return view('error.message-standard')
						->with('title', 'Error retrieving Dosage Sensitivity details')
						->with('message', 'The system was not able to retrieve details for this report.  Error message was: ' . GeneLib::getError() . '. Please return to the previous page and try again.')
						->with('back', url()->previous());

//dd($record);
		// since we don't run through resources, we add some helpers here for now.  To be eventually
		// moved back into the library
		$record->haplo_assertion = GeneLib::haploAssertionString($record->has_dosage_haplo);
        $record->triplo_assertion = GeneLib::triploAssertionString($record->has_dosage_triplo);
        $record->report = env('CG_URL_CURATIONS_DOSAGE', '#') . $record->symbol . '&subject=';
		$record->date = $record->displayDate($record->dosage_report_date);

		// some data just to test the ideogram and sequence viewer
		$record->chromosome = $record->formatPosition($record->GRCh37_position, 'chr');

		$record->start_location=$record->formatPosition($record->GRCh37_position, 'from');
		$record->stop_location=$record->formatPosition($record->GRCh37_position, 'to');
		//$record->GRCh38_loc = 'chr22: 42,692,115-42,721,301';
		//$record->seqID = 'NC_000022.10';
		$record->sv_start = $record->formatPosition($record->GRCh37_position, 'svfrom');
		$record->sv_stop = $record->formatPosition($record->GRCh37_position, 'svto');
		//$record->loc = 'chr22: 43,088,121-43,117,307';
		//$record->GRCh38_seqID = 'NC_000022.11';
		$record->GRCh38_sv_start = $record->formatPosition($record->GRCh38_position, 'svfrom');
		$record->GRCh38_sv_stop = $record->formatPosition($record->GRCh38_position, 'svto');
		//dd($record);


		// set display context for view
		$display_tabs = collect([
			'active' => "dosage",
			'title' => $record->label . " curation results for Dosage Sensitivity"
		]);

		return view('gene-dosage.show', compact('display_tabs', 'record'));
	}


	/**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request)
    {
		return Gexcel::download(new DosageExport, 'Clingen-Dosage-Sensitivity.csv');
	}


	/**
     * Demo page for new dosage listing.
     *
     * @return \Illuminate\Http\Response
     */
    public function newindex(Request $request, $page = 1, $size = 100)
    {
        // process request args
		foreach ($request->only(['page', 'size', 'sort', 'search', 'direction']) as $key => $value)
			$$key = $value;

		// set display context for view
        $display_tabs = collect([
            'active' => "dosage",
            'title' => "Dosage Sensitivity Curations"
        ]);

		return view('new-dosage.index', compact('display_tabs'));
		//				->with('count', $results->count)
		//				->with('apiurl', '/api/dosage')
		//				->with('pagesize', $size)
		//				->with('page', $page);
	}


	/**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function newshow(Request $request, $id = '')
    {

		$record = GeneLib::dosageDetail([ 'gene' => 'HGNC:18149',
										'curations' => true,
										'action_scores' => true,
										'validity' => true,
										'dosage' => true
									]);

		if ($record === null)
		{
			die(print_r(GeneLib::getError()));
		}

		// since we don't run through resources, we add some helpers here for now.  To be eventually
		// moved back into the library
		$record->haplo_assertion = GeneLib::haploAssertionString($record->has_dosage_haplo);
        $record->triplo_assertion = GeneLib::triploAssertionString($record->has_dosage_triplo);
        $record->report = env('CG_URL_CURATIONS_DOSAGE', '#') . $record->symbol . '&subject=';
		$record->date = $record->displayDate($record->dosage_report_date);

		// some data just to test the ideogram and sequence viewer
		$record->chromosome = '22';
		$record->start_location="43088121";
		$record->stop_location="43117307";
		$record->GRCh38_loc = 'chr22: 42,692,115-42,721,301';
		$record->seqID = 'NC_000022.10';
		$record->sv_start = '43085202.3';
		$record->sv_stop = '43120225.7';
		$record->loc = 'chr22: 43,088,121-43,117,307';
		$record->GRCh38_seqID = 'NC_000022.11';
		$record->GRCh38_sv_start = '42689196.3';
		$record->GRCh38_sv_stop = '42724219.7';
		//dd($record);


		// set display context for view
		$display_tabs = collect([
			'active' => "dosage",
			'title' => $record->label . " curation results for Dosage Sensitivity"
		]);

		return view('new-dosage.show', compact('display_tabs', 'record'));
	}

	/**
     * Show the ftp downloads page.
     *
     * @return \Illuminate\Http\Response
     */
    public function ftps(Request $request)
    {
		$filelist = [
        	'ClinGen recurrent CNV .aed file V1.1-hg19.aed',
			'ClinGen recurrent CNV .aed file V1.1-hg38.aed',
			'ClinGen recurrent CNV .bed file V1.1-hg19.bed',
			'ClinGen recurrent CNV .bed file V1.1-hg38.bed',
			'ClinGen_gene_curation_list_GRCh37.tsv',
			'ClinGen_gene_curation_list_GRCh38.tsv',
			'ClinGen_haploinsufficiency_gene_GRCh37.bed',
			'ClinGen_haploinsufficiency_gene_GRCh38.bed',
			'ClinGen_region_curation_list_GRCh37.tsv',
			'ClinGen_region_curation_list_GRCh38.tsv',
			'ClinGen_triplosensitivity_gene_GRCh37.bed',
			'ClinGen_triplosensitivity_gene_GRCh38.bed',
			'README'];

		// set display context for view
        $display_tabs = collect([
            'active' => "dosage"
        ]);

		return view('gene-dosage.downloads', compact('display_tabs'))
						->with('filelist', $filelist);
	}


	/**
     * Show the revurrent  cnv listings.
     *
     * @return \Illuminate\Http\Response
     */
    public function cnv(Request $request, $page = 1, $size = 100)
    {

        // process request args
		foreach ($request->only(['page', 'size', 'sort', 'search', 'direction']) as $key => $value)
			$$key = $value;

		// set display context for view
        $display_tabs = collect([
            'active' => "dosage",
            'title' => "Dosage Sensitivity CNV Curations"
		]);

		return view('gene-dosage.cnv', compact('display_tabs'))
						->with('apiurl', '/api/dosage/cnv')
						->with('pagesize', $size)
						->with('page', $page);
	}


	/**
     * Show the acmg genes page.
     *
     * @return \Illuminate\Http\Response
     */
    public function acmg59(Request $request, $page = 1, $size = 100)
    {

        // process request args
		foreach ($request->only(['page', 'size', 'sort', 'search', 'direction']) as $key => $value)
			$$key = $value;

		// set display context for view
        $display_tabs = collect([
            'active' => "dosage"
		]);

		return view('gene-dosage.acmg59', compact('display_tabs'))
						->with('apiurl', '/api/dosage/acmg59')
						->with('pagesize', $size)
						->with('page', $page);
	}


	/**
     * Demo page for new dosage listing.
     *
     * @return \Illuminate\Http\Response
     */
    public function newreports(Request $request, $page = 1, $size = 100)
    {

        // process request args
		foreach ($request->only(['page', 'size', 'sort', 'search', 'direction']) as $key => $value)
			$$key = $value;

		// set display context for view
        $display_tabs = collect([
            'active' => "dosage",
            'title' => "Dosage Sensitivity Files"
        ]);

		return view('new-dosage.reports', compact('display_tabs'))
		//				->with('count', $results->count)
						->with('apiurl', '/api/dosage')
						->with('pagesize', $size)
						->with('page', $page);
	}


	/**
     * Demo page for new dosage listing.
     *
     * @return \Illuminate\Http\Response
     */
    public function newstats(Request $request, $page = 1, $size = 100)
    {

        // process request args
		foreach ($request->only(['page', 'size', 'sort', 'search', 'direction']) as $key => $value)
			$$key = $value;

		// set display context for view
        $display_tabs = collect([
            'active' => "dosage",
            'title' => "ClinGen Dosage Sensitivity Statistics"
        ]);

		return view('new-dosage.stats', compact('display_tabs'))
		//				->with('count', $results->count)
						->with('apiurl', '/api/dosage')
						->with('pagesize', $size)
						->with('page', $page);
	}
}
