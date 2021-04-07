<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Gene;

class UpdateAcmg59c extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:acmg59c';

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
        echo "Updating ACMG 59 flag from gene list ...";
            
        $handle = fopen(base_path() . '/data/acmg59.dat', "r");
        if ($handle)
        {
            while (($line = fgets($handle)) !== false)
            {
                // process the line read.
                echo "Processing " . $line . "\n";

                $gene = trim($line);

                if (empty($gene))
                    continue;

                $record = Gene::name($gene)->first();

                if ($record === null)
                    continue;

                echo "Updating " . $line . "\n";
                $record->update(['acmg59' => true]);
    
            }

            fclose($handle);
        }
        else
        {
            echo "\n(E001) Cannot access ACMG data file\n";
            exit;
        } 

        echo "DONE\n";

    }
}
