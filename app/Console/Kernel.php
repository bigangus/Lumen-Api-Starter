<?php

namespace App\Console;

use App\Console\Commands\CreateUser;
use App\Console\Commands\GeneratePermissions;
use App\Console\Commands\RotateLogs;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Scheduling\ScheduleListCommand;
use Illuminate\Console\Scheduling\ScheduleRunCommand;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        CreateUser::class,
        GeneratePermissions::class,
        RotateLogs::class,
        ScheduleListCommand::class
    ];

    /**
     * @param Schedule $schedule
     * @return void
     * To run in production server, setup cron entry like this:
     *  * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
     *
     * To test in local development, run this command:
     * php artisan queue:work
     */
    protected function schedule(Schedule $schedule): void
    {
         $schedule->command(GeneratePermissions::class)->hourly();
         $schedule->command(RotateLogs::class)->daily();
    }
}
