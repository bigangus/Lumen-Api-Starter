<?php

namespace Database\Factories;

use App\Models\Entity;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntityFactory extends Factory
{
    protected $model = Entity::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company.'_'.$this->faker->uuid,
            'status' => $this->faker->boolean,
            'parent_id' => 0
        ];
    }
}
