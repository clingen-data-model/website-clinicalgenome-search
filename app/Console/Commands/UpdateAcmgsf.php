<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

use App\Gene;
use App\Reportable;

class UpdateAcmgsf extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:acmgsf';

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
        echo "Updating Blacklist data from data ...";


        $handle = fopen(base_path() . '/data/ACMG_SF_Annotations.csv', "r");
        if ($handle)
        {
            // discard the header
            $line = fgetcsv($handle);

            // delete the old table entries
            Reportable::query()->truncate();

            while (($line = fgetcsv($handle)) !== false)
            {

                //dd($line);

                // add entry
                $record = Reportable::create( [
                    'gene_symbol' => $line[0],
                    'gene_hgnc_id' => $line[1],
                    'disease_name' => $line[2],
                    'disease_mondo_id' => $line[3],
                    'moi' => $line[4],
                    'reportable' => $line[5],
                    'comment' => $line[6],
                    'type' => 1,
                    'status' => 1]);


                // update the gene comment
                $comment = $line[6];

                $gene = Gene::hgnc($line[1])->first();

                if ($gene === null)
                {
                    print("(E0002) Gene " . $line[1] . " not found");
                    continue;
                }

                $gene->notes = $comment;
                $gene->save();

            }

            fclose($handle);
        }
        else
        {
            echo "\n(E001) Cannot access ACMG SF update file\n";
            exit;
        }

        echo "DONE\n";
    }
}
