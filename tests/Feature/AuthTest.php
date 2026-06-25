<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test active user can login.
     */
    public function test_active_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'active@example.com',
            'trang_thai' => 'active',
        ]);

        $response = $this->post('/login', [
            'email' => 'active@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test inactive/locked user cannot login via web interface.
     */
    public function test_inactive_user_cannot_login_web(): void
    {
        $user = User::factory()->create([
            'email' => 'inactive@example.com',
            'trang_thai' => 'inactive',
        ]);

        $response = $this->post('/login', [
            'email' => 'inactive@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test inactive/locked user cannot login via API.
     */
    public function test_inactive_user_cannot_login_api(): void
    {
        $user = User::factory()->create([
            'email' => 'inactive_api@example.com',
            'trang_thai' => 'inactive',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'inactive_api@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'Tài khoản của bạn đã bị khóa hoặc ngừng hoạt động.',
        ]);
        $this->assertGuest();
    }

    /**
     * Test user can change password with correct current password.
     */
    public function test_user_can_change_password_with_correct_current_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('old-password'),
        ]);

        $response = $this->actingAs($user)->post('/he-thong/users/change-password', [
            'current_password' => 'old-password',
            'new_password' => 'new-secure-password',
            'new_password_confirmation' => 'new-secure-password',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertTrue(\Hash::check('new-secure-password', $user->fresh()->password));
    }

    /**
     * Test user cannot change password with incorrect current password.
     */
    public function test_user_cannot_change_password_with_incorrect_current_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('old-password'),
        ]);

        $response = $this->actingAs($user)->post('/he-thong/users/change-password', [
            'current_password' => 'wrong-old-password',
            'new_password' => 'new-secure-password',
            'new_password_confirmation' => 'new-secure-password',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertFalse(\Hash::check('new-secure-password', $user->fresh()->password));
    }

    /**
     * Test user cannot change password with validation failures (e.g. short password, mismatched confirmation).
     */
    public function test_user_cannot_change_password_with_validation_failures(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('old-password'),
        ]);

        // Mismatched confirmation
        $response = $this->actingAs($user)->post('/he-thong/users/change-password', [
            'current_password' => 'old-password',
            'new_password' => 'new-password',
            'new_password_confirmation' => 'different-password',
        ]);

        $response->assertSessionHasErrors('new_password');

        // Short password
        $response = $this->actingAs($user)->post('/he-thong/users/change-password', [
            'current_password' => 'old-password',
            'new_password' => '123',
            'new_password_confirmation' => '123',
        ]);

        $response->assertSessionHasErrors('new_password');
    }
}
