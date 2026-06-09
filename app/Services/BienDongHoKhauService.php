<?php

namespace App\Services;

use App\Models\BienDongHoKhau;
use App\Models\HoKhau;
use App\Models\NhanKhau;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BienDongHoKhauService
{
    /**
     * Lấy danh sách biến động có phân trang và bộ lọc.
     */
    public function getBienDongList(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = BienDongHoKhau::query()
            ->with(['hoKhauNguon', 'hoKhauDich', 'nhanKhau', 'nguoiThucHien']);

        if (! empty($filters['q'])) {
            $keyword = $filters['q'];
            $query->where(function ($q) use ($keyword) {
                $q->where('so_quyet_dinh', 'like', "%{$keyword}%")
                    ->orWhere('ly_do', 'like', "%{$keyword}%")
                    ->orWhereHas('nhanKhau', function ($q2) use ($keyword) {
                        $q2->where('ho_ten', 'like', "%{$keyword}%")
                            ->orWhere('cccd_cmnd', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('hoKhauNguon', function ($q2) use ($keyword) {
                        $q2->where('so_so_ho_khau', 'like', "%{$keyword}%")
                            ->orWhere('ma_ho', 'like', "%{$keyword}%");
                    });
            });
        }

        if (! empty($filters['loai_bien_dong'])) {
            $query->where('loai_bien_dong', $filters['loai_bien_dong']);
        }

        if (! empty($filters['ngay_bat_dau'])) {
            $query->whereDate('ngay_bien_dong', '>=', $filters['ngay_bat_dau']);
        }

        if (! empty($filters['ngay_ket_thuc'])) {
            $query->whereDate('ngay_bien_dong', '<=', $filters['ngay_ket_thuc']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    /**
     * Nghiệp vụ tách hộ.
     */
    public function tachHo(array $data): HoKhau
    {
        return DB::transaction(function () use ($data) {
            $sourceHoKhau = HoKhau::findOrFail($data['ho_khau_nguon_id']);

            // 1. Tạo sổ hộ khẩu mới (hộ đích)
            $destHoKhau = HoKhau::create([
                'so_so_ho_khau' => $data['so_so_ho_khau_moi'],
                'ma_ho' => $data['ma_ho_moi'],
                'dia_chi_thuong_tru' => $data['dia_chi_thuong_tru_moi'],
                'thon_xom' => $data['thon_xom_moi'] ?? $sourceHoKhau->thon_xom,
                'phan_loai' => $data['phan_loai_moi'] ?? 'thuong_tru',
                'ngay_lap_so' => $data['ngay_bien_dong'],
                'trang_thai' => 'hoat_dong',
                'ghi_chu' => $data['ghi_chu_moi'] ?? null,
            ]);

            $newChuHoId = (int) $data['new_chu_ho_id'];
            $movedMembers = $data['thanh_vien_ids'] ?? [];

            // Đảm bảo chủ hộ mới có trong danh sách dời đi
            if (! in_array($newChuHoId, $movedMembers)) {
                $movedMembers[] = $newChuHoId;
            }

            // 2. Cập nhật các nhân khẩu dời đi sang hộ khẩu mới
            foreach ($movedMembers as $memberId) {
                $nhanKhau = NhanKhau::findOrFail($memberId);
                $oldHoKhauId = $nhanKhau->ho_khau_id;

                $isNewChuHo = ($nhanKhau->id === $newChuHoId);

                // Quan hệ đối với chủ hộ mới
                $quanHe = $isNewChuHo ? 'Chủ hộ' : ($data['quan_he'][$nhanKhau->id] ?? 'Thành viên');

                $nhanKhau->update([
                    'ho_khau_id' => $destHoKhau->id,
                    'la_chu_ho' => $isNewChuHo,
                    'quan_he_chu_ho' => $quanHe,
                ]);

                // 3. Ghi nhận biến động
                BienDongHoKhau::create([
                    'loai_bien_dong' => 'tach_ho',
                    'ho_khau_nguon_id' => $sourceHoKhau->id,
                    'ho_khau_dich_id' => $destHoKhau->id,
                    'nhan_khau_id' => $nhanKhau->id,
                    'ngay_bien_dong' => $data['ngay_bien_dong'],
                    'ly_do' => $data['ly_do'] ?? 'Tách hộ gia đình',
                    'so_quyet_dinh' => $data['so_quyet_dinh'] ?? null,
                    'nguoi_thuc_hien_id' => auth()->id(),
                    'ghi_chu' => $data['ghi_chu'] ?? null,
                ]);
            }

            // Thiết lập chủ hộ cho hộ mới
            $destHoKhau->update([
                'chu_ho_nhan_khau_id' => $newChuHoId,
            ]);

            // 4. Nếu chủ hộ cũ nằm trong danh sách dời đi, thì hộ cũ cần bầu chủ hộ mới (hoặc đặt tạm null)
            if (in_array($sourceHoKhau->chu_ho_nhan_khau_id, $movedMembers)) {
                $sourceHoKhau->update([
                    'chu_ho_nhan_khau_id' => null,
                ]);
            }

            // 5. Cập nhật lại số lượng thành viên của cả 2 hộ
            $this->updateSoThanhVien($sourceHoKhau->id);
            $this->updateSoThanhVien($destHoKhau->id);

            return $destHoKhau;
        });
    }

    /**
     * Nghiệp vụ nhập hộ.
     */
    public function nhapHo(array $data): HoKhau
    {
        return DB::transaction(function () use ($data) {
            $destHoKhau = HoKhau::findOrFail($data['ho_khau_dich_id']);
            $nhanKhauId = (int) $data['nhan_khau_id'];
            $nhanKhau = NhanKhau::findOrFail($nhanKhauId);

            $oldHoKhauId = $nhanKhau->ho_khau_id;

            // Cập nhật hộ mới cho nhân khẩu
            $nhanKhau->update([
                'ho_khau_id' => $destHoKhau->id,
                'la_chu_ho' => false, // nhập hộ thì không thể tự động làm chủ hộ, trừ khi đổi chủ hộ
                'quan_he_chu_ho' => $data['quan_he_chu_ho'] ?? 'Thành viên',
            ]);

            // Ghi nhận biến động
            BienDongHoKhau::create([
                'loai_bien_dong' => 'nhap_ho',
                'ho_khau_nguon_id' => $oldHoKhauId ?? $destHoKhau->id,
                'ho_khau_dich_id' => $destHoKhau->id,
                'nhan_khau_id' => $nhanKhau->id,
                'ngay_bien_dong' => $data['ngay_bien_dong'],
                'ly_do' => $data['ly_do'] ?? 'Nhập hộ khẩu',
                'so_quyet_dinh' => $data['so_quyet_dinh'] ?? null,
                'nguoi_thuc_hien_id' => auth()->id(),
                'ghi_chu' => $data['ghi_chu'] ?? null,
            ]);

            // Cập nhật số lượng thành viên của hộ đích và hộ nguồn cũ (nếu có)
            $this->updateSoThanhVien($destHoKhau->id);
            if ($oldHoKhauId) {
                $this->updateSoThanhVien($oldHoKhauId);
            }

            return $destHoKhau;
        });
    }

    /**
     * Nghiệp vụ chuyển đi (ngoài xã).
     */
    public function chuyenDi(array $data): void
    {
        DB::transaction(function () use ($data) {
            if (! empty($data['nhan_khau_id'])) {
                // Chuyển đi cá nhân
                $nhanKhau = NhanKhau::findOrFail($data['nhan_khau_id']);
                $oldHoKhauId = $nhanKhau->ho_khau_id;

                $nhanKhau->update([
                    'trang_thai' => 'da_chuyen_di',
                    'ngay_chuyen_di' => $data['ngay_bien_dong'],
                    'la_chu_ho' => false,
                    'quan_he_chu_ho' => null,
                ]);

                // Nếu là chủ hộ, reset chủ hộ
                if ($oldHoKhauId) {
                    $hoKhau = HoKhau::find($oldHoKhauId);
                    if ($hoKhau && $hoKhau->chu_ho_nhan_khau_id === $nhanKhau->id) {
                        $hoKhau->update(['chu_ho_nhan_khau_id' => null]);
                    }
                }

                BienDongHoKhau::create([
                    'loai_bien_dong' => 'chuyen_di',
                    'ho_khau_nguon_id' => $oldHoKhauId ?? 1, // fallback
                    'nhan_khau_id' => $nhanKhau->id,
                    'ngay_bien_dong' => $data['ngay_bien_dong'],
                    'ly_do' => $data['ly_do'] ?? 'Chuyển đi nơi khác',
                    'dia_chi_chuyen_den' => $data['dia_chi_chuyen_den'] ?? null,
                    'so_quyet_dinh' => $data['so_quyet_dinh'] ?? null,
                    'nguoi_thuc_hien_id' => auth()->id(),
                    'ghi_chu' => $data['ghi_chu'] ?? null,
                ]);

                if ($oldHoKhauId) {
                    $this->updateSoThanhVien($oldHoKhauId);
                }
            } elseif (! empty($data['ho_khau_id'])) {
                // Chuyển đi cả hộ
                $hoKhau = HoKhau::findOrFail($data['ho_khau_id']);

                $hoKhau->update([
                    'trang_thai' => 'chuyen_di',
                ]);

                $thanhVien = NhanKhau::where('ho_khau_id', $hoKhau->id)->get();
                foreach ($thanhVien as $nhanKhau) {
                    $nhanKhau->update([
                        'trang_thai' => 'da_chuyen_di',
                        'ngay_chuyen_di' => $data['ngay_bien_dong'],
                    ]);

                    BienDongHoKhau::create([
                        'loai_bien_dong' => 'chuyen_di',
                        'ho_khau_nguon_id' => $hoKhau->id,
                        'nhan_khau_id' => $nhanKhau->id,
                        'ngay_bien_dong' => $data['ngay_bien_dong'],
                        'ly_do' => $data['ly_do'] ?? 'Chuyển đi cả hộ',
                        'dia_chi_chuyen_den' => $data['dia_chi_chuyen_den'] ?? null,
                        'so_quyet_dinh' => $data['so_quyet_dinh'] ?? null,
                        'nguoi_thuc_hien_id' => auth()->id(),
                        'ghi_chu' => $data['ghi_chu'] ?? null,
                    ]);
                }

                $this->updateSoThanhVien($hoKhau->id);
            }
        });
    }

    /**
     * Nghiệp vụ chuyển đến (ngoài xã).
     */
    public function chuyenDen(array $data): NhanKhau
    {
        return DB::transaction(function () use ($data) {
            // Chuyển đến thường sẽ tạo mới một nhân khẩu và đưa vào một hộ có sẵn
            $hoKhau = HoKhau::findOrFail($data['ho_khau_id']);

            $nhanKhauData = $data['nhan_khau'];
            $nhanKhauData['ho_khau_id'] = $hoKhau->id;
            $nhanKhauData['trang_thai'] = 'hoat_dong';
            $nhanKhauData['la_chu_ho'] = false;
            $nhanKhauData['co_tien_an'] = false;

            $nhanKhau = NhanKhau::create($nhanKhauData);

            BienDongHoKhau::create([
                'loai_bien_dong' => 'chuyen_den',
                'ho_khau_nguon_id' => $hoKhau->id,
                'ho_khau_dich_id' => $hoKhau->id,
                'nhan_khau_id' => $nhanKhau->id,
                'ngay_bien_dong' => $data['ngay_bien_dong'],
                'ly_do' => $data['ly_do'] ?? 'Chuyển đến từ địa phương khác',
                'so_quyet_dinh' => $data['so_quyet_dinh'] ?? null,
                'nguoi_thuc_hien_id' => auth()->id(),
                'ghi_chu' => $data['ghi_chu'] ?? null,
            ]);

            $this->updateSoThanhVien($hoKhau->id);

            return $nhanKhau;
        });
    }

    /**
     * Cập nhật số lượng thành viên của một hộ.
     */
    public function updateSoThanhVien(int $hoKhauId): void
    {
        $count = NhanKhau::where('ho_khau_id', $hoKhauId)
            ->where('trang_thai', 'hoat_dong')
            ->count();

        HoKhau::where('id', $hoKhauId)->update(['so_thanh_vien' => $count]);
    }
}
