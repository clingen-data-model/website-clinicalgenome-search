<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

use App\Disease;
use App\GeneLib;

class UpdateDisease extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:disease';

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
        echo "Updating G-D-A Disease Activity from Genegraph ...";

        $results = GeneLib::conditionList([	'page' =>  0,
										'pagesize' => "null",
										'sort' => null,
                                        'direction' => null,
                                        'forcegg' => true,
                                        'search' => null,
                                        'curated' => true ]);

        if ($results === null)
        {

            echo "\n(E001) Error reading genegraph data\n";
            exit;
        }

        foreach($results->collection as $disease)
        {
            //echo "Updating  " . $disease->curie . "\n";

            $flags = ['actionability' => $disease->has_actionability,
                        'validity' => $disease->has_validity,
                        'dosage' => $disease->has_dosage
                    ];

            $status = Disease::STATUS_ACTIVE;

            if (strpos($disease->label, 'obsolete ') === 0)
            {
                $status = Disease::STATUS_GG_DEPRECATED;
                $disease->label = substr($disease->label, 9);
            }

            $record = Disease::updateOrCreate(['curie' => $disease->curie], [
                                        'label' => $disease->label,
                                        'description' => $disease->description,
                                        'synonyms' => $disease->synonyms,
                                        'last_curated_date' => $disease->last_curated_date,
                                        'curation_activities' => $flags,
                                        'type' => 1, 'status' => $status]);
        }

        // now hide all the obsolete, non-curated diseases

        echo "DONE\n";

    }
}
