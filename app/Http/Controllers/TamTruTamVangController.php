<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTamTruTamVangRequest;
use App\Http\Requests\UpdateTamTruTamVangRequest;
use App\Models\NhanKhau;
use App\Models\TamTruTamVang;
use App\Services\TamTruTamVangService;
use App\Support\ModuleRegistry;
use Illuminate\Http\Request;

class TamTruTamVangController extends Controller
{
    public function __construct(
        private TamTruTamVangService $tamTruService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'loai', 'trang_thai']);
        $perPage = $request->integer('per_page', 10);

        $records = $this->tamTruService->getList($filters, $perPage);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách tạm trú tạm vắng thành công.',
                'data' => $records,
                'stats' => $this->stats(),
            ], 200);
        }

        return view('ho-tich.tam-tru.index', [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('ho-tich-cu-tru'),
            'records' => $records,
            'filters' => $filters,
            'loai' => TamTruTamVang::LOAI,
            'trangThai' => TamTruTamVang::TRANG_THAI,
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
                'message' => 'Lấy dữ liệu form khai báo thành công.',
                'data' => $formData,
            ], 200);
        }

        return view('ho-tich.tam-tru.create', $formData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTamTruTamVangRequest $request)
    {
        $record = $this->tamTruService->createDeclaration($request->validated());

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã tạo khai báo tạm trú / tạm vắng thành công.',
                'data' => $record,
            ], 201);
        }

        return redirect()
            ->route('tam-tru.index')
            ->with('status', 'Đã tạo khai báo tạm trú / tạm vắng thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TamTruTamVang $tamTruTamVang, Request $request)
    {
        $tamTruTamVang->load('nhanKhau');

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy chi tiết khai báo thành công.',
                'data' => $tamTruTamVang,
            ], 200);
        }

        return view('ho-tich.tam-tru.show', $this->formData($tamTruTamVang));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TamTruTamVang $tamTruTamVang, Request $request)
    {
        $formData = $this->formData($tamTruTamVang);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy dữ liệu form chỉnh sửa thành công.',
                'data' => $formData,
            ], 200);
        }

        return view('ho-tich.tam-tru.edit', $formData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTamTruTamVangRequest $request, TamTruTamVang $tamTruTamVang)
    {
        $validated = $request->validated();

        $this->tamTruService->updateDeclaration($tamTruTamVang, $validated);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật khai báo thành công.',
                'data' => $tamTruTamVang,
            ], 200);
        }

        return redirect()
            ->route('tam-tru.index')
            ->with('status', 'Cập nhật khai báo thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TamTruTamVang $tamTruTamVang, Request $request)
    {
        $this->tamTruService->deleteDeclaration($tamTruTamVang);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa khai báo thành công.',
            ], 200);
        }

        return redirect()
            ->route('tam-tru.index')
            ->with('status', 'Xóa khai báo thành công.');
    }

    /**
     * Get shared form data.
     */
    private function formData(?TamTruTamVang $record = null): array
    {
        return [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('ho-tich-cu-tru'),
            'record' => $record,
            'nhanKhauList' => NhanKhau::query()
                ->whereNull('deleted_at')
                ->where('trang_thai', '!=', 'da_mat')
                ->orderBy('ho_ten')
                ->get(['id', 'ho_ten', 'cccd_cmnd', 'ngay_sinh']),
            'loai' => TamTruTamVang::LOAI,
            'trangThai' => TamTruTamVang::TRANG_THAI,
        ];
    }

    /**
     * Calculate stats.
     */
    private function stats(): array
    {
        return [
            'tong_so' => TamTruTamVang::count(),
            'tam_tru' => TamTruTamVang::where('loai', 'tam_tru')->where('trang_thai', 'dang_hieu_luc')->count(),
            'tam_vang' => TamTruTamVang::where('loai', 'tam_vang')->where('trang_thai', 'dang_hieu_luc')->count(),
            'het_han' => TamTruTamVang::where('trang_thai', 'da_het_han')->count(),
        ];
    }
}
