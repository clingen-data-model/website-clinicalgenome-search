<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use Carbon\Carbon;

use App\Gene;
use App\User;
use App\Actionability;
use App\Sensitivity;
use App\Validity;

class UpdateChanges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:changes';

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
        echo "Downloading Gene Disease Validity changes ...\n";

        $model = new Validity();
        $model->assertions();

        echo "Downloading Clinical Actionability changes ...\n";

        $model = new Actionability();
        $model->assertions();
        
        echo "Downloading Dosage Sensitivity changes ...\n";

        $model = new Sensitivity();
        $model->assertions();
        
    }
}
