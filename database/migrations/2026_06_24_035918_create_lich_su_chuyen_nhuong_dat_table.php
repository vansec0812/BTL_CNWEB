<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lich_su_chuyen_nhuong_dat', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('dat_dai_tai_san_id')->comment('FK -> dat_dai_tai_san.id');
            $table->foreign('dat_dai_tai_san_id')->references('id')->on('dat_dai_tai_san')->onDelete('cascade');

            $table->unsignedBigInteger('nguoi_ban_id')->comment('FK -> nhan_khau.id (Chủ cũ)');
            $table->foreign('nguoi_ban_id')->references('id')->on('nhan_khau')->onDelete('restrict');

            $table->unsignedBigInteger('nguoi_mua_id')->comment('FK -> nhan_khau.id (Chủ mới)');
            $table->foreign('nguoi_mua_id')->references('id')->on('nhan_khau')->onDelete('restrict');

            $table->date('ngay_chuyen_nhuong')->comment('Ngày thực hiện sang tên');
            $table->text('ghi_chu')->nullable()->comment('Ghi chú giao dịch');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_su_chuyen_nhuong_dat');
    }
};
