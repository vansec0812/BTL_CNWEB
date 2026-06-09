<?php

namespace App\Services;

use App\Models\DoanhNghiep;
use App\Models\KetNoiViecLam;
use App\Models\LaoDong;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class KetNoiViecLamService
{
    /**
     * Lấy danh sách kết nối giới thiệu việc làm.
     */
    public function getKetNoiList(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = KetNoiViecLam::query()->with(['laoDong.nhanKhau', 'doanhNghiep', 'nguoiPhuTrach']);

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('laoDong.nhanKhau', function ($ql) use ($search) {
                    $ql->where('ho_ten', 'like', '%'.$search.'%');
                })->orWhereHas('doanhNghiep', function ($qd) use ($search) {
                    $qd->where('ten_co_so', 'like', '%'.$search.'%');
                });
            });
        }

        if (! empty($filters['ket_qua'])) {
            $query->where('ket_qua', $filters['ket_qua']);
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Tìm kiếm doanh nghiệp phù hợp cho người lao động.
     * Doanh nghiệp đang hoạt động và có tuyển dụng (so_vi_tri_tuyen_dung > 0).
     */
    public function matchEligibleJobsForLabor(LaoDong $laoDong)
    {
        // Gợi ý các doanh nghiệp hoạt động cùng ngành nghề chính của người lao động
        // Nganh nghe: nong_nghiep_lam_ngu_nghiep, cong_nghiep_xay_dung, dich_vu_thuong_mai, giao_duc_y_te, hanh_chinh_cong, khac
        $sectorMap = [
            'nong_nghiep_lam_ngu_nghiep' => ['nông nghiệp', 'trồng trọt', 'chăn nuôi', 'lâm nghiệp', 'thủy sản'],
            'cong_nghiep_xay_dung' => ['công nghiệp', 'xây dựng', 'may', 'cơ khí', 'sản xuất', 'nhà máy'],
            'dich_vu_thuong_mai' => ['dịch vụ', 'thương mại', 'tạp hóa', 'bán lẻ', 'vận tải', 'du lịch'],
            'giao_duc_y_te' => ['giáo dục', 'y tế', 'trường học', 'bệnh viện', 'phòng khám'],
            'hanh_chinh_cong' => ['hành chính', 'nhà nước', 'ủy ban'],
        ];

        $query = DoanhNghiep::query()
            ->where('trang_thai', 'dang_hoat_dong')
            ->where('so_vi_tri_tuyen_dung', '>', 0);

        // Loại trừ những doanh nghiệp đã kết nối và đang chờ phản hồi
        $connectedDoanhNghiepIds = KetNoiViecLam::where('lao_dong_id', $laoDong->id)
            ->where('ket_qua', 'dang_cho_phan_hoi')
            ->pluck('doanh_nghiep_id');

        $query->whereNotIn('id', $connectedDoanhNghiepIds);

        $keywords = $sectorMap[$laoDong->nganh_nghe] ?? [];

        if (! empty($keywords)) {
            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $kw) {
                    $q->orWhere('nganh_nghe_chinh', 'like', '%'.$kw.'%');
                }
            });
        }

        return $query->limit(10)->get();
    }

    /**
     * Tìm kiếm lao động thất nghiệp phù hợp cho doanh nghiệp tuyển dụng.
     */
    public function matchEligibleLaborsForJob(DoanhNghiep $doanhNghiep)
    {
        $query = LaoDong::query()
            ->with('nhanKhau')
            ->where('trang_thai_lao_dong', 'that_nghiep');

        // Loại trừ lao động đã kết nối với doanh nghiệp này và đang chờ phản hồi
        $connectedLaborIds = KetNoiViecLam::where('doanh_nghiep_id', $doanhNghiep->id)
            ->where('ket_qua', 'dang_cho_phan_hoi')
            ->pluck('lao_dong_id');

        $query->whereNotIn('id', $connectedLaborIds);

        // Khớp ngành nghề
        $sector = null;
        $industry = mb_strtolower($doanhNghiep->nganh_nghe_chinh);
        if (str_contains($industry, 'nông') || str_contains($industry, 'trồng') || str_contains($industry, 'chăn nuôi') || str_contains($industry, 'thủy sản')) {
            $sector = 'nong_nghiep_lam_ngu_nghiep';
        } elseif (str_contains($industry, 'may') || str_contains($industry, 'công nghiệp') || str_contains($industry, 'xây dựng') || str_contains($industry, 'cơ khí') || str_contains($industry, 'sản xuất')) {
            $sector = 'cong_nghiep_xay_dung';
        } elseif (str_contains($industry, 'dịch vụ') || str_contains($industry, 'thương mại') || str_contains($industry, 'tạp hóa') || str_contains($industry, 'bán lẻ')) {
            $sector = 'dich_vu_thuong_mai';
        } elseif (str_contains($industry, 'giáo dục') || str_contains($industry, 'y tế')) {
            $sector = 'giao_duc_y_te';
        }

        if ($sector) {
            $query->where('nganh_nghe', $sector);
        }

        return $query->limit(15)->get();
    }

    /**
     * Tạo một kết nối việc làm mới.
     */
    public function createKetNoi(array $data): KetNoiViecLam
    {
        return DB::transaction(function () use ($data) {
            $data['ngay_ket_noi'] = $data['ngay_ket_noi'] ?? now()->format('Y-m-d');
            $data['ket_qua'] = $data['ket_qua'] ?? 'dang_cho_phan_hoi';
            $data['nguoi_phu_trach_id'] = auth()->id();

            $record = KetNoiViecLam::create($data);

            // Nếu được tuyển ngay lập tức
            if ($record->ket_qua === 'duoc_nhan') {
                $this->updateEnterpriseLaborStats($record->doanh_nghiep_id);
                $this->updateLaborStatusToEmployed($record->lao_dong_id, $record->vi_tri_gioi_thieu);
            }

            return $record;
        });
    }

    /**
     * Cập nhật kết quả kết nối.
     */
    public function updateKetNoi(KetNoiViecLam $record, array $data): KetNoiViecLam
    {
        return DB::transaction(function () use ($record, $data) {
            $oldResult = $record->ket_qua;
            $record->update($data);

            // Nếu kết quả thay đổi thành được nhận
            if ($oldResult !== 'duoc_nhan' && $record->ket_qua === 'duoc_nhan') {
                $this->updateEnterpriseLaborStats($record->doanh_nghiep_id);
                $this->updateLaborStatusToEmployed($record->lao_dong_id, $record->vi_tri_gioi_thieu);
            }

            return $record;
        });
    }

    /**
     * Xóa kết nối việc làm.
     */
    public function deleteKetNoi(KetNoiViecLam $record): bool
    {
        return $record->delete();
    }

    /**
     * Cập nhật số lao động và số vị trí tuyển dụng của doanh nghiệp.
     */
    private function updateEnterpriseLaborStats(int $doanhNghiepId): void
    {
        $doanhNghiep = DoanhNghiep::find($doanhNghiepId);
        if ($doanhNghiep) {
            $doanhNghiep->increment('so_lao_dong_hien_tai');
            if ($doanhNghiep->so_vi_tri_tuyen_dung > 0) {
                $doanhNghiep->decrement('so_vi_tri_tuyen_dung');
            }
        }
    }

    /**
     * Chuyển trạng thái lao động thành có việc làm và cập nhật nghề nghiệp.
     */
    private function updateLaborStatusToEmployed(int $laoDongId, ?string $jobTitle): void
    {
        $laoDong = LaoDong::find($laoDongId);
        if ($laoDong && $laoDong->trang_thai_lao_dong === 'that_nghiep') {
            $laoDongService = new LaoDongService;
            $laoDongService->updateLaoDongRecord($laoDong, [
                'trang_thai_lao_dong' => 'co_viec_lam',
                'nghe_nghiep' => $jobTitle ?: 'Nhân viên',
                'loai_hinh_cong_viec' => 'tu_nhan', // Mặc định doanh nghiệp địa phương
                'ly_do_thay_doi' => 'Được tuyển dụng thông qua kết nối việc làm địa phương',
            ]);
        }
    }
}
