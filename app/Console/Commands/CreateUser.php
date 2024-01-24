<?php

namespace App\Console\Commands;

use App\Models\Entity;
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
        $role = $this->choice('What is the role', Role::query()->whereNot('name', 'Basic')->pluck('name')->toArray(), 0);

        $this->info('Creating user...');

        $entities = Entity::all();

        if ($entities->isEmpty()) {
            $this->info('Creating Super Entity...');
            $entity = Entity::query()->create([
                'name' => 'Super Entity',
                'parent_id' => 0,
                'status' => true
            ]);
        } else {
            $entity = Entity::query()->where('name', $this->choice('Please choose an entity', $entities->pluck('name')->toArray(), 0))->first();
        }

        $user = User::query()->firstOrCreate([
            'username' => $username
        ], [
            'password' => Hash::make($password),
            'entity_id' => $entity->getAttribute('id')
        ]);

        if (Role::findByName($role)) {
            $user->assignRole($role);
        } else {
            $this->error('Role not found!');
        }

        $this->info('User created successfully!');

    }
}
