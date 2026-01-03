<?php

namespace Tests\Feature;

use App\Models\ArticleCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleCategorySecurityTest extends TestCase
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
            'username' => 'admin_cat',
            'email' => 'admin@cat.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active'
        ]);

        // Editor with manage-article permission (simulated by role check)
        $this->editor = User::create([
            'username' => 'editor_cat',
            'email' => 'editor@cat.com',
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
    }

    /**
     * Test Authorization.
     */
    public function test_unauthorized_user_cannot_access_categories()
    {
        $response = $this->actingAs($this->user)->get('/articles/categories');
        $response->assertStatus(403);
    }

    /**
     * Test XSS Sanitization in Name.
     */
    public function test_it_sanitizes_category_name()
    {
        $payload = [
            'name' => 'News <script>alert("xss")</script>'
        ];

        $response = $this->actingAs($this->admin)->postJson('/articles/categories', $payload);

        $response->assertStatus(201);
        
        $category = ArticleCategory::first();
        $this->assertEquals('News ', $category->name); // Middleware removes the entire script block
        $this->assertEquals('news', $category->slug);
    }

    /**
     * Test Update Authorization and Unique validation.
     */
    public function test_it_enforces_unique_category_names_on_update()
    {
        ArticleCategory::create(['name' => 'Tech', 'slug' => 'tech']);
        $cat2 = ArticleCategory::create(['name' => 'Science', 'slug' => 'science']);

        $payload = [
            'name' => 'Tech'
        ];

        $response = $this->actingAs($this->admin)->putJson("/articles/categories/{$cat2->id}", $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * Test Delete Authorization.
     */
    public function test_unauthorized_user_cannot_delete_category()
    {
        $category = ArticleCategory::create(['name' => 'DeleteMe', 'slug' => 'deleteme']);

        $response = $this->actingAs($this->user)->deleteJson("/articles/categories/{$category->id}/delete");
        $response->assertStatus(403);
    }

    /**
     * Test Editor permission.
     */
    public function test_editor_can_create_category()
    {
        $payload = ['name' => 'Test Category'];
        $response = $this->actingAs($this->admin)->postJson('/articles/categories', $payload);
        $response->assertStatus(201);
    }
}
