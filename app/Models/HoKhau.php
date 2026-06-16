<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HoKhau extends Model
{
    use AuditableTrait, SoftDeletes;

    protected $auditModule = 'ho_khau';

    public const PHAN_LOAI = [
        'thuong_tru' => 'Thường trú',
        'tam_tru' => 'Tạm trú',
        'tam_vang' => 'Tạm vắng',
    ];

    public const TRANG_THAI = [
        'hoat_dong' => 'Hoạt động',
        'da_giai_the' => 'Đã giải thể',
        'chuyen_di' => 'Chuyển đi',
    ];

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
        return $this->belongsTo(NhanKhau::class, 'chu_ho_nhan_khau_id')->withTrashed();
    }

    public function thanhVien(): HasMany
    {
        return $this->hasMany(NhanKhau::class, 'ho_khau_id');
    }

    public function datDaiTaiSan(): HasMany
    {
        return $this->hasMany(DatDaiTaiSan::class, 'ho_khau_id');
    }

    public function thueVaPhi(): HasMany
    {
        return $this->hasMany(ThueVaPhiDiaPhuong::class, 'ho_khau_id');
    }

    public function phanLoaiLabel(): string
    {
        return self::PHAN_LOAI[$this->phan_loai] ?? 'Không xác định';
    }

    public function trangThaiLabel(): string
    {
        return self::TRANG_THAI[$this->trang_thai] ?? 'Không xác định';
    }
}
