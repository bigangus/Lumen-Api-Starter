<?php

namespace App\Http\Controllers;

use App\Http\Responses\Facade\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh', 'logout']]);
    }

    /**
     * @throws ValidationException
     */
    public function login(Request $request): \App\Http\Responses\HttpResponse
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $credentials = $request->only(['username', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return HttpResponse::error('The provided credentials are incorrect', [], 401);
        }

        return HttpResponse::success('Login successfully', ['token' => $token]);
    }

    public function logout(): \App\Http\Responses\HttpResponse
    {
        auth()->logout();

        return HttpResponse::success('Successfully logged out');
    }

    public function me(): \App\Http\Responses\HttpResponse
    {
        return HttpResponse::success('Success', [
           'user' => auth()->user()
        ]);
    }
}
