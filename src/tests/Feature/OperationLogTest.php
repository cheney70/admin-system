<?php

namespace Tests\Feature;

use Cheney\AdminSystem\Models\OperationLog;
use Cheney\AdminSystem\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OperationLogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = auth('api')->login($this->user);
    }

    public function test_authenticated_user_can_get_log_list()
    {
        OperationLog::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'username' => $this->user->username,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/operation-logs');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'total',
                'current_page',
                'per_page',
            ]);
    }

    public function test_authenticated_user_can_get_log_statistics()
    {
        OperationLog::factory()->count(3)->create(['status' => 1]);
        OperationLog::factory()->count(2)->create(['status' => 0]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/operation-logs/statistics');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'total',
                'success',
                'failed',
                'module_stats',
                'action_stats',
            ]);

        $data = $response->json();
        $this->assertEquals(5, $data['total']);
        $this->assertEquals(3, $data['success']);
        $this->assertEquals(2, $data['failed']);
    }

    public function test_authenticated_user_can_delete_log()
    {
        $log = OperationLog::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/operation-logs/' . $log->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => '删除成功',
            ]);

        $this->assertDatabaseMissing('operation_logs', [
            'id' => $log->id,
        ]);
    }

    public function test_authenticated_user_can_clear_old_logs()
    {
        OperationLog::factory()->create([
            'created_at' => now()->subDays(40),
        ]);

        OperationLog::factory()->create([
            'created_at' => now()->subDays(20),
        ]);

        OperationLog::factory()->create([
            'created_at' => now()->subDays(10),
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->deleteJson('/api/operation-logs/clear', [
                'days' => 30,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => '清理成功',
            ]);

        $this->assertDatabaseMissing('operation_logs', [
            'created_at' => now()->subDays(40)->toDateTimeString(),
        ]);

        $this->assertDatabaseHas('operation_logs', [
            'created_at' => now()->subDays(20)->toDateTimeString(),
        ]);
    }

    public function test_log_list_can_be_filtered_by_username()
    {
        OperationLog::factory()->create(['username' => 'admin']);
        OperationLog::factory()->create(['username' => 'admin']);
        OperationLog::factory()->create(['username' => 'user']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/operation-logs?username=admin');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(2, $data);
    }

    public function test_log_list_can_be_filtered_by_module()
    {
        OperationLog::factory()->create(['module' => 'User']);
        OperationLog::factory()->create(['module' => 'User']);
        OperationLog::factory()->create(['module' => 'Role']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/operation-logs?module=User');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(2, $data);
    }

    public function test_log_list_can_be_filtered_by_action()
    {
        OperationLog::factory()->create(['action' => '列表']);
        OperationLog::factory()->create(['action' => '列表']);
        OperationLog::factory()->create(['action' => '创建']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/operation-logs?action=列表');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(2, $data);
    }

    public function test_log_list_can_be_filtered_by_status()
    {
        OperationLog::factory()->count(3)->create(['status' => 1]);
        OperationLog::factory()->count(2)->create(['status' => 0]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/operation-logs?status=1');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(3, $data);
    }

    public function test_log_list_can_be_filtered_by_date_range()
    {
        OperationLog::factory()->create([
            'created_at' => now()->subDays(5),
        ]);

        OperationLog::factory()->create([
            'created_at' => now()->subDays(3),
        ]);

        OperationLog::factory()->create([
            'created_at' => now()->subDays(1),
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
            ->getJson('/api/operation-logs?start_date=' . now()->subDays(4)->toDateString() . '&end_date=' . now()->toDateString());

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(2, $data);
    }

    public function test_unauthenticated_user_cannot_access_log_routes()
    {
        $response = $this->getJson('/api/operation-logs');

        $response->assertStatus(401);
    }
}