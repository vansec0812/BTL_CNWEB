<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichSuChuyenNhuongDat extends Model
{
    protected $table = 'lich_su_chuyen_nhuong_dat';

    protected $fillable = [
        'dat_dai_tai_san_id',
        'nguoi_ban_id',
        'nguoi_mua_id',
        'ngay_chuyen_nhuong',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_chuyen_nhuong' => 'date',
    ];

    public function datDaiTaiSan()
    {
        return $this->belongsTo(DatDaiTaiSan::class, 'dat_dai_tai_san_id');
    }

    public function nguoiBan()
    {
        return $this->belongsTo(NhanKhau::class, 'nguoi_ban_id');
    }

    public function nguoiMua()
    {
        return $this->belongsTo(NhanKhau::class, 'nguoi_mua_id');
    }
}
