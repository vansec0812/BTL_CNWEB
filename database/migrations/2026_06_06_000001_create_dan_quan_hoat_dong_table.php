<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dan_quan_hoat_dong', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dan_quan_tu_ve_id');
            $table->string('loai_hoat_dong', 50); // tap_huan, truc_ban
            $table->string('ten_hoat_dong', 255);
            $table->date('ngay_thuc_hien');
            $table->string('trang_thai', 50); // e.g., tham_gia, vang_co_phep, vang_khong_phep, da_truc, vang_mat
            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->foreign('dan_quan_tu_ve_id', 'fk_dqhd_dqtv')
                ->references('id')
                ->on('dan_quan_tu_ve')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dan_quan_hoat_dong');
    }
};
