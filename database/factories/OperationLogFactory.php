<?php

namespace Database\Factories;

use Admin\Models\OperationLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class OperationLogFactory extends Factory
{
    protected $model = OperationLog::class;

    public function definition()
    {
        return [
            'user_id' => 1,
            'username' => $this->faker->userName,
            'module' => $this->faker->randomElement(['用户管理', '角色管理', '权限管理', '菜单管理']),
            'action' => $this->faker->randomElement(['创建', '更新', '删除', '查看']),
            'method' => $this->faker->randomElement(['GET', 'POST', 'PUT', 'DELETE']),
            'url' => $this->faker->url,
            'ip' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'params' => [],
            'status' => $this->faker->randomElement([0, 1]),
            'error_message' => $this->faker->optional()->sentence,
        ];
    }
}