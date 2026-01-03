<?php

namespace Tests\Feature;

use App\Models\CyberSecurityService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CyberSecurityServiceSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Admin
        $this->admin = User::create([
            'username' => 'admin_cs',
            'email' => 'admin@cs.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active'
        ]);

        // Setup Unauthorized User
        $this->user = User::create([
            'username' => 'citizen',
            'email' => 'citizen@test.com',
            'password' => bcrypt('password'),
            'role' => 'user', 
            'status' => 'active'
        ]);
    }

    /**
     * Test Authorization.
     */
    public function test_unauthorized_user_cannot_access_index()
    {
        $response = $this->actingAs($this->user)->get('/cyber-security-services');
        $response->assertStatus(403);
    }

    /**
     * Test Store Sanitization (XSS).
     */
    public function test_it_sanitizes_summary_and_description()
    {
        $payload = [
            'name' => 'SOC 24/7',
            'category' => 'soc',
            'summary' => 'Safe <script>alert("xss")</script> Summary',
            'description' => '<p>Description</p><img src=x onerror=alert(1)>',
            'is_active' => true,
            'is_ai_visible' => false
        ];

        $response = $this->actingAs($this->admin)->postJson('/cyber-security-services/store', $payload);

        $response->assertStatus(201);
        
        $service = CyberSecurityService::first();
        
        // Summary: script tagged content is removed by sanitizeHtml
        $this->assertEquals('Safe  Summary', $service->summary);
        
        // Description: onerror is removed
        $this->assertStringContainsString('<p>Description</p>', $service->description);
        $this->assertStringNotContainsString('onerror', $service->description);
        $this->assertStringNotContainsString('alert(1)', $service->description);
    }

    /**
     * Test JSON Normalization.
     */
    public function test_it_normalizes_json_strings_to_arrays()
    {
        $payload = [
            'name' => 'Pentest',
            'category' => 'pentest',
            'is_active' => true,
            'is_ai_visible' => true,
            'service_scope' => ['Web', 'Mobile'],
            'deliverables' => ['Report', 'Cert']
        ];

        $response = $this->actingAs($this->admin)->postJson('/cyber-security-services/store', $payload);

        $response->assertStatus(201);
        
        $service = CyberSecurityService::first();
        $this->assertIsArray($service->service_scope);
        $this->assertCount(2, $service->service_scope);
        $this->assertEquals('Web', $service->service_scope[0]);
    }

    /**
     * Test Validation and Unique Name.
     */
    public function test_it_enforces_unique_service_names()
    {
        CyberSecurityService::create([
            'name' => 'Audit',
            'slug' => 'audit',
            'category' => 'audit'
        ]);

        $payload = [
            'name' => 'Audit',
            'category' => 'audit'
        ];

        $response = $this->actingAs($this->admin)->postJson('/cyber-security-services/store', $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * Test Delete Authorization.
     */
    public function test_unauthorized_user_cannot_delete_service()
    {
        $service = CyberSecurityService::create([
            'name' => 'Temp',
            'slug' => 'temp',
            'category' => 'consulting'
        ]);

        $response = $this->actingAs($this->user)->deleteJson("/cyber-security-services/{$service->id}/delete");
        $response->assertStatus(403);
    }
}
