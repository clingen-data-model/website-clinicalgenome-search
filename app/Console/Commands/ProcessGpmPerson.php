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
        $members = Member::get();
        //Get all data from members where last_nameis unique...
//        $members = Member::select('*', DB::raw('count(*) as count'))
//            ->whereNull('processwire_id')
//            ->groupBy('last_name')
//            ->having('count', '=', 1)->get();

        $bar = $this->output->createProgressBar($members->count());
        $members->each ( function (Member $member) use ($bar) {
            $response = $member->pushToProcessWire('update-gpm-id');
            if ($userData = json_decode($response, true)) {
                $member->processwire_id = $userData['page_id'];
                $member->save();
            }
            $bar->advance();
        });

        $bar->finish();
    }
}
