<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * HoKhauSeeder - Người 2
 * Tạo 10 hộ gia đình mẫu. chu_ho_nhan_khau_id sẽ được cập nhật sau bởi NhanKhauSeeder.
 */
class HoKhauSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $hoKhauList = [
            [
                'so_so_ho_khau'   => 'HK001',
                'ma_ho'           => 'MH2024001',
                'dia_chi_thuong_tru' => 'Số 15, Xóm 3, Thôn Đoàn Kết, Xã Quốc Oai',
                'thon_xom'        => 'Thôn Đoàn Kết',
                'phan_loai'       => 'thuong_tru',
                'so_thanh_vien'   => 4,
                'ngay_lap_so'     => '2015-03-10',
                'trang_thai'      => 'hoat_dong',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'so_so_ho_khau'   => 'HK002',
                'ma_ho'           => 'MH2024002',
                'dia_chi_thuong_tru' => 'Số 8, Xóm 1, Thôn Bình An, Xã Quốc Oai',
                'thon_xom'        => 'Thôn Bình An',
                'phan_loai'       => 'thuong_tru',
                'so_thanh_vien'   => 3,
                'ngay_lap_so'     => '2018-07-22',
                'trang_thai'      => 'hoat_dong',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'so_so_ho_khau'   => 'HK003',
                'ma_ho'           => 'MH2024003',
                'dia_chi_thuong_tru' => 'Số 42, Xóm 5, Thôn Phú Lợi, Xã Quốc Oai',
                'thon_xom'        => 'Thôn Phú Lợi',
                'phan_loai'       => 'thuong_tru',
                'so_thanh_vien'   => 5,
                'ngay_lap_so'     => '2010-01-05',
                'trang_thai'      => 'hoat_dong',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'so_so_ho_khau'   => 'HK004',
                'ma_ho'           => 'MH2024004',
                'dia_chi_thuong_tru' => 'Số 7, Xóm 2, Thôn Hòa Bình, Xã Quốc Oai',
                'thon_xom'        => 'Thôn Hòa Bình',
                'phan_loai'       => 'thuong_tru',
                'so_thanh_vien'   => 2,
                'ngay_lap_so'     => '2020-11-15',
                'trang_thai'      => 'hoat_dong',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'so_so_ho_khau'   => 'HK005',
                'ma_ho'           => 'MH2024005',
                'dia_chi_thuong_tru' => 'Số 23, Xóm 4, Thôn Đoàn Kết, Xã Quốc Oai',
                'thon_xom'        => 'Thôn Đoàn Kết',
                'phan_loai'       => 'tam_tru',
                'so_thanh_vien'   => 2,
                'ngay_lap_so'     => '2023-06-01',
                'trang_thai'      => 'hoat_dong',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'so_so_ho_khau'   => 'HK006',
                'ma_ho'           => 'MH2024006',
                'dia_chi_thuong_tru' => 'Số 11, Xóm 3, Thôn Bình An, Xã Quốc Oai',
                'thon_xom'        => 'Thôn Bình An',
                'phan_loai'       => 'thuong_tru',
                'so_thanh_vien'   => 6,
                'ngay_lap_so'     => '2005-08-20',
                'trang_thai'      => 'hoat_dong',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'so_so_ho_khau'   => 'HK007',
                'ma_ho'           => 'MH2024007',
                'dia_chi_thuong_tru' => 'Số 56, Xóm 1, Thôn Phú Lợi, Xã Quốc Oai',
                'thon_xom'        => 'Thôn Phú Lợi',
                'phan_loai'       => 'thuong_tru',
                'so_thanh_vien'   => 3,
                'ngay_lap_so'     => '2012-04-18',
                'trang_thai'      => 'hoat_dong',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'so_so_ho_khau'   => 'HK008',
                'ma_ho'           => 'MH2024008',
                'dia_chi_thuong_tru' => 'Số 3, Xóm 6, Thôn Hòa Bình, Xã Quốc Oai',
                'thon_xom'        => 'Thôn Hòa Bình',
                'phan_loai'       => 'thuong_tru',
                'so_thanh_vien'   => 1,
                'ngay_lap_so'     => '2019-02-14',
                'trang_thai'      => 'hoat_dong',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'so_so_ho_khau'   => 'HK009',
                'ma_ho'           => 'MH2024009',
                'dia_chi_thuong_tru' => 'Số 18, Xóm 2, Thôn Đoàn Kết, Xã Quốc Oai',
                'thon_xom'        => 'Thôn Đoàn Kết',
                'phan_loai'       => 'thuong_tru',
                'so_thanh_vien'   => 4,
                'ngay_lap_so'     => '2016-09-30',
                'trang_thai'      => 'hoat_dong',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'so_so_ho_khau'   => 'HK010',
                'ma_ho'           => 'MH2024010',
                'dia_chi_thuong_tru' => 'Số 29, Xóm 5, Thôn Bình An, Xã Quốc Oai',
                'thon_xom'        => 'Thôn Bình An',
                'phan_loai'       => 'thuong_tru',
                'so_thanh_vien'   => 3,
                'ngay_lap_so'     => '2014-12-05',
                'trang_thai'      => 'hoat_dong',
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
        ];

        DB::table('ho_khau')->insert($hoKhauList);
        $this->command->info('✅ Đã tạo ' . count($hoKhauList) . ' hộ khẩu mẫu.');
    }
}
