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
        $query = DatDaiTaiSan::with('chuSoHuu');

        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where(function($qBuilder) use ($q) {
                $qBuilder->where('so_gcn_qsdd', 'like', "%{$q}%")
                         ->orWhere('so_to_ban_do', 'like', "%{$q}%")
                         ->orWhere('so_thua_dat', 'like', "%{$q}%")
                         ->orWhere('vi_tri_mo_ta', 'like', "%{$q}%")
                         ->orWhereHas('chuSoHuu', function($subQuery) use ($q) {
                             $subQuery->where('ho_ten', 'like', "%{$q}%")
                                      ->orWhere('cccd_cmnd', 'like', "%{$q}%");
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
        return view('dat-dai-tai-san.create', [
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
        $datDaiTaiSan->load('chuSoHuu');
        return view('dat-dai-tai-san.show', [
            'datDaiTaiSan' => $datDaiTaiSan,
            'modules' => ModuleRegistry::all(),
        ]);
    }

    public function edit(DatDaiTaiSan $datDaiTaiSan)
    {
        return view('dat-dai-tai-san.edit', [
            'datDaiTaiSan' => $datDaiTaiSan,
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

    public function checkCccd(Request $request)
    {
        $cccd = $request->get('cccd');
        if (!$cccd) {
            return response()->json(['success' => false, 'message' => 'Vui lòng nhập CCCD']);
        }

        $nhanKhau = \App\Models\NhanKhau::where('cccd_cmnd', $cccd)->first();
        if ($nhanKhau) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $nhanKhau->id,
                    'ho_ten' => $nhanKhau->ho_ten,
                    'ngay_sinh' => $nhanKhau->ngay_sinh,
                ]
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Không tìm thấy cá nhân với CCCD này']);
    }

    public function chuyenNhuong(Request $request, DatDaiTaiSan $datDaiTaiSan)
    {
        $request->validate([
            'nguoi_mua_id' => 'required|exists:nhan_khau,id',
            'ngay_chuyen_nhuong' => 'required|date',
        ]);

        $nguoiMuaId = $request->input('nguoi_mua_id');
        
        if ($nguoiMuaId == $datDaiTaiSan->chu_so_huu_nhan_khau_id) {
            return back()->withErrors(['error' => 'Người mua không thể là chủ sở hữu hiện tại.']);
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $datDaiTaiSan, $nguoiMuaId) {
            \App\Models\LichSuChuyenNhuongDat::create([
                'dat_dai_tai_san_id' => $datDaiTaiSan->id,
                'nguoi_ban_id' => $datDaiTaiSan->chu_so_huu_nhan_khau_id,
                'nguoi_mua_id' => $nguoiMuaId,
                'ngay_chuyen_nhuong' => $request->input('ngay_chuyen_nhuong'),
                'ghi_chu' => 'Chuyển nhượng qua hệ thống',
            ]);

            $datDaiTaiSan->chu_so_huu_nhan_khau_id = $nguoiMuaId;
            $datDaiTaiSan->save();
        });

        return redirect()->route('dat-dai-tai-san.index')->with('status', 'Sang tên thửa đất thành công!');
    }
}
