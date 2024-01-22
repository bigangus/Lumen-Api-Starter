<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $response = $next($request);

        $data = [
            'Request Method' => $request->method(),
            'Request Path' => $request->path(),
            'Requesting User' => Auth::user() ? Auth::user()->username : 'guest',
            'Request Params' => $request->all(),
            'Request IP' => $request->ip(),
            'Origin' => $request->header('host'),
        ];

        return $response;
    }
}
