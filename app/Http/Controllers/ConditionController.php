<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\GeneLib;
use App\Nodal;
use App\User;
use App\Filter;
use App\Disease;
use App\Mim;
use App\Pmid;
use App\Panel;

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
class ConditionController extends Controller
{
	private $api = '/api/conditions';
	private $user = null;

	protected $validity_sort_order = [
		'SEPIO:0004504' => 20,				// Definitive
		'SEPIO:0004505' => 19,				// Strong
		'SEPIO:0004506' => 18,				// Moderate
		'Supportive' => 17,					// Supportive
		'SEPIO:0004507' => 16,				// Limited
		'Animal Model Only' => 15,			// Animal Mode Only
		'SEPIO:0000404' => 14,				// Disputing
		'SEPIO:0004510' => 13,				// Refuted
		'SEPIO:0004508' => 12				// No Known Disease Relationship
	];

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
    public function index(Request $request, $page = 1, $size = 50, $search="")
    {
		// process request args
		foreach ($request->only(['page', 'size', 'order', 'sort', 'search']) as $key => $value)
			$$key = $value;

		// set display context for view
        $display_tabs = collect([
            'active' => "condition",
            'title' => "ClinGen Diseases",
            'scrid' => Filter::SCREEN_ALL_DISEASES,
			'display' => "All Disease"
        ]);

        // get list of all current bookmarks for the page
        $bookmarks = ($this->user === null ? collect() : $this->user->filters()->screen(Filter::SCREEN_ALL_DISEASES)->get()->sortBy('name', SORT_STRING | SORT_FLAG_CASE));

        // get active bookmark, if any
        $filter = Filter::preferences($request, $this->user, Filter::SCREEN_ALL_DISEASES);

        if ($filter !== null && getType($filter) == "object" && get_class($filter) == "Illuminate\Http\RedirectResponse")
            return $filter;

        // don't apply global settings if local ones present
        $settings = Filter::parseSettings($request->fullUrl());

        if (empty($settings['size']))
            $display_list = ($this->user === null ? 25 : $this->user->preferences['display_list'] ?? 25);
        else
            $display_list = $settings['size'];

		return view('condition.index', compact('display_tabs'))
						->with('apiurl', $this->api)
						->with('pagesize', $size)
						->with('page', $page)
						->with('search', $search)
						->with('user', $this->user)
						->with('display_list', $display_list)
						->with('bookmarks', $bookmarks)
                        ->with('currentbookmark', $filter);
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
			return view('error.message-standard')
						->with('title', 'Error retrieving Disease details')
						->with('message', 'The system was not able to retrieve details for this Disease. Please return to the previous page and try again.')
						->with('back', url()->previous())
						->with('user', $this->user);

        // check if the condition came in as an OMIM ID, and if so convert it.
        /*if (strpos($id, "MONDO:") !== 0)
        {
            $check = Disease::omim($id)->first();

            if ($check !== null)
                $id = $check->curie;
        }*/

        $disease = Disease::rosetta($id);

        $mimflag = false;

        /*if ($disease === null && (stripos($id, 'OMIM:') === 0 ||stripos($id, 'MIM:') === 0))
        {
			$t = explode(':', $id);
            //$t = substr($id, 5 );
			if (isset($t[1]))
			{
				$mim = Mim::mim($t[1])->first();
				if ($mim !== null)
					$gene = $mim->gene;
				$mimflag = ($gene === null ? false : $t[1]);
			}
        }*/

        if ($disease === null)
            return view('error.message-standard')
                    ->with('title', 'Error retrieving Disease details')
                    ->with('message', 'The system was not able to retrieve details for this Disease. Please return to the previous page and try again.')
                    ->with('back', url()->previous())
                    ->with('user', $this->user);

        $id = $disease->curie;

		$record = GeneLib::conditionDetail([
										'condition' => $id,
										'curations' => true,
										'action_scores' => true,
										'validity' => true,
										'dosage' => true,
                                        'variant' => true
										]);

		if ($record === null)
			return view('error.message-standard')
						->with('title', 'Error retrieving Disease details')
						->with('message', 'The system was not able to retrieve details for this Disease.  Error message was: ' . GeneLib::getError() . '. Please return to the previous page and try again.')
						->with('back', url()->previous())
						->with('user', $this->user);

		//reformat the response structure for view by activity
		$validity_collection = collect();
        $variant_collection = collect();
        $mims = [];
        $pmids = [];

		foreach ($record->genetic_conditions as $key => $disease)
		{
			// actionability
			/*foreach ($disease->actionability_assertions as $assertion)
			{
				$node = new Nodal([	'order' => $this->actionability_sort_order[$assertion->classification->label] ?? 0,
									'disease' => $disease->disease, 'assertion' => $assertion]);
				$actionability_collection->push($node);
			}*/

			// validity
			foreach ($disease->gene_validity_assertions as $assertion)
			{
				$node = new Nodal([	'order' => $this->validity_sort_order[$assertion->classification->curie] ?? 0,
									'gene' => $disease->gene, 'assertion' => $assertion]);
				$validity_collection->push($node);
                $mims = array_merge($mims, $assertion->las_included, $assertion->las_excluded);
                if (isset($assertion->las_rationale['pmids']))
                    $pmids = array_merge($pmids, $assertion->las_rationale['pmids']);
			}

			// dosage
			/*foreach ($disease->gene_dosage_assertions as $assertion)
			{
				$node = new Nodal([	'order' => $assertion->dosage_classification->oridinal ?? 0,
									'disease' => $disease->disease, 'assertion' => $assertion]);
				$dosage_collection->push($node);
			}*/
		}

        // get the mim names
        $mim_names = MIM::whereIn('mim', $mims)->get();

        $mims = [];

        foreach ($mim_names as $mim)
            $mims[$mim->mim] = $mim->title;

        // get the pmids
        $pmid_names = Pmid::whereIn('pmid', $pmids)->get();

        $pmids = [];

        foreach($pmid_names as $pmid)
            $pmids[$pmid->pmid] = ['title' => $pmid->sortfirstauthor . ', et al, ' . $pmid->pubdate . ', ' . $pmid->title,
                               //     'author' => $pmid->sortfirstauthor,
                                //    'published' =>  $pmid->pubdate,
                                    'abstract' => $pmid->abstract];

		// reapply any sorting requirements
		$validity_collection = $validity_collection->sortByDesc('order');

         // we don't do any special sorting on variant path at this time
         if ($record->nvariant > 0)
            $variant_collection = collect($record->variant);

        $validity_panels = [];
        $validity_collection->each(function ($item) use (&$validity_panels){
            if (!in_array($item->assertion->attributed_to->label, $validity_panels))
                array_push($validity_panels, $item->assertion->attributed_to->label);
        });


        $validity_eps = count($validity_panels);
        //$actionability_collection = $actionability_collection->sortByDesc('order');

        if ($record->nvariant > 0)
            $variant_collection = collect($record->variant);

        // collect all the unique panels
        $variant_panels = [];
        $variant_collection->each(function ($item) use (&$variant_panels){
            $variant_panels = array_merge($variant_panels, array_column($item['panels'], 'affiliation'));
        });

        $variant_panels = array_unique($variant_panels);

        $vceps = Disease::curie($id)->first()->panels->where('type', PANEL::TYPE_VCEP);
        $gceps = Disease::curie($id)->first()->panels->where('type', PANEL::TYPE_GCEP);
        $pregceps = collect();

        if ($record->curation_status !== null)
        {
            foreach ($record->curation_status as $precuration)
            {
                if ($precuration['status'] == "Retired Assignment" || $precuration['status'] == "Published")
                    continue;

                if (empty($precuration['group_id']))
                    $panel = Panel::where('title_abbreviated', $precuration['group'])->first();
                else
                    $panel = Panel::allids($precuration['group_id'])->first();

                if ($panel == null)
                    continue;

                // blacklist panels we don't want displayed
                if ($panel->affiliate_id == "40018" || $panel->affiliate_id == "40019")
                    continue;

                $pregceps->push($panel);
            }

            $remids = $gceps->pluck('id');
            $pregceps = $pregceps->whereNotIn('id', $remids);
        }

        $total_panels = /*$validity_eps + count($variant_panels)
                        + ($record->ndosage > 0 ? 1 : 0)
                        + ($actionability_collection->isEmpty() ? 0 : 1)
                        + */ count($pregceps);

		// set display context for view
		$display_tabs = collect([
			'active' => "condition",
			'title' => $record->label . " curation results by ClinGen activity"
		]);
//dd($record);
        //dd($validity_collection);
		return view('condition.by-activity', compact('display_tabs', 'record', 'validity_collection', 'total_panels',
                                                    'mims', 'pmids', 'mimflag', 'pregceps', 'variant_collection'));
	}


