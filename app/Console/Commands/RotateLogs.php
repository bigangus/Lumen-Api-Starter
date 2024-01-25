<?php

namespace App\Console\Commands;

use App\Models\RequestLog;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RotateLogs extends Command
{
    protected $signature = 'logs:rotate';

    protected $description = 'Rotate the request logs';

    public function handle(): void
    {
        $days = 30;

        RequestLog::query()
            ->where('created_at', '<', Carbon::now()->subDays($days))
            ->delete();

        $this->info('Logs rotated successfully.');
    }
}
