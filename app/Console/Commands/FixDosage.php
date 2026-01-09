<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

use App\Curation;
use App\Disease;

class FixDosage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:dosage';

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
        echo "Fixing dosage links for phenotype names ...";

        Curation::dosage()->status(Curation::STATUS_ACTIVE)->each(function ($item) {

            if ($item->disease_id !== null)
            {
                if (array_key_exists('disease_phenotype_name', $item->condition_details) && empty($item->condition_details['disease_phenotype_name']))
                {   
                    $disease = Disease::find($item->disease_id);

                    if ($disease !== null)
                    {
                        echo "...fixing $item->assertion_uuid \n";
                        $details = $item->condition_details;
                        $details['disease_phenotype_name'] = $disease->label;
                        $item->condition_details = $details;
                        $item->save();
                    }
                }

            }

        });

        echo "DONE\n";
    }
}
