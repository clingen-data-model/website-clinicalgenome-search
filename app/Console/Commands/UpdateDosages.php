<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jira;
use App\Dosage;

class UpdateDosages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:dosages';

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
        echo "Accessing Jira ...\n";

        $regions = Jira::regionLoad([]);

        if ($regions === null || $regions->collection->isEmpty())
        {
            echo "ERROR:  Region Load returned an empty set\n";
            exit;
        }

        echo "Updating Dosages Table\n";

        foreach ($regions->collection as $region)
        {
            //dd($region->toArray());
            $status = Dosage::updateOrCreate(['issue' => $region->issue, 'type' => 1],
                                                $region->toArray());

            if (empty($status))
            {
                echo "ERROR:  Issue {$region->issue} not updated \n";
            }
        }
        
        echo "Update Complete\n";
    }
}
