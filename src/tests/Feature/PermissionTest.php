<?php

namespace Tests\Feature;

use Cheney\AdminSystem\Models\Permission;
use Cheney\AdminSystem\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = auth('api')->login($this->user);
    }

    public function test_authenticated_user_can_get_permission_list()
    {
        Permission::factory()->count(5)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/permissions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'total',
                'current_page',
                'per_page',
            ]);
    }

    public function test_authenticated_user_can_create_permission()
    {
        $permissionData = [
            'name' => '新权限',
            'code' => 'new_permission',
            'description' => '这是一个新权限',
            'type' => 2,
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/permissions', $permissionData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => '创建成功',
            ]);

        $this->assertDatabaseHas('permissions', [
            'name' => '新权限',
            'code' => 'new_permission',
        ]);
    }

    public function test_authenticated_user_can_update_permission()
    {
        $permission = Permission::factory()->create();
        $updateData = [
            'name' => '更新后的权限',
            'code' => $permission->code,
            'description' => '更新后的描述',
            'type' => 2,
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/permissions/' . $permission->id, $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => '更新成功',
            ]);

        $this->assertDatabaseHas('permissions', [
            'id' => $permission->id,
            'name' => '更新后的权限',
        ]);
    }

    public function test_authenticated_user_can_delete_permission()
    {
        $permission = Permission::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/permissions/' . $permission->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => '删除成功',
            ]);

        $this->assertDatabaseMissing('permissions', [
            'id' => $permission->id,
        ]);
    }

    public function test_user_cannot_delete_permission_assigned_to_role()
    {
        $permission = Permission::factory()->create();
        $role = \App\Models\Role::factory()->create();
        $role->permissions()->attach($permission->id);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/permissions/' . $permission->id);

        $response->assertStatus(400)
            ->assertJson([
                'message' => '该权限已被角色使用，无法删除',
            ]);
    }

    public function test_permission_list_can_be_filtered_by_name()
    {
        Permission::factory()->create(['name' => '查看用户']);
        Permission::factory()->create(['name' => '编辑用户']);
        Permission::factory()->create(['name' => '删除用户']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/permissions?name=用户');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(3, $data);
    }

    public function test_permission_list_can_be_filtered_by_type()
    {
        Permission::factory()->count(3)->create(['type' => 1]);
        Permission::factory()->count(2)->create(['type' => 2]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/permissions?type=1');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(3, $data);
    }

    public function test_permission_code_must_be_unique()
    {
        Permission::factory()->create(['code' => 'test_permission']);

        $permissionData = [
            'name' => '新权限',
            'code' => 'test_permission',
            'description' => '描述',
            'type' => 2,
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/permissions', $permissionData);

        $response->assertStatus(422);
    }

    public function test_permission_type_must_be_valid()
    {
        $permissionData = [
            'name' => '新权限',
            'code' => 'new_permission',
            'description' => '描述',
            'type' => 3,
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/permissions', $permissionData);

        $response->assertStatus(422);
    }
}