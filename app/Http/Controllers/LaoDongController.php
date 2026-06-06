<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLaoDongRequest;
use App\Http\Requests\UpdateLaoDongRequest;
use App\Models\LaoDong;
use App\Models\NhanKhau;
use App\Services\LaoDongService;
use App\Support\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class LaoDongController extends Controller
{
    public function __construct(
        private LaoDongService $laoDongService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only([
            'search', 
            'trang_thai_lao_dong', 
            'nganh_nghe', 
            'loai_hinh_cong_viec', 
            'xuat_khau_lao_dong', 
            'lam_viec_ngoai_tinh',
            'thon_xom'
        ]);
        $perPage = $request->integer('per_page', 10);

        $records = $this->laoDongService->getLaoDongList($filters, $perPage);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách lao động thành công.',
                'data' => $records,
                'stats' => $this->stats(),
            ], 200);
        }

        return view('lao-dong.ho-so.index', [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('kinh-te-lao-dong'),
            'records' => $records,
            'filters' => $filters,
            'trangThaiLaoDong' => LaoDong::TRANG_THAI_LAO_DONG,
            'loaiHinhCongViec' => LaoDong::LOAI_HINH_CONG_VIEC,
            'nganhNghe' => LaoDong::NGANH_NGHE,
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
                'message' => 'Lấy dữ liệu tạo mới hồ sơ lao động thành công.',
                'data' => $formData,
            ], 200);
        }

        return view('lao-dong.ho-so.create', $formData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLaoDongRequest $request)
    {
        $record = $this->laoDongService->createLaoDongRecord($request->validated());

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Tạo hồ sơ lao động thành công.',
                'data' => $record,
            ], 201);
        }

        return redirect()
            ->route('ho-so.index')
            ->with('status', 'Đã tạo hồ sơ lao động thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LaoDong $hoSo, Request $request)
    {
        $hoSo->load(['nhanKhau.hoKhau', 'lichSuCongViec.nguoiCapNhat']);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy chi tiết hồ sơ lao động thành công.',
                'data' => $hoSo,
            ], 200);
        }

        return view('lao-dong.ho-so.show', $this->formData($hoSo));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LaoDong $hoSo, Request $request)
    {
        $hoSo->load(['nhanKhau.hoKhau']);
        $formData = $this->formData($hoSo);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy dữ liệu chỉnh sửa hồ sơ lao động thành công.',
                'data' => $formData,
            ], 200);
        }

        return view('lao-dong.ho-so.edit', $formData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLaoDongRequest $request, LaoDong $hoSo)
    {
        $record = $this->laoDongService->updateLaoDongRecord($hoSo, $request->validated());

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật hồ sơ lao động thành công.',
                'data' => $record,
            ], 200);
        }

        return redirect()
            ->route('ho-so.index')
            ->with('status', 'Đập nhật hồ sơ lao động thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LaoDong $hoSo, Request $request)
    {
        $this->laoDongService->deleteLaoDongRecord($hoSo);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa hồ sơ lao động thành công.',
            ], 200);
        }

        return redirect()
            ->route('ho-so.index')
            ->with('status', 'Đã xóa hồ sơ lao động.');
    }

    private function formData(?LaoDong $record = null): array
    {
        $nhanKhauQuery = NhanKhau::query()
            ->whereIn('trang_thai', ['hoat_dong', 'tam_tru', 'tam_vang']);

        // Chỉ hiển thị người chưa có hồ sơ lao động
        if (!$record) {
            $nhanKhauQuery->whereDoesntHave('laoDong');
        } else {
            $nhanKhauQuery->where(function ($q) use ($record) {
                $q->whereDoesntHave('laoDong')
                  ->orWhere('id', $record->nhan_khau_id);
            });
        }

        $nhanKhau = $nhanKhauQuery->orderBy('ho_ten')->get(['id', 'ho_ten', 'cccd_cmnd', 'ngay_sinh']);

        return [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('kinh-te-lao-dong'),
            'record' => $record,
            'nhanKhau' => $nhanKhau,
            'trangThaiLaoDong' => LaoDong::TRANG_THAI_LAO_DONG,
            'loaiHinhCongViec' => LaoDong::LOAI_HINH_CONG_VIEC,
            'nganhNghe' => LaoDong::NGANH_NGHE,
        ];
    }

    private function stats(): array
    {
        return [
            'tong_lao_dong' => LaoDong::count(),
            'co_viec_lam' => LaoDong::where('trang_thai_lao_dong', 'co_viec_lam')->count(),
            'that_nghiep' => LaoDong::where('trang_thai_lao_dong', 'that_nghiep')->count(),
            'xkld' => LaoDong::where('xuat_khau_lao_dong', true)->count(),
        ];
    }
}
