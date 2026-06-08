<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DanQuanTuVe extends Model
{
    use AuditableTrait;

    protected $auditModule = 'dan_quan_tu_ve';

    protected $table = 'dan_quan_tu_ve';

    public const CHUC_VU_LIST = [
        'Chiến sĩ',
        'Tổ trưởng',
        'Thôn đội trưởng',
        'Tiểu đội trưởng',
        'Trung đội trưởng',
        'Phó Đại đội trưởng',
        'Đại đội trưởng',
    ];

    protected $fillable = [
        'nhan_khau_id',
        'chuc_vu',
        'don_vi',
        'ngay_gia_nhap',
        'ngay_ket_thuc',
        'trang_thai',
        'ghi_chu',
    ];

    protected $casts = [
        'nhan_khau_id' => 'integer',
        'ngay_gia_nhap' => 'date',
        'ngay_ket_thuc' => 'date',
    ];

    public function nhanKhau(): BelongsTo
    {
        return $this->belongsTo(NhanKhau::class, 'nhan_khau_id');
    }

    public function hoatDong(): HasMany
    {
        return $this->hasMany(DanQuanHoatDong::class, 'dan_quan_tu_ve_id');
    }
}
