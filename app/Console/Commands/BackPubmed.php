<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Precuration;
use App\Pmid;

class BackPubmed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pubmed:back';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backload pubmed IDs for older curations';

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
		// gather up all the curation pubmed IDs that are absent from the system
        $curations = Precuration::all();

        foreach($curations as $curation)
        {
            if (isset($curation->rationale['pmids']))
            {
                foreach($curation->rationale['pmids'] as $pmid)
                {
                    if (empty($pmid))
                        continue;

                    echo "Checking for $pmid \n";
                    $entry = Pmid::firstOrCreate(['pmid' => $pmid, 'uid' => $pmid],
                                        [ 'status' => 20]);
                }
            }
        }
    }
}
