<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng: thue_va_phi_dia_phuong
     * Người phụ trách: Người 5 - Quản lý Nghĩa vụ, Đất đai & Dashboard
     * Mô tả: CRUD và theo dõi các khoản nộp ngân sách của hộ dân.
     *         (Thuế đất, phí vệ sinh môi trường, quỹ khuyến học...)
     */
    public function up(): void
    {
        Schema::create('thue_va_phi_dia_phuong', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ho_khau_id')->comment('FK → ho_khau.id');
            $table->foreign('ho_khau_id')->references('id')->on('ho_khau')->onDelete('restrict');

            $table->integer('nam')->comment('Năm áp dụng khoản thu');

            $table->enum('loai_khoan_thu', [
                'thue_dat_phi_nong_nghiep',    // Thuế đất phi nông nghiệp
                'phi_ve_sinh_moi_truong',       // Phí vệ sinh môi trường hàng tháng/năm
                'quy_khuyen_hoc',              // Quỹ khuyến học địa phương
                'phi_xay_dung_nong_thon_moi',  // Đóng góp xây dựng NTM
                'phi_an_ninh_trat_tu',         // Đóng góp an ninh trật tự thôn xóm
                'khac',
            ])->comment('Loại khoản thu');

            $table->decimal('so_tien_phai_nop', 15, 0)->comment('Số tiền phải nộp (VNĐ)');
            $table->decimal('so_tien_da_nop', 15, 0)->default(0)->comment('Số tiền đã nộp');

            $table->enum('trang_thai_thanh_toan', ['chua_nop', 'nop_mot_phan', 'da_nop_du'])
                  ->default('chua_nop')
                  ->comment('Trạng thái thanh toán');

            $table->date('han_nop')->nullable()->comment('Hạn nộp');
            $table->date('ngay_nop_thuc_te')->nullable()->comment('Ngày nộp thực tế');

            $table->string('bien_lai_so', 100)->nullable()->comment('Số biên lai thu tiền');

            $table->unsignedBigInteger('nguoi_thu_id')->nullable()->comment('FK → users.id (cán bộ thu tiền)');
            $table->foreign('nguoi_thu_id')->references('id')->on('users')->onDelete('set null');

            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->index(['ho_khau_id', 'nam']);
            $table->index('trang_thai_thanh_toan');
            $table->index('han_nop');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thue_va_phi_dia_phuong');
    }
};
