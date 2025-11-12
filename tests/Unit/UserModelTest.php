<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user email verification check
     */
    public function test_email_verified_method(): void
    {
        $verifiedUser = User::factory()->create(['email_verified_at' => now()]);
        $unverifiedUser = User::factory()->unverified()->create();

        $this->assertTrue($verifiedUser->isEmailVerified());
        $this->assertFalse($unverifiedUser->isEmailVerified());
    }

    /**
     * Test user role methods
     */
    public function test_user_role_methods(): void
    {
        $seller = User::factory()->seller()->create();
        $buyer = User::factory()->buyer()->create();

        $this->assertTrue($seller->isSeller());
        $this->assertFalse($seller->isBuyer());

        $this->assertTrue($buyer->isBuyer());
        $this->assertFalse($buyer->isSeller());
    }

    /**
     * Test update last login
     */
    public function test_update_last_login(): void
    {
        $user = User::factory()->create(['last_login_at' => null]);

        $this->assertNull($user->last_login_at);

        $user->updateLastLogin();
        $user->refresh();

        $this->assertNotNull($user->last_login_at);
    }
}
