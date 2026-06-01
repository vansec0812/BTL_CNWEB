<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BaoTroXaHoiSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        DB::table('bao_tro_xa_hoi')->insert([
            // Hộ 2 - hộ nghèo
            ['ho_khau_id'=>2,'nhan_khau_id'=>null,'loai_bao_tro'=>'ho_ngheo','muc_do_khuyet_tat'=>'khong_ap_dung','so_quyet_dinh'=>'QĐ-UBND-017/2023','ngay_bat_dau_huong'=>'2023-01-01','muc_tro_cap_hang_thang'=>500000,'trang_thai'=>'dang_huong','created_at'=>$now,'updated_at'=>$now],
            // Bà Võ Thị Cúc - người già neo đơn
            ['ho_khau_id'=>null,'nhan_khau_id'=>28,'loai_bao_tro'=>'nguoi_gia_neo_don','muc_do_khuyet_tat'=>'khong_ap_dung','so_quyet_dinh'=>'QĐ-UBND-018/2023','ngay_bat_dau_huong'=>'2019-07-01','muc_tro_cap_hang_thang'=>1000000,'trang_thai'=>'dang_huong','created_at'=>$now,'updated_at'=>$now],
            // Hộ 3 - hộ cận nghèo
            ['ho_khau_id'=>3,'nhan_khau_id'=>null,'loai_bao_tro'=>'ho_can_ngheo','muc_do_khuyet_tat'=>'khong_ap_dung','so_quyet_dinh'=>'QĐ-UBND-019/2023','ngay_bat_dau_huong'=>'2023-01-01','muc_tro_cap_hang_thang'=>null,'trang_thai'=>'dang_huong','created_at'=>$now,'updated_at'=>$now],
        ]);
        $this->command->info('✅ Đã tạo đối tượng bảo trợ xã hội mẫu.');
    }
}
