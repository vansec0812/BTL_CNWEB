<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class YTeNhanKhau extends Model
{
    use AuditableTrait;

    protected $auditModule = 'y_te_nhan_khau';

    public const LOAI_BHYT = [
        'bat_buoc' => 'Bắt buộc (có BHXH)',
        'tu_nguyen' => 'Tự nguyện',
        'ho_ngheo' => 'Hộ nghèo (Nhà nước hỗ trợ)',
        'chinh_sach' => 'Chính sách (người có công)',
        'tre_em_duoi_6' => 'Trẻ em dưới 6 tuổi',
        'khong_co' => 'Chưa có thẻ BHYT',
    ];

    protected $table = 'y_te_nhan_khau';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'ngay_cap_the_bhyt' => 'date',
            'ngay_het_han_the_bhyt' => 'date',
            'hoan_thanh_tiem_chung_mo_rong' => 'boolean',
            'lich_su_tiem_chung' => 'array',
        ];
    }

    public function nhanKhau(): BelongsTo
    {
        return $this->belongsTo(NhanKhau::class, 'nhan_khau_id')->withTrashed();
    }

    public function loaiBhytLabel(): string
    {
        return self::LOAI_BHYT[$this->loai_bhyt] ?? 'Không xác định';
    }

    public function isTheBhytConHan(): bool
    {
        if ($this->loai_bhyt === 'khong_co') {
            return false;
        }

        if (! $this->ngay_het_han_the_bhyt) {
            return true; // Không hạn
        }

        return $this->ngay_het_han_the_bhyt->isFuture();
    }
}
