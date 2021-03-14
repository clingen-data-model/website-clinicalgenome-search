<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

use DB;
use Carbon\Carbon;

use App\Gene;
use App\User;
use App\Title;
use App\Report;

use App\Mail\NotifyFrequency;

class UpdateFollow extends Command
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

        $users = User::has('genes')->with('genes')->get();

        $history = [];

        foreach ($users as $user)
        {
            // clean up old reports
            $oldreports = $user->titles()->system()->unlocked()->expire(30)->get();
            foreach ($oldreports as $oldreport)
                $oldreport->delete();

            $notify = $user->notification;
            if ($notify === null)
                continue;

            $lists = $notify->toReport();

            if (empty($lists))
                continue;

            $title = new Title(['type' => 1, 'title' => 'ClinGen Followed Genes Notification', 'status' => 1]);

            $user->titles()->save($title);

            foreach ($lists as $list)
            {
                $report = new Report($list);
                $report->type = 1;
                $report->status = 1;
                $report->user_id = $user->id;
                $title->reports()->save($report);
            }

            $changes = $title->run();

            if ($changes->isNotEmpty())
            {
                $user->titles()->save($title);
                $genes = [  'ABCD',
                            'KFR',
                            'SMAD3'];

                // send out notification (TODO move this to a queue and link into preferences)
                Mail::to($user)
                // ->cc($moreUsers)
                // ->bcc($evenMoreUsers)
                    ->send(new NotifyFrequency(['report' => $title->ident, 'genes' => $genes, 'name' => $user->name, 'content' => 'this is the custom message']));
            }
        }
    }
}
