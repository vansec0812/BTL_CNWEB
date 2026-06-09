<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BieuDoTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * Test that an authenticated user can view the dashboard and charts.
     */
    public function test_authenticated_user_can_view_dashboard_and_charts(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('he-thong.dashboard-bieu-do'));

        $response->assertStatus(200)
            ->assertSee('Báo cáo')
            ->assertSee('Biểu đồ trực quan')
            ->assertSee('Tháp dân số xã')
            ->assertSee('Tỷ lệ hộ nghèo')
            ->assertSee('Xu hướng')
            ->assertSee('Hiện trạng lao động')
            ->assertSee('populationPyramidChart')
            ->assertSee('povertyPieChart')
            ->assertSee('laborTrendChart');
    }

    /**
     * Test that a guest user cannot view the dashboard and charts.
     */
    public function test_guest_user_cannot_view_dashboard_and_charts(): void
    {
        $response = $this->get(route('he-thong.dashboard-bieu-do'));

        $response->assertRedirect(route('login'));
    }
}
