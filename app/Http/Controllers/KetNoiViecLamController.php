<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKetNoiViecLamRequest;
use App\Http\Requests\UpdateKetNoiViecLamRequest;
use App\Models\KetNoiViecLam;
use App\Models\LaoDong;
use App\Models\DoanhNghiep;
use App\Services\KetNoiViecLamService;
use App\Support\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class KetNoiViecLamController extends Controller
{
    public function __construct(
        private KetNoiViecLamService $ketNoiViecLamService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'ket_qua']);
        $perPage = $request->integer('per_page', 10);

        $records = $this->ketNoiViecLamService->getKetNoiList($filters, $perPage);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách kết nối việc làm thành công.',
                'data' => $records,
                'stats' => $this->stats(),
            ], 200);
        }

        return view('lao-dong.ket-noi.index', [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('kinh-te-lao-dong'),
            'records' => $records,
            'filters' => $filters,
            'ketQua' => KetNoiViecLam::KET_QUA,
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
                'message' => 'Lấy dữ liệu form kết nối thành công.',
                'data' => $formData,
            ], 200);
        }

        return view('lao-dong.ket-noi.create', $formData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKetNoiViecLamRequest $request)
    {
        $record = $this->ketNoiViecLamService->createKetNoi($request->validated());

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Tạo kết nối việc làm thành công.',
                'data' => $record,
            ], 201);
        }

        return redirect()
            ->route('ket-noi.index')
            ->with('status', 'Đã tạo kết nối việc làm thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KetNoiViecLam $ketNoi, Request $request)
    {
        $ketNoi->load(['laoDong.nhanKhau', 'doanhNghiep', 'nguoiPhuTrach']);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy chi tiết kết nối việc làm thành công.',
                'data' => $ketNoi,
            ], 200);
        }

        return view('lao-dong.ket-noi.show', $this->formData($ketNoi));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KetNoiViecLam $ketNoi, Request $request)
    {
        $ketNoi->load(['laoDong.nhanKhau', 'doanhNghiep']);
        $formData = $this->formData($ketNoi);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy dữ liệu chỉnh sửa kết nối thành công.',
                'data' => $formData,
            ], 200);
        }

        return view('lao-dong.ket-noi.edit', $formData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKetNoiViecLamRequest $request, KetNoiViecLam $ketNoi)
    {
        $record = $this->ketNoiViecLamService->updateKetNoi($ketNoi, $request->validated());

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật kết nối việc làm thành công.',
                'data' => $record,
            ], 200);
        }

        return redirect()
            ->route('ket-noi.index')
            ->with('status', 'Đã cập nhật kết nối việc làm thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KetNoiViecLam $ketNoi, Request $request)
    {
        $this->ketNoiViecLamService->deleteKetNoi($ketNoi);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa kết nối việc làm thành công.',
            ], 200);
        }

        return redirect()
            ->route('ket-noi.index')
            ->with('status', 'Đã xóa kết nối việc làm thành công.');
    }

    /**
     * AJAX auto-matching: doanh nghiệp phù hợp cho lao động.
     */
    public function getJobsForLabor(LaoDong $laoDong): JsonResponse
    {
        $jobs = $this->ketNoiViecLamService->matchEligibleJobsForLabor($laoDong);
        return response()->json([
            'success' => true,
            'data' => $jobs
        ]);
    }

    /**
     * AJAX auto-matching: lao động phù hợp cho doanh nghiệp.
     */
    public function getLaborsForJob(DoanhNghiep $doanhNghiep): JsonResponse
    {
        $labors = $this->ketNoiViecLamService->matchEligibleLaborsForJob($doanhNghiep);
        $data = $labors->map(function ($l) {
            return [
                'id' => $l->id,
                'ho_ten' => $l->nhanKhau->ho_ten,
                'cccd_cmnd' => $l->nhanKhau->cccd_cmnd ?? 'Chưa cập nhật',
                'nganh_nghe' => $l->nganhNgheLabel(),
                'nghe_nghiep' => $l->nghe_nghiep ?? '—'
            ];
        });
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    private function formData(?KetNoiViecLam $record = null): array
    {
        // Lao động thất nghiệp
        $laoDongQuery = LaoDong::query()
            ->with('nhanKhau')
            ->where('trang_thai_lao_dong', 'that_nghiep');

        if ($record) {
            $laoDongQuery->orWhere('id', $record->lao_dong_id);
        }
        $laoDong = $laoDongQuery->get()->map(function ($l) {
            return [
                'id' => $l->id,
                'ho_ten' => $l->nhanKhau->ho_ten,
                'cccd_cmnd' => $l->nhanKhau->cccd_cmnd ?? '—',
                'nganh_nghe' => $l->nganhNgheLabel()
            ];
        });

        // Doanh nghiệp đang hoạt động
        $doanhNghiepQuery = DoanhNghiep::query()->where('trang_thai', 'dang_hoat_dong');
        if ($record) {
            $doanhNghiepQuery->orWhere('id', $record->doanh_nghiep_id);
        }
        $doanhNghiep = $doanhNghiepQuery->get(['id', 'ten_co_so', 'nganh_nghe_chinh', 'so_vi_tri_tuyen_dung']);

        return [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('kinh-te-lao-dong'),
            'record' => $record,
            'laoDong' => $laoDong,
            'doanhNghiep' => $doanhNghiep,
            'ketQua' => KetNoiViecLam::KET_QUA,
        ];
    }

    private function stats(): array
    {
        return [
            'tong_ket_noi' => KetNoiViecLam::count(),
            'dang_cho' => KetNoiViecLam::where('ket_qua', 'dang_cho_phan_hoi')->count(),
            'thanh_cong' => KetNoiViecLam::where('ket_qua', 'duoc_nhan')->count(),
        ];
    }
}
