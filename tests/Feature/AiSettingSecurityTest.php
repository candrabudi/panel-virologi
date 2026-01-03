<?php

namespace Tests\Feature;

use App\Models\AiSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiSettingSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Admin
        $this->admin = User::create([
            'username' => 'admin_ai',
            'email' => 'admin@ai.com',
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
    public function test_unauthorized_user_cannot_access_ai_settings_page()
    {
        $response = $this->actingAs($this->user)->get('/ai/settings');
        $response->assertStatus(403);
    }

    /**
     * Test Store Authorization.
     */
    public function test_unauthorized_user_cannot_update_ai_settings()
    {
        $payload = [
            'provider' => 'openai',
            'model' => 'gpt-4',
            'temperature' => 0.7,
            'max_tokens' => 1000,
            'timeout' => 30,
            'is_active' => true,
            'cybersecurity_only' => false
        ];

        $response = $this->actingAs($this->user)->postJson('/ai/settings', $payload);
        $response->assertStatus(403);
    }

    /**
     * Test Valid Store and API Key Protection.
     */
    public function test_it_updates_settings_and_preserves_api_key_if_null()
    {
        // 1. Initial setup with API Key
        AiSetting::create([
            'id' => 1,
            'provider' => 'openai',
            'model' => 'gpt-3.5',
            'api_key' => 'secret-key-123',
            'temperature' => 0.5,
            'max_tokens' => 500,
            'timeout' => 20,
            'is_active' => true,
            'cybersecurity_only' => true,
        ]);

        $payload = [
            'provider' => 'azure',
            'model' => 'gpt-4',
            'base_url' => 'https://api.openai.com/v1',
            'api_key' => '', // Leave empty to check preservation
            'temperature' => 0.8,
            'max_tokens' => 2000,
            'timeout' => 60,
            'is_active' => false,
            'cybersecurity_only' => false
        ];

        $response = $this->actingAs($this->admin)->postJson('/ai/settings', $payload);

        $response->assertStatus(200);
        
        $setting = AiSetting::first();
        $this->assertEquals('azure', $setting->provider);
        $this->assertEquals('gpt-4', $setting->model);
        $this->assertEquals('secret-key-123', $setting->api_key); // Preserved
        $this->assertFalse($setting->is_active);
    }

    /**
     * Test XSS Sanitization in URL and Model.
     */
    public function test_it_sanitizes_model_name()
    {
        $payload = [
            'provider' => 'openai',
            'model' => 'gpt-4 <script>alert(1)</script>',
            'temperature' => 0.7,
            'max_tokens' => 1000,
            'timeout' => 30,
            'is_active' => true,
            'cybersecurity_only' => false
        ];

        $response = $this->actingAs($this->admin)->postJson('/ai/settings', $payload);

        $response->assertStatus(200);
        
        $setting = AiSetting::first();
        $this->assertEquals('gpt-4 ', $setting->model); // script is removed entirely by sanitizeHtml
    }

    /**
     * Test URL Validation.
     */
    public function test_it_validates_base_url_format()
    {
        $payload = [
            'provider' => 'custom',
            'base_url' => 'not-a-url',
            'model' => 'local',
            'temperature' => 0.7,
            'max_tokens' => 1000,
            'timeout' => 30,
            'is_active' => '1',
            'cybersecurity_only' => '0'
        ];

        $response = $this->actingAs($this->admin)->postJson('/ai/settings', $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['base_url']);
    }
}
