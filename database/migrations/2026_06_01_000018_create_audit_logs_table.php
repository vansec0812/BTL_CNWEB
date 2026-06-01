<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bảng: audit_logs (Nhật ký hệ thống)
     * Người phụ trách: Người 1 - Nhóm trưởng / Kiến trúc hệ thống
     * Mô tả: Ghi lại toàn bộ hành động của cán bộ trong hệ thống (Ai, Lúc nào, Thao tác gì).
     *         Đây là cơ chế bảo mật thông tin công dân.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable()->comment('FK → users.id (cán bộ thực hiện)');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            $table->string('user_name', 255)->nullable()->comment('Tên cán bộ (snapshot lúc ghi log)');
            $table->string('ip_address', 50)->nullable()->comment('Địa chỉ IP của cán bộ');
            $table->string('user_agent')->nullable()->comment('Trình duyệt sử dụng');

            $table->string('action', 50)->comment('Hành động (create, update, delete, login, logout, export)');
            $table->string('module', 100)->comment('Module bị tác động (ho_khau, nhan_khau, lao_dong...)');
            $table->string('model_class', 255)->nullable()->comment('Tên class Model bị tác động');
            $table->unsignedBigInteger('model_id')->nullable()->comment('ID của bản ghi bị tác động');

            // Snapshot dữ liệu trước và sau khi thay đổi
            $table->json('gia_tri_cu')->nullable()->comment('Dữ liệu trước khi thay đổi (JSON)');
            $table->json('gia_tri_moi')->nullable()->comment('Dữ liệu sau khi thay đổi (JSON)');

            $table->text('mo_ta')->nullable()->comment('Mô tả thủ công hành động (VD: "Tách hộ 001 thành hộ 025")');

            $table->timestamps();

            $table->index('user_id');
            $table->index('action');
            $table->index('module');
            $table->index(['model_class', 'model_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
