<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
       $schedule->command('query:kafka gpm-general-events --assign')
        ->everyFiveMinutes()
        ->withoutOverlapping(30)   // prevents overlap for up to 30 mins
        ->appendOutputTo('/tmp/gpm-general.out');


        $schedule->command('query:kafka gpm-person-events')
            ->everyTenMinutes()
            ->withoutOverlapping(30) // prevents overlap for up to 30 mins
            ->appendOutputTo('/tmp/gpm-person.out');
        }


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
