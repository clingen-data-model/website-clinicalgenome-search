<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Decipher;
use App\Gene;

class UpdateGnomad extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:gnomad';

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
			//$data = file_get_contents(base_path() . "/data/gnomad.v2.1.1.lof_metrics.by_gene.txt","r");

            $data = fopen("https://storage.googleapis.com/gcp-public-data--gnomad/release/4.0/constraint/gnomad.v4.0.constraint_metrics.tsv", 'r');
            
		} catch (\Exception $e) {

			echo "\n(E001) Error retreiving Gnomad LOF data\n";
			exit;

		}

		// discard the header
		$line = fgetcsv($data, 0, "\t");

        $genes = [];

		// parse the remaining file
		while (($parts = fgetcsv($data, 0, "\t")) !== false)
		{

            // for whatever reason, google is reading past end of the spreadsheet so we need to check this
            if (empty($parts[0]))
                break;

			//echo "Gene " . $parts[0] . " Loeuf = " . $parts[20] . " Pli=" . $parts[16] .  " transcript= " . $parts[1] . "\n";

			$gene = Gene::where('name', $parts[0])->first();

            if ($gene === null)
            {
                // check previous sympols and aliases
                $gene = Gene::previous($parts[0])->first();

                if ($gene === null)
                    $gene = Gene::alias($parts[0])->first();
                    
            }

            if ($gene === null)
            {
                echo "GENE " . $parts[0] . " NOT FOUND...SKIPPING\n";
                continue;
            }

            if (empty($genes[$gene->name]))
            {
                // first instance of gene, add it to the array
                $genes[$gene->name] = ['gene_id' => $gene->id, 'plof' => $parts[20], 'pli' => $parts[16], 'transcript' => $parts[1], 'mane' => $parts[2]];
            }
            else if ($genes[$gene->name]['mane'] == "false" && $parts[2] == "true")
            {
                // always prefer the mane transcript
                $genes[$gene->name] = ['gene_id' => $gene->id, 'plof' => $parts[20], 'pli' => $parts[16], 'transcript' => $parts[1], 'mane' => $parts[2]];
            }

        }


        foreach ($genes as $name)
        {
            $gene = Gene::find($name['gene_id']);
            $gene->plof = $name['plof'];
            $gene->pli = $name['pli'];
            $gene->transcript = $name['transcript'];
            $gene->save();
        }

		echo "DONE\n";

    }
}
