<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TamTruTamVang extends Model
{
    protected $table = 'tam_tru_tam_vang';

    public const LOAI = [
        'tam_tru' => 'Tạm trú',
        'tam_vang' => 'Tạm vắng',
    ];

    public const TRANG_THAI = [
        'dang_hieu_luc' => 'Đang hiệu lực',
        'da_het_han' => 'Đã hết hạn',
        'da_huy' => 'Đã hủy',
    ];

    protected $fillable = [
        'nhan_khau_id',
        'loai',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'dia_chi_cu_tru_thuc_te',
        'dia_chi_vang_mat',
        'ly_do',
        'trang_thai',
        'nguoi_xac_nhan_id',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_bat_dau' => 'date',
        'ngay_ket_thuc' => 'date',
    ];

    public function nhanKhau(): BelongsTo
    {
        return $this->belongsTo(NhanKhau::class, 'nhan_khau_id')->withTrashed();
    }

    public function nguoiXacNhan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nguoi_xac_nhan_id');
    }

    public function loaiLabel(): string
    {
        return self::LOAI[$this->loai] ?? 'Không xác định';
    }

    public function trangThaiLabel(): string
    {
        return self::TRANG_THAI[$this->trang_thai] ?? 'Không xác định';
    }
}
