<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

use App\Gene;
use App\Jira;

class UpdateJira extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:jira {report=none}';

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
		** pLI:  11635
		** HI:  12243
        ** LOEUF: 12244
        ** GRCh38 Genomic Position:  10532

		** Gene Symbol:  10030
		** HGNC_ID: 12430
		** pLI:  11635
		** HI:  12431
		** LOEUF:
		** GRCh38 Genomic Position:  10532
		*/

//exit;  //MAINTENANCE ONLY SCRIPT - DO NOT RUN

		$report = $this->argument('report');

        switch ($report)
        {
            case 'pli':
                self::updatepli();
                echo "Report Complete\n";
                return;
			case 'loeuf':
				self::updateloeuf();
				echo "Report Complete\n";
				return;
			case 'grch38':
				self::updategrch38();
				echo "Report Complete\n";
				return;
            case 'grch37':
                self::updategrch37();
                echo "Report Complete\n";
                return;
            case 'none':
            default:
                break;
        }

		echo "Invalid report type\n";

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
	
		/* $results = Jira::getIssues('project = ISCA AND issuetype = "ISCA Gene Curation" AND "Gene Type" = protein-coding AND "HGNC ID"  is EMPTY');

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

		echo "Update Complete\n";
		*/
	}


	/**
     * This function updates the pli field in Jira
     */
    public static function updatepli()
    {
        $genes = Gene::whereNotNull('pli')->get();

        foreach ($genes as $gene)
		{
            $pli = round($gene->pli, 3);

            echo "Searching for $gene->hgnc_id";

            $results = Jira::getIssues('project = ISCA AND issuetype = "ISCA Gene Curation" AND "HGNC ID" ~ "' . $gene->hgnc_id . '"');

            foreach ($results->issues as $issue)
            {
			    $key  = $issue->key;

                $record = (object) $issue->fields->customFields;

                // gain phenotype ID is 10201, original gain id is 12631
                echo "...Processing Symbol " . $record->customfield_10030;

                if (isset($record->customfield_11635) && $record->customfield_11635 == $pli)
                {
                    echo "...pli same, skipping\n";
                    continue;
                }

                if (!isset($record->customfield_11635) && $pli == 0)
                {
                    echo "...pli same, skipping\n";
                    continue;
                }

                Jira::updateIssue($key, 'customfield_11635', $pli);

                echo "...changing from " . ($record->customfield_11635 ?? 0) . " to " . $pli . " ...DONE\n";

				//die("cp1");

            }

        }
        echo "\nPass 1 Complete";
    }


	/**
     * This function updates the loeuf field in Jira
     */
    public static function updateloeuf()
    {
        $genes = Gene::whereNotNull('pli')->get();

		$check = true;

        foreach ($genes as $gene)
		{
			if ($gene->name == "SPOCK2")
				$check = false;
			
			if ($check)
				continue;

            $loeuf = round($gene->plof, 2);

            echo "Searching for $gene->hgnc_id";

            $results = Jira::getIssues('project = ISCA AND issuetype = "ISCA Gene Curation" AND "HGNC ID" ~ "' . $gene->hgnc_id . '"');

            foreach ($results->issues as $issue)
            {
			    $key  = $issue->key;

                $record = (object) $issue->fields->customFields;

                // gain phenotype ID is 10201, original gain id is 12631
                echo "...Processing Symbol " . $record->customfield_10030;

                if (isset($record->customfield_12244) && $record->customfield_12244 == $loeuf)
                {
                    echo "...loeuf same, skipping\n";
                    continue;
                }

                if (!isset($record->customfield_12244) && $loeuf == 0)
                {
                    echo "...loeuf same, skipping\n";
                    continue;
                }

                Jira::updateIssue($key, 'customfield_12244', strval($loeuf));

                echo "...changing from " . ($record->customfield_12244 ?? 0) . " to " . $loeuf . " ...DONE\n";

				//die("cp1");

            }

        }
        echo "\nPass 1 Complete";
    }


	/**
     * This function updates the grch38 field in Jira
     */
    public static function updategrch38()
    {
        $genes = Gene::whereNotNull('start38')->get();

		$check = false;

        foreach ($genes as $gene)
		{
			
			if ($check)
				continue;

			if ($gene->chr == 23)
				$gene->chr = 'X';
			else if ($gene->chr == 24)
				$gene->chr = 'Y';

            $grch38 = 'chr' . $gene->chr . ':' . $gene->start38 . '-' . $gene->stop38;

            echo "Searching for $gene->hgnc_id";

            $results = Jira::getIssues('project = ISCA AND issuetype = "ISCA Gene Curation" AND "HGNC ID" ~ "' . $gene->hgnc_id . '"');

            foreach ($results->issues as $issue)
            {
			    $key  = $issue->key;

                $record = (object) $issue->fields->customFields;

                // gain phenotype ID is 10201, original gain id is 12631
                echo "...Processing Symbol " . $record->customfield_10030;

                if (isset($record->customfield_10532) && strcmp($record->customfield_10532, $grch38) == 0)
                {
                    echo "...grch38 same, skipping\n";
                    continue;
                }

                if (!isset($record->customfield_10532) && empty($grch38))
                {
                    echo "...grch38 same, skipping\n";
                    continue;
                }

                Jira::updateIssue($key, 'customfield_10532', $grch38);
				Jira::updateIssue($key, 'customfield_10537', $gene->seqid38);
				

                echo "...changing from " . ($record->customfield_10532 ?? 0) . " to " . $grch38 . " ...DONE\n";

				//die("cp1");

            }

        }
        echo "\nPass 1 Complete";
    }


    /**
     * This function updates the grch37 field in Jira
     */
    public static function updategrch37()
    {
        $genes = Gene::whereNotNull('start37')->get();

		$check = false;

        foreach ($genes as $gene)
		{
			
			if ($check)
				continue;

			if ($gene->chr == 23)
				$gene->chr = 'X';
			else if ($gene->chr == 24)
				$gene->chr = 'Y';

            $grch37 = 'chr' . $gene->chr . ':' . $gene->start37 . '-' . $gene->stop37;

            echo "Searching for $gene->hgnc_id";

            $results = Jira::getIssues('project = ISCA AND issuetype = "ISCA Gene Curation" AND "HGNC ID" ~ "' . $gene->hgnc_id . '"');

            foreach ($results->issues as $issue)
            {
			    $key  = $issue->key;

                $record = (object) $issue->fields->customFields;

                // gain phenotype ID is 10201, original gain id is 12631
                echo "...Processing Symbol " . $record->customfield_10030;

                if (isset($record->customfield_10160) && strcmp($record->customfield_10160, $grch37) == 0)
                {
                    echo "...grch37 same, skipping\n";
                    continue;
                }

                if (!isset($record->customfield_10160) && empty($grch37))
                {
                    echo "...grch37 same, skipping\n";
                    continue;
                }

                Jira::updateIssue($key, 'customfield_10160', $grch37);
				Jira::updateIssue($key, 'customfield_10158', $gene->seqid37);
				

                echo "...changing from " . ($record->customfield_10160 ?? 0) . " to " . $grch37 . " ...DONE\n";

				//die("cp1");

            }

        }
        echo "\nPass 1 Complete";
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
}
