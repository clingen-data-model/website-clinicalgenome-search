<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Precuration;
use App\Term;

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
        // type 14 (OMIM match) terms are written only by this feature, so a
        // full purge-and-rebuild keeps the backfill idempotent even when the
        // (name, value) mapping changes — no stale rows left behind.
        $purged = Term::where('type', 14)->forceDelete();
        $this->info($purged . ' existing OMIM phenotype terms cleared');

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
