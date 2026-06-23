<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LocDongTest extends TestCase
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

    public function test_index_page_renders_successfully(): void
    {
        $response = $this->get(route('he-thong.loc-dong'));
        $response->assertOk();
        $response->assertViewIs('he-thong.loc_dong');
    }

    public function test_dynamic_filtering_combinations(): void
    {
        // 1. Seed Households
        $hoKhauNgheoId = DB::table('ho_khau')->insertGetId([
            'so_so_ho_khau' => 'HK000001',
            'ma_ho' => 'MH000001',
            'dia_chi_thuong_tru' => 'Thôn 1, Xã Quốc Oai',
            'thon_xom' => 'Thôn 1',
            'phan_loai' => 'thuong_tru',
            'trang_thai' => 'hoat_dong',
        ]);

        $hoKhauGiauId = DB::table('ho_khau')->insertGetId([
            'so_so_ho_khau' => 'HK000002',
            'ma_ho' => 'MH000002',
            'dia_chi_thuong_tru' => 'Thôn 2, Xã Quốc Oai',
            'thon_xom' => 'Thôn 2',
            'phan_loai' => 'thuong_tru',
            'trang_thai' => 'hoat_dong',
        ]);

        // 2. Seed Poor Household support status in bao_tro_xa_hoi
        DB::table('bao_tro_xa_hoi')->insert([
            'ho_khau_id' => $hoKhauNgheoId,
            'loai_bao_tro' => 'ho_ngheo',
            'trang_thai' => 'dang_huong',
            'ngay_bat_dau_huong' => '2026-01-01',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Seed Residents (Nhan Khau)
        // Resident 1: Nam, 20 tuoi (born 2006), ho ngheo, that nghiep
        $nk1Id = DB::table('nhan_khau')->insertGetId([
            'ho_khau_id' => $hoKhauNgheoId,
            'ho_ten' => 'Nguyễn Văn A',
            'cccd_cmnd' => '111222333444',
            'ngay_sinh' => '2006-05-15',
            'gioi_tinh' => 'nam',
            'dan_toc' => 'Kinh',
            'ton_giao' => 'Không',
            'trinh_do_hoc_van' => 'thpt',
            'tinh_trang_hon_nhan' => 'doc_than',
            'quan_he_chu_ho' => 'Con',
            'trang_thai' => 'hoat_dong',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Resident 2: Nu, 30 tuoi (born 1996), ho giau, co viec lam
        $nk2Id = DB::table('nhan_khau')->insertGetId([
            'ho_khau_id' => $hoKhauGiauId,
            'ho_ten' => 'Trần Thị B',
            'cccd_cmnd' => '222333444555',
            'ngay_sinh' => '1996-08-20',
            'gioi_tinh' => 'nu',
            'dan_toc' => 'Kinh',
            'ton_giao' => 'Phật giáo',
            'trinh_do_hoc_van' => 'dai_hoc',
            'tinh_trang_hon_nhan' => 'da_ket_hon',
            'quan_he_chu_ho' => 'Chủ hộ',
            'trang_thai' => 'hoat_dong',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Resident 3: Nam, 26 tuoi (born 2000), ho ngheo, hoc sinh/sinh vien
        $nk3Id = DB::table('nhan_khau')->insertGetId([
            'ho_khau_id' => $hoKhauNgheoId,
            'ho_ten' => 'Nguyễn Văn C',
            'cccd_cmnd' => '333444555666',
            'ngay_sinh' => '2000-02-10',
            'gioi_tinh' => 'nam',
            'dan_toc' => 'Kinh',
            'ton_giao' => 'Không',
            'trinh_do_hoc_van' => 'dai_hoc',
            'tinh_trang_hon_nhan' => 'doc_than',
            'quan_he_chu_ho' => 'Con',
            'trang_thai' => 'hoat_dong',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Seed Labor profiles
        DB::table('lao_dong')->insert([
            [
                'nhan_khau_id' => $nk1Id,
                'trang_thai_lao_dong' => 'that_nghiep',
                'loai_hinh_cong_viec' => 'khong_co_viec',
                'nganh_nghe' => 'khac',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nhan_khau_id' => $nk2Id,
                'trang_thai_lao_dong' => 'co_viec_lam',
                'loai_hinh_cong_viec' => 'tu_nhan',
                'nganh_nghe' => 'dich_vu_thuong_mai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nhan_khau_id' => $nk3Id,
                'trang_thai_lao_dong' => 'hoc_sinh_sinh_vien',
                'loai_hinh_cong_viec' => 'khong_co_viec',
                'nganh_nghe' => 'khac',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 5. Test combinations of filters

        // Filter 1: Lọc nam từ 18-25 tuổi, thuộc hộ nghèo, thất nghiệp
        // Nguyễn Văn A should match (Nam, 20 tuổi, hộ nghèo, thất nghiệp)
        // Nguyễn Văn C is 26, so he is excluded
        // Trần Thị B is female, so she is excluded
        $response = $this->get(route('he-thong.loc-dong', [
            'gioi_tinh' => 'nam',
            'tuoi_tu' => 18,
            'tuoi_den' => 25,
            'ho_ngheo' => '1',
            'trang_thai_lao_dong' => 'that_nghiep',
        ]));

        $response->assertOk();
        $results = $response->viewData('results');
        $this->assertCount(1, $results);
        $this->assertEquals('Nguyễn Văn A', $results->first()->ho_ten);

        // Check stats calculations
        $stats = $response->viewData('stats');
        $this->assertEquals(1, $stats['total']);
        $this->assertEquals(1, $stats['nam']);
        $this->assertEquals(0, $stats['nu']);

        // Filter 2: Lọc hộ nghèo ở Thôn 1
        // Nguyễn Văn A and Nguyễn Văn C should match
        $response = $this->get(route('he-thong.loc-dong', [
            'thon_xom' => 'Thôn 1',
            'ho_ngheo' => '1',
        ]));

        $response->assertOk();
        $results = $response->viewData('results');
        $this->assertCount(2, $results);
        $names = $results->pluck('ho_ten')->all();
        $this->assertContains('Nguyễn Văn A', $names);
        $this->assertContains('Nguyễn Văn C', $names);

        // Filter 3: Lọc Nam trong độ tuổi nghĩa vụ quân sự (18-25, hoặc đến 27 nếu đại học)
        // Nguyễn Văn A (Nam, 20 tuổi) -> Trong độ tuổi NVQS (18-25) -> Khớp
        // Nguyễn Văn C (Nam, 26 tuổi, Đại học) -> Trong độ tuổi NVQS (26-27 có bằng đại học) -> Khớp
        // Trần Thị B is Female -> Khác giới tính -> Loại
        $response = $this->get(route('he-thong.loc-dong', [
            'trong_do_tuoi_nvqs' => '1',
        ]));

        $response->assertOk();
        $results = $response->viewData('results');
        $this->assertCount(2, $results);
        $names = $results->pluck('ho_ten')->all();
        $this->assertContains('Nguyễn Văn A', $names);
        $this->assertContains('Nguyễn Văn C', $names);
    }
}
