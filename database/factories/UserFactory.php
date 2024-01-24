<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'username' => $this->faker->unique()->userName.'_'.$this->faker->uuid,
            'password' => Hash::make('123456'),
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'status' => $this->faker->boolean
        ];
    }
}
