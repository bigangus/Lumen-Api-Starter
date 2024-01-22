<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RecordLogsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $data = [
            'Request Method' => $request->method(),
            'Request Path' => $request->path(),
            'Requesting User' => $request->user()->toArray(),
            'Request Params' => $request->all(),
            'Request IP' => $request->ip(),
            'Origin' => $request->header('host'),
        ];

        return $next($request);
    }
}
