<?php

namespace App\Http\Middleware;

use App\Http\Responses\Facade\HttpResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessControl
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::check() && Auth::user()->hasPermissionTo($request->getRequestUri())) {
            return $next($request);
        }

        return HttpResponse::error('Not allowed to access this route', [], 403);
    }
}
