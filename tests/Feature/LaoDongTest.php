<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LaoDongTest extends TestCase
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

    public function test_index_page_renders_labor_records(): void
    {
        $nhanKhauId = $this->createNhanKhau('Nguyễn Văn A');

        DB::table('lao_dong')->insert([
            'nhan_khau_id' => $nhanKhauId,
            'trang_thai_lao_dong' => 'co_viec_lam',
            'nghe_nghiep' => 'Lập trình viên',
            'loai_hinh_cong_viec' => 'tu_nhan',
            'nganh_nghe' => 'dich_vu_thuong_mai',
            'lam_viec_ngoai_tinh' => false,
            'xuat_khau_lao_dong' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get(route('ho-so.index'));

        $response->assertOk();
        $response->assertSee('Nguyễn Văn A');
        $response->assertSee('Lập trình viên');
        $response->assertSee('Có việc làm');
    }

    public function test_store_creates_labor_record_and_initial_history(): void
    {
        $nhanKhauId = $this->createNhanKhau('Trần Thị B');

        $response = $this->post(route('ho-so.store'), [
            'nhan_khau_id' => $nhanKhauId,
            'trang_thai_lao_dong' => 'co_viec_lam',
            'nghe_nghiep' => 'Kế toán',
            'loai_hinh_cong_viec' => 'tu_nhan',
            'nganh_nghe' => 'dich_vu_thuong_mai',
            'lam_viec_ngoai_tinh' => false,
            'xuat_khau_lao_dong' => false,
        ]);

        $response->assertRedirect(route('ho-so.index'));
        $this->assertDatabaseHas('lao_dong', [
            'nhan_khau_id' => $nhanKhauId,
            'nghe_nghiep' => 'Kế toán',
        ]);

        // Kiểm tra xem có tạo lịch sử công việc ban đầu không
        $this->assertDatabaseHas('lich_su_cong_viec', [
            'ten_cong_viec_cu' => 'Bắt đầu tạo hồ sơ',
            'ten_cong_viec_moi' => 'Kế toán',
        ]);
    }

    public function test_update_creates_history_record_on_change(): void
    {
        $nhanKhauId = $this->createNhanKhau('Lê Văn C');
        $laoDongId = DB::table('lao_dong')->insertGetId([
            'nhan_khau_id' => $nhanKhauId,
            'trang_thai_lao_dong' => 'co_viec_lam',
            'nghe_nghiep' => 'Công nhân may',
            'loai_hinh_cong_viec' => 'tu_nhan',
            'nganh_nghe' => 'cong_nghiep_xay_dung',
            'lam_viec_ngoai_tinh' => false,
            'xuat_khau_lao_dong' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->put(route('ho-so.update', $laoDongId), [
            'nhan_khau_id' => $nhanKhauId,
            'trang_thai_lao_dong' => 'co_viec_lam',
            'nghe_nghiep' => 'Kỹ sư cầu đường', // thay đổi công việc
            'loai_hinh_cong_viec' => 'tu_nhan',
            'nganh_nghe' => 'cong_nghiep_xay_dung',
            'lam_viec_ngoai_tinh' => false,
            'xuat_khau_lao_dong' => false,
            'ly_do_thay_doi' => 'Lên chức',
        ]);

        $response->assertRedirect(route('ho-so.index'));
        $this->assertDatabaseHas('lao_dong', [
            'id' => $laoDongId,
            'nghe_nghiep' => 'Kỹ sư cầu đường',
        ]);

        // Kiểm tra xem có tạo lịch sử công việc mới không
        $this->assertDatabaseHas('lich_su_cong_viec', [
            'lao_dong_id' => $laoDongId,
            'ten_cong_viec_cu' => 'Công nhân may',
            'ten_cong_viec_moi' => 'Kỹ sư cầu đường',
            'ly_do_thay_doi' => 'Lên chức',
        ]);
    }

    public function test_destroy_deletes_labor_record(): void
    {
        $nhanKhauId = $this->createNhanKhau('Phạm Văn D');
        $laoDongId = DB::table('lao_dong')->insertGetId([
            'nhan_khau_id' => $nhanKhauId,
            'trang_thai_lao_dong' => 'that_nghiep',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->delete(route('ho-so.destroy', $laoDongId));

        $response->assertRedirect(route('ho-so.index'));
        $this->assertDatabaseMissing('lao_dong', [
            'id' => $laoDongId,
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
    }
}