	/**
	 * Display the specified condition.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show_by_gene(Request $request, $id = null)
	{

        if ($id === null)
			return view('error.message-standard')
						->with('title', 'Error retrieving Disease details')
						->with('message', 'The system was not able to retrieve details for this Disease. Please return to the previous page and try again.')
						->with('back', url()->previous())
						->with('user', $this->user);

        $disease = Disease::rosetta($id);

        $mimflag = false;

        /*if ($disease === null && (stripos($id, 'OMIM:') === 0 ||stripos($id, 'MIM:') === 0))
        {
			$t = explode(':', $id);
            //$t = substr($id, 5 );
			if (isset($t[1]))
			{
				$mim = Mim::mim($t[1])->first();
				if ($mim !== null)
					$gene = $mim->gene;
				$mimflag = ($gene === null ? false : $t[1]);
			}
        }*/

        if ($disease === null)
            return view('error.message-standard')
                    ->with('title', 'Error retrieving Disease details')
                    ->with('message', 'The system was not able to retrieve details for this Disease. Please return to the previous page and try again.')
                    ->with('back', url()->previous())
                    ->with('user', $this->user);

        $id = $disease->curie;

		$record = GeneLib::conditionDetail([
                                'condition' => $id,
                                'curations' => true,
                                'action_scores' => true,
                                'validity' => true,
                                'dosage' => true,
                                'variant' => true
		                    ]);

