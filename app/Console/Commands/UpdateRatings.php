<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jira;
use App\Gene;
use App\Region;

class UpdateRatings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:ratings';

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
        echo "Checking for recent updates ...\n";

        $updates = Jira::ratingsList(['page' =>  0,
                                    'pagesize' =>  "null",
                                    'sort' => 'GENE_LABEL',
                                    'direction' =>  'ASC',
                                    'search' =>  null,
                                    'curated' => false ]);
            
        foreach($updates as $update)
        {
            // process the line read.
            echo "Processing " . $update->key . "\n";

            switch ($update->type)
            {
                case 'ISCA Gene Curation':
                    $gene = Gene::name($update->title)->first();
                    if ($gene === null)
                        continue;
                    if ($gene->history === null)
                        $history = [ $update->attributesToArray() ];
                    else
                    {
                        $history = $gene->history;
                        $history[] = $update->attributesToArray();
                    }
                    $gene->update(['history' => $history]);
                    break;
                case 'ISCA Region Curation':
                    $region = Region::issue($update->issue)->first();
                    if ($region === null)
                        continue;
                    if ($region->history === null)
                        $history = [ $update->attributesToArray()];
                    else
                    {
                        $history = $region->history;
                        $history[] = $update->attributesToArray();
                    }
                    $region->update(['history' => $history]);
                    break;
            }
        }
    }
}
