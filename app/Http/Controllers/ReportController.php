<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Dosage as DosageResource;

use Auth;

use App\GeneLib;
use App\Metric;

class ReportController extends Controller
{
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		/*$b = Jira::updateIssue("ISCA-21328");

		dd($b);
		return DosageResource::collection($c);*/
	}

	public function statistics()
	{
		// set display context for view
		$display_tabs = collect([
			'active' => "stats",
			'title' => "ClinGen Gene Level Summary Statistics"
		]);

		$metrics = Metric::latest()->first();

		return view('reports.stats.index', compact('display_tabs', 'metrics'))
						->with('user', $this->user);
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
