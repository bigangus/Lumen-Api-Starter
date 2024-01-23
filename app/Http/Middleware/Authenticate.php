<?php

namespace App\Http\Middleware;

use App\Http\Responses\Facade\HttpResponse;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var Auth
     */
    protected Auth $auth;

    /**
     * Create a new middleware instance.
     *
     * @param Auth $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $guard = null): mixed
    {
        if ($this->auth->guard($guard)->guest()) {
            return HttpResponse::error('Unauthorized', [], 401);
        }

        if (!\Illuminate\Support\Facades\Auth::user()->hasPermissionTo($request->getRequestUri())) {
            return HttpResponse::error('Not allowed to access this route', [], 403);
        }

        return $next($request);
    }
}
