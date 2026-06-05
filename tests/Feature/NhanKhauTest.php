<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NhanKhauTest extends TestCase
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

    public function test_index_page_renders_nhankhau_records(): void
    {
        $hoKhauId = DB::table('ho_khau')->insertGetId([
            'so_so_ho_khau' => 'HK-NK',
            'ma_ho' => 'MH-NK',
            'dia_chi_thuong_tru' => 'Địa chỉ NK',
            'phan_loai' => 'thuong_tru',
            'trang_thai' => 'hoat_dong',
        ]);

        $nkId = DB::table('nhan_khau')->insertGetId([
            'ho_khau_id' => $hoKhauId,
            'ho_ten' => 'Nguyen Van NKTest',
            'gioi_tinh' => 'nam',
            'dan_toc' => 'Kinh',
            'tinh_trang_hon_nhan' => 'doc_than',
            'trang_thai' => 'hoat_dong',
            'ngay_sinh' => '1995-01-01',
        ]);

        $response = $this->getJson(route('api.nhan-khau.index'));
        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonFragment(['ho_ten' => 'Nguyen Van NKTest']);
    }

    public function test_store_creates_nhankhau_record(): void
    {
        $hoKhauId = DB::table('ho_khau')->insertGetId([
            'so_so_ho_khau' => 'HK-NK2',
            'ma_ho' => 'MH-NK2',
            'dia_chi_thuong_tru' => 'Địa chỉ NK2',
            'phan_loai' => 'thuong_tru',
            'trang_thai' => 'hoat_dong',
        ]);

        $response = $this->postJson(route('api.nhan-khau.store'), [
            'ho_khau_id' => $hoKhauId,
            'ho_ten' => 'Nguyen Van NKB',
            'cccd_cmnd' => '123456789',
            'ngay_sinh' => '1990-01-01',
            'gioi_tinh' => 'nam',
            'dan_toc' => 'Kinh',
            'tinh_trang_hon_nhan' => 'doc_than',
            'trang_thai' => 'hoat_dong',
        ]);

        $response->assertCreated();
        $response->assertJson([
            'success' => true,
            'message' => 'Đã thêm nhân khẩu mới thành công.',
        ]);

        $this->assertDatabaseHas('nhan_khau', [
            'ho_ten' => 'Nguyen Van NKB',
            'cccd_cmnd' => '123456789',
            'ho_khau_id' => $hoKhauId,
        ]);
    }

    public function test_update_updates_nhankhau_record(): void
    {
        $hoKhauId = DB::table('ho_khau')->insertGetId([
            'so_so_ho_khau' => 'HK-NK3',
            'ma_ho' => 'MH-NK3',
            'dia_chi_thuong_tru' => 'Địa chỉ NK3',
            'phan_loai' => 'thuong_tru',
            'trang_thai' => 'hoat_dong',
        ]);

        $nkId = DB::table('nhan_khau')->insertGetId([
            'ho_khau_id' => $hoKhauId,
            'ho_ten' => 'Nguyen Van NKC',
            'gioi_tinh' => 'nam',
            'dan_toc' => 'Kinh',
            'tinh_trang_hon_nhan' => 'doc_than',
            'trang_thai' => 'hoat_dong',
            'ngay_sinh' => '1995-01-01',
        ]);

        $response = $this->putJson(route('api.nhan-khau.update', $nkId), [
            'ho_khau_id' => $hoKhauId,
            'ho_ten' => 'Nguyen Van NKC-Edited',
            'ngay_sinh' => '1995-01-01',
            'gioi_tinh' => 'nu',
            'dan_toc' => 'Tay',
            'tinh_trang_hon_nhan' => 'da_ket_hon',
            'trang_thai' => 'tam_tru',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Cập nhật thông tin nhân khẩu thành công.',
        ]);

        $this->assertDatabaseHas('nhan_khau', [
            'id' => $nkId,
            'ho_ten' => 'Nguyen Van NKC-Edited',
            'gioi_tinh' => 'nu',
            'dan_toc' => 'Tay',
            'trang_thai' => 'tam_tru',
        ]);
    }

    public function test_destroy_deletes_nhankhau_record(): void
    {
        $hoKhauId = DB::table('ho_khau')->insertGetId([
            'so_so_ho_khau' => 'HK-NK4',
            'ma_ho' => 'MH-NK4',
            'dia_chi_thuong_tru' => 'Địa chỉ NK4',
            'phan_loai' => 'thuong_tru',
            'trang_thai' => 'hoat_dong',
        ]);

        $nkId = DB::table('nhan_khau')->insertGetId([
            'ho_khau_id' => $hoKhauId,
            'ho_ten' => 'Nguyen Van NKD',
            'gioi_tinh' => 'nam',
            'dan_toc' => 'Kinh',
            'tinh_trang_hon_nhan' => 'doc_than',
            'trang_thai' => 'hoat_dong',
            'ngay_sinh' => '1995-01-01',
        ]);

        $response = $this->deleteJson(route('api.nhan-khau.destroy', $nkId));
        $response->assertOk();
        $this->assertSoftDeleted('nhan_khau', ['id' => $nkId]);
    }
}
