<?php

namespace App\DataExchange\Commands;

use App\User;
use App\Affiliation;
use App\StreamError;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use App\DataExchange\Notifications\StreamErrorNotification;

class CreateNotificationsForStreamErrors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dx:notify-errors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends notifications for errors resulting from the streaming service integeration.';

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
        $groupedErrors = StreamError::unsent()
                            ->with('geneModel', 'diseaseModel', 'moiModel')
                            ->get()
                            ->groupBy('affiliation_id');
        $affiliations = Affiliation::with('expertPanel')->get()->keyBy('clingen_id');
        $groupedErrors->each(function ($errors, $affiliation_id) use ($affiliations) {
            $affiliation = $affiliations->get($affiliation_id);
            if (!$affiliation) {
                return;
            }
            if (!$affiliation->expertPanel || $affiliation->expertPanel->coordinators->count() == 0) {
                Notification::send(User::role('admin')->get(), new StreamErrorNotification($errors));
                return;
            }
            Notification::send($affiliation->expertPanel->coordinators, new StreamErrorNotification($errors));
        });

        $groupedErrors->flatten()->each->markSent();
    }
}
