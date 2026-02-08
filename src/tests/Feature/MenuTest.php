<?php

namespace Tests\Feature;

use Cheney\AdminSystem\Models\Menu;
use Cheney\AdminSystem\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = auth('api')->login($this->user);
    }

    public function test_authenticated_user_can_get_menu_list()
    {
        Menu::factory()->count(5)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/menus');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_create_menu()
    {
        $menuData = [
            'title' => '新菜单',
            'name' => 'new_menu',
            'parent_id' => 0,
            'path' => '/new',
            'component' => 'new/index',
            'icon' => 'menu',
            'type' => 2,
            'sort' => 1,
            'status' => 1,
            'is_hidden' => false,
            'keep_alive' => false,
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/menus', $menuData);

        $response->assertStatus(201)
            ->assertJson([
                'message' => '创建成功',
            ]);

        $this->assertDatabaseHas('menus', [
            'title' => '新菜单',
            'name' => 'new_menu',
        ]);
    }

    public function test_authenticated_user_can_create_submenu()
    {
        $parentMenu = Menu::factory()->create();

        $menuData = [
            'title' => '子菜单',
            'name' => 'submenu',
            'parent_id' => $parentMenu->id,
            'path' => '/submenu',
            'component' => 'submenu/index',
            'type' => 2,
            'sort' => 1,
            'status' => 1,
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/menus', $menuData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('menus', [
            'title' => '子菜单',
            'parent_id' => $parentMenu->id,
        ]);
    }

    public function test_authenticated_user_can_update_menu()
    {
        $menu = Menu::factory()->create();
        $updateData = [
            'title' => '更新后的菜单',
            'name' => $menu->name,
            'parent_id' => $menu->parent_id,
            'path' => '/updated',
            'component' => 'updated/index',
            'type' => 2,
            'sort' => 2,
            'status' => 1,
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->putJson('/api/menus/' . $menu->id, $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => '更新成功',
            ]);

        $this->assertDatabaseHas('menus', [
            'id' => $menu->id,
            'title' => '更新后的菜单',
        ]);
    }

    public function test_authenticated_user_can_delete_menu()
    {
        $menu = Menu::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/menus/' . $menu->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => '删除成功',
            ]);

        $this->assertDatabaseMissing('menus', [
            'id' => $menu->id,
        ]);
    }

    public function test_user_cannot_delete_menu_with_children()
    {
        $parentMenu = Menu::factory()->create();
        Menu::factory()->create(['parent_id' => $parentMenu->id]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/menus/' . $parentMenu->id);

        $response->assertStatus(400)
            ->assertJson([
                'message' => '该菜单下有子菜单，无法删除',
            ]);
    }

    public function test_menu_list_can_be_filtered_by_title()
    {
        Menu::factory()->create(['title' => '用户管理']);
        Menu::factory()->create(['title' => '角色管理']);
        Menu::factory()->create(['title' => '菜单管理']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/menus?title=管理');

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(3, $data);
    }

    public function test_menu_list_can_be_filtered_by_status()
    {
        Menu::factory()->count(3)->create(['status' => 1]);
        Menu::factory()->count(2)->create(['status' => 0]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/menus?status=1');

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertCount(3, $data);
    }

    public function test_menu_name_must_be_unique()
    {
        Menu::factory()->create(['name' => 'test_menu']);

        $menuData = [
            'title' => '新菜单',
            'name' => 'test_menu',
            'type' => 2,
            'status' => 1,
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/menus', $menuData);

        $response->assertStatus(422);
    }

    public function test_menu_type_must_be_valid()
    {
        $menuData = [
            'title' => '新菜单',
            'name' => 'new_menu',
            'type' => 4,
            'status' => 1,
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->postJson('/api/menus', $menuData);

        $response->assertStatus(422);
    }

    public function test_authenticated_user_can_get_user_menus()
    {
        $menu1 = Menu::factory()->create(['status' => 1, 'is_hidden' => false]);
        $menu2 = Menu::factory()->create(['status' => 1, 'is_hidden' => false]);
        Menu::factory()->create(['status' => 0]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/user-menus');

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertGreaterThanOrEqual(2, count($data));
    }
}