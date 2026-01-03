<?php

namespace Tests\Feature;

use App\Models\HomepageThreatMapSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageThreatMapSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'admin_threat',
            'email' => 'admin@threat.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'status' => 'active'
        ]);

        $this->user = User::create([
            'username' => 'regular_user',
            'email' => 'user@threat.com',
            'password' => bcrypt('password123'),
            'role' => 'user', 
            'status' => 'active'
        ]);
    }

    /**
     * Test Authorization.
     */
    public function test_unauthorized_user_cannot_access_threat_map_management()
    {
        $response = $this->actingAs($this->user)->get('/homepage-threat-map');
        $response->assertStatus(403);
    }

    /**
     * Test XSS Sanitization in all text fields.
     */
    public function test_it_sanitizes_threat_map_data()
    {
        $payload = [
            'pre_title'   => 'Live <script>alert(1)</script>',
            'title'       => 'Threats <iframe src="javascript:alert(1)"></iframe>',
            'description' => 'Global <object data="data:text/html,..."></object>',
            'cta_text'    => 'View <embed src="bad.swf">',
            'cta_url'     => 'https://safe.com',
            'is_active'   => true
        ];

        $response = $this->actingAs($this->admin)->postJson('/homepage-threat-map', $payload);

        $response->assertStatus(200);
        
        $section = HomepageThreatMapSection::first();
        $this->assertNotNull($section);
        
        // SecurityHelper::sanitizeHtml (via global middleware) removes the entire script/iframe/object block
        $this->assertEquals('Live ', $section->pre_title);
        $this->assertEquals('Threats ', $section->title);
        $this->assertEquals('Global ', $section->description);
        $this->assertEquals('View ', $section->cta_text);
    }

    /**
     * Test Validation.
     */
    public function test_it_validates_required_fields()
    {
        $payload = [
            'description' => 'Missing title and status'
        ];

        $response = $this->actingAs($this->admin)->postJson('/homepage-threat-map', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'is_active']);
    }

    /**
     * Test Update logic (should only have one record).
     */
    public function test_it_updates_existing_section_rather_than_creating_many()
    {
        HomepageThreatMapSection::create([
            'title'     => 'Original',
            'is_active' => true
        ]);

        $payload = [
            'title'     => 'Updated',
            'is_active' => true
        ];

        $response = $this->actingAs($this->admin)->postJson('/homepage-threat-map', $payload);
        $response->assertStatus(200);

        $this->assertEquals(1, HomepageThreatMapSection::count());
        $this->assertEquals('Updated', HomepageThreatMapSection::first()->title);
    }
}
