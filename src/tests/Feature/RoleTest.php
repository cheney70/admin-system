<?php

namespace Tests\Feature;

use Cheney\AdminSystem\Models\Role;
use Cheney\AdminSystem\Models\Permission;
use Cheney\AdminSystem\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = auth('api')->login($this->user);
    }

    public function test_authenticated_user_can_get_role_list()
    {
        Role::factory()->count(5)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/roles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'total',
                'current_page',
                'per_page',
            ]);
    }

    public function test_authenticated_user_can_create_role()
    {
        $roleData = [
            'name' => '新角色',
            'code' => 'new_role',
            'description' => '这是一个新角色',
            'sort' => 1,
            'status' => 1,
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/roles', $roleData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => '创建成功',
            ]);

        $this->assertDatabaseHas('roles', [
            'name' => '新角色',
            'code' => 'new_role',
        ]);
    }

    public function test_authenticated_user_can_update_role()
    {
        $role = Role::factory()->create();
        $updateData = [
            'name' => '更新后的角色',
            'code' => $role->code,
            'description' => '更新后的描述',
            'sort' => 2,
            'status' => 1,
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/roles/' . $role->id, $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => '更新成功',
            ]);

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => '更新后的角色',
        ]);
    }

    public function test_authenticated_user_can_delete_role()
    {
        $role = Role::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/roles/' . $role->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => '删除成功',
            ]);

        $this->assertDatabaseMissing('roles', [
            'id' => $role->id,
        ]);
    }

    public function test_user_cannot_delete_role_with_users()
    {
        $role = Role::factory()->create();
        $user = User::factory()->create();
        $user->roles()->attach($role->id);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/roles/' . $role->id);

        $response->assertStatus(400)
            ->assertJson([
                'message' => '该角色下有用户，无法删除',
            ]);
    }

    public function test_authenticated_user_can_assign_permissions_to_role()
    {
        $role = Role::factory()->create();
        $permission1 = Permission::factory()->create();
        $permission2 = Permission::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/roles/' . $role->id . '/permissions', [
                'permission_ids' => [$permission1->id, $permission2->id],
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => '权限分配成功',
            ]);

        $this->assertDatabaseHas('permission_role', [
            'role_id' => $role->id,
            'permission_id' => $permission1->id,
        ]);

        $this->assertDatabaseHas('permission_role', [
            'role_id' => $role->id,
            'permission_id' => $permission2->id,
        ]);
    }

    public function test_role_list_can_be_filtered_by_name()
    {
        Role::factory()->create(['name' => '管理员']);
        Role::factory()->create(['name' => '超级管理员']);
        Role::factory()->create(['name' => '普通用户']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/roles?name=管理员');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(2, $data);
    }

    public function test_role_list_can_be_filtered_by_status()
    {
        Role::factory()->count(3)->create(['status' => 1]);
        Role::factory()->count(2)->create(['status' => 0]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/roles?status=1');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(3, $data);
    }

    public function test_role_code_must_be_unique()
    {
        Role::factory()->create(['code' => 'test_role']);

        $roleData = [
            'name' => '新角色',
            'code' => 'test_role',
            'description' => '描述',
            'status' => 1,
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/roles', $roleData);

        $response->assertStatus(422);
    }
}