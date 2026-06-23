<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('co_so_vat_chat', function (Blueprint $table) {
            $table->id();
            $table->string('ten_cong_trinh', 255)->comment('Tên cơ sở vật chất');
            $table->string('phan_loai', 50)->comment('Phân loại công trình (giao_thong, y_te...)');
            $table->string('thon_xom', 100)->nullable()->comment('Thuộc thôn xóm nào');
            $table->date('ngay_dua_vao_su_dung')->nullable()->comment('Ngày khánh thành/đưa vào SD');
            $table->decimal('kinh_phi_xay_dung', 15, 0)->nullable()->comment('Kinh phí (VNĐ)');
            $table->string('tinh_trang', 50)->default('tot')->comment('Tình trạng hiện tại (tot, xuong_cap...)');
            $table->text('ghi_chu')->nullable()->comment('Lịch sử bảo trì / Ghi chú');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('co_so_vat_chat');
    }
};
