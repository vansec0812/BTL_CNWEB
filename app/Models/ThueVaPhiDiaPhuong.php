<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThueVaPhiDiaPhuong extends Model
{
    use AuditableTrait;

    protected $table = 'thue_va_phi_dia_phuong';

    protected $fillable = [
        'ho_khau_id',
        'dat_dai_tai_san_id',
        'nam',
        'loai_khoan_thu',
        'so_tien_phai_nop',
        'so_tien_da_nop',
        'trang_thai_thanh_toan',
        'han_nop',
        'ngay_nop_thuc_te',
        'bien_lai_so',
        'nguoi_thu_id',
        'ghi_chu',
    ];

    protected $casts = [
        'han_nop' => 'date',
        'ngay_nop_thuc_te' => 'date',
    ];

    public const LOAI_KHOAN_THU = [
        'thue_dat_phi_nong_nghiep' => 'Thuế đất phi nông nghiệp',
        'phi_ve_sinh_moi_truong' => 'Phí vệ sinh môi trường',
        'quy_khuyen_hoc' => 'Quỹ khuyến học',
        'phi_xay_dung_nong_thon_moi' => 'Đóng góp xây dựng NTM',
        'phi_an_ninh_trat_tu' => 'Phí an ninh trật tự',
        'khac' => 'Khác',
    ];

    public const TRANG_THAI = [
        'chua_nop' => 'Chưa nộp',
        'nop_mot_phan' => 'Nộp một phần',
        'da_nop_du' => 'Đã nộp đủ',
    ];

    public function loaiKhoanThuLabel(): string
    {
        return self::LOAI_KHOAN_THU[$this->loai_khoan_thu] ?? $this->loai_khoan_thu;
    }

    public function trangThaiLabel(): string
    {
        return self::TRANG_THAI[$this->trang_thai_thanh_toan] ?? $this->trang_thai_thanh_toan;
    }

    public function hoKhau(): BelongsTo
    {
        return $this->belongsTo(HoKhau::class, 'ho_khau_id');
    }

    public function datDaiTaiSan(): BelongsTo
    {
        return $this->belongsTo(DatDaiTaiSan::class, 'dat_dai_tai_san_id');
    }

    public function nguoiThu(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nguoi_thu_id');
    }
}
