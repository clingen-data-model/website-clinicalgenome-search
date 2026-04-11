<?php

namespace App\Console\Commands;

use App\Member;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessGpmPerson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:gpmuser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process person data from the gpm kafka person events';

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
     * @return int
     */
    public function handle()
    {
        //$members = Member::whereNull('processwire_id')->take(3)->offset(20)->first();
        $members = Member::where('id', 13473)->get();
        //Get all data from members where last_nameis unique...
//        $members = Member::select('*', DB::raw('crount(*) as count'))
//            ->whereNull('processwire_id')
//            ->groupBy('last_name')
//            ->having('count', '=', 1)->get();


        $bar = $this->output->createProgressBar($members->count());
        $members->each ( function (Member $member) use ($bar) {
            $response = $member->createProcessWireUser();
            dd($response);
            $bar->advance();
        });

        $bar->finish();
    }
}
