<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Iscamap;
use App\Gene;
use App\Term;

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

                $symbol = trim($value[0]);

                // see if there is a usable hgnc id
                $gene = Gene::name($symbol)->first();

                if ($gene === null)
                {
                    $term = Term::name($symbol)->first();

                    if ($term !== null)
                    {
                        $gene = Gene::hgnc($term->value)->first();
                    }
                }

                $issue = Iscamap::updateOrCreate(['issue' => trim($value[1])], ['symbol' => $symbol,
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
