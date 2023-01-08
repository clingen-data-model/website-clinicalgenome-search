<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

//use Maatwebsite\Excel\Facades\Excel as Gexcel;

//use App\Imports\Excel;
//use App\Imports\ExcelGRT;

use DB;

use App\Gtr;
use App\Gene;
use App\Drug;

class TestTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:test';

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
        echo "Fixing genes table...\n";

        $results = DB::select( DB::raw("SELECT hgnc_id, COUNT(hgnc_id) FROM genes WHERE deleted_at is null GROUP BY hgnc_id HAVING COUNT(hgnc_id) > 1"));

        foreach ($results as $result)
        {
            $record = Gene::hgnc($result->hgnc_id)->orderBy('id', 'desc')->first();

            echo "Fixing $record->name \n";

            $record->delete();
            // $record->restore();
        }

    }


    public function savegtr()
    {

        echo "Testing GRT data ...\n";

        $gtrs = Gtr::all();

        $terms = [];
        $headers = [];

        foreach($gtrs as $gtr)
        {
            if ($gtr->condition_identifiers === null)
                continue;

            foreach($gtr->condition_identifiers as $condition_identifier)
            {
                if (!isset($terms[$condition_identifier]))
                    $terms[$condition_identifier] = ['condition' => $condition_identifier, 'genes' => [], 'labs' => [] ];

                $terms[$condition_identifier]['genes'] = array_unique (array_merge($terms[$condition_identifier]['genes'], $gtr->genes));
                array_push($terms[$condition_identifier]['labs'], $gtr->lab_test_name);
                $terms[$condition_identifier]['labs'] = array_unique ($terms[$condition_identifier]['labs']);
            }

        }

        $collection = collect($terms);

        $search = "Autism";

        $results = $collection->filter(function ($item) use ($search) {
            return false !== stripos($item['condition'], $search);
        });

        // create the header row
        foreach($results as $result)
        {
            $headers = array_unique (array_merge($headers, $result['labs']));
        }

        dd($headers);

        echo "count = " . count($terms) . "\n";


        echo "DONE\n";
    }


    public function genelook()
    {
        $gtrs = Gtr::all();

        foreach($gtrs as $gtr)
        {
            foreach($gtr->genes as $gene)
            {
                $check = Gene::name($gene)->first();

                if ($check === null)
                    $check = Gene::previous($gene)->first();

                if ($check === null)
                    echo "$gene not found! \n";
            }
        }
    }


    public function partition($data)
    {
        if (empty($data))
            return [];

        return explode('|', $data);
    }
}
