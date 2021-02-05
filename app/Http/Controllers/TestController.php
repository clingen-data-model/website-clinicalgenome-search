<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Dosage as DosageResource;

use App\GeneLib;
use App\Jira;
use App\Gene;
use App\Graphql;

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
		Graphql::geneMetrics([]);
		/*$results = Jira::getIssues('project = ISCA AND issuetype = "ISCA Gene Curation" AND "Gene Type" = protein-coding AND "HGNC ID"  is EMPTY');
		
		foreach ($results->issues as $issue)
		{
			$record = Jira::getIssue($issue->key);

			$symbol = $record->customfield_10030;

			echo "Checking " . $symbol . "\n";

			// verify that this gene is not current
			$gene = Gene::name($symbol)->first();

			if ($gene !== null)
			{
				echo $symbol . " is a current symbol issue  " . $issue->key . "\n";
				continue;
			}

			// check for previous symbols
			$gene = Gene::whereJsonContains('prev_symbol', [$symbol])->first();

			if ($gene === null)
			{
				echo $symbol . " has no previous symbol  " . $issue->key . "\n";
				continue;
			}

			echo $symbol . " has a symbol of " . $gene->name . " key: " . $issue->key . "\n";
		}
		*/

		die("done");
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
