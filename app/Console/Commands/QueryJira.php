<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

use App\Jira;

class QueryJira extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'query:jira';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        //$results = Jira::getIssues('project = ISCA AND issuetype = "ISCA Gene Curation" AND "HGNC ID"  is EMPTY');


		$record = Jira::getIssue('ISCA-4799');
        $record = Jira::getHistory('ISCA-4799');

        /*$response = Jira::getIssues('project = ISCA AND issuetype in ("ISCA Region Curation") AND Resolution = Complete');

          if (empty($response))
               return $collection;
;
          foreach ($response->issues as $issue)
          {
               dd($issue);
          }*/
		dd($record);

		echo "Update Complete\n";
	}
}
