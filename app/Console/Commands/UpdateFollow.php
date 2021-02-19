<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use Carbon\Carbon;

use App\Gene;
use App\User;

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
            // has last_updated changed in the past 24 hours?
            foreach ($user->genes as $gene)
            {
                $last = Carbon::parse($gene->date_last_curated);
                $now = Carbon::now();

                $diff = $last->diffInHours($now);
                echo $gene->date_last_curated . " -- $diff" . "\n";

                // retrieve the frequency values for this user
                $notification = $user->notification;

                $time = ($notification->frequency['frequency'] ?? Notification::FREQUENCY_DAILY);

                $time = $notification->toHours($time);
                
                if ($diff < $time)
                {
                    $history[] = "Gene " . $gene->name . " changed in the past 24 hours";
                }
            }

            // if there is something to report, send out
            if (!empty($history))
            {
                // send the email
                Mail::to($user)
                       // ->cc($moreUsers)
                       // ->bcc($evenMoreUsers)
                        ->send(new NotifyFrequency(['notes' => $history, 'name' => $user->name]));
            }
        }
    }
}
