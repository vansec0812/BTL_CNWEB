<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('so_cccd')->unique()->nullable()->after('email');
            $table->string('gioi_tinh')->default('nam')->after('so_cccd');
            $table->date('ngay_sinh')->nullable()->after('gioi_tinh');
            $table->string('so_dien_thoai')->nullable()->after('ngay_sinh');
            $table->string('chuc_vu')->nullable()->after('so_dien_thoai');
            $table->string('dia_chi')->nullable()->after('chuc_vu');
            $table->string('que_quan')->nullable()->after('dia_chi');
            $table->string('trang_thai')->default('active')->after('que_quan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'so_cccd',
                'gioi_tinh',
                'ngay_sinh',
                'so_dien_thoai',
                'chuc_vu',
                'dia_chi',
                'que_quan',
                'trang_thai',
            ]);
        });
    }
};
