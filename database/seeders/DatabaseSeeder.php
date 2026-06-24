<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Chạy: php artisan db:seed
     * Hoặc kết hợp migrate: php artisan migrate:fresh --seed
     *
     * Thứ tự seeder PHẢI theo đúng dependency (bảng cha trước, bảng con sau)
     */
    public function run(): void
    {
        $this->call([
            // ─── NGƯỜI 1: Hệ thống & Phân quyền ───────────────────────────
            UserSeeder::class,
            RolesAndPermissionsSeeder::class,

            // ─── NGƯỜI 2: Hộ khẩu & Nhân khẩu ─────────────────────────────
            HoKhauSeeder::class,       // Tạo hộ trước (chưa có chu_ho)
            NhanKhauSeeder::class,     // Tạo nhân khẩu và cập nhật chu_ho vào ho_khau
            BienDongHoKhauSeeder::class,
            TamTruTamVangSeeder::class,

            // ─── NGƯỜI 3: Lao động & Doanh nghiệp ──────────────────────────
            LaoDongSeeder::class,
            DoanhNghiepSeeder::class,
            KetNoiViecLamSeeder::class,

            // ─── NGƯỜI 4: An sinh, Y tế & Trợ cấp ─────────────────────────
            DoiTuongChinhSachSeeder::class,
            BaoTroXaHoiSeeder::class,
            DotTroCapSeeder::class,
            YTeNhanKhauSeeder::class,

            // ─── NGƯỜI 5: NVQS, Đất đai & Thuế phí ─────────────────────────
            NghiaVuQuanSuSeeder::class,
            DanQuanTuVeSeeder::class,
            DanQuanHoatDongSeeder::class,
            DatDaiTaiSanSeeder::class,
            ThueVaPhiSeeder::class,
            CoSoVatChatSeeder::class,
            AnNinhTratTuSeeder::class,
        ]);
    }
}
