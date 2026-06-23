<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoSoVatChat extends Model
{
    protected $table = 'co_so_vat_chat';

    protected $fillable = [
        'ten_cong_trinh',
        'phan_loai',
        'thon_xom',
        'ngay_dua_vao_su_dung',
        'kinh_phi_xay_dung',
        'tinh_trang',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_dua_vao_su_dung' => 'date',
    ];

    public const PHAN_LOAI = [
        'giao_thong' => 'Giao thông',
        'giao_duc' => 'Giáo dục',
        'y_te' => 'Y tế',
        'van_hoa' => 'Văn hóa - Thể thao',
        'thuy_loi' => 'Thủy lợi',
        'khac' => 'Khác',
    ];

    public const TINH_TRANG = [
        'tot' => 'Tốt',
        'dang_su_dung' => 'Đang sử dụng',
        'xuong_cap' => 'Xuống cấp',
        'can_sua_chua' => 'Cần sửa chữa gấp',
        'ngung_su_dung' => 'Ngưng sử dụng',
    ];

    public function phanLoaiLabel(): string
    {
        return self::PHAN_LOAI[$this->phan_loai] ?? $this->phan_loai;
    }

    public function tinhTrangLabel(): string
    {
        return self::TINH_TRANG[$this->tinh_trang] ?? $this->tinh_trang;
    }
}
