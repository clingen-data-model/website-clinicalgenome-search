<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Precuration;

class UpdateOmimTerms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:omimterms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill condition autocomplete synonym terms from precuration included OMIM phenotypes';

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
     * Idempotent — re-runnable.  Each precuration's included OMIM phenotypes
     * are written as (name, value) synonym terms via updateOrCreate, so the
     * condition autocomplete can find them by disease name.
     *
     * @return int
     */
    public function handle()
    {
        $precurations = Precuration::whereNotNull('omim_phenotypes')->get();

        $this->info($precurations->count() . ' precurations to process');

        $terms = 0;
        $bar = $this->output->createProgressBar($precurations->count());

        foreach ($precurations as $precuration)
        {
            $terms += $precuration->syncOmimPhenotypeTerms();
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info($terms . ' OMIM phenotype synonym terms written');

        return 0;
    }
}
