<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Setting;

use App\Gene;
use App\Morbid;

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
        
        try {

            $results = file_get_contents("https://data.omim.org/downloads/" . $key . "/morbidmap.txt");

		} catch (\Exception $e) {
		
			echo "\n(E001) Error retreiving Omim Morbid data\n";
			exit;
		}
    
        Morbid::query()->forceDelete();

        $line = strtok($results, "\n");
        
        while ($line !== false)
        {

                $value = explode("\t", $line);

                if (strpos($value[0], '#') === 0)
                {
                    $line = strtok("\n");
                    continue;
                }

                $genes = $value[1];

                foreach (explode(',', $genes) as $gene)
                {
                    $record = Gene::name($gene)->first();

                    if ($record !== null)
                        $record->update(['morbid' => 1]);

                }
                // check for disputing flag
                $disputing = (strpos($value[0], '?') === 0);
                $phenotype = Morbid::parsePhenotype($value[0], true);

                $primary =  $phenotype['primary'];

                // check for brackets
                $nondisease = (strpos($phenotype['primary'], '[') === 0 && strpos($phenotype['primary'], ']') !== false);

                // check for braces
                $mutations = (strpos($phenotype['primary'], '{') === 0 && strpos($phenotype['primary'], '}') !== false);

                if ($nondisease || $mutations)
                    $primary = substr($primary, 1, strlen($primary) - 2);

                // check for disputing flag
                $disputing = (strpos($primary, '?') === 0);

                if ($disputing)
                    $primary = substr($primary, 1);

                $stat = Morbid::create(['phenotype' => $primary,
                                        'secondary' => $phenotype['secondary'],
                                        'pheno_omim' => $phenotype['omim'],
                                        'mapkey' => $phenotype['map'],
                                        'genes' => explode(',', $genes),
                                        'disputing' => ($disputing ? 'Y' : 'N'),
                                        'mutations' => ($mutations ? 'Y' : 'N'),
                                        'nondisease' => ($nondisease ? 'Y' : 'N'),
                                        'mim' => $value[2],
                                        'cyto' => $value[3],
                                        'type' => 1,
                                        'status' => 1
                                        ]);

            $line = strtok("\n");
        }

        echo "DONE\n";

    }
}
