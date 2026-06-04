<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TamTruTamVangSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        DB::table('tam_tru_tam_vang')->insert([
            [
                'nhan_khau_id' => 15, // Trịnh Văn Quý
                'loai' => 'tam_tru',
                'ngay_bat_dau' => '2023-06-01',
                'ngay_ket_thuc' => '2025-06-01',
                'dia_chi_cu_tru_thuc_te' => 'Số 23, Xóm 4, Thôn Đoàn Kết, Xã Quốc Oai',
                'ly_do' => 'Đến làm việc tại Công ty TNHH Bình Minh',
                'trang_thai' => 'dang_hieu_luc',
                'nguoi_xac_nhan_id' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nhan_khau_id' => 16, // Ngô Thị Rạng
                'loai' => 'tam_tru',
                'ngay_bat_dau' => '2023-06-01',
                'ngay_ket_thuc' => '2025-06-01',
                'dia_chi_cu_tru_thuc_te' => 'Số 23, Xóm 4, Thôn Đoàn Kết, Xã Quốc Oai',
                'ly_do' => 'Theo chồng làm việc',
                'trang_thai' => 'dang_hieu_luc',
                'nguoi_xac_nhan_id' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nhan_khau_id' => 21, // Phan Văn Uy
                'loai' => 'tam_vang',
                'ngay_bat_dau' => '2022-09-01',
                'ngay_ket_thuc' => null,
                'dia_chi_vang_mat' => 'TP. Hồ Chí Minh, Q. Bình Thạnh',
                'ly_do' => 'Đi làm việc tại TP.HCM',
                'trang_thai' => 'dang_hieu_luc',
                'nguoi_xac_nhan_id' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
        $this->command->info('✅ Đã tạo dữ liệu tạm trú/tạm vắng mẫu.');
    }
}
