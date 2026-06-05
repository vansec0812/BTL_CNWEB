<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNhanKhauRequest;
use App\Http\Requests\UpdateNhanKhauRequest;
use App\Models\HoKhau;
use App\Models\NhanKhau;
use App\Services\NhanKhauService;
use App\Support\ModuleRegistry;
use Illuminate\Http\Request;

class NhanKhauController extends Controller
{
    public function __construct(
        private NhanKhauService $nhanKhauService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'gioi_tinh', 'trang_thai', 'co_tien_an']);
        $perPage = $request->integer('per_page', 10);

        $records = $this->nhanKhauService->getNhanKhauList($filters, $perPage);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách nhân khẩu thành công.',
                'data' => $records,
                'stats' => $this->stats(),
            ], 200);
        }

        return view('ho-tich.nhan-khau.index', [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('ho-tich-cu-tru'),
            'records' => $records,
            'filters' => $filters,
            'gioiTinh' => NhanKhau::GIOI_TINH,
            'trangThai' => NhanKhau::TRANG_THAI,
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

        return view('ho-tich.nhan-khau.create', $formData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNhanKhauRequest $request)
    {
        $nhanKhau = $this->nhanKhauService->createNhanKhau($request->validated());

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm nhân khẩu mới thành công.',
                'data' => $nhanKhau,
            ], 201);
        }

        return redirect()
            ->route('nhan-khau.index')
            ->with('status', 'Đã thêm nhân khẩu mới thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(NhanKhau $nhanKhau, Request $request)
    {
        $nhanKhau->load('hoKhau');

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy chi tiết nhân khẩu thành công.',
                'data' => $nhanKhau,
            ], 200);
        }

        return view('ho-tich.nhan-khau.show', $this->formData($nhanKhau));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NhanKhau $nhanKhau, Request $request)
    {
        $formData = $this->formData($nhanKhau);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy dữ liệu form chỉnh sửa thành công.',
                'data' => $formData,
            ], 200);
        }

        return view('ho-tich.nhan-khau.edit', $formData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNhanKhauRequest $request, NhanKhau $nhanKhau)
    {
        $updated = $this->nhanKhauService->updateNhanKhau($nhanKhau, $request->validated());

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin nhân khẩu thành công.',
                'data' => $updated,
            ], 200);
        }

        return redirect()
            ->route('nhan-khau.index')
            ->with('status', 'Cập nhật thông tin nhân khẩu thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NhanKhau $nhanKhau, Request $request)
    {
        $this->nhanKhauService->deleteNhanKhau($nhanKhau);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa nhân khẩu thành công.',
            ], 200);
        }

        return redirect()
            ->route('nhan-khau.index')
            ->with('status', 'Xóa nhân khẩu thành công.');
    }

    /**
     * Get shared form data.
     */
    private function formData(?NhanKhau $record = null): array
    {
        return [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('ho-tich-cu-tru'),
            'record' => $record,
            'hoKhauList' => HoKhau::query()
                ->orderBy('so_so_ho_khau')
                ->get(['id', 'so_so_ho_khau', 'ma_ho', 'dia_chi_thuong_tru']),
            'gioiTinh' => NhanKhau::GIOI_TINH,
            'trinhDoHocVan' => NhanKhau::TRINH_DO_HOC_VAN,
            'tinhTrangHonNhan' => NhanKhau::TINH_TRANG_HON_NHAN,
            'trangThai' => NhanKhau::TRANG_THAI,
        ];
    }

    /**
     * Calculate stats for the dashboard.
     */
    private function stats(): array
    {
        return [
            'tong_so' => NhanKhau::count(),
            'nam' => NhanKhau::where('gioi_tinh', 'nam')->count(),
            'nu' => NhanKhau::where('gioi_tinh', 'nu')->count(),
            'tam_tru' => NhanKhau::where('trang_thai', 'tam_tru')->count(),
        ];
    }
}
