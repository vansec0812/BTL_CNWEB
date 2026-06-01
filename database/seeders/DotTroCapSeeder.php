<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DotTroCapSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        // Tạo 2 đợt trợ cấp mẫu
        $dot1 = DB::table('dot_tro_cap')->insertGetId([
            'ten_dot'               => 'Quà Tết Nguyên Đán Ất Tỵ 2025',
            'mo_ta'                 => 'Tặng quà cho các hộ nghèo, cận nghèo và đối tượng chính sách dịp Tết Nguyên Đán 2025',
            'loai_tro_cap'          => 'ket_hop',
            'gia_tri_quy_doi'       => 500000,
            'nguon_kinh_phi'        => 'Ngân sách xã + Quỹ từ thiện địa phương',
            'ngay_bat_dau_cap_phat' => '2025-01-15',
            'ngay_ket_thuc_cap_phat'=> '2025-01-25',
            'dieu_kien_doi_tuong'   => json_encode(['loai_bao_tro' => ['ho_ngheo', 'ho_can_ngheo', 'nguoi_gia_neo_don']]),
            'tong_so_doi_tuong'     => 3,
            'so_da_nhan'            => 2,
            'trang_thai'            => 'hoan_thanh',
            'nguoi_tao_id'          => 1,
            'created_at'            => $now,
            'updated_at'            => $now,
        ]);

        $dot2 = DB::table('dot_tro_cap')->insertGetId([
            'ten_dot'               => 'Trợ cấp khó khăn đột xuất tháng 6/2024',
            'mo_ta'                 => 'Hỗ trợ các hộ bị ảnh hưởng bởi hạn hán',
            'loai_tro_cap'          => 'tien_mat',
            'gia_tri_quy_doi'       => 1000000,
            'nguon_kinh_phi'        => 'Ngân sách huyện',
            'ngay_bat_dau_cap_phat' => '2024-06-01',
            'ngay_ket_thuc_cap_phat'=> '2024-06-15',
            'dieu_kien_doi_tuong'   => json_encode(['loai_bao_tro' => ['ho_ngheo']]),
            'tong_so_doi_tuong'     => 1,
            'so_da_nhan'            => 1,
            'trang_thai'            => 'hoan_thanh',
            'nguoi_tao_id'          => 3,
            'created_at'            => $now,
            'updated_at'            => $now,
        ]);

        // Chi tiết cấp phát cho đợt 1
        DB::table('chi_tiet_cap_phat_tro_cap')->insert([
            ['dot_tro_cap_id'=>$dot1,'ho_khau_id'=>2,'nhan_khau_id'=>null,'so_suat'=>1,'gia_tri_nhan'=>500000,'da_nhan'=>true,'thoi_gian_nhan'=>'2025-01-17 09:00:00','nguoi_xac_nhan_id'=>3,'created_at'=>$now,'updated_at'=>$now],
            ['dot_tro_cap_id'=>$dot1,'ho_khau_id'=>null,'nhan_khau_id'=>28,'so_suat'=>1,'gia_tri_nhan'=>500000,'da_nhan'=>true,'thoi_gian_nhan'=>'2025-01-17 09:30:00','nguoi_xac_nhan_id'=>3,'created_at'=>$now,'updated_at'=>$now],
            ['dot_tro_cap_id'=>$dot1,'ho_khau_id'=>3,'nhan_khau_id'=>null,'so_suat'=>1,'gia_tri_nhan'=>500000,'da_nhan'=>false,'thoi_gian_nhan'=>null,'nguoi_xac_nhan_id'=>null,'created_at'=>$now,'updated_at'=>$now],
            ['dot_tro_cap_id'=>$dot2,'ho_khau_id'=>2,'nhan_khau_id'=>null,'so_suat'=>1,'gia_tri_nhan'=>1000000,'da_nhan'=>true,'thoi_gian_nhan'=>'2024-06-05 10:00:00','nguoi_xac_nhan_id'=>3,'created_at'=>$now,'updated_at'=>$now],
        ]);

        $this->command->info('✅ Đã tạo 2 đợt trợ cấp và 4 chi tiết cấp phát mẫu.');
    }
}
