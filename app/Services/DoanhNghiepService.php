<?php

namespace App\Services;

use App\Models\DoanhNghiep;
use Illuminate\Pagination\LengthAwarePaginator;

class DoanhNghiepService
{
    /**
     * Lấy danh sách doanh nghiệp kèm bộ lọc và phân trang.
     */
    public function getDoanhNghiepList(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = DoanhNghiep::query()->with('nguoiDaiDien');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('ten_co_so', 'like', '%'.$search.'%')
                    ->orWhere('ma_so_thue', 'like', '%'.$search.'%')
                    ->orWhere('ma_so_dang_ky_kinh_doanh', 'like', '%'.$search.'%')
                    ->orWhere('ten_nguoi_dai_dien', 'like', '%'.$search.'%');
            });
        }

        if (! empty($filters['loai_hinh'])) {
            $query->where('loai_hinh', $filters['loai_hinh']);
        }

        if (! empty($filters['trang_thai'])) {
            $query->where('trang_thai', $filters['trang_thai']);
        }

        if (! empty($filters['thon_xom'])) {
            $query->where('thon_xom', 'like', '%'.$filters['thon_xom'].'%');
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Tạo doanh nghiệp/hộ kinh doanh mới.
     */
    public function createDoanhNghiep(array $data): DoanhNghiep
    {
        return DoanhNghiep::create($data);
    }

    /**
     * Cập nhật thông tin doanh nghiệp/hộ kinh doanh.
     */
    public function updateDoanhNghiep(DoanhNghiep $record, array $data): DoanhNghiep
    {
        $record->update($data);

        return $record;
    }

    /**
     * Xóa doanh nghiệp (Soft Delete).
     */
    public function deleteDoanhNghiep(DoanhNghiep $record): bool
    {
        return $record->delete();
    }
}
