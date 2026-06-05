<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * UserSeeder - Người 1
 * Tạo tài khoản cán bộ mẫu cho từng vai trò trong hệ thống.
 * Tất cả thành viên nhóm sẽ có cùng tài khoản để test.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin Hệ thống',
                'email' => 'admin@ubnd-xa.vn',
                'password' => Hash::make('Admin@123456'),
                'so_cccd' => '001090123456',
                'gioi_tinh' => 'nam',
                'ngay_sinh' => '1990-01-15',
                'so_dien_thoai' => '0987654321',
                'chuc_vu' => 'Quản trị viên',
                'dia_chi' => 'UBND Xã Quốc Oai',
                'que_quan' => 'Hà Nội',
                'trang_thai' => 'active',
            ],
            [
                'name' => 'Cán bộ Tư pháp',
                'email' => 'tupháp@ubnd-xa.vn',
                'password' => Hash::make('CanBo@123456'),
                'so_cccd' => '001092123457',
                'gioi_tinh' => 'nu',
                'ngay_sinh' => '1992-05-20',
                'so_dien_thoai' => '0976543210',
                'chuc_vu' => 'Cán bộ Tư pháp - Hộ tịch',
                'dia_chi' => 'Thôn 1, Xã Quốc Oai',
                'que_quan' => 'Hà Nội',
                'trang_thai' => 'active',
            ],
            [
                'name' => 'Cán bộ Lao động - TB&XH',
                'email' => 'laodong@ubnd-xa.vn',
                'password' => Hash::make('CanBo@123456'),
                'so_cccd' => '001088123458',
                'gioi_tinh' => 'nam',
                'ngay_sinh' => '1988-10-30',
                'so_dien_thoai' => '0965432109',
                'chuc_vu' => 'Cán bộ Lao động - TB&XH',
                'dia_chi' => 'Thôn 2, Xã Quốc Oai',
                'que_quan' => 'Hòa Bình',
                'trang_thai' => 'active',
            ],
            [
                'name' => 'Cán bộ Địa chính',
                'email' => 'diachinh@ubnd-xa.vn',
                'password' => Hash::make('CanBo@123456'),
                'so_cccd' => '001085123459',
                'gioi_tinh' => 'nam',
                'ngay_sinh' => '1985-07-22',
                'so_dien_thoai' => '0954321098',
                'chuc_vu' => 'Cán bộ Địa chính - Xây dựng',
                'dia_chi' => 'Thôn 3, Xã Quốc Oai',
                'que_quan' => 'Hà Nội',
                'trang_thai' => 'active',
            ],
            [
                'name' => 'Trưởng thôn Đoàn Kết',
                'email' => 'truongthon1@ubnd-xa.vn',
                'password' => Hash::make('TruongThon@123'),
                'so_cccd' => '001078123460',
                'gioi_tinh' => 'nam',
                'ngay_sinh' => '1978-12-05',
                'so_dien_thoai' => '0943210987',
                'chuc_vu' => 'Trưởng thôn Đoàn Kết',
                'dia_chi' => 'Thôn Đoàn Kết, Xã Quốc Oai',
                'que_quan' => 'Hà Nội',
                'trang_thai' => 'active',
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info('✅ Đã tạo '.count($users).' tài khoản cán bộ mẫu.');
        $this->command->info('   👑 Admin: admin@ubnd-xa.vn / Admin@123456');
        $this->command->info('   👤 Cán bộ: *@ubnd-xa.vn / CanBo@123456');
    }
}
