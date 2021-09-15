<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Iscamap;
use App\Gene;

class UpdateMap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:map';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the ISCA data for each gene';

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
        echo "Updating ISCA Dosage Gene Map from DCI ...";

        $handle = fopen(base_path() . '/data/gene_isca.idx', "r");
        if ($handle)
        {
            while (($line = fgets($handle)) !== false)
            {
                // process the line read.
                //echo "Processing " . $line . "\n";

                $value = explode("\t", $line);

                // see if there is a usable hgnc id
                $gene = Gene::name(trim($value[0]))->first();

                if ($gene === null)
                {
                    $gene = Gene::whereJsonContains('prev_symbol', trim($value[0]))->first();

                    if ($gene === null)
                    {
                        $gene = Gene::whereJsonContains('alias_symbol', trim($value[0]))->first();
                    }
                }

                $issue = Iscamap::updateOrCreate(['symbol' => trim($value[0])], ['issue' => trim($value[1]),
                                                                                'hgnc_id' => $gene->hgnc_id ?? null] );
            }

            fclose($handle);
        }
        else
        {
            echo "\n(E001) Cannot access gene_isca file\n";
            exit;
        }

        echo "DONE\n";
    }
}
