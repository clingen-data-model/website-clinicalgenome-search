<?php

namespace App\Console\Commands;

use App\Member;
use Illuminate\Console\Command;

class PushMembersToProcessWire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'processwire:members {member_id?}';

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
        $args = $this->arguments();
        $query = Member::whereNotNull('gpm_id');

        if ($memberId = $this->argument('member_id')) {
            $query->where('id', $memberId);
        }

        $members = $query->get();

        foreach ($members as $member) {
            $this->output->info('sending memberid ' . $member->id . $member->first_name);
            $e = $member->createProcessWireUser();
        }
    }
}
