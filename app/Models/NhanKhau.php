<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class NhanKhau extends Model
{
    use SoftDeletes;

    protected $table = 'nhan_khau';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'ngay_sinh' => 'date',
            'ngay_dang_ky_khai_sinh' => 'date',
            'ngay_khai_tu' => 'date',
            'ngay_chuyen_di' => 'date',
            'la_chu_ho' => 'boolean',
            'co_tien_an' => 'boolean',
        ];
    }

    public function doiTuongChinhSach(): HasOne
    {
        return $this->hasOne(DoiTuongChinhSach::class, 'nhan_khau_id');
    }

    public function nghiaVuQuanSu(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(NghiaVuQuanSu::class, 'nhan_khau_id');
    }

    public function hoKhau(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(HoKhau::class, 'ho_khau_id');
    }
}

