<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;

class AuthService
{
    /**
     * Validate user credentials and return user if valid
     */
    public static function validateCredentials(string $email, string $password): ?User
    {
        $user = User::where('email', $email)->first();

        if (!$user || !password_verify($password, $user->password)) {
            return null;
        }

        return $user;
    }

    /**
     * Generate email verification token
     */
    public static function generateVerificationToken(): string
    {
        return Str::random(64);
    }

    /**
     * Check if account has been verified recently
     */
    public static function isRecentlyVerified(User $user, int $minutes = 5): bool
    {
        if ($user->email_verified_at === null) {
            return false;
        }

        return $user->email_verified_at->diffInMinutes(now()) <= $minutes;
    }

    /**
     * Can resend verification email (rate limiting)
     */
    public static function canResendVerificationEmail(User $user, int $waitMinutes = 1): bool
    {
        if ($user->email_verified_at !== null) {
            return false;
        }

        // Simple rate limiting - dapat diimplementasikan lebih kompleks dengan Redis
        return true;
    }

    /**
     * Get user profile data
     */
    public static function getUserProfile(User $user): array
    {
        return [
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
            'created_at' => $user->created_at,
        ];
    }
}
