<?php

namespace Tests;

use App\Models\Entity;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthTest extends TestCase
{
    protected string $username;
    protected string $password;
    protected ?User $user = null;
    protected ?Entity $entity = null;

    public function test_login_with_valid_credentials()
    {
        $this->json('POST', '/api/auth/login', ['username' => $this->username, 'password' => $this->password]);

        $this->seeJson([
            'code' => 200,
            'status' => true
        ]);

        $response = $this->response->json();

        return $response['data']['token'];
    }

    public function test_login_with_invalid_credentials()
    {
        $this->json('POST', '/api/auth/login', ['username' => $this->username, 'password' => 'invalid']);

        $this->seeJson([
            'code' => 401,
            'status' => false
        ]);
    }

    public function test_login_with_invalid_username()
    {
        $this->json('POST', '/api/auth/login', ['username' => 'invalid', 'password' => $this->password]);

        $this->seeJson([
            'code' => 401,
            'status' => false
        ]);
    }

    public function test_login_with_invalid_password()
    {
        $this->json('POST', '/api/auth/login', ['username' => $this->username, 'password' => 'invalid']);

        $this->seeJson([
            'code' => 401,
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
    }


    protected function setUp(): void
    {
        parent::setUp();

        $this->username = Str::random(10);
        $this->password = Str::random(10);

        $this->entity = Entity::query()->firstOrCreate([
            'name' => 'Test Entity'
        ]);

        $this->user = User::query()->create([
            'username' => $this->username,
            'password' => Hash::make($this->password),
            'entity_id' => $this->entity->id
        ]);
    }

    protected function tearDown(): void
    {
        $this->user?->delete();

        $this->entity?->delete();
        parent::tearDown();
    }
}
