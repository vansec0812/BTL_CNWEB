<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNghiaVuQuanSuRequest;
use App\Http\Requests\UpdateNghiaVuQuanSuRequest;
use App\Models\NghiaVuQuanSu;
use App\Models\NhanKhau;
use App\Services\NghiaVuQuanSuService;
use App\Support\ModuleRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NghiaVuQuanSuController extends Controller
{
    // Constants for translations
    public const TRANG_THAI_NVQS = [
        'chua_den_tuoi' => 'Chưa đến tuổi',
        'du_dieu_kien' => 'Đủ điều kiện',
        'tam_hoan' => 'Tạm hoãn',
        'mien_goi' => 'Miễn gọi',
        'trung_tuyen' => 'Trúng tuyển',
        'da_nhap_ngu' => 'Đã nhập ngũ',
        'xuat_ngu' => 'Xuất ngũ',
        'da_qua_tuoi' => 'Đã quá tuổi',
    ];

    public const LY_DO_TAM_HOAN = [
        'khong_ap_dung' => 'Không áp dụng',
        'di_hoc_dai_hoc' => 'Học Đại học/Sau Đại học',
        'benh_tat_suc_khoe' => 'Bệnh tật/Sức khoẻ không đạt',
        'con_mot_con' => 'Con một/Hoàn cảnh đặc biệt',
        'nuoi_duong_than_nhan' => 'Nuôi dưỡng thân nhân',
        'ly_do_khac' => 'Lý do khác',
    ];

    public const KET_QUA_KHAM = [
        'chua_kham' => 'Chưa khám',
        'loai_1' => 'Loại 1 (Rất tốt)',
        'loai_2' => 'Loại 2 (Tốt)',
        'loai_3' => 'Loại 3 (Khá)',
        'loai_4' => 'Loại 4 (Trung bình)',
        'loai_5' => 'Loại 5 (Yếu)',
        'khong_du_suc_khoe' => 'Không đủ sức khoẻ',
    ];

    public function __construct(
        private NghiaVuQuanSuService $nghiaVuQuanSuService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'trang_thai_nvqs', 'nam_tuoi_tuyen_quan', 'thon_xom']);
        $perPage = $request->integer('per_page', 10);

        $records = $this->nghiaVuQuanSuService->getNghiaVuList($filters, $perPage);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách nghĩa vụ quân sự thành công.',
                'data' => $records,
                'stats' => $this->stats(),
            ], 200);
        }

        return view('nghia-vu-an-ninh.nghia-vu-quan-su.index', [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('nghia-vu-an-ninh'),
            'records' => $records,
            'filters' => $filters,
            'trangThaiNVQS' => self::TRANG_THAI_NVQS,
            'lyDoTamHoan' => self::LY_DO_TAM_HOAN,
            'ketQuaKham' => self::KET_QUA_KHAM,
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

        return view('nghia-vu-an-ninh.nghia-vu-quan-su.create', $formData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNghiaVuQuanSuRequest $request)
    {
        $record = $this->nghiaVuQuanSuService->createNghiaVuRecord($request->validated());

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã tạo hồ sơ nghĩa vụ quân sự thành công.',
                'data' => $record,
            ], 201);
        }

        return redirect()
            ->route('nghia-vu-quan-su.index')
            ->with('status', 'Đã tạo hồ sơ nghĩa vụ quân sự thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(NghiaVuQuanSu $nghiaVuQuanSu, Request $request)
    {
        $nghiaVuQuanSu->load(['nhanKhau.hoKhau']);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy chi tiết hồ sơ nghĩa vụ quân sự thành công.',
                'data' => $nghiaVuQuanSu,
            ], 200);
        }

        return view('nghia-vu-an-ninh.nghia-vu-quan-su.show', $this->formData($nghiaVuQuanSu));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NghiaVuQuanSu $nghiaVuQuanSu, Request $request)
    {
        $nghiaVuQuanSu->load(['nhanKhau.hoKhau']);
        $formData = $this->formData($nghiaVuQuanSu);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy dữ liệu form chỉnh sửa thành công.',
                'data' => $formData,
            ], 200);
        }

        return view('nghia-vu-an-ninh.nghia-vu-quan-su.edit', $formData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNghiaVuQuanSuRequest $request, NghiaVuQuanSu $nghiaVuQuanSu)
    {
        $record = $this->nghiaVuQuanSuService->updateNghiaVuRecord($nghiaVuQuanSu, $request->validated());

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật hồ sơ nghĩa vụ quân sự thành công.',
                'data' => $record,
            ], 200);
        }

        return redirect()
            ->route('nghia-vu-quan-su.index')
            ->with('status', 'Cập nhật hồ sơ nghĩa vụ quân sự thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NghiaVuQuanSu $nghiaVuQuanSu, Request $request)
    {
        $this->nghiaVuQuanSuService->deleteNghiaVuRecord($nghiaVuQuanSu);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa hồ sơ nghĩa vụ quân sự thành công.',
            ], 200);
        }

        return redirect()
            ->route('nghia-vu-quan-su.index')
            ->with('status', 'Xóa hồ sơ nghĩa vụ quân sự thành công.');
    }

    /**
     * Preview list of eligible citizens before adding.
     */
    public function scanPreview(Request $request): JsonResponse
    {
        $targetYear = $request->integer('nam_tuyen_quan', (int) date('Y'));
        $citizens = $this->nghiaVuQuanSuService->getEligibleScannedCitizens($targetYear);

        $data = $citizens->map(function ($citizen) {
            return [
                'id' => $citizen->id,
                'ho_ten' => $citizen->ho_ten,
                'cccd_cmnd' => $citizen->cccd_cmnd ?? 'Chưa cập nhật',
                'nam_sinh' => $citizen->ngay_sinh ? $citizen->ngay_sinh->format('Y') : '—',
                'trinh_do_hoc_van' => $citizen->trinh_do_hoc_van === 'dai_hoc' ? 'Đại học' : ($citizen->trinh_do_hoc_van === 'sau_dai_hoc' ? 'Sau Đại học' : 'Phổ thông/Khác'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Bulk store the selected scanned citizens into the military service table.
     */
    public function scanStore(Request $request): RedirectResponse
    {
        $targetYear = $request->integer('nam_tuyen_quan', (int) date('Y'));
        $nhanKhauIds = $request->input('nhan_khau_ids', []);

        if (empty($nhanKhauIds)) {
            return redirect()
                ->route('nghia-vu-quan-su.index')
                ->with('status', 'Không có công dân nào được chọn để thêm mới.');
        }

        $addedCount = $this->nghiaVuQuanSuService->storeScannedCitizens($targetYear, $nhanKhauIds);

        return redirect()
            ->route('nghia-vu-quan-su.index')
            ->with('status', "Tự động quét hoàn tất. Đã thêm mới {$addedCount} công dân được chọn vào danh sách NVQS.");
    }

    /**
     * Lấy danh sách công dân nam chưa có hồ sơ nghĩa vụ quân sự phục vụ tạo thủ công (AJAX).
     */
    public function eligibleCitizens(Request $request): JsonResponse
    {
        $search = $request->input('search');
        
        $query = NhanKhau::query()
            ->where('gioi_tinh', 'nam')
            ->whereIn('trang_thai', ['hoat_dong', 'tam_tru', 'tam_vang'])
            ->whereDoesntHave('nghiaVuQuanSu');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('ho_ten', 'like', '%' . $search . '%')
                  ->orWhere('cccd_cmnd', 'like', '%' . $search . '%');
            });
        }

        $citizens = $query->limit(15)->get(['id', 'ho_ten', 'cccd_cmnd', 'ngay_sinh', 'trinh_do_hoc_van']);

        return response()->json([
            'success' => true,
            'data' => $citizens,
        ], 200);
    }

    private function formData(?NghiaVuQuanSu $record = null): array
    {
        $nhanKhauQuery = NhanKhau::query()
            ->where('gioi_tinh', 'nam')
            ->whereIn('trang_thai', ['hoat_dong', 'tam_tru', 'tam_vang']);

        if (!$record) {
            $nhanKhauQuery->whereDoesntHave('nghiaVuQuanSu');
        } else {
            $nhanKhauQuery->where(function ($q) use ($record) {
                $q->whereDoesntHave('nghiaVuQuanSu')
                  ->orWhere('id', $record->nhan_khau_id);
            });
        }

        $nhanKhau = $nhanKhauQuery->orderBy('ho_ten')->get(['id', 'ho_ten', 'cccd_cmnd', 'ngay_sinh']);

        return [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('nghia-vu-an-ninh'),
            'record' => $record,
            'nhanKhau' => $nhanKhau,
            'trangThaiNVQS' => self::TRANG_THAI_NVQS,
            'lyDoTamHoan' => self::LY_DO_TAM_HOAN,
            'ketQuaKham' => self::KET_QUA_KHAM,
        ];
    }

    private function stats(): array
    {
        return [
            'nghia_vu_quan_su' => NghiaVuQuanSu::count(),
            'du_dieu_kien' => NghiaVuQuanSu::where('trang_thai_nvqs', 'du_dieu_kien')->count(),
            'tam_hoan' => NghiaVuQuanSu::where('trang_thai_nvqs', 'tam_hoan')->count(),
            'da_nhap_ngu' => NghiaVuQuanSu::where('trang_thai_nvqs', 'da_nhap_ngu')->count(),
        ];
    }
}
