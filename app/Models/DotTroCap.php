<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DotTroCap extends Model
{
    public const LOAI_TRO_CAP = [
        'tien_mat' => 'Tiền mặt',
        'hien_vat' => 'Hiện vật',
        'ket_hop' => 'Kết hợp',
    ];

    public const TRANG_THAI = [
        'sap_dien_ra' => 'Sắp diễn ra',
        'dang_thuc_hien' => 'Đang thực hiện',
        'hoan_thanh' => 'Hoàn thành',
        'huy_bo' => 'Hủy bỏ',
    ];

    protected $table = 'dot_tro_cap';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'ngay_bat_dau_cap_phat' => 'date',
            'ngay_ket_thuc_cap_phat' => 'date',
            'dieu_kien_doi_tuong' => 'array',
            'gia_tri_quy_doi' => 'integer',
            'tong_so_doi_tuong' => 'integer',
            'so_da_nhan' => 'integer',
        ];
    }

    public function nguoiTao(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nguoi_tao_id');
    }

    public function chiTietCapPhats(): HasMany
    {
        return $this->hasMany(ChiTietCapPhatTroCap::class, 'dot_tro_cap_id');
    }

    public function loaiLabel(): string
    {
        return self::LOAI_TRO_CAP[$this->loai_tro_cap] ?? 'Không xác định';
    }

    public function trangThaiLabel(): string
    {
        return self::TRANG_THAI[$this->trang_thai] ?? 'Không xác định';
    }

    public function trangThaiBadgeColor(): string
    {
        return match ($this->trang_thai) {
            'sap_dien_ra' => 'secondary',
            'dang_thuc_hien' => 'warning text-dark',
            'hoan_thanh' => 'success',
            'huy_bo' => 'danger',
            default => 'secondary',
        };
    }
}
