<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Decipher;
use App\Gene;

class QueryDecipher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'decipher:query';

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
		echo "downloading decipher bed data ...\n";
				
			
		try {
					
			$results = file_get_contents("https://decipher.sanger.ac.uk/files/downloads/HI_Predictions_Version3.bed.gz");

		} catch (\Exception $e) {
		
			echo "(E001) Error retreiving decipher data\n";
			
		}
	
		// unzip the data
		$data = gzdecode($results);
		
		// discard the header
		$line = strtok($data, "\n");
		
		// parse the remaining file
		while (($line = strtok("\n")) !== false)
		{
			$parts = explode("\t", $line, 5);
			
			// what we want is in the fourth section...
			if (isset($parts[3]))
			{
				//... and is itself delimited by pipes
				$subparts = explode('|', $parts[3]);
				
				if (isset($subparts['2']) && strpos($subparts['2'], '%') !== false)
				{
					$gene = Gene::where('name', $subparts[0])->first();
					
					if (empty($gene))
						continue;
					
					// remove the percent sign
					$k = strpos($subparts['2'], '%');
					
					$gene->hi = substr($subparts['2'], 0, $k);
					$gene->save();
					echo $subparts['0'] . " has a HI of " . $subparts['2'] . "\n";
				}
			}
		}
		
			
		
		/*foreach ($data as $json)
		{
			echo $json['name'] . "\n";

			// check if entry already exists, if not create
			$decipher = Decipher::firstOrNew(['name' => $json['name']],
											['hi_index' => $json['hi_index'], 'p_li' => $json['p_li'],
											 'ensembl_id' => $json['ensembl_id'], 'content' => $results,
											 'status' => 1 ]);
		
			// look up corresponding entry in genes table
			$gene = Gene::where('name', $json['name'])->first();
		
			if (!empty($gene))
				$decipher->gene_id = $gene->id;
							
			$decipher->save();
		}*/
    }
}
