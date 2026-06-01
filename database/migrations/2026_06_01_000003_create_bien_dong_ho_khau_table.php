<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng: bien_dong_ho_khau
     * Người phụ trách: Người 2 - Quản lý Hộ tịch & Cư trú
     * Mô tả: Ghi lại lịch sử các biến động hộ (tách hộ, nhập hộ, chuyển đi, chuyển đến).
     *         Đây là bảng nghiệp vụ chuyên sâu nhất của Người 2.
     */
    public function up(): void
    {
        Schema::create('bien_dong_ho_khau', function (Blueprint $table) {
            $table->id();

            $table->enum('loai_bien_dong', [
                'tach_ho',        // Chuyển một số thành viên ra sổ mới
                'nhap_ho',        // Thêm thành viên từ nơi khác vào
                'chuyen_di',      // Cả hộ hoặc thành viên chuyển đi ngoài xã
                'chuyen_den',     // Cả hộ hoặc thành viên chuyển đến từ nơi khác
                'doi_chu_ho',     // Thay đổi chủ hộ
                'khai_tu',        // Khai báo tử vong
                'khai_sinh',      // Thêm thành viên mới sinh
            ])->comment('Loại biến động hộ khẩu');

            // Hộ nguồn và hộ đích (cho tách/nhập hộ)
            $table->unsignedBigInteger('ho_khau_nguon_id')->comment('FK → ho_khau.id (hộ gốc)');
            $table->foreign('ho_khau_nguon_id')->references('id')->on('ho_khau')->onDelete('restrict');

            $table->unsignedBigInteger('ho_khau_dich_id')->nullable()->comment('FK → ho_khau.id (hộ đích/mới)');
            $table->foreign('ho_khau_dich_id')->references('id')->on('ho_khau')->onDelete('set null');

            // Nhân khẩu liên quan
            $table->unsignedBigInteger('nhan_khau_id')->comment('FK → nhan_khau.id (người bị biến động)');
            $table->foreign('nhan_khau_id')->references('id')->on('nhan_khau')->onDelete('restrict');

            $table->date('ngay_bien_dong')->comment('Ngày thực hiện biến động');
            $table->string('ly_do', 500)->nullable()->comment('Lý do biến động');
            $table->string('dia_chi_chuyen_den', 500)->nullable()->comment('Địa chỉ nơi chuyển đến (nếu chuyển đi)');
            $table->string('so_quyet_dinh', 100)->nullable()->comment('Số quyết định/văn bản phê duyệt');

            // Người thực hiện (cán bộ)
            $table->unsignedBigInteger('nguoi_thuc_hien_id')->nullable()->comment('FK → users.id (cán bộ xử lý)');
            $table->foreign('nguoi_thuc_hien_id')->references('id')->on('users')->onDelete('set null');

            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->index('loai_bien_dong');
            $table->index('ngay_bien_dong');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bien_dong_ho_khau');
    }
};
