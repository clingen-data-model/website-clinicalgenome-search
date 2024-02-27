<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel as Gexcel;
use Illuminate\Support\Facades\Mail;

use App\Imports\Excel;
use App\Exports\ValidityExport;
use App\Exports\ValidityExportLS;

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
use App\Nodal;
use App\Blacklist;
use App\Mail\Feedback;
use App\Validity;
use App\Slug;

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

        // Genegraph never fixed the timestamp in the assertion ID, and now we have saved links of both in the wild :(
        //$id = Validity::fixid($id);

        // if new Clingen slug, map to real id.
        if (substr($id, 0, 5) == 'CCID:') {
            $s = Slug::alias($id)->first();

            if ($s === null || $s->target === null)
                return view('error.message-standard')
                    ->with('title', 'Error retrieving Gene Validity details')
                    ->with('message', 'The system was not able to retrieve details for this Disease. Please return to the previous page and try again.')
                    ->with('back', url()->previous())
                    ->with('user', $this->user);

            $id = $s->target;
        }

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

        $bcheck = Blacklist::gg(substr($id, 0, 51))->first();

        if ($bcheck == null)
            $extrecord =  GeneLib::newValidityDetail([
                'page' => 0,
                'pagesize' => 20,
                'perm' => $id
            ]);
        else
            $extrecord = null;

        $exp_count = null;
        // do not count the reviews
        if ($extrecord && $extrecord->experimental_evidence) {
            $scorable = [];

            foreach ($extrecord->experimental_evidence as $e)
                if (isset($e->score_status->label) && $e->score_status->label == "Score")
                    $scorable[] = $e;

            $exp_count = number_format(array_sum(array_column($scorable, 'score')), 2);
        }

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

        foreach ($msave as $value) {
            if (!isset($mims[intval($value)])) {
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
        $propoints = [];
        $temp = [];

        // uhg, since all the segregation is in one structure we need to maintain watch flags
        $clfs = false;
        $clfswopb = false;
        if ($extrecord !== null) {
            $genev = collect($extrecord->genetic_evidence);
            //dd($extrecord);
            $genev->each(function ($item) use (&$temp, &$segregation, &$casecontrol, &$caselevel, &$propoints, &$pmids, &$clfs, &$clfswopb) {
                if ($item->type[0]->curie == "SEPIO:0004012"  && !empty($item->evidence)) {
                    //dd($item->evidence);
                    foreach ($item->evidence as $e) {
                        if ($e->proband !== null) // && $e->proband->label !== null && ($e->estimated_lod_score !== null || $e->published_lod_score !== null))
                            $clfs = true;
                        else if ($e->proband === null) // || $e->proband->label === null || ($e->estimated_lod_score === null && $e->published_lod_score === null))
                            $clfswopb = true;
                    }

                    $segregation[] = $item;
                } else if ($item->type[0]->curie == "SEPIO:0004021" || $item->type[0]->curie == "SEPIO:0004020")
                    $casecontrol[] = $item;
                else if ($item->type[0]->curie == "SEPIO:0004174") // the separate proband counted points records
                {
                    // This thing is a hot mess and totally unusable as is.  Only choice it to completely restructure.
                    // First, seperate out the two statement records from the proband

                    $statements = [];
                    $proband = null;
                    $n = 0;

                    foreach ($item->evidence as $evidence) {
                        if ($evidence->__typename == "Statement") {
                            $variant = new Nodal([
                                'description' => $evidence->description ?? '',
                                'type' => $evidence->type ?? '',
                                'score_status' => $evidence->score_status->label ?? '',
                                'score' => $evidence->score ?? '',
                                'calculated_score' => $evidence->calculated_score ?? '',
                                'proband_counted_score' => $item->score ?? '',
                            ]);

                            foreach ($evidence->nested_variant as $nest) {
                                if ($nest->__typename == "VariantEvidence") {
                                    $variant->variant = $nest;
                                } else if ($nest->__typename == "GenericResource") {
                                    $variant->function = $nest;
                                }
                            }

                            $variants[] = $variant;
                        }
                    }

                    // this is stupid.  you have to go hunting for the proper reference
                    /*$label = null;
                    foreach ($item->evidence as $evidence)
                    {
                        if ($evidence->__typename == "ProbandEvidence")
                        {
                            $temp[] = $item;
                            $label = $evidence->label;
                        }
                    }

                    if ($label !== null)
                        $propoints[$label] = $item->score;

                    return true;
                    */

                    if (isset($variants)) {
                        $item->altvariants = $variants;
                        $caselevel[] = $item;
                    }
                } else
                    $caselevel[] = $item;
                //dd($item);
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
            if (isset($extrecord->earliest_articles)) {
                foreach ($extrecord->earliest_articles as $article)
                    $eas[] = $article->curie;
            }

            $extrecord->segregation = $segregation;
            $extrecord->casecontrol = $casecontrol;
            $extrecord->caselevel = $caselevel;
            $extrecord->nonscorable = $nonscorable;
            $extrecord->eas = $eas;

            // sort the pmid array by numerical pmid value
            $sortedpmids = [];
            foreach ($pmids as $key => $value) {
                $t = substr($key, 5);
                $sortedpmids[intval($t)] = $value;
            }
            ksort($sortedpmids);
            $extrecord->pmids = $sortedpmids;
        }

        //$ge_count = ($extrecord && !empty($extrecord->caselevel) ? number_format(array_sum(array_column($extrecord->caselevel, 'score')), 2) : null);
        $ge_count = null;
        // do not count the reviews
        if ($extrecord && $extrecord->caselevel) {
            $scorable = [];

            foreach ($extrecord->caselevel as $e)
                if (isset($e->score_status->label) && $e->score_status->label == "Score")
                    $scorable[] = $e;

            $ge_count = number_format(array_sum(array_column($scorable, 'score')), 2);
        }

        $cc_count = ($extrecord && !empty($extrecord->casecontrol) ? number_format(array_sum(array_column($extrecord->casecontrol, 'score')), 2) : null);

        // the segregation statements are strangly formatted in that they are an array within an array and the scores are mixed
        $cls_count = 0;
        $clfs_count = 0;

        if ($extrecord && !empty($extrecord->segregation)) {
            $exomeflag = false;
            foreach ($extrecord->segregation[0]->evidence as $evidence) {
                if ($evidence->meets_inclusion_criteria == true) {
                    if ($evidence->proband !== null && $evidence->proband->label !== null && ($evidence->estimated_lod_score !== null || $evidence->published_lod_score !== null)) {
                        $cls_count += ($evidence->published_lod_score === null ? $evidence->estimated_lod_score : $evidence->published_lod_score);
                        if (($evidence->sequencing_method->curie ?? false) == "SEPIO:0004541")
                            $exomeflag = true;
                    } else if ($evidence->proband === null || $evidence->proband->label === null || ($evidence->estimated_lod_score === null && $evidence->published_lod_score === null)) {
                        $clfs_count += ($evidence->published_lod_score === null ? $evidence->estimated_lod_score : $evidence->published_lod_score);
                    }
                }
            }
        }

        $cls_count = number_format($cls_count, 2);
        $clfs_count = number_format($clfs_count, 2);

        $cls_pt_count = 0;
        $cls_sum = $cls_count + $clfs_count;
        if ($cls_sum >= 2 && $cls_sum < 3)
            $cls_pt_count += ($exomeflag ? 1 : .5);
        else if ($cls_sum >= 3 && $cls_sum < 5)
            $cls_pt_count += ($exomeflag ? 2 : 1);
        else if ($cls_sum >= 5)
            $cls_pt_count += ($exomeflag ? 3 : 1.5);
        $cls_sum = number_format($cls_sum, 2);
        $cls_pt_count = number_format($cls_pt_count, 2);

        // temporary way to allow a link to the corresponding GCI page.
        $gdm_uuid = $record->report_id;

        if ($gdm_uuid === null) {
            $gg_uuid = substr($id, 5);

            $map = Gdmmap::gg($gg_uuid)->first();

            $gdm_uuid = $map->gdm_uuid ?? null;
        }

        $gcilink = null; //($gdm_uuid === null ? null : "https://curation.clinicalgenome.org/curation-central/" . $gdm_uuid);

        $showzygosity = $record->mode_of_inheritance->label == "Semidominant inheritance";

        switch ($record->specified_by->label) {
            case "ClinGen Gene Validity Evaluation Criteria SOP10":
            case "ClinGen Gene Validity Evaluation Criteria SOP9":
            case "ClinGen Gene Validity Evaluation Criteria SOP8":
                $showfunctionaldata = true;
                break;
            default:
                $showfunctionaldata = false;
                break;
        }

        $moiflag =  ($record->mode_of_inheritance->website_display_label === "Semidominant inheritance");

        $slug = Slug::target($id)->first();

        //dd($extrecord->genetic_evidence);
        return view(
            'gene-validity.show',
            compact('gcilink', 'showzygosity', 'showfunctionaldata', 'propoints', 'display_tabs', 'record', 'moiflag', 'extrecord', 'ge_count', 'exp_count', 'cc_count',
                    'cls_count', 'cls_pt_count', 'clfs_count', 'cls_sum', 'pmids', 'mims', 'clfs', 'clfswopb', 'slug')
        )
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


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download_ls(Request $request)
    {
        $date = date('Y-m-d');

        return Gexcel::download(new ValidityExportLS, 'Clingen-Gene-Disease-Summary-LS-' . $date . '.csv');
    }


    /**
     * Process the Validity Evidence Feedback Form
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function feedback(Request $request)
    {
        $data = $request->all();

        $recips = env('MAIL_GDV_FEEDBACK', 'pweller1@geisinger.edu');

        if (strpos($recips, ',') > 0)
            $recips = explode(',', $recips);

        $mail = Mail::to($recips);

        /*

        return response()->json(['success' => 'false',
								 'status_code' => 1001,
							 	 'message' => "Invalid Email Address"],
                                  501);
        */


        $date = Carbon::now()->yesterday()->format('m/d/Y');

        $classifications = [];
        foreach (['type_incorrect', 'type_missing', 'type_classification', 'type_typo', 'type_other'] as $v)
            if (isset($data[$v]))
                $classifications[] = $data[$v];

        $mail->send(new Feedback([
            'fullname' => $data['name'], 'gcep' => $data['gcep'], 'company' => $data['company'], 'email' => $data['email'],
            'title' => $data['position'], 'type_incorrect' => $data['type_incorrect'] ?? '', 'type_missing' => $data['type_missing'] ?? '',
            'type_classification' => $data['type_classification'] ?? '', 'type_typo' => $data['type_typo'] ?? '', 'type_other' => $data['type_other'] ?? '',
            'comment' => $data['comment'], 'link' => $data['link'], 'gene' => $data['gene'], 'classifications' => $classifications

        ]));

        return response()->json(
            [
                'success' => 'true',
                'status_code' => 200,
                'message' => "Request completed"
            ],
            200
        );
    }
}
