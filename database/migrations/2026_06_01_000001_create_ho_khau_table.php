<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng: ho_khau
     * Người phụ trách: Người 2 - Quản lý Hộ tịch & Cư trú
     * Mô tả: Lưu thông tin sổ hộ khẩu của từng hộ gia đình trên địa bàn xã.
     */
    public function up(): void
    {
        Schema::create('ho_khau', function (Blueprint $table) {
            $table->id();
            $table->string('so_so_ho_khau', 50)->unique()->comment('Số sổ hộ khẩu');
            $table->string('ma_ho', 30)->unique()->comment('Mã hộ duy nhất trong hệ thống');

            // Chủ hộ - sẽ được thiết lập sau khi tạo nhân khẩu (nullable để tạo trước)
            $table->unsignedBigInteger('chu_ho_nhan_khau_id')->nullable()->comment('FK → nhan_khau.id (chủ hộ)');

            $table->string('dia_chi_thuong_tru', 500)->comment('Địa chỉ thường trú đầy đủ');
            $table->string('thon_xom', 100)->nullable()->comment('Tên thôn/xóm/đội');

            $table->enum('phan_loai', ['thuong_tru', 'tam_tru', 'tam_vang'])
                  ->default('thuong_tru')
                  ->comment('Phân loại hộ: thường trú / tạm trú / tạm vắng');

            $table->integer('so_thanh_vien')->default(0)->comment('Số thành viên hiện tại trong hộ');

            $table->date('ngay_lap_so')->nullable()->comment('Ngày lập sổ hộ khẩu');
            $table->date('ngay_cap_nhat')->nullable()->comment('Ngày cập nhật gần nhất');

            $table->text('ghi_chu')->nullable()->comment('Ghi chú thêm');
            $table->enum('trang_thai', ['hoat_dong', 'da_giai_the', 'chuyen_di'])
                  ->default('hoat_dong')
                  ->comment('Trạng thái sổ hộ khẩu');

            $table->timestamps();
            $table->softDeletes();

            $table->index('thon_xom');
            $table->index('phan_loai');
            $table->index('trang_thai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ho_khau');
    }
};
