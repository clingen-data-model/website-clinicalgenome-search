<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

use App\Gene;
use App\User;

class UpdateRatings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:follow';

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
        echo "Get list of followed genes ...\n";

        $genes = DB::table('gene_user')->select('gene_id')->distinct()->get();

        foreach ($genes as $geneid)
        {
            echo $geneid->gene_id . "\n";

            $gene = Gene::find($geneid->gene_id);

            if ($gene === null)
                continue;

            $record = GeneLib::geneDetail([
                'gene' => $gene->hgnc_id,
                'curations' => true,
                'action_scores' => true,
                'validity' => true,
                'dosage' => true,
                'pharma' => true
            ]);

            // validity
            // dosage sensitivity
            // actionability
            // pharma

            // if any changed in the past period, send email to all users
        }
    }
}
