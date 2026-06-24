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
        $response = $this->get(route('nghia-vu-quan-su.index'));

        $response->assertStatus(200)
            ->assertSee('Hồ sơ Nghĩa vụ quân sự')
            ->assertSee('Bộ lọc tìm kiếm');
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

        // 1. Kiểm tra preview danh sách quét
        $previewResponse = $this->getJson(route('nghia-vu-quan-su.scan-preview', [
            'nam_tuyen_quan' => 2026,
        ]));

        $previewResponse->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data');

        // 2. Thực hiện lưu những người được chọn (chỉ chọn nhanKhau1)
        $storeResponse = $this->post(route('nghia-vu-quan-su.scan-store'), [
            'nam_tuyen_quan' => 2026,
            'nhan_khau_ids' => [$nhanKhau1->id],
        ]);

        $storeResponse->assertStatus(302)
            ->assertRedirect(route('nghia-vu-quan-su.index'));

        // Công dân 1 được thêm thành công
        $this->assertDatabaseHas('nghia_vu_quan_su', [
            'nhan_khau_id' => $nhanKhau1->id,
            'trang_thai_nvqs' => 'du_dieu_kien',
        ]);

        // Công dân 2 KHÔNG được thêm vì không được chọn
        $this->assertDatabaseMissing('nghia_vu_quan_su', [
            'nhan_khau_id' => $nhanKhau2->id,
        ]);
    }

    public function test_store_nvqs_validation_error(): void
    {
        $response = $this->post(route('nghia-vu-quan-su.store'), []);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['nhan_khau_id']);
    }

    public function test_store_nvqs_female_validation_error(): void
    {
        $hoKhau = HoKhau::create([
            'so_so_ho_khau' => 'SHK999',
            'ma_ho' => 'MH999',
            'dia_chi_thuong_tru' => 'Thôn 99, Xã X',
            'phan_loai' => 'thuong_tru',
            'trang_thai' => 'hoat_dong',
        ]);

        $nhanKhauNu = NhanKhau::create([
            'ho_khau_id' => $hoKhau->id,
            'ho_ten' => 'Nguyễn Thị Nữ Test',
            'cccd_cmnd' => '123456789088',
            'ngay_sinh' => '2005-05-15',
            'gioi_tinh' => 'nu',
            'dan_toc' => 'Kinh',
            'trinh_do_hoc_van' => 'thpt',
            'trang_thai' => 'hoat_dong',
        ]);

        $response = $this->post(route('nghia-vu-quan-su.store'), [
            'nhan_khau_id' => $nhanKhauNu->id,
            'nam_tuoi_tuyen_quan' => 2026,
            'trang_thai_nvqs' => 'du_dieu_kien',
        ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['nhan_khau_id']);
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

        $storeResponse = $this->post(route('nghia-vu-quan-su.store'), $storeData);

        $storeResponse->assertStatus(302)
            ->assertRedirect(route('nghia-vu-quan-su.index'));

        $this->assertDatabaseHas('nghia_vu_quan_su', [
            'nhan_khau_id' => $nhanKhau->id,
            'trang_thai_nvqs' => 'du_dieu_kien',
        ]);

        $nvqs = NghiaVuQuanSu::where('nhan_khau_id', $nhanKhau->id)->first();
        $this->assertNotNull($nvqs);

        // 3. Xem chi tiết HTML
        $showResponse = $this->get(route('nghia-vu-quan-su.show', $nvqs));
        $showResponse->assertStatus(200)
            ->assertSee('Nguyễn Test Binh')
            ->assertSee('Chi tiết hồ sơ NVQS');

        // 4. Cập nhật trạng thái
        $updateData = [
            'nhan_khau_id' => $nhanKhau->id,
            'trang_thai_nvqs' => 'tam_hoan',
            'ly_do_tam_hoan' => 'benh_tat_suc_khoe',
            'ngay_tam_hoan_den' => '2027-06-01',
        ];

        $updateResponse = $this->put(route('nghia-vu-quan-su.update', $nvqs), $updateData);
        $updateResponse->assertStatus(302)
            ->assertRedirect(route('nghia-vu-quan-su.index'));

        $this->assertDatabaseHas('nghia_vu_quan_su', [
            'id' => $nvqs->id,
            'trang_thai_nvqs' => 'tam_hoan',
            'ly_do_tam_hoan' => 'benh_tat_suc_khoe',
        ]);

        // 5. Xóa
        $deleteResponse = $this->delete(route('nghia-vu-quan-su.destroy', $nvqs));
        $deleteResponse->assertStatus(302)
            ->assertRedirect(route('nghia-vu-quan-su.index'));

        $this->assertNull(NghiaVuQuanSu::find($nvqs->id));
    }

    public function test_nvqs_page_handles_soft_deleted_nhan_khau(): void
    {
        $hoKhau = HoKhau::create([
            'so_so_ho_khau' => 'SHK003',
            'ma_ho' => 'MH003',
            'dia_chi_thuong_tru' => 'Thôn 3, Xã X',
            'phan_loai' => 'thuong_tru',
            'trang_thai' => 'hoat_dong',
        ]);

        $nhanKhau = NhanKhau::create([
            'ho_khau_id' => $hoKhau->id,
            'ho_ten' => 'Nguyễn Bị Xóa',
            'cccd_cmnd' => '123456789098',
            'ngay_sinh' => '2005-05-15',
            'gioi_tinh' => 'nam',
            'dan_toc' => 'Kinh',
            'trinh_do_hoc_van' => 'thpt',
            'trang_thai' => 'hoat_dong',
        ]);

        $nvqs = NghiaVuQuanSu::create([
            'nhan_khau_id' => $nhanKhau->id,
            'nam_tuoi_tuyen_quan' => 2026,
            'trang_thai_nvqs' => 'du_dieu_kien',
        ]);

        // Soft-delete the nhan khau
        $nhanKhau->delete();

        // Check index page renders correctly
        $response = $this->get(route('nghia-vu-quan-su.index'));
        $response->assertStatus(200)
            ->assertSee('Nguyễn Bị Xóa');

        // Check show page renders correctly
        $response = $this->get(route('nghia-vu-quan-su.show', $nvqs));
        $response->assertStatus(200)
            ->assertSee('Nguyễn Bị Xóa');
    }
}
