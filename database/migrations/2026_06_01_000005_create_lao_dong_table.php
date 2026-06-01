<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng: lao_dong
     * Người phụ trách: Người 3 - Quản lý Kinh tế, Lao động & Doanh nghiệp
     * Mô tả: Lưu hồ sơ lao động của từng nhân khẩu trong độ tuổi lao động.
     */
    public function up(): void
    {
        Schema::create('lao_dong', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('nhan_khau_id')->unique()->comment('FK → nhan_khau.id (1-1 quan hệ)');
            $table->foreign('nhan_khau_id')->references('id')->on('nhan_khau')->onDelete('cascade');

            // Trạng thái lao động
            $table->enum('trang_thai_lao_dong', [
                'co_viec_lam',
                'that_nghiep',
                'hoc_sinh_sinh_vien',
                'mat_suc_lao_dong',
                'nghi_huu',
                'noi_tro',
                'chua_den_tuoi_lao_dong',
            ])->default('co_viec_lam')->comment('Trạng thái lao động hiện tại');

            // Nghề nghiệp hiện tại
            $table->string('nghe_nghiep', 255)->nullable()->comment('Tên nghề nghiệp cụ thể');
            $table->enum('loai_hinh_cong_viec', [
                'nha_nuoc',
                'tu_nhan',
                'tu_do_thoi_vu',
                'nuoc_ngoai',
                'khong_co_viec',
            ])->nullable()->comment('Loại hình công việc');

            $table->enum('nganh_nghe', [
                'nong_nghiep_lam_ngu_nghiep',
                'cong_nghiep_xay_dung',
                'dich_vu_thuong_mai',
                'giao_duc_y_te',
                'hanh_chinh_cong',
                'khac',
            ])->nullable()->comment('Lĩnh vực ngành nghề');

            // Xuất khẩu lao động / Làm việc xa
            $table->boolean('lam_viec_ngoai_tinh')->default(false)->comment('Đang làm việc ngoài tỉnh không?');
            $table->boolean('xuat_khau_lao_dong')->default(false)->comment('Đang xuất khẩu lao động không?');
            $table->string('quoc_gia_lam_viec', 100)->nullable()->comment('Nước đang làm việc (nếu XKLĐ)');
            $table->string('ten_cong_ty_nuoc_ngoai', 255)->nullable()->comment('Tên công ty/đối tác nước ngoài');
            $table->date('ngay_xuat_canh')->nullable()->comment('Ngày xuất cảnh đi làm');
            $table->date('ngay_het_hop_dong_nuoc_ngoai')->nullable()->comment('Ngày hết hạn hợp đồng nước ngoài');
            $table->string('tinh_thanh_lam_viec', 255)->nullable()->comment('Tỉnh/thành làm việc (nếu làm xa trong nước)');

            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->index('trang_thai_lao_dong');
            $table->index('nganh_nghe');
            $table->index('xuat_khau_lao_dong');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lao_dong');
    }
};
