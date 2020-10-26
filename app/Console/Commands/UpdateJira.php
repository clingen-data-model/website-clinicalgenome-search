<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Gene;
use App\Jira;

class UpdateJira extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:jira';

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
        echo "Importing uniprot function information ...\n";

        $current = ['gn' => null, 'fn' => [], 'ac' => null];
        $state = 0;
		
        $genes = Gene::all();

        foreach ($genes as $gene)
        {
            $issueKey = "ISCA-19534";

            try {			
                $issueField = new IssueField(true);
dd($issueField);
                $issueField->addCustomField('customfield_12430', 'NA');

                // optionally set some query params
                $editParams = [
                    'notifyUsers' => false,
                ];

                $issueService = new IssueService();

                // You can set the $paramArray param to disable notifications in example
                $ret = $issueService->update($issueKey, $issueField, $editParams);

                var_dump($ret);
            } catch (JiraRestApi\JiraException $e) {
                $this->assertTrue(FALSE, "update Failed : " . $e->getMessage());
            }
        }
    }
}
