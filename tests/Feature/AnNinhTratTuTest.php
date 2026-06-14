<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AnNinhTratTuTest extends TestCase
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

    public function test_an_ninh_trat_tu_index_page_renders(): void
    {
        $nkId = $this->createNhanKhau('Nguyễn Văn Đối Tượng');

        DB::table('an_ninh_trat_tu')->insert([
            'nhan_khau_id' => $nkId,
            'ho_ten' => 'Nguyễn Văn Đối Tượng',
            'nhom_doi_tuong' => 'quan_ly_dac_biet',
            'loai_doi_tuong' => 'tien_an_tien_su',
            'co_quan_giai_quyet' => 'Công an huyện',
            'ngay_ghi_nhan' => '2025-05-15',
            'noi_dung' => 'Có tiền án trộm cắp',
            'hinh_thuc_xu_ly' => 'Giám sát hành vi',
            'trang_thai' => 'dang_quan_ly',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get(route('an-ninh-trat-tu.index'))
            ->assertOk()
            ->assertSee('Nguyễn Văn Đối Tượng')
            ->assertSee('Công an huyện');
    }

    public function test_store_an_ninh_trat_tu_dac_biet_success(): void
    {
        $nkId = $this->createNhanKhau('Công Dân Mới');

        $this->post(route('an-ninh-trat-tu.store'), [
            'nhan_khau_id' => $nkId,
            'ho_ten' => 'Công Dân Mới',
            'nhom_doi_tuong' => 'quan_ly_dac_biet',
            'loai_doi_tuong' => 'nguoi_nghien_ma_tuy',
            'co_quan_giai_quyet' => 'UBND xã',
            'ngay_ghi_nhan' => '2026-01-20',
            'noi_dung' => 'Sử dụng chất cấm',
            'hinh_thuc_xu_ly' => 'Cai nghiện tại cộng đồng',
            'trang_thai' => 'dang_quan_ly',
        ])->assertRedirect(route('an-ninh-trat-tu.index'));

        $this->assertDatabaseHas('an_ninh_trat_tu', [
            'nhan_khau_id' => $nkId,
            'ho_ten' => 'Công Dân Mới',
            'nhom_doi_tuong' => 'quan_ly_dac_biet',
            'loai_doi_tuong' => 'nguoi_nghien_ma_tuy',
            'co_quan_giai_quyet' => 'UBND xã',
        ]);
    }

    public function test_store_an_ninh_trat_tu_vi_pham_success(): void
    {
        $nkId = $this->createNhanKhau('Người Vi Phạm Mới');

        $this->post(route('an-ninh-trat-tu.store'), [
            'nhan_khau_id' => $nkId,
            'ho_ten' => 'Người Vi Phạm Mới',
            'nhom_doi_tuong' => 'vi_pham_hanh_chinh',
            'loai_doi_tuong' => 'vi_pham_hanh_chinh',
            'co_quan_giai_quyet' => 'Công an xã',
            'ngay_ghi_nhan' => '2026-06-01',
            'noi_dung' => 'Gây rối trật tự',
            'hinh_thuc_xu_ly' => 'Phạt tiền',
            'so_tien_phat' => 200000.00,
            'trang_thai' => 'da_chap_hanh',
        ])->assertRedirect(route('an-ninh-trat-tu.index'));

        $this->assertDatabaseHas('an_ninh_trat_tu', [
            'nhan_khau_id' => $nkId,
            'ho_ten' => 'Người Vi Phạm Mới',
            'nhom_doi_tuong' => 'vi_pham_hanh_chinh',
            'co_quan_giai_quyet' => 'Công an xã',
            'so_tien_phat' => 200000.00,
        ]);
    }

    public function test_store_an_ninh_trat_tu_vang_lai_success(): void
    {
        // Vi phạm vãng lai thì nhan_khau_id = null, bắt buộc có ho_ten_vang_lai
        $this->post(route('an-ninh-trat-tu.store'), [
            'nhan_khau_id' => null,
            'ho_ten' => 'Trần Văn Vãng Lai',
            'nhom_doi_tuong' => 'vi_pham_hanh_chinh',
            'loai_doi_tuong' => 'vi_pham_hanh_chinh',
            'co_quan_giai_quyet' => 'UBND xã',
            'ngay_ghi_nhan' => '2026-06-02',
            'noi_dung' => 'Kinh doanh không giấy phép',
            'hinh_thuc_xu_ly' => 'Cảnh cáo',
            'trang_thai' => 'da_chap_hanh',
        ])->assertRedirect(route('an-ninh-trat-tu.index'));

        $this->assertDatabaseHas('an_ninh_trat_tu', [
            'nhan_khau_id' => null,
            'ho_ten' => 'Trần Văn Vãng Lai',
            'nhom_doi_tuong' => 'vi_pham_hanh_chinh',
            'co_quan_giai_quyet' => 'UBND xã',
        ]);
    }

    public function test_store_an_ninh_trat_tu_validation_fails(): void
    {
        // Thiếu ho_ten khi nhan_khau_id = null
        $this->post(route('an-ninh-trat-tu.store'), [
            'nhan_khau_id' => null,
            'nhom_doi_tuong' => 'vi_pham_hanh_chinh',
            'loai_doi_tuong' => 'vi_pham_hanh_chinh',
            'co_quan_giai_quyet' => 'Công an xã',
            'ngay_ghi_nhan' => '2026-06-01',
            'noi_dung' => 'Vi phạm luật lệ',
            'trang_thai' => 'chua_chap_hanh',
        ])->assertSessionHasErrors('ho_ten');
    }

    private function createNhanKhau(string $hoTen): int
    {
        $hoKhauId = DB::table('ho_khau')->insertGetId([
            'so_so_ho_khau' => 'HK'.str()->random(8),
            'ma_ho' => 'MH'.str()->random(8),
            'dia_chi_thuong_tru' => 'Thôn 3, Xã Quốc Oai',
            'thon_xom' => 'Thôn 3',
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
            'ngay_sinh' => '1990-01-01',
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
