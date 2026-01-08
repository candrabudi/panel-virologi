<?php

namespace Tests\Feature;

use App\Models\WebsiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WebsiteSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'admin_web',
            'email' => 'admin@web.com',
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

        WebsiteSetting::create([
            'site_name' => 'Original Web'
        ]);

        Storage::fake('public');
    }

    /**
     * Test Authorization.
     */
    public function test_unauthorized_user_cannot_save_website_general_info()
    {
        $response = $this->actingAs($this->user)->postJson('/website/general', [
            'name' => 'New Name'
        ]);
        $response->assertStatus(403);
    }

    /**
     * Test XSS Sanitization in General Info.
     */
    public function test_it_sanitizes_website_general_info()
    {
        $payload = [
            'name'        => 'My Web <script>alert(1)</script>',
            'tagline'     => 'Tagline <iframe src="javascript:alert(1)"></iframe>',
            'description' => 'Desc <object data="data:text/html,..."></object>'
        ];

        $response = $this->actingAs($this->admin)->postJson('/website/general', $payload);

        $response->assertStatus(200);
        
        $website = WebsiteSetting::first();
        $this->assertNotNull($website);
        
        // SecurityHelper::cleanString removes entire script blocks
        $this->assertEquals('My Web ', $website->site_name);
        $this->assertEquals('Tagline ', $website->site_tagline);
        $this->assertEquals('Desc ', $website->site_description);
    }

    /**
     * Test Contact Info Security.
     */
    public function test_it_saves_contact_info_securely()
    {
        $payload = [
            'email' => 'contact@test.com',
            'phone' => '+628123456789 <script>alert(1)</script>'
        ];

        $response = $this->actingAs($this->admin)->postJson('/website/contact', $payload);
        $response->assertStatus(200);

        $website = WebsiteSetting::first();
        $this->assertEquals('contact@test.com', $website->site_email);
        $this->assertEquals('+628123456789 ', $website->site_phone);
    }

    /**
     * Test Branding (File Uploads) Security.
     */
    public function test_it_saves_branding_and_deletes_old_files()
    {
        $website = WebsiteSetting::first();
        $website->update([
            'site_logo' => asset('storage/website/old_rect.jpg'),
        ]);
        
        Storage::disk('public')->put('website/old_rect.jpg', 'fake content');

        $payload = [
            'logo_rectangle' => UploadedFile::fake()->image('new_rect.jpg'),
            'logo_square'    => UploadedFile::fake()->image('new_square.jpg'),
            'favicon'        => UploadedFile::fake()->image('favicon.png')
        ];

        $response = $this->actingAs($this->admin)->post('/website/branding', $payload);
        $response->assertStatus(200);

        $website->refresh();
        $this->assertStringContainsString('/storage/website/site_logo_', $website->site_logo);
        $this->assertStringContainsString('/storage/website/site_logo_footer_', $website->site_logo_footer);
        $this->assertStringContainsString('/storage/website/site_favicon_', $website->site_favicon);

        Storage::disk('public')->assertMissing('website/old_rect.jpg');
    }
}
