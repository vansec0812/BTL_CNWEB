<?php

namespace App\Services;

use App\Models\NhanKhau;
use App\Models\TamTruTamVang;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TamTruTamVangService
{
    /**
     * Lấy danh sách khai báo tạm trú tạm vắng.
     */
    public function getList(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = TamTruTamVang::query()
            ->with(['nhanKhau', 'nguoiXacNhan']);

        if (! empty($filters['q'])) {
            $keyword = $filters['q'];
            $query->where(function ($q) use ($keyword) {
                $q->where('ly_do', 'like', "%{$keyword}%")
                    ->orWhere('dia_chi_cu_tru_thuc_te', 'like', "%{$keyword}%")
                    ->orWhere('dia_chi_vang_mat', 'like', "%{$keyword}%")
                    ->orWhereHas('nhanKhau', function ($q2) use ($keyword) {
                        $q2->where('ho_ten', 'like', "%{$keyword}%")
                            ->orWhere('cccd_cmnd', 'like', "%{$keyword}%");
                    });
            });
        }

        if (! empty($filters['loai'])) {
            $query->where('loai', $filters['loai']);
        }

        if (! empty($filters['trang_thai'])) {
            $query->where('trang_thai', $filters['trang_thai']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    /**
     * Tạo khai báo mới.
     */
    public function createDeclaration(array $data): TamTruTamVang
    {
        return DB::transaction(function () use ($data) {
            $nhanKhau = NhanKhau::findOrFail($data['nhan_khau_id']);

            // Cập nhật trạng thái của Nhân khẩu tương ứng
            if ($data['loai'] === 'tam_tru') {
                $nhanKhau->update(['trang_thai' => 'tam_tru']);
            } elseif ($data['loai'] === 'tam_vang') {
                $nhanKhau->update(['trang_thai' => 'tam_vang']);
            }

            $data['nguoi_xac_nhan_id'] = auth()->id();
            $data['trang_thai'] = 'dang_hieu_luc';

            return TamTruTamVang::create($data);
        });
    }

    /**
     * Cập nhật khai báo.
     */
    public function updateDeclaration(TamTruTamVang $record, array $data): TamTruTamVang
    {
        return DB::transaction(function () use ($record, $data) {
            $oldStatus = $record->trang_thai;
            $newStatus = $data['trang_thai'] ?? $oldStatus;

            $record->update($data);

            $nhanKhau = $record->nhanKhau;

            // Nếu thay đổi trạng thái từ đang hiệu lực sang hết hạn/hủy
            if ($oldStatus === 'dang_hieu_luc' && in_array($newStatus, ['da_het_han', 'da_huy'])) {
                // Trả trạng thái nhân khẩu về bình thường (hoat_dong)
                $nhanKhau->update(['trang_thai' => 'hoat_dong']);
            } elseif ($oldStatus !== 'dang_hieu_luc' && $newStatus === 'dang_hieu_luc') {
                // Kích hoạt lại trạng thái tạm trú/tạm vắng
                $nhanKhau->update(['trang_thai' => $record->loai]);
            }

            return $record;
        });
    }

    /**
     * Xóa khai báo.
     */
    public function deleteDeclaration(TamTruTamVang $record): bool
    {
        return DB::transaction(function () use ($record) {
            // Trả trạng thái nhân khẩu về hoạt động bình thường
            $nhanKhau = $record->nhanKhau;
            if ($nhanKhau && in_array($nhanKhau->trang_thai, ['tam_tru', 'tam_vang'])) {
                $nhanKhau->update(['trang_thai' => 'hoat_dong']);
            }

            return (bool) $record->delete();
        });
    }
}
