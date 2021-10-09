<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\GeneLib;
use App\Nodal;
use App\User;
use App\Filter;
use App\Disease;

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
        if (strpos($id, "MONDO:") !== 0)
        {
            $check = Disease::omim($id)->first();

            if ($check !== null)
                $id = $check->curie;
        }

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

		// set display context for view
		$display_tabs = collect([
			'active' => "condition",
			'title' => $record->label . " curation results by ClinGen activity"
		]);

		return view('condition.by-activity', compact('display_tabs', 'record', 'validity_collection',
                                                    'variant_collection'));
	}


	/**
	 * Display the specified condition.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show_by_gene(Request $request, $id = null)
	{
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

        $variant_collection = collect();

        // we don't do any special sorting on variant path at this time
        if ($record->nvariant > 0)
			$variant_collection = collect($record->variant);

		// set display context for view
		$display_tabs = collect([
			'active' => "condition",
			'title' => $record->label . " curation results organized by gene"
		]);

		$user = $this->user;

		return view('condition.by-gene', compact('display_tabs', 'record', 'user', 'variant_collection'));
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
