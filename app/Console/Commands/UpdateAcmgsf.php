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
        echo "Updating ACMG SF data from file ...";


       $this->updateGuidanceNew();
       $this->updateReportingNew();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function updateGuidance()
    {
        echo "Updating ACMG SF data from file ...";


        $handle = fopen(base_path() . '/data/ACMG_SF_Reporting_Guidance_20250626.tsv', "r");

        if ($handle)
        {
            // discard the header
            $line = fgetcsv($handle, null, "\t");

            while (($line = fgetcsv($handle, null, "\t")) !== false)
            {

                $gene = Gene::name($line[0])->first();

                if ($gene === null)
                {
                    print("(E0002) Gene " . $line[0] . " not found");
                    continue;
                }

                $gene->notes = $line[1];
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


     public function updateReporting()
    {
        echo "Updating ACMG SF data from file ...";


        $handle = fopen(base_path() . '/data/ACMG_SF_Gene-Disease_Reporting_20250626.tsv', "r");
        if ($handle)
        {
            // discard the header
            $line = fgetcsv($handle, null, "\t");

            // delete the old table entries
            Reportable::query()->truncate();

            while (($line = fgetcsv($handle, null, "\t")) !== false)
            {

                //dd($line);

                // add entry
                $record = Reportable::create( [
                    'gene_symbol' => trim($line[0]),
                    'gene_hgnc_id' => trim($line[1]),
                    'disease_name' => trim($line[2]),
                    'disease_mondo_id' => trim($line[3]),
                    'moi' => trim($line[4]),
                    'reportable' => trim($line[6]),
                    'comment' => trim($line[5]),        // this is now the classification
                    'type' => 1,
                    'status' => 1]);

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


    public function save()
    {
        echo "Updating ACMG SF data from file ...";


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
                    'gene_symbol' => trim($line[0]),
                    'gene_hgnc_id' => trim($line[1]),
                    'disease_name' => trim($line[2]),
                    'disease_mondo_id' => trim($line[3]),
                    'moi' => trim($line[4]),
                    'reportable' => trim($line[5]),
                    'comment' => trim($line[6]),
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


    public function UpdateReportingNew()
    {
        echo "Updating ACMG SF data from file ...";


        $handle = fopen(base_path() . '/data/Clingen-Curation-ACMG-Summary-Report-2025-11-12.tsv', "r");
        if ($handle)
        {
            // discard the date line
            $line = fgetcsv($handle, null, "\t");

            // discard the header
            $line = fgetcsv($handle, null, "\t");

            // delete the old table entries
            Reportable::query()->truncate();

            while (($line = fgetcsv($handle, null, "\t")) !== false)
            {

                //dd($line);

                // add entry
                $record = Reportable::create( [
                    'gene_symbol' => trim($line[0]),
                    'gene_hgnc_id' => trim($line[1]),
                    'disease_name' => trim($line[2]),
                    'disease_mondo_id' => trim($line[3]),
                    'moi' => trim($line[4]),
                    'reportable' => trim($line[6]),
                    'comment' => trim($line[5]),        // this is now the classification
                    'type' => 1,
                    'status' => 1]);

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


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function updateGuidanceNew()
    {
        echo "Updating ACMG SF data from file ...";


        $handle = fopen(base_path() . '/data/Clingen-Curation-ACMG-Summary-Report-2025-11-12.tsv', "r");

        if ($handle)
        {
            // discard date line
            $line = fgetcsv($handle, null, "\t");

            // discard the header
            $line = fgetcsv($handle, null, "\t");

            while (($line = fgetcsv($handle, null, "\t")) !== false)
            {

                $gene = Gene::hgnc($line[1])->first();

                if ($gene === null)
                {
                    print("(E0002) Gene " . $line[0] . " not found");
                    continue;
                }

                $gene->notes = $line[7];
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
