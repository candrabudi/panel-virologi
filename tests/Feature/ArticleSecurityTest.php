<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleTag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $category;
    protected $tag;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        // Setup User
        $this->admin = User::create([
            'username' => 'admin_article',
            'email' => 'admin@article.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active'
        ]);

        // Setup Category & Tag
        $this->category = ArticleCategory::create(['name' => 'Tech', 'slug' => 'tech']);
        $this->tag = ArticleTag::create(['name' => 'Laravel', 'slug' => 'laravel']);
    }

    /**
     * Test XSS Sanitization in Article Content.
     */
    public function test_it_sanitizes_article_content_and_excerpt()
    {
        $payload = [
            'title' => 'Security Alert',
            'excerpt' => 'Short desc <script>alert(1)</script>',
            'content' => '<h1>Content</h1><script>alert("xss")</script><img src=x onerror=alert(1)>',
            'categories' => [$this->category->id],
            'tags' => [$this->tag->id],
            'is_published' => '1'
        ];

        $response = $this->actingAs($this->admin)
            ->postJson('/articles', $payload);

        $response->assertStatus(200);

        $article = Article::first();

        // Content should keep <h1> but lose <script> and onerror
        $this->assertStringContainsString('<h1>Content</h1>', $article->content);
        $this->assertStringNotContainsString('<script>', $article->content);
        $this->assertStringNotContainsString('onerror', $article->content);

        // Excerpt should lose all tags
        $this->assertStringNotContainsString('<script>', $article->excerpt);
        $this->assertEquals('Short desc ', $article->excerpt);
    }

    /**
     * Test Unique Slug Generation.
     */
    public function test_it_generates_unique_slugs_for_duplicate_titles()
    {
        $title = 'My Awesome Article';
        
        // Create first article
        $this->actingAs($this->admin)->postJson('/articles', [
            'title' => $title,
            'content' => 'First content',
            'categories' => [$this->category->id],
        ]);

        // Create second article with same title
        $this->actingAs($this->admin)->postJson('/articles', [
            'title' => $title,
            'content' => 'Second content',
            'categories' => [$this->category->id],
        ]);

        $articles = Article::where('title', $title)->get();
        $this->assertCount(2, $articles);
        $this->assertNotEquals($articles[0]->slug, $articles[1]->slug);
        $this->assertStringContainsString('my-awesome-article', $articles[1]->slug);
    }

    /**
     * Test Thumbnail Upload and relative path storage.
     */
    public function test_it_stores_thumbnail_as_relative_path()
    {
        $file = UploadedFile::fake()->image('thumb.jpg');

        $payload = [
            'title' => 'Article with Image',
            'content' => 'Content here',
            'categories' => [$this->category->id],
            'thumbnail' => $file
        ];

        $this->actingAs($this->admin)->postJson('/articles', $payload);

        $article = Article::first();

        // Database should store relative path
        // Note: Raw value from DB is checked because accessor will prepend URL
        $rawThumbnail = \Illuminate\Support\Facades\DB::table('articles')->where('id', $article->id)->value('thumbnail');
        $this->assertStringNotContainsString('http', $rawThumbnail);
        $this->assertStringContainsString('articles/', $rawThumbnail);

        // Model attribute (via accessor) should return full URL
        $this->assertStringContainsString('http', $article->thumbnail);
        
        Storage::disk('public')->assertExists($rawThumbnail);
    }

    /**
     * Test File Cleanup on update.
     */
    public function test_it_deletes_old_thumbnail_on_update()
    {
        $oldFile = UploadedFile::fake()->image('old.jpg');
        $newFile = UploadedFile::fake()->image('new.jpg');

        // 1. Create with old file
        $response = $this->actingAs($this->admin)->postJson('/articles', [
            'title' => 'Cleanup Test',
            'content' => 'Content',
            'categories' => [$this->category->id],
            'thumbnail' => $oldFile
        ]);
        
        $article = Article::first();
        $oldPath = \Illuminate\Support\Facades\DB::table('articles')->where('id', $article->id)->value('thumbnail');

        // 2. Update with new file
        $this->actingAs($this->admin)->putJson("/articles/{$article->id}", [
            'title' => 'Cleanup Test Updated',
            'content' => 'Content Updated',
            'categories' => [$this->category->id],
            'thumbnail' => $newFile
        ]);

        // 3. Assert old file is gone, new file exists
        Storage::disk('public')->assertMissing($oldPath);
        $newPath = \Illuminate\Support\Facades\DB::table('articles')->where('id', $article->id)->value('thumbnail');
        Storage::disk('public')->assertExists($newPath);
    }
}
