<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'admin_mgr',
            'email' => 'admin@mgr.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'status' => 'active'
        ]);

        $this->user = User::create([
            'username' => 'regular_user',
            'email' => 'user@test.com',
            'password' => bcrypt('password123'),
            'role' => 'user', 
            'status' => 'active'
        ]);
    }

    /**
     * Test Authorization.
     */
    public function test_unauthorized_user_cannot_access_user_management()
    {
        $response = $this->actingAs($this->user)->get('/users');
        $response->assertStatus(403);
    }

    /**
     * Test XSS Sanitization in User Details.
     */
    public function test_it_sanitizes_user_management_data()
    {
        $payload = [
            'username'     => 'newuser <script>alert(1)</script>',
            'email'        => 'newuser@test.com',
            'password'     => 'securepassword123',
            'role'         => 'editor',
            'status'       => 'active',
            'full_name'    => 'Full Name <iframe src="javascript:alert(1)"></iframe>',
            'phone_number' => '08123456789 <style>body{color:red}</style>'
        ];

        $response = $this->actingAs($this->admin)->postJson('/users', $payload);

        $response->assertStatus(201);
        
        $newUser = User::where('email', 'newuser@test.com')->first();
        $this->assertNotNull($newUser);
        
        // username: Entire script block removed by improved sanitizeHtml via global middleware
        $this->assertEquals('newuser ', $newUser->username);
        
        $this->assertNotNull($newUser->detail);
        // full_name: Entire iframe block removed by improved sanitizeHtml
        $this->assertEquals('Full Name ', $newUser->detail->full_name);
    }

    /**
     * Test Self-Deletion Prevention.
     */
    public function test_it_prevents_self_deletion()
    {
        $response = $this->actingAs($this->admin)->deleteJson("/users/{$this->admin->id}");

        $response->assertStatus(403);
        $response->assertJson([
            'status'  => false,
            'message' => 'Tidak dapat menghapus akun Anda sendiri'
        ]);
        
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    /**
     * Test Validation.
     */
    public function test_it_validates_required_fields_and_unique_constraints()
    {
        $payload = [
            'username' => 'admin_mgr', // Taken
            'email'    => 'admin@mgr.com', // Taken
        ];

        $response = $this->actingAs($this->admin)->postJson('/users', $payload);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['username', 'email', 'password', 'role', 'status', 'full_name']);
    }

    /**
     * Test Update with Password.
     */
    public function test_it_updates_user_and_details()
    {
        $targetUser = User::create([
            'username' => 'target',
            'email' => 'target@test.com',
            'password' => bcrypt('oldpass'),
            'role' => 'user',
            'status' => 'active'
        ]);

        $payload = [
            'username'     => 'updated_target',
            'email'        => 'updated@test.com',
            'password'     => 'newsecurepass123',
            'role'         => 'editor',
            'status'       => 'inactive',
            'full_name'    => 'Updated Name',
            'phone_number' => '0987654321'
        ];

        $response = $this->actingAs($this->admin)->putJson("/users/{$targetUser->id}/update", $payload);

        $response->assertStatus(200);
        
        $targetUser->refresh();
        $this->assertEquals('updated_target', $targetUser->username);
        $this->assertTrue(Hash::check('newsecurepass123', $targetUser->password));
        $this->assertEquals('editor', $targetUser->role);
        
        $this->assertEquals('Updated Name', $targetUser->detail->full_name);
    }
}
