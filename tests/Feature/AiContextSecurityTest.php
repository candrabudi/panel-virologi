<?php

namespace Tests\Feature;

use App\Models\AiContext;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiContextSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'admin',
            'email'    => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role'     => 'admin',
            'status'   => 'active'
        ]);

        $this->user = User::create([
            'username' => 'user',
            'email'    => 'user@test.com',
            'password' => bcrypt('password123'),
            'role'     => 'user',
            'status'   => 'active'
        ]);
    }

    /**
     * Test Authorization.
     */
    public function test_non_admin_cannot_manage_ai_contexts()
    {
        $response = $this->actingAs($this->user)->getJson('/ai/contexts/list');
        $response->assertStatus(403);

        $response = $this->actingAs($this->user)->postJson('/ai/contexts/store', [
            'code' => 'new-ctx',
            'name' => 'New Context',
            'use_internal_source' => true
        ]);
        $response->assertStatus(403);
    }

    /**
     * Test XSS Sanitization.
     */
    public function test_it_sanitizes_context_name()
    {
        $payload = [
            'code'                => 'xss-ctx',
            'name'                => 'Context <script>alert(1)</script>',
            'use_internal_source' => true
        ];

        $response = $this->actingAs($this->admin)->postJson('/ai/contexts/store', $payload);
        $response->assertStatus(201);

        $context = AiContext::where('code', 'xss-ctx')->first();
        $this->assertNotNull($context);
        $this->assertEquals('Context ', $context->name);
    }

    /**
     * Test Regex Validation for Code.
     */
    public function test_it_validates_code_regex()
    {
        $payload = [
            'code'                => 'Invalid Code!',
            'name'                => 'Valid Name',
            'use_internal_source' => true
        ];

        $response = $this->actingAs($this->admin)->postJson('/ai/contexts/store', $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['code']);
    }

    /**
     * Test Unique Constraint.
     */
    public function test_it_enforces_unique_code_at_store()
    {
        AiContext::create([
            'code'                => 'main-ctx',
            'name'                => 'Main',
            'use_internal_source' => true,
            'session_token'       => 'test-token' // just in case
        ]);

        $payload = [
            'code'                => 'main-ctx',
            'name'                => 'Duplicate',
            'use_internal_source' => false
        ];

        $response = $this->actingAs($this->admin)->postJson('/ai/contexts/store', $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['code']);
    }

    /**
     * Test Update.
     */
    public function test_it_updates_context_securely()
    {
        $context = AiContext::create([
            'code'                => 'update-ctx',
            'name'                => 'Old Name',
            'use_internal_source' => true
        ]);

        $payload = [
            'name'                => 'New Name',
            'use_internal_source' => false,
            'is_active'           => false
        ];

        $response = $this->actingAs($this->admin)->postJson("/ai/contexts/update/{$context->id}", $payload);
        $response->assertStatus(200);

        $context->refresh();
        $this->assertEquals('New Name', $context->name);
        $this->assertFalse((bool)$context->use_internal_source);
        $this->assertFalse((bool)$context->is_active);
    }

    /**
     * Test Delete.
     */
    public function test_it_deletes_context()
    {
        $context = AiContext::create([
            'code'                => 'del-ctx',
            'name'                => 'Delete Me',
            'use_internal_source' => true
        ]);

        $response = $this->actingAs($this->admin)->deleteJson("/ai/contexts/destroy/{$context->id}");
        $response->assertStatus(200);

        $this->assertDatabaseMissing('ai_contexts', ['id' => $context->id]);
    }
}
