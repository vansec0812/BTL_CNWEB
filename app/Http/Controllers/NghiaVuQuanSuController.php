<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNghiaVuQuanSuRequest;
use App\Http\Requests\UpdateNghiaVuQuanSuRequest;
use App\Models\NghiaVuQuanSu;
use App\Services\NghiaVuQuanSuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NghiaVuQuanSuController extends Controller
{
    public function __construct(
        private NghiaVuQuanSuService $nghiaVuQuanSuService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'trang_thai_nvqs', 'nam_tuoi_tuyen_quan', 'thon_xom']);
        $perPage = $request->integer('per_page', 15);

        $list = $this->nghiaVuQuanSuService->getNghiaVuList($filters, $perPage);

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách nghĩa vụ quân sự thành công.',
            'data' => $list,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNghiaVuQuanSuRequest $request): JsonResponse
    {
        $record = $this->nghiaVuQuanSuService->createNghiaVuRecord($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Đã tạo hồ sơ nghĩa vụ quân sự thành công.',
            'data' => $record,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(NghiaVuQuanSu $nghiaVuQuanSu): JsonResponse
    {
        $nghiaVuQuanSu->load(['nhanKhau.hoKhau']);

        return response()->json([
            'success' => true,
            'message' => 'Lấy chi tiết hồ sơ nghĩa vụ quân sự thành công.',
            'data' => $nghiaVuQuanSu,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNghiaVuQuanSuRequest $request, NghiaVuQuanSu $nghiaVuQuanSu): JsonResponse
    {
        $updatedRecord = $this->nghiaVuQuanSuService->updateNghiaVuRecord($nghiaVuQuanSu, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật hồ sơ nghĩa vụ quân sự thành công.',
            'data' => $updatedRecord,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NghiaVuQuanSu $nghiaVuQuanSu): JsonResponse
    {
        $this->nghiaVuQuanSuService->deleteNghiaVuRecord($nghiaVuQuanSu);

        return response()->json([
            'success' => true,
            'message' => 'Xóa hồ sơ nghĩa vụ quân sự thành công.',
        ], 200);
    }

    /**
     * Automatically scan and sync eligible citizens into the military service table.
     */
    public function scan(Request $request): JsonResponse
    {
        $targetYear = $request->integer('nam_tuyen_quan', (int) date('Y'));
        
        $result = $this->nghiaVuQuanSuService->scanEligibleCitizens($targetYear);

        return response()->json([
            'success' => true,
            'message' => 'Tự động quét danh sách đủ tuổi nghĩa vụ quân sự hoàn tất.',
            'data' => $result,
        ], 200);
    }

    /**
     * Lấy danh sách công dân nam chưa có hồ sơ nghĩa vụ quân sự phục vụ tạo thủ công.
     */
    public function eligibleCitizens(Request $request): JsonResponse
    {
        $search = $request->input('search');
        
        $query = \App\Models\NhanKhau::query()
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
}
