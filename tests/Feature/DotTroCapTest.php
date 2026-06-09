<?php

namespace Tests\Feature;

use App\Models\ChiTietCapPhatTroCap;
use App\Models\DotTroCap;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DotTroCapTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::where('email', 'laodong@ubnd-xa.vn')->first();
        $this->actingAs($user);
    }

    public function test_index_page_renders_packages(): void
    {
        DB::table('dot_tro_cap')->insert([
            'ten_dot' => 'Cứu trợ lũ lụt 2026',
            'loai_tro_cap' => 'tien_mat',
            'gia_tri_quy_doi' => 1000000,
            'nguon_kinh_phi' => 'Mạnh thường quân',
            'ngay_bat_dau_cap_phat' => '2026-06-01',
            'tong_so_doi_tuong' => 0,
            'so_da_nhan' => 0,
            'trang_thai' => 'sap_dien_ra',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get(route('dot-tro-cap.index'))
            ->assertOk()
            ->assertSee('Cứu trợ lũ lụt 2026')
            ->assertSee('1.000.000đ/suất')
            ->assertSee('Mạnh thường quân');
    }

    public function test_create_page_renders_criteria_form(): void
    {
        $this->get(route('dot-tro-cap.create'))
            ->assertOk()
            ->assertSee('Tạo đợt trợ cấp mới')
            ->assertSee('Đối tượng Bảo trợ xã hội')
            ->assertSee('Đối tượng Diện chính sách')
            ->assertSee('Giới hạn địa bàn');
    }

    public function test_store_creates_package_and_scans_recipients(): void
    {
        // Setup data
        // 1. Citizen A (Thương binh - Diện chính sách)
        $nhanKhauAId = $this->createNhanKhau('Nguyễn Văn A', 'hoat_dong', 'Thôn 1');
        DB::table('doi_tuong_chinh_sach')->insert([
            'nhan_khau_id' => $nhanKhauAId,
            'loai_chinh_sach' => 'thuong_binh',
            'trang_thai' => 'dang_huong_che_do',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Citizen B (Người khuyết tật - Bảo trợ cá nhân)
        $nhanKhauBId = $this->createNhanKhau('Trần Thị B', 'hoat_dong', 'Thôn 2');
        DB::table('bao_tro_xa_hoi')->insert([
            'nhan_khau_id' => $nhanKhauBId,
            'loai_bao_tro' => 'nguoi_khuyet_tat',
            'trang_thai' => 'dang_huong',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Citizen C (Hộ nghèo - Bảo trợ theo hộ)
        $hoKhauCId = $this->createHoKhau('HKC123', 'MHC123', 'Thôn 1');
        $nhanKhauCId = DB::table('nhan_khau')->insertGetId([
            'ho_khau_id' => $hoKhauCId,
            'ho_ten' => 'Lê Văn C',
            'cccd_cmnd' => '123456789012',
            'ngay_sinh' => '1980-01-01',
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
        DB::table('ho_khau')->where('id', $hoKhauCId)->update(['chu_ho_nhan_khau_id' => $nhanKhauCId]);
        DB::table('bao_tro_xa_hoi')->insert([
            'ho_khau_id' => $hoKhauCId,
            'loai_bao_tro' => 'ho_ngheo',
            'trang_thai' => 'dang_huong',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Post store request
        $response = $this->post(route('dot-tro-cap.store'), [
            'ten_dot' => 'Quà hỗ trợ khó khăn 2026',
            'loai_tro_cap' => 'tien_mat',
            'gia_tri_quy_doi' => 500000,
            'nguon_kinh_phi' => 'Ngân sách xã',
            'ngay_bat_dau_cap_phat' => '2026-06-05',
            'trang_thai' => 'sap_dien_ra',
            'loai_bao_tro' => ['ho_ngheo', 'nguoi_khuyet_tat'],
            'loai_chinh_sach' => ['thuong_binh'],
            'thon_xom' => ['Thôn 1'], // limit location to Thôn 1
        ]);

        $response->assertRedirect(route('dot-tro-cap.index'));

        // Check if package is created
        $this->assertDatabaseHas('dot_tro_cap', [
            'ten_dot' => 'Quà hỗ trợ khó khăn 2026',
            'tong_so_doi_tuong' => 2, // Nguyễn Văn A (Thương binh - Thôn 1) and Hộ Lê Văn C (Hộ nghèo - Thôn 1). Trần Thị B in Thôn 2 should be excluded!
        ]);

        // Check recipient details
        $package = DotTroCap::where('ten_dot', 'Quà hỗ trợ khó khăn 2026')->first();
        $this->assertDatabaseHas('chi_tiet_cap_phat_tro_cap', [
            'dot_tro_cap_id' => $package->id,
            'nhan_khau_id' => $nhanKhauAId,
            'ho_khau_id' => null,
        ]);
        $this->assertDatabaseHas('chi_tiet_cap_phat_tro_cap', [
            'dot_tro_cap_id' => $package->id,
            'ho_khau_id' => $hoKhauCId,
            'nhan_khau_id' => null,
        ]);
        $this->assertDatabaseMissing('chi_tiet_cap_phat_tro_cap', [
            'dot_tro_cap_id' => $package->id,
            'nhan_khau_id' => $nhanKhauBId,
        ]);
    }

    public function test_confirm_receipt(): void
    {
        $dotId = DB::table('dot_tro_cap')->insertGetId([
            'ten_dot' => 'Hỗ trợ y tế 2026',
            'loai_tro_cap' => 'tien_mat',
            'gia_tri_quy_doi' => 200000,
            'ngay_bat_dau_cap_phat' => '2026-06-05',
            'tong_so_doi_tuong' => 1,
            'so_da_nhan' => 0,
            'trang_thai' => 'dang_thuc_hien',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $nkId = $this->createNhanKhau('Lý Thị D');
        $detailId = DB::table('chi_tiet_cap_phat_tro_cap')->insertGetId([
            'dot_tro_cap_id' => $dotId,
            'nhan_khau_id' => $nkId,
            'so_suat' => 1,
            'gia_tri_nhan' => 200000,
            'da_nhan' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->post(route('dot-tro-cap.confirm', [$dotId, $detailId]));
        $response->assertRedirect();

        $this->assertDatabaseHas('chi_tiet_cap_phat_tro_cap', [
            'id' => $detailId,
            'da_nhan' => true,
            'nguoi_xac_nhan_id' => User::where('email', 'laodong@ubnd-xa.vn')->first()->id,
        ]);

        $this->assertDatabaseHas('dot_tro_cap', [
            'id' => $dotId,
            'so_da_nhan' => 1,
        ]);
    }

    public function test_add_and_remove_manual_recipients(): void
    {
        $dotId = DB::table('dot_tro_cap')->insertGetId([
            'ten_dot' => 'Hỗ trợ cứu đói giáp hạt 2026',
            'loai_tro_cap' => 'hien_vat',
            'gia_tri_quy_doi' => 300000,
            'ngay_bat_dau_cap_phat' => '2026-06-05',
            'tong_so_doi_tuong' => 0,
            'so_da_nhan' => 0,
            'trang_thai' => 'dang_thuc_hien',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $nkId = $this->createNhanKhau('Vương Văn E');

        // Add manually
        $response = $this->post(route('dot-tro-cap.add-recipient', $dotId), [
            'type' => 'nhan_khau',
            'nhan_khau_id' => $nkId,
            'so_suat' => 2,
            'gia_tri_nhan' => 600000,
            'ghi_chu' => 'Hoàn cảnh neo đơn đặc biệt',
        ]);
        $response->assertRedirect();

        $this->assertDatabaseHas('chi_tiet_cap_phat_tro_cap', [
            'dot_tro_cap_id' => $dotId,
            'nhan_khau_id' => $nkId,
            'so_suat' => 2,
            'gia_tri_nhan' => 600000,
            'ghi_chu' => 'Hoàn cảnh neo đơn đặc biệt',
        ]);

        $this->assertDatabaseHas('dot_tro_cap', [
            'id' => $dotId,
            'tong_so_doi_tuong' => 1,
        ]);

        $detail = ChiTietCapPhatTroCap::where('dot_tro_cap_id', $dotId)->first();

        // Remove recipient
        $removeResponse = $this->delete(route('dot-tro-cap.remove-recipient', [$dotId, $detail->id]));
        $removeResponse->assertRedirect();

        $this->assertDatabaseMissing('chi_tiet_cap_phat_tro_cap', [
            'id' => $detail->id,
        ]);

        $this->assertDatabaseHas('dot_tro_cap', [
            'id' => $dotId,
            'tong_so_doi_tuong' => 0,
        ]);
    }

    private function createHoKhau(string $soSo, string $maHo, string $thonXom): int
    {
        return DB::table('ho_khau')->insertGetId([
            'so_so_ho_khau' => $soSo,
            'ma_ho' => $maHo,
            'dia_chi_thuong_tru' => "Địa chỉ {$thonXom}",
            'thon_xom' => $thonXom,
            'phan_loai' => 'thuong_tru',
            'so_thanh_vien' => 1,
            'trang_thai' => 'hoat_dong',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function createNhanKhau(string $hoTen, string $trangThai = 'hoat_dong', string $thonXom = 'Thôn 1'): int
    {
        $hoKhauId = $this->createHoKhau('HK'.str()->random(5), 'MH'.str()->random(5), $thonXom);

        return DB::table('nhan_khau')->insertGetId([
            'ho_khau_id' => $hoKhauId,
            'ho_ten' => $hoTen,
            'cccd_cmnd' => (string) random_int(100000000000, 999999999999),
            'ngay_sinh' => '1975-01-01',
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
}
