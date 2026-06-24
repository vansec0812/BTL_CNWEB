<?php

namespace App\Services;

use App\Models\HoKhau;
use App\Models\NhanKhau;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

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
        return DB::transaction(function () use ($data) {
            $createNewChuHo = ! empty($data['create_new_chu_ho']);

            $hoKhauData = Arr::except($data, [
                'create_new_chu_ho',
                'chu_ho_ho_ten', 'chu_ho_cccd_cmnd', 'chu_ho_ngay_sinh', 'chu_ho_gioi_tinh',
                'chu_ho_dan_toc', 'chu_ho_ton_giao', 'chu_ho_que_quan', 'chu_ho_noi_sinh',
                'chu_ho_trinh_do_hoc_van', 'chu_ho_tinh_trang_hon_nhan',
            ]);

            $hoKhau = HoKhau::create($hoKhauData);

            if ($createNewChuHo) {
                // Create the new resident as head of household
                $nhanKhau = NhanKhau::create([
                    'ho_khau_id' => $hoKhau->id,
                    'ho_ten' => $data['chu_ho_ho_ten'],
                    'cccd_cmnd' => $data['chu_ho_cccd_cmnd'] ?? null,
                    'ngay_sinh' => $data['chu_ho_ngay_sinh'],
                    'gioi_tinh' => $data['chu_ho_gioi_tinh'],
                    'dan_toc' => $data['chu_ho_dan_toc'] ?? 'Kinh',
                    'ton_giao' => $data['chu_ho_ton_giao'] ?? 'Không',
                    'que_quan' => $data['chu_ho_que_quan'],
                    'noi_sinh' => $data['chu_ho_noi_sinh'] ?? null,
                    'trinh_do_hoc_van' => $data['chu_ho_trinh_do_hoc_van'],
                    'tinh_trang_hon_nhan' => $data['chu_ho_tinh_trang_hon_nhan'],
                    'quan_he_chu_ho' => 'Chủ hộ',
                    'la_chu_ho' => true,
                    'trang_thai' => 'hoat_dong',
                ]);

                // Update the household with the new owner's ID
                $hoKhau->update([
                    'chu_ho_nhan_khau_id' => $nhanKhau->id,
                ]);
            } elseif ($hoKhau->chu_ho_nhan_khau_id) {
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

            // Sync/update so_thanh_vien for safety
            $count = NhanKhau::where('ho_khau_id', $hoKhau->id)
                ->where('trang_thai', 'hoat_dong')
                ->count();
            $hoKhau->update(['so_thanh_vien' => $count]);

            return $hoKhau;
        });
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

        // Sync/update so_thanh_vien
        $count = NhanKhau::where('ho_khau_id', $hoKhau->id)
            ->where('trang_thai', 'hoat_dong')
            ->count();
        $hoKhau->update(['so_thanh_vien' => $count]);

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
