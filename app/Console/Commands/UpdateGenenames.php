<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Gene;

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
            $results = file_get_contents("http://ftp.ebi.ac.uk/pub/databases/genenames/new/json/hgnc_complete_set.json");

		} catch (\Exception $e) {
		
			echo "\n(E001) Error retrieving search data\n";
			exit;
			
		}
	
		$data = json_decode($results, true);
		
		if ($data['response']['numFound'] == 0)
		{
            echo "\n(E002) Error fetching search data.\n";
            exit;
		}
	
		foreach ($data['response']['docs'] as $doc)
		{
			//echo "Processing " . $doc['symbol'] . "  " . $doc['name'] .  "  " .  $doc['hgnc_id'] . "\n";
			
			// change doc status to gene status
			$doc['status'] = 0;
			
			// change doc name and symbol to description and name
			$doc['description'] = $doc['name'];
            $doc['name'] = $doc['symbol'];
			
			// check if entry already exists, if not create
            $gene = Gene::updateOrCreate(['name' => $doc['symbol']], $doc);
        }
        
        echo "DONE\n";

    }
}
