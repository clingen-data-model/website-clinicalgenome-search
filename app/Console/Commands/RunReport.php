<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

use App\Gene;
use App\GeneLib;
use App\Morbid;
use App\Panel;

class RunReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Erins omim report';

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
        echo "Running Erin Report ...";
        $this->report3();

    }

    public function report1()
    {
        $results = GeneLib::validityList([	'page' =>  0,
										'pagesize' => "null",
										'sort' => 'GENE_LABEL',
                                        'direction' => 'ASC',
                                        'search' => null,
                                        'forcegg' => true,
                                        'curated' => true ]);

        //TODO:  this will likely hang on a refresh, need to time out
        if ($results === null)
        {
            echo "(E001) Genegraph failed\n";
            exit;
        }

        // remove redundat genes
        $collection = $results->collection->unique(function ($item) {
            return $item->gene->label;
        });


        echo "Gene\tHGNCID\tValidity\tPhenotype\tPheno Omim\tDisputing\tNonDisease\tMutations\tMap Key\n";

        foreach ($collection as $assertion)
        {
            switch ($assertion->classification->label)
            {
                case 'limited evidence':
                case 'disputing':
                case 'refuting evidence':
                    // look up the gene in morbid file
                    $records = Morbid::whereJsonContains('genes', $assertion->gene->label)->get();
                    if ($records->isEmpty())
                        break;
                    foreach ($records as $record)
                    {
                        echo $assertion->gene->label . "\t"
                            . $assertion->gene->hgnc_id . "\t"
                            . $assertion->classification->label . "\t"
                            . $record->phenotype . "\t"
                            . $record->pheno_omim . "\t"
                            . $record->disputing . "\t"
                            . $record->nondisease . "\t"
                            . $record->mutations . "\t"
                            . $record->mapkey . "\n";
                    }
                default:
                    break;
            }
        }
    }


    public function report2()
    {
        $con = new \App\Http\Controllers\ExcelController();

        $results = [];

        $lines = 0;

        $handle = fopen(base_path() . '/data/ADMI_CNV_Molly.csv', "r");
        if ($handle)
        {

            while (($line = fgetcsv($handle)) !== false)
            {
                $row = array_values($line);

                // remove any unprintables
                $row[0] = preg_replace('/[[:^print:]]/', '', $row[0]);
                $row[13] = preg_replace('/[[:^print:]]/', '', $row[13]);
                $row[14] = preg_replace('/[[:^print:]]/', '', $row[14]);

                if (empty($row[0]))
                    break;

                $region = $row[0] . ':' . $row[13] . '-' . $row[14];
                $type = 'GRCh37';

                dd($region);

                //echo "$region \n";

                 $genes = Gene::searchList(['type' => $type,
                        "region" => $region,
                        'option' => 1 ]);

                $row[] = implode(', ', $genes->collection->pluck('name')->toArray());

                $results[] = $row;

                $lines++;
            }
        }

        echo "\n $lines processed \n";
        $con->output($results);
    }


    public function report3()
    {
        $panels = Panel::all();

        $ofd = fopen("/tmp/panelreport.tsv", "w");

        foreach ($panels as $panel)
        {
            $line = [];

            $title = $panel->title;
            $status = $panel->affiliate_status;

            if ($status === null)
                continue;

            // change epochs to dates
            foreach (["gene_date_step_1", "gene_date_step_2", "variant_date_step_1", "variant_date_step_2", "variant_date_step_3", "variant_date_step_4"] as $key)
            {
                if (!empty($status[$key]))
                    $status[$key] = date("Y-m-d H:i:s", $status[$key]);
            }

            if ($status["gene"] === null)
                $status["gene"] = "";

            if ($status["variant"] === null)
                $status["variant"] = "";

            echo "$title\n";

            fwrite($ofd, $title . "\t" . implode("\t", $status) . PHP_EOL);

        }

        fclose($ofd);
    }
}