		if ($record === null)
			return view('error.message-standard')
						->with('title', 'Error retrieving Disease details')
						->with('message', 'The system was not able to retrieve details for this Disease.  Error message was: ' . GeneLib::getError() . '. Please return to the previous page and try again.')
						->with('back', url()->previous())
						->with('user', $this->user);

		$user = $this->user;
//dd($variant_collection);
        //reformat the response structure for view by activity
        $validity_collection = collect();
        $variant_collection = collect();
        $mims = [];
        $pmids = [];

        foreach ($record->genetic_conditions as $key => $disease)
		{
			// actionability
			/*foreach ($disease->actionability_assertions as $assertion)
			{
				$node = new Nodal([	'order' => $this->actionability_sort_order[$assertion->classification->label] ?? 0,
									'disease' => $disease->disease, 'assertion' => $assertion]);
				$actionability_collection->push($node);
			}*/

			// validity
			foreach ($disease->gene_validity_assertions as $assertion)
			{
				$node = new Nodal([	'order' => $this->validity_sort_order[$assertion->classification->curie] ?? 0,
									'gene' => $disease->gene, 'assertion' => $assertion]);
				$validity_collection->push($node);
                $mims = array_merge($mims, $assertion->las_included, $assertion->las_excluded);
                if (isset($assertion->las_rationale['pmids']))
                    $pmids = array_merge($pmids, $assertion->las_rationale['pmids']);
			}

			// dosage
			/*foreach ($disease->gene_dosage_assertions as $assertion)
			{
				$node = new Nodal([	'order' => $assertion->dosage_classification->oridinal ?? 0,
									'disease' => $disease->disease, 'assertion' => $assertion]);
				$dosage_collection->push($node);
			}*/
		}

        // get the mim names
        $mim_names = MIM::whereIn('mim', $mims)->get();

        $mims = [];

        foreach ($mim_names as $mim)
            $mims[$mim->mim] = $mim->title;

        // get the pmids
        $pmid_names = Pmid::whereIn('pmid', $pmids)->get();

        $pmids = [];

        foreach($pmid_names as $pmid)
            $pmids[$pmid->pmid] = ['title' => $pmid->sortfirstauthor . ', et al, ' . $pmid->pubdate . ', ' . $pmid->title,
                               //     'author' => $pmid->sortfirstauthor,
                                //    'published' =>  $pmid->pubdate,
                                    'abstract' => $pmid->abstract];


		// reapply any sorting requirements
		$validity_collection = $validity_collection->sortByDesc('order');

         // we don't do any special sorting on variant path at this time
         if ($record->nvariant > 0)
            $variant_collection = collect($record->variant);

        $validity_panels = [];
        $validity_collection->each(function ($item) use (&$validity_panels){
            if (!in_array($item->assertion->attributed_to->label, $validity_panels))
                array_push($validity_panels, $item->assertion->attributed_to->label);
        });


        $validity_eps = count($validity_panels);

        //$actionability_collection = $actionability_collection->sortByDesc('order');

        if ($record->nvariant > 0)
            $variant_collection = collect($record->variant);

        // collect all the unique panels
        $variant_panels = [];
        $variant_collection->each(function ($item) use (&$variant_panels){
            $variant_panels = array_merge($variant_panels, array_column($item['panels'], 'affiliation'));
        });

        $variant_panels = array_unique($variant_panels);

