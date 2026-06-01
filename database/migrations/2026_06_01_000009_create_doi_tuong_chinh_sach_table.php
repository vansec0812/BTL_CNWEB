<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng: doi_tuong_chinh_sach
     * Người phụ trách: Người 4 - Quản lý An sinh xã hội, Y tế & Giáo dục
     * Mô tả: CRUD danh sách thương binh, bệnh binh, thân nhân liệt sĩ, người có công.
     */
    public function up(): void
    {
        Schema::create('doi_tuong_chinh_sach', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('nhan_khau_id')->comment('FK → nhan_khau.id');
            $table->foreign('nhan_khau_id')->references('id')->on('nhan_khau')->onDelete('cascade');

            $table->enum('loai_chinh_sach', [
                'thuong_binh',         // Thương binh
                'benh_binh',           // Bệnh binh
                'than_nhan_liet_si',   // Thân nhân liệt sĩ
                'nguoi_co_cong',       // Người có công với cách mạng
                'gia_dinh_liet_si',    // Gia đình liệt sĩ
                'anh_hung_luc_luong_vu_trang', // Anh hùng LLVT
                'anh_hung_lao_dong',   // Anh hùng lao động
                'khac',
            ])->comment('Loại diện chính sách');

            $table->string('so_quyet_dinh_cong_nhan', 100)->nullable()->comment('Số quyết định công nhận');
            $table->date('ngay_cong_nhan')->nullable()->comment('Ngày cơ quan có thẩm quyền công nhận');
            $table->string('co_quan_cap', 255)->nullable()->comment('Cơ quan ban hành quyết định');

            $table->decimal('ty_le_thuong_tat', 5, 2)->nullable()
                  ->comment('Tỷ lệ thương tật (%) - dành cho thương binh, bệnh binh');

            $table->decimal('muc_tro_cap_hang_thang', 15, 0)->nullable()
                  ->comment('Mức trợ cấp hàng tháng (VNĐ)');

            $table->enum('trang_thai', ['dang_huong_che_do', 'ngung_huong', 'da_mat'])
                  ->default('dang_huong_che_do')
                  ->comment('Trạng thái hưởng chế độ');

            $table->text('ghi_chu')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('loai_chinh_sach');
            $table->index('trang_thai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doi_tuong_chinh_sach');
    }
};
