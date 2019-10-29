<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Alexaandrov\GraphQL\Facades\Client as Genegraph;

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
	}
	
	
	/**
	 * 	Run a test sequence against genegraph
	 * 
	 */
	public function test(Request $request)
	{
		$query = <<<QUERY
{
	gene(iri: "HGNC:6407") {
		label
		conditions {
			iri
			label
			actionability_curations {
				report_date
				source
			}
		}     
	}
}
QUERY;

		$queryResponse = Genegraph::fetch($query);
		
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
