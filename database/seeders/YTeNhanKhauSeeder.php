<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YTeNhanKhauSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $records = [
            ['nhan_khau_id'=>1, 'so_the_bhyt'=>'DN4010100234','loai_bhyt'=>'bat_buoc','ngay_cap_the_bhyt'=>'2020-01-01','ngay_het_han_the_bhyt'=>'2025-12-31','noi_dang_ky_kham_chua_benh'=>'Trạm Y tế xã Quốc Oai','hoan_thanh_tiem_chung_mo_rong'=>true],
            ['nhan_khau_id'=>2, 'so_the_bhyt'=>'DN4010100235','loai_bhyt'=>'bat_buoc','ngay_cap_the_bhyt'=>'2020-01-01','ngay_het_han_the_bhyt'=>'2025-12-31','noi_dang_ky_kham_chua_benh'=>'Trạm Y tế xã Quốc Oai','hoan_thanh_tiem_chung_mo_rong'=>true],
            ['nhan_khau_id'=>5, 'so_the_bhyt'=>'DN4010200100','loai_bhyt'=>'tu_nguyen','ngay_cap_the_bhyt'=>'2023-01-01','ngay_het_han_the_bhyt'=>'2024-12-31','noi_dang_ky_kham_chua_benh'=>'Bệnh viện huyện','hoan_thanh_tiem_chung_mo_rong'=>true],
            ['nhan_khau_id'=>7, 'so_the_bhyt'=>'DN4010300101','loai_bhyt'=>'ho_ngheo','ngay_cap_the_bhyt'=>'2023-06-01','ngay_het_han_the_bhyt'=>'2025-05-31','noi_dang_ky_kham_chua_benh'=>'Trạm Y tế xã Quốc Oai','hoan_thanh_tiem_chung_mo_rong'=>true],
            ['nhan_khau_id'=>28,'so_the_bhyt'=>'DN4010800001','loai_bhyt'=>'chinh_sach','ngay_cap_the_bhyt'=>'2020-01-01','ngay_het_han_the_bhyt'=>'2025-12-31','noi_dang_ky_kham_chua_benh'=>'Trạm Y tế xã Quốc Oai','hoan_thanh_tiem_chung_mo_rong'=>true],
            ['nhan_khau_id'=>32,'so_the_bhyt'=>null,'loai_bhyt'=>'khong_co','ngay_cap_the_bhyt'=>null,'ngay_het_han_the_bhyt'=>null,'noi_dang_ky_kham_chua_benh'=>null,'hoan_thanh_tiem_chung_mo_rong'=>false],
        ];
        foreach ($records as $r) {
            $r += ['created_at'=>$now,'updated_at'=>$now,'lich_su_tiem_chung'=>null,'ghi_chu_suc_khoe'=>null];
            DB::table('y_te_nhan_khau')->insert($r);
        }
        $this->command->info('✅ Đã tạo dữ liệu y tế nhân khẩu mẫu.');
    }
}
