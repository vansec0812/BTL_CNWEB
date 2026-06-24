<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDanQuanHoatDongRequest;
use App\Http\Requests\UpdateDanQuanHoatDongRequest;
use App\Models\DanQuanHoatDong;
use App\Models\DanQuanTuVe;
use App\Services\DanQuanHoatDongService;
use App\Support\ModuleRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DanQuanHoatDongController extends Controller
{
    // Constants for translations
    public const LOAI_HOAT_DONG = [
        'tap_huan' => 'Tập huấn',
        'truc_ban' => 'Trực ban',
    ];

    public const TRANG_THAI = [
        'tham_gia' => 'Tham gia',
        'vang_co_phep' => 'Vắng có phép',
        'vang_khong_phep' => 'Vắng không phép',
        'da_truc' => 'Đã trực',
        'vang_mat' => 'Vắng mặt',
    ];

    public function __construct(
        private DanQuanHoatDongService $danQuanHoatDongService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'loai_hoat_dong', 'trang_thai', 'don_vi']);
        $perPage = $request->integer('per_page', 10);

        $records = $this->danQuanHoatDongService->getHoatDongList($filters, $perPage);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách hoạt động dân quân thành công.',
                'data' => $records,
                'stats' => $this->stats(),
            ], 200);
        }

        return view('nghia-vu-an-ninh.dan-quan-hoat-dong.index', [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('nghia-vu-an-ninh'),
            'records' => $records,
            'filters' => $filters,
            'loaiHoatDong' => self::LOAI_HOAT_DONG,
            'trangThai' => self::TRANG_THAI,
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

        return view('nghia-vu-an-ninh.dan-quan-hoat-dong.create', $formData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDanQuanHoatDongRequest $request)
    {
        $record = $this->danQuanHoatDongService->createHoatDongRecord($request->validated());

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã tạo hoạt động dân quân thành công.',
                'data' => $record,
            ], 201);
        }

        return redirect()
            ->route('dan-quan-hoat-dong.index')
            ->with('status', 'Đã tạo hoạt động dân quân thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DanQuanHoatDong $danQuanHoatDong, Request $request)
    {
        $danQuanHoatDong->load(['danQuanTuVe.nhanKhau']);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy chi tiết hoạt động dân quân thành công.',
                'data' => $danQuanHoatDong,
            ], 200);
        }

        return view('nghia-vu-an-ninh.dan-quan-hoat-dong.show', $this->formData($danQuanHoatDong));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DanQuanHoatDong $danQuanHoatDong, Request $request)
    {
        $danQuanHoatDong->load(['danQuanTuVe.nhanKhau']);
        $formData = $this->formData($danQuanHoatDong);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy dữ liệu form chỉnh sửa thành công.',
                'data' => $formData,
            ], 200);
        }

        return view('nghia-vu-an-ninh.dan-quan-hoat-dong.edit', $formData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDanQuanHoatDongRequest $request, DanQuanHoatDong $danQuanHoatDong)
    {
        $record = $this->danQuanHoatDongService->updateHoatDongRecord($danQuanHoatDong, $request->validated());

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật hoạt động dân quân thành công.',
                'data' => $record,
            ], 200);
        }

        return redirect()
            ->route('dan-quan-hoat-dong.index')
            ->with('status', 'Cập nhật hoạt động dân quân thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DanQuanHoatDong $danQuanHoatDong, Request $request)
    {
        $this->danQuanHoatDongService->deleteHoatDongRecord($danQuanHoatDong);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa hoạt động dân quân thành công.',
            ], 200);
        }

        return redirect()
            ->route('dan-quan-hoat-dong.index')
            ->with('status', 'Xóa hoạt động dân quân thành công.');
    }

    /**
     * Lấy danh sách dân quân tự vệ đang phục vụ (AJAX).
     */
    public function eligibleMilitia(Request $request): JsonResponse
    {
        $search = $request->input('search');

        $query = DanQuanTuVe::query()
            ->with(['nhanKhau'])
            ->where('trang_thai', 'dang_phuc_vu');

        if (! empty($search)) {
            $query->whereHas('nhanKhau', function ($q) use ($search) {
                $q->where('ho_ten', 'like', '%'.$search.'%')
                    ->orWhere('cccd_cmnd', 'like', '%'.$search.'%');
            });
        }

        $militia = $query->limit(15)->get();

        $data = $militia->map(function ($item) {
            return [
                'id' => $item->id,
                'ho_ten' => $item->nhanKhau?->ho_ten ?? 'Không rõ',
                'cccd_cmnd' => $item->nhanKhau?->cccd_cmnd ?? '',
                'chuc_vu' => $item->chuc_vu,
                'don_vi' => $item->don_vi,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    private function formData(?DanQuanHoatDong $record = null): array
    {
        $militiaQuery = DanQuanTuVe::query()
            ->with(['nhanKhau'])
            ->where('trang_thai', 'dang_phuc_vu');

        if ($record) {
            $militiaQuery->orWhere('id', $record->dan_quan_tu_ve_id);
        }

        $militia = $militiaQuery->get()->sortBy(function ($m) {
            return $m->nhanKhau?->ho_ten ?? '';
        });

        return [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('nghia-vu-an-ninh'),
            'record' => $record,
            'militia' => $militia,
            'loaiHoatDong' => self::LOAI_HOAT_DONG,
            'trangThai' => self::TRANG_THAI,
        ];
    }

    private function stats(): array
    {
        return [
            'tong_so' => DanQuanHoatDong::count(),
            'tap_huan' => DanQuanHoatDong::where('loai_hoat_dong', 'tap_huan')->count(),
            'truc_ban' => DanQuanHoatDong::where('loai_hoat_dong', 'truc_ban')->count(),
            'vang' => DanQuanHoatDong::whereIn('trang_thai', ['vang_co_phep', 'vang_khong_phep', 'vang_mat'])->count(),
        ];
    }
}
