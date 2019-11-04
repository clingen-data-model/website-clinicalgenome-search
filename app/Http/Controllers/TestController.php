<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
