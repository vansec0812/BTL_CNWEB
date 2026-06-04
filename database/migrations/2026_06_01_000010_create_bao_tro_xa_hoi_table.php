<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng: bao_tro_xa_hoi
     * Người phụ trách: Người 4 - Quản lý An sinh xã hội, Y tế & Giáo dục
     * Mô tả: CRUD danh sách hộ nghèo, cận nghèo, người khuyết tật, người già neo đơn, trẻ mồ côi.
     */
    public function up(): void
    {
        Schema::create('bao_tro_xa_hoi', function (Blueprint $table) {
            $table->id();

            // Bảo trợ có thể gắn với hộ hoặc cá nhân
            $table->unsignedBigInteger('ho_khau_id')->nullable()->comment('FK → ho_khau.id (nếu là hộ nghèo/cận nghèo)');
            $table->foreign('ho_khau_id')->references('id')->on('ho_khau')->onDelete('cascade');

            $table->unsignedBigInteger('nhan_khau_id')->nullable()->comment('FK → nhan_khau.id (nếu là cá nhân)');
            $table->foreign('nhan_khau_id')->references('id')->on('nhan_khau')->onDelete('cascade');

            $table->enum('loai_bao_tro', [
                'ho_ngheo',            // Hộ nghèo (theo tiêu chí Chính phủ)
                'ho_can_ngheo',        // Hộ cận nghèo
                'nguoi_khuyet_tat',    // Người khuyết tật các dạng
                'nguoi_gia_neo_don',   // Người cao tuổi sống một mình không người chăm sóc
                'tre_mo_coi',          // Trẻ em mồ côi cha lẫn mẹ
                'tre_co_hoan_canh_kho_khan', // Trẻ em có hoàn cảnh đặc biệt khó khăn
                'nguoi_tam_than',      // Người mắc bệnh tâm thần không nơi nương tựa
                'khac',
            ])->comment('Loại đối tượng bảo trợ xã hội');

            // Thông tin đặc thù cho người khuyết tật
            $table->enum('muc_do_khuyet_tat', [
                'dac_biet_nang', 'nang', 'nhe', 'khong_ap_dung',
            ])->default('khong_ap_dung')->comment('Mức độ khuyết tật (nếu có)');

            $table->string('dang_khuyet_tat', 255)->nullable()
                ->comment('Dạng khuyết tật (vận động, nghe, nhìn, trí tuệ, tâm thần, ngôn ngữ)');

            // Thông tin hỗ trợ
            $table->string('so_quyet_dinh', 100)->nullable()->comment('Số quyết định phê duyệt bảo trợ');
            $table->date('ngay_bat_dau_huong')->nullable()->comment('Ngày bắt đầu hưởng trợ cấp bảo trợ');
            $table->date('ngay_ket_thuc_huong')->nullable()->comment('Ngày kết thúc (null = hưởng dài hạn)');
            $table->decimal('muc_tro_cap_hang_thang', 15, 0)->nullable()
                ->comment('Mức trợ cấp hàng tháng (VNĐ)');

            $table->enum('trang_thai', ['dang_huong', 'tam_ngung', 'het_dieu_kien'])
                ->default('dang_huong')
                ->comment('Trạng thái hưởng bảo trợ');

            $table->text('ghi_chu')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('loai_bao_tro');
            $table->index('trang_thai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bao_tro_xa_hoi');
    }
};
