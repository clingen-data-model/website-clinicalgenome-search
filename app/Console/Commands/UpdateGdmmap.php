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

            $results = file_get_contents(base_path() . "/data/gdmPublished.json");

		} catch (\Exception $e) {

			echo "\n(E001) Error retrieving map data\n";
			return 0;

		}

		$data = json_decode($results, true);

		foreach ($data as $record)
		{
            /*
                Multiple classifications for 45802e0e-b9cd-4012-937a-7baa958f2ead
                Multiple classifications for c5ace529-6b4e-41af-ac51-a76a844e642e
                Multiple classifications for b848f70c-c07c-4ecf-82e2-ddfc6605eeb9
                Multiple classifications for 1c9c851d-bb87-4311-adff-e1239fee42c3
                Multiple classifications for 981bf8d6-a559-4345-aa07-2d81b92fe58b
                Multiple classifications for 9f294c4e-5964-41b5-96c0-4920d3bc23b8
                Multiple classifications for 0a788226-5113-4b55-aa38-7d92372520cf
                Multiple classifications for 352ca78d-b94b-4205-a385-ed16d6ae8d05
                Multiple classifications for d1819cd5-2591-4a98-af44-c34cc9790ae2
                Multiple classifications for e5df2f5f-7a3c-48cf-a2cc-260485af7003
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
