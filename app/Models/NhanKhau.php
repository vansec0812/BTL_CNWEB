<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class NhanKhau extends Model
{
    use SoftDeletes;

    protected $table = 'nhan_khau';

    protected $fillable = [
        'ho_khau_id',
        'ho_ten',
        'cccd_cmnd',
        'ngay_sinh',
        'gioi_tinh',
        'dan_toc',
        'ton_giao',
        'que_quan',
        'noi_sinh',
        'trinh_do_hoc_van',
        'tinh_trang_hon_nhan',
        'quan_he_chu_ho',
        'la_chu_ho',
        'co_tien_an',
        'ghi_chu_tien_an',
        'trang_thai',
        'ngay_dang_ky_khai_sinh',
        'ngay_khai_tu',
        'ngay_chuyen_di',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_sinh' => 'date',
        'la_chu_ho' => 'boolean',
        'co_tien_an' => 'boolean',
        'ngay_dang_ky_khai_sinh' => 'date',
        'ngay_khai_tu' => 'date',
        'ngay_chuyen_di' => 'date',
    ];

    public function hoKhau(): BelongsTo
    {
        return $this->belongsTo(HoKhau::class, 'ho_khau_id');
    }
}
