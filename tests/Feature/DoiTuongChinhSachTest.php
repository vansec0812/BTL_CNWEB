<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DoiTuongChinhSachTest extends TestCase
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

    public function test_index_page_renders_policy_records(): void
    {
        $nhanKhauId = $this->createNhanKhau('Võ Thị Chính Sách');
        DB::table('doi_tuong_chinh_sach')->insert([
            'nhan_khau_id' => $nhanKhauId,
            'loai_chinh_sach' => 'thuong_binh',
            'so_quyet_dinh_cong_nhan' => 'QD-TEST-001',
            'trang_thai' => 'dang_huong_che_do',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get(route('doi-tuong-chinh-sach.index'))
            ->assertOk()
            ->assertSee('Quản lý Diện chính sách')
            ->assertSee('Võ Thị Chính Sách')
            ->assertSee('QD-TEST-001');
    }

    public function test_index_page_renders_policy_records_for_soft_deleted_people(): void
    {
        $nhanKhauId = $this->createNhanKhau('Phạm Văn Đã Chuyển Hồ Sơ');
        DB::table('doi_tuong_chinh_sach')->insert([
            'nhan_khau_id' => $nhanKhauId,
            'loai_chinh_sach' => 'nguoi_co_cong',
            'so_quyet_dinh_cong_nhan' => 'QD-TEST-SOFT',
            'trang_thai' => 'dang_huong_che_do',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('nhan_khau')->where('id', $nhanKhauId)->update(['deleted_at' => now()]);

        $this->get(route('doi-tuong-chinh-sach.index'))
            ->assertOk()
            ->assertSee('Phạm Văn Đã Chuyển Hồ Sơ')
            ->assertSee('QD-TEST-SOFT');
    }

    public function test_create_page_renders_active_people_select(): void
    {
        $this->createNhanKhau('Nguyễn Văn Có Công');
        $this->createNhanKhau('Nguyễn Văn Đã Mất', 'da_mat');

        $this->get(route('doi-tuong-chinh-sach.create'))
            ->assertOk()
            ->assertSee('Thêm hồ sơ diện chính sách')
            ->assertSee('Nguyễn Văn Có Công')
            ->assertDontSee('Nguyễn Văn Đã Mất');
    }

    public function test_edit_page_keeps_current_person_when_person_is_deceased(): void
    {
        $nhanKhauId = $this->createNhanKhau('Bùi Văn Hưởng Chế Độ');
        $recordId = DB::table('doi_tuong_chinh_sach')->insertGetId([
            'nhan_khau_id' => $nhanKhauId,
            'loai_chinh_sach' => 'thuong_binh',
            'so_quyet_dinh_cong_nhan' => 'QD-TEST-DA-MAT',
            'trang_thai' => 'dang_huong_che_do',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('nhan_khau')->where('id', $nhanKhauId)->update(['trang_thai' => 'da_mat']);

        $this->get(route('doi-tuong-chinh-sach.edit', $recordId))
            ->assertOk()
            ->assertSee('Bùi Văn Hưởng Chế Độ')
            ->assertSee('QD-TEST-DA-MAT');
    }

    public function test_index_filters_policy_records(): void
    {
        $matchingNhanKhauId = $this->createNhanKhau('Đặng Thị Lọc Đúng');
        $otherNhanKhauId = $this->createNhanKhau('Đặng Thị Lọc Sai');

        DB::table('doi_tuong_chinh_sach')->insert([
            [
                'nhan_khau_id' => $matchingNhanKhauId,
                'loai_chinh_sach' => 'thuong_binh',
                'so_quyet_dinh_cong_nhan' => 'QD-FILTER-OK',
                'trang_thai' => 'dang_huong_che_do',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nhan_khau_id' => $otherNhanKhauId,
                'loai_chinh_sach' => 'benh_binh',
                'so_quyet_dinh_cong_nhan' => 'QD-FILTER-NO',
                'trang_thai' => 'ngung_huong',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->get(route('doi-tuong-chinh-sach.index', [
            'q' => 'Lọc Đúng',
            'loai_chinh_sach' => 'thuong_binh',
            'trang_thai' => 'dang_huong_che_do',
        ]))
            ->assertOk()
            ->assertSee('Đặng Thị Lọc Đúng')
            ->assertSee('QD-FILTER-OK')
            ->assertDontSee('Đặng Thị Lọc Sai')
            ->assertDontSee('QD-FILTER-NO');
    }

    public function test_store_requires_valid_policy_fields(): void
    {
        $this->post(route('doi-tuong-chinh-sach.store'), [])
            ->assertSessionHasErrors(['nhan_khau_id', 'loai_chinh_sach', 'trang_thai']);
    }

    public function test_store_rejects_invalid_policy_values(): void
    {
        $nhanKhauId = $this->createNhanKhau('Hoàng Văn Kiểm Tra');

        $this->post(route('doi-tuong-chinh-sach.store'), [
            'nhan_khau_id' => $nhanKhauId,
            'loai_chinh_sach' => 'sai_loai',
            'ty_le_thuong_tat' => 150,
            'muc_tro_cap_hang_thang' => -1,
            'trang_thai' => 'sai_trang_thai',
        ])->assertSessionHasErrors([
            'loai_chinh_sach',
            'ty_le_thuong_tat',
            'muc_tro_cap_hang_thang',
            'trang_thai',
        ]);
    }

    public function test_store_rejects_inactive_people(): void
    {
        $nhanKhauId = $this->createNhanKhau('Đỗ Văn Không Hợp Lệ', 'da_mat');

        $this->post(route('doi-tuong-chinh-sach.store'), [
            'nhan_khau_id' => $nhanKhauId,
            'loai_chinh_sach' => 'thuong_binh',
            'trang_thai' => 'dang_huong_che_do',
        ])->assertSessionHasErrors(['nhan_khau_id']);
    }

    public function test_store_creates_policy_record(): void
    {
        $nhanKhauId = $this->createNhanKhau('Trần Thị Người Có Công');

        $response = $this->post(route('doi-tuong-chinh-sach.store'), [
            'nhan_khau_id' => $nhanKhauId,
            'loai_chinh_sach' => 'nguoi_co_cong',
            'so_quyet_dinh_cong_nhan' => 'QD-TEST-002',
            'ngay_cong_nhan' => '2020-07-27',
            'co_quan_cap' => 'UBND xã Quốc Oai',
            'muc_tro_cap_hang_thang' => 1500000,
            'trang_thai' => 'dang_huong_che_do',
        ]);

        $response->assertRedirect(route('doi-tuong-chinh-sach.index'));
        $this->assertDatabaseHas('doi_tuong_chinh_sach', [
            'nhan_khau_id' => $nhanKhauId,
            'loai_chinh_sach' => 'nguoi_co_cong',
            'so_quyet_dinh_cong_nhan' => 'QD-TEST-002',
        ]);
    }

    public function test_update_and_destroy_policy_record(): void
    {
        $nhanKhauId = $this->createNhanKhau('Lê Văn Thương Binh');
        $recordId = DB::table('doi_tuong_chinh_sach')->insertGetId([
            'nhan_khau_id' => $nhanKhauId,
            'loai_chinh_sach' => 'thuong_binh',
            'so_quyet_dinh_cong_nhan' => 'QD-OLD',
            'trang_thai' => 'dang_huong_che_do',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->put(route('doi-tuong-chinh-sach.update', $recordId), [
            'nhan_khau_id' => $nhanKhauId,
            'loai_chinh_sach' => 'benh_binh',
            'so_quyet_dinh_cong_nhan' => 'QD-NEW',
            'ty_le_thuong_tat' => 25.5,
            'muc_tro_cap_hang_thang' => 1800000,
            'trang_thai' => 'ngung_huong',
        ])->assertRedirect(route('doi-tuong-chinh-sach.index'));

        $this->assertDatabaseHas('doi_tuong_chinh_sach', [
            'id' => $recordId,
            'loai_chinh_sach' => 'benh_binh',
            'so_quyet_dinh_cong_nhan' => 'QD-NEW',
            'trang_thai' => 'ngung_huong',
        ]);

        $this->delete(route('doi-tuong-chinh-sach.destroy', $recordId))
            ->assertRedirect(route('doi-tuong-chinh-sach.index'));

        $this->assertSoftDeleted('doi_tuong_chinh_sach', ['id' => $recordId]);
    }

    public function test_api_index_returns_json(): void
    {
        $nhanKhauId = $this->createNhanKhau('Võ Thị Chính Sách API');
        DB::table('doi_tuong_chinh_sach')->insert([
            'nhan_khau_id' => $nhanKhauId,
            'loai_chinh_sach' => 'thuong_binh',
            'so_quyet_dinh_cong_nhan' => 'QD-API-001',
            'trang_thai' => 'dang_huong_che_do',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson(route('api.doi-tuong-chinh-sach.index'));
        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'nhan_khau_id',
                            'loai_chinh_sach',
                            'so_quyet_dinh_cong_nhan',
                        ],
                    ],
                ],
                'stats',
            ])
            ->assertJsonFragment(['so_quyet_dinh_cong_nhan' => 'QD-API-001']);
    }

    public function test_api_create_returns_json(): void
    {
        $response = $this->getJson(route('doi-tuong-chinh-sach.create'));
        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'nhanKhau',
                    'loaiChinhSach',
                    'trangThai',
                ],
            ]);
    }

    public function test_api_store_returns_json(): void
    {
        $nhanKhauId = $this->createNhanKhau('Trần Thị Người Có Công API');

        $response = $this->postJson(route('api.doi-tuong-chinh-sach.store'), [
            'nhan_khau_id' => $nhanKhauId,
            'loai_chinh_sach' => 'nguoi_co_cong',
            'so_quyet_dinh_cong_nhan' => 'QD-API-002',
            'ngay_cong_nhan' => '2020-07-27',
            'co_quan_cap' => 'UBND xã Quốc Oai',
            'muc_tro_cap_hang_thang' => 1500000,
            'trang_thai' => 'dang_huong_che_do',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'nhan_khau_id',
                    'loai_chinh_sach',
                    'so_quyet_dinh_cong_nhan',
                ],
            ])
            ->assertJsonFragment(['so_quyet_dinh_cong_nhan' => 'QD-API-002']);

        $this->assertDatabaseHas('doi_tuong_chinh_sach', [
            'nhan_khau_id' => $nhanKhauId,
            'so_quyet_dinh_cong_nhan' => 'QD-API-002',
        ]);
    }

    public function test_api_show_returns_json(): void
    {
        $nhanKhauId = $this->createNhanKhau('Lê Văn Thương Binh API');
        $recordId = DB::table('doi_tuong_chinh_sach')->insertGetId([
            'nhan_khau_id' => $nhanKhauId,
            'loai_chinh_sach' => 'thuong_binh',
            'so_quyet_dinh_cong_nhan' => 'QD-API-003',
            'trang_thai' => 'dang_huong_che_do',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson(route('api.doi-tuong-chinh-sach.show', $recordId));
        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'nhan_khau_id',
                    'loai_chinh_sach',
                    'nhan_khau',
                ],
            ])
            ->assertJsonFragment(['so_quyet_dinh_cong_nhan' => 'QD-API-003']);
    }

    public function test_api_edit_returns_json(): void
    {
        $nhanKhauId = $this->createNhanKhau('Bùi Văn Hưởng Chế Độ API');
        $recordId = DB::table('doi_tuong_chinh_sach')->insertGetId([
            'nhan_khau_id' => $nhanKhauId,
            'loai_chinh_sach' => 'thuong_binh',
            'so_quyet_dinh_cong_nhan' => 'QD-API-004',
            'trang_thai' => 'dang_huong_che_do',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson(route('doi-tuong-chinh-sach.edit', $recordId));
        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'record',
                    'nhanKhau',
                    'loaiChinhSach',
                    'trangThai',
                ],
            ]);
    }

    public function test_api_update_returns_json(): void
    {
        $nhanKhauId = $this->createNhanKhau('Lê Văn Thương Binh API 2');
        $recordId = DB::table('doi_tuong_chinh_sach')->insertGetId([
            'nhan_khau_id' => $nhanKhauId,
            'loai_chinh_sach' => 'thuong_binh',
            'so_quyet_dinh_cong_nhan' => 'QD-API-OLD',
            'trang_thai' => 'dang_huong_che_do',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->putJson(route('api.doi-tuong-chinh-sach.update', $recordId), [
            'nhan_khau_id' => $nhanKhauId,
            'loai_chinh_sach' => 'benh_binh',
            'so_quyet_dinh_cong_nhan' => 'QD-API-NEW',
            'ty_le_thuong_tat' => 25.5,
            'muc_tro_cap_hang_thang' => 1800000,
            'trang_thai' => 'ngung_huong',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'loai_chinh_sach',
                    'so_quyet_dinh_cong_nhan',
                    'trang_thai',
                ],
            ])
            ->assertJsonFragment(['so_quyet_dinh_cong_nhan' => 'QD-API-NEW']);
    }

    public function test_api_destroy_returns_json(): void
    {
        $nhanKhauId = $this->createNhanKhau('Lê Văn Thương Binh API 3');
        $recordId = DB::table('doi_tuong_chinh_sach')->insertGetId([
            'nhan_khau_id' => $nhanKhauId,
            'loai_chinh_sach' => 'thuong_binh',
            'so_quyet_dinh_cong_nhan' => 'QD-API-TO-DELETE',
            'trang_thai' => 'dang_huong_che_do',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->deleteJson(route('api.doi-tuong-chinh-sach.destroy', $recordId));
        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
            ]);

        $this->assertSoftDeleted('doi_tuong_chinh_sach', ['id' => $recordId]);
    }

    private function createNhanKhau(string $hoTen, string $trangThai = 'hoat_dong'): int
    {
        $hoKhauId = DB::table('ho_khau')->insertGetId([
            'so_so_ho_khau' => 'HK'.str()->random(8),
            'ma_ho' => 'MH'.str()->random(8),
            'dia_chi_thuong_tru' => 'Thôn 1, Xã Quốc Oai',
            'thon_xom' => 'Thôn 1',
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
            'ngay_sinh' => '1970-01-01',
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
