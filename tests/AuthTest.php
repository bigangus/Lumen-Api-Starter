<?php

namespace Tests;

use App\Models\Entity;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthTest extends TestCase
{
    protected static bool $initialized = FALSE;
    protected static bool $testDone = false;

    protected static string $username;
    protected static string $password;
    protected static ?User $user = null;
    protected static ?Entity $entity = null;

    public function setUp(): void
    {
        parent::setUp();

        if (!self::$initialized) {
            self::$username = Str::random(10);
            self::$password = Str::random(10);

            self::$entity = Entity::query()->firstOrCreate([
                'name' => 'Test Entity'
            ]);

            self::$user = User::query()->create([
                'username' => self::$username,
                'password' => Hash::make(self::$password),
                'email' => self::$username . '@example.com',
                'phone' => '0123456789',
                'entity_id' => self::$entity->id
            ]);
            self::$initialized = TRUE;
        }
    }

    public function tearDown(): void
    {
        if (self::$testDone) {
            self::$user?->delete();
            self::$entity?->delete();
        }
        parent::tearDown();
    }

    public function test_login_with_valid_credentials()
    {
        $this->json('POST', '/api/auth/login', ['username' => self::$username, 'password' => self::$password]);

        $this->seeJson([
            'code' => 200,
            'status' => true
        ]);

        $response = $this->response->json();

        return $response['data']['token'];
    }

    public function test_login_with_invalid_credentials()
    {
        $this->json('POST', '/api/auth/login', ['username' => self::$username, 'password' => 'invalid']);

        $this->seeJson([
            'code' => 401,
            'status' => false
        ]);
    }

    public function test_login_with_invalid_username()
    {
        $this->json('POST', '/api/auth/login', ['username' => 'invalid', 'password' => self::$password]);

        $this->seeJson([
            'code' => 401,
            'status' => false
        ]);
    }

    public function test_login_with_invalid_password()
    {
        $this->json('POST', '/api/auth/login', ['username' => self::$username, 'password' => 'invalid']);

        $this->seeJson([
            'code' => 401,
            'status' => false
        ]);
    }

    public function test_login_with_empty_username()
    {
        $this->json('POST', '/api/auth/login', ['username' => '', 'password' => self::$password]);

        $this->seeJson([
            'code' => 422,
            'status' => false
        ]);
    }

    public function test_login_with_empty_password()
    {
        $this->json('POST', '/api/auth/login', ['username' => self::$username, 'password' => '']);

        $this->seeJson([
            'code' => 422,
            'status' => false
        ]);
    }

    public function test_login_with_empty_credentials()
    {
        $this->json('POST', '/api/auth/login', ['username' => '', 'password' => '']);

        $this->seeJson([
            'code' => 422,
            'status' => false
        ]);
    }

    public function test_login_with_missing_credentials()
    {
        $this->json('POST', '/api/auth/login');

        $this->seeJson([
            'code' => 422,
            'status' => false
        ]);
    }

    public function test_login_with_missing_username()
    {
        $this->json('POST', '/api/auth/login', ['password' => self::$password]);

        $this->seeJson([
            'code' => 422,
            'status' => false
        ]);
    }

    public function test_login_with_missing_password()
    {
        $this->json('POST', '/api/auth/login', ['username' => self::$username]);

        $this->seeJson([
            'code' => 422,
            'status' => false
        ]);
    }

    public function test_login_with_missing_username_and_password()
    {
        $this->json('POST', '/api/auth/login');

        $this->seeJson([
            'code' => 422,
            'status' => false
        ]);
    }

    public function test_login_with_missing_username_and_invalid_password()
    {
        $this->json('POST', '/api/auth/login', ['password' => 'invalid']);

        $this->seeJson([
            'code' => 422,
            'status' => false
        ]);
    }

    public function test_login_with_missing_password_and_invalid_username()
    {
        $this->json('POST', '/api/auth/login', ['username' => 'invalid']);

        $this->seeJson([
            'code' => 422,
            'status' => false
        ]);
    }

    public function test_login_with_missing_username_and_empty_password()
    {
        $this->json('POST', '/api/auth/login', ['password' => '']);

        $this->seeJson([
            'code' => 422,
            'status' => false
        ]);
    }

    public function test_login_with_missing_password_and_empty_username()
    {
        $this->json('POST', '/api/auth/login', ['username' => '']);

        $this->seeJson([
            'code' => 422,
            'status' => false
        ]);
    }

    public function test_login_with_email()
    {
        $this->json('POST', '/api/auth/login', ['username' => self::$user->email, 'password' => self::$password]);

        $this->seeJson([
            'code' => 200,
            'status' => true
        ]);
    }

    public function test_login_with_phone()
    {
        $this->json('POST', '/api/auth/login', ['username' => self::$user->phone, 'password' => self::$password]);

        $this->seeJson([
            'code' => 200,
            'status' => true
        ]);
    }

    public function test_login_with_remember()
    {
        $this->json('POST', '/api/auth/login', ['username' => self::$username, 'password' => self::$password, 'remember' => true]);

        $this->seeJson([
            'code' => 200,
            'status' => true
        ]);
    }

    public function test_login_with_invalid_remember()
    {
        $this->json('POST', '/api/auth/login', ['username' => self::$username, 'password' => self::$password, 'remember' => 'invalid']);

        $this->seeJson([
            'code' => 422,
            'status' => false
        ]);
    }

    public function test_login_with_number_username_and_empty_phone()
    {
        self::$user->username = self::$user->phone;
        self::$user->phone = null;
        self::$user->save();

        $this->json('POST', '/api/auth/login', ['username' => self::$user->username, 'password' => self::$password]);

        $this->seeJson([
            'code' => 200,
            'status' => true
        ]);
    }

    public function test_login_with_email_username_and_empty_email()
    {
        self::$user->username = self::$user->email;
        self::$user->email = null;
        self::$user->save();

        $this->json('POST', '/api/auth/login', ['username' => self::$user->username, 'password' => self::$password]);

        $this->seeJson([
            'code' => 200,
            'status' => true
        ]);
    }

    public function test_send_verification_code()
    {
        self::$user->phone = '0123456789';
        self::$user->save();

        $this->json('POST', '/api/auth/send-sms-code', ['phone' => self::$user->phone]);

        $this->seeJson([
            'code' => 200,
            'status' => true
        ]);

        $response = $this->response->json();

        return $response['data']['sms_code'];
    }

    /**
     * @depends test_send_verification_code
     */
    public function test_forgot_password_works($code)
    {
        $newPassword = Str::random(10);

        $this->json('POST', '/api/auth/forgot-password', [
            'phone' => self::$user->phone,
            'code' => $code,
            'password' => $newPassword
        ]);

        $this->seeJson([
            'code' => 200,
            'status' => true
        ]);

        return $newPassword;
    }

    /**
     * @depends test_forgot_password_works
     */
    public function test_login_with_new_password($password)
    {
        $this->json('POST', '/api/auth/login', ['username' => self::$username, 'password' => $password]);

        $this->seeJson([
            'code' => 200,
            'status' => true
        ]);
    }

    /**
     * @depends test_login_with_valid_credentials
     */
    public function test_logout($token)
    {
        $this->json('POST', '/api/auth/logout', ['token' => $token]);

        $this->seeJson([
            'code' => 200,
            'status' => true
        ]);

        self::$testDone = true;
    }
}
