<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng: nhan_khau
     * Người phụ trách: Người 2 - Quản lý Hộ tịch & Cư trú
     * Mô tả: Lưu thông tin chi tiết từng công dân (nhân khẩu) trong các hộ gia đình.
     *         Đây là bảng TRUNG TÂM của toàn bộ hệ thống.
     */
    public function up(): void
    {
        Schema::create('nhan_khau', function (Blueprint $table) {
            $table->id();

            // Liên kết hộ khẩu
            $table->unsignedBigInteger('ho_khau_id')->comment('FK → ho_khau.id');
            $table->foreign('ho_khau_id')->references('id')->on('ho_khau')->onDelete('restrict');

            // Thông tin định danh cá nhân
            $table->string('ho_ten', 255)->comment('Họ và tên đầy đủ');
            $table->string('cccd_cmnd', 20)->unique()->nullable()->comment('Số CCCD/CMND/Mã định danh quốc gia');
            $table->date('ngay_sinh')->comment('Ngày sinh');
            $table->enum('gioi_tinh', ['nam', 'nu', 'khac'])->default('nam')->comment('Giới tính');
            $table->string('dan_toc', 100)->default('Kinh')->comment('Dân tộc');
            $table->string('ton_giao', 100)->nullable()->comment('Tôn giáo');
            $table->string('que_quan', 500)->nullable()->comment('Quê quán (gốc)');
            $table->string('noi_sinh', 500)->nullable()->comment('Nơi sinh');

            // Trình độ & hôn nhân
            $table->enum('trinh_do_hoc_van', [
                'mu_chu', 'tieu_hoc', 'thcs', 'thpt',
                'trung_cap', 'cao_dang', 'dai_hoc', 'sau_dai_hoc',
            ])->nullable()->comment('Trình độ học vấn cao nhất');

            $table->enum('tinh_trang_hon_nhan', [
                'doc_than', 'da_ket_hon', 'ly_hon', 'goa',
            ])->default('doc_than')->comment('Tình trạng hôn nhân');

            // Mối quan hệ với chủ hộ
            $table->string('quan_he_chu_ho', 100)->nullable()
                ->comment('Mối quan hệ với chủ hộ (Vợ, Chồng, Con, Cháu, Bố, Mẹ, Anh/Chị/Em...)');

            $table->boolean('la_chu_ho')->default(false)->comment('Có phải chủ hộ không?');

            // Tiền án, tiền sự (thông tin nhạy cảm)
            $table->boolean('co_tien_an')->default(false)->comment('Có tiền án tiền sự không?');
            $table->text('ghi_chu_tien_an')->nullable()->comment('Chi tiết tiền án (nếu có, cần quyền đặc biệt mới xem)');

            // Trạng thái nhân khẩu
            $table->enum('trang_thai', [
                'hoat_dong',    // Đang sinh sống bình thường
                'tam_tru',      // Tạm trú có đăng ký
                'tam_vang',     // Tạm vắng (đi làm xa, đi học)
                'da_chuyen_di', // Đã chuyển khẩu ra ngoài xã
                'da_mat',       // Đã khai tử
            ])->default('hoat_dong')->comment('Trạng thái nhân khẩu hiện tại');

            $table->date('ngay_dang_ky_khai_sinh')->nullable()->comment('Ngày đăng ký khai sinh');
            $table->date('ngay_khai_tu')->nullable()->comment('Ngày khai tử (nếu đã mất)');
            $table->date('ngay_chuyen_di')->nullable()->comment('Ngày chuyển khẩu đi');

            $table->text('ghi_chu')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['ho_ten']);
            $table->index(['cccd_cmnd']);
            $table->index(['ngay_sinh']);
            $table->index(['gioi_tinh']);
            $table->index(['trang_thai']);
            $table->index(['ho_khau_id', 'la_chu_ho']);
        });

        // Sau khi tạo bảng nhan_khau, thêm khóa ngoại ngược vào ho_khau
        Schema::table('ho_khau', function (Blueprint $table) {
            $table->foreign('chu_ho_nhan_khau_id')->references('id')->on('nhan_khau')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('ho_khau', function (Blueprint $table) {
            $table->dropForeign(['chu_ho_nhan_khau_id']);
        });
        Schema::dropIfExists('nhan_khau');
    }
};
