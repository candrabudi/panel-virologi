<?php

namespace Tests\Feature;

use App\Models\AboutUs;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutUsSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        // Create an admin user for testing
        $this->admin = User::create([
            'username' => 'admintest',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);
    }

    /**
     * Test XSS Sanitization for left_content and right_content.
     */
    public function test_it_sanitizes_xss_payloads()
    {
        $payload = [
            'page_title' => 'About Us Test',
            'headline' => 'Secure Headline',
            'left_content' => '<p>Normal text</p><script>alert("xss")</script><b onmouseover="alert(1)">Hover me</b>',
            'right_content' => '<img src=x onerror=alert(1)> <a href="javascript:alert(1)">Click</a>',
            'is_active' => '1'
        ];

        $response = $this->actingAs($this->admin)
            ->postJson('/api/about-us', $payload);

        $response->assertStatus(200);

        $about = AboutUs::first();

        // Check left_content
        $this->assertStringNotContainsString('<script>', $about->left_content);
        $this->assertStringNotContainsString('onmouseover', $about->left_content);
        
        // Check right_content
        $this->assertStringNotContainsString('onerror', $about->right_content);
        $this->assertStringNotContainsString('javascript:', $about->right_content);
    }

    /**
     * Test character limit (10,000 chars).
     */
    public function test_it_enforces_character_limits()
    {
        $longString = str_repeat('a', 10001);

        $payload = [
            'page_title' => 'Limit Test',
            'left_content' => $longString,
            'is_active' => '1'
        ];

        $response = $this->actingAs($this->admin)
            ->postJson('/api/about-us', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['left_content']);
    }

    /**
     * Test Security Headers presence.
     */
    public function test_it_returns_security_headers()
    {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
    }

    /**
     * Test Unauthorized access.
     */
    public function test_unauthorized_user_cannot_update_about_us()
    {
        $user = User::create([
            'username' => 'regularuser',
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
            'role' => 'user' // Not admin
        ]);

        $payload = [
            'page_title' => 'Hacked Title',
            'is_active' => '1'
        ];

        $response = $this->actingAs($user)
            ->postJson('/api/about-us', $payload);

        $response->assertStatus(403);
    }
}
