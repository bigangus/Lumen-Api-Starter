<?php

namespace App\Http\Controllers;

use App\Http\Responses\Facade\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Cookie;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * @throws ValidationException
     */
    public function login(Request $request): Response
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string',
            'remember' => 'nullable|boolean'
        ]);

        $credentials = $request->only(['username', 'password']);
        $remember = $request->input('remember', false);

        if (!$token = Auth::attempt($credentials, $remember)) {
            return HttpResponse::error('The provided credentials are incorrect', [], 401);
        }

        if ($remember) {
            $cookie = Cookie::create('jwt_token', $token, 60 * 24 * 365);
            return HttpResponse::success('Login successfully', [
                'token' => $token
            ])->withCookie($cookie);
        }

        return HttpResponse::success('Login successfully', [
            'token' => $token
        ]);
    }

    public function logout(): Response
    {
        Auth::logout();

        return HttpResponse::success('Successfully logged out');
    }
}
