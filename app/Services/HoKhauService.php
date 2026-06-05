<?php

namespace App\Services;

use App\Models\HoKhau;
use App\Models\NhanKhau;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class HoKhauService
{
    public function getHoKhauList(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = HoKhau::query()->with('chuHo');

        $search = $filters['q'] ?? $filters['search'] ?? null;
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('so_so_ho_khau', 'like', "%{$search}%")
                    ->orWhere('ma_ho', 'like', "%{$search}%")
                    ->orWhere('dia_chi_thuong_tru', 'like', "%{$search}%")
                    ->orWhereHas('chuHo', function ($q2) use ($search) {
                        $q2->where('ho_ten', 'like', "%{$search}%");
                    });
            });
        }

        if (! empty($filters['thon_xom'])) {
            $query->where('thon_xom', $filters['thon_xom']);
        }

        if (! empty($filters['phan_loai'])) {
            $query->where('phan_loai', $filters['phan_loai']);
        }

        if (! empty($filters['trang_thai'])) {
            $query->where('trang_thai', $filters['trang_thai']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    /**
     * Tạo hộ khẩu mới.
     */
    public function createHoKhau(array $data): HoKhau
    {
        $hoKhau = HoKhau::create($data);

        if ($hoKhau->chu_ho_nhan_khau_id) {
            // Update the selected citizen to be head of household and belong to this household
            NhanKhau::where('id', $hoKhau->chu_ho_nhan_khau_id)->update([
                'la_chu_ho' => true,
                'quan_he_chu_ho' => 'Chủ hộ',
                'ho_khau_id' => $hoKhau->id,
            ]);

            // Unset other members
            NhanKhau::where('ho_khau_id', $hoKhau->id)
                ->where('id', '!=', $hoKhau->chu_ho_nhan_khau_id)
                ->update(['la_chu_ho' => false, 'quan_he_chu_ho' => 'Thành viên']);
        }

        return $hoKhau;
    }

    /**
     * Cập nhật hộ khẩu.
     */
    public function updateHoKhau(HoKhau $hoKhau, array $data): HoKhau
    {
        $oldChuHoId = $hoKhau->chu_ho_nhan_khau_id;
        $hoKhau->update($data);

        $newChuHoId = $hoKhau->chu_ho_nhan_khau_id;
        if ($newChuHoId) {
            // Update the selected citizen to be head of household and belong to this household
            NhanKhau::where('id', $newChuHoId)->update([
                'la_chu_ho' => true,
                'quan_he_chu_ho' => 'Chủ hộ',
                'ho_khau_id' => $hoKhau->id,
            ]);

            // Unset other members
            NhanKhau::where('ho_khau_id', $hoKhau->id)
                ->where('id', '!=', $newChuHoId)
                ->update(['la_chu_ho' => false, 'quan_he_chu_ho' => 'Thành viên']);
        } else {
            // If chu_ho_nhan_khau_id is set to null, unset la_chu_ho of the old head of household
            if ($oldChuHoId) {
                NhanKhau::where('id', $oldChuHoId)->update([
                    'la_chu_ho' => false,
                    'quan_he_chu_ho' => 'Thành viên',
                ]);
            }
        }

        return $hoKhau;
    }

    /**
     * Xóa hộ khẩu (soft delete).
     */
    public function deleteHoKhau(HoKhau $hoKhau): bool
    {
        return (bool) $hoKhau->delete();
    }
}
