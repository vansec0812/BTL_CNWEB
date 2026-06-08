<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDanQuanTuVeRequest;
use App\Http\Requests\UpdateDanQuanTuVeRequest;
use App\Http\Controllers\DanQuanHoatDongController;
use App\Models\DanQuanTuVe;
use App\Models\NhanKhau;
use App\Services\DanQuanTuVeService;
use App\Support\ModuleRegistry;
use Illuminate\Http\Request;

class DanQuanTuVeController extends Controller
{
    public const TRANG_THAI = [
        'dang_phuc_vu' => 'Đang phục vụ',
        'da_hoan_thanh' => 'Đã hoàn thành nghĩa vụ',
        'da_roi' => 'Đã rời lực lượng',
    ];

    public function __construct(
        private DanQuanTuVeService $danQuanTuVeService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['q', 'trang_thai', 'don_vi']);
        $perPage = $request->integer('per_page', 10);

        $records = $this->danQuanTuVeService->getDanQuanList($filters, $perPage);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách dân quân tự vệ thành công.',
                'data' => $records,
                'stats' => $this->stats(),
            ], 200);
        }

        return view('nghia-vu-an-ninh.dan-quan-tu-ve.index', [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('nghia-vu-an-ninh'),
            'records' => $records,
            'filters' => $filters,
            'trangThai' => self::TRANG_THAI,
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

        return view('nghia-vu-an-ninh.dan-quan-tu-ve.create', $formData);
    }

    public function store(StoreDanQuanTuVeRequest $request)
    {
        $records = $this->danQuanTuVeService->storeMilitia($request->validated());

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm thành viên dân quân tự vệ thành công.',
                'data' => count($records) === 1 ? $records[0] : $records,
            ], 201);
        }

        $msg = 'Đã thêm ' . count($records) . ' thành viên dân quân tự vệ thành công.';
        return redirect()
            ->route('dan-quan-tu-ve.index')
            ->with('status', $msg);
    }

    public function show(DanQuanTuVe $danQuanTuVe, Request $request)
    {
        $danQuanTuVe->load([
            'nhanKhau',
            'hoatDong' => fn ($query) => $query->latest('ngay_thuc_hien')->latest('id'),
        ]);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy chi tiết thành viên dân quân tự vệ thành công.',
                'data' => $danQuanTuVe,
            ], 200);
        }

        return view('nghia-vu-an-ninh.dan-quan-tu-ve.show', $this->formData($danQuanTuVe));
    }

    public function edit(DanQuanTuVe $danQuanTuVe, Request $request)
    {
        $formData = $this->formData($danQuanTuVe);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy dữ liệu form chỉnh sửa thành công.',
                'data' => $formData,
            ], 200);
        }

        return view('nghia-vu-an-ninh.dan-quan-tu-ve.edit', $formData);
    }

    public function update(UpdateDanQuanTuVeRequest $request, DanQuanTuVe $danQuanTuVe)
    {
        $this->danQuanTuVeService->updateMilitia($danQuanTuVe, $request->validated());

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin dân quân tự vệ thành công.',
                'data' => $danQuanTuVe,
            ], 200);
        }

        return redirect()
            ->route('dan-quan-tu-ve.index')
            ->with('status', 'Cập nhật thông tin dân quân tự vệ thành công.');
    }

    public function destroy(DanQuanTuVe $danQuanTuVe, Request $request)
    {
        $this->danQuanTuVeService->deleteMilitia($danQuanTuVe);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa thành viên dân quân tự vệ thành công.',
            ], 200);
        }

        return redirect()
            ->route('dan-quan-tu-ve.index')
            ->with('status', 'Xóa thành viên dân quân tự vệ thành công.');
    }

    private function formData(?DanQuanTuVe $record = null): array
    {
        return [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('nghia-vu-an-ninh'),
            'record' => $record,
            'nhanKhau' => NhanKhau::query()
                ->whereNull('deleted_at')
                ->where('trang_thai', '!=', 'da_mat')
                ->whereDoesntHave('nghiaVuQuanSu', function ($query) {
                    $query->whereIn('trang_thai_nvqs', ['trung_tuyen', 'da_nhap_ngu']);
                })
                ->where(function ($query) use ($record): void {
                    $query->whereDoesntHave('danQuanTuVe');
                    if ($record?->nhan_khau_id) {
                        $query->orWhere('id', $record->nhan_khau_id);
                    }
                })
                ->orderBy('ho_ten')
                ->get(['id', 'ho_ten', 'cccd_cmnd', 'ngay_sinh']),
            'trangThai' => self::TRANG_THAI,
            'loaiHoatDong' => DanQuanHoatDongController::LOAI_HOAT_DONG,
            'trangThaiHoatDong' => DanQuanHoatDongController::TRANG_THAI,
        ];
    }

    private function stats(): array
    {
        return [
            'tong_so' => DanQuanTuVe::count(),
            'dang_phuc_vu' => DanQuanTuVe::where('trang_thai', 'dang_phuc_vu')->count(),
            'da_hoan_thanh' => DanQuanTuVe::where('trang_thai', 'da_hoan_thanh')->count(),
            'da_roi' => DanQuanTuVe::where('trang_thai', 'da_roi')->count(),
        ];
    }
}
