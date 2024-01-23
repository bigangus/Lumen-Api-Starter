<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateUser extends Command
{
    protected $signature = 'user:create';

    protected $description = 'Create user';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $username = $this->ask('What is the username', 'admin');
        $password = $this->ask('What is the password', 'admin');
        $role = $this->ask('What is the role', 'Super Admin');

        $this->info('Creating user...');

        $user = User::query()->firstOrCreate([
            'username' => $username
        ], [
            'password' => Hash::make($password)
        ]);

        if (Role::findByName($role)) {
            $user->assignRole($role);
        } else {
            $this->error('Role not found!');
        }

        $this->info('User created successfully!');

    }
}
