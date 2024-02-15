<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel as Gexcel;

use GuzzleHttp\Client;

use Auth;

use App\Exports\DosageExport;
use App\GeneLib;
use App\Gene;
use App\Filter;
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
	private $api = '/api/dosage';
	private $user = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::guard('api')->check())
                $this->user = Auth::guard('api')->user();
            return $next($request);
        });
    }


    /**
     * Display a listing of curated genes with a dosage sensitivity.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $page = 1, $size = 50)
    {
        // process request args
		foreach ($request->only(['page', 'size', 'sort', 'search','direction', 'col_search', 'col_search_val']) as $key => $value)
			$$key = $value;

		// set display context for view
        $display_tabs = collect([
            'active' => "dosage",
            'title' => "Dosage Sensitivity Curations",
            'scrid' => Filter::SCREEN_DOSAGE_CURATIONS,
			'display' => "Dosage Sensitivity"
		]);

		$col_search = collect([
			'col_search' => $request->col_search,
			'col_search_val' => $request->col_search_val,
		]);

        // get list of all current bookmarks for the page
        $bookmarks = ($this->user === null ? collect() : $this->user->filters()->screen(Filter::SCREEN_DOSAGE_CURATIONS)->get()->sortBy('name', SORT_STRING | SORT_FLAG_CASE));

        // get active bookmark, if any
        $filter = Filter::preferences($request, $this->user, Filter::SCREEN_DOSAGE_CURATIONS);

        if ($filter !== null && getType($filter) == "object" && get_class($filter) == "Illuminate\Http\RedirectResponse")
            return $filter;

        // don't apply global settings if local ones present
        $settings = Filter::parseSettings($request->fullUrl());

        if (empty($settings['size']))
            $display_list = ($this->user === null ? 25 : $this->user->preferences['display_list'] ?? 25);
        else
            $display_list = $settings['size'];

        //Set for search result page
        $is_search = false;

		return view('gene-dosage.index', compact('display_tabs'))
		//				->with('count', $results->count)
						->with('apiurl', $this->api)
						->with('pagesize', $size)
						->with('page', $page)
						->with('col_search', $col_search)
						->with('user', $this->user)
                        ->with('display_list', $display_list)
                        ->with('is_search', $is_search)
						->with('bookmarks', $bookmarks)
                        ->with('currentbookmark', $filter)
                        ->with('type', 'GRCh37');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id = '')
    {
        if (stripos('HGNC:', $id) !== 0)
        {
            $gene = Gene::name($id)->first();

            if ($gene === null)
            {
                $gene = Gene::previous($id)->first();
            }

            $id = ($gene === null) ? $id : $gene->hgnc_id;
        }

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
						->with('back', url()->previous())
                        ->with('user', $this->user);

        // Dosage has a strange publication workflow where part occurs on genegraph and part oj Jira.  To mimic
        // that we need to act like genegraph would.
        if ($record->issue_status != "Complete" || $record->jira_status != "Closed")
        {
            //$record = Jira::rollback($record);
        }

		// since we don't run through resources, we add some helpers here for now.  To be eventually
		// moved back into the library
		if ($record->genetype == "pseudogene")
		{
			$record->haplo_assertion = GeneLib::haploAssertionString($record->haplo_score);
			$record->triplo_assertion = GeneLib::triploAssertionString($record->triplo_score);
		}
		else
		{
			$record->haplo_assertion = GeneLib::haploAssertionString($record->has_dosage_haplo);
			$record->triplo_assertion = GeneLib::triploAssertionString($record->has_dosage_triplo);
		}

        if (empty($record->haplo_assertion))
            $record->haplo_assertion = "Not Yet Evaluated";

        if (empty($record->triplo_assertion))
            $record->triplo_assertion = "Not Yet Evaluated";

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

		$user = $this->user;

		return view('gene-dosage.show', compact('display_tabs', 'record', 'user'));
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
						->with('back', url()->previous())
                        ->with('user', $this->user);

		$record->haplo_assertion = GeneLib::haploAssertionString($record->haplo_score);
        $record->triplo_assertion = GeneLib::triploAssertionString($record->triplo_score);
		$record->chromosome = $record->formatPosition($record->grch37, 'chr');
		$record->start_location=$record->formatPosition($record->grch37, 'from');
		$record->stop_location=$record->formatPosition($record->grch37, 'to');
		$record->sv_start = $record->formatPosition($record->grch37, 'svfrom');
		$record->sv_stop = $record->formatPosition($record->grch37, 'svto');
		$record->GRCh38_sv_start = $record->formatPosition($record->grch38, 'svfrom');
		$record->GRCh38_sv_stop = $record->formatPosition($record->grch38, 'svto');

        //$record->GRCh37_seqid = "NC_000014.8";

		// set display context for view
		$display_tabs = collect([
			'active' => "dosage",
			'title' => $record->label . " curation results for Dosage Sensitivity"
		]);

		$user = $this->user;
//dd($record);
		return view('gene-dosage.region_show', compact('display_tabs', 'record', 'user'));
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
            'title' => "Dosage Sensitivity Curations",
            'scrid' => Filter::SCREEN_DOSAGE_REGION_SEARCH,
			'display' => "Dosage Sensitivity Region Search"
		]);

		$original = $region;

        // normalize the type string
        if (strcasecmp($type, "GRCh37") === 0)
            $type = "GRCh37";
        else if (strcasecmp($type, "GRCh38") === 0)
            $type = "GRCh38";

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

                $regions = explode('-', $region);

				$response = $client->request('POST', 'band2bp.cgi?taxid=9606&assm=' . $type, ['body' => $regions[0]]);

				$cords = json_decode($response->getBody()->getContents());

				if (isset($cords->coords[0]->bp))
					$region = 'chr' . $cords->coords[0]->bp->chrom . ':'
								. $cords->coords[0]->bp->bp->from . '-';
				else
					$region = 'INVALID';

                if (isset($regions[1]))
                {
                    // allow user to drop the second chromosome
                    if (!(is_numeric($regions[1][0]) || $regions[1][0] == 'X'  || $regions[1][0] == 'Y'))
                        $regions[1] = $cords->coords[0]->bp->chrom . $regions[1];

                    $response = $client->request('POST', 'band2bp.cgi?taxid=9606&assm=' . $type, ['body' => $regions[1]]);

				    $seccords = json_decode($response->getBody()->getContents());

                    if (isset($seccords->coords[0]->bp))
                        $region .=  $seccords->coords[0]->bp->bp->to;
                    else
                        $region = 'INVALID';
                }
                else
                {
                    if (count($cords->coords)) {
                        $region .=  $cords->coords[0]->bp->bp->to;
                    } else {
                        $region = 'INVALID';
                    }

                }

			} catch (ClientException $e) {
				$region = 'INVALID';
			}
		}

		session(['dosage_region_search' => $region]);
		session(['dosage_region_search_type' => $type]);

        // get list of all current bookmarks for the page
        $bookmarks = ($this->user === null ? collect() : $this->user->filters()->screen(Filter::SCREEN_DOSAGE_REGION_SEARCH)->get()->sortBy('name', SORT_STRING | SORT_FLAG_CASE));

        // get active bookmark, if any
        $filter = Filter::preferences($request, $this->user, Filter::SCREEN_DOSAGE_REGION_SEARCH);

        if ($filter !== null && getType($filter) == "object" && get_class($filter) == "Illuminate\Http\RedirectResponse")
            return $filter;

        // don't apply global settings if local ones present
        $settings = Filter::parseSettings($request->fullUrl());

        if (empty($settings['size']))
            $display_list = ($this->user === null ? 25 : $this->user->preferences['display_list'] ?? 25);
        else
            $display_list = $settings['size'];

        $is_search = true;

		return view('gene-dosage.region_search', compact('display_tabs'))
		//				->with('count', $results->count)
						->with('type', $type)
						->with('original', $original)
						->with('region', $region)
						->with('apiurl', '/api/dosage/region_search/' . $type . '/' . $region)
						->with('pagesize', $size)
						->with('page', $page)
						->with('user', $this->user)
						->with('display_list', $display_list)
                        ->with('bookmarks', $bookmarks)
                        ->with('is_search', $is_search)
                        ->with('currentbookmark', $filter);
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
            'title' => "Dosage Sensitivity Curations",
            'scrid' => Filter::SCREEN_DOSAGE_REGION_REFRESH,
			'display' => "Expert Panel Curations"
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
                $regions = explode('-', $region);

				$response = $client->request('POST', 'band2bp.cgi?taxid=9606&assm=' . $type, ['body' => $regions[0]]);

				$cords = json_decode($response->getBody()->getContents());

				if (isset($cords->coords[0]->bp))
					$region = 'chr' . $cords->coords[0]->bp->chrom . ':'
								. $cords->coords[0]->bp->bp->from . '-';
				else
					$region = 'INVALID';

                if (isset($regions[1]))
                {
                    // allow user to drop the second chromosome
                    if (!(is_numeric($regions[1][0]) || $regions[1][0] == 'X'  || $regions[1][0] == 'Y'))
                        $regions[1] = $cords->coords[0]->bp->chrom . $regions[1];

                    $response = $client->request('POST', 'band2bp.cgi?taxid=9606&assm=' . $type, ['body' => $regions[1]]);

                    $seccords = json_decode($response->getBody()->getContents());

                    if (isset($seccords->coords[0]->bp))
                        $region .=  $seccords->coords[0]->bp->bp->to;
                    else
                        $region = 'INVALID';
                }
                else
                {
                    $region .=  $cords->coords[0]->bp->bp->to;
                }

			} catch (ClientException $e) {
				$region = 'INVALID';
			}
		}

        // get list of all current bookmarks for the page
        $bookmarks = ($this->user === null ? collect() : $this->user->filters()->screen(Filter::SCREEN_DOSAGE_REGION_REFRESH)->get()->sortBy('name', SORT_STRING | SORT_FLAG_CASE));

        // get active bookmark, if any
        $filter = Filter::preferences($request, $this->user, Filter::SCREEN_DOSAGE_REGION_REFRESH);

        if ($filter !== null && getType($filter) == "object" && get_class($filter) == "Illuminate\Http\RedirectResponse")
            return $filter;

        // don't apply global settings if local ones present
        $settings = Filter::parseSettings($request->fullUrl());
        $is_search = true;

        if (empty($settings['size']))
            $display_list = ($this->user === null ? 25 : $this->user->preferences['display_list'] ?? 25);
        else
            $display_list = $settings['size'];

		return view('gene-dosage.region_search', compact('display_tabs'))
		//				->with('count', $results->count)
						->with('type', $type)
						->with('original', $original)
						->with('region', $region)
						->with('apiurl', '/api/dosage/region_search/' . $type . '/' . $region)
						->with('pagesize', $size)
						->with('page', $page)
						->with('user', $this->user)
						->with('display_list', $display_list)
                        ->with('bookmarks', $bookmarks)
                        ->with('is_search', $is_search)
                        ->with('currentbookmark', $filter);
	}


	/**
     * Download the specified file.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request)
    {
		$date = date('Y-m-d');

		return Gexcel::download(new DosageExport, 'Clingen-Dosage-Sensitivity-' . $date . '.csv');
	}


// THIS MOVED TO HOME CONTROLLR AS A CENTRAL LOCATIONS - TODO CLEANUP
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
						->with('filelist', $filelist)
                        ->with('user', $this->user);
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
            'title' => "Dosage Sensitivity CNV Curations",
            'scrid' => Filter::SCREEN_DOSAGE_CNVS,
			'display' => "Dosage Sensitivity CNV"
		]);

        // get list of all current bookmarks for the page
        $bookmarks = ($this->user === null ? collect() : $this->user->filters()->screen(Filter::SCREEN_DOSAGE_CNVS)->get()->sortBy('name', SORT_STRING | SORT_FLAG_CASE));

        // get active bookmark, if any
        $filter = Filter::preferences($request, $this->user, Filter::SCREEN_DOSAGE_CNVS);

        if ($filter !== null && getType($filter) == "object" && get_class($filter) == "Illuminate\Http\RedirectResponse")
            return $filter;

        // don't apply global settings if local ones present
        $settings = Filter::parseSettings($request->fullUrl());

        if (empty($settings['size']))
            $display_list = ($this->user === null ? 25 : $this->user->preferences['display_list'] ?? 25);
        else
            $display_list = $settings['size'];

		return view('gene-dosage.cnv', compact('display_tabs'))
						->with('apiurl', '/api/dosage/cnv')
						->with('pagesize', $size)
						->with('page', $page)
						->with('user', $this->user)
                        ->with('display_list', $display_list)
						->with('bookmarks', $bookmarks)
                        ->with('currentbookmark', $filter);
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

		$display_list = ($this->user === null ? 25 : $this->user->preferences['display_list'] ?? 25);

		return view('gene-dosage.acmg59', compact('display_tabs'))
						->with('apiurl', '/api/dosage/acmg59')
						->with('pagesize', $size)
						->with('page', $page)
						->with('user', $this->user)
						->with('display_list', $display_list);
	}

}
