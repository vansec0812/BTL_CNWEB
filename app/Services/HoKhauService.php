<?php

namespace App\Services;

use App\Models\HoKhau;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class HoKhauService
{
    /**
     * Lấy danh sách hộ khẩu có bộ lọc và phân trang.
     */
    public function getHoKhauList(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = HoKhau::query()->with('chuHo');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('so_so_ho_khau', 'like', "%{$search}%")
                    ->orWhere('ma_ho', 'like', "%{$search}%")
                    ->orWhere('dia_chi_thuong_tru', 'like', "%{$search}%");
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

        return $query->latest()->paginate($perPage);
    }

    /**
     * Tạo hộ khẩu mới.
     */
    public function createHoKhau(array $data): HoKhau
    {
        return HoKhau::create($data);
    }

    /**
     * Cập nhật hộ khẩu.
     */
    public function updateHoKhau(HoKhau $hoKhau, array $data): HoKhau
    {
        $hoKhau->update($data);

        return $hoKhau;
    }

    /**
     * Xóa hộ khẩu (soft delete).
     */
    public function deleteHoKhau(HoKhau $hoKhau): bool
    {
        return $hoKhau->delete();
    }
}
