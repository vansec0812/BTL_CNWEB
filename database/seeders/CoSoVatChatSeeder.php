<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoSoVatChatSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $records = [
            [
                'ten_cong_trinh' => 'Nhà văn hóa Thôn 1',
                'phan_loai' => 'van_hoa',
                'thon_xom' => 'Thôn 1',
                'ngay_dua_vao_su_dung' => '2015-05-19',
                'kinh_phi_xay_dung' => 500000000,
                'tinh_trang' => 'tot',
                'ghi_chu' => 'Mới sơn lại năm 2023',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten_cong_trinh' => 'Trạm y tế Xã',
                'phan_loai' => 'y_te',
                'thon_xom' => 'Thôn Trung Tâm',
                'ngay_dua_vao_su_dung' => '2010-02-27',
                'kinh_phi_xay_dung' => 1200000000,
                'tinh_trang' => 'dang_su_dung',
                'ghi_chu' => '',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten_cong_trinh' => 'Trường Mầm non Sao Mai',
                'phan_loai' => 'giao_duc',
                'thon_xom' => 'Thôn 2',
                'ngay_dua_vao_su_dung' => '2018-09-05',
                'kinh_phi_xay_dung' => 3500000000,
                'tinh_trang' => 'tot',
                'ghi_chu' => 'Đạt chuẩn quốc gia',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten_cong_trinh' => 'Đường bê tông liên thôn 1-2',
                'phan_loai' => 'giao_thong',
                'thon_xom' => 'Thôn 1, Thôn 2',
                'ngay_dua_vao_su_dung' => '2012-10-10',
                'kinh_phi_xay_dung' => 800000000,
                'tinh_trang' => 'xuong_cap',
                'ghi_chu' => 'Nhiều ổ gà, cần nâng cấp',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten_cong_trinh' => 'Đập thủy lợi Đồng Bến',
                'phan_loai' => 'thuy_loi',
                'thon_xom' => 'Thôn 3',
                'ngay_dua_vao_su_dung' => '2005-04-30',
                'kinh_phi_xay_dung' => 2000000000,
                'tinh_trang' => 'can_sua_chua',
                'ghi_chu' => 'Nguy cơ vỡ vào mùa mưa bão',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten_cong_trinh' => 'Trường Tiểu học Xã',
                'phan_loai' => 'giao_duc',
                'thon_xom' => 'Thôn Trung Tâm',
                'ngay_dua_vao_su_dung' => '2000-08-15',
                'kinh_phi_xay_dung' => 5000000000,
                'tinh_trang' => 'dang_su_dung',
                'ghi_chu' => 'Cần xây thêm dãy phòng học mới',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten_cong_trinh' => 'Nhà văn hóa Thôn 2',
                'phan_loai' => 'van_hoa',
                'thon_xom' => 'Thôn 2',
                'ngay_dua_vao_su_dung' => '1998-11-20',
                'kinh_phi_xay_dung' => 150000000,
                'tinh_trang' => 'ngung_su_dung',
                'ghi_chu' => 'Đã xây mới nhà văn hóa khác',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'ten_cong_trinh' => 'Đường liên xã đi qua Thôn 3',
                'phan_loai' => 'giao_thong',
                'thon_xom' => 'Thôn 3',
                'ngay_dua_vao_su_dung' => '2020-01-01',
                'kinh_phi_xay_dung' => 1500000000,
                'tinh_trang' => 'tot',
                'ghi_chu' => 'Đường rải nhựa',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('co_so_vat_chat')->insert($records);
        $this->command->info('✅ Đã tạo '.count($records).' bản ghi cơ sở vật chất mẫu.');
    }
}
