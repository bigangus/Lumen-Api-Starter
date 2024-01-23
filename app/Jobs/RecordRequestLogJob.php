<?php

namespace App\Jobs;

use App\Models\RequestLog;

class RecordRequestLogJob extends Job
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        RequestLog::query()->create($this->data);
    }
}
