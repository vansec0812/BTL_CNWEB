<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatDaiTaiSanSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        // Đảm bảo mỗi record có đủ cùng set fields (kể cả ngay_het_han_gcn = null)
        $records = [
            ['ho_khau_id' => 1, 'so_to_ban_do' => '05', 'so_thua_dat' => '123', 'so_gcn_qsdd' => 'GCN-2010-001', 'loai_dat' => 'dat_tho_cu',      'dien_tich_m2' => 200.00, 'vi_tri_mo_ta' => 'Thửa đất ở số 15, Xóm 3',            'thon_xom' => 'Thôn Phủ Quốc', 'ngay_cap_gcn' => '2010-05-15', 'ngay_het_han_gcn' => null,          'trang_thai' => 'dang_su_dung'],
            ['ho_khau_id' => 1, 'so_to_ban_do' => '08', 'so_thua_dat' => '045', 'so_gcn_qsdd' => 'GCN-2010-002', 'loai_dat' => 'dat_nong_nghiep', 'dien_tich_m2' => 3500.00, 'vi_tri_mo_ta' => 'Ruộng lúa cánh đồng phía Đông', 'thon_xom' => 'Thôn Phủ Quốc', 'ngay_cap_gcn' => '2010-05-15', 'ngay_het_han_gcn' => '2043-12-31', 'trang_thai' => 'dang_su_dung'],
            ['ho_khau_id' => 2, 'so_to_ban_do' => '03', 'so_thua_dat' => '067', 'so_gcn_qsdd' => 'GCN-2018-015', 'loai_dat' => 'dat_tho_cu',      'dien_tich_m2' => 150.00, 'vi_tri_mo_ta' => 'Thửa đất ở số 8, Xóm 1',             'thon_xom' => 'Thôn Ngô Sài',  'ngay_cap_gcn' => '2018-08-01', 'ngay_het_han_gcn' => null,          'trang_thai' => 'dang_su_dung'],
            ['ho_khau_id' => 3, 'so_to_ban_do' => '11', 'so_thua_dat' => '200', 'so_gcn_qsdd' => 'GCN-2008-003', 'loai_dat' => 'dat_tho_cu',      'dien_tich_m2' => 300.00, 'vi_tri_mo_ta' => 'Thửa đất ở số 42, Xóm 5',            'thon_xom' => 'Thôn Hoa Vôi',  'ngay_cap_gcn' => '2008-03-20', 'ngay_het_han_gcn' => null,          'trang_thai' => 'dang_su_dung'],
            ['ho_khau_id' => 3, 'so_to_ban_do' => '12', 'so_thua_dat' => '215', 'so_gcn_qsdd' => 'GCN-2008-004', 'loai_dat' => 'dat_nong_nghiep', 'dien_tich_m2' => 5200.00, 'vi_tri_mo_ta' => 'Ruộng lúa và ao nuôi cá',         'thon_xom' => 'Thôn Hoa Vôi',  'ngay_cap_gcn' => '2008-03-20', 'ngay_het_han_gcn' => '2058-12-31', 'trang_thai' => 'dang_su_dung'],
            ['ho_khau_id' => 4, 'so_to_ban_do' => '07', 'so_thua_dat' => '088', 'so_gcn_qsdd' => 'GCN-2020-025', 'loai_dat' => 'dat_tho_cu',      'dien_tich_m2' => 180.00, 'vi_tri_mo_ta' => 'Thửa đất ở số 7, Xóm 2',             'thon_xom' => 'Thôn Du Nghệ', 'ngay_cap_gcn' => '2020-12-01', 'ngay_het_han_gcn' => null,          'trang_thai' => 'dang_su_dung'],
            ['ho_khau_id' => 6, 'so_to_ban_do' => '06', 'so_thua_dat' => '112', 'so_gcn_qsdd' => 'GCN-2005-007', 'loai_dat' => 'dat_tho_cu',      'dien_tich_m2' => 250.00, 'vi_tri_mo_ta' => 'Thửa đất ở số 11, Xóm 3',            'thon_xom' => 'Thôn Ngô Sài',  'ngay_cap_gcn' => '2005-09-10', 'ngay_het_han_gcn' => null,          'trang_thai' => 'dang_su_dung'],
            ['ho_khau_id' => 8, 'so_to_ban_do' => '09', 'so_thua_dat' => '033', 'so_gcn_qsdd' => 'GCN-2019-018', 'loai_dat' => 'dat_tho_cu',      'dien_tich_m2' => 120.00, 'vi_tri_mo_ta' => 'Thửa đất ở số 3, Xóm 6',             'thon_xom' => 'Thôn Du Nghệ', 'ngay_cap_gcn' => '2019-03-01', 'ngay_het_han_gcn' => null,          'trang_thai' => 'dang_su_dung'],
        ];

        foreach ($records as $r) {
            $r['ghi_chu'] = null;
            $r['deleted_at'] = null;
            $r['created_at'] = $now;
            $r['updated_at'] = $now;
            DB::table('dat_dai_tai_san')->insert($r);
        }

        $this->command->info('✅ Đã tạo '.count($records).' thửa đất mẫu.');
    }
}
