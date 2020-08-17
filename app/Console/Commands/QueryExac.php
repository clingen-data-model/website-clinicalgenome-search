<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Decipher;
use App\Gene;

class QueryExac extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exac:query';

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
		echo "Loading Exac data ...\n";
			
		try {
					
			//echo base_path() . "/data/ExAC.r1.sites.vep.gene.table\n";
			//$file = fopen(base_path() . "/data/ExAC.r1.sites.vep.gene.table","r");
			$file = fopen(base_path() . "/data/forweb_cleaned_exac_r03_march16_z_data_pLI.txt","r");

		} catch (\Exception $e) {
		
			echo "(E001) Error accessing ExAC data\n";
			exit;
			
		}
	
		// discard the header
		$line = fgets($file);
		
		//$parts = explode("\t", $line);
			
		//echo $parts[19];
		//exit;
		
		// parse the remaining file
		while (($line = fgets($file)) !== false)
		{
			$parts = explode("\t", $line);
			
			echo "Gene " . $parts[1] . " PLi = " . $parts[19] . "\n";
			
			// what we want is in the second and 20th sections...
			if (isset($parts[1]))
			{
				//... and is itself delimited by pipes
				//$subparts = explode('|', $parts[3]);
				
				//if (isset($subparts['2']) && strpos($subparts['2'], '%') !== false)
				//{
					$gene = Gene::where('name', $parts[1])->first();
					
					if (empty($gene))
						continue;
					
					// remove the percent sign
					//$k = strpos($subparts['2'], '%');
					
					$gene->pli = $parts[19]; //substr($subparts['2'], 0, $k);
					$gene->save();
					//echo $subparts['0'] . " has a HI of " . $subparts['2'] . "\n";
				//}
			}
			
		}
		
		fclose($file);
		
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
