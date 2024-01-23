<?php

namespace App\Http\Controllers;

use App\Http\Responses\Facade\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * @throws ValidationException
     */
    public function login(Request $request): Response
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $credentials = $request->only(['username', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return HttpResponse::error('The provided credentials are incorrect', [], 401);
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

    public function me(): Response
    {
        return HttpResponse::success('Success', [
           'user' => Auth::user()
        ]);
    }
}
