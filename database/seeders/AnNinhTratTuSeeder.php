<?php

namespace Database\Seeders;

use App\Models\AnNinhTratTu;
use App\Models\NhanKhau;
use Illuminate\Database\Seeder;

class AnNinhTratTuSeeder extends Seeder
{
    public function run(): void
    {
        $nhanKhaus = NhanKhau::all();

        if ($nhanKhaus->count() < 10) {
            return;
        }

        // Tạo dữ liệu mẫu đối tượng quản lý đặc biệt
        $specialCases = [
            [
                'nhom_doi_tuong' => 'quan_ly_dac_biet',
                'loai_doi_tuong' => 'tien_an_tien_su',
                'co_quan_giai_quyet' => 'Công an huyện Quốc Oai',
                'ngay_ghi_nhan' => '2025-05-15',
                'noi_dung' => 'Đối tượng có tiền án về tội trộm cắp tài sản, mới mãn hạn tù trở về địa phương và đang thuộc diện theo dõi tái hòa nhập cộng đồng.',
                'hinh_thuc_xu_ly' => 'Giám sát hành vi tại nơi cư trú',
                'so_tien_phat' => null,
                'trang_thai' => 'dang_quan_ly',
            ],
            [
                'nhom_doi_tuong' => 'quan_ly_dac_biet',
                'loai_doi_tuong' => 'nguoi_nghien_ma_tuy',
                'co_quan_giai_quyet' => 'UBND xã Quốc Oai',
                'ngay_ghi_nhan' => '2026-01-20',
                'noi_dung' => 'Đối tượng xác định nghiện ma túy đá, đang trong diện cai nghiện bắt buộc/tự nguyện tại cộng đồng.',
                'hinh_thuc_xu_ly' => 'Cai nghiện tại gia đình và cộng đồng',
                'so_tien_phat' => null,
                'trang_thai' => 'dang_quan_ly',
            ],
            [
                'nhom_doi_tuong' => 'quan_ly_dac_biet',
                'loai_doi_tuong' => 'theo_doi_an_ninh',
                'co_quan_giai_quyet' => 'Công an xã Quốc Oai',
                'ngay_ghi_nhan' => '2025-11-10',
                'noi_dung' => 'Đối tượng thường xuyên tụ tập gây mất trật tự công cộng, có biểu hiện lôi kéo thanh thiếu niên tham gia tệ nạn.',
                'hinh_thuc_xu_ly' => 'Răn đe giáo dục định kỳ',
                'so_tien_phat' => null,
                'trang_thai' => 'dang_quan_ly',
            ],
            [
                'nhom_doi_tuong' => 'quan_ly_dac_biet',
                'loai_doi_tuong' => 'bao_luc_gia_dinh',
                'co_quan_giai_quyet' => 'UBND xã Quốc Oai',
                'ngay_ghi_nhan' => '2026-03-02',
                'noi_dung' => 'Đối tượng có hành vi bạo lực với vợ con khi say xỉn, đã bị lập biên bản nhắc nhở nhiều lần.',
                'hinh_thuc_xu_ly' => 'Phạt tiền và cam kết không tái phạm',
                'so_tien_phat' => 1500000.00,
                'trang_thai' => 'da_chap_hanh',
            ],
            [
                'nhom_doi_tuong' => 'quan_ly_dac_biet',
                'loai_doi_tuong' => 'tien_an_tien_su',
                'co_quan_giai_quyet' => 'Tòa án nhân dân huyện Quốc Oai',
                'ngay_ghi_nhan' => '2024-08-12',
                'noi_dung' => 'Án treo về tội đánh bạc, thời gian thử thách 24 tháng.',
                'hinh_thuc_xu_ly' => 'Cải tạo không giam giữ',
                'so_tien_phat' => null,
                'trang_thai' => 'da_chap_hanh',
            ],
        ];

        // Gán 5 đối tượng đặc biệt cho các nhân khẩu đầu tiên
        foreach ($specialCases as $index => $case) {
            $nk = $nhanKhaus[$index];
            AnNinhTratTu::create(array_merge($case, [
                'nhan_khau_id' => $nk->id,
                'ho_ten' => $nk->ho_ten,
                'cccd' => $nk->cccd_cmnd,
                'dia_chi' => $nk->hoKhau->dia_chi_thuong_tru ?? 'Xã Quốc Oai',
            ]));
        }
        // Tạo dữ liệu vi phạm hành chính
        $violations = [
            [
                'nhom_doi_tuong' => 'vi_pham_hanh_chinh',
                'loai_doi_tuong' => 'vi_pham_hanh_chinh',
                'co_quan_giai_quyet' => 'Công an xã Quốc Oai',
                'ngay_ghi_nhan' => '2026-05-10',
                'noi_dung' => 'Hành vi vi phạm: Không đội mũ bảo hiểm khi tham gia giao thông, chở quá số người quy định.',
                'hinh_thuc_xu_ly' => 'Phạt tiền',
                'so_tien_phat' => 500000.00,
                'trang_thai' => 'da_chap_hanh',
            ],
            [
                'nhom_doi_tuong' => 'vi_pham_hanh_chinh',
                'loai_doi_tuong' => 'vi_pham_hanh_chinh',
                'co_quan_giai_quyet' => 'UBND xã Quốc Oai',
                'ngay_ghi_nhan' => '2026-05-22',
                'noi_dung' => 'Hành vi vi phạm: Lấn chiếm lòng lề đường làm nơi kinh doanh buôn bán gây cản trở giao thông.',
                'hinh_thuc_xu_ly' => 'Phạt tiền và tịch thu phương tiện lấn chiếm',
                'so_tien_phat' => 2000000.00,
                'trang_thai' => 'chua_chap_hanh',
            ],
            [
                'nhom_doi_tuong' => 'vi_pham_hanh_chinh',
                'loai_doi_tuong' => 'vi_pham_hanh_chinh',
                'co_quan_giai_quyet' => 'UBND xã Quốc Oai',
                'ngay_ghi_nhan' => '2026-06-01',
                'noi_dung' => 'Hành vi vi phạm: Gây mất trật tự công cộng tại quán ăn đêm, cãi vã xô xát nhẹ.',
                'hinh_thuc_xu_ly' => 'Cảnh cáo và nhắc nhở tại chỗ',
                'so_tien_phat' => null,
                'trang_thai' => 'da_chap_hanh',
            ],
        ];

        // Gán vi phạm cho các nhân khẩu tiếp theo
        foreach ($violations as $index => $violation) {
            $nk = $nhanKhaus[$index + 5];
            AnNinhTratTu::create(array_merge($violation, [
                'nhan_khau_id' => $nk->id,
                'ho_ten' => $nk->ho_ten,
                'cccd' => $nk->cccd_cmnd,
                'dia_chi' => $nk->hoKhau->dia_chi_thuong_tru ?? 'Xã Quốc Oai',
            ]));
        }

        // Tạo dữ liệu vi phạm của đối tượng vãng lai (nhan_khau_id = null)
        $outsiders = [
            [
                'nhom_doi_tuong' => 'vi_pham_hanh_chinh',
                'loai_doi_tuong' => 'vi_pham_hanh_chinh',
                'ho_ten' => 'Trần Văn Hùng',
                'cccd' => '001090123456',
                'dia_chi' => 'Phường Hàng Bông, Quận Hoàn Kiếm, Hà Nội',
                'co_quan_giai_quyet' => 'Công an xã Quốc Oai',
                'ngay_ghi_nhan' => '2026-05-28',
                'noi_dung' => 'Hành vi vi phạm: Đổ chất thải xây dựng trái phép ra khu vực công cộng ven đê sông Đáy.',
                'hinh_thuc_xu_ly' => 'Phạt tiền và buộc khôi phục hiện trạng ban đầu',
                'so_tien_phat' => 5000000.00,
                'trang_thai' => 'chua_chap_hanh',
            ],
            [
                'nhom_doi_tuong' => 'vi_pham_hanh_chinh',
                'loai_doi_tuong' => 'vi_pham_hanh_chinh',
                'ho_ten' => 'Nguyễn Thị Hoa',
                'cccd' => '038195000987',
                'dia_chi' => 'Xã Tuyết Nghĩa, Huyện Quốc Oai, Hà Nội',
                'co_quan_giai_quyet' => 'UBND xã Quốc Oai',
                'ngay_ghi_nhan' => '2026-06-05',
                'noi_dung' => 'Hành vi vi phạm: Bán hàng rong không đúng nơi quy định tại khu vực cổng trường học.',
                'hinh_thuc_xu_ly' => 'Cảnh cáo',
                'so_tien_phat' => null,
                'trang_thai' => 'da_chap_hanh',
            ],
        ];

        foreach ($outsiders as $outsider) {
            AnNinhTratTu::create($outsider);
        }
    }
}
