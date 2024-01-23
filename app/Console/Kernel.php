<?php

namespace App\Console;

use App\Console\Commands\GeneratePermissions;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        GeneratePermissions::class
    ];

    protected function schedule(Schedule $schedule): void
    {
//        $schedule->command('permissions:generate')->hourly();
    }
}
