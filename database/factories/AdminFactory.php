<?php

namespace Database\Factories;

use Cheney\AdminSystem\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AdminFactory extends Factory
{
    protected $model = Admin::class;

    public function definition()
    {
        return [
            'username' => $this->faker->unique()->userName,
            'password' => bcrypt('password'),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'avatar' => $this->faker->imageUrl(),
            'status' => 1,
            'last_login_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'last_login_ip' => $this->faker->ipv4,
        ];
    }
}
