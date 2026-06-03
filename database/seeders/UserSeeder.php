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
            ],
            [
                'name' => 'Cán bộ Tư pháp',
                'email' => 'tupháp@ubnd-xa.vn',
                'password' => Hash::make('CanBo@123456'),
            ],
            [
                'name' => 'Cán bộ Lao động - TB&XH',
                'email' => 'laodong@ubnd-xa.vn',
                'password' => Hash::make('CanBo@123456'),
            ],
            [
                'name' => 'Cán bộ Địa chính',
                'email' => 'diachinh@ubnd-xa.vn',
                'password' => Hash::make('CanBo@123456'),
            ],
            [
                'name' => 'Trưởng thôn Đoàn Kết',
                'email' => 'truongthon1@ubnd-xa.vn',
                'password' => Hash::make('TruongThon@123'),
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
