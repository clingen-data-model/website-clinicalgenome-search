<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Gene;

class UpdateUniprot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:uniprot';

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
        echo "Importing uniprot function information ...\n";

        $current = ['gn' => null, 'fn' => [], 'ac' => null];
        $state = 0;
		
		$handle = fopen(base_path() . '/data/uniprot_sprot_human.dat', "r");
        if ($handle)
        {
            while (($line = fgets($handle)) !== false)
            {
                $parms = preg_split('/\s+/', $line, 2);
                switch ($parms[0])
                {
                    case 'ID':      // start of new section
                        $state = 1;     // seen a valid id
                        break;
                    case 'AC':      // uniprot id
                        if ($state != 1)
                            continue 2;
                        $state = 2;
                        $ac = preg_split('/;/', $parms[1], 2);
                        $current['ac'] = $ac[0];
                        break;
                    case 'GN':      // gene name
                        if ($state != 2)
                            continue 2;
                        $state = 3;
                        $gn = preg_split('/[ ;]/', substr($parms[1], 5));
                        $current['gn'] = $gn[0];
                        break;
                    case 'CC':      // annotations, possibly function
                        if ($state < 3)
                            continue 2;
                        if (strpos($parms[1], '-!- FUNCTION: ') === 0)
                        {
                            $state = 4;
                            $parms[1] = substr($parms[1], 14);
                        }
                        else if (strpos($parms[1], '-!- ') === 0 && $state == 4)
                        {
                            $state = 0;
                            echo "Processing " . $current['gn'] . "\n";

                            // combine the function lines into one.
                            $function = implode(' ', $current['fn']);

                            $function = str_replace("\n", "", $function);

                            if (strlen($function) > 500)
                            {
                                $function = substr($function, 0, 500) . '...';
                            }

                            $record = Gene::name($current['gn'])->first();

                            if ($record !== null)
                                $record->update(['uniprot_id' => $current['ac'], 'function' => $function]);

                            $current = ['gn' => null, 'fn' => [], 'ac' => null];

                        }
                        if ($state == 4)
                            $current['fn'][] = $parms[1];
                        break;
                    default:
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
