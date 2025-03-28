<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Carbon\Carbon;

use App\Curation;

class FixAffiliate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:affiliate';

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
        echo "Fixing affiliate links for unpublished curations ...";

        //$g = Curation::validity()->status(Curation::STATUS_UNPUBLISH)->get();

        //dd($g);

        Curation::validity()->status(Curation::STATUS_UNPUBLISH)->each(function ($item) {
            $item->panels()->detach();
        });

        echo "DONE\n";
    }
}
