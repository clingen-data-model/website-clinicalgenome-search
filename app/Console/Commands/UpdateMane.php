<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Decipher;
use App\Gene;

class UpdateMane extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:mane';

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
		echo "Updating MANE data from NCBI ...";


		try {

			$results = file_get_contents("https://ftp.ncbi.nlm.nih.gov/refseq/MANE/MANE_human/current/MANE.GRCh38.v1.3.summary.txt.gz");

		} catch (\Exception $e) {

			echo "\n(E001) Error retreiving MANE data\n";
			exit;

		}

		// unzip the data
		$data = gzdecode($results);

		// discard the header
		$line = strtok($data, "\n");

		// hgncid is col2, plus/select is col9, transcipt is 10 through 13 (chr, start, stop, strand)

		// clear the plus fields since there can be any number of them
		Gene::query()->update(['mane_plus' => null]);
		Gene::query()->update(['mane_select' => null]);

		// parse the remaining file
		while (($line = strtok("\n")) !== false)
		{
			$parts = explode("\t", $line);

			//echo "Updating " . $parts[2] . " \n";

			$gene = Gene::hgnc($parts[2])->first();

			// there is at least one record with no hgncid, but it does have a symbol.
			if (empty($gene))
			{
				$gene = Gene::name($parts[3])->first();
			}

			if (empty($gene))
				continue;

			$xscript = [
					'chr' => $parts[10],
					'start' => $parts[11],
					'stop' => $parts[12],
					'strand' => $parts[13],
					'refseq_nuc' => $parts[5],
					'ensembl_nuc' => $parts[7]
				];

			if ($parts[9] == 'MANE Select')
				$gene->update(['mane_select' => $xscript]);
			else if ($parts[9] == 'MANE Plus Clinical')
			{
				$old = $gene->mane_plus;
				if ($old == null)
					$old = [];
				$old[] = $xscript;
				$gene->update(['mane_plus' => $old]);
			}
			else
				echo "Bad Status " . $parts[9] . " \n";
		}

		echo "DONE\n";

    }
}
