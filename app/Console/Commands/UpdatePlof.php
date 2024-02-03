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
		echo "Updating Gnomad LOF data from GNOMAD ...";

		// https://gnomad-public-us-east-1.s3.amazonaws.com/release/2.1.1/constraint/gnomad.v2.1.1.lof_metrics.by_gene.txt.bgz

		try {

			//$results = file_get_contents("https://gnomad-public-us-east-1.s3.amazonaws.com/release/2.1.1/constraint/gnomad.v2.1.1.lof_metrics.by_gene.txt.bgz");
			$data = file_get_contents(base_path() . "/data/gnomad.v2.1.1.lof_metrics.by_gene.txt","r");

            //https://storage.googleapis.com/gcp-public-data--gnomad/release/4.0/constraint/gnomad.v4.0.constraint_metrics.tsv
            
		} catch (\Exception $e) {

			echo "\n(E001) Error retreiving Gnomad LOF data\n";
			exit;

		}

		// unzip the data
		//$data = gzdecode($results);
		//dd($data);

		// discard the header
		$line = strtok($data, "\n");


		// parse the remaining file
		while (($line = strtok("\n")) !== false)
		{

			$parts = explode("\t", $line);

			echo "Gene " . $parts[0] . " PLOF = " . $parts[29] . " Pli=" . $parts[20] .  " ensr= " . $parts[63] . "\n";

            // first, try to match the entry by ensemble ID
            $gene = Gene::ensembl($parts[63])->first();

            // if no valid match, then try to match up by symbol name
            if (empty($gene) && isset($parts[0]))
			{
				$gene = Gene::where('name', $parts[0])->first();

				if (empty($gene))
				{
					// check previous sympols
					$genes = Gene::whereJsonContains('prev_symbol', $parts[0])->get();

                    if ($genes->isEmpty())
                        continue;

                    foreach ($genes as $option)
                    {
                        if ($parts[74] == 'X')
                            $parts[74] = '23';
                        else if ($parts[74] == 'Y')
                            $parts[74] = '24';
                        if ( $option->chr !== null && $option->chr == $parts[74])
                        {
                            $gene = $option;
                            break;
                        }
                    }
				}

                // a redudnant check before moving on
                if ($parts[74] == 'X')
                    $parts[74] = '23';
                else if ($parts[74] == 'Y')
                    $parts[74] = '24';

                if ( !empty($gene) && $gene->chr !== null && $gene->chr != $parts[74])
                {
                    echo $parts[0] . " incorrectly matched to gene $gene->name hgnc $gene->hgnc_id   - $parts[63] \n";
                    continue;
                }
            }

            // if still no match, skip it.
            if (empty($gene))
                continue;

            $gene->plof = $parts[29];
            $gene->pli = $parts[20];
            $stat = $gene->save();
            //echo "Gene " . $parts[0] . " PLOF = " . $parts[29] . " Pli=" . $parts[20] . "(" . $stat . ") \n";
		}


		echo "DONE\n";

    }
}
