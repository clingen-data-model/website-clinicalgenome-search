<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

use App\Gene;
use App\Jira;

class UpdateJira2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:jira2';

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
        /*  For DCI, the values are:
        ** Gene Symbol:  10030
		** HGNC_ID: 12230
		** Previous Gene Symbol:  12430
		*/
exit;
		$this->update1();

		echo "Update Complete\n";
	}


	public function addorupdate($db, $issue, $values)
	{
		foreach ($values as $field => $value)
		{
			$record = $db->select('select * from customfieldvalue where customfield = ? and issue = ?', [ (int) $field, $issue]);

			$ts = time() . '000';

			// add/update HGNC, pLI, PLEUF, and HI
			if (empty($record))
			{
				$maxValue = $db->table('customfieldvalue')->max('id');
				$db->insert('insert into customfieldvalue (id, issue, customfield, stringvalue, updated) values (?, ?, ?, ?, ?)', [$maxValue + 1, $issue, (int) $field, $value, $ts]);
			}
			else
			{
				$db->update('update customfieldvalue set stringvalue = ? where id = ?', [$value, $record[0]->ID]);
			}
		}
	}

	/*
$db = DB::connection('jira');

		$genes = Gene::where('locus_group', "protein-coding gene")->get();

		foreach ($genes as $gene)
		{
			$symbol = $gene->name;

			echo "Updating " . $symbol . "...\n";

			// find the issue associated with the gene symbol
			$record = $db->select('select * from customfieldvalue where customfield = ? and stringvalue = ?', [10030, $symbol]);

            // Do NOT add new genes!
            if (empty($record))
            {
                echo "Symbol " . $symbol . "not found\n";
                continue;
            }

			$issue = $record[0]->ISSUE ?? null;
			$entry = $record[0];

			// add or update the HGNC_ID
			$this->addorupdate($db, $issue, ['12230' => $gene->hgnc_id,
											 '11635' => $gene->pli,
											 '12244' => $gene->plof,
											 '12243' => $gene->hi,
											 '10532' => 'chr' . $gene->chr . ':' . $gene->start38 . '-' . $gene->stop38]);

		}

		echo "Update Complete\n";
	*/


    public function update1()
    {
        $results = Jira::getIssues('project = ISCA AND issuetype = "ISCA Gene Curation" AND "Gene Type" = pseudo AND "HGNC ID"  is EMPTY');

		echo "Processing " . count($results->issues) . " responses\n";

        foreach ($results->issues as $issue)
		{
			$record = Jira::getIssue($issue->key);

			$symbol = $record->customfield_10030;

			//echo "Checking " . $symbol . "\n";

			// verify that this gene is not current
			$gene = Gene::name($symbol)->first();

			if ($gene !== null)
			{
                // update just the hgncid field
                $key = Jira::updateIssue($issue->key, 'customfield_12230', $gene->hgnc_id);
                echo $symbol . " (" . $issue->key . ") updated hgnc \n";
				continue;
			}

			// check for previous symbols
			$gene = Gene::whereJsonContains('prev_symbol', [$symbol])->first();

			if ($gene !== null)
			{
                // update the hgncid and original symbol fields
                $key = Jira::updateIssue($issue->key, 'customfield_12230', $gene->hgnc_id);
                $key = Jira::updateIssue($issue->key, 'customfield_12430', $symbol);
                echo $symbol . " (" . $issue->key . ") updated hgnc \n";
				continue;
			}

			$gene = Gene::whereJsonContains('alias_symbol', [$symbol])->first();

			if ($gene !== null)
			{
                // update the hgncid and original symbol fields
                $key = Jira::updateIssue($issue->key, 'customfield_12230', $gene->hgnc_id);
                $key = Jira::updateIssue($issue->key, 'customfield_12430', $symbol);
                echo $symbol . " (" . $issue->key . ") updated hgnc \n";
				continue;
			}

			//echo $symbol . " (" . $issue->key . ") Not Found \n";
		}

		echo "Update Complete\n";
	}


    public function update2()
    {
        /*  For DCI, the values are:
        ** Gene Symbol:  10030
		** HGNC_ID: 12230
		** Previous Gene Symbol:  12430
		*/

		//$results = Jira::getIssues('project = ISCA AND issuetype = "ISCA Gene Curation" AND "HGNC ID"  is not EMPTY');
        $results = Jira::getIssues('project = ISCA AND issuetype = "ISCA Gene Curation" AND "Gene Type" = ncRNA AND "HGNC ID"  is not EMPTY');

		echo "Processing " . count($results->issues) . " responses\n";

        foreach ($results->issues as $issue)
		{
			$record = Jira::getIssue($issue->key);

            echo "Checking " . $issue->key . "\n";

			$symbol = $record->customfield_10030;
            $prev = $record->customfield_12430 ?? null;
            $hgnc = $record->customfield_12230;

			//echo "Checking " . $symbol . "\n";

			// verify that this gene is not current
			$gene = Gene::hgnc($hgnc)->first();

			if ($gene === null)
			{
                echo "$symbol ($hgnc) not in genenames.\n";
				continue;
			}

            if ($prev === null)
                    $key = Jira::updateIssue($issue->key, 'customfield_12430', $symbol);

			if ($gene->name !== $symbol)
			{
                // update gene symbol
                $key = Jira::updateIssue($issue->key, 'customfield_10030', $gene->name);

                echo $symbol . " (" . $issue->key . ") updated to " . $gene->name . " \n";
				//exit;
			}

		}

		echo "Update Complete\n";
	}
}
