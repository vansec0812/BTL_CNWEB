<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng: dot_tro_cap
     * Người phụ trách: Người 4 - Quản lý An sinh xã hội, Y tế & Giáo dục
     * Mô tả: Quản lý các đợt cấp phát quà, tiền trợ cấp (Tết, 27/7, khó khăn đột xuất...).
     */
    public function up(): void
    {
        Schema::create('dot_tro_cap', function (Blueprint $table) {
            $table->id();

            $table->string('ten_dot', 255)->comment('Tên đợt trợ cấp (VD: Quà Tết Nguyên Đán 2025)');
            $table->text('mo_ta')->nullable()->comment('Mô tả chi tiết đợt trợ cấp');

            $table->enum('loai_tro_cap', [
                'tien_mat',    // Tiền mặt
                'hien_vat',    // Hiện vật (gạo, quần áo...)
                'ket_hop',     // Cả tiền và hiện vật
            ])->default('tien_mat')->comment('Hình thức trợ cấp');

            $table->decimal('gia_tri_quy_doi', 15, 0)->nullable()
                ->comment('Giá trị quy đổi ra tiền (VNĐ) / suất');

            $table->string('nguon_kinh_phi', 255)->nullable()
                ->comment('Nguồn kinh phí (Ngân sách xã, huyện, tỉnh, mạnh thường quân...)');

            $table->date('ngay_bat_dau_cap_phat')->comment('Ngày bắt đầu cấp phát');
            $table->date('ngay_ket_thuc_cap_phat')->nullable()->comment('Ngày kết thúc cấp phát');

            // Điều kiện đối tượng được hưởng (JSON để linh hoạt)
            $table->json('dieu_kien_doi_tuong')->nullable()
                ->comment('Điều kiện lọc đối tượng (VD: {"loai_bao_tro": ["ho_ngheo", "nguoi_khuyet_tat"]})');

            $table->integer('tong_so_doi_tuong')->default(0)->comment('Tổng số đối tượng được hưởng');
            $table->integer('so_da_nhan')->default(0)->comment('Số đã nhận');

            $table->enum('trang_thai', ['sap_dien_ra', 'dang_thuc_hien', 'hoan_thanh', 'huy_bo'])
                ->default('sap_dien_ra')
                ->comment('Trạng thái đợt trợ cấp');

            $table->unsignedBigInteger('nguoi_tao_id')->nullable()->comment('FK → users.id (cán bộ tạo đợt)');
            $table->foreign('nguoi_tao_id')->references('id')->on('users')->onDelete('set null');

            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->index('trang_thai');
            $table->index('ngay_bat_dau_cap_phat');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dot_tro_cap');
    }
};
