<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * NhanKhauSeeder - Người 2
 * Tạo nhân khẩu mẫu và cập nhật lại chu_ho trong ho_khau.
 */
class NhanKhauSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Danh sách nhân khẩu mẫu (ho_khau_id 1–10)
        $nhanKhauList = [
            // Hộ 1 - 4 thành viên
            ['ho_khau_id' => 1, 'ho_ten' => 'Nguyễn Văn An', 'cccd_cmnd' => '001085001234', 'ngay_sinh' => '1975-04-12', 'gioi_tinh' => 'nam', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'thpt', 'tinh_trang_hon_nhan' => 'da_ket_hon', 'quan_he_chu_ho' => 'Chủ hộ', 'la_chu_ho' => true, 'trang_thai' => 'hoat_dong'],
            ['ho_khau_id' => 1, 'ho_ten' => 'Trần Thị Bình', 'cccd_cmnd' => '001085001235', 'ngay_sinh' => '1978-08-20', 'gioi_tinh' => 'nu', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'thpt', 'tinh_trang_hon_nhan' => 'da_ket_hon', 'quan_he_chu_ho' => 'Vợ', 'la_chu_ho' => false, 'trang_thai' => 'hoat_dong'],
            ['ho_khau_id' => 1, 'ho_ten' => 'Nguyễn Văn Cường', 'cccd_cmnd' => '001105001100', 'ngay_sinh' => '2002-01-15', 'gioi_tinh' => 'nam', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'dai_hoc', 'tinh_trang_hon_nhan' => 'doc_than', 'quan_he_chu_ho' => 'Con', 'la_chu_ho' => false, 'trang_thai' => 'hoat_dong'],
            ['ho_khau_id' => 1, 'ho_ten' => 'Nguyễn Thị Dung', 'cccd_cmnd' => '001108001101', 'ngay_sinh' => '2005-06-30', 'gioi_tinh' => 'nu', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'thpt', 'tinh_trang_hon_nhan' => 'doc_than', 'quan_he_chu_ho' => 'Con', 'la_chu_ho' => false, 'trang_thai' => 'hoat_dong'],

            // Hộ 2 - 3 thành viên
            ['ho_khau_id' => 2, 'ho_ten' => 'Lê Văn Em', 'cccd_cmnd' => '001080002100', 'ngay_sinh' => '1968-11-05', 'gioi_tinh' => 'nam', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'tieu_hoc', 'tinh_trang_hon_nhan' => 'da_ket_hon', 'quan_he_chu_ho' => 'Chủ hộ', 'la_chu_ho' => true, 'trang_thai' => 'hoat_dong'],
            ['ho_khau_id' => 2, 'ho_ten' => 'Phạm Thị Giang', 'cccd_cmnd' => '001082002101', 'ngay_sinh' => '1972-03-18', 'gioi_tinh' => 'nu', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'thcs', 'tinh_trang_hon_nhan' => 'da_ket_hon', 'quan_he_chu_ho' => 'Vợ', 'la_chu_ho' => false, 'trang_thai' => 'hoat_dong'],
            ['ho_khau_id' => 2, 'ho_ten' => 'Lê Thị Hoa', 'cccd_cmnd' => '001106002102', 'ngay_sinh' => '2003-09-22', 'gioi_tinh' => 'nu', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'thpt', 'tinh_trang_hon_nhan' => 'doc_than', 'quan_he_chu_ho' => 'Con', 'la_chu_ho' => false, 'trang_thai' => 'hoat_dong'],

            // Hộ 3 - 5 thành viên (có người già)
            ['ho_khau_id' => 3, 'ho_ten' => 'Hoàng Văn Ích', 'cccd_cmnd' => '001060003001', 'ngay_sinh' => '1955-07-10', 'gioi_tinh' => 'nam', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'mu_chu', 'tinh_trang_hon_nhan' => 'goa', 'quan_he_chu_ho' => 'Chủ hộ', 'la_chu_ho' => true, 'trang_thai' => 'hoat_dong'],
            ['ho_khau_id' => 3, 'ho_ten' => 'Hoàng Thị Kim', 'cccd_cmnd' => '001078003002', 'ngay_sinh' => '1978-02-14', 'gioi_tinh' => 'nu', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'thcs', 'tinh_trang_hon_nhan' => 'da_ket_hon', 'quan_he_chu_ho' => 'Con', 'la_chu_ho' => false, 'trang_thai' => 'hoat_dong'],
            ['ho_khau_id' => 3, 'ho_ten' => 'Vũ Văn Long', 'cccd_cmnd' => '001075003003', 'ngay_sinh' => '1975-05-20', 'gioi_tinh' => 'nam', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'thcs', 'tinh_trang_hon_nhan' => 'da_ket_hon', 'quan_he_chu_ho' => 'Con rể', 'la_chu_ho' => false, 'trang_thai' => 'hoat_dong'],
            ['ho_khau_id' => 3, 'ho_ten' => 'Vũ Thị Mai', 'cccd_cmnd' => '001100003004', 'ngay_sinh' => '2000-12-01', 'gioi_tinh' => 'nu', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'thpt', 'tinh_trang_hon_nhan' => 'doc_than', 'quan_he_chu_ho' => 'Cháu', 'la_chu_ho' => false, 'trang_thai' => 'hoat_dong'],
            ['ho_khau_id' => 3, 'ho_ten' => 'Vũ Văn Nam', 'cccd_cmnd' => '001103003005', 'ngay_sinh' => '2003-08-15', 'gioi_tinh' => 'nam', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'thcs', 'tinh_trang_hon_nhan' => 'doc_than', 'quan_he_chu_ho' => 'Cháu', 'la_chu_ho' => false, 'trang_thai' => 'hoat_dong'],

            // Hộ 4 - 2 thành viên (vợ chồng trẻ)
            ['ho_khau_id' => 4, 'ho_ten' => 'Đinh Văn Oanh', 'cccd_cmnd' => '001090004001', 'ngay_sinh' => '1988-03-22', 'gioi_tinh' => 'nam', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'cao_dang', 'tinh_trang_hon_nhan' => 'da_ket_hon', 'quan_he_chu_ho' => 'Chủ hộ', 'la_chu_ho' => true, 'trang_thai' => 'hoat_dong'],
            ['ho_khau_id' => 4, 'ho_ten' => 'Bùi Thị Phương', 'cccd_cmnd' => '001092004002', 'ngay_sinh' => '1992-11-08', 'gioi_tinh' => 'nu', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'dai_hoc', 'tinh_trang_hon_nhan' => 'da_ket_hon', 'quan_he_chu_ho' => 'Vợ', 'la_chu_ho' => false, 'trang_thai' => 'hoat_dong'],

            // Hộ 5 - 2 người (tạm trú)
            ['ho_khau_id' => 5, 'ho_ten' => 'Trịnh Văn Quý', 'cccd_cmnd' => '036088005001', 'ngay_sinh' => '1988-06-14', 'gioi_tinh' => 'nam', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'thpt', 'tinh_trang_hon_nhan' => 'da_ket_hon', 'quan_he_chu_ho' => 'Chủ hộ', 'la_chu_ho' => true, 'trang_thai' => 'tam_tru'],
            ['ho_khau_id' => 5, 'ho_ten' => 'Ngô Thị Rạng', 'cccd_cmnd' => '052090005002', 'ngay_sinh' => '1990-09-25', 'gioi_tinh' => 'nu', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'thcs', 'tinh_trang_hon_nhan' => 'da_ket_hon', 'quan_he_chu_ho' => 'Vợ', 'la_chu_ho' => false, 'trang_thai' => 'tam_tru'],

            // Hộ 6 - 6 người (gia đình lớn)
            ['ho_khau_id' => 6, 'ho_ten' => 'Phan Văn Sơn', 'cccd_cmnd' => '001065006001', 'ngay_sinh' => '1965-01-30', 'gioi_tinh' => 'nam', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'thcs', 'tinh_trang_hon_nhan' => 'da_ket_hon', 'quan_he_chu_ho' => 'Chủ hộ', 'la_chu_ho' => true, 'trang_thai' => 'hoat_dong'],
            ['ho_khau_id' => 6, 'ho_ten' => 'Lý Thị Thu', 'cccd_cmnd' => '001068006002', 'ngay_sinh' => '1968-04-15', 'gioi_tinh' => 'nu', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'tieu_hoc', 'tinh_trang_hon_nhan' => 'da_ket_hon', 'quan_he_chu_ho' => 'Vợ', 'la_chu_ho' => false, 'trang_thai' => 'hoat_dong'],
            ['ho_khau_id' => 6, 'ho_ten' => 'Phan Văn Uy', 'cccd_cmnd' => '001095006003', 'ngay_sinh' => '1995-07-20', 'gioi_tinh' => 'nam', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'dai_hoc', 'tinh_trang_hon_nhan' => 'doc_than', 'quan_he_chu_ho' => 'Con', 'la_chu_ho' => false, 'trang_thai' => 'hoat_dong'],
            ['ho_khau_id' => 6, 'ho_ten' => 'Phan Thị Vân', 'cccd_cmnd' => '001097006004', 'ngay_sinh' => '1997-12-03', 'gioi_tinh' => 'nu', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'cao_dang', 'tinh_trang_hon_nhan' => 'doc_than', 'quan_he_chu_ho' => 'Con', 'la_chu_ho' => false, 'trang_thai' => 'hoat_dong'],
            ['ho_khau_id' => 6, 'ho_ten' => 'Phan Văn Xe', 'cccd_cmnd' => '001003006005', 'ngay_sinh' => '2003-03-10', 'gioi_tinh' => 'nam', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'thpt', 'tinh_trang_hon_nhan' => 'doc_than', 'quan_he_chu_ho' => 'Con', 'la_chu_ho' => false, 'trang_thai' => 'hoat_dong'],
            ['ho_khau_id' => 6, 'ho_ten' => 'Phan Thị Yến', 'cccd_cmnd' => '001007006006', 'ngay_sinh' => '2007-08-28', 'gioi_tinh' => 'nu', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'thcs', 'tinh_trang_hon_nhan' => 'doc_than', 'quan_he_chu_ho' => 'Con', 'la_chu_ho' => false, 'trang_thai' => 'hoat_dong'],

            // Hộ 7 - 3 người
            ['ho_khau_id' => 7, 'ho_ten' => 'Đỗ Văn Zương', 'cccd_cmnd' => '001072007001', 'ngay_sinh' => '1972-10-05', 'gioi_tinh' => 'nam', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'thcs', 'tinh_trang_hon_nhan' => 'da_ket_hon', 'quan_he_chu_ho' => 'Chủ hộ', 'la_chu_ho' => true, 'trang_thai' => 'hoat_dong'],
            ['ho_khau_id' => 7, 'ho_ten' => 'Cao Thị Ân', 'cccd_cmnd' => '001075007002', 'ngay_sinh' => '1975-02-18', 'gioi_tinh' => 'nu', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'thpt', 'tinh_trang_hon_nhan' => 'da_ket_hon', 'quan_he_chu_ho' => 'Vợ', 'la_chu_ho' => false, 'trang_thai' => 'hoat_dong'],
            ['ho_khau_id' => 7, 'ho_ten' => 'Đỗ Văn Bảo', 'cccd_cmnd' => '001004007003', 'ngay_sinh' => '2004-05-12', 'gioi_tinh' => 'nam', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'thpt', 'tinh_trang_hon_nhan' => 'doc_than', 'quan_he_chu_ho' => 'Con', 'la_chu_ho' => false, 'trang_thai' => 'hoat_dong'],

            // Hộ 8 - 1 người (người già neo đơn)
            ['ho_khau_id' => 8, 'ho_ten' => 'Võ Thị Cúc', 'cccd_cmnd' => '001045008001', 'ngay_sinh' => '1945-06-19', 'gioi_tinh' => 'nu', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'mu_chu', 'tinh_trang_hon_nhan' => 'goa', 'quan_he_chu_ho' => 'Chủ hộ', 'la_chu_ho' => true, 'trang_thai' => 'hoat_dong'],

            // Hộ 9 - 4 người
            ['ho_khau_id' => 9, 'ho_ten' => 'Tô Văn Đức', 'cccd_cmnd' => '001082009001', 'ngay_sinh' => '1982-09-14', 'gioi_tinh' => 'nam', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'thpt', 'tinh_trang_hon_nhan' => 'da_ket_hon', 'quan_he_chu_ho' => 'Chủ hộ', 'la_chu_ho' => true, 'trang_thai' => 'hoat_dong'],
            ['ho_khau_id' => 9, 'ho_ten' => 'Hà Thị Ếch', 'cccd_cmnd' => '001085009002', 'ngay_sinh' => '1985-04-07', 'gioi_tinh' => 'nu', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'cao_dang', 'tinh_trang_hon_nhan' => 'da_ket_hon', 'quan_he_chu_ho' => 'Vợ', 'la_chu_ho' => false, 'trang_thai' => 'hoat_dong'],
            ['ho_khau_id' => 9, 'ho_ten' => 'Tô Văn Giang', 'cccd_cmnd' => '001007009003', 'ngay_sinh' => '2007-11-28', 'gioi_tinh' => 'nam', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'thcs', 'tinh_trang_hon_nhan' => 'doc_than', 'quan_he_chu_ho' => 'Con', 'la_chu_ho' => false, 'trang_thai' => 'hoat_dong'],
            ['ho_khau_id' => 9, 'ho_ten' => 'Tô Thị Hiên', 'cccd_cmnd' => '001010009004', 'ngay_sinh' => '2010-03-16', 'gioi_tinh' => 'nu', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'tieu_hoc', 'tinh_trang_hon_nhan' => 'doc_than', 'quan_he_chu_ho' => 'Con', 'la_chu_ho' => false, 'trang_thai' => 'hoat_dong'],

            // Hộ 10 - 3 người
            ['ho_khau_id' => 10, 'ho_ten' => 'Mạc Văn Inh', 'cccd_cmnd' => '001070010001', 'ngay_sinh' => '1970-12-25', 'gioi_tinh' => 'nam', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'thpt', 'tinh_trang_hon_nhan' => 'da_ket_hon', 'quan_he_chu_ho' => 'Chủ hộ', 'la_chu_ho' => true, 'trang_thai' => 'hoat_dong'],
            ['ho_khau_id' => 10, 'ho_ten' => 'Kiều Thị Lan', 'cccd_cmnd' => '001073010002', 'ngay_sinh' => '1973-07-11', 'gioi_tinh' => 'nu', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'thpt', 'tinh_trang_hon_nhan' => 'da_ket_hon', 'quan_he_chu_ho' => 'Vợ', 'la_chu_ho' => false, 'trang_thai' => 'hoat_dong'],
            ['ho_khau_id' => 10, 'ho_ten' => 'Mạc Văn Minh', 'cccd_cmnd' => '001000010003', 'ngay_sinh' => '2000-10-04', 'gioi_tinh' => 'nam', 'dan_toc' => 'Kinh', 'trinh_do_hoc_van' => 'dai_hoc', 'tinh_trang_hon_nhan' => 'doc_than', 'quan_he_chu_ho' => 'Con', 'la_chu_ho' => false, 'trang_thai' => 'hoat_dong'],
        ];

        foreach ($nhanKhauList as $data) {
            $data['created_at'] = $now;
            $data['updated_at'] = $now;
            $id = DB::table('nhan_khau')->insertGetId($data);

            // Cập nhật chu_ho vào ho_khau ngay khi insert chủ hộ
            if ($data['la_chu_ho']) {
                DB::table('ho_khau')->where('id', $data['ho_khau_id'])
                    ->update(['chu_ho_nhan_khau_id' => $id]);
            }
        }

        $this->command->info('✅ Đã tạo '.count($nhanKhauList).' nhân khẩu mẫu.');
    }
}