        $vceps = Disease::curie($id)->first()->panels->where('type', PANEL::TYPE_VCEP);
        $gceps = Disease::curie($id)->first()->panels->where('type', PANEL::TYPE_GCEP);
        $pregceps = collect();

		if ($record->curation_status !== null)
		{
			foreach ($record->curation_status as $precuration)
			{
                switch ($precuration['status'])
				{
					case 'Uploaded':
                    case "Disease Entity Assigned":
                    case "Disease entity assigned":
                        $bucket = 3;
                        break;
                    case "Precuration":
                    case "Precuration Complete":
                        $bucket = 1;
                        break;
                    case "Curation Provisional":
                    case "Curation Approved":
                        $bucket = 2;
                        break;
                    case "Retired Assignment":
                    case "Published":
                        continue 2;
				}

				//if ($precuration['status'] == "Retired Assignment" || $precuration['status'] == "Published")
				//	continue;

				if (empty($precuration['group_id']))
					$panel = Panel::where('title_abbreviated', $precuration['group'])->first();
				else
					$panel = Panel::allids($precuration['group_id'])->first();

				if ($panel == null)
				{
					//dd($precuration);
					continue;
				}

                // blacklist panels we don't want displayed
                if ($panel->affiliate_id == "40018" || $panel->affiliate_id == "40019")
                    continue;

                $panel->bucket = $bucket;

				$pregceps->push($panel);
			}

            $remids = $gceps->pluck('id');
			$pregceps = $pregceps->whereNotIn('id', $remids);
		}

     //   dd($record);

		// set display context for view
		$display_tabs = collect([
			'active' => "gene",
			'title' => $record->label . " curation results"
		]);

        $total_panels = /*$validity_eps + count($variant_panels)
                        + ($record->ndosage > 0 ? 1 : 0)
                        + ($actionability_collection->isEmpty() ? 0 : 1)
                        +*/ count($pregceps);

