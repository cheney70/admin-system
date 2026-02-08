<?php

namespace Database\Factories;

use Admin\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(2, true),
            'code' => $this->faker->unique()->slug(3),
            'description' => $this->faker->sentence,
            'menu_id' => null,
            'type' => $this->faker->numberBetween(1, 2),
        ];
    }
}