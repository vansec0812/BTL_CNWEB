<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBienDongHoKhauRequest;
use App\Models\BienDongHoKhau;
use App\Models\HoKhau;
use App\Models\NhanKhau;
use App\Services\BienDongHoKhauService;
use App\Support\ModuleRegistry;
use Illuminate\Http\Request;

class BienDongHoKhauController extends Controller
{
    public function __construct(
        private BienDongHoKhauService $bienDongService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'loai_bien_dong', 'ngay_bat_dau', 'ngay_ket_thuc']);
        $perPage = $request->integer('per_page', 10);

        $records = $this->bienDongService->getBienDongList($filters, $perPage);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách biến động hộ khẩu thành công.',
                'data' => $records,
                'stats' => $this->stats(),
            ], 200);
        }

        return view('ho-tich.bien-dong.index', [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('ho-tich-cu-tru'),
            'records' => $records,
            'filters' => $filters,
            'loaiBienDong' => BienDongHoKhau::LOAI_BIEN_DONG,
            'stats' => $this->stats(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->input('type');
        if (! in_array($type, ['tach_ho', 'nhap_ho', 'chuyen_di', 'chuyen_den'])) {
            abort(404, 'Loại biến động không hợp lệ');
        }

        $sourceHoKhauId = $request->input('source_ho_khau_id');
        $sourceHoKhau = $sourceHoKhauId ? HoKhau::with('chuHo')->find($sourceHoKhauId) : null;
        $members = $sourceHoKhau ? NhanKhau::where('ho_khau_id', $sourceHoKhau->id)->get() : [];

        $formData = [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('ho-tich-cu-tru'),
            'hoKhauList' => HoKhau::with('chuHo')->get(),
            'nhanKhauList' => NhanKhau::with('hoKhau')->whereNull('deleted_at')->where('trang_thai', '!=', 'da_mat')->get(),
            'sourceHoKhau' => $sourceHoKhau,
            'members' => $members,
            'phanLoai' => HoKhau::PHAN_LOAI,
            'gioiTinh' => NhanKhau::GIOI_TINH,
            'trinhDoHocVan' => NhanKhau::TRINH_DO_HOC_VAN,
            'tinhTrangHonNhan' => NhanKhau::TINH_TRANG_HON_NHAN,
        ];

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy dữ liệu form tạo biến động thành công.',
                'data' => $formData,
            ], 200);
        }

        return view("ho-tich.bien-dong.create_{$type}", $formData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBienDongHoKhauRequest $request)
    {
        $type = $request->input('loai_bien_dong');

        if ($type === 'tach_ho') {
            $this->bienDongService->tachHo($request->validated());
            $msg = 'Đã thực hiện tách hộ khẩu thành công.';
        } elseif ($type === 'nhap_ho') {
            $this->bienDongService->nhapHo($request->validated());
            $msg = 'Đã thực hiện nhập hộ khẩu thành công.';
        } elseif ($type === 'chuyen_di') {
            $this->bienDongService->chuyenDi($request->validated());
            $msg = 'Đã thực hiện chuyển đi thành công.';
        } elseif ($type === 'chuyen_den') {
            $this->bienDongService->chuyenDen($request->validated());
            $msg = 'Đã đăng ký chuyển đến thành công.';
        } else {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Loại biến động không hợp lệ.',
                ], 400);
            }

            return redirect()->back()->withErrors(['loai_bien_dong' => 'Loại biến động không hợp lệ.']);
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => $msg,
            ], 201);
        }

        return redirect()
            ->route('bien-dong.index')
            ->with('status', $msg);
    }

    /**
     * Display the specified resource.
     */
    public function show(BienDongHoKhau $bienDong, Request $request)
    {
        $bienDong->load(['hoKhauNguon', 'hoKhauDich', 'nhanKhau', 'nguoiThucHien']);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy chi tiết biến động hộ khẩu thành công.',
                'data' => $bienDong,
            ], 200);
        }

        return view('ho-tich.bien-dong.show', [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('ho-tich-cu-tru'),
            'record' => $bienDong,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BienDongHoKhau $bienDong, Request $request)
    {
        return view('ho-tich.bien-dong.edit', [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('ho-tich-cu-tru'),
            'record' => $bienDong,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BienDongHoKhau $bienDong)
    {
        $validated = $request->validate([
            'ngay_bien_dong' => 'required|date',
            'so_quyet_dinh' => 'nullable|string|max:100',
            'ly_do' => 'nullable|string|max:500',
            'dia_chi_chuyen_den' => 'nullable|string|max:500',
            'ghi_chu' => 'nullable|string',
        ]);

        $bienDong->update($validated);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật lịch sử biến động thành công.',
                'data' => $bienDong,
            ], 200);
        }

        return redirect()
            ->route('bien-dong.index')
            ->with('status', 'Cập nhật lịch sử biến động thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BienDongHoKhau $bienDong, Request $request)
    {
        $bienDong->delete();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa lịch sử biến động thành công.',
            ], 200);
        }

        return redirect()
            ->route('bien-dong.index')
            ->with('status', 'Xóa lịch sử biến động thành công.');
    }

    /**
     * Calculate stats.
     */
    private function stats(): array
    {
        return [
            'tong_so' => BienDongHoKhau::count(),
            'tach_ho' => BienDongHoKhau::where('loai_bien_dong', 'tach_ho')->count(),
            'nhap_ho' => BienDongHoKhau::where('loai_bien_dong', 'nhap_ho')->count(),
            'chuyen_di' => BienDongHoKhau::where('loai_bien_dong', 'chuyen_di')->count(),
            'chuyen_den' => BienDongHoKhau::where('loai_bien_dong', 'chuyen_den')->count(),
        ];
    }
}
