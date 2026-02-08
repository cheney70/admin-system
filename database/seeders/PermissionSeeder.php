<?php

namespace Database\Seeders;

use Admin\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        Permission::create([
            'name' => '用户列表',
            'code' => 'user:list',
            'description' => '查看用户列表',
            'menu_id' => 2,
            'type' => 1,
        ]);

        Permission::create([
            'name' => '创建用户',
            'code' => 'user:create',
            'description' => '创建用户',
            'menu_id' => 2,
            'type' => 2,
        ]);

        Permission::create([
            'name' => '编辑用户',
            'code' => 'user:update',
            'description' => '编辑用户',
            'menu_id' => 2,
            'type' => 2,
        ]);

        Permission::create([
            'name' => '删除用户',
            'code' => 'user:delete',
            'description' => '删除用户',
            'menu_id' => 2,
            'type' => 2,
        ]);

        Permission::create([
            'name' => '角色列表',
            'code' => 'role:list',
            'description' => '查看角色列表',
            'menu_id' => 3,
            'type' => 1,
        ]);

        Permission::create([
            'name' => '创建角色',
            'code' => 'role:create',
            'description' => '创建角色',
            'menu_id' => 3,
            'type' => 2,
        ]);

        Permission::create([
            'name' => '编辑角色',
            'code' => 'role:update',
            'description' => '编辑角色',
            'menu_id' => 3,
            'type' => 2,
        ]);

        Permission::create([
            'name' => '删除角色',
            'code' => 'role:delete',
            'description' => '删除角色',
            'menu_id' => 3,
            'type' => 2,
        ]);

        Permission::create([
            'name' => '权限列表',
            'code' => 'permission:list',
            'description' => '查看权限列表',
            'menu_id' => 4,
            'type' => 1,
        ]);

        Permission::create([
            'name' => '创建权限',
            'code' => 'permission:create',
            'description' => '创建权限',
            'menu_id' => 4,
            'type' => 2,
        ]);

        Permission::create([
            'name' => '编辑权限',
            'code' => 'permission:update',
            'description' => '编辑权限',
            'menu_id' => 4,
            'type' => 2,
        ]);

        Permission::create([
            'name' => '删除权限',
            'code' => 'permission:delete',
            'description' => '删除权限',
            'menu_id' => 4,
            'type' => 2,
        ]);

        Permission::create([
            'name' => '菜单列表',
            'code' => 'menu:list',
            'description' => '查看菜单列表',
            'menu_id' => 5,
            'type' => 1,
        ]);

        Permission::create([
            'name' => '创建菜单',
            'code' => 'menu:create',
            'description' => '创建菜单',
            'menu_id' => 5,
            'type' => 2,
        ]);

        Permission::create([
            'name' => '编辑菜单',
            'code' => 'menu:update',
            'description' => '编辑菜单',
            'menu_id' => 5,
            'type' => 2,
        ]);

        Permission::create([
            'name' => '删除菜单',
            'code' => 'menu:delete',
            'description' => '删除菜单',
            'menu_id' => 5,
            'type' => 2,
        ]);

        Permission::create([
            'name' => '日志列表',
            'code' => 'log:list',
            'description' => '查看日志列表',
            'menu_id' => 6,
            'type' => 1,
        ]);

        Permission::create([
            'name' => '删除日志',
            'code' => 'log:delete',
            'description' => '删除日志',
            'menu_id' => 6,
            'type' => 2,
        ]);
    }
}