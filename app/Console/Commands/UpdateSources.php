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
        $this->call('update:acmg59');     // local file
        $this->call('update:activiity');
        $this->call('update:cytobands');  // local file
        $this->call('update:dosages');
        $this->call('update:map');        // local file
        $this->call('update:ratings');
        $this->call('update:region');     // local file
        break;
			case 'weekly':
        $this->call('update:Genenames');
        $this->call('update:cpic');       // local file
        $this->call('update:locations');  // local file
        $this->call('update:mane');
        $this->call('update:omim');       // local file
        $this->call('update:morbid');     // local file
        $this->call('update:plof');       // local file
        $this->call('update:uniprot');       // local file
				break;
			case 'monthly':
				$this->call('decipher:query');
				break;
		}

    }
}
