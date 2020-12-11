<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Decipher;
use App\Gene;

class UpdatePlof extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:plof';

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
			$file = fopen(base_path() . "/data/gnomad.v2.1.1.lof_metrics.by_gene.txt","r");

		} catch (\Exception $e) {
		
			echo "(E001) Error accessing ExAC plof data\n";
			exit;
			
		}
	
		// discard the header
		$line = fgets($file);
		
		/*$parts = explode("\t", $line);
			
		echo $parts[29];
		exit;*/
		
		// parse the remaining file
		while (($line = fgets($file)) !== false)
		{
			$parts = explode("\t", $line);
			
			echo "Gene " . $parts[0] . " PLOF = " . $parts[29] . " Pli=" . $parts[20] . "\n";

			// what we want is in the second and 20th sections...
			if (isset($parts[0]))
			{
				$gene = Gene::where('name', $parts[0])->first();
				
				if (empty($gene))
				{
					// check previous sympols
					$gene = Gene::whereJsonContains('prev_symbol', $parts[0])->first();
				}

				if (empty($gene))
					continue;
				
				// observed is 16, expected is 19
				$gene->plof = $parts[29];
				$gene->pli = $parts[20];
				$gene->save();
			}
			
		}
		
		fclose($file);
    }
}
