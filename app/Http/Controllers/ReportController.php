<?php

namespace App\Http\Controllers;

use App\Exports\GenesCuratedExport;
use App\Exports\AcmgCuratedExport;
use Illuminate\Http\Request;
use App\Http\Resources\Dosage as DosageResource;
use Maatwebsite\Excel\Facades\Excel as Gexcel;

use Auth;

use App\GeneLib;
use App\Gene;
use App\Metric;
use App\Disease;
use App\Curation;

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

	public function statistics($sort = 'label')
	{
		// set display context for view
		$display_tabs = collect([
			'active' => "stats",
			'title' => "ClinGen Gene Level Summary Statistics"
		]);

		$metrics = Metric::latest('id')->first();

        if ($sort == 'count')
            $gceps = collect($metrics->values[Metric::KEY_EXPERT_PANELS])->sortByDesc($sort)->toArray();
        else
            $gceps = collect($metrics->values[Metric::KEY_EXPERT_PANELS])->sortBy('label')->toArray();

        if ($sort == 'count')
            $vceps = collect($metrics->values[Metric::KEY_EXPERT_PANELS_PATHOGENICITY])->sortByDesc($sort)->toArray();
        else
            $vceps = collect($metrics->values[Metric::KEY_EXPERT_PANELS_PATHOGENICITY])->sortBy('label')->toArray();


		return view('reports.stats.index', compact('display_tabs', 'metrics', 'sort', 'gceps', 'vceps'))
						->with('user', $this->user);
	}

	public function genesReportDownload()
	{
		$date = date('Y-m-d');
		return Gexcel::download(new GenesCuratedExport, 'Clingen-Curation-Activity-Summary-Report-' . $date . '.csv');
	}


	public function acmgReportDownload()
	{
		$date = date('Y-m-d');
		return Gexcel::download(new AcmgCuratedExport, 'Clingen-Curation-ACMG-Summary-Report-' . $date . '.csv');
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

		//dd($queryResponse);

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
