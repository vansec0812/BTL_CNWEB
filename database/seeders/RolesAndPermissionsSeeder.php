<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Tạo Permissions
        $permissions = [
            'manage_users',
            'view_audit_logs',

            // Phân hệ Hộ tịch & Cư trú
            'view_ho_khau',
            'manage_ho_khau',
            'view_nhan_khau',
            'manage_nhan_khau',

            // Phân hệ Lao động & Doanh nghiệp
            'view_lao_dong',
            'manage_lao_dong',

            // Phân hệ An sinh & Trợ cấp
            'view_an_sinh',
            'manage_an_sinh',

            // Phân hệ Nghĩa vụ quân sự
            'view_nghia_vu',
            'manage_nghia_vu',

            // Phân hệ Đất đai & Thuế phí
            'view_dat_dai',
            'manage_dat_dai',
        ];

        foreach ($permissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web');
        }

        // 2. Tạo Roles và gán permissions
        
        // Admin Hệ thống
        $admin = Role::findOrCreate('admin', 'web');
        $admin->syncPermissions(Permission::all());

        // Cán bộ Tư pháp (Hộ tịch & Cư trú)
        $tuPhap = Role::findOrCreate('tu_phap', 'web');
        $tuPhap->syncPermissions([
            'view_ho_khau', 'manage_ho_khau',
            'view_nhan_khau', 'manage_nhan_khau',
            'view_lao_dong',
            'view_an_sinh',
            'view_nghia_vu',
            'view_dat_dai',
        ]);

        // Cán bộ Lao động
        $laoDong = Role::findOrCreate('lao_dong', 'web');
        $laoDong->syncPermissions([
            'view_ho_khau',
            'view_nhan_khau',
            'view_lao_dong', 'manage_lao_dong',
            'view_an_sinh', 'manage_an_sinh',
            'view_nghia_vu',
            'view_dat_dai',
        ]);

        // Cán bộ Địa chính
        $diaChinh = Role::findOrCreate('dia_chinh', 'web');
        $diaChinh->syncPermissions([
            'view_ho_khau',
            'view_nhan_khau',
            'view_lao_dong',
            'view_an_sinh',
            'view_nghia_vu',
            'view_dat_dai', 'manage_dat_dai',
        ]);

        // Cán bộ Quân sự
        $quanSu = Role::findOrCreate('quan_su', 'web');
        $quanSu->syncPermissions([
            'view_ho_khau',
            'view_nhan_khau',
            'view_lao_dong',
            'view_an_sinh',
            'view_nghia_vu', 'manage_nghia_vu',
            'view_dat_dai',
        ]);

        // Trưởng thôn
        $truongThon = Role::findOrCreate('truong_thon', 'web');
        $truongThon->syncPermissions([
            'view_ho_khau',
            'view_nhan_khau',
            'view_lao_dong',
            'view_an_sinh',
            'view_nghia_vu',
            'view_dat_dai',
        ]);

        // 3. Gán Role cho tài khoản trong UserSeeder
        $userAdmin = User::where('email', 'admin@ubnd-xa.vn')->first();
        if ($userAdmin) {
            $userAdmin->assignRole('admin');
        }

        $userTuPhap = User::where('email', 'tupháp@ubnd-xa.vn')->first();
        if ($userTuPhap) {
            $userTuPhap->assignRole('tu_phap');
        }

        $userLaoDong = User::where('email', 'laodong@ubnd-xa.vn')->first();
        if ($userLaoDong) {
            $userLaoDong->assignRole('lao_dong');
        }

        $userDiaChinh = User::where('email', 'diachinh@ubnd-xa.vn')->first();
        if ($userDiaChinh) {
            $userDiaChinh->assignRole('dia_chinh');
        }

        $userTruongThon = User::where('email', 'truongthon1@ubnd-xa.vn')->first();
        if ($userTruongThon) {
            $userTruongThon->assignRole('truong_thon');
        }

        $userQuanSu = User::where('email', 'quansu@ubnd-xa.vn')->first();
        if ($userQuanSu) {
            $userQuanSu->assignRole('quan_su');
        }

        $this->command->info('✅ Đã khởi tạo vai trò và phân quyền thành công.');
    }
}
