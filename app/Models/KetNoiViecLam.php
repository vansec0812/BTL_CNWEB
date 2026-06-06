<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KetNoiViecLam extends Model
{
    protected $table = 'ket_noi_viec_lam';

    public const KET_QUA = [
        'dang_cho_phan_hoi' => 'Đang chờ phản hồi',
        'duoc_nhan' => 'Được nhận',
        'khong_duoc_nhan' => 'Không được nhận',
        'lao_dong_tu_choi' => 'Lao động từ chối',
    ];

    protected $fillable = [
        'lao_dong_id',
        'doanh_nghiep_id',
        'ngay_ket_noi',
        'vi_tri_gioi_thieu',
        'ket_qua',
        'nguoi_phu_trach_id',
        'ghi_chu',
    ];

    protected function casts(): array
    {
        return [
            'ngay_ket_noi' => 'date',
        ];
    }

    public function laoDong(): BelongsTo
    {
        return $this->belongsTo(LaoDong::class, 'lao_dong_id');
    }

    public function doanhNghiep(): BelongsTo
    {
        return $this->belongsTo(DoanhNghiep::class, 'doanh_nghiep_id')->withTrashed();
    }

    public function nguoiPhuTrach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nguoi_phu_trach_id');
    }

    public function ketQuaLabel(): string
    {
        return self::KET_QUA[$this->ket_qua] ?? 'Không xác định';
    }
}
