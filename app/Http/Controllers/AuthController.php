<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show the login view.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Step 1: Validate credentials and prepare for OTP.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'identity' => 'required|string',
            'password' => 'required|string',
        ]);

        $ipKey = 'login-ip:' . $request->ip();
        if (RateLimiter::tooManyAttempts($ipKey, 10)) {
            Log::warning('Brute force attempt detected on IP: ' . $request->ip());
            return ResponseHelper::fail('Terlalu banyak percobaan login, silakan tunggu sebentar', null, 429);
        }
        RateLimiter::hit($ipKey, 60);

        $identity = Str::lower(trim($request->identity));

        $user = User::where(function ($q) use ($identity) {
            $q->whereRaw('LOWER(username) = ?', [$identity])
              ->orWhereRaw('LOWER(email) = ?', [$identity]);
        })
            ->whereIn('role', ['admin', 'editor'])
            ->where('status', 'active')
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            Log::info('Failed login attempt for identity: ' . $identity . ' from IP: ' . $request->ip());
            return ResponseHelper::fail('Username, email, atau kata sandi tidak valid', null, 401);
        }

        // Regenerate session to prevent fixation and fixation during 2FA
        $request->session()->regenerate();

        session([
            'step1_user_id' => $user->id,
            'step1_user_ip' => $request->ip(), // Bind user id to IP for security
        ]);

        Log::info('Successful Step 1 login for User ID: ' . $user->id);

        return ResponseHelper::ok(null, 'Kredensial valid, silakan verifikasi OTP');
    }

    /**
     * Send OTP to the user's email.
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $userId = session('step1_user_id');
        $sessionIp = session('step1_user_ip');

        // Verify session and it belongs to the same requester IP
        if (!$userId || $sessionIp !== $request->ip()) {
            Log::warning('OTP request from invalid session or IP mismatch. IP: ' . $request->ip());
            return ResponseHelper::fail('Sesi login telah berakhir, silakan login ulang', null, 419);
        }

        $user = User::where('id', $userId)
            ->whereIn('role', ['admin', 'editor'])
            ->where('status', 'active')
            ->first();

        if (!$user) {
            return ResponseHelper::fail('Akses ditolak', null, 403);
        }

        $otpKey = 'otp-send-user:' . $user->id;
        if (RateLimiter::tooManyAttempts($otpKey, 5)) {
            return ResponseHelper::fail('Terlalu sering meminta OTP, silakan tunggu', null, 429);
        }
        RateLimiter::hit($otpKey, 60);

        $otp = (string) random_int(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(5);

        // Invalidate previous unused OTPs
        Otp::where('user_id', $user->id)
            ->where('purpose', 'login')
            ->whereNull('verified_at')
            ->update(['expires_at' => Carbon::now()]);

        Otp::create([
            'user_id' => $user->id,
            'code_hash' => password_hash($otp, PASSWORD_BCRYPT),
            'code_last4' => substr($otp, -4),
            'purpose' => 'login',
            'expires_at' => $expiresAt,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit($request->userAgent(), 255),
        ]);

        $body = "Halo {$user->username}\n\n" .
                "Kode OTP Login Panel Virologi:\n\n" .
                "{$otp}\n\n" .
                "Berlaku sampai {$expiresAt->format('Y-m-d H:i:s')} WIB\n\n" .
                "Jika ini bukan Anda, segera amankan akun Anda.";

        Mail::raw($body, function ($message) use ($user) {
            $message->to($user->email)->subject('Kode OTP Login Panel Virologi');
        });

        Log::info('OTP sent to email for User ID: ' . $user->id);

        return ResponseHelper::ok(null, 'Kode OTP telah dikirim ke email Anda');
    }

    /**
     * Step 2: Verify OTP and complete login.
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $userId = session('step1_user_id');
        $sessionIp = session('step1_user_ip');

        if (!$userId || $sessionIp !== $request->ip()) {
            return ResponseHelper::fail('Sesi login telah berakhir, silakan login ulang', null, 419);
        }

        $verifyKey = 'otp-verify-user:' . $userId;
        if (RateLimiter::tooManyAttempts($verifyKey, 8)) {
            Log::warning('Too many OTP verification attempts for User ID: ' . $userId);
            return ResponseHelper::fail('Terlalu banyak percobaan OTP, silakan tunggu', null, 429);
        }
        RateLimiter::hit($verifyKey, 60);

        $otpRecord = Otp::where('user_id', $userId)
            ->where('purpose', 'login')
            ->whereNull('verified_at')
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$otpRecord || !password_verify($request->otp, $otpRecord->code_hash)) {
            Log::info('Failed OTP verification for User ID: ' . $userId);
            return ResponseHelper::fail('Kode OTP salah atau sudah kedaluwarsa', null, 401);
        }

        $otpRecord->update([
            'verified_at' => Carbon::now(),
        ]);

        $user = User::where('id', $userId)
            ->whereIn('role', ['admin', 'editor'])
            ->where('status', 'active')
            ->firstOrFail();

        // Final authentication step
        Auth::login($user, true);
        $request->session()->regenerate();

        $user->update([
            'last_login_at' => Carbon::now(),
        ]);

        // Cleanup temporary session data
        session()->forget(['step1_user_id', 'step1_user_ip']);
        RateLimiter::clear($verifyKey);

        Log::info('Successful full login (including OTP) for User ID: ' . $user->id);

        return ResponseHelper::ok(null, 'Login berhasil');
    }

    /**
     * Log out the user.
     */
    public function logout(Request $request): JsonResponse
    {
        Log::info('User logged out: ' . (auth()->id() ?? 'Unknown'));

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return ResponseHelper::ok(null, 'Berhasil logout');
    }
}
