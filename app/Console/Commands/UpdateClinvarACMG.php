<?php

namespace App\Console\Commands;

require_once app_path() . '/../vendor/ezyang/htmlpurifier/library/HTMLPurifier.auto.php';

use Illuminate\Console\Command;
//use ezyang\htmlpurifier\library\HTMLPurifier;

use DOMDocument;

use Setting;

use App\Disease;
use App\Gene;
use App\Acmg;

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

    if ($results === false) {
      // error getting the query results
      $status = false;
      exit;
    }

    // extract the table
    $i = strpos($results, '<table ');
    $table = substr($results, $i);
    $i = strpos($table, '</table>');
    $table = substr($table, 0, $i + 8);

    // The ClinVar page has a bunch of missing tags in the table which causes DOMDocument to fail, fix them here.
    $pattern = '/<\/tr>(\s*)<td>/i';
    $replacement = '</tr><tr><td>';
    $table = preg_replace($pattern, $replacement, $table);

    $config = \HTMLPurifier_Config::createDefault();
    $purifier = new \HTMLPurifier($config);
    $clean_html = $purifier->purify($table);

    
    // Now import into a searchable DOM
    $DOM = new DOMDocument();
    $DOM->loadHTML($clean_html);

    $records = [];

    $table = $DOM->getElementsByTagName('table')->item(0);

    foreach ($table->getElementsByTagName('tr') as $tr) {
      $tds = $tr->getElementsByTagName('td');
      if ($tds->length == 4) {
        $dn = $tds->item(0)->nodeValue;  //Disease name and MIM number
        preg_match('/(.+)\s\(MIM (\d+)(, MIM (\d+))?\)/', $dn, $matches);

        $gn = $tds->item(2)->nodeValue;  // Gene and MIM number
        preg_match('/(.+)\s\(MIM (\d+)(, MIM (\d+))?\)/', $gn, $matches2);

        $cvl = trim($tds->item(3)->getElementsByTagName('a')->item(0)->getAttribute('href'))
                  . '"single+gene"[prop]';
        $mgl = trim($tds->item(1)->getElementsByTagName('a')->item(0)->getAttribute('href'));

        $disease_mims = isset($matches[2]) ? [ $matches[2]] : [];
        if (isset($matches[4]))
          $disease_mims[] = $matches[4];

        $records[] = [
          'disease_name' => $matches[1] ?? trim($dn), 'disease_mims' => $disease_mims,
          'disease_mondo' => null, 'disease_mondo_2' => null,
          'gene_symbol' => $matches2[1], 'gene_mim' => $matches2[2],
          'gene_id' => null, 'disease_id' => null, 'medgen' => $mgl,
          'clinvar' =>  $cvl
        ];
      }
    }


    // Do some post processing
    foreach ($records as &$record) {
      if (isset($record['disease_mims'][0])) {
        $d1 = Disease::omim($record['disease_mims'][0])->first();

        if ($d1 !== null) {
          $record['disease_mondo'] = $d1->curie;
          $record['disease_id'] = $d1->id;
        } else {
          echo "No MONDO for MIM " . $record['disease_mims'][0] . " \n";
        }

      }
      else
      {
        if ($record['disease_name'] == "Long QT syndrome")
        {
          // map this to mondo 0002442
          $d1 = Disease::curie('MONDO:0002442')->first();
          $record['disease_mondo'] = $d1->curie;
          $record['disease_id'] = $d1->id;
        }
      }

      if (isset($record['disease_mim_2'][1])) {
        $d1 = Disease::omim($record['disease_mims'][1])->first();

        if ($d1 !== null) {
          $record['disease_mondo_2'] = $d1->curie;
        } else {
          echo "No MONDO for MIM " . $record['disease_mims'][1] . " \n";
        }
      }

      $gene = Gene::name($record['gene_symbol'])->first();
      if ($gene !== null)
        $record['gene_id'] = $gene->id;
      else
        echo "No Gene Entry for " . $record['gene_symbol'] . " \n";

    }

    // Create/update table entries
    foreach ($records as $acmg)
    {
        $acmg = Acmg::updateOrCreate(['gene_id' => $acmg['gene_id'], 'disease_id' =>  $acmg['disease_id']],
                                      ['type' => 1,
                                      'gene_symbol' => $acmg['gene_symbol'],
                                      'gene_mim' => $acmg['gene_mim'],
                                      'disease_name' => $acmg['disease_name'],
                                      'disease_mims' => $acmg['disease_mims'],
                                      'documents' => ['MedGen' => basename($acmg['medgen'])],
                                      'demographics' => null,
                                      'scores' => null,
                                      'clinvar_link' => $acmg['clinvar'],
                                      'is_curated' => false,
                                      'status' => 1
                                    ]);
    }

    echo "DONE\n";
  }
}
