<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'admin_prod',
            'email' => 'admin@prod.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'status' => 'active'
        ]);

        $this->user = User::create([
            'username' => 'regular_user',
            'email' => 'user@prod.com',
            'password' => bcrypt('password123'),
            'role' => 'user', 
            'status' => 'active'
        ]);

        Storage::fake('public');
    }

    /**
     * Test Authorization.
     */
    public function test_unauthorized_user_cannot_access_product_management()
    {
        $response = $this->actingAs($this->user)->get('/products');
        $response->assertStatus(403);
    }

    /**
     * Test XSS Sanitization in Rich Text and Metadata.
     */
    public function test_it_sanitizes_product_data()
    {
        $payload = [
            'product_name'      => 'Secure Router <script>alert(1)</script>',
            'subtitle'          => 'subtitle <iframe src="javascript:alert(1)"></iframe>',
            'summary'           => '<p>Clean summary</p><script>alert("xss")</script>',
            'content'           => '<div>Dangerous <object></object> content</div>',
            'product_type'      => 'hardware',
            'ai_domain'         => 'network_security',
            'is_ai_visible'     => true,
            'is_ai_recommended' => false,
            'ai_keywords'       => ['secure', 'router'],
            'thumbnail'         => UploadedFile::fake()->image('thumb.jpg')
        ];

        $response = $this->actingAs($this->admin)->postJson('/products/store', $payload);

        $response->assertStatus(201);
        
        $product = Product::first();
        $this->assertNotNull($product);
        
        // SecurityHelper::cleanString removes script blocks entirely
        $this->assertEquals('Secure Router ', $product->name);
        $this->assertEquals('subtitle ', $product->subtitle);
        
        // rich text sanitization
        $this->assertStringNotContainsString('<script>', $product->summary);
        $this->assertStringNotContainsString('<object>', $product->content);
        
        $this->assertNotNull($product->thumbnail);
        $this->assertStringContainsString('/storage/products/', $product->thumbnail);
    }

    /**
     * Test Validation.
     */
    public function test_it_validates_required_fields()
    {
        $payload = [
            'subtitle' => 'No name here'
        ];

        $response = $this->actingAs($this->admin)->postJson('/products/store', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['product_name', 'product_type']);
    }

    /**
     * Test Secure Update and File Deletion.
     */
    public function test_it_updates_product_and_deletes_old_thumbnail()
    {
        $product = Product::create([
            'name'         => 'Old Item',
            'product_name' => 'Old Item',
            'slug'         => 'old-item',
            'product_type' => 'digital',
            'thumbnail'    => asset('storage/products/old.jpg'),
            'is_ai_visible' => true,
        ]);
        
        Storage::disk('public')->put('products/old.jpg', 'fake content');

        $payload = [
            'product_name'      => 'New Item',
            'product_type'      => 'digital',
            'thumbnail'         => UploadedFile::fake()->image('new.jpg'),
            'is_ai_visible'     => false,
        ];

        $response = $this->actingAs($this->admin)->putJson("/products/{$product->id}/update", $payload);

        $response->assertStatus(200);
        
        $product->refresh();
        $this->assertEquals('New Item', $product->name);
        $this->assertFalse($product->is_ai_visible);
        
        $this->assertStringContainsString('/storage/products/', $product->thumbnail);
        $this->assertStringNotContainsString('old.jpg', $product->thumbnail);
        
        Storage::disk('public')->assertMissing('products/old.jpg');
        Storage::disk('public')->assertExists(str_replace(asset('storage/'), '', $product->thumbnail));
    }
}
