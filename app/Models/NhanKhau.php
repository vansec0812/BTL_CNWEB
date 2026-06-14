<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class NhanKhau extends Model
{
    use AuditableTrait, SoftDeletes;

    protected $auditModule = 'nhan_khau';

    public const GIOI_TINH = [
        'nam' => 'Nam',
        'nu' => 'Nữ',
        'khac' => 'Khác',
    ];

    public const TRINH_DO_HOC_VAN = [
        'mu_chu' => 'Mù chữ',
        'tieu_hoc' => 'Tiểu học',
        'thcs' => 'Trung học cơ sở (THCS)',
        'thpt' => 'Trung học phổ thông (THPT)',
        'trung_cap' => 'Trung cấp',
        'cao_dang' => 'Cao đẳng',
        'dai_hoc' => 'Đại học',
        'sau_dai_hoc' => 'Sau đại học',
    ];

    public const TINH_TRANG_HON_NHAN = [
        'doc_than' => 'Độc thân',
        'da_ket_hon' => 'Đã kết hôn',
        'ly_hon' => 'Ly hôn',
        'goa' => 'Góa',
    ];

    public const TRANG_THAI = [
        'hoat_dong' => 'Thường trú',
        'tam_tru' => 'Tạm trú',
        'tam_vang' => 'Tạm vắng',
        'da_chuyen_di' => 'Đã chuyển đi',
        'da_mat' => 'Đã mất (Khai tử)',
    ];

    protected $table = 'nhan_khau';

    protected $fillable = [
        'ho_khau_id',
        'ho_ten',
        'cccd_cmnd',
        'ngay_sinh',
        'gioi_tinh',
        'dan_toc',
        'ton_giao',
        'que_quan',
        'noi_sinh',
        'trinh_do_hoc_van',
        'tinh_trang_hon_nhan',
        'quan_he_chu_ho',
        'la_chu_ho',
        'co_tien_an',
        'ghi_chu_tien_an',
        'trang_thai',
        'ngay_dang_ky_khai_sinh',
        'ngay_khai_tu',
        'ngay_chuyen_di',
        'ghi_chu',
    ];

    protected function casts(): array
    {
        return [
            'ngay_sinh' => 'date',
            'ngay_dang_ky_khai_sinh' => 'date',
            'ngay_khai_tu' => 'date',
            'ngay_chuyen_di' => 'date',
            'la_chu_ho' => 'boolean',
            'co_tien_an' => 'boolean',
        ];
    }

    public function hoKhau()
    {
        return $this->belongsTo(HoKhau::class, 'ho_khau_id')->withTrashed();
    }

    public function doiTuongChinhSach(): HasOne
    {
        return $this->hasOne(DoiTuongChinhSach::class, 'nhan_khau_id');
    }

    public function nghiaVuQuanSu(): HasOne
    {
        return $this->hasOne(NghiaVuQuanSu::class, 'nhan_khau_id');
    }

    public function danQuanTuVe(): HasOne
    {
        return $this->hasOne(DanQuanTuVe::class, 'nhan_khau_id');
    }

    public function yTeNhanKhau(): HasOne
    {
        return $this->hasOne(YTeNhanKhau::class, 'nhan_khau_id');
    }

    public function laoDong(): HasOne
    {
        return $this->hasOne(LaoDong::class, 'nhan_khau_id');
    }

    public function anNinhTratTu(): HasMany
    {
        return $this->hasMany(AnNinhTratTu::class, 'nhan_khau_id');
    }

    public function doanhNghiepDaiDien(): HasMany
    {
        return $this->hasMany(DoanhNghiep::class, 'nguoi_dai_dien_nhan_khau_id');
    }

    public function gioiTinhLabel(): string
    {
        return self::GIOI_TINH[$this->gioi_tinh] ?? 'Không xác định';
    }

    public function trinhDoLabel(): string
    {
        return self::TRINH_DO_HOC_VAN[$this->trinh_do_hoc_van] ?? 'Không xác định';
    }

    public function honNhanLabel(): string
    {
        return self::TINH_TRANG_HON_NHAN[$this->tinh_trang_hon_nhan] ?? 'Không xác định';
    }

    public function trangThaiLabel(): string
    {
        return self::TRANG_THAI[$this->trang_thai] ?? 'Không xác định';
    }
}
