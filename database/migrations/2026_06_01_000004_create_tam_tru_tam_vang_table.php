<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng: tam_tru_tam_vang
     * Người phụ trách: Người 2 - Quản lý Hộ tịch & Cư trú
     * Mô tả: Theo dõi tình trạng tạm trú / tạm vắng có thời hạn của nhân khẩu.
     *         Có scheduler tự động chuyển trạng thái khi hết hạn.
     */
    public function up(): void
    {
        Schema::create('tam_tru_tam_vang', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('nhan_khau_id')->comment('FK → nhan_khau.id');
            $table->foreign('nhan_khau_id')->references('id')->on('nhan_khau')->onDelete('cascade');

            $table->enum('loai', ['tam_tru', 'tam_vang'])->comment('Loại: tạm trú hoặc tạm vắng');

            $table->date('ngay_bat_dau')->comment('Ngày bắt đầu tạm trú/tạm vắng');
            $table->date('ngay_ket_thuc')->nullable()->comment('Ngày hết hạn (null = không xác định)');

            $table->string('dia_chi_cu_tru_thuc_te', 500)->nullable()
                  ->comment('Địa chỉ nơi cư trú thực tế (cho tạm trú người từ nơi khác đến)');
            $table->string('dia_chi_vang_mat', 500)->nullable()
                  ->comment('Địa chỉ nơi đến (cho tạm vắng - người tạm thời rời đi)');

            $table->string('ly_do', 500)->nullable()->comment('Lý do tạm trú/tạm vắng (đi học, đi làm, chữa bệnh...)');

            $table->enum('trang_thai', ['dang_hieu_luc', 'da_het_han', 'da_huy'])
                  ->default('dang_hieu_luc')
                  ->comment('Trạng thái của khai báo tạm trú/tạm vắng');

            // Cán bộ xác nhận
            $table->unsignedBigInteger('nguoi_xac_nhan_id')->nullable()->comment('FK → users.id');
            $table->foreign('nguoi_xac_nhan_id')->references('id')->on('users')->onDelete('set null');

            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->index(['nhan_khau_id', 'loai', 'trang_thai']);
            $table->index('ngay_ket_thuc'); // Để scheduler scan hàng ngày
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tam_tru_tam_vang');
    }
};
