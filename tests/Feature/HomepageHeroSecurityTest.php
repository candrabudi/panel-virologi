<?php

namespace Tests\Feature;

use App\Models\HomepageHero;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageHeroSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'admin_hero',
            'email' => 'admin@hero.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'status' => 'active'
        ]);

        $this->user = User::create([
            'username' => 'regular_user',
            'email' => 'user@hero.com',
            'password' => bcrypt('password123'),
            'role' => 'user', 
            'status' => 'active'
        ]);
    }

    /**
     * Test Authorization.
     */
    public function test_unauthorized_user_cannot_access_homepage_hero()
    {
        $response = $this->actingAs($this->user)->get('/homepage-hero');
        $response->assertStatus(403);
    }

    /**
     * Test XSS Sanitization in all text fields.
     */
    public function test_it_sanitizes_homepage_hero_data()
    {
        $payload = [
            'pre_title'             => 'Pre <script>alert(1)</script>',
            'title'                 => 'Title <iframe src="javascript:alert(1)"></iframe>',
            'subtitle'              => 'Sub <object data="data:text/html,..."></object>',
            'primary_button_text'   => 'Click <embed src="bad.swf">',
            'primary_button_url'    => 'http://safe.com',
            'secondary_button_text' => 'Wait',
            'secondary_button_url'  => 'https://secure.com',
            'is_active'             => true
        ];

        $response = $this->actingAs($this->admin)->postJson('/homepage-hero', $payload);

        $response->assertStatus(201);
        
        $hero = HomepageHero::first();
        $this->assertNotNull($hero);
        
        // SecurityHelper::sanitizeHtml (via global middleware) removes the entire script/iframe/object block
        $this->assertEquals('Pre ', $hero->pre_title);
        $this->assertEquals('Title ', $hero->title);
        $this->assertEquals('Sub ', $hero->subtitle);
        $this->assertEquals('Click ', $hero->primary_button_text);
    }

    /**
     * Test Multiple Active Hero Deactivation.
     */
    public function test_it_deactivates_old_hero_when_new_one_is_active()
    {
        $oldHero = HomepageHero::create([
            'pre_title'             => 'Old Pre',
            'title'                 => 'Old Title',
            'subtitle'              => 'Old Sub',
            'primary_button_text'   => 'Old',
            'primary_button_url'    => '#',
            'secondary_button_text' => 'Old',
            'secondary_button_url'  => '#',
            'is_active'             => true
        ]);

        $payload = [
            'pre_title'             => 'New Pre',
            'title'                 => 'New Title',
            'subtitle'              => 'New Sub',
            'primary_button_text'   => 'New',
            'primary_button_url'    => '#',
            'secondary_button_text' => 'New',
            'secondary_button_url'  => '#',
            'is_active'             => true
        ];

        $response = $this->actingAs($this->admin)->postJson('/homepage-hero', $payload);
        $response->assertStatus(201);
        $newHeroId = $response->json('data.id');

        $oldHero->refresh();
        $this->assertFalse($oldHero->is_active);

        $newHero = HomepageHero::find($newHeroId);
        $this->assertTrue($newHero->is_active);
    }

    /**
     * Test Validation.
     */
    public function test_it_validates_required_fields()
    {
        $payload = [
            'title' => 'Missing almost everything'
        ];

        $response = $this->actingAs($this->admin)->postJson('/homepage-hero', $payload);

        $response->assertStatus(422);
        // Should catch missing pre_title, subtitle, buttons and is_active
        $response->assertJsonValidationErrors(['pre_title', 'subtitle', 'primary_button_text', 'is_active']);
    }
}
