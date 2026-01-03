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
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'identity' => 'required|string',
            'password' => 'required|string',
        ]);

        $ipKey = 'login-ip:'.$request->ip();
        if (RateLimiter::tooManyAttempts($ipKey, 10)) {
            return ResponseHelper::fail('Terlalu banyak percobaan login, silakan tunggu', null, 429);
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
            return ResponseHelper::fail('Username, email, atau kata sandi tidak valid', null, 401);
        }

        session([
            'step1_user_id' => $user->id,
            'step1_user_ip' => $request->ip(),
        ]);

        Log::info('Login step 1 success', ['user_id' => $user->id]);

        return ResponseHelper::ok(null, 'Kredensial valid, silakan verifikasi OTP');
    }

    public function sendOtp(Request $request): JsonResponse
    {
        $userId = session('step1_user_id');
        $sessionIp = session('step1_user_ip');

        if (!$userId || $sessionIp !== $request->ip()) {
            return ResponseHelper::fail('Sesi login telah berakhir, silakan login ulang', null, 419);
        }

        $user = User::where('id', $userId)
            ->whereIn('role', ['admin', 'editor'])
            ->where('status', 'active')
            ->firstOrFail();

        $otpKey = 'otp-send-user:'.$user->id;
        if (RateLimiter::tooManyAttempts($otpKey, 5)) {
            return ResponseHelper::fail('Terlalu sering meminta OTP, silakan tunggu', null, 429);
        }
        RateLimiter::hit($otpKey, 60);

        $otp = (string) random_int(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(5);

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

        Mail::raw(
            "Kode OTP Login:\n\n{$otp}\n\nBerlaku sampai {$expiresAt->format('Y-m-d H:i:s')} WIB",
            fn ($m) => $m->to($user->email)->subject('Kode OTP Login')
        );

        Log::info('OTP sent', ['user_id' => $user->id]);

        return ResponseHelper::ok(null, 'Kode OTP telah dikirim ke email Anda');
    }

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

        $verifyKey = 'otp-verify-user:'.$userId;
        if (RateLimiter::tooManyAttempts($verifyKey, 8)) {
            return ResponseHelper::fail('Terlalu banyak percobaan OTP', null, 429);
        }
        RateLimiter::hit($verifyKey, 60);

        $otpRecord = Otp::where('user_id', $userId)
            ->where('purpose', 'login')
            ->whereNull('verified_at')
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$otpRecord || !password_verify($request->otp, $otpRecord->code_hash)) {
            return ResponseHelper::fail('Kode OTP salah atau kedaluwarsa', null, 401);
        }

        $otpRecord->update(['verified_at' => Carbon::now()]);

        $user = User::where('id', $userId)
            ->whereIn('role', ['admin', 'editor'])
            ->where('status', 'active')
            ->firstOrFail();

        Auth::login($user, true);

        $request->session()->regenerate();

        $user->update(['last_login_at' => Carbon::now()]);

        session()->forget(['step1_user_id', 'step1_user_ip']);
        RateLimiter::clear($verifyKey);

        Log::info('Login success full', ['user_id' => $user->id]);

        return ResponseHelper::ok(null, 'Login berhasil');
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return ResponseHelper::ok(null, 'Berhasil logout');
    }
}
