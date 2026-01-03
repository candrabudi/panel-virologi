<?php

namespace Tests\Feature;

use App\Models\ArticleTag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ArticleTagSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $editor;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup Admin
        $this->admin = User::create([
            'username' => 'admin_tag',
            'email' => 'admin@tag.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active'
        ]);

        // Setup Unauthorized User (e.g., standard user if roles allow)
        $this->user = User::create([
            'username' => 'citizen',
            'email' => 'citizen@test.com',
            'password' => bcrypt('password'),
            'role' => 'user', // Assuming 'user' cannot manage articles
            'status' => 'active'
        ]);
    }

    /**
     * Test XSS Sanitization in Tag Name.
     */
    public function test_it_sanitizes_tag_name()
    {
        $payload = [
            'name' => 'Secure Tag <script>alert(1)</script>'
        ];

        $response = $this->actingAs($this->admin)
            ->postJson('/articles/tags', $payload);

        $response->assertStatus(201);

        $tag = ArticleTag::first();

        // Tag name should be cleaned from HTML
        $this->assertEquals('Secure Tag', $tag->name);
        $this->assertStringNotContainsString('<script>', $tag->name);
    }

    /**
     * Test Validation and Unique Constraint.
     */
    public function test_it_enforces_unique_tag_names()
    {
        ArticleTag::create(['name' => 'Laravel', 'slug' => 'laravel']);

        $payload = [
            'name' => 'Laravel'
        ];

        $response = $this->actingAs($this->admin)
            ->postJson('/articles/tags', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * Test Authorization.
     */
    public function test_unauthorized_user_cannot_create_tag()
    {
        $payload = ['name' => 'New Tag'];

        $response = $this->actingAs($this->user)
            ->postJson('/articles/tags', $payload);

        $response->assertStatus(403);
    }

    /**
     * Test Update Sanitization and Validation.
     */
    public function test_it_sanitizes_on_update()
    {
        $tag = ArticleTag::create(['name' => 'Old Name', 'slug' => 'old-name']);

        $payload = [
            'name' => 'Updated Name <b>Bold</b>'
        ];

        $response = $this->actingAs($this->admin)
            ->putJson("/articles/tags/{$tag->id}", $payload);

        $response->assertStatus(200);

        $tag->refresh();
        $this->assertEquals('Updated Name Bold', $tag->name);
    }

    /**
     * Test Non-existent Tag Update.
     */
    public function test_it_returns_404_for_missing_tag()
    {
        $response = $this->actingAs($this->admin)
            ->putJson("/articles/tags/999", ['name' => 'Missing']);

        $response->assertStatus(404);
    }
}
