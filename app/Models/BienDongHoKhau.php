<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\AuditableTrait;

class BienDongHoKhau extends Model
{
    use AuditableTrait;

    protected $auditModule = 'bien_dong_ho_khau';
    protected $table = 'bien_dong_ho_khau';

    public const LOAI_BIEN_DONG = [
        'tach_ho' => 'Tách hộ',
        'nhap_ho' => 'Nhập hộ',
        'chuyen_di' => 'Chuyển đi ngoài xã',
        'chuyen_den' => 'Chuyển đến từ nơi khác',
        'doi_chu_ho' => 'Thay đổi chủ hộ',
        'khai_tu' => 'Khai báo tử vong',
        'khai_sinh' => 'Thêm thành viên mới sinh',
    ];

    protected $fillable = [
        'loai_bien_dong',
        'ho_khau_nguon_id',
        'ho_khau_dich_id',
        'nhan_khau_id',
        'ngay_bien_dong',
        'ly_do',
        'dia_chi_chuyen_den',
        'so_quyet_dinh',
        'nguoi_thuc_hien_id',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_bien_dong' => 'date',
    ];

    public function hoKhauNguon(): BelongsTo
    {
        return $this->belongsTo(HoKhau::class, 'ho_khau_nguon_id')->withTrashed();
    }

    public function hoKhauDich(): BelongsTo
    {
        return $this->belongsTo(HoKhau::class, 'ho_khau_dich_id')->withTrashed();
    }

    public function nhanKhau(): BelongsTo
    {
        return $this->belongsTo(NhanKhau::class, 'nhan_khau_id')->withTrashed();
    }

    public function nguoiThucHien(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nguoi_thuc_hien_id');
    }

    public function loaiLabel(): string
    {
        return self::LOAI_BIEN_DONG[$this->loai_bien_dong] ?? 'Không xác định';
    }
}
