<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng: y_te_nhan_khau
     * Người phụ trách: Người 4 - Quản lý An sinh xã hội, Y tế & Giáo dục
     * Mô tả: Theo dõi thông tin y tế (tiêm chủng, thẻ BHYT) của từng nhân khẩu.
     */
    public function up(): void
    {
        Schema::create('y_te_nhan_khau', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('nhan_khau_id')->unique()->comment('FK → nhan_khau.id (1-1)');
            $table->foreign('nhan_khau_id')->references('id')->on('nhan_khau')->onDelete('cascade');

            // Thẻ Bảo hiểm Y tế
            $table->string('so_the_bhyt', 50)->nullable()->comment('Số thẻ bảo hiểm y tế');
            $table->enum('loai_bhyt', [
                'bat_buoc',         // Người đi làm có đóng BHXH
                'tu_nguyen',        // Tự nguyện mua
                'ho_ngheo',         // Nhà nước hỗ trợ theo diện hộ nghèo
                'chinh_sach',       // Người có công, thương binh...
                'tre_em_duoi_6',    // Trẻ em dưới 6 tuổi
                'khong_co',         // Chưa có thẻ BHYT
            ])->default('khong_co')->comment('Loại bảo hiểm y tế');

            $table->date('ngay_cap_the_bhyt')->nullable()->comment('Ngày cấp thẻ BHYT');
            $table->date('ngay_het_han_the_bhyt')->nullable()->comment('Ngày hết hạn thẻ BHYT');
            $table->string('noi_dang_ky_kham_chua_benh', 255)->nullable()
                ->comment('Nơi đăng ký khám chữa bệnh ban đầu');

            // Tiêm chủng mở rộng (chủ yếu cho trẻ em)
            $table->boolean('hoan_thanh_tiem_chung_mo_rong')->default(false)
                ->comment('Đã hoàn thành chương trình tiêm chủng mở rộng chưa?');
            $table->json('lich_su_tiem_chung')->nullable()
                ->comment('Chi tiết các mũi tiêm đã tiêm (JSON array)');

            $table->text('ghi_chu_suc_khoe')->nullable()->comment('Ghi chú về tình trạng sức khỏe đặc biệt');
            $table->timestamps();

            $table->index('loai_bhyt');
            $table->index('ngay_het_han_the_bhyt');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('y_te_nhan_khau');
    }
};
