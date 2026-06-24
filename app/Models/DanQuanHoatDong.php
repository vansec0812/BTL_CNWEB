<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DanQuanHoatDong extends Model
{
    use AuditableTrait;

    protected $auditModule = 'dan_quan_hoat_dong';

    protected $table = 'dan_quan_hoat_dong';

    protected $fillable = [
        'dan_quan_tu_ve_id',
        'loai_hoat_dong',
        'ten_hoat_dong',
        'ngay_thuc_hien',
        'trang_thai',
        'ghi_chu',
    ];

    protected $casts = [
        'dan_quan_tu_ve_id' => 'integer',
        'ngay_thuc_hien' => 'date',
    ];

    public function danQuanTuVe(): BelongsTo
    {
        return $this->belongsTo(DanQuanTuVe::class, 'dan_quan_tu_ve_id');
    }
}
