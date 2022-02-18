<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Pmid;

class EfetchPubmed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pubmed:efetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use Efetch to retrieve additional information';

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
		// set up search array
		$parms = ['db' => 'pubmed', 'id' => '',
				  'api_key' => '59e073ce6f18becd93e36bd2613dfde47509',
				  'retmode' => 'xml'];

        // Get the query strings
        $querys = Pmid::where('status', 21)->get();

		foreach ($querys as $query)
		{
			echo "updating {$query->pmid} ...\n";

            if (empty($query->pmid))
                continue;

			$parms['id'] = $query->pmid;
        	$encoded_parms = http_build_query($parms);
			$results = file_get_contents('https://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?' . $encoded_parms);

			if ($results === false)
			{
				continue;
			}

			//echo $results;
			$xml = new \SimpleXMLElement($results);

            if (empty($xml));
                continue;


			if (isset($xml->PubmedBookArticle->BookDocument->Abstract->AbstractText))
				$abstract = $xml->PubmedBookArticle->BookDocument->Abstract->AbstractText;
			else
				$abstract = $xml->PubmedArticle->MedlineCitation->Article->Abstract->AbstractText;


			$query->status = 1;		// 3change back to 1 for daily post
			//$query->user_id = 1;	// remove this for daily post
			$query->update(['efetch' => $results, 'abstract' => $abstract]);
		}

    }
}
