<?php

namespace App\Http\Controllers;

use App\Http\Responses\Facade\HttpResponse;
use App\Jobs\SendVerificationCodeJob;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Cookie;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api', ['only' => ['logout']]);
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

        $username = $request->input('username');
        $credentials = $request->only(['password']);
        $remember = $request->input('remember', false);

        $token = Auth::attempt($request->only(['username', 'password']));

        if (!$token) {
            if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                $credentials['email'] = $username;
            } elseif (preg_match('/^\d+$/', $username)) {
                $credentials['phone'] = $username;
            }

            $token = Auth::attempt($credentials);
        }

        if (!$token) {
            return HttpResponse::error('The provided credentials are incorrect', [], 401);
        }

        if (Auth::user()->isDisabled()) {
            return HttpResponse::error('Your account has been disabled', [], 403);
        }

        if ($remember) {
            $cookie = Cookie::create('jwt_token', $token, time() + (3600 * 720));
            return HttpResponse::success('Login successfully', [
                'token' => $token
            ])->withCookie($cookie);
        }

        return HttpResponse::success('Login successfully', [
            'token' => $token
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function loginWithVerificationCode(Request $request): Response
    {
        $this->validate($request, [
            'phone' => 'required|string',
            'code' => 'required|integer',
        ]);

        $phoneNumber = $request->input('phone');
        $code = $request->input('code');

        $user = User::query()->where('phone', $phoneNumber)->first();

        if (!$user) {
            return HttpResponse::error('User not found', [], 404);
        }

        $smsCode = $request->session()->get('sms_code')['value'] ?? null;

        if ($smsCode != $code) {
            return HttpResponse::error('Verification code is incorrect', [], 400);
        }

        $token = Auth::login($user);

        $request->session()->forget('sms_code');

        return HttpResponse::success('Login successfully', [
            'token' => $token
        ]);
    }

    public function logout(): Response
    {
        Auth::logout();

        return HttpResponse::success('Successfully logged out');
    }

    /**
     * @throws ValidationException
     */
    public function sendSmsCode(Request $request): Response
    {
        $this->validate($request, [
            'phone' => 'required|string',
        ]);

        $phoneNumber = $request->input('phone');

        $user = User::query()->where('phone', $phoneNumber)->first();

        if (!$user) {
            return HttpResponse::error('Phone not found', [], 404);
        }

        $smsCode = rand(100000, 999999);

        if (app()->isProduction()) {
            Queue::push(new SendVerificationCodeJob($phoneNumber, $smsCode));
        }

        $request->session()->put('sms_code', ['value' => $smsCode, 'expires_at' => Carbon::now()->addSeconds(60)]);

        return HttpResponse::success('SMS code sent successfully, expires after 60 seconds');
    }

    /**
     * @throws ValidationException
     */
    public function forgotPassword(Request $request): Response
    {
        $this->validate($request, [
            'phone' => 'required|string',
            'code' => 'required|integer',
            'password' => 'required|string',
        ]);

        $phoneNumber = $request->input('phone');
        $code = $request->input('code');
        $password = $request->input('password');

        $smsCode = $request->session()->get('sms_code')['value'] ?? null;

        if ($smsCode != $code) {
            return HttpResponse::error('SMS code is incorrect', [], 400);
        }

        $user = User::query()->where('phone', $phoneNumber)->first();

        if (!$user) {
            return HttpResponse::error('User not found', [], 404);
        }

        $user->password = Hash::make($password);
        $user->save();

        $request->session()->forget('sms_code');

        return HttpResponse::success('Password changed successfully');
    }
}
