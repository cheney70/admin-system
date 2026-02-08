<?php

namespace Database\Seeders;

use Admin\Models\Role;
use Admin\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    public function run()
    {
        $superAdminRole = Role::where('code', 'super_admin')->first();
        $adminRole = Role::where('code', 'admin')->first();
        $editorRole = Role::where('code', 'editor')->first();
        $userRole = Role::where('code', 'user')->first();

        $allPermissions = Permission::all()->pluck('id')->toArray();

        $superAdminRole->permissions()->sync($allPermissions);

        $adminPermissions = Permission::whereIn('code', [
            'user:list', 'user:create', 'user:update',
            'role:list', 'role:create', 'role:update',
            'permission:list', 'menu:list',
            'log:list',
        ])->pluck('id')->toArray();
        $adminRole->permissions()->sync($adminPermissions);

        $editorPermissions = Permission::whereIn('code', [
            'user:list', 'user:update',
            'role:list',
            'permission:list', 'menu:list',
        ])->pluck('id')->toArray();
        $editorRole->permissions()->sync($editorPermissions);

        $userPermissions = Permission::whereIn('code', [
            'user:list',
            'role:list',
            'permission:list', 'menu:list',
        ])->pluck('id')->toArray();
        $userRole->permissions()->sync($userPermissions);
    }
}