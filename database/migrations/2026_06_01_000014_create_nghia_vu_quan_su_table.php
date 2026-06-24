<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng: nghia_vu_quan_su
     * Người phụ trách: Người 5 - Quản lý Nghĩa vụ, Đất đai & Dashboard
     * Mô tả: Quản lý danh sách nam công dân trong độ tuổi NVQS và trạng thái thực hiện nghĩa vụ.
     *         Thuật toán tự động quét nam từ 18-25 tuổi (hoặc 27 nếu có bằng ĐH).
     */
    public function up(): void
    {
        Schema::create('nghia_vu_quan_su', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('nhan_khau_id')->unique()->comment('FK → nhan_khau.id (1-1 với nam giới)');
            $table->foreign('nhan_khau_id')->references('id')->on('nhan_khau')->onDelete('cascade');

            $table->integer('nam_tuoi_tuyen_quan')->nullable()->comment('Năm đến tuổi gọi nhập ngũ');

            $table->enum('trang_thai_nvqs', [
                'chua_den_tuoi',       // Chưa đến tuổi (< 18)
                'du_dieu_kien',        // Trong độ tuổi, chưa xử lý
                'tam_hoan',            // Tạm hoãn
                'mien_goi',            // Miễn gọi nhập ngũ
                'trung_tuyen',         // Đã trúng tuyển NVQS
                'da_nhap_ngu',         // Đang thực hiện NVQS
                'xuat_ngu',            // Đã xuất ngũ hoàn thành
                'da_qua_tuoi',         // Quá tuổi, không còn nghĩa vụ
            ])->default('chua_den_tuoi')->comment('Trạng thái nghĩa vụ quân sự');

            // Tạm hoãn
            $table->enum('ly_do_tam_hoan', [
                'di_hoc_dai_hoc',
                'benh_tat_suc_khoe',
                'con_mot_con',
                'nuoi_duong_than_nhan',
                'ly_do_khac',
                'khong_ap_dung',
            ])->default('khong_ap_dung')->comment('Lý do tạm hoãn NVQS (nếu có)');

            $table->date('ngay_tam_hoan_den')->nullable()->comment('Ngày tạm hoãn đến (ngày hết hạn tạm hoãn)');

            // Nhập ngũ - xuất ngũ
            $table->date('ngay_nhap_ngu')->nullable()->comment('Ngày nhập ngũ');
            $table->string('don_vi_quan_doi', 255)->nullable()->comment('Đơn vị quân đội phục vụ');
            $table->date('ngay_xuat_ngu')->nullable()->comment('Ngày xuất ngũ');
            $table->string('quan_ham_khi_xuat_ngu', 100)->nullable()->comment('Quân hàm khi xuất ngũ');

            // Năm đăng ký khám
            $table->integer('nam_dang_ky_kham_nvqs')->nullable()->comment('Năm đăng ký khám NVQS gần nhất');
            $table->enum('ket_qua_kham_suc_khoe', [
                'chua_kham', 'loai_1', 'loai_2', 'loai_3', 'loai_4', 'loai_5', 'khong_du_suc_khoe',
            ])->default('chua_kham')->comment('Kết quả khám sức khỏe NVQS');

            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->index('trang_thai_nvqs');
            $table->index('nam_tuoi_tuyen_quan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nghia_vu_quan_su');
    }
};
