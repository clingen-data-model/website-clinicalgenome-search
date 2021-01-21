<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Maatwebsite\Excel\Facades\Excel as Gexcel;

use App\Imports\Excel;
use App\Imports\ExcelGKB;

use App\Cpic;
use App\GeneLib;
use App\Gene;

class UpdateCpic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:cpic';

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
    
        echo "Importing pharma data from CPIC ...\n";

        $file = base_path() . '/data/cpicPairs.csv';

        $worksheets = (new Excel)->toArray($file);
        
        foreach($worksheets[0] as $row)
        {
            echo "Updating  " . $row['gene'] . "\n";

            $stat = Cpic::updateOrCreate(['gene' => $row['gene'], 'drug' => $row['drug']], $row);

        }

        echo "Augmenting pharma data from pharmGKB ...\n";

        $file = base_path() . '/data/pharmgkb/genes.tsv';
			
		try {
					
			//echo base_path() . "/data/ExAC.r1.sites.vep.gene.table\n";
			$file = fopen($file,"r");

		} catch (\Exception $e) {
		
			echo "(E001) Error accessing pharmGKB data\n";
			exit;
			
		}
	
		// discard the header
		$line = fgets($file);
		
		
		// parse the remaining file
		while (($line = fgets($file)) !== false)
		{
			$row = explode("\t", $line);
        
            echo "Updating  " . $row[5] . "\n";

            $records = Cpic::gene($row[5])->get();

            foreach($records as $record)
                $record->update(['hgnc_id' => $row[2], 'pa_id' => $row[0], 'is_vip' => $row[8],
                    'has_va' => $row[9], 'had_cpic_guideline' => $row[11]]);

        }

        echo "Augmenting drug data from pharmGKB ...\n";

        $file = base_path() . '/data/pharmgkb/drugs.tsv';
			
		try {
					
			//echo base_path() . "/data/ExAC.r1.sites.vep.gene.table\n";
			$file = fopen($file,"r");

		} catch (\Exception $e) {
		
			echo "(E001) Error accessing pharmGKB data\n";
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
			$row = explode("\t", $line);
        
            echo "Updating  " . $row[1] . "\n";

            $records = Cpic::drug($row[1])->get();

            foreach($records as $record)
                $record->update(['pa_id_drug' => $row[0]]);

        }

        // update the main gene table
        $list = Cpic::select('hgnc_id')->distinct('hgnc_id')->get();
        
        foreach($list as $record)
        {
            $gene = Gene::hgnc($record->hgnc_id)->first();

            if ($gene !== null)
            {
                $activity = $gene->activity;
                $activity['pharma'] = true;
                $gene->activity = $activity;
                $gene->save();
            }
        }

    }
}
