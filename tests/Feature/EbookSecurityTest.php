<?php

namespace Tests\Feature;

use App\Models\Ebook;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EbookSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->admin = User::create([
            'username' => 'admin_ebook',
            'email' => 'admin@ebook.com',
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
    public function test_unauthorized_user_cannot_access_ebooks()
    {
        $response = $this->actingAs($this->user)->get('/ebooks');
        $response->assertStatus(403);
    }

    /**
     * Test XSS Sanitization.
     */
    public function test_it_sanitizes_ebook_content_and_summary()
    {
        $file = UploadedFile::fake()->create('secure.pdf', 100, 'application/pdf');

        $payload = [
            'title'   => 'Securing PDF',
            'summary' => 'Summary <script>alert("xss")</script>',
            'content' => 'Content <iframe src="javascript:alert(1)"></iframe>',
            'level'   => 'beginner',
            'topic'   => 'general',
            'file'    => $file,
            'is_active' => true
        ];

        $response = $this->actingAs($this->admin)->postJson('/ebooks', $payload);

        $response->assertStatus(201);
        
        $ebook = Ebook::first();
        // Summary: script tag content removed by sanitizeHtml
        $this->assertStringNotContainsString('<script>', $ebook->summary);
        // Content: iframe with javascript proto removed
        $this->assertStringNotContainsString('javascript:', $ebook->content);
        $this->assertStringNotContainsString('<iframe', $ebook->content);
    }

    /**
     * Test Secure File Storage.
     */
    public function test_it_stores_ebook_files_securely()
    {
        $file = UploadedFile::fake()->create('ebook.pdf', 100, 'application/pdf');

        $payload = [
            'title'   => 'Network Security 101',
            'level'   => 'intermediate',
            'topic'   => 'network_security',
            'file'    => $file,
        ];

        $response = $this->actingAs($this->admin)->postJson('/ebooks', $payload);
        $response->assertStatus(201);

        $ebook = Ebook::first();
        $filePath = str_replace(asset('storage/'), '', $ebook->file_path);
        
        Storage::disk('public')->assertExists($filePath);
        $this->assertEquals('pdf', $ebook->file_type);
    }

    /**
     * Test Validation.
     */
    public function test_it_enforces_pdf_mimes_and_size()
    {
        $file = UploadedFile::fake()->create('malware.exe', 100, 'application/octet-stream');

        $payload = [
            'title' => 'Malware',
            'level' => 'advanced',
            'topic' => 'malware',
            'file'  => $file,
        ];

        $response = $this->actingAs($this->admin)->postJson('/ebooks', $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['file']);
    }

    /**
     * Test Cleanup on Delete.
     */
    public function test_it_deletes_old_files_on_destroy()
    {
        $file = UploadedFile::fake()->create('temp.pdf', 100, 'application/pdf');
        
        $ebook = Ebook::create([
            'uuid' => 'uuid-123',
            'slug' => 'temp-ebook',
            'title' => 'Temp',
            'level' => 'beginner',
            'topic' => 'general',
            'file_path' => asset('storage/ebooks/files/temp.pdf'),
            'file_type' => 'pdf'
        ]);

        Storage::disk('public')->put('ebooks/files/temp.pdf', 'content');

        $response = $this->actingAs($this->admin)->deleteJson("/ebooks/{$ebook->id}/delete");
        $response->assertStatus(200);

        Storage::disk('public')->assertMissing('ebooks/files/temp.pdf');
        $this->assertDatabaseMissing('ebooks', ['id' => $ebook->id]);
    }
}
