<?php

namespace Database\Seeders;

use App\Models\Entity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class EntitySeeder extends Seeder
{
    public function run(): void
    {
        $created = Entity::all();

        Entity::factory()
            ->count(20)
            ->create()
            ->each(function (Entity $entity) use ($created) {
                $id = 0;
                if ($created->count() > 0) {
                    $id = $created->random()->id;
                }
                $entity->parent_id = $id;
                $entity->save();
                $created->add($entity);
            });
    }
}