		return view('condition.by-gene', compact('display_tabs', 'record', 'user', 'variant_collection',
                                                    'pregceps', 'pmids', 'mims', 'mimflag', 'validity_collection',
                                                    'total_panels'));
	}


    /**
	 * Display the specified conditions, precuration activity.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show_groups(Request $request, $id = null)
	{

		if ($id === null)
			return view('error.message-standard')
			->with('title', 'Error retrieving Disease details')
			->with('message', 'The system was not able to retrieve details for this Disease. Please return to the previous page and try again.')
			->with('back', url()->previous())
				->with('user', $this->user);

        $disease = Disease::rosetta($id);

        if ($disease === null)
            return view('error.message-standard')
                    ->with('title', 'Error retrieving Disease details')
                    ->with('message', 'The system was not able to retrieve details for this Disease. Please return to the previous page and try again.')
                    ->with('back', url()->previous())
                    ->with('user', $this->user);

        $id = $disease->curie;

		$record = GeneLib::conditionDetail([
                                    'condition' => $id,
                                    'curations' => true,
                                    'action_scores' => true,
                                    'validity' => true,
                                    'dosage' => true,
                                    'variant' => true
                                    ]);

        if ($record === null)
            return view('error.message-standard')
            ->with('title', 'Error retrieving Disease details')
            ->with('message', 'The system was not able to retrieve details for this Disease.  Error message was: ' . GeneLib::getError() . '. Please return to the previous page and try again.')
            ->with('back', url()->previous())
            ->with('user', $this->user);

        //reformat the response structure for view by activity
        $validity_collection = collect();
        $variant_collection = collect();

        foreach ($record->genetic_conditions as $key => $disease)
		{
			// actionability
			/*foreach ($disease->actionability_assertions as $assertion)
			{
				$node = new Nodal([	'order' => $this->actionability_sort_order[$assertion->classification->label] ?? 0,
									'disease' => $disease->disease, 'assertion' => $assertion]);
				$actionability_collection->push($node);
			}*/

			// validity
			foreach ($disease->gene_validity_assertions as $assertion)
			{
				$node = new Nodal([	'order' => $this->validity_sort_order[$assertion->classification->curie] ?? 0,
									'gene' => $disease->gene, 'assertion' => $assertion]);
				$validity_collection->push($node);
			}

			// dosage
			/*foreach ($disease->gene_dosage_assertions as $assertion)
			{
				$node = new Nodal([	'order' => $assertion->dosage_classification->oridinal ?? 0,
									'disease' => $disease->disease, 'assertion' => $assertion]);
				$dosage_collection->push($node);
			}*/
		}

		// reapply any sorting requirements
		$validity_collection = $validity_collection->sortByDesc('order');

         // we don't do any special sorting on variant path at this time
         if ($record->nvariant > 0)
            $variant_collection = collect($record->variant);

        $validity_panels = [];
        $validity_collection->each(function ($item) use (&$validity_panels){
            if (!in_array($item->assertion->attributed_to->label, $validity_panels))
                array_push($validity_panels, $item->assertion->attributed_to->label);
        });


        $validity_eps = count($validity_panels);

        //$actionability_collection = $actionability_collection->sortByDesc('order');

        if ($record->nvariant > 0)
            $variant_collection = collect($record->variant);

        // collect all the unique panels
        $variant_panels = [];
        $variant_collection->each(function ($item) use (&$variant_panels){
            $variant_panels = array_merge($variant_panels, array_column($item['panels'], 'affiliation'));
        });

        $variant_panels = array_unique($variant_panels);

        $vceps = Disease::curie($id)->first()->panels->where('type', PANEL::TYPE_VCEP);
        $gceps = Disease::curie($id)->first()->panels->where('type', PANEL::TYPE_GCEP);
        $pregceps = collect();

		if ($record->curation_status !== null)
		{
			foreach ($record->curation_status as $precuration)
			{
                switch ($precuration['status'])
				{
					case 'Uploaded':
                    case "Disease Entity Assigned":
                    case "Disease entity assigned":
                        $bucket = 3;
                        break;
                    case "Precuration":
                    case "Precuration Complete":
                        $bucket = 1;
                        break;
                    case "Curation Provisional":
                    case "Curation Approved":
                        $bucket = 2;
                        break;
                    case "Retired Assignment":
                    case "Published":
                        continue 2;
				}

				//if ($precuration['status'] == "Retired Assignment" || $precuration['status'] == "Published")
				//	continue;

				if (empty($precuration['group_id']))
					$panel = Panel::where('title_abbreviated', $precuration['group'])->first();
				else
					$panel = Panel::allids($precuration['group_id'])->first();

				if ($panel == null)
				{
					//dd($precuration);
					continue;
				}

                // blacklist panels we don't want displayed
                if ($panel->affiliate_id == "40018" || $panel->affiliate_id == "40019")
                    continue;

                $panel->bucket = $bucket;

				$pregceps->push($panel);
			}

            $remids = $gceps->pluck('id');
			$pregceps = $pregceps->whereNotIn('id', $remids);
		}

        //dd($pregceps);

		// set display context for view
		$display_tabs = collect([
			'active' => "gene",
			'title' => $record->label . " curation results"
		]);

        $total_panels = /*$validity_eps + count($variant_panels)
                        + ($record->ndosage > 0 ? 1 : 0)
                        + ($actionability_collection->isEmpty() ? 0 : 1)
                        +*/ count($pregceps);

		return view('condition.show-groups', compact(
			'display_tabs',
			'record',
			'validity_collection',
			'variant_collection',
            'variant_panels',
			//'group_collection',
			'gceps',
			'vceps',
            'pregceps',
            'total_panels'

		))
			->with('user', $this->user);
	}


	/**
	* Display the External Genomic Resources section of the specific condition..
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function external(Request $request, $id = null)
	{
		if ($id === null)
			return view('error.message-standard')
				->with('title', 'Error retrieving Disease details')
				->with('message', 'The system was not able to retrieve details for this Disease. Please return to the previous page and try again.')
				->with('back', url()->previous()
				->with('user', $this->user));


		$record = GeneLib::conditionDetail([
											'condition' => $id,
											'curations' => true,
											'action_scores' => true,
											'validity' => true,
											'dosage' => true
										]);

		if ($record === null)
			return view('error.message-standard')
						->with('title', 'Error retrieving Disease details')
						->with('message', 'The system was not able to retrieve details for this Disease.  Error message was: ' . GeneLib::getError() . '. Please return to the previous page and try again.')
						->with('back', url()->previous())
						->with('user', $this->user);

		// set display context for view
		$display_tabs = collect([
			'active' => "condition",
			'title' => $record->label . " Disease External Resources"
		]);

		$user = $this->user;

		return view('condition.show-external-resources', compact('display_tabs', 'record', 'user'));
	}


	/**
	* Display a listing of all genes.
	*
	* @return \Illuminate\Http\Response
	*/
	public function search(Request $request)
	{
		// process request args
		foreach ($request->only(['search']) as $key => $value)
			$$key = $value;

		// the way layouts is set up, everything is named search.  Condition is the second

		return redirect()->route('condition-index', ['page' => 1, 'size' => 50, 'search' => $search[1] ]);
	}
}
