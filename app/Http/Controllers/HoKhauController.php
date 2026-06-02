<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHoKhauRequest;
use App\Http\Requests\UpdateHoKhauRequest;
use App\Models\HoKhau;
use App\Services\HoKhauService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HoKhauController extends Controller
{
    public function __construct(
        private HoKhauService $hoKhauService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'thon_xom', 'phan_loai', 'trang_thai']);
        $perPage = $request->integer('per_page', 15);

        $hoKhau = $this->hoKhauService->getHoKhauList($filters, $perPage);

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách hộ khẩu thành công.',
            'data' => $hoKhau,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHoKhauRequest $request): JsonResponse
    {
        $hoKhau = $this->hoKhauService->createHoKhau($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Đã tạo hộ khẩu mới thành công.',
            'data' => $hoKhau,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(HoKhau $hoKhau): JsonResponse
    {
        // Eager load relationships
        $hoKhau->load(['chuHo', 'thanhVien']);

        return response()->json([
            'success' => true,
            'message' => 'Lấy thông tin chi tiết hộ khẩu thành công.',
            'data' => $hoKhau,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHoKhauRequest $request, HoKhau $hoKhau): JsonResponse
    {
        $updatedHoKhau = $this->hoKhauService->updateHoKhau($hoKhau, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật hộ khẩu thành công.',
            'data' => $updatedHoKhau,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HoKhau $hoKhau): JsonResponse
    {
        $this->hoKhauService->deleteHoKhau($hoKhau);

        return response()->json([
            'success' => true,
            'message' => 'Xóa hộ khẩu thành công.',
        ], 200);
    }
}
