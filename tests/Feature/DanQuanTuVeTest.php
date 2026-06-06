<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\NhanKhau;
use App\Models\DanQuanTuVe;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DanQuanTuVeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);

        $this->withoutMiddleware([
            Authenticate::class,
            Authorize::class,
        ]);
    }

    public function test_index_page_renders_militia_records(): void
    {
        $nhanKhauId = $this->createNhanKhau('Vũ Văn Long Dân Quân');
        DB::table('dan_quan_tu_ve')->insert([
            'nhan_khau_id' => $nhanKhauId,
            'chuc_vu' => 'Tiểu đội trưởng',
            'don_vi' => 'Tổ 1 Thôn 1',
            'ngay_gia_nhap' => '2026-06-01',
            'trang_thai' => 'dang_phuc_vu',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get(route('dan-quan-tu-ve.index'))
            ->assertOk()
            ->assertSee('Vũ Văn Long Dân Quân')
            ->assertSee('Tiểu đội trưởng');
    }

    public function test_store_creates_militia_record(): void
    {
        $nhanKhauId = $this->createNhanKhau('Vũ Văn Nam Dân Quân');

        $response = $this->post(route('dan-quan-tu-ve.store'), [
            'nhan_khau_id' => $nhanKhauId,
            'chuc_vu' => 'Chiến sĩ',
            'don_vi' => 'Tổ 2 Thôn 2',
            'ngay_gia_nhap' => '2026-06-01',
            'trang_thai' => 'dang_phuc_vu',
        ]);

        $response->assertRedirect(route('dan-quan-tu-ve.index'));
        $this->assertDatabaseHas('dan_quan_tu_ve', [
            'nhan_khau_id' => $nhanKhauId,
            'chuc_vu' => 'Chiến sĩ',
        ]);
    }

    public function test_store_bulk_creates_militia_records(): void
    {
        $nhanKhauId1 = $this->createNhanKhau('Lê Văn A Dân Quân');
        $nhanKhauId2 = $this->createNhanKhau('Lê Văn B Dân Quân');

        $response = $this->post(route('dan-quan-tu-ve.store'), [
            'nhan_khau_ids' => [$nhanKhauId1, $nhanKhauId2],
            'chuc_vu' => 'Chiến sĩ',
            'don_vi' => 'Tổ 3 Thôn 3',
            'ngay_gia_nhap' => '2026-06-01',
            'trang_thai' => 'dang_phuc_vu',
        ]);

        $response->assertRedirect(route('dan-quan-tu-ve.index'));
        $this->assertDatabaseHas('dan_quan_tu_ve', [
            'nhan_khau_id' => $nhanKhauId1,
            'chuc_vu' => 'Chiến sĩ',
        ]);
        $this->assertDatabaseHas('dan_quan_tu_ve', [
            'nhan_khau_id' => $nhanKhauId2,
            'chuc_vu' => 'Chiến sĩ',
        ]);
    }

    public function test_store_bulk_validation_fails_for_already_enrolled(): void
    {
        $nhanKhauId = $this->createNhanKhau('Đã Tham Gia Dân Quân');
        
        DB::table('dan_quan_tu_ve')->insert([
            'nhan_khau_id' => $nhanKhauId,
            'chuc_vu' => 'Chiến sĩ',
            'don_vi' => 'Tổ 1 Thôn 1',
            'ngay_gia_nhap' => '2026-06-01',
            'trang_thai' => 'dang_phuc_vu',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->post(route('dan-quan-tu-ve.store'), [
            'nhan_khau_ids' => [$nhanKhauId],
            'chuc_vu' => 'Chiến sĩ',
            'don_vi' => 'Tổ 1 Thôn 1',
            'ngay_gia_nhap' => '2026-06-01',
            'trang_thai' => 'dang_phuc_vu',
        ]);

        $response->assertSessionHasErrors(['nhan_khau_ids.0']);
    }

    public function test_store_fails_when_ngay_ket_thuc_is_not_after_ngay_gia_nhap(): void
    {
        $nhanKhauId = $this->createNhanKhau('Test Date Validation');

        // Test equal dates
        $response = $this->post(route('dan-quan-tu-ve.store'), [
            'nhan_khau_id' => $nhanKhauId,
            'chuc_vu' => 'Chiến sĩ',
            'don_vi' => 'Tổ 1 Thôn 1',
            'ngay_gia_nhap' => '2026-06-01',
            'ngay_ket_thuc' => '2026-06-01',
            'trang_thai' => 'dang_phuc_vu',
        ]);

        $response->assertSessionHasErrors(['ngay_ket_thuc']);

        // Test before dates
        $response = $this->post(route('dan-quan-tu-ve.store'), [
            'nhan_khau_id' => $nhanKhauId,
            'chuc_vu' => 'Chiến sĩ',
            'don_vi' => 'Tổ 1 Thôn 1',
            'ngay_gia_nhap' => '2026-06-01',
            'ngay_ket_thuc' => '2026-05-31',
            'trang_thai' => 'dang_phuc_vu',
        ]);

        $response->assertSessionHasErrors(['ngay_ket_thuc']);
    }

    public function test_create_excludes_enlisted_military_citizens(): void
    {
        $eligibleId = $this->createNhanKhau('Eligible Citizen');
        $enlistedId = $this->createNhanKhau('Enlisted Soldier');
        $selectedId = $this->createNhanKhau('Selected Soldier');

        // Gán nghĩa vụ quân sự
        DB::table('nghia_vu_quan_su')->insert([
            [
                'nhan_khau_id' => $enlistedId,
                'trang_thai_nvqs' => 'da_nhap_ngu',
                'ly_do_tam_hoan' => 'khong_ap_dung',
                'ket_qua_kham_suc_khoe' => 'chua_kham',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nhan_khau_id' => $selectedId,
                'trang_thai_nvqs' => 'trung_tuyen',
                'ly_do_tam_hoan' => 'khong_ap_dung',
                'ket_qua_kham_suc_khoe' => 'chua_kham',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        $response = $this->getJson(route('dan-quan-tu-ve.create'));
        $response->assertOk();
        
        $nhanKhauData = $response->json('data.nhanKhau');
        $nhanKhauIds = collect($nhanKhauData)->pluck('id')->all();

        $this->assertContains($eligibleId, $nhanKhauIds);
        $this->assertNotContains($enlistedId, $nhanKhauIds);
        $this->assertNotContains($selectedId, $nhanKhauIds);
    }

    public function test_api_index_returns_json(): void
    {
        $nhanKhauId = $this->createNhanKhau('Vũ Văn Long Dân Quân API');
        DB::table('dan_quan_tu_ve')->insert([
            'nhan_khau_id' => $nhanKhauId,
            'chuc_vu' => 'Chiến sĩ',
            'don_vi' => 'Tổ 1 Thôn 1',
            'ngay_gia_nhap' => '2026-06-01',
            'trang_thai' => 'dang_phuc_vu',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson(route('api.dan-quan-tu-ve.index'));
        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'nhan_khau_id',
                            'chuc_vu',
                        ]
                    ]
                ]
            ]);
    }

    private function createNhanKhau(string $hoTen): int
    {
        $hoKhauId = DB::table('ho_khau')->insertGetId([
            'so_so_ho_khau' => 'HK'.str()->random(8),
            'ma_ho' => 'MH'.str()->random(8),
            'dia_chi_thuong_tru' => 'Thôn 1, Xã Quốc Oai',
            'thon_xom' => 'Thôn 1',
            'phan_loai' => 'thuong_tru',
            'so_thanh_vien' => 1,
            'trang_thai' => 'hoat_dong',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('nhan_khau')->insertGetId([
            'ho_khau_id' => $hoKhauId,
            'ho_ten' => $hoTen,
            'cccd_cmnd' => (string) random_int(100000000000, 999999999999),
            'ngay_sinh' => '2000-01-01',
            'gioi_tinh' => 'nam',
            'dan_toc' => 'Kinh',
            'trinh_do_hoc_van' => 'thpt',
            'tinh_trang_hon_nhan' => 'doc_than',
            'quan_he_chu_ho' => 'Chủ hộ',
            'la_chu_ho' => true,
            'co_tien_an' => false,
            'trang_thai' => 'hoat_dong',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
