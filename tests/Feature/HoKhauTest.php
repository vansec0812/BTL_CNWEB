<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class HoKhauTest extends TestCase
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

    public function test_index_page_renders_hokhau_records(): void
    {
        $hoKhauId = DB::table('ho_khau')->insertGetId([
            'so_so_ho_khau' => 'HK123456',
            'ma_ho' => 'MH123456',
            'dia_chi_thuong_tru' => 'Thôn 1, Xã Quốc Oai',
            'thon_xom' => 'Thôn 1',
            'phan_loai' => 'thuong_tru',
            'so_thanh_vien' => 3,
            'trang_thai' => 'hoat_dong',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson(route('api.ho-khau.index'));
        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonFragment(['so_so_ho_khau' => 'HK123456']);
    }

    public function test_store_creates_hokhau_record(): void
    {
        $response = $this->postJson(route('api.ho-khau.store'), [
            'so_so_ho_khau' => 'HK999999',
            'ma_ho' => 'MH999999',
            'dia_chi_thuong_tru' => 'Thôn 2, Xã Quốc Oai',
            'thon_xom' => 'Thôn 2',
            'phan_loai' => 'tam_tru',
            'so_thanh_vien' => 4,
            'trang_thai' => 'hoat_dong',
            'ngay_lap_so' => '2026-06-01',
        ]);

        $response->assertCreated();
        $response->assertJson([
            'success' => true,
            'message' => 'Đã tạo sổ hộ khẩu mới thành công.',
        ]);

        $this->assertDatabaseHas('ho_khau', [
            'so_so_ho_khau' => 'HK999999',
            'ma_ho' => 'MH999999',
            'dia_chi_thuong_tru' => 'Thôn 2, Xã Quốc Oai',
            'phan_loai' => 'tam_tru',
        ]);
    }

    public function test_store_validation_errors(): void
    {
        $response = $this->postJson(route('api.ho-khau.store'), []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['so_so_ho_khau', 'ma_ho', 'dia_chi_thuong_tru', 'phan_loai', 'trang_thai']);
    }

    public function test_update_updates_hokhau_record(): void
    {
        $hoKhauId = DB::table('ho_khau')->insertGetId([
            'so_so_ho_khau' => 'HK888888',
            'ma_ho' => 'MH888888',
            'dia_chi_thuong_tru' => 'Thôn 1, Xã Quốc Oai',
            'phan_loai' => 'thuong_tru',
            'trang_thai' => 'hoat_dong',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->putJson(route('api.ho-khau.update', $hoKhauId), [
            'so_so_ho_khau' => 'HK888888-EDITED',
            'ma_ho' => 'MH888888',
            'dia_chi_thuong_tru' => 'Thôn 3, Xã Quốc Oai',
            'thon_xom' => 'Thôn 3',
            'phan_loai' => 'tam_vang',
            'so_thanh_vien' => 2,
            'trang_thai' => 'da_giai_the',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'message' => 'Cập nhật sổ hộ khẩu thành công.',
        ]);

        $this->assertDatabaseHas('ho_khau', [
            'id' => $hoKhauId,
            'so_so_ho_khau' => 'HK888888-EDITED',
            'dia_chi_thuong_tru' => 'Thôn 3, Xã Quốc Oai',
            'phan_loai' => 'tam_vang',
            'trang_thai' => 'da_giai_the',
        ]);
    }

    public function test_destroy_deletes_hokhau_record(): void
    {
        $hoKhauId = DB::table('ho_khau')->insertGetId([
            'so_so_ho_khau' => 'HK555555',
            'ma_ho' => 'MH555555',
            'dia_chi_thuong_tru' => 'Thôn 1, Xã Quốc Oai',
            'phan_loai' => 'thuong_tru',
            'trang_thai' => 'hoat_dong',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->deleteJson(route('api.ho-khau.destroy', $hoKhauId));
        $response->assertOk();
        $this->assertSoftDeleted('ho_khau', ['id' => $hoKhauId]);
    }
}
