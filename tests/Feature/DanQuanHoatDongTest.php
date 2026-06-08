<?php

namespace Tests\Feature;

use App\Models\HoKhau;
use App\Models\NhanKhau;
use App\Models\DanQuanTuVe;
use App\Models\DanQuanHoatDong;
use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DanQuanHoatDongTest extends TestCase
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

    public function test_get_list_dan_quan_hoat_dong(): void
    {
        $response = $this->get(route('dan-quan-hoat-dong.index'));

        $response->assertStatus(200)
            ->assertSee('Hoạt động Dân quân tự vệ')
            ->assertSee('Bộ lọc tìm kiếm');
    }

    public function test_store_dan_quan_hoat_dong_validation_errors(): void
    {
        $response = $this->post(route('dan-quan-hoat-dong.store'), []);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['dan_quan_tu_ve_id', 'loai_hoat_dong', 'ten_hoat_dong', 'ngay_thuc_hien', 'trang_thai']);
    }

    public function test_store_and_crud_dan_quan_hoat_dong(): void
    {
        // 1. Tạo hộ khẩu & nhân khẩu
        $hoKhau = HoKhau::create([
            'so_so_ho_khau' => 'SHK111',
            'ma_ho' => 'MH111',
            'dia_chi_thuong_tru' => 'Thôn 1, Xã X',
            'phan_loai' => 'thuong_tru',
            'trang_thai' => 'hoat_dong',
        ]);

        $nhanKhau = NhanKhau::create([
            'ho_khau_id' => $hoKhau->id,
            'ho_ten' => 'Nguyễn Dân Quân',
            'cccd_cmnd' => '123456789123',
            'ngay_sinh' => '2000-01-01',
            'gioi_tinh' => 'nam',
            'dan_toc' => 'Kinh',
            'trinh_do_hoc_van' => 'thpt',
            'trang_thai' => 'hoat_dong',
        ]);

        // 2. Tạo thành viên dân quân tự vệ
        $tuVe = DanQuanTuVe::create([
            'nhan_khau_id' => $nhanKhau->id,
            'chuc_vu' => 'Chiến sĩ',
            'don_vi' => 'Trung đội Dân quân cơ động xã',
            'ngay_gia_nhap' => '2025-01-01',
            'trang_thai' => 'dang_phuc_vu',
        ]);

        // 3. Thêm mới hoạt động dân quân (loai_hoat_dong = tap_huan)
        $storeData = [
            'dan_quan_tu_ve_id' => $tuVe->id,
            'loai_hoat_dong' => 'tap_huan',
            'ten_hoat_dong' => 'Tập huấn kỹ năng cứu hộ',
            'ngay_thuc_hien' => '2026-06-08',
            'trang_thai' => 'tham_gia',
            'ghi_chu' => 'Hoạt động huấn luyện tập trung.',
        ];

        $storeResponse = $this->post(route('dan-quan-hoat-dong.store'), $storeData);

        $storeResponse->assertStatus(302)
            ->assertRedirect(route('dan-quan-hoat-dong.index'));

        $this->assertDatabaseHas('dan_quan_hoat_dong', [
            'dan_quan_tu_ve_id' => $tuVe->id,
            'ten_hoat_dong' => 'Tập huấn kỹ năng cứu hộ',
            'trang_thai' => 'tham_gia',
        ]);

        $hoatDong = DanQuanHoatDong::where('dan_quan_tu_ve_id', $tuVe->id)->first();
        $this->assertNotNull($hoatDong);

        // 4. Xem chi tiết HTML
        $showResponse = $this->get(route('dan-quan-hoat-dong.show', $hoatDong));
        $showResponse->assertStatus(200)
            ->assertSee('Nguyễn Dân Quân')
            ->assertSee('Chi tiết hoạt động dân quân');

        // 5. Cập nhật hoạt động
        $updateData = [
            'dan_quan_tu_ve_id' => $tuVe->id,
            'loai_hoat_dong' => 'tap_huan',
            'ten_hoat_dong' => 'Tập huấn kỹ năng cứu hộ chuyên sâu',
            'ngay_thuc_hien' => '2026-06-08',
            'trang_thai' => 'vang_co_phep',
            'ghi_chu' => 'Được miễn do ốm đau.',
        ];

        $updateResponse = $this->put(route('dan-quan-hoat-dong.update', $hoatDong), $updateData);
        $updateResponse->assertStatus(302)
            ->assertRedirect(route('dan-quan-hoat-dong.index'));

        $this->assertDatabaseHas('dan_quan_hoat_dong', [
            'id' => $hoatDong->id,
            'ten_hoat_dong' => 'Tập huấn kỹ năng cứu hộ chuyên sâu',
            'trang_thai' => 'vang_co_phep',
        ]);

        // 6. Xóa hoạt động
        $deleteResponse = $this->delete(route('dan-quan-hoat-dong.destroy', $hoatDong));
        $deleteResponse->assertStatus(302)
            ->assertRedirect(route('dan-quan-hoat-dong.index'));

        $this->assertNull(DanQuanHoatDong::find($hoatDong->id));
    }

    public function test_eligible_militia_autocomplete(): void
    {
        // 1. Tạo hộ khẩu & nhân khẩu
        $hoKhau = HoKhau::create([
            'so_so_ho_khau' => 'SHK222',
            'ma_ho' => 'MH222',
            'dia_chi_thuong_tru' => 'Thôn 2, Xã X',
            'phan_loai' => 'thuong_tru',
            'trang_thai' => 'hoat_dong',
        ]);

        $nhanKhau = NhanKhau::create([
            'ho_khau_id' => $hoKhau->id,
            'ho_ten' => 'Vũ Dân Quân',
            'cccd_cmnd' => '987654321098',
            'ngay_sinh' => '1999-12-31',
            'gioi_tinh' => 'nam',
            'dan_toc' => 'Kinh',
            'trinh_do_hoc_van' => 'thpt',
            'trang_thai' => 'hoat_dong',
        ]);

        // 2. Tạo thành viên dân quân tự vệ đang phục vụ
        $tuVe = DanQuanTuVe::create([
            'nhan_khau_id' => $nhanKhau->id,
            'chuc_vu' => 'Tổ trưởng',
            'don_vi' => 'Tiểu đội Dân quân tại chỗ Thôn 2',
            'ngay_gia_nhap' => '2025-01-01',
            'trang_thai' => 'dang_phuc_vu',
        ]);

        // 3. Gọi AJAX search
        $response = $this->getJson(route('dan-quan-hoat-dong.eligible-militia', [
            'search' => 'Vũ Dân Quân',
        ]));

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.ho_ten', 'Vũ Dân Quân');
    }
}
