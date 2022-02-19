<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Setting;

use App\Pmid;
use App\Task;

class QueryPubmed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pubmed:query';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch summary information related to PubMed IDs';

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
        $key = Setting::get('pubmed', false);

        if (!$key)
        {
            echo "\n(E002) Error retreiving Pubmed key\n";
            exit;
        }

		// set up search array
		$parms = ['db' => 'pubmed', 'id' => '',
				  'api_key' => $key,
				  'retmode' => 'json'];

        // Get the query strings
        $querys = Pmid::where('status', 20)->get();

		foreach ($querys as $query)
		{
            if (empty($query->pmid))
                continue;

			echo "updating {$query->pmid} ...\n";

			$parms['id'] = $query->pmid;
        	$encoded_parms = http_build_query($parms);
			$results = file_get_contents('http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?' . $encoded_parms);

			if ($results === false)
			{
				continue;
			}

			//echo $results;
			$json = json_decode($results, true);

			//TODO: implement OTHER net

			$query->status = 21;
			$query->update($json['result'][$parms['id']]);
		}

    }
}
