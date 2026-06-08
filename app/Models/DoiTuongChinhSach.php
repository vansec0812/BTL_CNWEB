<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoiTuongChinhSach extends Model
{
    use SoftDeletes, AuditableTrait;

    protected $auditModule = 'doi_tuong_chinh_sach';

    public const LOAI_CHINH_SACH = [
        'thuong_binh' => 'Thương binh',
        'benh_binh' => 'Bệnh binh',
        'than_nhan_liet_si' => 'Thân nhân liệt sĩ',
        'nguoi_co_cong' => 'Người có công với cách mạng',
        'gia_dinh_liet_si' => 'Gia đình liệt sĩ',
        'anh_hung_luc_luong_vu_trang' => 'Anh hùng lực lượng vũ trang',
        'anh_hung_lao_dong' => 'Anh hùng lao động',
        'khac' => 'Khác',
    ];

    public const TRANG_THAI = [
        'dang_huong_che_do' => 'Đang hưởng chế độ',
        'ngung_huong' => 'Ngừng hưởng',
        'da_mat' => 'Đã mất',
    ];

    protected $table = 'doi_tuong_chinh_sach';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'ngay_cong_nhan' => 'date',
            'ty_le_thuong_tat' => 'decimal:2',
            'muc_tro_cap_hang_thang' => 'integer',
        ];
    }

    public function nhanKhau(): BelongsTo
    {
        return $this->belongsTo(NhanKhau::class, 'nhan_khau_id')->withTrashed();
    }

    public function loaiLabel(): string
    {
        return self::LOAI_CHINH_SACH[$this->loai_chinh_sach] ?? 'Khác';
    }

    public function trangThaiLabel(): string
    {
        return self::TRANG_THAI[$this->trang_thai] ?? 'Không xác định';
    }
}
