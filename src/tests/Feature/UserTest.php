<?php

namespace Tests\Feature;

use Cheney\AdminSystem\Models\User;
use Cheney\AdminSystem\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password123'),
            'status' => 1,
        ]);
        $this->token = auth('api')->attempt([
            'username' => 'testuser',
            'password' => 'password123',
        ]);
    }

    public function test_authenticated_user_can_get_users_list()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 10000,
            ]);
    }

    public function test_authenticated_user_can_create_user()
    {
        $userData = [
            'username' => 'newuser',
            'password' => 'password123',
            'name' => '新用户',
            'email' => 'newuser@example.com',
            'phone' => '13800138000',
            'status' => 1,
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/users', $userData);

        $response->assertStatus(200)
            ->assertJson([
                'code' => 10000,
            ]);

        $this->assertDatabaseHas('users', [
            'username' => 'newuser',
            'email' => 'newuser@example.com',
        ]);
    }

    public function test_authenticated_user_can_update_user()
    {
        $user = User::factory()->create([
            'username' => 'updateme',
            'password' => bcrypt('password123'),
            'status' => 1,
        ]);

        $updateData = [
            'username' => 'updateme',
            'name' => '更新后的用户',
            'email' => 'updated@example.com',
            'status' => 1,
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/users/' . $user->id, $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'code' => 10000,
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => '更新后的用户',
        ]);
    }

    public function test_authenticated_user_can_delete_user()
    {
        $user = User::factory()->create([
            'username' => 'deleteme',
            'password' => bcrypt('password123'),
            'status' => 1,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/users/' . $user->id);

        $response->assertStatus(200)
            ->assertJson([
                'code' => 10000,
            ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_user_cannot_delete_themselves()
    {
        $currentUser = auth('api')->user();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/users/' . $currentUser->id);

        $response->assertStatus(200)
            ->assertJson([
                'code' => 20000,
            ]);
    }

    public function test_authenticated_user_can_assign_roles_to_user()
    {
        $user = User::factory()->create([
            'username' => 'roleuser',
            'password' => bcrypt('password123'),
            'status' => 1,
        ]);

        $role = Role::factory()->create([
            'name' => '测试角色',
            'code' => 'test_role',
            'status' => 1,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/users/' . $user->id . '/roles', [
                'role_ids' => [$role->id],
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'code' => 10000,
            ]);
    }

    public function test_authenticated_user_can_reset_user_password()
    {
        $user = User::factory()->create([
            'username' => 'passworduser',
            'password' => bcrypt('oldpassword'),
            'status' => 1,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/users/' . $user->id . '/reset-password', [
                'password' => 'newpassword123',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'code' => 10000,
            ]);
    }

    public function test_unauthenticated_user_cannot_access_users()
    {
        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 401,
            ]);
    }

    public function test_user_list_can_be_filtered_by_username()
    {
        User::factory()->create([
            'username' => 'searchuser',
            'status' => 1,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/users?username=search');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 10000,
            ]);
    }

    public function test_user_list_can_be_filtered_by_status()
    {
        User::factory()->create([
            'username' => 'disableduser',
            'status' => 0,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/users?status=1');

        $response->assertStatus(200)
            ->assertJson([
                'code' => 10000,
            ]);
    }
}