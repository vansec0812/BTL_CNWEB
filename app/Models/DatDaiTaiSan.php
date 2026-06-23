<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DatDaiTaiSan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dat_dai_tai_san';

    protected $fillable = [
        'chu_so_huu_nhan_khau_id',
        'so_to_ban_do',
        'so_thua_dat',
        'so_gcn_qsdd',
        'loai_dat',
        'dien_tich_m2',
        'vi_tri_mo_ta',
        'thon_xom',
        'ngay_cap_gcn',
        'ngay_het_han_gcn',
        'trang_thai',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_cap_gcn' => 'date',
        'ngay_het_han_gcn' => 'date',
        'dien_tich_m2' => 'decimal:2',
    ];

    public function chuSoHuu()
    {
        return $this->belongsTo(NhanKhau::class, 'chu_so_huu_nhan_khau_id');
    }

    public function loaiDatLabel()
    {
        return match ($this->loai_dat) {
            'dat_tho_cu' => 'Đất thổ cư',
            'dat_nong_nghiep' => 'Đất nông nghiệp',
            'dat_lam_nghiep' => 'Đất lâm nghiệp',
            'dat_nuoi_trong_thuy_san' => 'Đất nuôi trồng thủy sản',
            'dat_kinh_doanh' => 'Đất sản xuất kinh doanh',
            default => 'Khác',
        };
    }

    public function trangThaiLabel()
    {
        return match ($this->trang_thai) {
            'dang_su_dung' => 'Đang sử dụng',
            'cho_thue' => 'Cho thuê',
            'bi_tranh_chap' => 'Bị tranh chấp',
            'da_chuyen_nhuong' => 'Đã chuyển nhượng',
            'thu_hoi' => 'Thu hồi',
            default => 'Không xác định',
        };
    }
}
