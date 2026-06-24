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
     * Test that an authenticated user can get dashboard statistics via JSON API.
     */
    public function test_authenticated_user_can_get_dashboard_data_via_json_api(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('he-thong.dashboard-bieu-do'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'pyramid' => [
                        'labels',
                        'male',
                        'female',
                    ],
                    'poverty' => [
                        'ho_ngheo',
                        'ho_can_ngheo',
                        'ho_binh_thuong',
                        'tong_so_ho',
                    ],
                    'labor' => [
                        'labels',
                        'values',
                    ],
                    'metrics' => [
                        'tong_nhan_khau',
                        'tong_lao_dong',
                        'tong_doanh_nghiep',
                        'tuoi_trung_binh',
                    ],
                ],
            ])
            ->assertJsonPath('success', true);
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
