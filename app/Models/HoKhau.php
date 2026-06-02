<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HoKhau extends Model
{
    use SoftDeletes;

    protected $table = 'ho_khau';

    protected $fillable = [
        'so_so_ho_khau',
        'ma_ho',
        'chu_ho_nhan_khau_id',
        'dia_chi_thuong_tru',
        'thon_xom',
        'phan_loai',
        'so_thanh_vien',
        'ngay_lap_so',
        'ngay_cap_nhat',
        'ghi_chu',
        'trang_thai',
    ];

    protected $casts = [
        'ngay_lap_so' => 'date',
        'ngay_cap_nhat' => 'date',
        'so_thanh_vien' => 'integer',
    ];

    public function chuHo(): BelongsTo
    {
        return $this->belongsTo(NhanKhau::class, 'chu_ho_nhan_khau_id');
    }

    public function thanhVien(): HasMany
    {
        return $this->hasMany(NhanKhau::class, 'ho_khau_id');
    }
}
