<?php

namespace Database\Seeders;

use App\Models\Entity;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $entities = Entity::all();

        User::factory()
            ->count(20)
            ->make()
            ->each(function (User $user) use ($entities) {
                $user->entity_id = $entities->random()->id;
                $user->save();
        });
    }
}
