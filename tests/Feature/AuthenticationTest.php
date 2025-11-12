<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user registration
     */
    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'buyer',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success',
            'message',
            'user' => ['id', 'name', 'email', 'role'],
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'role' => 'buyer',
        ]);
    }

    /**
     * Test seller registration with store name
     */
    public function test_seller_registration_requires_store_name(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Jane Seller',
            'email' => 'jane@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'seller',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['store_name']);
    }

    /**
     * Test duplicate email registration
     */
    public function test_cannot_register_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->postJson('/api/auth/register', [
            'name' => 'Another User',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'buyer',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    /**
     * Test login with unverified email
     */
    public function test_cannot_login_with_unverified_email(): void
    {
        $user = User::factory()->unverified()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
        ]);
    }

    /**
     * Test successful login
     */
    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'user' => ['id', 'name', 'email', 'role'],
            'token',
        ]);
    }

    /**
     * Test login with wrong password
     */
    public function test_cannot_login_with_wrong_password(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'success' => false,
        ]);
    }

    /**
     * Test email verification
     */
    public function test_user_can_verify_email(): void
    {
        $token = 'test-verification-token';
        $user = User::factory()->unverified()->create([
            'email' => 'test@example.com',
            'email_verification_token' => $token,
        ]);

        $response = $this->postJson('/api/auth/verify-email', [
            'token' => $token,
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Email berhasil diverifikasi! Anda sekarang dapat login.',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email_verified_at' => now()->format('Y-m-d'),
            'email_verification_token' => null,
        ]);
    }

    /**
     * Test invalid verification token
     */
    public function test_cannot_verify_with_invalid_token(): void
    {
        User::factory()->unverified()->create([
            'email' => 'test@example.com',
            'email_verification_token' => 'valid-token',
        ]);

        $response = $this->postJson('/api/auth/verify-email', [
            'token' => 'invalid-token',
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
        ]);
    }

    /**
     * Test get current user
     */
    public function test_can_get_current_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/auth/me');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'user' => ['id', 'name', 'email', 'role'],
        ]);
    }

    /**
     * Test logout
     */
    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/auth/logout');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Logout berhasil!',
        ]);
    }

    /**
     * Test update profile
     */
    public function test_user_can_update_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->putJson('/api/auth/profile', [
            'name' => 'Updated Name',
            'phone' => '089876543210',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Profil berhasil diperbarui!',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'phone' => '089876543210',
        ]);
    }
}
