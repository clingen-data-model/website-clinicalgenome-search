<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\GeneListRequest;

use Auth;

use App\GeneLib;
use App\User;
use App\Gene;
use App\Nodal;

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
*
* */
class GeneController extends Controller
{
	private $api = '/api/genes';
	private $api_curated = '/api/curations';
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

	protected $actionability_sort_value = [
		'Definitive Actionability' => 20,
		'Strong Actionability' => 19,
		'Moderate Actionability' => 18,
		'Limited Actionability' => 17,
		'Insufficient Actionability' => 16,
		'No Actionability' => 15,
		'Assertion Pending' => 14,				// 0003541
		'Has Insufficient Evidence for Actionability Based on Early Rule-out' => 13, 		//0003539
		'N/A - Insufficient evidence: early rule-out' => 13
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
	* Display a listing of all genes.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index(GeneListRequest $request, $page = 1, $size = 25, $search = "")
	{
		// process request args
		foreach ($request->only(['page', 'size', 'order', 'sort', 'search']) as $key => $value)
			$$key = $value;

		// set display context for view
        $display_tabs = collect([
            'active' => "gene",
            'title' => "Genes"
		]);
		
		$display_list = ($this->user === null ? 25 : $this->user->preferences['display_list'] ?? 25);

		return view('gene.index', compact('display_tabs'))
						->with('apiurl', $this->api)
						->with('pagesize', $size)
						->with('page', $page)
						->with('search', $search)
						->with('user', $this->user)
						->with('display_list', $display_list);
	}


	/**
	* Show all of the curated genes
	*
	* @return \Illuminate\Http\Response
	*/
	public function curated(GeneListRequest $request, $page = 1, $size = 50)
	{
		// process request args
		foreach ($request->only(['page', 'size', 'order', 'sort', 'search']) as $key => $value)
			$$key = $value;

		// set display context for view
        $display_tabs = collect([
            'active' => "gene-curations",
            'title' => "ClinGen Curated Genes"
        ]);

		$display_list = ($this->user === null ? 25 : $this->user->preferences['display_list'] ?? 25);

		return view('gene.curated', compact('display_tabs'))
						->with('apiurl', $this->api_curated)
						->with('pagesize', $size)
						->with('page', $page)
						->with('user', $this->user)
						->with('display_list', $display_list);
	}


	/**
	* Display the specified gene, organized by disease.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function show_by_disease(Request $request, $id = null)
	{
		if ($id === null)
			return view('error.message-standard')
				->with('title', 'Error retrieving Gene details')
				->with('message', 'The system was not able to retrieve details for this Gene. Please return to the previous page and try again.')
				->with('back', url()->previous())
				->with('user', $this->user);


		$record = GeneLib::geneDetail([
										'gene' => $id,
										'curations' => true,
										'action_scores' => true,
										'validity' => true,
										'dosage' => true
									]);

		if ($record === null)
			return view('error.message-standard')
						->with('title', 'Error retrieving Gene details')
						->with('message', 'The system was not able to retrieve details for this Gene.  Error message was: ' . GeneLib::getError() . '. Please return to the previous page and try again.')
						->with('back', url()->previous())
                        ->with('user', $this->user);

		$disease_collection = collect();

		foreach ($record->genetic_conditions as $key => $disease)
		{
			$node = new Nodal([	'disease' => $disease->disease->label, 'validity' => null]);

			$validity_collection = collect();

			// validity
			foreach ($disease->gene_validity_assertions as $assertion)
			{
				$assertion_node = new Nodal(['order' => $this->validity_sort_order[$assertion->classification->curie] ?? 0,
									'disease' => $disease->disease, 'assertion' => $assertion]);
				$validity_collection->push($assertion_node);
			}

			// reapply any sorting requirements
			if ($validity_collection->isNotEmpty())
				$node->validity = $validity_collection->sortByDesc('order');

			$disease_collection->push($node);

		}

		//dd($disease_collection->where('disease', $disease->disease->label)->first()->validity);

		// set display context for view
		$display_tabs = collect([
			'active' => "gene",
			'title' => $record->label . " curation results"
		]);

		return view('gene.by-disease', compact('display_tabs', 'record', 'disease_collection'))
						->with('user', $this->user);;
	}


	/**
	* Display the specified gene, organized by condition.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function show_by_activity(Request $request, $id = null)
	{
		if ($id === null)
			return view('error.message-standard')
						->with('title', 'Error retrieving Gene details')
						->with('message', 'The system was not able to retrieve details for this Gene. Please return to the previous page and try again.')
						->with('back', url()->previous())
						->with('user', $this->user);


		$record = GeneLib::geneDetail([
									'gene' => $id,
									'curations' => true,
									'action_scores' => true,
									'validity' => true,
									'dosage' => true,
									'pharma' => true,
									'variant' => true
								]);

		if ($record === null)
			return view('error.message-standard')
						->with('title', 'Error retrieving Gene details')
						->with('message', 'The system was not able to retrieve details for this Gene.  Error message was: ' . GeneLib::getError() . '. Please return to the previous page and try again.')
						->with('back', url()->previous())
						->with('user', $this->user);

		// the new follow stuff.  protptype wip
		$follow = false;
		$email = '';
		$user = Auth::guard('api')->user();

		if (Auth::guard('api')->check())
		{
			$user = Auth::guard('api')->user();
			//dd($user);
			$follow = $user->genes->contains('hgnc_id', $id);
		}
		else
		{

			$cookie = $request->cookie('clingenfollow');

			if ($cookie !== null)
			{
				$user = User::cookie($cookie)->first();

				if ($user !== null)
				{
					$follow = $user->genes->contains('hgnc_id', $id);
					$email = $user->email;
				}
			}
		}
		// end follow

		//reformat the response structure for view by activity
		$actionability_collection = collect();
		$validity_collection = collect();
		$dosage_collection = collect();
		$variant_collection = collect();
		$pharma_collection = collect();

		foreach ($record->genetic_conditions as $key => $disease)
		{
			// actionability
			if (!empty($disease->actionability_assertions))
			{
				$adult = null;
				$pediatric = null;
				$order = 0;

				// regroup the adult and pediatric assertions
				foreach ($disease->actionability_assertions as $assertion)
				{
					if ($assertion->attributed_to->label == "Adult Actionability Working Group")
					{
						$adult = $assertion;
					}
					if ($assertion->attributed_to->label == "Pediatric Actionability Working Group")
					{
						$pediatric = $assertion;
					}

					$aorder = $this->actionability_sort_order[$assertion->classification->label] ?? 0;
					if ($aorder > $order)
						$order = $aorder;
				}

				$node = new Nodal([	'order' => $order,
										'disease' => $disease->disease, 'adult_assertion' => $adult,
										'pediatric_assertion' => $pediatric]);

				$actionability_collection->push($node);
			}
			//dd($actionability_collection);

			// validity
			foreach ($disease->gene_validity_assertions as $assertion)
			{
				$node = new Nodal([	'order' => $this->validity_sort_order[$assertion->classification->curie] ?? 0,
									'disease' => $disease->disease, 'assertion' => $assertion]);
				$validity_collection->push($node);
			}

			// dosage
			foreach ($disease->gene_dosage_assertions as $assertion)
			{
				$node = new Nodal([	'order' => $assertion->dosage_classification->oridinal ?? 0,
									'disease' => $disease->disease, 'assertion' => $assertion]);
				$dosage_collection->push($node);
			}
		}

		// reapply any sorting requirements
		$validity_collection = $validity_collection->sortByDesc('order');
		$actionability_collection = $actionability_collection->sortByDesc('order');

		if ($record->nvariant > 0)
			$variant_collection = collect($record->variant);

		// set display context for view
		$display_tabs = collect([
			'active' => "gene",
			'title' => $record->label . " curation results"
		]);

		return view('gene.by-activity', compact('display_tabs', 'record', 'follow', 'email', 'user',
												'validity_collection', 'actionability_collection',
												'variant_collection'))
												->with('user', $this->user);
	}


	/**
	 * Display the external resources section.  Since this is mostly static
	 * it wuld just get displayed in a tab with the gene.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function external(Request $request, $id = null)
	{
		if ($id === null)
			return view('error.message-standard')
				->with('title', 'Error retrieving Gene details')
				->with('message', 'The system was not able to retrieve details for this Gene. Please return to the previous page and try again.')
				->with('back', url()->previous())
				->with('user', $this->user);


		$record = GeneLib::geneDetail([
										'gene' => $id,
										'curations' => true,
										'action_scores' => true,
										'validity' => true,
										'dosage' => true
									]);

		if ($record === null)
			return view('error.message-standard')
						->with('title', 'Error retrieving Gene details')
						->with('message', 'The system was not able to retrieve details for this Gene.  Error message was: ' . GeneLib::getError() . '. Please return to the previous page and try again.')
						->with('back', url()->previous())
                        ->with('user', $this->user);

		// set display context for view
		$display_tabs = collect([
			'active' => "gene",
			'title' => $record->label . " external resources"
		]);
		
		return view('gene.show-external-resources', compact('display_tabs', 'record'))
						->with('user', $this->user);
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

		// the way layouts is set up, everything is named search.  Gene is the first

		return redirect()->route('gene-index', ['page' => 1, 'size' => 50, 'search' => $search[0] ]);
	}
}
