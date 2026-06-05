<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BienDongHoKhauTest extends TestCase
{
    use RefreshDatabase;

    protected int $userId;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->userId = $user->id;
        $this->actingAs($user);

        $this->withoutMiddleware([
            Authenticate::class,
            Authorize::class,
        ]);
    }

    public function test_index_page_renders_bien_dong_records(): void
    {
        $hoKhauId = DB::table('ho_khau')->insertGetId([
            'so_so_ho_khau' => 'HK123',
            'ma_ho' => 'MH123',
            'dia_chi_thuong_tru' => 'Địa chỉ 1',
            'phan_loai' => 'thuong_tru',
            'trang_thai' => 'hoat_dong',
        ]);

        $nhanKhauId = DB::table('nhan_khau')->insertGetId([
            'ho_khau_id' => $hoKhauId,
            'ho_ten' => 'Nguyen Van A',
            'gioi_tinh' => 'nam',
            'dan_toc' => 'Kinh',
            'tinh_trang_hon_nhan' => 'doc_than',
            'trang_thai' => 'hoat_dong',
            'ngay_sinh' => '1990-01-01',
        ]);

        DB::table('bien_dong_ho_khau')->insert([
            'loai_bien_dong' => 'tach_ho',
            'ho_khau_nguon_id' => $hoKhauId,
            'nhan_khau_id' => $nhanKhauId,
            'ngay_bien_dong' => '2026-06-01',
            'ly_do' => 'Tach ho gia dinh test',
            'nguoi_thuc_hien_id' => $this->userId,
        ]);

        $response = $this->getJson(route('api.bien-dong.index'));
        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonFragment(['ho_ten' => 'Nguyen Van A']);
    }

    public function test_store_tach_ho_successfully(): void
    {
        $hoKhauId = DB::table('ho_khau')->insertGetId([
            'so_so_ho_khau' => 'HK-Nguon',
            'ma_ho' => 'MH-Nguon',
            'dia_chi_thuong_tru' => 'Địa chỉ Nguồn',
            'phan_loai' => 'thuong_tru',
            'trang_thai' => 'hoat_dong',
        ]);

        $nk1 = DB::table('nhan_khau')->insertGetId([
            'ho_khau_id' => $hoKhauId,
            'ho_ten' => 'Nguyen Van ChuHo',
            'gioi_tinh' => 'nam',
            'dan_toc' => 'Kinh',
            'tinh_trang_hon_nhan' => 'doc_than',
            'trang_thai' => 'hoat_dong',
            'ngay_sinh' => '1990-01-01',
        ]);

        $nk2 = DB::table('nhan_khau')->insertGetId([
            'ho_khau_id' => $hoKhauId,
            'ho_ten' => 'Nguyen Van Con',
            'gioi_tinh' => 'nam',
            'dan_toc' => 'Kinh',
            'tinh_trang_hon_nhan' => 'doc_than',
            'trang_thai' => 'hoat_dong',
            'ngay_sinh' => '2015-01-01',
        ]);

        $response = $this->postJson(route('api.bien-dong.store'), [
            'loai_bien_dong' => 'tach_ho',
            'ho_khau_nguon_id' => $hoKhauId,
            'so_so_ho_khau_moi' => 'HK-Moi',
            'ma_ho_moi' => 'MH-Moi',
            'dia_chi_thuong_tru_moi' => 'Địa chỉ Mới',
            'new_chu_ho_id' => $nk2,
            'thanh_vien_ids' => [$nk2],
            'quan_he' => [$nk2 => 'Chủ hộ'],
            'ngay_bien_dong' => '2026-06-01',
            'ly_do' => 'Tach ho test',
        ]);

        $response->assertCreated();
        $response->assertJson([
            'success' => true,
            'message' => 'Đã thực hiện tách hộ khẩu thành công.',
        ]);

        $this->assertDatabaseHas('ho_khau', [
            'so_so_ho_khau' => 'HK-Moi',
            'ma_ho' => 'MH-Moi',
            'chu_ho_nhan_khau_id' => $nk2,
        ]);
    }

    public function test_store_nhap_ho_successfully(): void
    {
        $hoKhauId = DB::table('ho_khau')->insertGetId([
            'so_so_ho_khau' => 'HK-Dich',
            'ma_ho' => 'MH-Dich',
            'dia_chi_thuong_tru' => 'Địa chỉ Đích',
            'phan_loai' => 'thuong_tru',
            'trang_thai' => 'hoat_dong',
        ]);

        $nk = DB::table('nhan_khau')->insertGetId([
            'ho_khau_id' => $hoKhauId,
            'ho_ten' => 'Nguyen Van A',
            'gioi_tinh' => 'nam',
            'dan_toc' => 'Kinh',
            'tinh_trang_hon_nhan' => 'doc_than',
            'trang_thai' => 'hoat_dong',
            'ngay_sinh' => '1995-01-01',
        ]);

        $response = $this->postJson(route('api.bien-dong.store'), [
            'loai_bien_dong' => 'nhap_ho',
            'ho_khau_dich_id' => $hoKhauId,
            'nhan_khau_id' => $nk,
            'quan_he_chu_ho' => 'Con',
            'ngay_bien_dong' => '2026-06-01',
            'ly_do' => 'Nhap ho test',
        ]);

        $response->assertCreated();
        $response->assertJson([
            'success' => true,
            'message' => 'Đã thực hiện nhập hộ khẩu thành công.',
        ]);

        $this->assertDatabaseHas('nhan_khau', [
            'id' => $nk,
            'ho_khau_id' => $hoKhauId,
            'quan_he_chu_ho' => 'Con',
        ]);
    }
}
