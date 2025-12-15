<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'identity' => 'required|string',
            'password' => 'required|string',
        ]);

        $ipKey = 'login-ip:'.$request->ip();
        if (RateLimiter::tooManyAttempts($ipKey, 10)) {
            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak percobaan login, silakan tunggu sebentar',
            ], 429);
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
            return response()->json([
                'success' => false,
                'message' => 'Username, email, atau kata sandi tidak valid',
            ], 401);
        }

        session([
            'step1_user_id' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kredensial valid',
        ]);
    }

    public function sendOtp(Request $request)
    {
        $userId = session('step1_user_id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi login telah berakhir, silakan login ulang',
            ], 419);
        }

        $user = User::where('id', $userId)
            ->whereIn('role', ['admin', 'editor'])
            ->where('status', 'active')
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak',
            ], 403);
        }

        $otpKey = 'otp-send-user:'.$user->id;
        if (RateLimiter::tooManyAttempts($otpKey, 5)) {
            return response()->json([
                'success' => false,
                'message' => 'Terlalu sering meminta OTP, silakan tunggu',
            ], 429);
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

        $body =
            "Halo {$user->username}\n\n".
            "Kode OTP Login Panel Virologi:\n\n".
            "{$otp}\n\n".
            "Berlaku sampai {$expiresAt->format('Y-m-d H:i:s')} WIB\n\n".
            'Jika ini bukan Anda, abaikan email ini.';

        Mail::raw($body, function ($message) use ($user) {
            $message->to($user->email)->subject('Kode OTP Login Panel Virologi');
        });

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP telah dikirim ke email Anda',
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $userId = session('step1_user_id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi login telah berakhir, silakan login ulang',
            ], 419);
        }

        $verifyKey = 'otp-verify-user:'.$userId;
        if (RateLimiter::tooManyAttempts($verifyKey, 8)) {
            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak percobaan OTP, silakan tunggu',
            ], 429);
        }
        RateLimiter::hit($verifyKey, 60);

        $otp = Otp::where('user_id', $userId)
            ->where('purpose', 'login')
            ->whereNull('verified_at')
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$otp || !password_verify($request->otp, $otp->code_hash)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP salah atau sudah kedaluwarsa',
            ], 401);
        }

        $otp->update([
            'verified_at' => Carbon::now(),
        ]);

        $user = User::where('id', $userId)
            ->whereIn('role', ['admin', 'editor'])
            ->where('status', 'active')
            ->firstOrFail();

        Auth::login($user, true);
        $request->session()->regenerate();

        $user->update([
            'last_login_at' => Carbon::now(),
        ]);

        session()->forget('step1_user_id');
        RateLimiter::clear($verifyKey);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil logout',
        ]);
    }
}
