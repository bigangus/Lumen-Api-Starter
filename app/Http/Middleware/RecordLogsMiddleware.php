<?php

namespace App\Http\Middleware;

use App\Jobs\RecordRequestLogJob;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Queue;

class RecordLogsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        /* @var Response $response */
        $response = $next($request);

        $logData = [
            'user_id' => Auth::user() ? Auth::user()->id : null,
            'ticket' => $response->getOriginalContent()->ticket,
            'method' => $request->method(),
            'path' => $request->path(),
            'ip' => $request->ip(),
            'origin' => $request->header('host'),
            'request' => json_encode($request->all()),
            'response' => $response->getContent(),
            'time' => Carbon::now()
        ];

        Queue::push(new RecordRequestLogJob($logData));

        return $response;
    }
}
