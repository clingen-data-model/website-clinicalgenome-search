<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Gdmmap;

class UpdateGdmmap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:Gdmmap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the system with the latest gdm to genegraph map';

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
        echo "Updating GDM to Genegraph Map from GCI ...";


		try {

            $results = file_get_contents(base_path() . "/data/allGdms.json");

		} catch (\Exception $e) {

			echo "\n(E001) Error retrieving map data\n";
			return 0;

		}

		$data = json_decode($results, true);

		foreach ($data as $record)
		{
            /*
                Multiple classifications for 352ca78d-b94b-4205-a385-ed16d6ae8d05
                Multiple classifications for b1219ced-5b9b-421d-9449-edd0071839e2
                Multiple classifications for d1819cd5-2591-4a98-af44-c34cc9790ae2
                Multiple classifications for e5df2f5f-7a3c-48cf-a2cc-260485af7003
                Multiple classifications for c5353aa5-906f-4ec1-8eb9-ddf3cbd0a653
                Multiple classifications for c8e925b7-1d59-4b40-920a-5987c3b056e3
                Multiple classifications for e2267ada-584b-4368-a73a-90174d74f1d3
            */
            if (count($record['provisionalClassifications']) > 1)
                echo "Multiple classifications for " . $record['PK']. " \n";

			// check if entry already exists, if not create
            foreach ($record['provisionalClassifications'] as $classification)
            {
                $stat = Gdmmap::Create(['gdm_uuid' => $record['PK'],
                                        'gg_uuid' => $classification, 'status' => 1, 'type' => 1]);
            }

        }

        echo "DONE\n";

    }
}
