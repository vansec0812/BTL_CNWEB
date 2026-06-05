<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TamTruTamVangTest extends TestCase
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

    public function test_index_page_renders_declarations(): void
    {
        $hoKhauId = DB::table('ho_khau')->insertGetId([
            'so_so_ho_khau' => 'HK-TT',
            'ma_ho' => 'MH-TT',
            'dia_chi_thuong_tru' => 'Địa chỉ TT',
            'phan_loai' => 'thuong_tru',
            'trang_thai' => 'hoat_dong',
        ]);

        $nk = DB::table('nhan_khau')->insertGetId([
            'ho_khau_id' => $hoKhauId,
            'ho_ten' => 'Nguyen Van TamTru',
            'gioi_tinh' => 'nam',
            'dan_toc' => 'Kinh',
            'tinh_trang_hon_nhan' => 'doc_than',
            'trang_thai' => 'hoat_dong',
            'ngay_sinh' => '1995-01-01',
        ]);

        DB::table('tam_tru_tam_vang')->insert([
            'nhan_khau_id' => $nk,
            'loai' => 'tam_tru',
            'ngay_bat_dau' => '2026-06-01',
            'dia_chi_cu_tru_thuc_te' => 'Thôn 1, Xã Quốc Oai',
            'ly_do' => 'Di hoc dai hoc',
            'trang_thai' => 'dang_hieu_luc',
            'nguoi_xac_nhan_id' => $this->userId,
        ]);

        $response = $this->getJson(route('api.tam-tru.index'));
        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonFragment(['ho_ten' => 'Nguyen Van TamTru']);
    }

    public function test_store_creates_declaration(): void
    {
        $hoKhauId = DB::table('ho_khau')->insertGetId([
            'so_so_ho_khau' => 'HK-TT2',
            'ma_ho' => 'MH-TT2',
            'dia_chi_thuong_tru' => 'Địa chỉ TT2',
            'phan_loai' => 'thuong_tru',
            'trang_thai' => 'hoat_dong',
        ]);

        $nk = DB::table('nhan_khau')->insertGetId([
            'ho_khau_id' => $hoKhauId,
            'ho_ten' => 'Nguyen Van B',
            'gioi_tinh' => 'nam',
            'dan_toc' => 'Kinh',
            'tinh_trang_hon_nhan' => 'doc_than',
            'trang_thai' => 'hoat_dong',
            'ngay_sinh' => '1995-01-01',
        ]);

        $response = $this->postJson(route('api.tam-tru.store'), [
            'nhan_khau_id' => $nk,
            'loai' => 'tam_tru',
            'ngay_bat_dau' => '2026-06-01',
            'dia_chi_cu_tru_thuc_te' => 'Địa chỉ tạm trú mới',
            'ly_do' => 'Lam viec tai xa',
        ]);

        $response->assertCreated();
        $response->assertJson([
            'success' => true,
            'message' => 'Đã tạo khai báo tạm trú / tạm vắng thành công.',
        ]);

        $this->assertDatabaseHas('tam_tru_tam_vang', [
            'nhan_khau_id' => $nk,
            'loai' => 'tam_tru',
            'dia_chi_cu_tru_thuc_te' => 'Địa chỉ tạm trú mới',
        ]);
        $this->assertDatabaseHas('nhan_khau', [
            'id' => $nk,
            'trang_thai' => 'tam_tru',
        ]);
    }
}
