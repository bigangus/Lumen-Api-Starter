<?php

namespace App\Console;

use App\Console\Commands\CreateUser;
use App\Console\Commands\GeneratePermissions;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        CreateUser::class,
        GeneratePermissions::class
    ];

    protected function schedule(Schedule $schedule): void
    {
         $schedule->command('inspire')->hourly();
    }
}
