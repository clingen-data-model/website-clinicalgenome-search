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

        $moreusers = User::doesntHave('genes')->with('notification')->get();

        foreach ($moreusers as $moreuser)
            if (!empty($moreuser->notification->frequency["Groups"]))
                $users->push($moreuser);

        $history = [];

        foreach ($users as $user)
        {
            echo "Processing " . $user->name . "\n";
            // clean up old reports
            $oldreports = $user->titles()->system()->unlocked()->expire(30)->get();
            foreach ($oldreports as $oldreport)
                $oldreport->delete();

            $notify = $user->notification;
            if ($notify === null)
                continue;

            if ($notify->frequency['global'] == 'off')
                continue;

            $lists = $notify->toReport();

            if (empty($lists))
                continue;

            $title = new Title(['type' => 1, 'title' => 'ClinGen Followed Genes Notification',
                                'description' => 'This report shows the genes that have published updates during the period shown.'
                                                . " To view details of a specific gene, click on the gene symbol name.  In rare cases, a change"
                                                . " may have been unpublished since this report was generated, and thus not be depicted on the"
                                                . " gene page.",
                                'status' => 1]);

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
                $genes = $changes->pluck('element.name')->unique()->sort();

                // override the primary
                if (!empty($notify->primary['email']))
                    $user->email = $notify->primary['email'];

                // send out notification (TODO move this to a queue and link into preferences)
                $mail = Mail::to($user);

                $date = Carbon::now()->yesterday()->format('m/d/Y');

                if (!empty($notify->secondary['email']))
                {
                    $cc = preg_split('/[\s,;]+/', $notify->secondary['email']);
                    $mail->cc($cc);
                }
                    
                $mail->send(new NotifyFrequency(['report' => $title->ident, 'date' => $date, 'genes' => $genes, 'name' => $user->name, 'content' => 'this is the custom message']));
            }
        }
    }
}
