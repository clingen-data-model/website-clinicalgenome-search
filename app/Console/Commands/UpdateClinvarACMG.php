<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DOMDocument;

use Setting;

class UpdateClinvarACMG extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:cvacmg';

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
        
      $results = file_get_contents('https://www.ncbi.nlm.nih.gov/clinvar/docs/acmg/');
          //dd($results);

      if ($results === false)
      {
        // error getting the query results
        $status = false;
        exit;
      }

      // extract the table
      $i = strpos($results, '<table ');
      $table = substr($results, $i);
      $i = strpos($table, '</table>');
      $table = substr($table, 0, $i + 8);

      // the clinvar page has a bunch of malformed rows.  Fix them here
      $pattern = '/<\/tr>(\s*)<td>/i';
      $replacement = '</tr><tr><td>';
      $table = preg_replace($pattern, $replacement, $table);

      $pattern = '/<\/tr>(\s*)<td>/i';
      $replacement = '</tr><tr><td>';
      $table = preg_replace($pattern, $replacement, $table);

      //dd($table);

      $DOM = new DOMDocument();
	    $DOM->loadHTML($table);
	
	    $Header = $DOM->getElementsByTagName('th');
	    $Detail = $DOM->getElementsByTagName('td');

      dd($Header);

        echo "DONE\n";
          
    }

    public function cleanHTML($html) {
      $doc = new DOMDocument();
      /* Load the HTML */
      $doc->loadHTML($html,
              LIBXML_HTML_NOIMPLIED | # Make sure no extra BODY
              LIBXML_HTML_NODEFDTD |  # or DOCTYPE is created
              LIBXML_NOERROR |        # Suppress any errors
              LIBXML_NOWARNING        # or warnings about prefixes.
      );
      /* Immediately save the HTML and return it. */
      return $doc->saveHTML();
  }
}
