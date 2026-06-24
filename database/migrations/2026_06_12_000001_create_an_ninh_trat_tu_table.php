<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng: an_ninh_trat_tu
     * Mô tả: Theo dõi các đối tượng quản lý đặc biệt và vi phạm hành chính tại địa phương.
     */
    public function up(): void
    {
        Schema::create('an_ninh_trat_tu', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('nhan_khau_id')->nullable()->comment('FK → nhan_khau.id (nullable để hỗ trợ đối tượng vãng lai)');
            $table->foreign('nhan_khau_id')->references('id')->on('nhan_khau')->onDelete('set null');

            $table->string('ho_ten', 255)->comment('Họ tên đối tượng');
            $table->string('cccd', 20)->nullable()->comment('Căn cước công dân / CMND');
            $table->string('dia_chi', 255)->nullable()->comment('Địa chỉ cư trú');
            $table->enum('nhom_doi_tuong', ['vi_pham_hanh_chinh', 'quan_ly_dac_biet'])->comment('Nhóm đối tượng: vi_pham_hanh_chinh hoặc quan_ly_dac_biet');
            $table->string('loai_doi_tuong', 100)->comment('Loại đối tượng quản lý hoặc loại vi phạm');

            $table->string('co_quan_giai_quyet', 255)->comment('Cơ quan xử lý / Cơ quan ban hành quyết định');
            $table->date('ngay_ghi_nhan')->comment('Ngày xảy ra vi phạm hoặc ngày đưa vào diện quản lý');
            $table->text('noi_dung')->comment('Nội dung chi tiết vụ việc hoặc lý do đưa vào quản lý');

            $table->string('hinh_thuc_xu_ly', 255)->nullable()->comment('Hình thức xử phạt (phạt tiền, cảnh cáo,...) hoặc biện pháp quản lý');
            $table->decimal('so_tien_phat', 15, 2)->nullable()->comment('Số tiền phạt (nếu có)');

            $table->enum('trang_thai', [
                'dang_quan_ly',
                'chua_chap_hanh',
                'da_chap_hanh',
            ])->default('dang_quan_ly')->comment('Trạng thái của hồ sơ');

            $table->timestamps();

            $table->index('nhom_doi_tuong');
            $table->index('loai_doi_tuong');
            $table->index('trang_thai');
            $table->index('ngay_ghi_nhan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('an_ninh_trat_tu');
    }
};
