<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng: dat_dai_tai_san
     * Người phụ trách: Người 5 - Quản lý Nghĩa vụ, Đất đai & Dashboard
     * Mô tả: CRUD thông tin diện tích đất sở hữu của từng hộ gia đình.
     *         Mỗi hộ có thể có nhiều thửa đất.
     */
    public function up(): void
    {
        Schema::create('dat_dai_tai_san', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('chu_so_huu_nhan_khau_id')->comment('FK → nhan_khau.id (chủ sở hữu cá nhân)');
            $table->foreign('chu_so_huu_nhan_khau_id')->references('id')->on('nhan_khau')->onDelete('restrict');

            // Thông tin thửa đất (theo Giấy chứng nhận QSDĐ)
            $table->string('so_to_ban_do', 50)->nullable()->comment('Số tờ bản đồ địa chính');
            $table->string('so_thua_dat', 50)->nullable()->comment('Số thửa đất');
            $table->string('so_gcn_qsdd', 100)->nullable()->comment('Số Giấy chứng nhận QSDĐ');

            $table->enum('loai_dat', [
                'dat_tho_cu',              // Đất thổ cư (ở)
                'dat_nong_nghiep',         // Đất nông nghiệp (trồng lúa, hoa màu)
                'dat_lam_nghiep',          // Đất lâm nghiệp (rừng)
                'dat_nuoi_trong_thuy_san', // Đất nuôi trồng thủy sản
                'dat_kinh_doanh',          // Đất sản xuất kinh doanh phi nông nghiệp
                'khac',
            ])->comment('Loại đất theo mục đích sử dụng');

            $table->decimal('dien_tich_m2', 10, 2)->comment('Diện tích (m²)');
            $table->string('vi_tri_mo_ta', 500)->nullable()->comment('Mô tả vị trí thửa đất');
            $table->string('thon_xom', 100)->nullable()->comment('Thôn/xóm nơi có đất');

            $table->date('ngay_cap_gcn')->nullable()->comment('Ngày cấp Giấy chứng nhận');
            $table->date('ngay_het_han_gcn')->nullable()->comment('Ngày hết hạn (đất nông nghiệp thường 50 năm)');

            $table->enum('trang_thai', [
                'dang_su_dung',
                'cho_thue',
                'bi_tranh_chap',
                'da_chuyen_nhuong',
                'thu_hoi',
            ])->default('dang_su_dung')->comment('Trạng thái sử dụng đất');

            $table->text('ghi_chu')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('loai_dat');
            $table->index('trang_thai');
            $table->index('thon_xom');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dat_dai_tai_san');
    }
};
