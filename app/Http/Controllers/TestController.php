<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Dosage as DosageResource;

use App\GeneLib;

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
		$a = GeneLib::dosageList(['page' => $input['offset'] ?? 0,
										'pagesize' => $input['limit'] ?? "null",
										'sort' => $sort ?? 'GENE_LABEL',
										'direction' => $input['order'] ?? 'ASC',
										'search' => $input['search'] ?? null,
										'curated' => true ]);

		$b = GeneLib::regionList([	'page' =>  0,
		'pagesize' =>  "null",
		'sort' => 'GENE_LABEL',
		'direction' =>  'ASC',
		'search' =>  null,
		'curated' => false ]);

		$c = $a->collection->concat($b->collection);
		//dd($c->count());
		return DosageResource::collection($c);
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
