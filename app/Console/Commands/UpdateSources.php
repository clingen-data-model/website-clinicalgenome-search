<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

use App\Gene;
use App\Jira;

class UpdateSources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:sources {schedule=daily}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Master command to update all sources based on schedule';

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
      $schedule = $this->argument('schedule');
      switch ($schedule)
      {
        case 'daily':
          $this->call('update:Genenames');  // HGNC
          $this->call('update:activity');   // Genegraph
          $this->call('update:cpic');       // CPIC and PharmGKB
          $this->call('update:erepo');      // Erepo
          $this->call('update:cytobands');  // UCSC (goldenpath hg19)
          $this->call('update:index', ['report' =>  'gene']); // Refresh index file from Jira
          $this->call('update:index', ['report' =>  'region']);// Refresh index file from Jira
          $this->call('update:dosages');    // DCI (Jira)
          $this->call('update:map');        // local file
          $this->call('update:ratings');    // DCI (Jira)
          $this->call('update:region');     // local file
          $this->call('update:disease');    // Genephap
          $this->call('gencc:query');       // Gencc souce
          $this->call('query:oms');         // update afflisliates from website
          $this->call('update:affiliates'); // Update from genegraph and erepo
          $this->call('update:changes');
          $this->call('update:follow');
          $this->call('update:summary');
          $this->call('update:actionability-summaries');  // Get Actionability data from ACI
          $this->call('run:metrics');       // Daily stats
          break;
        case 'weekly':
          $this->call('decipher:query');    // Decipher
          $this->call('update:locations');  // local file from NCBI
          $this->call('update:mane');       // NCBI MANE
          $this->call('update:omim');       // OMIM
          $this->call('update:morbid');     // OMIM Morbid
          $this->call('update:mim');       // OMIM
          $this->call('update:plof');       // local file Gnomad EXAC
          $this->call('update:uniprot');    // Uniprot
          $this->call('update:mondo');      // Monarch
          break;
        case 'monthly':
          $this->call('update:acmg59');     // local file
          $this->call('update:rxnorm');       // Local file from bioontology
          break;
        case 'init':
          $this->call('update:acmg59c');     // local file
          $this->call('update:Gdmmap');     // local file
          break;

      }

    }
}
