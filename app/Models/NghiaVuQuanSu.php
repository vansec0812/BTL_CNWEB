<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NghiaVuQuanSu extends Model
{
    use AuditableTrait;

    protected $auditModule = 'nghia_vu_quan_su';

    protected $table = 'nghia_vu_quan_su';

    protected $fillable = [
        'nhan_khau_id',
        'nam_tuoi_tuyen_quan',
        'trang_thai_nvqs',
        'ly_do_tam_hoan',
        'ngay_tam_hoan_den',
        'ngay_nhap_ngu',
        'don_vi_quan_doi',
        'ngay_xuat_ngu',
        'quan_ham_khi_xuat_ngu',
        'nam_dang_ky_kham_nvqs',
        'ket_qua_kham_suc_khoe',
        'ghi_chu',
    ];

    protected $casts = [
        'nhan_khau_id' => 'integer',
        'nam_tuoi_tuyen_quan' => 'integer',
        'ngay_tam_hoan_den' => 'date',
        'ngay_nhap_ngu' => 'date',
        'ngay_xuat_ngu' => 'date',
        'nam_dang_ky_kham_nvqs' => 'integer',
    ];

    /**
     * Lấy thông tin nhân khẩu liên kết với hồ sơ NVQS này.
     */
    public function nhanKhau(): BelongsTo
    {
        return $this->belongsTo(NhanKhau::class, 'nhan_khau_id');
    }
}
