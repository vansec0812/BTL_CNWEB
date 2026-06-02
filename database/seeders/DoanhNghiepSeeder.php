<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoanhNghiepSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $base = ['nguoi_dai_dien_nhan_khau_id' => null, 'ghi_chu' => null, 'created_at' => $now, 'updated_at' => $now, 'deleted_at' => null];

        DB::table('doanh_nghiep_ho_kinh_doanh')->insert(array_merge($base, [
            'ten_co_so' => 'HTX Nông nghiệp Quốc Oai', 'ma_so_thue' => '1234567890',
            'ma_so_dang_ky_kinh_doanh' => null,
            'loai_hinh' => 'hop_tac_xa', 'nganh_nghe_chinh' => 'Nông nghiệp',
            'dia_chi' => 'Thôn Đoàn Kết, Xã Quốc Oai', 'thon_xom' => 'Thôn Đoàn Kết',
            'ten_nguoi_dai_dien' => 'Nguyễn Văn An', 'so_dien_thoai_lien_he' => '0901234567',
            'ngay_thanh_lap' => '2010-03-15', 'so_lao_dong_hien_tai' => 12,
            'so_vi_tri_tuyen_dung' => 2, 'trang_thai' => 'dang_hoat_dong',
        ]));

        DB::table('doanh_nghiep_ho_kinh_doanh')->insert(array_merge($base, [
            'ten_co_so' => 'Công ty TNHH Bình Minh', 'ma_so_thue' => '1234567891',
            'ma_so_dang_ky_kinh_doanh' => null,
            'loai_hinh' => 'cong_ty_tnhh', 'nganh_nghe_chinh' => 'Sản xuất may mặc',
            'dia_chi' => 'Lô 5, KCN Quốc Oai, Xã Quốc Oai', 'thon_xom' => 'Thôn Bình An',
            'ten_nguoi_dai_dien' => 'Lê Văn Bình', 'so_dien_thoai_lien_he' => '0912345678',
            'ngay_thanh_lap' => '2015-08-20', 'so_lao_dong_hien_tai' => 45,
            'so_vi_tri_tuyen_dung' => 5, 'trang_thai' => 'dang_hoat_dong',
        ]));

        DB::table('doanh_nghiep_ho_kinh_doanh')->insert(array_merge($base, [
            'ten_co_so' => 'Cửa hàng Tạp hóa Phú Lợi', 'ma_so_thue' => null,
            'ma_so_dang_ky_kinh_doanh' => null,
            'loai_hinh' => 'ho_kinh_doanh_ca_the', 'nganh_nghe_chinh' => 'Bán lẻ hàng tiêu dùng',
            'dia_chi' => 'Số 56, Xóm 1, Thôn Phú Lợi', 'thon_xom' => 'Thôn Phú Lợi',
            'nguoi_dai_dien_nhan_khau_id' => 27,
            'ten_nguoi_dai_dien' => 'Đỗ Văn Zương', 'so_dien_thoai_lien_he' => '0976543210',
            'ngay_thanh_lap' => '2012-04-18', 'so_lao_dong_hien_tai' => 2,
            'so_vi_tri_tuyen_dung' => 0, 'trang_thai' => 'dang_hoat_dong',
        ]));

        DB::table('doanh_nghiep_ho_kinh_doanh')->insert(array_merge($base, [
            'ten_co_so' => 'Trang trại Chăn nuôi Hòa Bình', 'ma_so_thue' => '1234567892',
            'ma_so_dang_ky_kinh_doanh' => null,
            'loai_hinh' => 'doanh_nghiep_tu_nhan', 'nganh_nghe_chinh' => 'Chăn nuôi heo, gà',
            'dia_chi' => 'Số 7, Xóm 2, Thôn Hòa Bình', 'thon_xom' => 'Thôn Hòa Bình',
            'nguoi_dai_dien_nhan_khau_id' => 13,
            'ten_nguoi_dai_dien' => 'Đinh Văn Oanh', 'so_dien_thoai_lien_he' => '0988776655',
            'ngay_thanh_lap' => '2018-05-10', 'so_lao_dong_hien_tai' => 5,
            'so_vi_tri_tuyen_dung' => 1, 'trang_thai' => 'dang_hoat_dong',
        ]));

        $this->command->info('✅ Đã tạo 4 doanh nghiệp/hộ kinh doanh mẫu.');
    }
}
