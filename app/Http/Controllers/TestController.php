<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Dosage as DosageResource;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Mail;


use App\GeneLib;
use App\Jira;
use App\Gene;
use App\User;
use App\Graphql;
use App\Nodal;
use App\Report;
use App\Title;
use App\Notification;
use App\Actionability;

use App\Mail\NotifyFrequency;
//use App\Neo4j;

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

		//Graphql::geneMetrics([]);

		//$response = Neo4j::geneList(['pagesize' => null])

		//$history = 'my history notes';
		//$user = User::find(8);

		// send out notification (TODO move this to a queue)
		//Mail::to($user)
			// ->cc($moreUsers)
			// ->bcc($evenMoreUsers)
	//		->send(new NotifyFrequency(['notes' => $history, 'name' => $user->name, 'content' => 'this is the custom message']));
	//return;
/* $query = <<<GQL
{
	genes(limit: null) {
		count
		gene_list {
			label
			alternative_label
			hgnc_id
			last_curated_date
			curation_activities
		}
	  }
}
GQL;

try {
	$response = Http::withHeaders([
		'Content-Type' => 'application/json',
	])->timeout(1)->post('http://ds-stage.clingen.info/graphql', [
		'query' => $query
	]);
} catch (\Illuminate\Http\Client\ConnectionException $e) {
	die ("timeout");
} catch (Exception $e) {
	die ("exception");

}*/

/*
Illuminate\Http\Client\ConnectionException
cURL error 28: Operation timed out after 1001 milliseconds with 0 bytes received (see https://curl.haxx.se/libcurl/c/libcurl-errors.html)
*/

//dd($response->json());

		/*$users = User::has('genes')->with('genes')->get();
		$a = new Report();

		foreach ($users as $user)
		{
			$notify = $user->notification;
			if ($notify === null)
				continue;

			$lists = $notify->toReport();

			if (empty($lists))
				continue;

			$title = new Title(['type' => 1, 'title' => 'Change Report', 'status' => 1]);

			$user->titles()->save($title);

			foreach ($lists as $list)
			{
				$report = new Report($list);
				$report->type = 1;
				$report->status = 1;
				$report->user_id = $user->id;
				$title->reports()->save($report);
			}
			//dd($title->reports);
			$a = $title->run();
			dd($a);
		}

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
		//return view('new-dosage.reports');
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
