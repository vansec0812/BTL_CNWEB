<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\LaoDong;
use App\Models\DoanhNghiep;
use App\Models\KetNoiViecLam;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class KetNoiViecLamTest extends TestCase
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

    public function test_index_page_renders_ket_noi_records(): void
    {
        $laoDongId = $this->createLaoDong('Nguyễn Văn Thất Nghiệp');
        $doanhNghiepId = $this->createDoanhNghiep('Doanh Nghiệp A');

        DB::table('ket_noi_viec_lam')->insert([
            'lao_dong_id' => $laoDongId,
            'doanh_nghiep_id' => $doanhNghiepId,
            'ngay_ket_noi' => '2026-06-06',
            'vi_tri_gioi_thieu' => 'Nhân viên kho',
            'ket_qua' => 'dang_cho_phan_hoi',
            'nguoi_phu_trach_id' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get(route('ket-noi.index'));

        $response->assertOk();
        $response->assertSee('Nguyễn Văn Thất Nghiệp');
        $response->assertSee('Doanh Nghiệp A');
        $response->assertSee('Nhân viên kho');
        $response->assertSee('Đang chờ phản hồi');
    }

    public function test_store_creates_connection_and_updates_labor_on_success(): void
    {
        $laoDongId = $this->createLaoDong('Trần Gia Bảo');
        $doanhNghiepId = $this->createDoanhNghiep('Xưởng May Bình Minh', 5); // 5 vị trí tuyển dụng

        $response = $this->post(route('ket-noi.store'), [
            'lao_dong_id' => $laoDongId,
            'doanh_nghiep_id' => $doanhNghiepId,
            'ngay_ket_noi' => '2026-06-06',
            'vi_tri_gioi_thieu' => 'Thợ may bậc 3',
            'ket_qua' => 'duoc_nhan', // Được nhận trực tiếp
        ]);

        $response->assertRedirect(route('ket-noi.index'));
        $this->assertDatabaseHas('ket_noi_viec_lam', [
            'lao_dong_id' => $laoDongId,
            'doanh_nghiep_id' => $doanhNghiepId,
            'ket_qua' => 'duoc_nhan',
        ]);

        // Kiểm tra xem vị trí tuyển dụng của doanh nghiệp có bị giảm không
        $this->assertDatabaseHas('doanh_nghiep_ho_kinh_doanh', [
            'id' => $doanhNghiepId,
            'so_vi_tri_tuyen_dung' => 4, // 5 - 1 = 4
            'so_lao_dong_hien_tai' => 1, // 0 + 1 = 1
        ]);

        // Kiểm tra xem người lao động có đổi trạng thái sang "co_viec_lam" không
        $this->assertDatabaseHas('lao_dong', [
            'id' => $laoDongId,
            'trang_thai_lao_dong' => 'co_viec_lam',
            'nghe_nghiep' => 'Thợ may bậc 3',
        ]);
    }

    private function createLaoDong(string $hoTen): int
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

        $nhanKhauId = DB::table('nhan_khau')->insertGetId([
            'ho_khau_id' => $hoKhauId,
            'ho_ten' => $hoTen,
            'cccd_cmnd' => (string) random_int(100000000000, 999999999999),
            'ngay_sinh' => '1995-01-01',
            'gioi_tinh' => 'nam',
            'dan_toc' => 'Kinh',
            'trinh_do_hoc_van' => 'dai_hoc',
            'tinh_trang_hon_nhan' => 'doc_than',
            'quan_he_chu_ho' => 'Chủ hộ',
            'la_chu_ho' => true,
            'co_tien_an' => false,
            'trang_thai' => 'hoat_dong',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('lao_dong')->insertGetId([
            'nhan_khau_id' => $nhanKhauId,
            'trang_thai_lao_dong' => 'that_nghiep',
            'nghe_nghiep' => null,
            'loai_hinh_cong_viec' => null,
            'nganh_nghe' => 'cong_nghiep_xay_dung',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createDoanhNghiep(string $ten, int $tuyenDung = 0): int
    {
        return DB::table('doanh_nghiep_ho_kinh_doanh')->insertGetId([
            'ten_co_so' => $ten,
            'loai_hinh' => 'cong_ty_tnhh',
            'nganh_nghe_chinh' => 'Sản xuất may mặc',
            'dia_chi' => 'Lô B, KCN Quốc Oai',
            'thon_xom' => 'Thôn Bình An',
            'trang_thai' => 'dang_hoat_dong',
            'so_lao_dong_hien_tai' => 0,
            'so_vi_tri_tuyen_dung' => $tuyenDung,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
