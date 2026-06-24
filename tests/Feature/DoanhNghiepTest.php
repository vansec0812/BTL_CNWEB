<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DoanhNghiepTest extends TestCase
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

    public function test_index_page_renders_doanh_nghiep_records(): void
    {
        DB::table('doanh_nghiep_ho_kinh_doanh')->insert([
            'ten_co_so' => 'Cơ sở May mặc Hùng Vương',
            'ma_so_thue' => '9999999999',
            'loai_hinh' => 'cong_ty_tnhh',
            'nganh_nghe_chinh' => 'May mặc xuất khẩu',
            'dia_chi' => 'Lô B, KCN Quốc Oai',
            'thon_xom' => 'Thôn Bình An',
            'ten_nguoi_dai_dien' => 'Nguyễn Hùng Vương',
            'trang_thai' => 'dang_hoat_dong',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get(route('doanh-nghiep.index'));

        $response->assertOk();
        $response->assertSee('Cơ sở May mặc Hùng Vương');
        $response->assertSee('Nguyễn Hùng Vương');
    }

    public function test_store_creates_doanh_nghiep_successfully(): void
    {
        $response = $this->post(route('doanh-nghiep.store'), [
            'ten_co_so' => 'Hợp tác xã Nông nghiệp Sạch',
            'ma_so_thue' => '8888888888',
            'loai_hinh' => 'hop_tac_xa',
            'nganh_nghe_chinh' => 'Trồng trọt công nghệ cao',
            'dia_chi' => 'Thôn Đồng Tâm',
            'thon_xom' => 'Thôn Đồng Tâm',
            'ten_nguoi_dai_dien' => 'Lê Minh Tâm',
            'trang_thai' => 'dang_hoat_dong',
            'so_lao_dong_hien_tai' => 10,
            'so_vi_tri_tuyen_dung' => 3,
        ]);

        $response->assertRedirect(route('doanh-nghiep.index'));
        $this->assertDatabaseHas('doanh_nghiep_ho_kinh_doanh', [
            'ten_co_so' => 'Hợp tác xã Nông nghiệp Sạch',
            'ma_so_thue' => '8888888888',
        ]);
    }

    public function test_update_updates_doanh_nghiep_successfully(): void
    {
        $id = DB::table('doanh_nghiep_ho_kinh_doanh')->insertGetId([
            'ten_co_so' => 'Tạp hóa cô Mai',
            'loai_hinh' => 'ho_kinh_doanh_ca_the',
            'dia_chi' => 'Xóm 2',
            'thon_xom' => 'Thôn 2',
            'ten_nguoi_dai_dien' => 'Nguyễn Thị Mai',
            'trang_thai' => 'dang_hoat_dong',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->put(route('doanh-nghiep.update', $id), [
            'ten_co_so' => 'Cửa hàng tiện lợi Mai Mart', // Cập nhật tên mới
            'loai_hinh' => 'ho_kinh_doanh_ca_the',
            'dia_chi' => 'Xóm 2',
            'thon_xom' => 'Thôn 2',
            'ten_nguoi_dai_dien' => 'Nguyễn Thị Mai',
            'trang_thai' => 'tam_ngung', // Thay đổi trạng thái
        ]);

        $response->assertRedirect(route('doanh-nghiep.index'));
        $this->assertDatabaseHas('doanh_nghiep_ho_kinh_doanh', [
            'id' => $id,
            'ten_co_so' => 'Cửa hàng tiện lợi Mai Mart',
            'trang_thai' => 'tam_ngung',
        ]);
    }

    public function test_destroy_soft_deletes_doanh_nghiep(): void
    {
        $id = DB::table('doanh_nghiep_ho_kinh_doanh')->insertGetId([
            'ten_co_so' => 'Cơ sở sản xuất gạch nung',
            'loai_hinh' => 'doanh_nghiep_tu_nhan',
            'trang_thai' => 'dang_hoat_dong',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->delete(route('doanh-nghiep.destroy', $id));

        $response->assertRedirect(route('doanh-nghiep.index'));
        $this->assertSoftDeleted('doanh_nghiep_ho_kinh_doanh', [
            'id' => $id,
        ]);
    }
}
