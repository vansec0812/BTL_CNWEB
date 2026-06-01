<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KetNoiViecLamSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        // lao_dong_id=17 (Mạc Văn Minh - thất nghiệp) → doanh_nghiep_id=2
        DB::table('ket_noi_viec_lam')->insert([
            ['lao_dong_id'=>17,'doanh_nghiep_id'=>2,'ngay_ket_noi'=>'2024-01-10','vi_tri_gioi_thieu'=>'Công nhân may','ket_qua'=>'dang_cho_phan_hoi','nguoi_phu_trach_id'=>3,'created_at'=>$now,'updated_at'=>$now],
        ]);
        $this->command->info('✅ Đã tạo kết nối việc làm mẫu.');
    }
}
