<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LichSuCongViec extends Model
{
    protected $table = 'lich_su_cong_viec';

    protected $fillable = [
        'lao_dong_id',
        'ten_cong_viec_cu',
        'ten_cong_viec_moi',
        'ly_do_thay_doi',
        'ngay_thay_doi',
        'nguoi_cap_nhat_id',
        'ghi_chu',
    ];

    protected function casts(): array
    {
        return [
            'ngay_thay_doi' => 'date',
        ];
    }

    public function laoDong(): BelongsTo
    {
        return $this->belongsTo(LaoDong::class, 'lao_dong_id');
    }

    public function nguoiCapNhat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nguoi_cap_nhat_id');
    }
}
