<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $editor;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Admin
        $this->admin = User::create([
            'username' => 'admin_dash',
            'email' => 'admin@dash.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active'
        ]);

        // Editor
        $this->editor = User::create([
            'username' => 'editor_dash',
            'email' => 'editor@dash.com',
            'password' => bcrypt('password'),
            'role' => 'editor',
            'status' => 'active'
        ]);

        // Unauthorized User
        $this->user = User::create([
            'username' => 'citizen',
            'email' => 'citizen@test.com',
            'password' => bcrypt('password'),
            'role' => 'user', 
            'status' => 'active'
        ]);

        // Seed some dummy usage logs
        DB::table('ai_usage_logs')->insert([
            [
                'ip_address'   => '1.1.1.1',
                'model'        => 'gpt-4',
                'total_tokens' => 100,
                'is_blocked'   => false,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'ip_address'   => '2.2.2.2',
                'model'        => 'gpt-4',
                'total_tokens' => 50,
                'is_blocked'   => true,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]
        ]);
    }

    /**
     * Test Unauthorized access.
     */
    public function test_unauthorized_user_cannot_access_summary()
    {
        $response = $this->actingAs($this->user)->getJson('/dashboard/summary');
        $response->assertStatus(403);
    }

    /**
     * Test Admin access.
     */
    public function test_admin_can_access_summary()
    {
        $response = $this->actingAs($this->admin)->getJson('/dashboard/summary');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'total_units',
                'total_requests',
                'active_ips',
                'success_rate'
            ]
        ]);
    }

    /**
     * Test Editor access.
     */
    public function test_editor_can_access_analytics()
    {
        $response = $this->actingAs($this->editor)->getJson('/dashboard/summary');
        $response->assertStatus(200);
    }

    /**
     * Test Growth Calculation Logic.
     */
    public function test_it_calculates_analytics_summary_correctly()
    {
        $response = $this->actingAs($this->admin)->getJson('/dashboard/summary');
        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertEquals(2, $data['total_requests']);
        $this->assertEquals(50.0, $data['success_rate']); // 1 success out of 2
    }

    /**
     * Test AI Traffic Daily endpoint.
     */
    public function test_it_returns_daily_traffic_data()
    {
        $response = $this->actingAs($this->admin)->getJson('/dashboard/ai-traffic-daily');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'labels',
                'series' => ['tokens', 'requests']
            ]
        ]);
    }
}
