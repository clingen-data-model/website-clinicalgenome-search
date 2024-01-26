<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Genomeconnect;
use App\Gene;

use Setting;

class UpdateGenomeconnect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:genomeconnect {type=daily}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Pubmed for IDs relevant to search terms';

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
      $schedule = $this->argument('type');
      if ($schedule == 'init')
      {
        echo "Initializing Geneconnect Table...";
        try {

          $handle = fopen(base_path() . '/data/gcl_list.csv', "r");

        } catch (\Exception $e) {

          echo "ERROR (CANNOT OPEN INIT FILE)\n";
          exit;
        }

        while (($line = fgetcsv($handle)) !== false)
        {
          $name = $line[0];
          echo "$name \n";

          $gene = Gene::name($name)->first();

          if ($gene == null)
          {
            $gene = Gene::alias($name)->first();

            if ($gene == null)
              $gene = Gene::previous($name)->first();
          }

          if ($gene === null)
          {
              echo "ERROR (GENE $name NOT FOUND)\n";
             continue;
          }

          $gc = $gene->genomeconnect;

          if ($gc == null)
          {
            $gc = new Genomeconnect( ['status' => Genomeconnect::STATUS_INITIALIZED]);
            $gene->genomeconnect()->save($gc);
          }
        }
      }
        
      // set up search array
      $parms = ['db' => 'clinvar', 
          'term' => '',
				  'api_key' => '59e073ce6f18becd93e36bd2613dfde47509',
          'retstart' => 0,
          'retmax' => 1000,
          'datetype' => 'dp',
			    'retmode' => 'json'];

        echo "Querying Clinvar for Geneconnect...";

        $records = Genomeconnect::all();

        foreach ($records as $record)
        {
          $gene = $record->gene;

          echo "$gene->name \n";

          $parms['term'] = '(genomeconnect[Submitter] OR "genomeconnect, clingen"[Submitter]) AND ' . $gene->name . '[Gene Name]';
          $encoded_parms = http_build_query($parms);
        
          $results = file_get_contents('http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?' . $encoded_parms);
          //dd($results);
          if ($results === false)
          {
            // error getting the query results
            echo "ERROR (QUERY $gene->name)\n";
            $status = false;
            exit;
          }

          $json = json_decode($results);

        //dd($json->esearchresult);

          $count = (int) $json->esearchresult->count;

          $record->type = Genomeconnect::TYPE_CLINVAR;
          $record->variant_count = $count;
          $record->clinvar_ids = $json->esearchresult->idlist;
          $record->status = Genomeconnect::STATUS_SUCCESS;

          $record->update();

        }

        echo "DONE\n";
          
    }
}
