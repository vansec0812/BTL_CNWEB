<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng: lich_su_cong_viec
     * Người phụ trách: Người 3 - Quản lý Kinh tế, Lao động & Doanh nghiệp
     * Mô tả: Lịch sử thay đổi công việc của người dân.
     */
    public function up(): void
    {
        Schema::create('lich_su_cong_viec', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('lao_dong_id')->comment('FK → lao_dong.id');
            $table->foreign('lao_dong_id')->references('id')->on('lao_dong')->onDelete('cascade');

            $table->string('ten_cong_viec_cu', 255)->nullable()->comment('Tên công việc trước đó');
            $table->string('ten_cong_viec_moi', 255)->nullable()->comment('Tên công việc mới');
            $table->string('ly_do_thay_doi', 500)->nullable()->comment('Lý do thay đổi công việc');
            $table->date('ngay_thay_doi')->comment('Ngày thay đổi');

            $table->unsignedBigInteger('nguoi_cap_nhat_id')->nullable()->comment('FK → users.id');
            $table->foreign('nguoi_cap_nhat_id')->references('id')->on('users')->onDelete('set null');

            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->index('lao_dong_id');
            $table->index('ngay_thay_doi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lich_su_cong_viec');
    }
};
