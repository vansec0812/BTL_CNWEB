<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class HoKhauTest extends TestCase
{
    use RefreshDatabase;

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

        $this->get(route('ho-khau.index'))
            ->assertOk()
            ->assertSee('Danh sách sổ hộ khẩu')
            ->assertSee('HK123456')
            ->assertSee('MH123456');
    }

    public function test_create_page_renders_form(): void
    {
        $this->get(route('ho-khau.create'))
            ->assertOk()
            ->assertSee('Thêm sổ hộ khẩu mới');
    }

    public function test_store_creates_hokhau_record(): void
    {
        $response = $this->post(route('ho-khau.store'), [
            'so_so_ho_khau' => 'HK999999',
            'ma_ho' => 'MH999999',
            'dia_chi_thuong_tru' => 'Thôn 2, Xã Quốc Oai',
            'thon_xom' => 'Thôn 2',
            'phan_loai' => 'tam_tru',
            'so_thanh_vien' => 4,
            'trang_thai' => 'hoat_dong',
            'ngay_lap_so' => '2026-06-01',
        ]);

        $response->assertRedirect(route('ho-khau.index'));
        $this->assertDatabaseHas('ho_khau', [
            'so_so_ho_khau' => 'HK999999',
            'ma_ho' => 'MH999999',
            'dia_chi_thuong_tru' => 'Thôn 2, Xã Quốc Oai',
            'phan_loai' => 'tam_tru',
        ]);
    }

    public function test_store_validation_errors(): void
    {
        $this->post(route('ho-khau.store'), [])
            ->assertSessionHasErrors(['so_so_ho_khau', 'ma_ho', 'dia_chi_thuong_tru', 'phan_loai', 'trang_thai']);
    }

    public function test_edit_page_renders_form(): void
    {
        $hoKhauId = DB::table('ho_khau')->insertGetId([
            'so_so_ho_khau' => 'HK777777',
            'ma_ho' => 'MH777777',
            'dia_chi_thuong_tru' => 'Thôn 1, Xã Quốc Oai',
            'phan_loai' => 'thuong_tru',
            'trang_thai' => 'hoat_dong',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->get(route('ho-khau.edit', $hoKhauId))
            ->assertOk()
            ->assertSee('Sửa sổ hộ khẩu: MH777777');
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

        $response = $this->put(route('ho-khau.update', $hoKhauId), [
            'so_so_ho_khau' => 'HK888888-EDITED',
            'ma_ho' => 'MH888888',
            'dia_chi_thuong_tru' => 'Thôn 3, Xã Quốc Oai',
            'thon_xom' => 'Thôn 3',
            'phan_loai' => 'tam_vang',
            'so_thanh_vien' => 2,
            'trang_thai' => 'da_giai_the',
        ]);

        $response->assertRedirect(route('ho-khau.index'));
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

        $response = $this->delete(route('ho-khau.destroy', $hoKhauId));
        $response->assertRedirect(route('ho-khau.index'));
        $this->assertSoftDeleted('ho_khau', ['id' => $hoKhauId]);
    }
}
