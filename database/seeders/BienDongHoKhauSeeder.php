<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BienDongHoKhauSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        // Insert từng dòng riêng để tránh column count mismatch khi các row có số cột khác nhau
        DB::table('bien_dong_ho_khau')->insert([
            'loai_bien_dong'     => 'khai_sinh',
            'ho_khau_nguon_id'   => 1,
            'ho_khau_dich_id'    => null,
            'nhan_khau_id'       => 4,
            'ngay_bien_dong'     => '2005-06-30',
            'ly_do'              => 'Khai sinh thành viên mới',
            'dia_chi_chuyen_den' => null,
            'nguoi_thuc_hien_id' => 1,
            'created_at'         => $now,
            'updated_at'         => $now,
        ]);

        DB::table('bien_dong_ho_khau')->insert([
            'loai_bien_dong'     => 'chuyen_den',
            'ho_khau_nguon_id'   => 5,
            'ho_khau_dich_id'    => null,
            'nhan_khau_id'       => 15,
            'ngay_bien_dong'     => '2023-06-01',
            'ly_do'              => 'Chuyển đến làm việc tại khu công nghiệp địa phương',
            'dia_chi_chuyen_den' => 'Số 23, Xóm 4, Thôn Đoàn Kết, Xã An Phú',
            'nguoi_thuc_hien_id' => 2,
            'created_at'         => $now,
            'updated_at'         => $now,
        ]);
        $this->command->info('✅ Đã tạo biến động hộ khẩu mẫu.');
    }
}
