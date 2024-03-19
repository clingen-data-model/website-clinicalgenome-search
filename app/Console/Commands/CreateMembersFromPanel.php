<?php

namespace App\Console\Commands;

use App\Member;
use App\Panel;
use Illuminate\Console\Command;

class CreateMembersFromPanel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'panel:members {panel_id}';

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
     * @return int
     */
    public function handle()
    {
        if ($panel_id = $this->argument('panel_id')) {
            $panel = Panel::find($panel_id);
            if (null !== $panel) {
                if ($members = $panel->member) {
                    foreach( $members  as $index => $membersGroup) {
                        //echo $index;
                        foreach ($membersGroup as $group => $memberGroup) {
                            dd($member);
                           $member = Member::firstOrNew([

                           ]);
                        }
                    }

                }
            } else {
                throw new \Exception('We could not find this panel id: ' . $panel_id);
            }
        }
    }
}
