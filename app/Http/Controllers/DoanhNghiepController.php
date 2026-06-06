<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDoanhNghiepRequest;
use App\Http\Requests\UpdateDoanhNghiepRequest;
use App\Models\DoanhNghiep;
use App\Models\NhanKhau;
use App\Services\DoanhNghiepService;
use App\Support\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class DoanhNghiepController extends Controller
{
    public function __construct(
        private DoanhNghiepService $doanhNghiepService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'loai_hinh', 'trang_thai', 'thon_xom']);
        $perPage = $request->integer('per_page', 10);

        $records = $this->doanhNghiepService->getDoanhNghiepList($filters, $perPage);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách doanh nghiệp thành công.',
                'data' => $records,
                'stats' => $this->stats(),
            ], 200);
        }

        return view('lao-dong.doanh-nghiep.index', [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('kinh-te-lao-dong'),
            'records' => $records,
            'filters' => $filters,
            'loaiHinh' => DoanhNghiep::LOAI_HINH,
            'trangThai' => DoanhNghiep::TRANG_THAI,
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
                'message' => 'Lấy dữ liệu tạo mới doanh nghiệp thành công.',
                'data' => $formData,
            ], 200);
        }

        return view('lao-dong.doanh-nghiep.create', $formData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDoanhNghiepRequest $request)
    {
        $record = $this->doanhNghiepService->createDoanhNghiep($request->validated());

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Tạo doanh nghiệp thành công.',
                'data' => $record,
            ], 201);
        }

        return redirect()
            ->route('doanh-nghiep.index')
            ->with('status', 'Đã tạo doanh nghiệp thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DoanhNghiep $doanhNghiep, Request $request)
    {
        $doanhNghiep->load(['nguoiDaiDien', 'ketNoiViecLam.laoDong.nhanKhau']);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy chi tiết doanh nghiệp thành công.',
                'data' => $doanhNghiep,
            ], 200);
        }

        return view('lao-dong.doanh-nghiep.show', $this->formData($doanhNghiep));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DoanhNghiep $doanhNghiep, Request $request)
    {
        $doanhNghiep->load('nguoiDaiDien');
        $formData = $this->formData($doanhNghiep);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy dữ liệu chỉnh sửa doanh nghiệp thành công.',
                'data' => $formData,
            ], 200);
        }

        return view('lao-dong.doanh-nghiep.edit', $formData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDoanhNghiepRequest $request, DoanhNghiep $doanhNghiep)
    {
        $record = $this->doanhNghiepService->updateDoanhNghiep($doanhNghiep, $request->validated());

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật doanh nghiệp thành công.',
                'data' => $record,
            ], 200);
        }

        return redirect()
            ->route('doanh-nghiep.index')
            ->with('status', 'Đã cập nhật doanh nghiệp thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DoanhNghiep $doanhNghiep, Request $request)
    {
        $this->doanhNghiepService->deleteDoanhNghiep($doanhNghiep);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa doanh nghiệp thành công.',
            ], 200);
        }

        return redirect()
            ->route('doanh-nghiep.index')
            ->with('status', 'Đã xóa doanh nghiệp thành công.');
    }

    private function formData(?DoanhNghiep $record = null): array
    {
        $nhanKhau = NhanKhau::query()
            ->whereIn('trang_thai', ['hoat_dong', 'tam_tru', 'tam_vang'])
            ->orderBy('ho_ten')
            ->get(['id', 'ho_ten', 'cccd_cmnd']);

        return [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('kinh-te-lao-dong'),
            'record' => $record,
            'nhanKhau' => $nhanKhau,
            'loaiHinh' => DoanhNghiep::LOAI_HINH,
            'trangThai' => DoanhNghiep::TRANG_THAI,
        ];
    }

    private function stats(): array
    {
        return [
            'tong_doanh_nghiep' => DoanhNghiep::count(),
            'dang_hoat_dong' => DoanhNghiep::where('trang_thai', 'dang_hoat_dong')->count(),
            'tuyen_dung' => DoanhNghiep::where('trang_thai', 'dang_hoat_dong')->sum('so_vi_tri_tuyen_dung'),
        ];
    }
}
