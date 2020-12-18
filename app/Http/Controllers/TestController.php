<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Dosage as DosageResource;

use App\GeneLib;
use App\Jira;
use App\Gene;

class TestController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
			/*$genes = Gene::get(['name as symbol', 'description as name', 'hgnc_id', 'date_last_curated as last_curated_date', 'activity as curation_activities']);
			// add each gene to the collection
//dd($genes);
			
				$naction = 0;
			$nvalid = 0;
			$ndosage = 0;*/

			$results = GeneLib::geneList([	'page' =>  0,
										'pagesize' => "null",
										'sort' => 'GENE_LABEL',
                                        'direction' => 'ASC',
                                        'search' =>  null,
										'curated' => false ]);
										
			dd($results->collection->first());
			return view('new-dosage.reports');
	}

	public function statistics()
	{
		// set display context for view
		$display_tabs = collect([
			'active' => "stats",
			'title' => "ClinGen Gene Level Summary Statistics"
		]);
		return view('reports.stats.index', compact('display_tabs'));
	}


	public function reports()
    {
		return view('new-dosage.reports');
	}

	public function stats()
    {
		return view('new-dosage.stats');
	}


	public function show()
    {
		return view('new-dosage.show');
	}


	public function download()
    {
		return view('new-dosage.downloads');
	}


	/**
	 * 	Run a test sequence against genegraph
	 *
	 */
	public function test(Request $request)
	{
		$queryResponse = GeneLib::actionabilityList(['iri' => "HGNC:6407"]);

		dd($queryResponse);

		/*
			foreach ($queryResponse->users as $user) {
				// Do something with the data
				$user->id;
				$user->email;
			}
		*/

		return;
	}
}
