<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHoKhauRequest;
use App\Http\Requests\UpdateHoKhauRequest;
use App\Models\HoKhau;
use App\Models\NhanKhau;
use App\Services\HoKhauService;
use App\Support\ModuleRegistry;
use Illuminate\Http\Request;

class HoKhauController extends Controller
{
    public function __construct(
        private HoKhauService $hoKhauService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'thon_xom', 'phan_loai', 'trang_thai']);
        $perPage = $request->integer('per_page', 10);

        $records = $this->hoKhauService->getHoKhauList($filters, $perPage);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách hộ khẩu thành công.',
                'data' => $records,
                'stats' => $this->stats(),
            ], 200);
        }

        return view('ho-tich.ho-khau.index', [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('ho-tich-cu-tru'),
            'records' => $records,
            'filters' => $filters,
            'phanLoai' => HoKhau::PHAN_LOAI,
            'trangThai' => HoKhau::TRANG_THAI,
            'stats' => $this->stats(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
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

        return view('ho-tich.ho-khau.create', $formData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHoKhauRequest $request)
    {
        $hoKhau = $this->hoKhauService->createHoKhau($request->validated());

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã tạo sổ hộ khẩu mới thành công.',
                'data' => $hoKhau,
            ], 201);
        }

        return redirect()
            ->route('ho-khau.index')
            ->with('status', 'Đã tạo sổ hộ khẩu mới thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HoKhau $hoKhau, Request $request)
    {
        $hoKhau->load(['thanhVien', 'chuHo']);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy chi tiết sổ hộ khẩu thành công.',
                'data' => $hoKhau,
            ], 200);
        }

        return view('ho-tich.ho-khau.show', $this->formData($hoKhau));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HoKhau $hoKhau, Request $request)
    {
        $formData = $this->formData($hoKhau);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy dữ liệu form chỉnh sửa thành công.',
                'data' => $formData,
            ], 200);
        }

        return view('ho-tich.ho-khau.edit', $formData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHoKhauRequest $request, HoKhau $hoKhau)
    {
        $updated = $this->hoKhauService->updateHoKhau($hoKhau, $request->validated());

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật sổ hộ khẩu thành công.',
                'data' => $updated,
            ], 200);
        }

        return redirect()
            ->route('ho-khau.index')
            ->with('status', 'Cập nhật sổ hộ khẩu thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HoKhau $hoKhau, Request $request)
    {
        $this->hoKhauService->deleteHoKhau($hoKhau);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa sổ hộ khẩu thành công.',
            ], 200);
        }

        return redirect()
            ->route('ho-khau.index')
            ->with('status', 'Xóa sổ hộ khẩu thành công.');
    }

    /**
     * Get shared form data.
     */
    private function formData(?HoKhau $record = null): array
    {
        return [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('ho-tich-cu-tru'),
            'record' => $record,
            'nhanKhau' => NhanKhau::query()
                ->withTrashed()
                ->where(function ($query) use ($record): void {
                    $query->where(function ($query): void {
                        $query->whereNull('deleted_at')
                            ->where('trang_thai', '!=', 'da_mat');
                    });

                    if ($record?->chu_ho_nhan_khau_id) {
                        $query->orWhere('id', $record->chu_ho_nhan_khau_id);
                    }
                })
                ->orderBy('ho_ten')
                ->get(['id', 'ho_ten', 'cccd_cmnd', 'ngay_sinh']),
            'phanLoai' => HoKhau::PHAN_LOAI,
            'trangThai' => HoKhau::TRANG_THAI,
            'gioiTinh' => NhanKhau::GIOI_TINH,
            'trinhDoHocVan' => NhanKhau::TRINH_DO_HOC_VAN,
            'tinhTrangHonNhan' => NhanKhau::TINH_TRANG_HON_NHAN,
        ];
    }

    /**
     * Calculate stats for the dashboard.
     */
    private function stats(): array
    {
        return [
            'tong_so' => HoKhau::count(),
            'thuong_tru' => HoKhau::where('phan_loai', 'thuong_tru')->count(),
            'tam_tru' => HoKhau::where('phan_loai', 'tam_tru')->count(),
            'tam_vang' => HoKhau::where('phan_loai', 'tam_vang')->count(),
        ];
    }
}
