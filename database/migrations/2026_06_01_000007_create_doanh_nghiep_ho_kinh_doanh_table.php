<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng: doanh_nghiep_ho_kinh_doanh
     * Người phụ trách: Người 3 - Quản lý Kinh tế, Lao động & Doanh nghiệp
     * Mô tả: CRUD danh sách các cơ sở sản xuất, kinh doanh, công ty trên địa bàn xã.
     *         Bảng này cũng là nguồn để kết nối việc làm cho lao động thất nghiệp.
     */
    public function up(): void
    {
        Schema::create('doanh_nghiep_ho_kinh_doanh', function (Blueprint $table) {
            $table->id();

            $table->string('ten_co_so', 255)->comment('Tên công ty / cơ sở kinh doanh');
            $table->string('ma_so_thue', 50)->nullable()->unique()->comment('Mã số thuế');
            $table->string('ma_so_dang_ky_kinh_doanh', 100)->nullable()->comment('Số đăng ký kinh doanh');

            $table->enum('loai_hinh', [
                'doanh_nghiep_nha_nuoc',
                'cong_ty_co_phan',
                'cong_ty_tnhh',
                'doanh_nghiep_tu_nhan',
                'ho_kinh_doanh_ca_the',
                'hop_tac_xa',
                'khac',
            ])->default('ho_kinh_doanh_ca_the')->comment('Loại hình doanh nghiệp');

            $table->string('nganh_nghe_chinh', 255)->nullable()->comment('Ngành nghề kinh doanh chính');
            $table->string('dia_chi', 500)->nullable()->comment('Địa chỉ trụ sở');
            $table->string('thon_xom', 100)->nullable()->comment('Thôn/xóm/đội đặt trụ sở');

            // Người đại diện pháp luật (có thể là nhân khẩu trong hệ thống)
            $table->unsignedBigInteger('nguoi_dai_dien_nhan_khau_id')->nullable()
                ->comment('FK → nhan_khau.id (người đại diện nếu là dân địa phương)');
            $table->foreign('nguoi_dai_dien_nhan_khau_id')->references('id')->on('nhan_khau')->onDelete('set null');

            $table->string('ten_nguoi_dai_dien', 255)->nullable()->comment('Tên người đại diện pháp luật');
            $table->string('so_dien_thoai_lien_he', 20)->nullable()->comment('Điện thoại liên hệ');

            $table->date('ngay_thanh_lap')->nullable()->comment('Ngày thành lập / cấp phép');
            $table->integer('so_lao_dong_hien_tai')->default(0)->comment('Số lao động hiện đang làm việc');
            $table->integer('so_vi_tri_tuyen_dung')->default(0)->comment('Số vị trí đang tuyển dụng');

            $table->enum('trang_thai', ['dang_hoat_dong', 'tam_ngung', 'da_giai_the'])
                ->default('dang_hoat_dong')
                ->comment('Trạng thái hoạt động của cơ sở');

            $table->text('ghi_chu')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('loai_hinh');
            $table->index('trang_thai');
            $table->index('thon_xom');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doanh_nghiep_ho_kinh_doanh');
    }
};
