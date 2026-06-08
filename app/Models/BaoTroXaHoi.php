<?php

namespace App\Models;

use App\Traits\AuditableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaoTroXaHoi extends Model
{
    use SoftDeletes, AuditableTrait;

    protected $auditModule = 'bao_tro_xa_hoi';

    public const LOAI_BAO_TRO = [
        'ho_ngheo' => 'Hộ nghèo',
        'ho_can_ngheo' => 'Hộ cận nghèo',
        'nguoi_khuyet_tat' => 'Người khuyết tật',
        'nguoi_gia_neo_don' => 'Người già neo đơn',
        'tre_mo_coi' => 'Trẻ em mồ côi',
        'tre_co_hoan_canh_kho_khan' => 'Trẻ em có hoàn cảnh khó khăn',
        'nguoi_tam_than' => 'Người tâm thần',
        'khac' => 'Khác',
    ];

    public const MUC_DO_KHUYET_TAT = [
        'dac_biet_nang' => 'Đặc biệt nặng',
        'nang' => 'Nặng',
        'nhe' => 'Nhẹ',
        'khong_ap_dung' => 'Không áp dụng',
    ];

    public const TRANG_THAI = [
        'dang_huong' => 'Đang hưởng',
        'tam_ngung' => 'Tạm ngừng',
        'het_dieu_kien' => 'Hết điều kiện',
    ];

    public const LOAI_THEO_HO = ['ho_ngheo', 'ho_can_ngheo'];

    protected $table = 'bao_tro_xa_hoi';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'ngay_bat_dau_huong' => 'date',
            'ngay_ket_thuc_huong' => 'date',
            'muc_tro_cap_hang_thang' => 'integer',
        ];
    }

    public function hoKhau(): BelongsTo
    {
        return $this->belongsTo(HoKhau::class, 'ho_khau_id')->withTrashed();
    }

    public function nhanKhau(): BelongsTo
    {
        return $this->belongsTo(NhanKhau::class, 'nhan_khau_id')->withTrashed();
    }

    public function isHoGiaDinh(): bool
    {
        return in_array($this->loai_bao_tro, self::LOAI_THEO_HO, true);
    }

    public function loaiLabel(): string
    {
        return self::LOAI_BAO_TRO[$this->loai_bao_tro] ?? 'Khác';
    }

    public function mucDoKhuyetTatLabel(): string
    {
        return self::MUC_DO_KHUYET_TAT[$this->muc_do_khuyet_tat] ?? 'Không áp dụng';
    }

    public function trangThaiLabel(): string
    {
        return self::TRANG_THAI[$this->trang_thai] ?? 'Không xác định';
    }

    public function doiTuongLabel(): string
    {
        if ($this->ho_khau_id) {
            return $this->hoKhau?->so_so_ho_khau
                ? 'Hộ khẩu '.$this->hoKhau->so_so_ho_khau
                : 'Hộ khẩu chưa xác định';
        }

        return $this->nhanKhau?->ho_ten ?? 'Nhân khẩu chưa xác định';
    }
}
