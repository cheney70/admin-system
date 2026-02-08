<?php

namespace Database\Seeders;

use Admin\Models\User;
use Admin\Models\Role;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    public function run()
    {
        $adminUser = User::where('username', 'admin')->first();
        $editorUser = User::where('username', 'editor')->first();
        $normalUser = User::where('username', 'user')->first();

        $superAdminRole = Role::where('code', 'super_admin')->first();
        $adminRole = Role::where('code', 'admin')->first();
        $editorRole = Role::where('code', 'editor')->first();
        $userRole = Role::where('code', 'user')->first();

        $adminUser->roles()->attach($superAdminRole->id);
        $editorUser->roles()->attach($editorRole->id);
        $normalUser->roles()->attach($userRole->id);
    }
}