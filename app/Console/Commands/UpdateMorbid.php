<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Setting;

use App\Gene;

class UpdateMorbid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:morbid';

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
        echo "Updating OMIM Morbid Flags from OMIM ...";

        $key = Setting::get('omim', false);

        if (!$key)
        {
            echo "\n(E002) Error retreiving Omim key\n";
            exit;
        }
        
        // https://data.omim.org/downloads/gnEYXJE_RtCzjSCNEOWFHg/morbidmap.txt

        try {

            $results = file_get_contents("https://data.omim.org/downloads/" . $key . "/morbidmap.txt");

		} catch (\Exception $e) {
		
			echo "\n(E001) Error retreiving Omim Morbid data\n";
			exit;
		}
	
        $line = strtok($results, "\n");
        
        while ($line !== false)
        {

                $value = explode("\t", $line);

                if (strpos($value[0], '#') !== 0)
                {

                    $genes = $value[1];

                    foreach (explode(',', $genes) as $gene)
                    {
                        $record = Gene::name($gene)->first();

                        if ($record !== null)
                            $record->update(['morbid' => 1]);

                    }
                }

            $line = strtok("\n");
        }

        echo "DONE\n";

    }
}
