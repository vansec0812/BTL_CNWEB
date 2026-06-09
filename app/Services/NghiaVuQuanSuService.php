<?php

namespace App\Services;

use App\Models\NghiaVuQuanSu;
use App\Models\NhanKhau;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class NghiaVuQuanSuService
{
    /**
     * Lấy danh sách hồ sơ nghĩa vụ quân sự kèm bộ lọc và phân trang.
     */
    public function getNghiaVuList(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = NghiaVuQuanSu::query()->with(['nhanKhau.hoKhau']);

        // Tìm kiếm theo tên hoặc CCCD/CMND của nhân khẩu
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('nhanKhau', function ($q) use ($search) {
                $q->where('ho_ten', 'like', '%'.$search.'%')
                    ->orWhere('cccd_cmnd', 'like', '%'.$search.'%');
            });
        }

        // Lọc theo trạng thái NVQS
        if (! empty($filters['trang_thai_nvqs'])) {
            $query->where('trang_thai_nvqs', $filters['trang_thai_nvqs']);
        }

        // Lọc theo năm tuổi tuyển quan
        if (! empty($filters['nam_tuoi_tuyen_quan'])) {
            $query->where('nam_tuoi_tuyen_quan', $filters['nam_tuoi_tuyen_quan']);
        }

        // Lọc theo thôn xóm của hộ khẩu
        if (! empty($filters['thon_xom'])) {
            $query->whereHas('nhanKhau.hoKhau', function ($q) use ($filters) {
                $q->where('thon_xom', 'like', '%'.$filters['thon_xom'].'%');
            });
        }

        return $query->orderBy('id', 'asc')->paginate($perPage)->withQueryString();
    }

    /**
     * Lấy danh sách nam thanh niên đủ tuổi nghĩa vụ quân sự chưa có hồ sơ.
     * Độ tuổi: 18 - 25 hoặc đến 27 nếu có bằng đại học/sau đại học.
     */
    public function getEligibleScannedCitizens(int $targetYear)
    {
        // 18 tuổi: Y - 18. VD: 2026 - 18 = 2008
        // 25 tuổi: Y - 25. VD: 2026 - 25 = 2001
        // 26-27 tuổi: Y - 27 đến Y - 26. VD: 1999 đến 2000
        $startGeneral = ($targetYear - 25).'-01-01';
        $endGeneral = ($targetYear - 18).'-12-31';

        $startDegree = ($targetYear - 27).'-01-01';
        $endDegree = ($targetYear - 26).'-12-31';

        return NhanKhau::query()
            ->where('gioi_tinh', 'nam')
            ->whereIn('trang_thai', ['hoat_dong', 'tam_tru', 'tam_vang'])
            ->where(function ($query) use ($startGeneral, $endGeneral, $startDegree, $endDegree) {
                $query->whereBetween('ngay_sinh', [$startGeneral, $endGeneral])
                    ->orWhere(function ($q) use ($startDegree, $endDegree) {
                        $q->whereBetween('ngay_sinh', [$startDegree, $endDegree])
                            ->whereIn('trinh_do_hoc_van', ['dai_hoc', 'sau_dai_hoc']);
                    });
            })
            ->whereDoesntHave('nghiaVuQuanSu')
            ->orderBy('ho_ten', 'asc')
            ->get(['id', 'ho_ten', 'cccd_cmnd', 'ngay_sinh', 'trinh_do_hoc_van']);
    }

    /**
     * Thêm hàng loạt công dân được chọn vào danh sách NVQS.
     */
    public function storeScannedCitizens(int $targetYear, array $nhanKhauIds): int
    {
        $addedCount = 0;
        DB::transaction(function () use ($nhanKhauIds, $targetYear, &$addedCount) {
            foreach ($nhanKhauIds as $nhanKhauId) {
                // Kiểm tra xem đã có bản ghi chưa để tránh chèn trùng lặp
                $exists = NghiaVuQuanSu::where('nhan_khau_id', $nhanKhauId)->exists();
                if (! $exists) {
                    NghiaVuQuanSu::create([
                        'nhan_khau_id' => $nhanKhauId,
                        'nam_tuoi_tuyen_quan' => $targetYear,
                        'trang_thai_nvqs' => 'du_dieu_kien',
                        'ly_do_tam_hoan' => 'khong_ap_dung',
                        'ket_qua_kham_suc_khoe' => 'chua_kham',
                    ]);
                    $addedCount++;
                }
            }
        });

        return $addedCount;
    }

    /**
     * Tạo thủ công một hồ sơ NVQS mới.
     */
    public function createNghiaVuRecord(array $data): NghiaVuQuanSu
    {
        return NghiaVuQuanSu::create($data);
    }

    /**
     * Cập nhật thông tin hồ sơ NVQS.
     */
    public function updateNghiaVuRecord(NghiaVuQuanSu $record, array $data): NghiaVuQuanSu
    {
        $record->update($data);

        return $record;
    }

    /**
     * Xóa hồ sơ NVQS.
     */
    public function deleteNghiaVuRecord(NghiaVuQuanSu $record): bool
    {
        return $record->delete();
    }
}
