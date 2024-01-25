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
        $this->json('POST', '/api/auth/login', []);

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
        $this->json('POST', '/api/auth/login', []);

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
