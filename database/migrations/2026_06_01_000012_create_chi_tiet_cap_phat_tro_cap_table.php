<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng: chi_tiet_cap_phat_tro_cap
     * Người phụ trách: Người 4 - Quản lý An sinh xã hội, Y tế & Giáo dục
     * Mô tả: Bảng trung gian ghi nhận từng đối tượng nhận trong mỗi đợt trợ cấp.
     *         Trạng thái "Đã nhận / Chưa nhận" được quản lý ở đây.
     */
    public function up(): void
    {
        Schema::create('chi_tiet_cap_phat_tro_cap', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('dot_tro_cap_id')->comment('FK → dot_tro_cap.id');
            $table->foreign('dot_tro_cap_id')->references('id')->on('dot_tro_cap')->onDelete('cascade');

            // Có thể là hộ hoặc cá nhân
            $table->unsignedBigInteger('ho_khau_id')->nullable()->comment('FK → ho_khau.id');
            $table->foreign('ho_khau_id')->references('id')->on('ho_khau')->onDelete('cascade');

            $table->unsignedBigInteger('nhan_khau_id')->nullable()->comment('FK → nhan_khau.id');
            $table->foreign('nhan_khau_id')->references('id')->on('nhan_khau')->onDelete('cascade');

            $table->integer('so_suat')->default(1)->comment('Số suất được nhận');
            $table->decimal('gia_tri_nhan', 15, 0)->nullable()->comment('Giá trị thực tế nhận (VNĐ)');

            $table->boolean('da_nhan')->default(false)->comment('Đã nhận chưa?');
            $table->timestamp('thoi_gian_nhan')->nullable()->comment('Thời gian nhận thực tế');

            $table->unsignedBigInteger('nguoi_xac_nhan_id')->nullable()
                  ->comment('FK → users.id (cán bộ xác nhận đã nhận)');
            $table->foreign('nguoi_xac_nhan_id')->references('id')->on('users')->onDelete('set null');

            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->index(['dot_tro_cap_id', 'da_nhan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_cap_phat_tro_cap');
    }
};
