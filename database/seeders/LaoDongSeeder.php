<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaoDongSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        // Chỉ tạo hồ sơ lao động cho người trong độ tuổi (15-60)
        // nhan_khau_id tương ứng với dữ liệu đã seed
        $records = [
            ['nhan_khau_id' => 1,  'trang_thai_lao_dong' => 'nghi_huu',           'nghe_nghiep' => 'Nông dân',              'loai_hinh_cong_viec' => 'tu_do_thoi_vu',    'nganh_nghe' => 'nong_nghiep_lam_ngu_nghiep', 'lam_viec_ngoai_tinh' => false, 'xuat_khau_lao_dong' => false],
            ['nhan_khau_id' => 2,  'trang_thai_lao_dong' => 'co_viec_lam',         'nghe_nghiep' => 'Giáo viên tiểu học',   'loai_hinh_cong_viec' => 'nha_nuoc',         'nganh_nghe' => 'giao_duc_y_te',              'lam_viec_ngoai_tinh' => false, 'xuat_khau_lao_dong' => false],
            ['nhan_khau_id' => 3,  'trang_thai_lao_dong' => 'hoc_sinh_sinh_vien',  'nghe_nghiep' => null,                   'loai_hinh_cong_viec' => null,               'nganh_nghe' => null,                         'lam_viec_ngoai_tinh' => false, 'xuat_khau_lao_dong' => false],
            ['nhan_khau_id' => 5,  'trang_thai_lao_dong' => 'co_viec_lam',         'nghe_nghiep' => 'Thợ hồ',               'loai_hinh_cong_viec' => 'tu_do_thoi_vu',    'nganh_nghe' => 'cong_nghiep_xay_dung',       'lam_viec_ngoai_tinh' => false, 'xuat_khau_lao_dong' => false],
            ['nhan_khau_id' => 6,  'trang_thai_lao_dong' => 'noi_tro',             'nghe_nghiep' => 'Nội trợ',              'loai_hinh_cong_viec' => null,               'nganh_nghe' => null,                         'lam_viec_ngoai_tinh' => false, 'xuat_khau_lao_dong' => false],
            ['nhan_khau_id' => 8,  'trang_thai_lao_dong' => 'co_viec_lam',         'nghe_nghiep' => 'Kế toán',              'loai_hinh_cong_viec' => 'tu_nhan',          'nganh_nghe' => 'dich_vu_thuong_mai',         'lam_viec_ngoai_tinh' => false, 'xuat_khau_lao_dong' => false],
            ['nhan_khau_id' => 9,  'trang_thai_lao_dong' => 'co_viec_lam',         'nghe_nghiep' => 'Thợ may',              'loai_hinh_cong_viec' => 'tu_nhan',          'nganh_nghe' => 'cong_nghiep_xay_dung',       'lam_viec_ngoai_tinh' => false, 'xuat_khau_lao_dong' => false],
            ['nhan_khau_id' => 13, 'trang_thai_lao_dong' => 'co_viec_lam',         'nghe_nghiep' => 'Kỹ sư xây dựng',      'loai_hinh_cong_viec' => 'nha_nuoc',         'nganh_nghe' => 'cong_nghiep_xay_dung',       'lam_viec_ngoai_tinh' => false, 'xuat_khau_lao_dong' => false],
            ['nhan_khau_id' => 14, 'trang_thai_lao_dong' => 'co_viec_lam',         'nghe_nghiep' => 'Bác sĩ',               'loai_hinh_cong_viec' => 'nha_nuoc',         'nganh_nghe' => 'giao_duc_y_te',              'lam_viec_ngoai_tinh' => false, 'xuat_khau_lao_dong' => false],
            ['nhan_khau_id' => 15, 'trang_thai_lao_dong' => 'co_viec_lam',         'nghe_nghiep' => 'Công nhân',            'loai_hinh_cong_viec' => 'tu_nhan',          'nganh_nghe' => 'cong_nghiep_xay_dung',       'lam_viec_ngoai_tinh' => false, 'xuat_khau_lao_dong' => false],
            ['nhan_khau_id' => 17, 'trang_thai_lao_dong' => 'co_viec_lam',         'nghe_nghiep' => 'Buôn bán nhỏ',         'loai_hinh_cong_viec' => 'tu_do_thoi_vu',    'nganh_nghe' => 'dich_vu_thuong_mai',         'lam_viec_ngoai_tinh' => false, 'xuat_khau_lao_dong' => false],
            ['nhan_khau_id' => 18, 'trang_thai_lao_dong' => 'noi_tro',             'nghe_nghiep' => 'Nội trợ',              'loai_hinh_cong_viec' => null,               'nganh_nghe' => null,                         'lam_viec_ngoai_tinh' => false, 'xuat_khau_lao_dong' => false],
            ['nhan_khau_id' => 21, 'trang_thai_lao_dong' => 'co_viec_lam',         'nghe_nghiep' => 'Lập trình viên',       'loai_hinh_cong_viec' => 'tu_nhan',          'nganh_nghe' => 'dich_vu_thuong_mai',         'lam_viec_ngoai_tinh' => true, 'xuat_khau_lao_dong' => false, 'tinh_thanh_lam_viec' => 'TP. Hồ Chí Minh'],
            ['nhan_khau_id' => 25, 'trang_thai_lao_dong' => 'co_viec_lam',         'nghe_nghiep' => 'Công nhân điện tử',   'loai_hinh_cong_viec' => 'nuoc_ngoai',       'nganh_nghe' => 'cong_nghiep_xay_dung',       'lam_viec_ngoai_tinh' => false, 'xuat_khau_lao_dong' => true, 'quoc_gia_lam_viec' => 'Nhật Bản', 'ten_cong_ty_nuoc_ngoai' => 'Panasonic Vietnam', 'ngay_xuat_canh' => '2022-03-15', 'ngay_het_hop_dong_nuoc_ngoai' => '2025-03-15'],
            ['nhan_khau_id' => 29, 'trang_thai_lao_dong' => 'co_viec_lam',         'nghe_nghiep' => 'Nông dân',             'loai_hinh_cong_viec' => 'tu_do_thoi_vu',    'nganh_nghe' => 'nong_nghiep_lam_ngu_nghiep', 'lam_viec_ngoai_tinh' => false, 'xuat_khau_lao_dong' => false],
            ['nhan_khau_id' => 30, 'trang_thai_lao_dong' => 'co_viec_lam',         'nghe_nghiep' => 'Y tá',                 'loai_hinh_cong_viec' => 'nha_nuoc',         'nganh_nghe' => 'giao_duc_y_te',              'lam_viec_ngoai_tinh' => false, 'xuat_khau_lao_dong' => false],
            ['nhan_khau_id' => 33, 'trang_thai_lao_dong' => 'that_nghiep',          'nghe_nghiep' => null,                   'loai_hinh_cong_viec' => null,               'nganh_nghe' => null,                         'lam_viec_ngoai_tinh' => false, 'xuat_khau_lao_dong' => false],
        ];

        foreach ($records as $r) {
            $r += ['ghi_chu' => null, 'created_at' => $now, 'updated_at' => $now, 'lam_viec_ngoai_tinh' => false, 'xuat_khau_lao_dong' => false, 'quoc_gia_lam_viec' => null, 'ten_cong_ty_nuoc_ngoai' => null, 'ngay_xuat_canh' => null, 'ngay_het_hop_dong_nuoc_ngoai' => null, 'tinh_thanh_lam_viec' => null];
            DB::table('lao_dong')->insert($r);
        }
        $this->command->info('✅ Đã tạo '.count($records).' hồ sơ lao động mẫu.');
    }
}
