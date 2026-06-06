<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaoDong extends Model
{
    protected $table = 'lao_dong';

    public const TRANG_THAI_LAO_DONG = [
        'co_viec_lam' => 'Có việc làm',
        'that_nghiep' => 'Thất nghiệp',
        'hoc_sinh_sinh_vien' => 'Học sinh/Sinh viên',
        'mat_suc_lao_dong' => 'Mất sức lao động',
        'nghi_huu' => 'Nghỉ hưu',
        'noi_tro' => 'Nội trợ',
        'chua_den_tuoi_lao_dong' => 'Chưa đến tuổi lao động',
    ];

    public const LOAI_HINH_CONG_VIEC = [
        'nha_nuoc' => 'Nhà nước',
        'tu_nhan' => 'Tư nhân',
        'tu_do_thoi_vu' => 'Tự do/Thời vụ',
        'nuoc_ngoai' => 'Nước ngoài',
        'khong_co_viec' => 'Không có việc',
    ];

    public const NGANH_NGHE = [
        'nong_nghiep_lam_ngu_nghiep' => 'Nông, lâm, ngư nghiệp',
        'cong_nghiep_xay_dung' => 'Công nghiệp, xây dựng',
        'dich_vu_thuong_mai' => 'Dịch vụ, thương mại',
        'giao_duc_y_te' => 'Giáo dục, y tế',
        'hanh_chinh_cong' => 'Hành chính công',
        'khac' => 'Khác',
    ];

    protected $fillable = [
        'nhan_khau_id',
        'trang_thai_lao_dong',
        'nghe_nghiep',
        'loai_hinh_cong_viec',
        'nganh_nghe',
        'lam_viec_ngoai_tinh',
        'xuat_khau_lao_dong',
        'quoc_gia_lam_viec',
        'ten_cong_ty_nuoc_ngoai',
        'ngay_xuat_canh',
        'ngay_het_hop_dong_nuoc_ngoai',
        'tinh_thanh_lam_viec',
        'ghi_chu',
    ];

    protected function casts(): array
    {
        return [
            'lam_viec_ngoai_tinh' => 'boolean',
            'xuat_khau_lao_dong' => 'boolean',
            'ngay_xuat_canh' => 'date',
            'ngay_het_hop_dong_nuoc_ngoai' => 'date',
        ];
    }

    public function nhanKhau(): BelongsTo
    {
        return $this->belongsTo(NhanKhau::class, 'nhan_khau_id')->withTrashed();
    }

    public function lichSuCongViec(): HasMany
    {
        return $this->hasMany(LichSuCongViec::class, 'lao_dong_id');
    }

    public function ketNoiViecLam(): HasMany
    {
        return $this->hasMany(KetNoiViecLam::class, 'lao_dong_id');
    }

    public function trangThaiLabel(): string
    {
        return self::TRANG_THAI_LAO_DONG[$this->trang_thai_lao_dong] ?? 'Không xác định';
    }

    public function loaiHinhLabel(): string
    {
        return self::LOAI_HINH_CONG_VIEC[$this->loai_hinh_cong_viec] ?? '—';
    }

    public function nganhNgheLabel(): string
    {
        return self::NGANH_NGHE[$this->nganh_nghe] ?? '—';
    }
}
