<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Decipher;
use App\Gene;
use App\Gencc;

class QueryGenCC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gencc:query';

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
		echo "downloading gencc data ...\n";

		//$results = file_get_contents('https://search.thegencc.org/download/action/submissions-export-csv');

        $fp = fopen('https://search.thegencc.org/download/action/submissions-export-csv', 'r');

		if ($fp === false)
		{
			die("Error downloading table");
		}

        // parse the headers
        $keys = fgetcsv($fp);

        // clear the table since the import has no remove facility
		Gencc::query()->forceDelete();

		// parse the remaining file
        while (($data = fgetcsv($fp)) !== false)
        {
            /*
                0 => uuid
                1 => gene curie
                2 => gene symbol
                3 => disease curie (mondo)
                4 => disease title
                5 => disease original curie
                6 => disease original title
                7 => gencc classification curie
                8 => gencc classification
                9 => moi curie
                10 => moi title
                11 => submitter curie
                12 => submitter title
                13 => submitted as hgnc_id
                14 => submitted as hgnc symbol
                15 => submitted as disease id
                16 => submitted as disease name
                17 => submitted as moi id
                18 => submitted as moi name
                19 => sumitted as submitter id
                20 => submitted as submitter name
                21 => submitted as classification id
                22 => submitted as classification name
                23 => submitted as date
                24 => submitted as public report url
                25 => submitted as notes
                26 => submitted as pmids
                27 => submitted as assertion criteria url
                28 => submitted as submission id
                29 => submitted run date
            */

            $gene = Gene::hgnc($data[1])->first();

            if ($gene === null)
            {
				echo "Gene " . $data[1] . " not found\n";
				continue;
            }

            // the parser is having some issues with the submitted as data, so build it manually
            /*$a = [
                'uuid' => $data[0],
                'gene_curie' => $data[1],
                'gene_symbol' => $data[2],
                'disease_curie' => $data[3],
                'disease_title' => $data[4],
                'disease_original_curie' => $data[5],
                'disease_original_title' => $data[6],
                'classification_curie' => $data[7],
                'classification_title' => $data[8],
                'moi_curie' => $data[9],
                'moi_title' => $data[10],
                'submitter_curie' => $data[11],
                'submitter_title' => $data[12],
                'submitted_as_date' => $data[23] ?? ''
                ];*/

            $stat = Gencc::create(array_combine($keys, $data));
        }
    }
}
