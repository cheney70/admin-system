<?php

namespace Database\Factories;

use Admin\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition()
    {
        return [
            'title' => $this->faker->words(2, true),
            'name' => $this->faker->unique()->slug(2),
            'parent_id' => 0,
            'path' => '/' . $this->faker->slug(2),
            'component' => $this->faker->slug(2) . '/index',
            'icon' => $this->faker->randomElement(['user', 'setting', 'menu', 'file-text']),
            'type' => $this->faker->numberBetween(1, 3),
            'sort' => $this->faker->numberBetween(1, 100),
            'status' => 1,
            'is_hidden' => false,
            'keep_alive' => true,
        ];
    }
}