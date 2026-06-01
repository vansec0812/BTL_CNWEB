<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThueVaPhiSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $records = [];
        $nam = 2024;

        // Phí vệ sinh môi trường cho tất cả 10 hộ
        for ($hoId = 1; $hoId <= 10; $hoId++) {
            $records[] = [
                'ho_khau_id'           => $hoId,
                'nam'                  => $nam,
                'loai_khoan_thu'       => 'phi_ve_sinh_moi_truong',
                'so_tien_phai_nop'     => 120000,
                'so_tien_da_nop'       => ($hoId <= 7) ? 120000 : 0,
                'trang_thai_thanh_toan'=> ($hoId <= 7) ? 'da_nop_du' : 'chua_nop',
                'han_nop'              => '2024-03-31',
                'ngay_nop_thuc_te'     => ($hoId <= 7) ? '2024-03-20' : null,
                'nguoi_thu_id'         => 4,
                'created_at'           => $now,
                'updated_at'           => $now,
            ];
        }

        // Quỹ khuyến học cho một số hộ
        $hoQuyCKH = [1, 2, 3, 6, 9];
        foreach ($hoQuyCKH as $hoId) {
            $records[] = [
                'ho_khau_id'           => $hoId,
                'nam'                  => $nam,
                'loai_khoan_thu'       => 'quy_khuyen_hoc',
                'so_tien_phai_nop'     => 50000,
                'so_tien_da_nop'       => 50000,
                'trang_thai_thanh_toan'=> 'da_nop_du',
                'han_nop'              => '2024-06-30',
                'ngay_nop_thuc_te'     => '2024-05-15',
                'nguoi_thu_id'         => 4,
                'created_at'           => $now,
                'updated_at'           => $now,
            ];
        }

        DB::table('thue_va_phi_dia_phuong')->insert($records);
        $this->command->info('✅ Đã tạo ' . count($records) . ' bản ghi thuế/phí mẫu.');
    }
}
