<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnNinhTratTu extends Model
{
    use AuditableTrait;

    protected $table = 'an_ninh_trat_tu';

    protected $auditModule = 'an_ninh_trat_tu';

    protected $guarded = [];

    public const NHOM_DOI_TUONG = [
        'vi_pham_hanh_chinh' => 'Vi phạm hành chính',
        'quan_ly_dac_biet' => 'Quản lý đặc biệt',
    ];

    public const LOAI_DOI_TUONG = [
        'tien_an_tien_su' => 'Tiền án, tiền sự',
        'nguoi_nghien_ma_tuy' => 'Người nghiện ma túy',
        'theo_doi_an_ninh' => 'Theo dõi an ninh',
        'bao_luc_gia_dinh' => 'Bạo lực gia đình',
        'vi_pham_hanh_chinh' => 'Vi phạm hành chính',
        'khac' => 'Khác',
    ];

    public const TRANG_THAI = [
        'dang_quan_ly' => 'Đang quản lý',
        'chua_chap_hanh' => 'Chưa chấp hành',
        'da_chap_hanh' => 'Đã chấp hành',
    ];

    protected function casts(): array
    {
        return [
            'ngay_ghi_nhan' => 'date',
            'so_tien_phat' => 'decimal:2',
        ];
    }

    public function nhanKhau(): BelongsTo
    {
        return $this->belongsTo(NhanKhau::class, 'nhan_khau_id')->withTrashed();
    }

    public function nhomLabel(): string
    {
        return self::NHOM_DOI_TUONG[$this->nhom_doi_tuong] ?? 'Không xác định';
    }

    public function loaiLabel(): string
    {
        return self::LOAI_DOI_TUONG[$this->loai_doi_tuong] ?? $this->loai_doi_tuong;
    }

    public function trangThaiLabel(): string
    {
        return self::TRANG_THAI[$this->trang_thai] ?? 'Không xác định';
    }

    public function soTienPhatFormatted(): string
    {
        if ($this->so_tien_phat === null) {
            return '—';
        }
        return number_format($this->so_tien_phat, 0, ',', '.') . ' VNĐ';
    }
}
