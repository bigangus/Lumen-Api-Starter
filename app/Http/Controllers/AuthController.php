<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @throws ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $credentials = $request->only(['username', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.']
            ]);
        }

        return response()->json([
            'token' => $token
        ]);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
