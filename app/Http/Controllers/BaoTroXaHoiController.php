<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBaoTroXaHoiRequest;
use App\Http\Requests\UpdateBaoTroXaHoiRequest;
use App\Models\BaoTroXaHoi;
use App\Models\HoKhau;
use App\Models\NhanKhau;
use App\Support\ModuleRegistry;
use Illuminate\Http\Request;

class BaoTroXaHoiController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'loai_bao_tro', 'trang_thai', 'doi_tuong']);

        $query = BaoTroXaHoi::query()
            ->with(['hoKhau', 'nhanKhau'])
            ->when($filters['q'] ?? null, function ($query, string $keyword): void {
                $query->where(function ($query) use ($keyword): void {
                    $query->where('so_quyet_dinh', 'like', "%{$keyword}%")
                        ->orWhere('dang_khuyet_tat', 'like', "%{$keyword}%")
                        ->orWhereHas('nhanKhau', function ($query) use ($keyword): void {
                            $query->where('ho_ten', 'like', "%{$keyword}%")
                                ->orWhere('cccd_cmnd', 'like', "%{$keyword}%");
                        })
                        ->orWhereHas('hoKhau', function ($query) use ($keyword): void {
                            $query->where('so_so_ho_khau', 'like', "%{$keyword}%")
                                ->orWhere('ma_ho', 'like', "%{$keyword}%")
                                ->orWhere('dia_chi_thuong_tru', 'like', "%{$keyword}%");
                        });
                });
            })
            ->when($filters['loai_bao_tro'] ?? null, fn ($query, string $value) => $query->where('loai_bao_tro', $value))
            ->when($filters['trang_thai'] ?? null, fn ($query, string $value) => $query->where('trang_thai', $value))
            ->when(($filters['doi_tuong'] ?? null) === 'ho_khau', fn ($query) => $query->whereNotNull('ho_khau_id'))
            ->when(($filters['doi_tuong'] ?? null) === 'nhan_khau', fn ($query) => $query->whereNotNull('nhan_khau_id'))
            ->latest();

        $records = $query->paginate(10)->withQueryString();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách đối tượng bảo trợ xã hội thành công.',
                'data' => $records,
                'stats' => $this->stats(),
            ], 200);
        }

        return view('an-sinh.bao-tro-xa-hoi.index', [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('an-sinh-y-te-giao-duc'),
            'records' => $records,
            'filters' => $filters,
            'loaiBaoTro' => BaoTroXaHoi::LOAI_BAO_TRO,
            'trangThai' => BaoTroXaHoi::TRANG_THAI,
            'stats' => $this->stats(),
        ]);
    }

    public function create(Request $request)
    {
        $formData = $this->formData();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy dữ liệu form tạo mới thành công.',
                'data' => $formData,
            ], 200);
        }

        return view('an-sinh.bao-tro-xa-hoi.create', $formData);
    }

    public function store(StoreBaoTroXaHoiRequest $request)
    {
        $data = $request->validated();
        if (in_array($data['loai_bao_tro'], BaoTroXaHoi::LOAI_THEO_HO, true)) {
            $data['nhan_khau_id'] = null;
        } else {
            $data['ho_khau_id'] = null;
        }
        if ($data['loai_bao_tro'] !== 'nguoi_khuyet_tat') {
            $data['muc_do_khuyet_tat'] = 'khong_ap_dung';
            $data['dang_khuyet_tat'] = null;
        }

        $record = BaoTroXaHoi::create($data);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm hồ sơ bảo trợ xã hội.',
                'data' => $record,
            ], 201);
        }

        return redirect()
            ->route('bao-tro-xa-hoi.index')
            ->with('status', 'Đã thêm hồ sơ bảo trợ xã hội.');
    }

    public function show(BaoTroXaHoi $baoTroXaHoi, Request $request)
    {
        $baoTroXaHoi->load(['hoKhau', 'nhanKhau']);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy chi tiết hồ sơ bảo trợ xã hội thành công.',
                'data' => $baoTroXaHoi,
            ], 200);
        }

        return view('an-sinh.bao-tro-xa-hoi.show', $this->formData($baoTroXaHoi));
    }

    public function edit(BaoTroXaHoi $baoTroXaHoi, Request $request)
    {
        $formData = $this->formData($baoTroXaHoi);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy dữ liệu form chỉnh sửa thành công.',
                'data' => $formData,
            ], 200);
        }

        return view('an-sinh.bao-tro-xa-hoi.edit', $formData);
    }

    public function update(UpdateBaoTroXaHoiRequest $request, BaoTroXaHoi $baoTroXaHoi)
    {
        $data = $request->validated();
        if (in_array($data['loai_bao_tro'], BaoTroXaHoi::LOAI_THEO_HO, true)) {
            $data['nhan_khau_id'] = null;
        } else {
            $data['ho_khau_id'] = null;
        }
        if ($data['loai_bao_tro'] !== 'nguoi_khuyet_tat') {
            $data['muc_do_khuyet_tat'] = 'khong_ap_dung';
            $data['dang_khuyet_tat'] = null;
        }

        $baoTroXaHoi->update($data);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật hồ sơ bảo trợ xã hội.',
                'data' => $baoTroXaHoi,
            ], 200);
        }

        return redirect()
            ->route('bao-tro-xa-hoi.index')
            ->with('status', 'Đã cập nhật hồ sơ bảo trợ xã hội.');
    }

    public function destroy(BaoTroXaHoi $baoTroXaHoi, Request $request)
    {
        $baoTroXaHoi->delete();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã lưu hồ sơ bảo trợ vào trạng thái đã xoá.',
            ], 200);
        }

        return redirect()
            ->route('bao-tro-xa-hoi.index')
            ->with('status', 'Đã lưu hồ sơ bảo trợ vào trạng thái đã xoá.');
    }

    private function formData(?BaoTroXaHoi $record = null): array
    {
        return [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('an-sinh-y-te-giao-duc'),
            'record' => $record,
            'hoKhau' => HoKhau::query()
                ->withTrashed()
                ->where(function ($query) use ($record): void {
                    $query->where(function ($query): void {
                        $query->whereNull('deleted_at')
                            ->where('trang_thai', 'hoat_dong');
                    });

                    if ($record?->ho_khau_id) {
                        $query->orWhere('id', $record->ho_khau_id);
                    }
                })
                ->orderBy('so_so_ho_khau')
                ->get(['id', 'so_so_ho_khau', 'ma_ho', 'dia_chi_thuong_tru', 'thon_xom']),
            'nhanKhau' => NhanKhau::query()
                ->withTrashed()
                ->where(function ($query) use ($record): void {
                    $query->where(function ($query): void {
                        $query->whereNull('deleted_at')
                            ->where('trang_thai', '!=', 'da_mat');
                    });

                    if ($record?->nhan_khau_id) {
                        $query->orWhere('id', $record->nhan_khau_id);
                    }
                })
                ->orderBy('ho_ten')
                ->get(['id', 'ho_ten', 'cccd_cmnd', 'ngay_sinh']),
            'loaiBaoTro' => BaoTroXaHoi::LOAI_BAO_TRO,
            'mucDoKhuyetTat' => BaoTroXaHoi::MUC_DO_KHUYET_TAT,
            'trangThai' => BaoTroXaHoi::TRANG_THAI,
        ];
    }

    private function stats(): array
    {
        return [
            'tong_so' => BaoTroXaHoi::count(),
            'dang_huong' => BaoTroXaHoi::where('trang_thai', 'dang_huong')->count(),
            'ho_ngheo_can_ngheo' => BaoTroXaHoi::whereIn('loai_bao_tro', BaoTroXaHoi::LOAI_THEO_HO)->count(),
            'tong_tro_cap' => BaoTroXaHoi::where('trang_thai', 'dang_huong')->sum('muc_tro_cap_hang_thang'),
        ];
    }
}
