<?php

namespace Tests\Feature;

use App\Models\ProductPageSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPageSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'admin_page',
            'email' => 'admin@page.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'status' => 'active'
        ]);

        $this->user = User::create([
            'username' => 'regular_user',
            'email' => 'user@page.com',
            'password' => bcrypt('password123'),
            'role' => 'user', 
            'status' => 'active'
        ]);
    }

    /**
     * Test Authorization.
     */
    public function test_unauthorized_user_cannot_access_product_page_management()
    {
        $response = $this->actingAs($this->user)->get('/product-page');
        $response->assertStatus(403);
    }

    /**
     * Test XSS Sanitization in all text fields.
     */
    public function test_it_sanitizes_product_page_data()
    {
        $payload = [
            'page_title'       => 'Products <script>alert(1)</script>',
            'page_subtitle'    => 'Solutions <iframe src="javascript:alert(1)"></iframe>',
            'cta_text'         => 'Buy Now <object data="data:text/html,..."></object>',
            'cta_url'          => 'https://safe.com',
            'seo_title'        => 'SEO <embed src="bad.swf">',
            'seo_description'  => 'Desc',
            'seo_keywords'     => 'key',
            'is_active'        => true
        ];

        $response = $this->actingAs($this->admin)->postJson('/product-page', $payload);

        $response->assertStatus(200);
        
        $page = ProductPageSetting::first();
        $this->assertNotNull($page);
        
        // SecurityHelper::cleanString (via global middleware) removes the entire script/iframe/object block
        $this->assertEquals('Products ', $page->page_title);
        $this->assertEquals('Solutions ', $page->page_subtitle);
        $this->assertEquals('Buy Now ', $page->cta_text);
        $this->assertEquals('SEO ', $page->seo_title);
    }

    /**
     * Test Validation.
     */
    public function test_it_validates_required_fields()
    {
        $payload = [
            'page_subtitle' => 'Missing title and status'
        ];

        $response = $this->actingAs($this->admin)->postJson('/product-page', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['page_title', 'is_active']);
    }

    /**
     * Test Update logic (should only have one record).
     */
    public function test_it_updates_existing_settings_rather_than_creating_many()
    {
        ProductPageSetting::create([
            'page_title' => 'Original',
            'is_active'  => true
        ]);

        $payload = [
            'page_title' => 'Updated',
            'is_active'  => true
        ];

        $response = $this->actingAs($this->admin)->postJson('/product-page', $payload);
        $response->assertStatus(200);

        $this->assertEquals(1, ProductPageSetting::count());
        $this->assertEquals('Updated', ProductPageSetting::first()->page_title);
    }
}
