<?php

namespace App\Services;

use App\Models\DanQuanHoatDong;
use Illuminate\Pagination\LengthAwarePaginator;

class DanQuanHoatDongService
{
    /**
     * Lấy danh sách hoạt động dân quân kèm bộ lọc và phân trang.
     */
    public function getHoatDongList(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = DanQuanHoatDong::query()->with(['danQuanTuVe.nhanKhau']);

        // Tìm kiếm theo tên hoạt động hoặc tên/CCCD của dân quân tự vệ
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('ten_hoat_dong', 'like', '%' . $search . '%')
                  ->orWhereHas('danQuanTuVe.nhanKhau', function ($sub) use ($search) {
                      $sub->where('ho_ten', 'like', '%' . $search . '%')
                          ->orWhere('cccd_cmnd', 'like', '%' . $search . '%');
                  });
            });
        }

        // Lọc theo loại hoạt động (tap_huan, truc_ban)
        if (! empty($filters['loai_hoat_dong'])) {
            $query->where('loai_hoat_dong', $filters['loai_hoat_dong']);
        }

        // Lọc theo trạng thái
        if (! empty($filters['trang_thai'])) {
            $query->where('trang_thai', $filters['trang_thai']);
        }

        // Lọc theo đơn vị của dân quân tự vệ
        if (! empty($filters['don_vi'])) {
            $query->whereHas('danQuanTuVe', function ($q) use ($filters) {
                $q->where('don_vi', 'like', '%' . $filters['don_vi'] . '%');
            });
        }

        return $query->orderBy('id', 'asc')->paginate($perPage)->withQueryString();
    }

    /**
     * Tạo một hoạt động dân quân mới.
     */
    public function createHoatDongRecord(array $data): DanQuanHoatDong
    {
        return DanQuanHoatDong::create($data);
    }

    /**
     * Cập nhật thông tin hoạt động dân quân.
     */
    public function updateHoatDongRecord(DanQuanHoatDong $record, array $data): DanQuanHoatDong
    {
        $record->update($data);
        return $record;
    }

    /**
     * Xóa hoạt động dân quân.
     */
    public function deleteHoatDongRecord(DanQuanHoatDong $record): bool
    {
        return $record->delete();
    }
}