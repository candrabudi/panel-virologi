<?php

namespace Tests\Feature;

use App\Models\AiChatSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiChatSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $user1;
    protected $user2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user1 = User::create([
            'username' => 'user1',
            'email'    => 'user1@test.com',
            'password' => bcrypt('password123'),
            'role'     => 'user',
            'status'   => 'active'
        ]);

        $this->user2 = User::create([
            'username' => 'user2',
            'email'    => 'user2@test.com',
            'password' => bcrypt('password123'),
            'role'     => 'user',
            'status'   => 'active'
        ]);
    }

    /**
     * Test Isolation (P1 fix).
     */
    public function test_user_cannot_access_others_chat_session()
    {
        $session = AiChatSession::create([
            'user_id'       => $this->user1->id,
            'title'         => 'Secret Chat',
            'model'         => 'Virologi-o1',
            'session_token' => \Illuminate\Support\Str::random(40)
        ]);

        // User 2 tries to view User 1's session detail
        $response = $this->actingAs($this->user2)->getJson("/ai/chat/detail/{$session->id}");
        $response->assertStatus(403);

        // User 2 tries to view User 1's session page
        $response = $this->actingAs($this->user2)->get("/ai/chat/sessions/{$session->id}");
        $response->assertStatus(403);

        // User 2 tries to delete User 1's session
        $response = $this->actingAs($this->user2)->deleteJson("/ai/chat/sessions/{$session->id}");
        $response->assertStatus(403);
    }

    /**
     * Test XSS Sanitization.
     */
    public function test_it_sanitizes_chat_messages()
    {
        $payload = [
            'message' => 'Hello <script>alert(1)</script><iframe src="javascript:alert(1)"></iframe>'
        ];

        $response = $this->actingAs($this->user1)->postJson('/ai/chat/send', $payload);
        $response->assertStatus(201);

        $session = AiChatSession::where('user_id', $this->user1->id)->first();
        $this->assertNotNull($session);

        $message = $session->messages()->where('role', 'user')->first();
        // SecurityHelper::cleanString removes entire script blocks
        $this->assertEquals('Hello ', $message->content);
    }

    /**
     * Test Session Creation and Ownership.
     */
    public function test_it_creates_session_with_correct_owner()
    {
        $payload = [
            'message' => 'Testing session ownership'
        ];

        $response = $this->actingAs($this->user2)->postJson('/ai/chat/send', $payload);
        $response->assertStatus(201);

        $session = AiChatSession::where('user_id', $this->user2->id)->first();
        $this->assertNotNull($session);
        $this->assertEquals($this->user2->id, $session->user_id);
    }

    /**
     * Test List Isolation.
     */
    public function test_list_only_returns_own_sessions()
    {
        AiChatSession::create([
            'user_id'       => $this->user1->id,
            'title'         => 'User 1 Chat',
            'model'         => 'Virologi-o1',
            'session_token' => \Illuminate\Support\Str::random(40)
        ]);
        AiChatSession::create([
            'user_id'       => $this->user2->id,
            'title'         => 'User 2 Chat',
            'model'         => 'Virologi-o1',
            'session_token' => \Illuminate\Support\Str::random(40)
        ]);

        $response = $this->actingAs($this->user1)->getJson('/ai/chat/list');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $this->assertEquals('User 1 Chat', $response->json('data.0.title'));
    }
}
