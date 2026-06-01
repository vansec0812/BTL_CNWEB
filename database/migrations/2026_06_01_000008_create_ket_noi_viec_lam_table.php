<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng: ket_noi_viec_lam
     * Người phụ trách: Người 3 - Quản lý Kinh tế, Lao động & Doanh nghiệp
     * Mô tả: Bảng trung gian kết nối người lao động thất nghiệp với doanh nghiệp đang tuyển dụng.
     */
    public function up(): void
    {
        Schema::create('ket_noi_viec_lam', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('lao_dong_id')->comment('FK → lao_dong.id (người lao động)');
            $table->foreign('lao_dong_id')->references('id')->on('lao_dong')->onDelete('cascade');

            $table->unsignedBigInteger('doanh_nghiep_id')->comment('FK → doanh_nghiep_ho_kinh_doanh.id');
            $table->foreign('doanh_nghiep_id')->references('id')->on('doanh_nghiep_ho_kinh_doanh')->onDelete('cascade');

            $table->date('ngay_ket_noi')->comment('Ngày hệ thống tạo kết nối giới thiệu');
            $table->string('vi_tri_gioi_thieu', 255)->nullable()->comment('Vị trí việc làm được giới thiệu');

            $table->enum('ket_qua', [
                'dang_cho_phan_hoi',
                'duoc_nhan',
                'khong_duoc_nhan',
                'lao_dong_tu_choi',
            ])->default('dang_cho_phan_hoi')->comment('Kết quả của lần giới thiệu việc làm');

            $table->unsignedBigInteger('nguoi_phu_trach_id')->nullable()->comment('FK → users.id (cán bộ kết nối)');
            $table->foreign('nguoi_phu_trach_id')->references('id')->on('users')->onDelete('set null');

            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->unique(['lao_dong_id', 'doanh_nghiep_id', 'ngay_ket_noi'], 'unique_ket_noi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ket_noi_viec_lam');
    }
};
