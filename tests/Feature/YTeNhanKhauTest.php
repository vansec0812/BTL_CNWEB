<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class YTeNhanKhauTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());

        $this->withoutMiddleware([
            Authenticate::class,
            Authorize::class,
        ]);
    }

    // ============================================================
    // Blade (Web) Tests
    // ============================================================

    public function test_index_page_renders_health_records(): void
    {
        $nkId = $this->createNhanKhau('Nguyễn Thị Y Tế');

        DB::table('y_te_nhan_khau')->insert([
            'nhan_khau_id'                  => $nkId,
            'so_the_bhyt'                   => 'DN4001111111111',
            'loai_bhyt'                     => 'bat_buoc',
            'ngay_cap_the_bhyt'             => '2022-01-01',
            'ngay_het_han_the_bhyt'         => '2026-12-31',
            'noi_dang_ky_kham_chua_benh'    => 'Trạm y tế xã An Sinh',
            'hoan_thanh_tiem_chung_mo_rong' => false,
            'lich_su_tiem_chung'            => null,
            'ghi_chu_suc_khoe'              => null,
            'created_at'                    => now(),
            'updated_at'                    => now(),
        ]);

        $this->get(route('y-te-nhan-khau.index'))
            ->assertOk()
            ->assertSeeText('Hồ sơ Y tế & Bảo hiểm y tế', false)
            ->assertSee('Nguyễn Thị Y Tế')
            ->assertSee('DN4001111111111');
    }

    public function test_index_stats_show_correct_counts(): void
    {
        $nk1 = $this->createNhanKhau('Người Có BHYT');
        $nk2 = $this->createNhanKhau('Người Không BHYT');

        DB::table('y_te_nhan_khau')->insert([
            'nhan_khau_id'                  => $nk1,
            'loai_bhyt'                     => 'tu_nguyen',
            'ngay_het_han_the_bhyt'         => now()->addYear(),
            'hoan_thanh_tiem_chung_mo_rong' => true,
            'created_at'                    => now(),
            'updated_at'                    => now(),
        ]);
        DB::table('y_te_nhan_khau')->insert([
            'nhan_khau_id'                  => $nk2,
            'loai_bhyt'                     => 'khong_co',
            'hoan_thanh_tiem_chung_mo_rong' => false,
            'created_at'                    => now(),
            'updated_at'                    => now(),
        ]);

        $this->get(route('y-te-nhan-khau.index'))
            ->assertOk()
            ->assertSee('Hoàn thành tiêm chủng');
    }

    public function test_create_page_lists_only_nhan_khau_without_existing_record(): void
    {
        $freeNk = $this->createNhanKhau('Chưa Có Hồ Sơ');
        $takenNkId = $this->createNhanKhau('Đã Có Hồ Sơ');

        DB::table('y_te_nhan_khau')->insert([
            'nhan_khau_id'                  => $takenNkId,
            'loai_bhyt'                     => 'khong_co',
            'hoan_thanh_tiem_chung_mo_rong' => false,
            'created_at'                    => now(),
            'updated_at'                    => now(),
        ]);

        $this->get(route('y-te-nhan-khau.create'))
            ->assertOk()
            ->assertSee('Thêm hồ sơ y tế')
            ->assertSee('Chưa Có Hồ Sơ')
            ->assertDontSee('Đã Có Hồ Sơ');
    }

    public function test_store_creates_record_and_redirects(): void
    {
        $nkId = $this->createNhanKhau('Người Mới Tạo');

        $this->post(route('y-te-nhan-khau.store'), [
            'nhan_khau_id'                  => $nkId,
            'loai_bhyt'                     => 'tu_nguyen',
            'so_the_bhyt'                   => 'DN4009988776655',
            'ngay_cap_the_bhyt'             => '2023-01-01',
            'ngay_het_han_the_bhyt'         => '2025-12-31',
            'noi_dang_ky_kham_chua_benh'    => 'Bệnh viện huyện',
            'hoan_thanh_tiem_chung_mo_rong' => '1',
            'ghi_chu_suc_khoe'              => 'Không có bệnh nền',
        ])->assertRedirect(route('y-te-nhan-khau.index'));

        $this->assertDatabaseHas('y_te_nhan_khau', [
            'nhan_khau_id' => $nkId,
            'loai_bhyt'    => 'tu_nguyen',
            'so_the_bhyt'  => 'DN4009988776655',
        ]);
    }

    public function test_store_clears_bhyt_fields_when_loai_is_khong_co(): void
    {
        $nkId = $this->createNhanKhau('Người Không Thẻ');

        $this->post(route('y-te-nhan-khau.store'), [
            'nhan_khau_id'                  => $nkId,
            'loai_bhyt'                     => 'khong_co',
            'so_the_bhyt'                   => 'IGNORE_ME',
            'ngay_cap_the_bhyt'             => '2023-01-01',
            'hoan_thanh_tiem_chung_mo_rong' => '0',
        ])->assertRedirect(route('y-te-nhan-khau.index'));

        $this->assertDatabaseHas('y_te_nhan_khau', [
            'nhan_khau_id' => $nkId,
            'loai_bhyt'    => 'khong_co',
            'so_the_bhyt'  => null,
        ]);
    }

    public function test_store_fails_when_nhan_khau_already_has_record(): void
    {
        $nkId = $this->createNhanKhau('Đã Có Hồ Sơ Rồi');

        DB::table('y_te_nhan_khau')->insert([
            'nhan_khau_id'                  => $nkId,
            'loai_bhyt'                     => 'khong_co',
            'hoan_thanh_tiem_chung_mo_rong' => false,
            'created_at'                    => now(),
            'updated_at'                    => now(),
        ]);

        $this->post(route('y-te-nhan-khau.store'), [
            'nhan_khau_id'                  => $nkId,
            'loai_bhyt'                     => 'tu_nguyen',
            'hoan_thanh_tiem_chung_mo_rong' => '0',
        ])->assertSessionHasErrors('nhan_khau_id');
    }

    public function test_show_page_renders_record_detail(): void
    {
        $nkId = $this->createNhanKhau('Nguyễn Xem Hồ Sơ');
        $id = $this->createYTeRecord($nkId, 'bat_buoc', 'DN4001234567890');

        $this->get(route('y-te-nhan-khau.show', $id))
            ->assertOk()
            ->assertSee('Chi tiết hồ sơ y tế')
            ->assertSee('Nguyễn Xem Hồ Sơ')
            ->assertSee('DN4001234567890');
    }

    public function test_edit_page_includes_current_nhan_khau(): void
    {
        $nkId = $this->createNhanKhau('Người Sửa Hồ Sơ');
        $id = $this->createYTeRecord($nkId);

        $this->get(route('y-te-nhan-khau.edit', $id))
            ->assertOk()
            ->assertSee('Chỉnh sửa hồ sơ y tế')
            ->assertSee('Người Sửa Hồ Sơ');
    }

    public function test_update_changes_record(): void
    {
        $nkId = $this->createNhanKhau('Người Cập Nhật');
        $id = $this->createYTeRecord($nkId, 'khong_co');

        $this->put(route('y-te-nhan-khau.update', $id), [
            'nhan_khau_id'                  => $nkId,
            'loai_bhyt'                     => 'ho_ngheo',
            'so_the_bhyt'                   => 'DN4009900112233',
            'hoan_thanh_tiem_chung_mo_rong' => '1',
        ])->assertRedirect(route('y-te-nhan-khau.index'));

        $this->assertDatabaseHas('y_te_nhan_khau', [
            'id'           => $id,
            'loai_bhyt'    => 'ho_ngheo',
            'so_the_bhyt'  => 'DN4009900112233',
        ]);
    }

    public function test_update_can_uncheck_tiem_chung(): void
    {
        $nkId = $this->createNhanKhau('Người Tắt Tiêm Chủng');

        $id = DB::table('y_te_nhan_khau')->insertGetId([
            'nhan_khau_id'                  => $nkId,
            'loai_bhyt'                     => 'khong_co',
            'hoan_thanh_tiem_chung_mo_rong' => true,
            'created_at'                    => now(),
            'updated_at'                    => now(),
        ]);

        $this->put(route('y-te-nhan-khau.update', $id), [
            'nhan_khau_id' => $nkId,
            'loai_bhyt'    => 'khong_co',
        ])->assertRedirect(route('y-te-nhan-khau.index'));

        $this->assertDatabaseHas('y_te_nhan_khau', [
            'id'                            => $id,
            'hoan_thanh_tiem_chung_mo_rong' => false,
        ]);
    }

    public function test_destroy_deletes_record(): void
    {
        $nkId = $this->createNhanKhau('Người Bị Xóa');
        $id = $this->createYTeRecord($nkId);

        $this->delete(route('y-te-nhan-khau.destroy', $id))
            ->assertRedirect(route('y-te-nhan-khau.index'));

        $this->assertDatabaseMissing('y_te_nhan_khau', ['id' => $id]);
    }

    // ============================================================
    // JSON API Tests
    // ============================================================

    public function test_api_index_returns_json(): void
    {
        $nkId = $this->createNhanKhau('API Nhân Khẩu');
        $this->createYTeRecord($nkId, 'bat_buoc');

        $this->getJson(route('api.y-te-nhan-khau.index'))
            ->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['data'],
                'stats' => ['tong_so', 'co_bhyt', 'het_han', 'da_tiem_chung'],
            ]);
    }

    public function test_api_index_filters_by_loai_bhyt(): void
    {
        $nk1 = $this->createNhanKhau('Người Bắt Buộc');
        $nk2 = $this->createNhanKhau('Người Tự Nguyện');
        $this->createYTeRecord($nk1, 'bat_buoc');
        $this->createYTeRecord($nk2, 'tu_nguyen');

        $this->getJson(route('api.y-te-nhan-khau.index', ['loai_bhyt' => 'bat_buoc']))
            ->assertOk()
            ->assertJsonFragment(['loai_bhyt' => 'bat_buoc'])
            ->assertJsonMissing(['loai_bhyt' => 'tu_nguyen']);
    }

    public function test_api_show_returns_json_with_nhan_khau(): void
    {
        $nkId = $this->createNhanKhau('Người API Show');
        $id = $this->createYTeRecord($nkId, 'tu_nguyen', 'DN4001122334455');

        $this->getJson(route('api.y-te-nhan-khau.show', $id))
            ->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'nhan_khau_id', 'loai_bhyt', 'nhan_khau'],
            ])
            ->assertJsonFragment(['so_the_bhyt' => 'DN4001122334455']);
    }

    public function test_api_store_creates_and_returns_201(): void
    {
        $nkId = $this->createNhanKhau('API Tạo Mới');

        $this->postJson(route('api.y-te-nhan-khau.store'), [
            'nhan_khau_id'                  => $nkId,
            'loai_bhyt'                     => 'chinh_sach',
            'so_the_bhyt'                   => 'DN4005544332211',
            'hoan_thanh_tiem_chung_mo_rong' => false,
        ])->assertCreated()
          ->assertJsonStructure(['success', 'message', 'data' => ['id', 'loai_bhyt']]);

        $this->assertDatabaseHas('y_te_nhan_khau', [
            'nhan_khau_id' => $nkId,
            'loai_bhyt'    => 'chinh_sach',
        ]);
    }

    public function test_api_store_returns_422_for_duplicate_nhan_khau(): void
    {
        $nkId = $this->createNhanKhau('Người Bị Trùng');
        $this->createYTeRecord($nkId);

        $this->postJson(route('api.y-te-nhan-khau.store'), [
            'nhan_khau_id'                  => $nkId,
            'loai_bhyt'                     => 'khong_co',
            'hoan_thanh_tiem_chung_mo_rong' => false,
        ])->assertStatus(422)
          ->assertJsonValidationErrors(['nhan_khau_id']);
    }

    public function test_api_update_changes_record_and_returns_200(): void
    {
        $nkId = $this->createNhanKhau('Người API Sửa');
        $id = $this->createYTeRecord($nkId, 'khong_co');

        $this->putJson(route('api.y-te-nhan-khau.update', $id), [
            'nhan_khau_id'                  => $nkId,
            'loai_bhyt'                     => 'tu_nguyen',
            'so_the_bhyt'                   => 'DN4001231231231',
            'hoan_thanh_tiem_chung_mo_rong' => true,
        ])->assertOk()
          ->assertJsonFragment(['loai_bhyt' => 'tu_nguyen']);

        $this->assertDatabaseHas('y_te_nhan_khau', [
            'id'          => $id,
            'loai_bhyt'   => 'tu_nguyen',
            'so_the_bhyt' => 'DN4001231231231',
        ]);
    }

    public function test_api_destroy_deletes_record_and_returns_200(): void
    {
        $nkId = $this->createNhanKhau('Người API Xóa');
        $id = $this->createYTeRecord($nkId);

        $this->deleteJson(route('api.y-te-nhan-khau.destroy', $id))
            ->assertOk()
            ->assertJsonStructure(['success', 'message']);

        $this->assertDatabaseMissing('y_te_nhan_khau', ['id' => $id]);
    }

    // ============================================================
    // Helpers
    // ============================================================

    private function createYTeRecord(
        int $nhanKhauId,
        string $loaiBhyt = 'khong_co',
        ?string $soTheBhyt = null
    ): int {
        return DB::table('y_te_nhan_khau')->insertGetId([
            'nhan_khau_id'                  => $nhanKhauId,
            'so_the_bhyt'                   => $soTheBhyt,
            'loai_bhyt'                     => $loaiBhyt,
            'hoan_thanh_tiem_chung_mo_rong' => false,
            'created_at'                    => now(),
            'updated_at'                    => now(),
        ]);
    }

    private function createNhanKhau(string $hoTen, string $trangThai = 'hoat_dong'): int
    {
        $hoKhauId = $this->createHoKhau('HK'.str()->random(8));

        return DB::table('nhan_khau')->insertGetId([
            'ho_khau_id'        => $hoKhauId,
            'ho_ten'            => $hoTen,
            'cccd_cmnd'         => (string) random_int(100000000000, 999999999999),
            'ngay_sinh'         => '1985-06-15',
            'gioi_tinh'         => 'nam',
            'dan_toc'           => 'Kinh',
            'trinh_do_hoc_van'  => 'thpt',
            'tinh_trang_hon_nhan' => 'doc_than',
            'quan_he_chu_ho'    => 'Chủ hộ',
            'la_chu_ho'         => true,
            'co_tien_an'        => false,
            'trang_thai'        => $trangThai,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
    }

    private function createHoKhau(string $soSoHoKhau, string $trangThai = 'hoat_dong'): int
    {
        return DB::table('ho_khau')->insertGetId([
            'so_so_ho_khau'      => $soSoHoKhau,
            'ma_ho'              => 'MH'.str()->random(8),
            'dia_chi_thuong_tru' => 'Thôn 2, Xã An Sinh',
            'thon_xom'           => 'Thôn 2',
            'phan_loai'          => 'thuong_tru',
            'so_thanh_vien'      => 1,
            'trang_thai'         => $trangThai,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);
    }
}
