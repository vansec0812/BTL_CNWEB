<?php

namespace Tests\Feature;

use App\Models\HoKhau;
use App\Models\NghiaVuQuanSu;
use App\Models\NhanKhau;
use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NghiaVuQuanSuTest extends TestCase
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

    public function test_get_list_nvqs(): void
    {
        $response = $this->getJson(route('nghia-vu-quan-su.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'data',
                    'current_page',
                    'total',
                ],
            ]);
    }

    public function test_scan_eligible_citizens(): void
    {
        // Tạo hộ khẩu
        $hoKhau = HoKhau::create([
            'so_so_ho_khau' => 'SHK001',
            'ma_ho' => 'MH001',
            'dia_chi_thuong_tru' => 'Thôn 1, Xã X',
            'phan_loai' => 'thuong_tru',
            'trang_thai' => 'hoat_dong',
        ]);

        // Tạo 1 công dân nam đủ tuổi (20 tuổi)
        $nhanKhau1 = NhanKhau::create([
            'ho_khau_id' => $hoKhau->id,
            'ho_ten' => 'Nguyễn Văn Nam',
            'cccd_cmnd' => '123456789011',
            'ngay_sinh' => '2006-05-15', // Trong năm 2026 sẽ là 20 tuổi
            'gioi_tinh' => 'nam',
            'dan_toc' => 'Kinh',
            'trinh_do_hoc_van' => 'thpt',
            'trang_thai' => 'hoat_dong',
        ]);

        // Tạo 1 công dân nam 26 tuổi nhưng có bằng đại học
        $nhanKhau2 = NhanKhau::create([
            'ho_khau_id' => $hoKhau->id,
            'ho_ten' => 'Trần Đại Học',
            'cccd_cmnd' => '123456789012',
            'ngay_sinh' => '2000-08-10', // Trong năm 2026 sẽ là 26 tuổi
            'gioi_tinh' => 'nam',
            'dan_toc' => 'Kinh',
            'trinh_do_hoc_van' => 'dai_hoc',
            'trang_thai' => 'hoat_dong',
        ]);

        // Tạo 1 công dân nữ (không thuộc đối tượng)
        NhanKhau::create([
            'ho_khau_id' => $hoKhau->id,
            'ho_ten' => 'Nguyễn Thị Nữ',
            'cccd_cmnd' => '123456789013',
            'ngay_sinh' => '2006-05-15',
            'gioi_tinh' => 'nu',
            'dan_toc' => 'Kinh',
            'trinh_do_hoc_van' => 'thpt',
            'trang_thai' => 'hoat_dong',
        ]);

        $response = $this->postJson(route('nghia-vu-quan-su.scan'), [
            'nam_tuyen_quan' => 2026,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Tự động quét danh sách đủ tuổi nghĩa vụ quân sự hoàn tất.',
                'data' => [
                    'target_year' => 2026,
                    'total_scanned' => 2, // Chỉ có Nam và Đại học (nam)
                    'added_count' => 2,
                    'existing_count' => 0,
                ],
            ]);

        $this->assertDatabaseHas('nghia_vu_quan_su', [
            'nhan_khau_id' => $nhanKhau1->id,
            'trang_thai_nvqs' => 'du_dieu_kien',
        ]);

        $this->assertDatabaseHas('nghia_vu_quan_su', [
            'nhan_khau_id' => $nhanKhau2->id,
            'trang_thai_nvqs' => 'du_dieu_kien',
        ]);
    }

    public function test_store_nvqs_validation_error(): void
    {
        $response = $this->postJson(route('nghia-vu-quan-su.store'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nhan_khau_id']);
    }

    public function test_store_and_crud_nvqs(): void
    {
        // 1. Tạo hộ khẩu & nhân khẩu nam mới
        $hoKhau = HoKhau::create([
            'so_so_ho_khau' => 'SHK002',
            'ma_ho' => 'MH002',
            'dia_chi_thuong_tru' => 'Thôn 2, Xã X',
            'phan_loai' => 'thuong_tru',
            'trang_thai' => 'hoat_dong',
        ]);

        $nhanKhau = NhanKhau::create([
            'ho_khau_id' => $hoKhau->id,
            'ho_ten' => 'Nguyễn Test Binh',
            'cccd_cmnd' => '123456789099',
            'ngay_sinh' => '2005-05-15',
            'gioi_tinh' => 'nam',
            'dan_toc' => 'Kinh',
            'trinh_do_hoc_van' => 'thpt',
            'trang_thai' => 'hoat_dong',
        ]);

        // 2. Thêm hồ sơ NVQS
        $storeData = [
            'nhan_khau_id' => $nhanKhau->id,
            'nam_tuoi_tuyen_quan' => 2026,
            'trang_thai_nvqs' => 'du_dieu_kien',
        ];

        $storeResponse = $this->postJson(route('nghia-vu-quan-su.store'), $storeData);

        $storeResponse->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Đã tạo hồ sơ nghĩa vụ quân sự thành công.',
            ]);

        $nvqsId = $storeResponse->json('data.id');

        // 3. Xem chi tiết
        $showResponse = $this->getJson(route('nghia-vu-quan-su.show', $nvqsId));
        $showResponse->assertStatus(200)
            ->assertJsonPath('data.nhan_khau.ho_ten', 'Nguyễn Test Binh');

        // 4. Cập nhật trạng thái
        $updateData = [
            'nhan_khau_id' => $nhanKhau->id,
            'trang_thai_nvqs' => 'tam_hoan',
            'ly_do_tam_hoan' => 'benh_tat_suc_khoe',
            'ngay_tam_hoan_den' => '2027-06-01',
        ];

        $updateResponse = $this->putJson(route('nghia-vu-quan-su.update', $nvqsId), $updateData);
        $updateResponse->assertStatus(200)
            ->assertJsonPath('data.trang_thai_nvqs', 'tam_hoan')
            ->assertJsonPath('data.ly_do_tam_hoan', 'benh_tat_suc_khoe');

        // 5. Xóa
        $deleteResponse = $this->deleteJson(route('nghia-vu-quan-su.destroy', $nvqsId));
        $deleteResponse->assertStatus(200);

        $this->assertNull(NghiaVuQuanSu::find($nvqsId));
    }
}
