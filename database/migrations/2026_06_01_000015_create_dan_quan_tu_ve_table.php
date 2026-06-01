<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng: dan_quan_tu_ve
     * Người phụ trách: Người 5 - Quản lý Nghĩa vụ, Đất đai & Dashboard
     * Mô tả: Quản lý danh sách lực lượng dân quân tự vệ nòng cốt của xã
     *         và các đợt tập huấn, trực ban.
     */
    public function up(): void
    {
        Schema::create('dan_quan_tu_ve', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('nhan_khau_id')->unique()->comment('FK → nhan_khau.id');
            $table->foreign('nhan_khau_id')->references('id')->on('nhan_khau')->onDelete('cascade');

            $table->string('chuc_vu', 100)->nullable()->comment('Chức vụ trong đơn vị dân quân');
            $table->string('don_vi', 255)->nullable()->comment('Tổ/đội dân quân');

            $table->date('ngay_gia_nhap')->nullable()->comment('Ngày gia nhập lực lượng dân quân tự vệ');
            $table->date('ngay_ket_thuc')->nullable()->comment('Ngày kết thúc nhiệm kỳ (null = đang phục vụ)');

            $table->enum('trang_thai', ['dang_phuc_vu', 'da_hoan_thanh', 'da_roi'])
                  ->default('dang_phuc_vu')
                  ->comment('Trạng thái tham gia dân quân tự vệ');

            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->index('trang_thai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dan_quan_tu_ve');
    }
};
