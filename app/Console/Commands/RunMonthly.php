<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Gene;
use App\Metric;
use App\Graphql;

class RunMonthly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:monthly';

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
        echo "Running curation total report ...\n";

        $fd = fopen("/tmp/report.csv", "w");
        $line = "Date,Validity Curations,Dosage Curations,Monthly Validity, Monthly Dosage\n";
        fputs($fd, $line);

		$metrics = Metric::all();

        $vfirst = 0;
        $dfirst = 0;
        $vdelta = 0;
        $ddelta = 0;
        $month = 0;

        foreach ($metrics as $metric)
        {
            // deal with early days where a counter does not exist;
            if ($vfirst == 0)
                $vfirst = $metric->values[Metric::KEY_TOTAL_VALIDITY_CURATIONS] ?? 0;

            if ($dfirst == 0)
                $dfirst = $metric->values[Metric::KEY_TOTAL_DOSAGE_CURATIONS] ?? 0;


            $thismonth = $metric->created_at->month;
            if ($month != $thismonth)
            {
                $line = $metric->created_at->format('F Y') . ','
                    . '' . ' ,'
                    . '' . ','
                    . $vdelta . ',' . $ddelta . "\n";

                fputs($fd, $line);

                $month = $thismonth;
                $vfirst = ($vfirst == 0) ? $metric->values[Metric::KEY_TOTAL_VALIDITY_CURATIONS] ?? 0 : $vold;
                $dfirst = ($dfirst == 0) ? $metric->values[Metric::KEY_TOTAL_DOSAGE_CURATIONS] ?? 0 : $dold;
            }

            if ($vfirst != 0)
                $vdelta = $metric->values[Metric::KEY_TOTAL_VALIDITY_CURATIONS] - $vfirst;

            if ($dfirst != 0)
                $ddelta = $metric->values[Metric::KEY_TOTAL_DOSAGE_CURATIONS] - $dfirst;

            $line = $metric->displayDate($metric->created_at) . ','
                    . ($metric->values[Metric::KEY_TOTAL_VALIDITY_CURATIONS] ?? '') . ' ,'
                    . ($metric->values[Metric::KEY_TOTAL_DOSAGE_CURATIONS] ?? '') . ','
                    . '' . ',' . '' . "\n";
            fputs($fd, $line);


            $vold = $metric->values[Metric::KEY_TOTAL_VALIDITY_CURATIONS] ?? 0;
            $dold = $metric->values[Metric::KEY_TOTAL_DOSAGE_CURATIONS] ?? 0;

        }

        $line = $metric->created_at->format('F Y') . ','
                    . '' . ' ,'
                    . '' . ','
                    . $vdelta . ',' . $ddelta . "\n";

                fputs($fd, $line);

        fclose($fd);
    }
}
