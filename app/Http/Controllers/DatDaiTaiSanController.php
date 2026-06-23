<?php

namespace App\Http\Controllers;

use App\Models\DatDaiTaiSan;
use App\Models\HoKhau;
use App\Support\ModuleRegistry;
use Illuminate\Http\Request;
use App\Http\Requests\StoreDatDaiTaiSanRequest;
use App\Http\Requests\UpdateDatDaiTaiSanRequest;

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

    public function store(StoreDatDaiTaiSanRequest $request)
    {
        $validated = $request->validated();

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

    public function update(UpdateDatDaiTaiSanRequest $request, DatDaiTaiSan $datDaiTaiSan)
    {
        $validated = $request->validated();

        $datDaiTaiSan->update($validated);

        return redirect()->route('dat-dai-tai-san.index')->with('status', 'Cập nhật thửa đất thành công!');
    }

    public function destroy(DatDaiTaiSan $datDaiTaiSan)
    {
        $datDaiTaiSan->delete();
        return redirect()->route('dat-dai-tai-san.index')->with('status', 'Xóa thửa đất thành công!');
    }
}
