<?php

namespace App\Services;

use App\Models\LaoDong;
use App\Models\LichSuCongViec;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LaoDongService
{
    /**
     * Lấy danh sách hồ sơ lao động kèm bộ lọc và phân trang.
     */
    public function getLaoDongList(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = LaoDong::query()->with(['nhanKhau.hoKhau']);

        // Tìm kiếm theo tên, CCCD hoặc thôn xóm của nhân khẩu
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('nhanKhau', function ($q) use ($search) {
                $q->where('ho_ten', 'like', '%'.$search.'%')
                    ->orWhere('cccd_cmnd', 'like', '%'.$search.'%')
                    ->orWhereHas('hoKhau', function ($qh) use ($search) {
                        $qh->where('thon_xom', 'like', '%'.$search.'%');
                    });
            });
        }

        // Lọc theo trạng thái lao động
        if (! empty($filters['trang_thai_lao_dong'])) {
            $query->where('trang_thai_lao_dong', $filters['trang_thai_lao_dong']);
        }

        // Lọc theo ngành nghề
        if (! empty($filters['nganh_nghe'])) {
            $query->where('nganh_nghe', $filters['nganh_nghe']);
        }

        // Lọc theo loại hình công việc
        if (! empty($filters['loai_hinh_cong_viec'])) {
            $query->where('loai_hinh_cong_viec', $filters['loai_hinh_cong_viec']);
        }

        // Lọc xuất khẩu lao động
        if (isset($filters['xuat_khau_lao_dong']) && $filters['xuat_khau_lao_dong'] !== '') {
            $query->where('xuat_khau_lao_dong', (bool) $filters['xuat_khau_lao_dong']);
        }

        // Lọc làm việc ngoài tỉnh
        if (isset($filters['lam_viec_ngoai_tinh']) && $filters['lam_viec_ngoai_tinh'] !== '') {
            $query->where('lam_viec_ngoai_tinh', (bool) $filters['lam_viec_ngoai_tinh']);
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Tạo hồ sơ lao động mới và ghi nhận lịch sử công việc ban đầu.
     */
    public function createLaoDongRecord(array $data): LaoDong
    {
        return DB::transaction(function () use ($data) {
            $record = LaoDong::create($data);

            // Ghi nhận lịch sử công việc ban đầu
            LichSuCongViec::create([
                'lao_dong_id' => $record->id,
                'ten_cong_viec_cu' => 'Bắt đầu tạo hồ sơ',
                'ten_cong_viec_moi' => $record->nghe_nghiep ?? ($record->trangThaiLabel() ?: 'Không có việc'),
                'ly_do_thay_doi' => 'Khởi tạo hồ sơ lao động mới',
                'ngay_thay_doi' => now()->format('Y-m-d'),
                'nguoi_cap_nhat_id' => auth()->id(),
            ]);

            return $record;
        });
    }

    /**
     * Cập nhật hồ sơ lao động và ghi nhận lịch sử thay đổi công việc nếu có.
     */
    public function updateLaoDongRecord(LaoDong $record, array $data): LaoDong
    {
        return DB::transaction(function () use ($record, $data) {
            $oldJob = $record->nghe_nghiep;
            $oldStatus = $record->trang_thai_lao_dong;

            $record->update($data);

            // Kiểm tra xem nghề nghiệp hoặc trạng thái lao động có thay đổi không
            if ($oldJob !== $record->nghe_nghiep || $oldStatus !== $record->trang_thai_lao_dong) {
                LichSuCongViec::create([
                    'lao_dong_id' => $record->id,
                    'ten_cong_viec_cu' => $oldJob ?: (LaoDong::TRANG_THAI_LAO_DONG[$oldStatus] ?? '—'),
                    'ten_cong_viec_moi' => $record->nghe_nghiep ?: $record->trangThaiLabel(),
                    'ly_do_thay_doi' => $data['ly_do_thay_doi'] ?? 'Cập nhật thông tin công việc/trạng thái lao động',
                    'ngay_thay_doi' => now()->format('Y-m-d'),
                    'nguoi_cap_nhat_id' => auth()->id(),
                ]);
            }

            return $record;
        });
    }

    /**
     * Xóa hồ sơ lao động.
     */
    public function deleteLaoDongRecord(LaoDong $record): bool
    {
        return $record->delete();
    }
}
