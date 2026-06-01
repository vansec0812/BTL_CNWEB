<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoiTuongChinhSachSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        DB::table('doi_tuong_chinh_sach')->insert([
            ['nhan_khau_id'=>9,'loai_chinh_sach'=>'thuong_binh','so_quyet_dinh_cong_nhan'=>'QĐ-LDTBXH-001/2005','ngay_cong_nhan'=>'2005-07-27','co_quan_cap'=>'Sở LĐTBXH tỉnh','ty_le_thuong_tat'=>31.00,'muc_tro_cap_hang_thang'=>1860000,'trang_thai'=>'dang_huong_che_do','created_at'=>$now,'updated_at'=>$now],
            ['nhan_khau_id'=>8,'loai_chinh_sach'=>'nguoi_co_cong','so_quyet_dinh_cong_nhan'=>'QĐ-BQP-002/2000','ngay_cong_nhan'=>'2000-12-22','co_quan_cap'=>'Bộ Quốc phòng','ty_le_thuong_tat'=>null,'muc_tro_cap_hang_thang'=>1550000,'trang_thai'=>'dang_huong_che_do','created_at'=>$now,'updated_at'=>$now],
        ]);
        $this->command->info('✅ Đã tạo đối tượng chính sách mẫu.');
    }
}
