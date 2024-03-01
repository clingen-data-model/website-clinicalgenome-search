<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Gene;
use App\Term;

class UpdateGenenames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:Genenames';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the system with the latest genenames data';

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
        echo "Updating Gene List from Genenames ...";

		// set the options so genenames sends json instead of xml
		$options = array(
			'http'=> array(
				'method' => "GET",
				'header' => "Accept: application/json\r\n"
			)
		);

		$context = stream_context_create($options);

		try {

            //$results = file_get_contents("ftp://ftp.ebi.ac.uk/pub/databases/genenames/new/json/hgnc_complete_set.json");
            //$results = file_get_contents("http://ftp.ebi.ac.uk/pub/databases/genenames/new/json/hgnc_complete_set.json");
            $results = file_get_contents("http://ftp.ebi.ac.uk/pub/databases/genenames/hgnc/json/hgnc_complete_set.json");

		} catch (\Exception $e) {

			echo "\n(E001) Error retrieving search data\n";
			return 0;

		}

		$data = json_decode($results, true);

		if (empty($data['response']['numFound']))
		{
            echo "\n(E002) Error fetching search data.\n";
            return 0;
		}

		foreach ($data['response']['docs'] as $doc)
		{
			//echo "Processing " . $doc['symbol'] . "  " . $doc['name'] .  "  " .  $doc['hgnc_id'] . "\n";

			// change doc status to gene status
			$doc['status'] = 0;

			// change doc name and symbol to description and name
			$doc['description'] = $doc['name'];
            $doc['name'] = $doc['symbol'];

            if (isset($doc['mane_select']))
                unset($doc['mane_select']);

            if (isset($doc['mane_plus']))
                unset($doc['mane_plus']);

            $doc['is_par'] = (strpos($doc['location'], ' and ') > 0);

			// check if entry already exists, if not create
            $gene = Gene::updateOrCreate(['hgnc_id' => $doc['hgnc_id']], $doc);

            $term = Term::updateOrCreate(['name' => $gene->name, 'value' => $gene->hgnc_id],
                                        ['type' => 1, 'status -> 1']);
            if ($gene->prev_symbol !== null)
                foreach ($gene->prev_symbol as $symbol)
                    Term::updateOrCreate(['name' => $symbol, 'value' => $gene->hgnc_id],
                                        ['alias' => $gene->name, 'type' => 2, 'status -> 1']);
            if ($gene->alias_symbol !== null)
                foreach ($gene->alias_symbol as $symbol)
                    Term::updateOrCreate(['name' => $symbol, 'value' => $gene->hgnc_id],
                                        ['alias' => $gene->name, 'type' => 3, 'status -> 1']);

        }

        echo "DONE\n";

    }
}
