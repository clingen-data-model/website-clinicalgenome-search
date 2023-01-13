<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

use App\Blacklist;

class UpdateBlacklist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:blacklist';

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


        $handle = fopen(base_path() . '/data/blacklist.csv', "r");
        if ($handle)
        {
            // delete the old table
            Blacklist::query()->truncate();

            // discard the header
            $line = fgetcsv($handle);

            while (($line = fgetcsv($handle)) !== false)
            {

                //dd($line);

                $value = $line;

                $record = Blacklist::updateOrCreate(['gci_id' => $value[0]], [
                                        'gci_id' => $value[0],
                                        'gg_id' => $value[9],
                                        'gene_symbol' => $value[2],
                                        'disease' => $value[3],
                                        'moi' => $value[4],
                                        'gcep' => $value[5],
                                        'coordinator' => $value[6],
                                        'grant' => $value[7],
                                        'notes' => $value[8],
                                        'type' => 1, 'status' => 1]);
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
