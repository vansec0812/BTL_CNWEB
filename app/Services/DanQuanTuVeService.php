<?php

namespace App\Services;

use App\Models\DanQuanTuVe;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DanQuanTuVeService
{
    /**
     * Lấy danh sách thành viên dân quân tự vệ kèm bộ lọc và phân trang.
     */
    public function getDanQuanList(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = DanQuanTuVe::query()
            ->with('nhanKhau')
            ->when($filters['q'] ?? null, function ($query, string $keyword): void {
                $query->where(function ($query) use ($keyword): void {
                    $query->where('chuc_vu', 'like', "%{$keyword}%")
                        ->orWhere('don_vi', 'like', "%{$keyword}%")
                        ->orWhereHas('nhanKhau', function ($query) use ($keyword): void {
                            $query->where('ho_ten', 'like', "%{$keyword}%")
                                ->orWhere('cccd_cmnd', 'like', "%{$keyword}%");
                        });
                });
            })
            ->when($filters['trang_thai'] ?? null, fn ($query, string $value) => $query->where('trang_thai', $value))
            ->when($filters['chuc_vu'] ?? null, fn ($query, string $value) => $query->where('chuc_vu', $value))
            ->orderBy('id', 'asc');

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Đăng ký thành viên dân quân tự vệ mới (hỗ trợ thêm đơn lẻ hoặc hàng loạt).
     */
    public function storeMilitia(array $validated): array
    {
        $records = [];
        DB::transaction(function () use ($validated, &$records): void {
            if (isset($validated['nhan_khau_ids']) && is_array($validated['nhan_khau_ids'])) {
                foreach ($validated['nhan_khau_ids'] as $id) {
                    $records[] = DanQuanTuVe::create([
                        'nhan_khau_id' => $id,
                        'chuc_vu' => $validated['chuc_vu'] ?? null,
                        'don_vi' => $validated['don_vi'] ?? null,
                        'ngay_gia_nhap' => $validated['ngay_gia_nhap'] ?? null,
                        'ngay_ket_thuc' => $validated['ngay_ket_thuc'] ?? null,
                        'trang_thai' => $validated['trang_thai'],
                        'ghi_chu' => $validated['ghi_chu'] ?? null,
                    ]);
                }
            } else {
                $records[] = DanQuanTuVe::create($validated);
            }
        });

        return $records;
    }

    /**
     * Cập nhật thông tin thành viên dân quân tự vệ.
     */
    public function updateMilitia(DanQuanTuVe $danQuanTuVe, array $data): DanQuanTuVe
    {
        $danQuanTuVe->update($data);

        return $danQuanTuVe;
    }

    /**
     * Xóa thành viên dân quân tự vệ.
     */
    public function deleteMilitia(DanQuanTuVe $danQuanTuVe): bool
    {
        return $danQuanTuVe->delete();
    }
}
