<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChiTietCapPhatTroCap extends Model
{
    protected $table = 'chi_tiet_cap_phat_tro_cap';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'da_nhan' => 'boolean',
            'thoi_gian_nhan' => 'datetime',
            'so_suat' => 'integer',
            'gia_tri_nhan' => 'integer',
        ];
    }

    public function dotTroCap(): BelongsTo
    {
        return $this->belongsTo(DotTroCap::class, 'dot_tro_cap_id');
    }

    public function hoKhau(): BelongsTo
    {
        return $this->belongsTo(HoKhau::class, 'ho_khau_id')->withTrashed();
    }

    public function nhanKhau(): BelongsTo
    {
        return $this->belongsTo(NhanKhau::class, 'nhan_khau_id')->withTrashed();
    }

    public function nguoiXacNhan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nguoi_xac_nhan_id');
    }

    public function getRecipentNameAttribute(): string
    {
        if ($this->ho_khau_id) {
            return $this->hoKhau?->chuHo?->ho_ten
                ? 'Hộ gia đình: '.$this->hoKhau->chuHo->ho_ten.' (Sổ: '.$this->hoKhau->so_so_ho_khau.')'
                : 'Hộ gia đình (Sổ: '.$this->hoKhau?->so_so_ho_khau.')';
        }

        return $this->nhanKhau?->ho_ten ?? 'Nhân khẩu không xác định';
    }

    public function getRecipientTypeAttribute(): string
    {
        return $this->ho_khau_id ? 'Hộ gia đình' : 'Cá nhân';
    }
}
