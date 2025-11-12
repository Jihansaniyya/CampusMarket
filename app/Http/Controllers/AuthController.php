<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:buyer,seller'],
            'phone' => ['nullable', 'string', 'max:20'],
            'store_name' => ['required_if:role,seller', 'nullable', 'string', 'max:255', 'unique:users,store_name'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        // Generate email verification token
        $verificationToken = Str::random(64);

        try {
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'phone' => $validated['phone'] ?? null,
                'store_name' => $validated['store_name'] ?? null,
                'description' => $validated['description'] ?? null,
                'email_verification_token' => $verificationToken,
            ]);

            // Send verification email
            $user->notify(new VerifyEmailNotification($verificationToken, $user));

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil! Silakan verifikasi email Anda. Cek folder spam jika email tidak terlihat.',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat registrasi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        // Check if user exists
        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
                'errors' => [
                    'email' => ['Email tidak terdaftar.']
                ]
            ], 401);
        }

        // Check email verification
        if (!$user->isEmailVerified()) {
            return response()->json([
                'success' => false,
                'message' => 'Email Anda belum diverifikasi. Silakan cek email untuk link verifikasi.',
                'errors' => [
                    'email' => ['Email belum diverifikasi.']
                ]
            ], 403);
        }

        // Check password
        if (!Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
                'errors' => [
                    'password' => ['Password salah.']
                ]
            ], 401);
        }

        // Update last login
        $user->updateLastLogin();

        // Login user
        Auth::login($user, $validated['remember'] ?? false);

        // Generate session token or API token if needed
        $token = $user->createToken('auth_token')->plainTextToken ?? null;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'store_name' => $user->store_name,
                'email_verified' => $user->isEmailVerified(),
            ],
            'token' => $token,
        ], 200);
    }

    /**
     * Verify email
     */
    public function verifyEmail(Request $request)
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
        ]);

        // Find user by email and token
        $user = User::where('email', $validated['email'])
            ->where('email_verification_token', $validated['token'])
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Token verifikasi tidak valid atau telah kadaluarsa.',
            ], 400);
        }

        // Check if already verified
        if ($user->isEmailVerified()) {
            return response()->json([
                'success' => true,
                'message' => 'Email Anda sudah diverifikasi sebelumnya.',
            ], 200);
        }

        try {
            // Update user
            $user->update([
                'email_verified_at' => now(),
                'email_verification_token' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email berhasil diverifikasi! Anda sekarang dapat login.',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified' => true,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat verifikasi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Resend verification email
     */
    public function resendVerificationEmail(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak terdaftar.',
            ], 404);
        }

        // Check if already verified
        if ($user->isEmailVerified()) {
            return response()->json([
                'success' => false,
                'message' => 'Email Anda sudah diverifikasi.',
            ], 400);
        }

        try {
            // Generate new token
            $verificationToken = Str::random(64);
            $user->update(['email_verification_token' => $verificationToken]);

            // Send verification email
            $user->notify(new VerifyEmailNotification($verificationToken, $user));

            return response()->json([
                'success' => true,
                'message' => 'Email verifikasi telah dikirim ulang. Silakan cek email Anda.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil!',
        ], 200);
    }

    /**
     * Get current user
     */
    public function currentUser(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terautentikasi.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'phone' => $user->phone,
                'store_name' => $user->store_name,
                'description' => $user->description,
                'email_verified' => $user->isEmailVerified(),
                'store_verified' => $user->store_verified_at !== null,
                'last_login_at' => $user->last_login_at,
            ]
        ], 200);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terautentikasi.',
            ], 401);
        }

        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:500'],
            'store_name' => ['nullable', 'string', 'max:255', 'unique:users,store_name,' . $user->id],
        ]);

        try {
            $user->update(array_filter($validated));

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui!',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'store_name' => $user->store_name,
                    'description' => $user->description,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
