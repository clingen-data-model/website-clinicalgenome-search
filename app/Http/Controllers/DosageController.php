<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel as Gexcel;

use GuzzleHttp\Client;

use App\Exports\DosageExport;
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
* @since      Class available since Release 1.2.0
* @deprecated
*
* */
class DosageController extends Controller
{
	private $api = '/api/dosage';


    /**
     * Display a listing of curated genes with a dosage sensitivity.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $page = 1, $size = 50)
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
						->with('apiurl', $this->api)
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

		// since we don't run through resources, we add some helpers here for now.  To be eventually
		// moved back into the library
		$record->haplo_assertion = GeneLib::haploAssertionString($record->has_dosage_haplo);
        $record->triplo_assertion = GeneLib::triploAssertionString($record->has_dosage_triplo);
        $record->report = env('CG_URL_CURATIONS_DOSAGE', '#') . $record->symbol . '&subject=';
		$record->date = $record->displayDate($record->dosage_report_date);
		$record->chromosome = $record->formatPosition($record->grch37, 'chr');
		$record->start_location=$record->formatPosition($record->grch37, 'from');
		$record->stop_location=$record->formatPosition($record->grch37, 'to');
		$record->sv_start = $record->formatPosition($record->grch37, 'svfrom');
		$record->sv_stop = $record->formatPosition($record->grch37, 'svto');
		$record->GRCh38_sv_start = $record->formatPosition($record->grch38, 'svfrom');
		$record->GRCh38_sv_stop = $record->formatPosition($record->grch38, 'svto');


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
    public function region_show(Request $request, $id = '')
    {
		$record = GeneLib::dosageRegionDetail([ 'gene' => $id,
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

		$record->haplo_assertion = GeneLib::haploAssertionString($record->haplo_score);
        $record->triplo_assertion = GeneLib::triploAssertionString($record->triplo_score);
		$record->chromosome = $record->formatPosition($record->grch37, 'chr');
		$record->start_location=$record->formatPosition($record->grch37, 'from');
		$record->stop_location=$record->formatPosition($record->grch37, 'to');
		$record->sv_start = $record->formatPosition($record->grch37, 'svfrom');
		$record->sv_stop = $record->formatPosition($record->grch37, 'svto');
		$record->GRCh38_sv_start = $record->formatPosition($record->grch38, 'svfrom');
		$record->GRCh38_sv_stop = $record->formatPosition($record->grch38, 'svto');
	
		// set display context for view
		$display_tabs = collect([
			'active' => "dosage",
			'title' => $record->label . " curation results for Dosage Sensitivity"
		]);

		return view('gene-dosage.region_show', compact('display_tabs', 'record'));
	}


	/**
     * Display the results of a region search.
     *
     * @return \Illuminate\Http\Response
     */
    public function region_search(Request $request, $type = '', $region = '', $page = 1, $size = 100)
    {
        // process request args
		foreach ($request->only(['page', 'size', 'sort', 'search', 'direction', 'region', 'type']) as $key => $value)
			$$key = $value;

		// set display context for view
        $display_tabs = collect([
            'active' => "dosage",
            'title' => "Dosage Sensitivity Curations"
		]);

		$original = $region;
		
		// if the region is a cytoband, convert to chromosomal location
		if (strtoupper(substr($region, 0, 3)) != 'CHR')
		{
			$client = new Client([
				'base_uri' => 'https://www.ncbi.nlm.nih.gov/projects/ideogram/data/',
				'headers' => [
					'Content-Type' => 'text/csv'
				]
			]);

			try {

				$response = $client->request('POST', 'band2bp.cgi?taxid=9606&assm=' . $type, ['body' => $region]);

				$cords = json_decode($response->getBody()->getContents());

				if (isset($cords->coords[0]->bp))
					$region = 'chr' . $cords->coords[0]->bp->chrom . ':'
								. $cords->coords[0]->bp->bp->from . '-' . $cords->coords[0]->bp->bp->to;
				else
					$region = 'INVALID';

			} catch (ClientException $e) {
				$region = 'INVALID';
			}
		}

		session(['dosage_region_search' => $region]);
		session(['dosage_region_search_type' => $type]);

		return view('gene-dosage.region_search', compact('display_tabs'))
		//				->with('count', $results->count)
						->with('type', $type)
						->with('original', $original)
						->with('region', $region)
						->with('apiurl', '/api/dosage/region_search/' . $type . '/' . $region)
						->with('pagesize', $size)
						->with('page', $page);
    }


	/**
     * Redisplay the results of a region search.
     *
     * @return \Illuminate\Http\Response
     */
    public function region_search_refresh(Request $request, $type = '', $region = '', $page = 1, $size = 100)
    {
        // process request args
		foreach ($request->only(['page', 'size', 'sort', 'search', 'direction', 'region', 'type']) as $key => $value)
			$$key = $value;

		// set display context for view
        $display_tabs = collect([
            'active' => "dosage",
            'title' => "Dosage Sensitivity Curations"
		]);

		$region = session('dosage_region_search', false);
		$type = session('dosage_region_search_type', false);

		if ($region === false)
			return redirect()->route('dosage-index');

		$original = $region;
		
		// if the region is a cytoband, convert to chromosomal location
		if (strtoupper(substr($region, 0, 3)) != 'CHR')
		{
			$client = new Client([
				'base_uri' => 'https://www.ncbi.nlm.nih.gov/projects/ideogram/data/',
				'headers' => [
					'Content-Type' => 'text/csv'
				]
			]);

			try {

				$response = $client->request('POST', 'band2bp.cgi?taxid=9606&assm=' . $type, ['body' => $region]);

				$cords = json_decode($response->getBody()->getContents());

				if (isset($cords->coords[0]->bp))
					$region = 'chr' . $cords->coords[0]->bp->chrom . ':'
								. $cords->coords[0]->bp->bp->from . '-' . $cords->coords[0]->bp->bp->to;
				else
					$region = 'INVALID';

			} catch (ClientException $e) {
				$region = 'INVALID';
			}
		}

		return view('gene-dosage.region_search', compact('display_tabs'))
		//				->with('count', $results->count)
						->with('type', $type)
						->with('original', $original)
						->with('region', $region)
						->with('apiurl', '/api/dosage/region_search/' . $type . '/' . $region)
						->with('pagesize', $size)
						->with('page', $page);
	}
	
	
	/**
     * Download the specified file.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request)
    {
		return Gexcel::download(new DosageExport, 'Clingen-Dosage-Sensitivity.csv');
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

}
