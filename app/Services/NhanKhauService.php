<?php

namespace App\Services;

use App\Models\NhanKhau;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NhanKhauService
{
    /**
     * Get a paginated list of NhanKhau records based on filters.
     */
    public function getNhanKhauList(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = NhanKhau::query()->with('hoKhau');

        $search = $filters['q'] ?? $filters['search'] ?? null;
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('ho_ten', 'like', "%{$search}%")
                  ->orWhere('cccd_cmnd', 'like', "%{$search}%")
                  ->orWhere('quan_he_chu_ho', 'like', "%{$search}%")
                  ->orWhereHas('hoKhau', function ($q2) use ($search) {
                      $q2->where('so_so_ho_khau', 'like', "%{$search}%")
                        ->orWhere('ma_ho', 'like', "%{$search}%");
                  });
            });
        }

        if (! empty($filters['gioi_tinh'])) {
            $query->where('gioi_tinh', $filters['gioi_tinh']);
        }

        if (! empty($filters['trang_thai'])) {
            $query->where('trang_thai', $filters['trang_thai']);
        }

        if (isset($filters['co_tien_an'])) {
            $query->where('co_tien_an', (bool) $filters['co_tien_an']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    /**
     * Create a new NhanKhau record.
     */
    public function createNhanKhau(array $data): NhanKhau
    {
        // Handle boolean fields
        $data['la_chu_ho'] = !empty($data['la_chu_ho']);
        $data['co_tien_an'] = !empty($data['co_tien_an']);

        return NhanKhau::create($data);
    }

    /**
     * Update an existing NhanKhau record.
     */
    public function updateNhanKhau(NhanKhau $nhanKhau, array $data): NhanKhau
    {
        // Handle boolean fields
        $data['la_chu_ho'] = !empty($data['la_chu_ho']);
        $data['co_tien_an'] = !empty($data['co_tien_an']);

        $nhanKhau->update($data);

        return $nhanKhau;
    }

    /**
     * Delete a NhanKhau record (soft delete).
     */
    public function deleteNhanKhau(NhanKhau $nhanKhau): bool
    {
        return $nhanKhau->delete();
    }
}
