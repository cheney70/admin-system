<?php

namespace Database\Factories;

use Admin\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'name' => $this->faker->jobTitle,
            'code' => $this->faker->unique()->slug(2),
            'description' => $this->faker->sentence,
            'sort' => $this->faker->numberBetween(1, 100),
            'status' => 1,
        ];
    }
}