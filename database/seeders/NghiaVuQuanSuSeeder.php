<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NghiaVuQuanSuSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        // Nam giới trong hệ thống: nhan_khau_id 1,3,5,9,10,11,13,15,17,19,23,25,27,29,31,33
        $records = [
            ['nhan_khau_id' => 1,  'trang_thai_nvqs' => 'da_qua_tuoi',  'ly_do_tam_hoan' => 'khong_ap_dung', 'ket_qua_kham_suc_khoe' => 'chua_kham'],
            ['nhan_khau_id' => 3,  'trang_thai_nvqs' => 'tam_hoan',     'ly_do_tam_hoan' => 'di_hoc_dai_hoc', 'ngay_tam_hoan_den' => '2026-09-01', 'ket_qua_kham_suc_khoe' => 'loai_2'],
            ['nhan_khau_id' => 5,  'trang_thai_nvqs' => 'da_qua_tuoi',  'ly_do_tam_hoan' => 'khong_ap_dung', 'ket_qua_kham_suc_khoe' => 'chua_kham'],
            ['nhan_khau_id' => 10, 'trang_thai_nvqs' => 'xuat_ngu',     'ly_do_tam_hoan' => 'khong_ap_dung', 'ngay_nhap_ngu' => '1990-02-15', 'don_vi_quan_doi' => 'Trung đoàn 5, Sư đoàn 8', 'ngay_xuat_ngu' => '1992-02-15', 'quan_ham_khi_xuat_ngu' => 'Hạ sĩ', 'ket_qua_kham_suc_khoe' => 'loai_1'],
            ['nhan_khau_id' => 12, 'trang_thai_nvqs' => 'xuat_ngu',     'ly_do_tam_hoan' => 'khong_ap_dung', 'ngay_nhap_ngu' => '1993-02-15', 'don_vi_quan_doi' => 'Tiểu đoàn 3', 'ngay_xuat_ngu' => '1995-02-15', 'quan_ham_khi_xuat_ngu' => 'Binh nhì', 'ket_qua_kham_suc_khoe' => 'loai_1'],
            ['nhan_khau_id' => 13, 'trang_thai_nvqs' => 'mien_goi',     'ly_do_tam_hoan' => 'khong_ap_dung', 'ket_qua_kham_suc_khoe' => 'loai_4'],
            ['nhan_khau_id' => 15, 'trang_thai_nvqs' => 'da_nhap_ngu',  'ly_do_tam_hoan' => 'khong_ap_dung', 'ngay_nhap_ngu' => '2023-02-01', 'don_vi_quan_doi' => 'Tiểu đoàn 1, Trung đoàn 12', 'ket_qua_kham_suc_khoe' => 'loai_1', 'nam_dang_ky_kham_nvqs' => 2022],
            ['nhan_khau_id' => 17, 'trang_thai_nvqs' => 'da_qua_tuoi',  'ly_do_tam_hoan' => 'khong_ap_dung', 'ket_qua_kham_suc_khoe' => 'chua_kham'],
            ['nhan_khau_id' => 19, 'trang_thai_nvqs' => 'du_dieu_kien', 'ly_do_tam_hoan' => 'khong_ap_dung', 'nam_tuoi_tuyen_quan' => 2026, 'ket_qua_kham_suc_khoe' => 'chua_kham'],
            ['nhan_khau_id' => 23, 'trang_thai_nvqs' => 'du_dieu_kien', 'ly_do_tam_hoan' => 'khong_ap_dung', 'nam_tuoi_tuyen_quan' => 2026, 'ket_qua_kham_suc_khoe' => 'chua_kham'],
            ['nhan_khau_id' => 25, 'trang_thai_nvqs' => 'da_qua_tuoi',  'ly_do_tam_hoan' => 'khong_ap_dung', 'ket_qua_kham_suc_khoe' => 'chua_kham'],
            ['nhan_khau_id' => 27, 'trang_thai_nvqs' => 'da_qua_tuoi',  'ly_do_tam_hoan' => 'khong_ap_dung', 'ket_qua_kham_suc_khoe' => 'chua_kham'],
            ['nhan_khau_id' => 29, 'trang_thai_nvqs' => 'da_qua_tuoi',  'ly_do_tam_hoan' => 'khong_ap_dung', 'ket_qua_kham_suc_khoe' => 'chua_kham'],
            ['nhan_khau_id' => 31, 'trang_thai_nvqs' => 'chua_den_tuoi', 'ly_do_tam_hoan' => 'khong_ap_dung', 'ket_qua_kham_suc_khoe' => 'chua_kham'],
            ['nhan_khau_id' => 33, 'trang_thai_nvqs' => 'du_dieu_kien', 'ly_do_tam_hoan' => 'khong_ap_dung', 'nam_tuoi_tuyen_quan' => 2025, 'ket_qua_kham_suc_khoe' => 'chua_kham'],
        ];

        foreach ($records as $r) {
            $r += ['nam_tuoi_tuyen_quan' => null, 'ngay_tam_hoan_den' => null, 'ngay_nhap_ngu' => null, 'don_vi_quan_doi' => null, 'ngay_xuat_ngu' => null, 'quan_ham_khi_xuat_ngu' => null, 'nam_dang_ky_kham_nvqs' => null, 'ghi_chu' => null, 'created_at' => $now, 'updated_at' => $now];
            DB::table('nghia_vu_quan_su')->insert($r);
        }
        $this->command->info('✅ Đã tạo '.count($records).' hồ sơ NVQS mẫu.');
    }
}
