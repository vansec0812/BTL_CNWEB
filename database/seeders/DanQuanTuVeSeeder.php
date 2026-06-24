<?php

namespace Database\Seeders;

use App\Models\NhanKhau;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DanQuanTuVeSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Lấy danh sách nhân khẩu còn sống, không bị xóa tạm, và không đang nhập ngũ/trúng tuyển nghĩa vụ quân sự
        $eligibleNhanKhau = NhanKhau::query()
            ->whereNull('deleted_at')
            ->where('trang_thai', '!=', 'da_mat')
            ->whereDoesntHave('nghiaVuQuanSu', function ($q) {
                $q->whereIn('trang_thai_nvqs', ['da_nhap_ngu', 'trung_tuyen']);
            })
            ->limit(5)
            ->get();

        if ($eligibleNhanKhau->isEmpty()) {
            $this->command->warn('⚠️ Không tìm thấy nhân khẩu hợp lệ để tạo Dân quân tự vệ.');

            return;
        }

        $positions = ['Chiến sĩ', 'Tổ trưởng', 'Thôn đội trưởng', 'Tiểu đội trưởng', 'Trung đội trưởng'];
        $units = ['Trung đội Dân quân Cơ động xã', 'Tiểu đội Dân quân tại chỗ Thôn 1', 'Tiểu đội Dân quân tại chỗ Thôn 2'];

        $records = [];
        foreach ($eligibleNhanKhau as $index => $nhanKhau) {
            $chucVu = $positions[$index % count($positions)];
            $donVi = $units[$index % count($units)];
            $ngayGiaNhap = now()->subYears($index + 1)->format('Y-m-d');

            // Một số người đã kết thúc, một số đang phục vụ
            $trangThai = ($index === 0) ? 'da_hoan_thanh' : 'dang_phuc_vu';
            $ngayKetThuc = ($trangThai === 'da_hoan_thanh') ? now()->subDays(10)->format('Y-m-d') : null;

            $records[] = [
                'nhan_khau_id' => $nhanKhau->id,
                'chuc_vu' => $chucVu,
                'don_vi' => $donVi,
                'ngay_gia_nhap' => $ngayGiaNhap,
                'ngay_ket_thuc' => $ngayKetThuc,
                'trang_thai' => $trangThai,
                'ghi_chu' => 'Dữ liệu mẫu seeder tự động.',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('dan_quan_tu_ve')->insert($records);
        $this->command->info('✅ Đã tạo '.count($records).' thành viên Dân quân tự vệ mẫu.');
    }
}
