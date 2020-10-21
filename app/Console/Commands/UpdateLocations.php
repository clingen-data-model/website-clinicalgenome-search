<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Gene;

class UpdateLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:locations';

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
        echo "Importing ncbi location information ...\n";
		
		$handle = fopen(base_path() . '/data/hg19/interim_GRCh37.p13_top_level_2017-01-13.gff3', "r");
        if ($handle)
        {
            while (($line = fgets($handle)) !== false)
            {
                if ($line[0] == '#')
                    continue;

                $parts = explode("\t", $line);

                if ($parts[2] != "gene")
                    continue;

                $split = preg_split('/[_\.]/', $parts[0]);

                $chr = ltrim($split[1], "0");
                $start = $parts[3];
                $stop = $parts[4];

                // the HGNC ID lives here
                $moreparts = explode(";", $parts[8]);
                $k = strpos($moreparts[1], ',HGNC:');
                if ($k === false)
                    continue;

                $split = substr($moreparts[1], $k + 6 );
                $split = explode(',', $split);
                $hgncid = $split[0];

                echo "Updating 37 " . $hgncid . "\n";
                
                $record = Gene::hgnc($hgncid)->first();

                if ($record !== null)
                    $record->update(['chr' => $chr, 'start37' => $start, 'stop37' => $stop]);

                        
            }

            fclose($handle);
        }
        else
        {
            echo "Cannot access IDX file\n";
        }

        $handle = fopen(base_path() . '/data/hg38/GCF_000001405.39_GRCh38.p13_genomic.gff', "r");
        if ($handle)
        {
            while (($line = fgets($handle)) !== false)
            {
                if ($line[0] == '#')
                    continue;

                $parts = explode("\t", $line);

                if ($parts[2] != "gene")
                    continue;

                $split = preg_split('/[_\.]/', $parts[0]);

                $chr = ltrim($split[1], "0");
                $start = $parts[3];
                $stop = $parts[4];

                // the HGNC ID lives here
                $moreparts = explode(";", $parts[8]);
                $k = strpos($moreparts[1], ',HGNC:');
                if ($k === false)
                    continue;

                $split = substr($moreparts[1], $k + 6 );
                $split = explode(',', $split);
                $hgncid = $split[0];

                echo "Updating 38 " . $hgncid . "\n";
                
                $record = Gene::hgnc($hgncid)->first();

                if ($record !== null)
                    $record->update(['start38' => $start, 'stop38' => $stop]);

                        
            }

            fclose($handle);
        }
        else
        {
            echo "Cannot access IDX file\n";
        }
    }
}
