<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Setting;

use App\Mim;
use App\Gene;

class UpdateMim extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:mim';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the MIM data from the OMIM genesmap2';

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

        echo "Updating Gene Map data from OMIM ...";

        $key = Setting::get('omim', false);

        if (!$key)
        {
            echo "\n(E002) Error retreiving Omim key\n";
            exit;
        }

        try {
            $results = file_get_contents("https://data.omim.org/downloads/" . $key . "/genemap2.txt");

		} catch (\Exception $e) {

			echo "\n(E001) Error retreiving Omim Map data\n";

		}

        $record = strtok($results, "\n");

        while ($record !== false)
        {
            /**
             *      Data format:
             *          0 - chromosome
            */
            // process the line read.
            //echo "Processing " . $record . "\n";

            $line = explode("\t", $record);

            if (strpos($line[0], '#') !== 0 && $line[8] !== null)
            {

                $gene = Gene::name($line[8])->first();

                if ($gene !== null)
                {
                    $phenotypes = $this->phenoparse($line[12]);

                    foreach ($phenotypes as $phenotype)
                    {
                        if (!isset($phenotype['mim']))
                            continue;

                        $stat = Mim::updateOrCreate(  ['mim' => $phenotype['mim']],
                                                [
                                                    'type' => Mim::TYPE_PHENO,
                                                    'gene_name' => $line[8],
                                                    'gene_id' => $gene->id,
                                                    'title' => $phenotype['title'],
                                                    'moi' => $phenotype['moi'],
                                                    'map_key' => $phenotype['key']
                                                    //'status' => 1
                                                ]);
                    }

                    // also store the gene mim
                    $stat = Mim::updateOrCreate(  ['mim' => $line[5]],
                    [
                        'type' => Mim::TYPE_GENE,
                        'gene_name' => $line[8],
                        'gene_id' => $gene->id,
                        'title' => $gene->description,
                        'moi' => null,
                        'map_key' => null
                        //'status' => 1
                    ]);


                }
                else
                {
                    echo "Skipping $record \n";
                }

            }

            $record = strtok("\n");

        }

        echo "... DONE\n";
    }


    public function phenoparse($data)
    {
        if (empty($data))
            return [];

        $phenotypes = [];

        /**
        # Each Phenotype is followed by its MIM number, if different from
        # that of the locus, preceded by a comma
        # Phenotype mapping key in parentheses follows the phenotype MIM
        # number (explanation below).
        # Allelic disorders are separated by a semi-colon following the
        # phenotype mapping key.
        # Inheritance for the phenotype follows the phenotype mapping key
        # preceded by a ,<space>
        # Explanation of the symbols and question marks (?) in the Phenotype
        # field is given in OMIM FAQ 1.6)
        #
        #
        # Phenotype Mapping Method - Appears in parentheses after a disorder :
        # --------------------------------------------------------------------
        #
        # 1 - the disorder is placed on the map based on its association with
        # a gene, but the underlying defect is not known.
        # 2 - the disorder has been placed on the map by linkage; no mutation has
        # been found.
        # 3 - the molecular basis for the disorder is known; a mutation has been
        # found in the gene.
        # 4 - a contiguous gene deletion or duplication syndrome, multiple genes
        # are deleted or duplicated causing the phenotype.

        Muscular dystrophy-dystroglycanopathy (congenital with brain and eye anomalies), type A, 1, 236670 (3), Autosomal recessive; Muscular dystrophy-dystroglycanopathy (limb-girdle), type C, 1, 609308 (3), Autosomal recessive; Muscular dystrophy-dystroglycanopathy (congenital with mental retardation), type B, 1, 613155 (3), Autosomal recessive

        */

        // split out the disorders
        $disorders = explode(';', $data);

        foreach($disorders as $disorder)
        {
            $disorder = trim($disorder);

            $stat = preg_match('/^(.*),\s(\d{6})\s\((\d)\)(|, (.*))$/', $disorder, $parts);

            if ($stat)
            {
                $phenotypes[] = [ 'title' => $parts[1], 'mim' => $parts[2], 'key' => $parts[3], 'moi' => $parts[5] ?? null ];
            }
            else{
                // this is what omim calls a short phenotype, which uses a different parser
                $stat = preg_match('/^(.*)\((\d)\)(|, (.*))$/', $disorder, $parts);
                if ($stat !== false)
                    $phenotypes[] = [ 'title' => $parts[1], 'key' => $parts[2], 'moi' => $parts[3] ?? null ];

            }
        }

        return $phenotypes;
    }
}
