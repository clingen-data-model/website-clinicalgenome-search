<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\Dosage as DosageResource;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Mail;

use App\DataExchange\Exceptions\StreamingServiceException;

//require base_path() . '/vendor/autoload.php';


use App\GeneLib;
use App\Term;
use App\Jira;
use App\Jirafield;
use App\Curation;
use App\Gene;
use App\User;
use App\Graphql;
use App\Nodal;
use App\Report;
use App\Title;
use App\Notification;
use App\Actionability;
use App\Panel;
use App\Slug;
use App\Mysql;

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


	public function survey()
    {
		return view('survey');
	}

	public function ccid($id)
	{
		if (substr($id, 0, 5) == 'CCID:') {
            $s = Slug::alias($id)->first();

            if ($s === null || $s->target === null)
                return view('error.message-standard')
                    ->with('title', 'Error retrieving Gene Validity details')
                    ->with('message', 'The system was not able to retrieve details for this Disease. Please return to the previous page and try again.')
                    ->with('back', url()->previous())
                    ->with('user', $this->user);

            $id = $s->target;
        }

		return redirect()->route('validity-show', ['id' => $id]);
	}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

		ReportController::summary_report($request);

		// get all the genes with active curations
		/*$genes = Gene::whereHas('curations', function ($query) {
			$query->whereIn('status', [Curation::STATUS_ACTIVE, Curation::STATUS_ACTIVE_REVIEW]);
		})->with('curations')->orderBy('name')->get();

		foreach ($genes as $gene)
		{
			dd($gene);
		}*/

		$results = Mysql::geneListForExportReport(
										['page' => 0,
										'pagesize' => null,
										'sort' => 'GENE_LABEL',
										'direction' => 'ASC',
										'search' => '',
                                        'include_lump_split' => true,
                                        'curated' => true ]);

		dd($results);

        /*$id = "CGGCIEX:assertion_3210";

        $g = Graphql::newValidityDetail(['page' => 0,
                        'pagesize' => 20,
                        'perm' => $id
                        ]);

        dd($g);

        exit;

        $a = Panel::all();

        //CG-PCER-AGENT:CG_50015_EP.1551905782.01949
        foreach($a as $item)
        {
            $t = $item->affiliate_id;
            //if (strpos($t, "CG-PCER-AGENT:CG_") === 0)
            //if (strpos($t, "CGAGENT:") === 0)
            if ($item->alternate_id !== null)
            {
                //$t = substr($t, strlen("CG-PCER-AGENT:CG_"));

                //$k = strpos($t, '_');

               // $k = intval($t) + 40000;

                $item->affiliate_id = intval($item->alternate_id) + 30000;

                //$item->alternate_id = $t;

            }

            //$item->title_short = $item->name;
            $item->save();
        }
        /*
        $data = file_get_contents('http://purl.obolibrary.org/obo/mondo/mondo-with-equivalents.json');

        $json = json_decode($data);

        $nodes = $json->graphs[0]->nodes;

        foreach ($nodes as $node)
        {
            dd($node);
        }


        $issue = Jira::getIssue('ISCA-4799', null);

        dd($issue->names);
        //$changelog = Jira::getIssue('ISCA-4799', 'changelog');

        foreach ($issue->names as $key => $value)
        {
            Jirafield::updateOrCreate(['field' => $key], ['label' => $value, 'status' => 1]);
        }

        // map issue to curation record
        $record = Curation::map($issue);

        foreach ($issue->changelog->histories as $history)
        {
            //dd($history);
        }

        $a = array_reverse($issue->changelog->histories);

        dd($a); */

        //dd($issue->changelog);
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
