<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

//use Maatwebsite\Excel\Facades\Excel as Gexcel;

//use App\Imports\Excel;
//use App\Imports\ExcelGRT;

use App\Gtr;
use App\Gene;
use App\Drug;

class UpdateGrt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:grt';

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

        echo "Updating GRT data from local file ...";

        try {

            $handle = fopen(base_path() . '/data/gtr_data.tsv', "r");

		} catch (\Exception $e) {

			echo "\n(E001) Error opening GTR data\n";
			exit;
		}

        //Cpic::query( => forceDelete();

        $num = 0;

        $conditions = [];

        $ofd = fopen("/tmp/conditions.txt", "w");

        if ($handle)
        {
            // header
            $line = fgetcsv($handle);

            while (($line = fgetcsv($handle, 0, "\t")) !== false)
            {
                $genes = $this->partition($line[21]);

                if (count($genes) < 2)
                    continue;

                $methods = $this->partition($line[18]);

                if (!in_array("Sequence analysis of the entire coding region", $methods))
                    continue;

                if (!in_array($line[11], $conditions))
                    $conditions[] = $line[11];

                //echo $line[21] . "\n";

                $record = new Gtr([
                    'type' => 1,
                    'test_accession_ver' => $line[0],
                    'name_of_laboratory' => $line[1],
                    'name_of_institution' => $line[2],
                    'facility_state' => $line[3],
                    'facility_postcode' => $line[4],
                    'facility_country' => $line[5],
                    'CLIA_number' => $line[6],
                    'state_licenses' => $this->partition($line[7]),
                    'state_license_numbers' => $this->partition($line[8]),
                    'lab_test_id' => $line[9],
                    'last_touch_date' => $line[10],
                    'lab_test_name' => $line[11],
                    'manufacturer_test_name' => $line[12],
                    'test_development' => $line[13],
                    'lab_unique_code' => $line[14],
                    'condition_identifiers' => $this->partition($line[15]),
                    'indication_types' => $this->partition($line[16]),
                    'inheritances' => $this->partition($line[17]),
                    'method_categories' => $methods,
                    'methods' => $this->partition($line[18]),
                    'platforms' => $this->partition($line[20]),
                    'genes' => $genes,
                    'drug_responses' => $this->partition($line[22]),
                    'now_current' => $line[23],
                    'test_currStat' => $line[24],
                    'test_pubStat' => $line[25],
                    'lab_currStat' => $line[26],
                    'lab_pubStat' => $line[27],
                    'test_create_date' => $line[28],
                    'test_deletion_data' => $line[29],
                    'version' => 1,
                    'status' => 1
                ]);

                //$record->save();

                $num++;

            }
        }
        else
        {
            echo "\n(E001) Error opening GTR data\n";
            exit;
        }

        foreach($conditions as $condition)
            fwrite($ofd, $condition . PHP_EOL);

        fclose($ofd);

        echo "$num ... DONE\n";
    }


    public function partition($data)
    {
        if (empty($data))
            return [];

        return explode('|', $data);
    }
}
