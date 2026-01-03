<?php

namespace Tests\Feature;

use App\Models\HomepageBlogSection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageBlogSectionSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'admin_cms',
            'email' => 'admin@cms.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active'
        ]);

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
    public function test_unauthorized_user_cannot_access_homepage_cms()
    {
        $response = $this->actingAs($this->user)->get('/homepage-blog-section');
        $response->assertStatus(403);
    }

    /**
     * Test XSS Sanitization in Title and Subtitle.
     */
    public function test_it_sanitizes_homepage_blog_section_data()
    {
        $payload = [
            'title'     => 'Latest News <script>alert("xss")</script>',
            'subtitle'  => 'Our latest updates <iframe src="javascript:alert(1)"></iframe>',
            'is_active' => true
        ];

        $response = $this->actingAs($this->admin)->postJson('/homepage-blog-section', $payload);

        $response->assertStatus(200);
        
        $section = HomepageBlogSection::first();
        
        // title: Entire script block removed by improved sanitizeHtml
        $this->assertEquals('Latest News ', $section->title);
        
        // subtitle: Entire iframe block removed by improved sanitizeHtml
        $this->assertEquals('Our latest updates ', $section->subtitle);
    }

    /**
     * Test Validation.
     */
    public function test_it_validates_required_fields()
    {
        $payload = [
            'subtitle' => 'No title here'
        ];

        $response = $this->actingAs($this->admin)->postJson('/homepage-blog-section', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'is_active']);
    }

    /**
     * Test Show JSON.
     */
    public function test_it_returns_correct_json_structure()
    {
        HomepageBlogSection::create([
            'title'     => 'Blog',
            'subtitle'  => 'Read more',
            'is_active' => true
        ]);

        $response = $this->actingAs($this->admin)->getJson('/homepage-blog-section/show');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'id',
                'title',
                'subtitle',
                'is_active'
            ]
        ]);
    }
}
