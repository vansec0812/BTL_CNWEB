<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BaoTroXaHoiTest extends TestCase
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

    public function test_index_page_renders_household_and_person_records(): void
    {
        $hoKhauId = $this->createHoKhau('HK-BT-001');
        $nhanKhauId = $this->createNhanKhau('Võ Thị Bảo Trợ');

        DB::table('bao_tro_xa_hoi')->insert([
            [
                'ho_khau_id' => $hoKhauId,
                'nhan_khau_id' => null,
                'loai_bao_tro' => 'ho_ngheo',
                'muc_do_khuyet_tat' => 'khong_ap_dung',
                'so_quyet_dinh' => 'QD-BT-HO',
                'trang_thai' => 'dang_huong',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ho_khau_id' => null,
                'nhan_khau_id' => $nhanKhauId,
                'loai_bao_tro' => 'nguoi_gia_neo_don',
                'muc_do_khuyet_tat' => 'khong_ap_dung',
                'so_quyet_dinh' => 'QD-BT-CN',
                'trang_thai' => 'dang_huong',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->get(route('bao-tro-xa-hoi.index'))
            ->assertOk()
            ->assertSee('Quản lý Đối tượng bảo trợ xã hội')
            ->assertSee('Hộ khẩu HK-BT-001')
            ->assertSee('Võ Thị Bảo Trợ')
            ->assertSee('QD-BT-HO')
            ->assertSee('QD-BT-CN');
    }

    public function test_create_page_lists_active_households_and_people_only(): void
    {
        $this->createHoKhau('HK-ACTIVE');
        $inactiveHoKhauId = $this->createHoKhau('HK-INACTIVE', 'da_giai_the');
        $this->createNhanKhau('Người Đang Hoạt Động');
        $this->createNhanKhau('Người Đã Mất', 'da_mat');

        $this->get(route('bao-tro-xa-hoi.create'))
            ->assertOk()
            ->assertSee('Thêm hồ sơ bảo trợ xã hội')
            ->assertSee('HK-ACTIVE')
            ->assertSee('Người Đang Hoạt Động')
            ->assertDontSee('HK-INACTIVE')
            ->assertDontSee('Người Đã Mất');

        DB::table('ho_khau')->where('id', $inactiveHoKhauId)->update(['trang_thai' => 'hoat_dong']);
    }

    public function test_edit_page_keeps_current_inactive_household(): void
    {
        $hoKhauId = $this->createHoKhau('HK-CURRENT');
        $recordId = $this->createHouseholdSupport($hoKhauId, 'QD-CURRENT');
        DB::table('ho_khau')->where('id', $hoKhauId)->update(['trang_thai' => 'da_giai_the']);

        $this->get(route('bao-tro-xa-hoi.edit', $recordId))
            ->assertOk()
            ->assertSee('HK-CURRENT')
            ->assertSee('QD-CURRENT');
    }

    public function test_index_filters_support_records(): void
    {
        $matchingHoKhauId = $this->createHoKhau('HK-FILTER-OK');
        $otherHoKhauId = $this->createHoKhau('HK-FILTER-NO');

        $this->createHouseholdSupport($matchingHoKhauId, 'QD-FILTER-OK', 'ho_ngheo', 'dang_huong');
        $this->createHouseholdSupport($otherHoKhauId, 'QD-FILTER-NO', 'ho_can_ngheo', 'tam_ngung');

        $this->get(route('bao-tro-xa-hoi.index', [
            'q' => 'FILTER-OK',
            'loai_bao_tro' => 'ho_ngheo',
            'trang_thai' => 'dang_huong',
            'doi_tuong' => 'ho_khau',
        ]))
            ->assertOk()
            ->assertSee('QD-FILTER-OK')
            ->assertDontSee('QD-FILTER-NO');
    }

    public function test_store_requires_correct_target_for_support_type(): void
    {
        $hoKhauId = $this->createHoKhau('HK-WRONG');
        $nhanKhauId = $this->createNhanKhau('Người Sai Nhóm');

        $this->post(route('bao-tro-xa-hoi.store'), [
            'loai_bao_tro' => 'ho_ngheo',
            'nhan_khau_id' => $nhanKhauId,
            'muc_do_khuyet_tat' => 'khong_ap_dung',
            'trang_thai' => 'dang_huong',
        ])->assertSessionHasErrors(['ho_khau_id']);

        $this->post(route('bao-tro-xa-hoi.store'), [
            'loai_bao_tro' => 'nguoi_gia_neo_don',
            'ho_khau_id' => $hoKhauId,
            'muc_do_khuyet_tat' => 'khong_ap_dung',
            'trang_thai' => 'dang_huong',
        ])->assertSessionHasErrors(['nhan_khau_id']);
    }

    public function test_store_rejects_two_targets_and_invalid_disability_fields(): void
    {
        $hoKhauId = $this->createHoKhau('HK-TWO');
        $nhanKhauId = $this->createNhanKhau('Người Hai Đích');

        $this->post(route('bao-tro-xa-hoi.store'), [
            'loai_bao_tro' => 'nguoi_khuyet_tat',
            'ho_khau_id' => $hoKhauId,
            'nhan_khau_id' => $nhanKhauId,
            'muc_do_khuyet_tat' => 'khong_ap_dung',
            'trang_thai' => 'dang_huong',
        ])->assertSessionHasErrors(['ho_khau_id', 'nhan_khau_id', 'muc_do_khuyet_tat', 'dang_khuyet_tat']);
    }

    public function test_store_creates_household_support_record(): void
    {
        $hoKhauId = $this->createHoKhau('HK-CREATE');

        $response = $this->post(route('bao-tro-xa-hoi.store'), [
            'loai_bao_tro' => 'ho_ngheo',
            'ho_khau_id' => $hoKhauId,
            'muc_do_khuyet_tat' => 'nang',
            'dang_khuyet_tat' => 'Vận động',
            'so_quyet_dinh' => 'QD-CREATE-HO',
            'ngay_bat_dau_huong' => '2024-01-01',
            'muc_tro_cap_hang_thang' => 500000,
            'trang_thai' => 'dang_huong',
        ]);

        $response->assertRedirect(route('bao-tro-xa-hoi.index'));
        $this->assertDatabaseHas('bao_tro_xa_hoi', [
            'ho_khau_id' => $hoKhauId,
            'nhan_khau_id' => null,
            'loai_bao_tro' => 'ho_ngheo',
            'muc_do_khuyet_tat' => 'khong_ap_dung',
            'dang_khuyet_tat' => null,
            'so_quyet_dinh' => 'QD-CREATE-HO',
        ]);
    }

    public function test_store_creates_person_disability_support_record(): void
    {
        $nhanKhauId = $this->createNhanKhau('Người Khuyết Tật');

        $this->post(route('bao-tro-xa-hoi.store'), [
            'loai_bao_tro' => 'nguoi_khuyet_tat',
            'nhan_khau_id' => $nhanKhauId,
            'muc_do_khuyet_tat' => 'nang',
            'dang_khuyet_tat' => 'Vận động',
            'so_quyet_dinh' => 'QD-CREATE-CN',
            'trang_thai' => 'dang_huong',
        ])->assertRedirect(route('bao-tro-xa-hoi.index'));

        $this->assertDatabaseHas('bao_tro_xa_hoi', [
            'ho_khau_id' => null,
            'nhan_khau_id' => $nhanKhauId,
            'loai_bao_tro' => 'nguoi_khuyet_tat',
            'muc_do_khuyet_tat' => 'nang',
            'dang_khuyet_tat' => 'Vận động',
        ]);
    }

    public function test_update_and_destroy_support_record(): void
    {
        $hoKhauId = $this->createHoKhau('HK-UPDATE');
        $recordId = $this->createHouseholdSupport($hoKhauId, 'QD-OLD');

        $this->put(route('bao-tro-xa-hoi.update', $recordId), [
            'loai_bao_tro' => 'ho_can_ngheo',
            'ho_khau_id' => $hoKhauId,
            'muc_do_khuyet_tat' => 'khong_ap_dung',
            'so_quyet_dinh' => 'QD-NEW',
            'muc_tro_cap_hang_thang' => 300000,
            'trang_thai' => 'tam_ngung',
        ])->assertRedirect(route('bao-tro-xa-hoi.index'));

        $this->assertDatabaseHas('bao_tro_xa_hoi', [
            'id' => $recordId,
            'loai_bao_tro' => 'ho_can_ngheo',
            'so_quyet_dinh' => 'QD-NEW',
            'trang_thai' => 'tam_ngung',
        ]);

        $this->delete(route('bao-tro-xa-hoi.destroy', $recordId))
            ->assertRedirect(route('bao-tro-xa-hoi.index'));

        $this->assertSoftDeleted('bao_tro_xa_hoi', ['id' => $recordId]);
    }

    private function createHouseholdSupport(string|int $hoKhauId, string $decision, string $type = 'ho_ngheo', string $status = 'dang_huong'): int
    {
        return DB::table('bao_tro_xa_hoi')->insertGetId([
            'ho_khau_id' => $hoKhauId,
            'nhan_khau_id' => null,
            'loai_bao_tro' => $type,
            'muc_do_khuyet_tat' => 'khong_ap_dung',
            'so_quyet_dinh' => $decision,
            'trang_thai' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createNhanKhau(string $hoTen, string $trangThai = 'hoat_dong'): int
    {
        $hoKhauId = $this->createHoKhau('HK'.str()->random(8));

        return DB::table('nhan_khau')->insertGetId([
            'ho_khau_id' => $hoKhauId,
            'ho_ten' => $hoTen,
            'cccd_cmnd' => (string) random_int(100000000000, 999999999999),
            'ngay_sinh' => '1970-01-01',
            'gioi_tinh' => 'nam',
            'dan_toc' => 'Kinh',
            'trinh_do_hoc_van' => 'thpt',
            'tinh_trang_hon_nhan' => 'doc_than',
            'quan_he_chu_ho' => 'Chủ hộ',
            'la_chu_ho' => true,
            'co_tien_an' => false,
            'trang_thai' => $trangThai,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createHoKhau(string $soSoHoKhau, string $trangThai = 'hoat_dong'): int
    {
        return DB::table('ho_khau')->insertGetId([
            'so_so_ho_khau' => $soSoHoKhau,
            'ma_ho' => 'MH'.str()->random(8),
            'dia_chi_thuong_tru' => 'Thôn 1, Xã Quốc Oai',
            'thon_xom' => 'Thôn 1',
            'phan_loai' => 'thuong_tru',
            'so_thanh_vien' => 1,
            'trang_thai' => $trangThai,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
