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
}
