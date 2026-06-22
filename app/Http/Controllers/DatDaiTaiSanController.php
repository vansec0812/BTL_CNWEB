<?php

namespace App\Http\Controllers;

use App\Models\DatDaiTaiSan;
use App\Models\HoKhau;
use App\Support\ModuleRegistry;
use Illuminate\Http\Request;

class DatDaiTaiSanController extends Controller
{
    public function index(Request $request)
    {
        $query = DatDaiTaiSan::with('hoKhau.chuHo');

        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where(function($qBuilder) use ($q) {
                $qBuilder->where('so_gcn_qsdd', 'like', "%{$q}%")
                         ->orWhere('so_to_ban_do', 'like', "%{$q}%")
                         ->orWhere('so_thua_dat', 'like', "%{$q}%")
                         ->orWhere('vi_tri_mo_ta', 'like', "%{$q}%")
                         ->orWhereHas('hoKhau.chuHo', function($subQuery) use ($q) {
                             $subQuery->where('ho_ten', 'like', "%{$q}%");
                         });
            });
        }

        if ($request->filled('loai_dat')) {
            $query->where('loai_dat', $request->get('loai_dat'));
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->get('trang_thai'));
        }

        $records = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $stats = [
            'tong_so' => DatDaiTaiSan::count(),
            'dat_tho_cu' => DatDaiTaiSan::where('loai_dat', 'dat_tho_cu')->count(),
            'dat_nong_nghiep' => DatDaiTaiSan::where('loai_dat', 'dat_nong_nghiep')->count(),
        ];

        return view('dat-dai-tai-san.index', [
            'records' => $records,
            'stats' => $stats,
            'modules' => ModuleRegistry::all(),
        ]);
    }

    public function create()
    {
        $hoKhaus = HoKhau::with('chuHo')->where('trang_thai', 'hoat_dong')->get();
        return view('dat-dai-tai-san.create', [
            'hoKhaus' => $hoKhaus,
            'modules' => ModuleRegistry::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ho_khau_id' => 'required|exists:ho_khau,id',
            'so_to_ban_do' => 'nullable|string|max:50',
            'so_thua_dat' => 'nullable|string|max:50',
            'so_gcn_qsdd' => 'nullable|string|max:100|unique:dat_dai_tai_san,so_gcn_qsdd',
            'loai_dat' => 'required|in:dat_tho_cu,dat_nong_nghiep,dat_lam_nghiep,dat_nuoi_trong_thuy_san,dat_kinh_doanh,khac',
            'dien_tich_m2' => 'required|numeric|min:0.01',
            'vi_tri_mo_ta' => 'nullable|string|max:500',
            'thon_xom' => 'nullable|string|max:100',
            'ngay_cap_gcn' => 'nullable|date',
            'ngay_het_han_gcn' => 'nullable|date|after_or_equal:ngay_cap_gcn',
            'trang_thai' => 'required|in:dang_su_dung,cho_thue,bi_tranh_chap,da_chuyen_nhuong,thu_hoi',
            'ghi_chu' => 'nullable|string',
        ]);

        DatDaiTaiSan::create($validated);

        return redirect()->route('dat-dai-tai-san.index')->with('status', 'Thêm thửa đất thành công!');
    }

    public function show(DatDaiTaiSan $datDaiTaiSan)
    {
        $datDaiTaiSan->load('hoKhau.chuHo');
        return view('dat-dai-tai-san.show', [
            'datDaiTaiSan' => $datDaiTaiSan,
            'modules' => ModuleRegistry::all(),
        ]);
    }

    public function edit(DatDaiTaiSan $datDaiTaiSan)
    {
        $hoKhaus = HoKhau::with('chuHo')->where('trang_thai', 'hoat_dong')->get();
        return view('dat-dai-tai-san.edit', [
            'datDaiTaiSan' => $datDaiTaiSan,
            'hoKhaus' => $hoKhaus,
            'modules' => ModuleRegistry::all(),
        ]);
    }

    public function update(Request $request, DatDaiTaiSan $datDaiTaiSan)
    {
        $validated = $request->validate([
            'ho_khau_id' => 'required|exists:ho_khau,id',
            'so_to_ban_do' => 'nullable|string|max:50',
            'so_thua_dat' => 'nullable|string|max:50',
            'so_gcn_qsdd' => 'nullable|string|max:100|unique:dat_dai_tai_san,so_gcn_qsdd,' . $datDaiTaiSan->id,
            'loai_dat' => 'required|in:dat_tho_cu,dat_nong_nghiep,dat_lam_nghiep,dat_nuoi_trong_thuy_san,dat_kinh_doanh,khac',
            'dien_tich_m2' => 'required|numeric|min:0.01',
            'vi_tri_mo_ta' => 'nullable|string|max:500',
            'thon_xom' => 'nullable|string|max:100',
            'ngay_cap_gcn' => 'nullable|date',
            'ngay_het_han_gcn' => 'nullable|date|after_or_equal:ngay_cap_gcn',
            'trang_thai' => 'required|in:dang_su_dung,cho_thue,bi_tranh_chap,da_chuyen_nhuong,thu_hoi',
            'ghi_chu' => 'nullable|string',
        ]);

        $datDaiTaiSan->update($validated);

        return redirect()->route('dat-dai-tai-san.index')->with('status', 'Cập nhật thửa đất thành công!');
    }

    public function destroy(DatDaiTaiSan $datDaiTaiSan)
    {
        $datDaiTaiSan->delete();
        return redirect()->route('dat-dai-tai-san.index')->with('status', 'Xóa thửa đất thành công!');
    }
}
