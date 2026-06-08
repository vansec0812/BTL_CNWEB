<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\DanQuanTuVe;

class DanQuanHoatDongSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $tuVes = DanQuanTuVe::all();

        if ($tuVes->isEmpty()) {
            $this->command->warn('⚠️ Không tìm thấy thành viên Dân quân tự vệ nào. Vui lòng chạy DanQuanTuVeSeeder trước.');
            return;
        }

        $activities = [
            // tap_huan activities
            [
                'loai_hoat_dong' => 'tap_huan',
                'ten_hoat_dong' => 'Tập huấn kỹ năng cứu hộ thiên tai và PCCC',
                'statuses' => ['tham_gia', 'vang_co_phep', 'vang_khong_phep'],
                'ghi_chu' => 'Tập huấn định kỳ cấp xã.',
            ],
            [
                'loai_hoat_dong' => 'tap_huan',
                'ten_hoat_dong' => 'Tập huấn bắn đạn thật dân quân tự vệ năm 2026',
                'statuses' => ['tham_gia', 'vang_co_phep', 'vang_khong_phep'],
                'ghi_chu' => 'Kiểm tra bắn đạn thật tại thao trường.',
            ],
            // truc_ban activities
            [
                'loai_hoat_dong' => 'truc_ban',
                'ten_hoat_dong' => 'Trực gác bảo vệ an ninh trật tự trụ sở UBND',
                'statuses' => ['da_truc', 'vang_mat'],
                'ghi_chu' => 'Ca trực ngày cuối tuần.',
            ],
            [
                'loai_hoat_dong' => 'truc_ban',
                'ten_hoat_dong' => 'Tuần tra đêm phòng chống tội phạm địa bàn',
                'statuses' => ['da_truc', 'vang_mat'],
                'ghi_chu' => 'Phối hợp với lực lượng công an xã.',
            ],
        ];

        $records = [];
        $count = 0;

        foreach ($tuVes as $index => $tuVe) {
            // Gán cho mỗi dân quân 3 hoạt động mẫu
            for ($i = 0; $i < 3; $i++) {
                $activityTemplate = $activities[($index + $i) % count($activities)];
                $statusList = $activityTemplate['statuses'];
                
                // Trạng thái ngẫu nhiên nhưng hợp lệ với loại hoạt động
                $trangThai = $statusList[($index + $i) % count($statusList)];
                $ngayThucHien = now()->subDays(($index * 2) + $i + 1)->format('Y-m-d');

                $records[] = [
                    'dan_quan_tu_ve_id' => $tuVe->id,
                    'loai_hoat_dong' => $activityTemplate['loai_hoat_dong'],
                    'ten_hoat_dong' => $activityTemplate['ten_hoat_dong'],
                    'ngay_thuc_hien' => $ngayThucHien,
                    'trang_thai' => $trangThai,
                    'ghi_chu' => $activityTemplate['ghi_chu'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $count++;
            }
        }

        DB::table('dan_quan_hoat_dong')->insert($records);
        $this->command->info("✅ Đã tạo {$count} bản ghi hoạt động dân quân mẫu thành công.");
    }
}