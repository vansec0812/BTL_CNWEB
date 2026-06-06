<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoanhNghiep extends Model
{
    use SoftDeletes;

    protected $table = 'doanh_nghiep_ho_kinh_doanh';

    public const LOAI_HINH = [
        'doanh_nghiep_nha_nuoc' => 'Doanh nghiệp Nhà nước',
        'cong_ty_co_phan' => 'Công ty Cổ phần',
        'cong_ty_tnhh' => 'Công ty TNHH',
        'doanh_nghiep_tu_nhan' => 'Doanh nghiệp Tư nhân',
        'ho_kinh_doanh_ca_the' => 'Hộ kinh doanh cá thể',
        'hop_tac_xa' => 'Hợp tác xã',
        'khac' => 'Khác',
    ];

    public const TRANG_THAI = [
        'dang_hoat_dong' => 'Đang hoạt động',
        'tam_ngung' => 'Tạm ngưng',
        'da_giai_the' => 'Đã giải thể',
    ];

    protected $fillable = [
        'ten_co_so',
        'ma_so_thue',
        'ma_so_dang_ky_kinh_doanh',
        'loai_hinh',
        'nganh_nghe_chinh',
        'dia_chi',
        'thon_xom',
        'nguoi_dai_dien_nhan_khau_id',
        'ten_nguoi_dai_dien',
        'so_dien_thoai_lien_he',
        'ngay_thanh_lap',
        'so_lao_dong_hien_tai',
        'so_vi_tri_tuyen_dung',
        'trang_thai',
        'ghi_chu',
    ];

    protected function casts(): array
    {
        return [
            'ngay_thanh_lap' => 'date',
            'so_lao_dong_hien_tai' => 'integer',
            'so_vi_tri_tuyen_dung' => 'integer',
        ];
    }

    public function nguoiDaiDien(): BelongsTo
    {
        return $this->belongsTo(NhanKhau::class, 'nguoi_dai_dien_nhan_khau_id')->withTrashed();
    }

    public function ketNoiViecLam(): HasMany
    {
        return $this->hasMany(KetNoiViecLam::class, 'doanh_nghiep_id');
    }

    public function loaiHinhLabel(): string
    {
        return self::LOAI_HINH[$this->loai_hinh] ?? 'Không xác định';
    }

    public function trangThaiLabel(): string
    {
        return self::TRANG_THAI[$this->trang_thai] ?? 'Không xác định';
    }
}
