<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel as Gexcel;

use App\Imports\Excel;
use App\Exports\ValidityExport;

use Auth;
use Carbon\Carbon;

require app_path() .  '/Helpers/helper.php';

use App\GeneLib;
use App\Filter;
use App\Gdmmap;
use App\Precuration;
use App\Mim;
use App\Omim;
use App\Pmid;

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
    private $api = '/api/validity';
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
     * Display a listing of all gene validity assertions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $page = 1, $size = 50)
    {
        // process request args
        foreach ($request->only(['page', 'size', 'order', 'sort', 'search', 'col_search', 'col_search_val']) as $key => $value)
            $$key = $value;

        // set display context for view
        $display_tabs = collect([
            'active' => "validity",
            'title' => "ClinGen Gene-Disease Validity Curations",
            'scrid' => Filter::SCREEN_VALIDITY_CURATIONS,
            'display' => "Gene-Disease Validity"
        ]);

        $col_search = collect([
            'col_search' => $request->col_search,
            'col_search_val' => $request->col_search_val,
        ]);

        // get list of all current bookmarks for the page
        $bookmarks = ($this->user === null ? collect() : $this->user->filters()->screen(Filter::SCREEN_VALIDITY_CURATIONS)->get()->sortBy('name', SORT_STRING | SORT_FLAG_CASE));

        // get active bookmark, if any
        $filter = Filter::preferences($request, $this->user, Filter::SCREEN_VALIDITY_CURATIONS);

        if ($filter !== null && getType($filter) == "object" && get_class($filter) == "Illuminate\Http\RedirectResponse")
            return $filter;

        // don't apply global settings if local ones present
        $settings = Filter::parseSettings($request->fullUrl());

        if (empty($settings['size']))
            $display_list = ($this->user === null ? 25 : $this->user->preferences['display_list'] ?? 25);
        else
            $display_list = $settings['size'];

        return view('gene-validity.index', compact('display_tabs'))
            ->with('apiurl', $this->api)
            ->with('pagesize', $size)
            ->with('page', $page)
            ->with('col_search', $col_search)
            ->with('user', $this->user)
            ->with('display_list', $display_list)
            ->with('bookmarks', $bookmarks)
            ->with('currentbookmark', $filter);
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
            return view('error.message-standard')
                ->with('title', 'Error retrieving Gene Validity details')
                ->with('message', 'The system was not able to retrieve details for this Disease. Please return to the previous page and try again.')
                ->with('back', url()->previous())
                ->with('user', $this->user);

        $record = GeneLib::validityDetail([
            'page' => 0,
            'pagesize' => 20,
            'perm' => $id
        ]);

        if ($record === null)
            return view('error.message-standard')
                ->with('title', 'Error retrieving Gene Validity details')
                ->with('message', 'The system was not able to retrieve details for this Gene Validity.  Error message was: ' . GeneLib::getError() . '. Please return to the previous page and try again.')
                ->with('back', url()->previous())
                ->with('user', $this->user);

        $extrecord = GeneLib::newValidityDetail([
            'page' => 0,
            'pagesize' => 20,
            'perm' => $id
        ]);

        $exp_count = ($extrecord && $extrecord->experimental_evidence ? number_format(array_sum(array_column($extrecord->experimental_evidence, 'score')), 2) : null);

        // set display context for view
        $display_tabs = collect([
            'active' => "validity",
            'scrid' => Filter::SCREEN_VALIDITY_CURATIONS,
            'title' => $record->label . " curation results for Gene-Disease Validity",
            'display' => "Gene-Disease Validity"
        ]);

        // genegraph changed so extrecord is not null on GC Express, so force it.
        if (!isset($extrecord) || strpos($extrecord->curie, 'CGGCIEX') === 0)
            $extrecord = null;

        // organize all the l&s data.  First the report_i
        $iri = $record->json->iri ?? null;
        $map = Gdmmap::gg($iri)->first();
        if ($map !== null)
            $record->report_id = $map->gdm_uuid;

        $record->las_included = [];
        $record->las_excluded = [];
        $record->las_rationale = [];
        $record->las_curation = '';
        $record->las_date = null;

        if ($record->report_id !== null) {
            $map = Precuration::gdmid($record->report_id)->first();
            if ($map !== null) {
                $record->las_included = $map->omim_phenotypes['included'] ?? [];
                $record->las_excluded = $map->omim_phenotypes['excluded'] ?? [];
                $record->las_rationale = $map->rationale;
                $record->las_curation = $map->curation_type['description'] ?? '';

                // the dates aren't always populated in the gene tracker, so we may need to restrict them.
                $prec_date = $map->disease_date;
                if ($prec_date !== null) {
                    $dd = Carbon::parse($prec_date);
                    $rd = Carbon::parse($record->report_date);
                    $record->las_date = ($dd->gt($rd) ? $record->report_date : $prec_date);
                } else {
                    $record->las_date = $record->report_date;
                }
            }
        }

        $mims = array_merge($record->las_included, $record->las_excluded);
        $pmids = [];

        if (isset($record->las_rationale['pmids']))
            $pmids = array_merge($pmids, $record->las_rationale['pmids']);

        // get the mim names
        $mim_names = Mim::whereIn('mim', $mims)->get();

        $msave = $mims;
        $mims = [];

        foreach ($mim_names as $mim)
            $mims[$mim->mim] = $mim->title;

        foreach ($msave as $value)
        {
            if (!isset($mims[intval($value)]))
            {
                $omim = Omim::omimid($value)->first();

                if ($omim !== null)
                    $mims[$omim->omimid] = $omim->titles;
            }
        }

        // get the pmids
        $pmid_names = Pmid::whereIn('pmid', $pmids)->get();

        $pmids = [];

        foreach ($pmid_names as $pmid)
            $pmids[$pmid->pmid] = [
                'title' => $pmid->sortfirstauthor . ', et al, ' . $pmid->pubdate . ', ' . $pmid->title,
                //     'author' => $pmid->sortfirstauthor,
                //    'published' =>  $pmid->pubdate,
                'abstract' => $pmid->abstract
            ];

        // unfortunately, genegraph mixes all the genetic evidence data in one big response set, so we are forced to separate out.
        $segregation = [];
        $casecontrol = [];
        $caselevel = [];
        $nonscorable = [];
        $pmids = [];

        // uhg, since all the segregation is in one structure we need to maintain watch flags
        $clfs = false;
        $clfswopb = false;

        if ($extrecord !== null) {
            $genev = collect($extrecord->genetic_evidence);

            $genev->each(function ($item) use (&$segregation, &$casecontrol, &$caselevel, &$pmids, &$clfs, &$clfswopb) {
                if ($item->type[0]->curie == "SEPIO:0004012"  && !empty($item->evidence))
                {
                    foreach ($item->evidence as $e)
                    {
                        if ($e->proband === null)
                            $clfswopb = true;
                        else
                            $clfs = true;
                    }

                    $segregation[] = $item;
                }
                else if ($item->type[0]->curie == "SEPIO:0004021" || $item->type[0]->curie == "SEPIO:0004020")
                    $casecontrol[] = $item;
                else if ($item->type[0]->curie != "SEPIO:0004097")
                    $caselevel[] = $item;

                if (!empty($item->evidence))
                    foreach ($item->evidence as $evidence)
                        if ($evidence->source !== null)
                            $pmids[$evidence->source->curie] = $evidence->source;
            });

            $nosev = collect($extrecord->direct_evidence);

            $nosev->each(function ($item) use (&$nonscorable, &$pmids) {
                if ($item->type[0]->curie == "SEPIO:0004127")
                    $nonscorable[] = $item;

                if (!empty($item->evidence))
                    foreach ($item->evidence as $evidence)
                        if ($evidence->source !== null)
                            $pmids[$evidence->source->curie] = $evidence->source;
            });

            $expev = collect($extrecord->experimental_evidence);

            $expev->each(function ($item) use (&$pmids) {
                if (!empty($item->evidence))
                    foreach ($item->evidence as $evidence)
                        if ($evidence->source !== null)
                            $pmids[$evidence->source->curie] = $evidence->source;
            });

            // build a quick list of the eariest earliest_articles
            $eas = [];
            if (isset($extrecord->earliest_articles))
            {
                foreach ($extrecord->earliest_articles as $article)
                    $eas[] = $article->curie;
            }

            $extrecord->segregation = $segregation;
            $extrecord->casecontrol = $casecontrol;
            $extrecord->caselevel = $caselevel;
            $extrecord->nonscorable = $nonscorable;
            $extrecord->eas = $eas;
            ksort($pmids);
            $extrecord->pmids = $pmids;


        }

        $ge_count = ($extrecord && !empty($extrecord->caselevel) ? number_format(array_sum(array_column($extrecord->caselevel, 'score')), 2) : null);
        $cc_count = ($extrecord && !empty($extrecord->casecontrol) ? number_format(array_sum(array_column($extrecord->casecontrol, 'score')), 2) : null);

        // the segregation statements are strangly formatted in that they are an array within an array and the scores are mixed
        $cls_count = 0;
        $clfs_count = 0;

        if ($extrecord && !empty($extrecord->segregation))
        {
            foreach ($extrecord->segregation[0]->evidence as $evidence)
            {
                if ($evidence->meets_inclusion_criteria)
                {
                    if ($evidence->proband !== null)
                        $cls_count += $evidence->estimated_lod_score;
                    else
                        $clfs_count += $evidence->estimated_lod_score;
                }
            }
        }

        $cls_count = number_format($cls_count, 2);
        $clfs_count = number_format($clfs_count, 2);

 //dd($extrecord);

        // collect the non-scorable records

        return view('gene-validity.show', compact('display_tabs', 'record', 'extrecord', 'ge_count', 'exp_count', 'cc_count', 'cls_count', 'clfs_count', 'pmids', 'mims','clfs', 'clfswopb'))
            ->with('user', $this->user);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request)
    {
        $date = date('Y-m-d');

        return Gexcel::download(new ValidityExport, 'Clingen-Gene-Disease-Summary-' . $date . '.csv');
    }
}
