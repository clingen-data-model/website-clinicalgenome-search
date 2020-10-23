<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Maatwebsite\Excel\Facades\Excel as Gexcel;

use App\Imports\Excel;

use App\Cpic;
use App\GeneLib;

class UpdateCpic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:cpic';

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
        echo "Importing pharma data from CPIC ...\n";

        $file = base_path() . '/data/cpicPairs.csv';

        $worksheets = (new Excel)->toArray($file);
        
        foreach($worksheets[0] as $row)
        {
            echo "Updating  " . $row['gene'] . "\n";

            $stat = Cpic::updateOrCreate(['gene' => $row['gene'], 'drug' => $row['drug']], $row);

        }

    }
}
