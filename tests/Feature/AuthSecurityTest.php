<?php

namespace Tests\Feature;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $password = 'Secret123!';

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();

        // Create a test admin user
        $this->user = User::create([
            'username' => 'admin_secure',
            'email' => 'admin@secure.com',
            'password' => Hash::make($this->password),
            'role' => 'admin',
            'status' => 'active'
        ]);
    }

    /**
     * Test Step 1: Valid Credentials.
     */
    public function test_step1_valid_credentials_should_set_session()
    {
        $response = $this->postJson('/login', [
            'identity' => 'admin_secure',
            'password' => $this->password,
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => true]);
        
        $this->assertEquals($this->user->id, session('step1_user_id'));
        $this->assertNotEmpty(session('step1_user_ip'));
    }

    /**
     * Test Step 1: Invalid Credentials.
     */
    public function test_step1_invalid_credentials_should_fail()
    {
        $response = $this->postJson('/login', [
            'identity' => 'admin_secure',
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(401);
        $this->assertNull(session('step1_user_id'));
    }

    /**
     * Test Step 2: Request OTP with valid session.
     */
    public function test_request_otp_with_valid_session()
    {
        // Simulate successful Step 1
        $this->withSession([
            'step1_user_id' => $this->user->id,
            'step1_user_ip' => '127.0.0.1'
        ]);

        $response = $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
            ->postJson('/login/send-otp');

        $response->assertStatus(200);
        $this->assertDatabaseHas('otps', [
            'user_id' => $this->user->id,
            'purpose' => 'login'
        ]);
    }

    /**
     * Test Step 2: Request OTP with IP mismatch (Security Check).
     */
    public function test_request_otp_fails_on_ip_mismatch()
    {
        // Step 1 done on IP 1.1.1.1
        $this->withSession([
            'step1_user_id' => $this->user->id,
            'step1_user_ip' => '1.1.1.1'
        ]);

        // Request OTP from IP 2.2.2.2
        $response = $this->withServerVariables(['REMOTE_ADDR' => '2.2.2.2'])
            ->postJson('/login/send-otp');

        $response->assertStatus(419); // Mismatch detected
    }

    /**
     * Test Step 3: Complete Login with Valid OTP.
     */
    public function test_complete_login_with_valid_otp()
    {
        // 1. Setup Step 1 session
        $this->withSession([
            'step1_user_id' => $this->user->id,
            'step1_user_ip' => '127.0.0.1'
        ]);

        // 2. Generate OTP manually in DB
        $otpCode = '123456';
        Otp::create([
            'user_id' => $this->user->id,
            'code_hash' => Hash::make($otpCode),
            'code_last4' => '3456',
            'purpose' => 'login',
            'expires_at' => now()->addMinutes(5),
            'ip_address' => '127.0.0.1'
        ]);

        // 3. Verify OTP
        $response = $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
            ->postJson('/login/verify-otp', [
                'otp' => $otpCode
            ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Login berhasil']);

        // 4. Check Authentication state
        $this->assertTrue(auth()->check());
        $this->assertEquals($this->user->id, auth()->id());
        
        // 5. Cleanup check
        $this->assertNull(session('step1_user_id'));
    }

    /**
     * Test Step 3: Invalid OTP should fail.
     */
    public function test_verify_otp_fails_with_wrong_code()
    {
        $this->withSession([
            'step1_user_id' => $this->user->id,
            'step1_user_ip' => '127.0.0.1'
        ]);

        $response = $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
            ->postJson('/login/verify-otp', [
                'otp' => '000000' // Wrong OTP
            ]);

        $response->assertStatus(401);
        $this->assertFalse(auth()->check());
    }
}
