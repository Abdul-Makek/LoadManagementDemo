<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\CustomClasses\SendHourlyMail;
use App\CustomClasses\ValidateHourlyMail;
use App\Models\HourlyLoadUpdatesMailTracker;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(new SendHourlyMail)->hourlyAt(01)->sendOutputTo("scheduler-output.log")->timezone('Asia/Dhaka');
        //$schedule->call(new SendHourlyMail)->everyMinute()->sendOutputTo("scheduler-output.log")->timezone('Asia/Dhaka');
        $schedule->call(new ValidateHourlyMail)->everyFiveMinutes()->sendOutputTo("scheduler-output.log")->timezone('Asia/Dhaka');
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
