<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocaleSwitcher
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
        app('translator')->setLocale($request->header('Accept-Language') ?? 'en');

        return $next($request);
    }
}
