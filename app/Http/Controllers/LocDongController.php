<?php

namespace App\Http\Controllers;

use App\Models\HoKhau;
use App\Models\NhanKhau;
use App\Support\ModuleRegistry;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LocDongController extends Controller
{
    /**
     * Handle the dynamic filtering of residents.
     */
    public function index(Request $request)
    {
        $query = NhanKhau::query()
            ->with(['hoKhau', 'laoDong', 'doiTuongChinhSach', 'baoTroXaHoi', 'nghiaVuQuanSu'])
            ->whereIn('trang_thai', ['hoat_dong', 'tam_tru', 'tam_vang']); // Only filter active residents by default

        // 1. Personal characteristics filters
        if ($request->filled('gioi_tinh')) {
            $query->where('gioi_tinh', $request->input('gioi_tinh'));
        }

        if ($request->filled('tuoi_tu')) {
            $birthYearMax = now()->subYears($request->integer('tuoi_tu'))->format('Y-12-31');
            $query->where('ngay_sinh', '<=', $birthYearMax);
        }

        if ($request->filled('tuoi_den')) {
            $birthYearMin = now()->subYears($request->integer('tuoi_den') + 1)->addDay()->format('Y-m-d');
            $query->where('ngay_sinh', '>=', $birthYearMin);
        }

        if ($request->filled('dan_toc')) {
            $query->where('dan_toc', 'like', '%'.$request->input('dan_toc').'%');
        }

        if ($request->filled('ton_giao')) {
            $query->where('ton_giao', 'like', '%'.$request->input('ton_giao').'%');
        }

        if ($request->filled('trinh_do_hoc_van')) {
            $query->where('trinh_do_hoc_van', $request->input('trinh_do_hoc_van'));
        }

        if ($request->filled('tinh_trang_hon_nhan')) {
            $query->where('tinh_trang_hon_nhan', $request->input('tinh_trang_hon_nhan'));
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->input('trang_thai'));
        }

        if ($request->filled('co_tien_an')) {
            $query->where('co_tien_an', $request->boolean('co_tien_an'));
        }

        // 2. Household & Location filters
        if ($request->filled('thon_xom')) {
            $query->whereHas('hoKhau', function ($q) use ($request) {
                $q->where('thon_xom', $request->input('thon_xom'));
            });
        }

        if ($request->filled('ho_ngheo')) {
            $query->whereHas('hoKhau.baoTroXaHoi', function ($q) {
                $q->where('loai_bao_tro', 'ho_ngheo')
                    ->where('trang_thai', 'dang_huong')
                    ->whereNull('deleted_at');
            });
        }

        if ($request->filled('ho_can_ngheo')) {
            $query->whereHas('hoKhau.baoTroXaHoi', function ($q) {
                $q->where('loai_bao_tro', 'ho_can_ngheo')
                    ->where('trang_thai', 'dang_huong')
                    ->whereNull('deleted_at');
            });
        }

        // 3. Labor & Employment filters
        if ($request->filled('trang_thai_lao_dong')) {
            $query->whereHas('laoDong', function ($q) use ($request) {
                $q->where('trang_thai_lao_dong', $request->input('trang_thai_lao_dong'));
            });
        }

        if ($request->filled('loai_hinh_cong_viec')) {
            $query->whereHas('laoDong', function ($q) use ($request) {
                $q->where('loai_hinh_cong_viec', $request->input('loai_hinh_cong_viec'));
            });
        }

        if ($request->filled('nganh_nghe')) {
            $query->whereHas('laoDong', function ($q) use ($request) {
                $q->where('nganh_nghe', $request->input('nganh_nghe'));
            });
        }

        if ($request->filled('lam_viec_ngoai_tinh')) {
            $query->whereHas('laoDong', function ($q) use ($request) {
                $q->where('lam_viec_ngoai_tinh', $request->boolean('lam_viec_ngoai_tinh'));
            });
        }

        if ($request->filled('xuat_khau_lao_dong')) {
            $query->whereHas('laoDong', function ($q) use ($request) {
                $q->where('xuat_khau_lao_dong', $request->boolean('xuat_khau_lao_dong'));
            });
        }

        // 4. Policy & Social Support filters
        if ($request->filled('co_dien_chinh_sach')) {
            if ($request->boolean('co_dien_chinh_sach')) {
                $query->whereHas('doiTuongChinhSach', function ($q) {
                    $q->whereNull('deleted_at');
                });
            } else {
                $query->whereDoesntHave('doiTuongChinhSach');
            }
        }

        if ($request->filled('loai_chinh_sach')) {
            $query->whereHas('doiTuongChinhSach', function ($q) use ($request) {
                $q->where('loai_chinh_sach', $request->input('loai_chinh_sach'));
            });
        }

        if ($request->filled('co_bao_tro_xa_hoi')) {
            if ($request->boolean('co_bao_tro_xa_hoi')) {
                $query->whereHas('baoTroXaHoi', function ($q) {
                    $q->whereNotIn('loai_bao_tro', ['ho_ngheo', 'ho_can_ngheo'])
                        ->where('trang_thai', 'dang_huong')
                        ->whereNull('deleted_at');
                });
            } else {
                $query->whereDoesntHave('baoTroXaHoi', function ($q) {
                    $q->whereNotIn('loai_bao_tro', ['ho_ngheo', 'ho_can_ngheo']);
                });
            }
        }

        if ($request->filled('loai_bao_tro')) {
            $query->whereHas('baoTroXaHoi', function ($q) use ($request) {
                $q->where('loai_bao_tro', $request->input('loai_bao_tro'))
                    ->where('trang_thai', 'dang_huong');
            });
        }

        // 5. Military Service filters
        if ($request->filled('trong_do_tuoi_nvqs')) {
            if ($request->boolean('trong_do_tuoi_nvqs')) {
                $targetYear = (int) date('Y');
                $startGeneral = ($targetYear - 25).'-01-01';
                $endGeneral = ($targetYear - 18).'-12-31';

                $startDegree = ($targetYear - 27).'-01-01';
                $endDegree = ($targetYear - 26).'-12-31';

                $query->where('gioi_tinh', 'nam')
                    ->where(function ($q) use ($startGeneral, $endGeneral, $startDegree, $endDegree) {
                        $q->whereBetween('ngay_sinh', [$startGeneral, $endGeneral])
                            ->orWhere(function ($sub) use ($startDegree, $endDegree) {
                                $sub->whereBetween('ngay_sinh', [$startDegree, $endDegree])
                                    ->whereIn('trinh_do_hoc_van', ['dai_hoc', 'sau_dai_hoc']);
                            });
                    });
            }
        }

        if ($request->filled('trang_thai_nvqs')) {
            $query->whereHas('nghiaVuQuanSu', function ($q) use ($request) {
                $q->where('trang_thai_nvqs', $request->input('trang_thai_nvqs'));
            });
        }

        // 6. Land ownership filters
        if ($request->filled('co_dat_tho_cu')) {
            if ($request->boolean('co_dat_tho_cu')) {
                $query->whereHas('hoKhau.datDaiTaiSan', function ($q) {
                    $q->where('loai_dat', 'dat_tho_cu')
                        ->whereNull('deleted_at');
                });
            } else {
                $query->whereDoesntHave('hoKhau.datDaiTaiSan', function ($q) {
                    $q->where('loai_dat', 'dat_tho_cu');
                });
            }
        }

        if ($request->filled('co_dat_nong_nghiep')) {
            if ($request->boolean('co_dat_nong_nghiep')) {
                $query->whereHas('hoKhau.datDaiTaiSan', function ($q) {
                    $q->where('loai_dat', 'dat_nong_nghiep')
                        ->whereNull('deleted_at');
                });
            } else {
                $query->whereDoesntHave('hoKhau.datDaiTaiSan', function ($q) {
                    $q->where('loai_dat', 'dat_nong_nghiep');
                });
            }
        }

        if ($request->filled('dien_tich_dat_tu')) {
            $dienTichTu = $request->input('dien_tich_dat_tu');
            $query->whereHas('hoKhau', function ($q) use ($dienTichTu) {
                $q->whereIn('id', function ($sub) use ($dienTichTu) {
                    $sub->select('ho_khau_id')
                        ->from('dat_dai_tai_san')
                        ->whereNull('deleted_at')
                        ->groupBy('ho_khau_id')
                        ->havingRaw('SUM(dien_tich) >= ?', [$dienTichTu]);
                });
            });
        }

        // Get unpaginated collection for statistics
        $allMatches = $query->get();
        $total = $allMatches->count();
        $males = $allMatches->where('gioi_tinh', 'nam')->count();
        $females = $allMatches->where('gioi_tinh', 'nu')->count();

        $avgAge = 0;
        if ($total > 0) {
            $totalAge = 0;
            foreach ($allMatches as $m) {
                $totalAge += Carbon::parse($m->ngay_sinh)->age;
            }
            $avgAge = round($totalAge / $total, 1);
        }

        // Labor breakdown for matches
        $coViec = $allMatches->filter(fn ($nk) => $nk->laoDong?->trang_thai_lao_dong === 'co_viec_lam')->count();
        $thatNghiep = $allMatches->filter(fn ($nk) => $nk->laoDong?->trang_thai_lao_dong === 'that_nghiep')->count();
        $hocSinh = $allMatches->filter(fn ($nk) => $nk->laoDong?->trang_thai_lao_dong === 'hoc_sinh_sinh_vien')->count();
        $khacLaoDong = $total - $coViec - $thatNghiep - $hocSinh;

        $stats = [
            'total' => $total,
            'nam' => $males,
            'nu' => $females,
            'avg_age' => $avgAge,
            'co_viec' => $coViec,
            'that_nghiep' => $thatNghiep,
            'hoc_sinh' => $hocSinh,
            'khac_lao_dong' => $khacLaoDong,
        ];

        // Paginate the results
        $results = $query->orderBy('ho_ten', 'asc')->paginate(15)->withQueryString();

        // Get dropdown lists
        $thonXomList = HoKhau::query()
            ->whereNotNull('thon_xom')
            ->where('thon_xom', '!=', '')
            ->distinct()
            ->orderBy('thon_xom', 'asc')
            ->pluck('thon_xom');

        $modules = ModuleRegistry::all();
        $parentModule = ModuleRegistry::findBySlug('he-thong-bao-cao');

        return view('he-thong.loc_dong', compact(
            'results',
            'stats',
            'thonXomList',
            'modules',
            'parentModule'
        ));
    }
}
