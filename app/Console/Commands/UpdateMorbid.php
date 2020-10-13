<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        echo "Importing omim morbid flags ...\n";
		
		$handle = fopen(base_path() . '/data/morbidmap.txt', "r");
        if ($handle)
        {
            while (($line = fgets($handle)) !== false)
            {
                // process the line read.
                echo "Processing " . $line . "\n";

                $value = explode("\t", $line);

                if (strpos($value[0], '#') === 0)
                    continue;

                $genes = $value[1];

                foreach (explode(',', $genes) as $gene)
                {
                    $record = Gene::name($gene)->first();

                    if ($record !== null)
                        $record->update(['morbid' => 1]);

                }
            }

            fclose($handle);
        }
        else
        {
            echo "Cannot access IDX file\n";
        }
    }
}
