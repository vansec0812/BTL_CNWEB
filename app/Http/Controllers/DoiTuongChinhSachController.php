<?php

namespace App\Http\Controllers;

use App\Models\DoiTuongChinhSach;
use App\Models\NhanKhau;
use App\Support\ModuleRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Http\Requests\StoreDoiTuongChinhSachRequest;
use App\Http\Requests\UpdateDoiTuongChinhSachRequest;

class DoiTuongChinhSachController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'loai_chinh_sach', 'trang_thai']);

        $query = DoiTuongChinhSach::query()
            ->with('nhanKhau')
            ->when($filters['q'] ?? null, function ($query, string $keyword): void {
                $query->where(function ($query) use ($keyword): void {
                    $query->where('so_quyet_dinh_cong_nhan', 'like', "%{$keyword}%")
                        ->orWhereHas('nhanKhau', function ($query) use ($keyword): void {
                            $query->where('ho_ten', 'like', "%{$keyword}%")
                                ->orWhere('cccd_cmnd', 'like', "%{$keyword}%");
                        });
                });
            })
            ->when($filters['loai_chinh_sach'] ?? null, fn ($query, string $value) => $query->where('loai_chinh_sach', $value))
            ->when($filters['trang_thai'] ?? null, fn ($query, string $value) => $query->where('trang_thai', $value))
            ->latest();

        $doiTuongChinhSach = $query->paginate(10)->withQueryString();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách đối tượng chính sách thành công.',
                'data' => $doiTuongChinhSach,
                'stats' => $this->stats(),
            ], 200);
        }

        return view('an-sinh.doi-tuong-chinh-sach.index', [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('an-sinh-y-te-giao-duc'),
            'records' => $doiTuongChinhSach,
            'filters' => $filters,
            'loaiChinhSach' => DoiTuongChinhSach::LOAI_CHINH_SACH,
            'trangThai' => DoiTuongChinhSach::TRANG_THAI,
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

        return view('an-sinh.doi-tuong-chinh-sach.create', $formData);
    }

    public function store(StoreDoiTuongChinhSachRequest $request)
    {
        $record = DoiTuongChinhSach::create($request->validated());

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm hồ sơ diện chính sách.',
                'data' => $record,
            ], 201);
        }

        return redirect()
            ->route('doi-tuong-chinh-sach.index')
            ->with('status', 'Đã thêm hồ sơ diện chính sách.');
    }

    public function show(DoiTuongChinhSach $doiTuongChinhSach, Request $request)
    {
        $doiTuongChinhSach->load('nhanKhau');

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy chi tiết hồ sơ diện chính sách thành công.',
                'data' => $doiTuongChinhSach,
            ], 200);
        }

        return view('an-sinh.doi-tuong-chinh-sach.show', $this->formData($doiTuongChinhSach));
    }

    public function edit(DoiTuongChinhSach $doiTuongChinhSach, Request $request)
    {
        $formData = $this->formData($doiTuongChinhSach);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy dữ liệu form chỉnh sửa thành công.',
                'data' => $formData,
            ], 200);
        }

        return view('an-sinh.doi-tuong-chinh-sach.edit', $formData);
    }

    public function update(UpdateDoiTuongChinhSachRequest $request, DoiTuongChinhSach $doiTuongChinhSach)
    {
        $doiTuongChinhSach->update($request->validated());

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật hồ sơ diện chính sách.',
                'data' => $doiTuongChinhSach,
            ], 200);
        }

        return redirect()
            ->route('doi-tuong-chinh-sach.index')
            ->with('status', 'Đã cập nhật hồ sơ diện chính sách.');
    }

    public function destroy(DoiTuongChinhSach $doiTuongChinhSach, Request $request)
    {
        $doiTuongChinhSach->delete();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã lưu hồ sơ vào trạng thái đã xoá.',
            ], 200);
        }

        return redirect()
            ->route('doi-tuong-chinh-sach.index')
            ->with('status', 'Đã lưu hồ sơ vào trạng thái đã xoá.');
    }

    private function formData(?DoiTuongChinhSach $record = null): array
    {
        return [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('an-sinh-y-te-giao-duc'),
            'record' => $record,
            'nhanKhau' => NhanKhau::query()
                ->withTrashed()
                ->where(function ($query) use ($record): void {
                    $query->where(function ($query): void {
                        $query->whereNull('deleted_at')
                            ->where('trang_thai', '!=', 'da_mat');
                    });

                    if ($record?->nhan_khau_id) {
                        $query->orWhere('id', $record->nhan_khau_id);
                    }
                })
                ->orderBy('ho_ten')
                ->get(['id', 'ho_ten', 'cccd_cmnd', 'ngay_sinh']),
            'loaiChinhSach' => DoiTuongChinhSach::LOAI_CHINH_SACH,
            'trangThai' => DoiTuongChinhSach::TRANG_THAI,
        ];
    }

    private function stats(): array
    {
        return [
            'tong_so' => DoiTuongChinhSach::count(),
            'dang_huong' => DoiTuongChinhSach::where('trang_thai', 'dang_huong_che_do')->count(),
            'can_ra_soat' => DoiTuongChinhSach::whereNull('so_quyet_dinh_cong_nhan')->count(),
            'tong_tro_cap' => DoiTuongChinhSach::where('trang_thai', 'dang_huong_che_do')->sum('muc_tro_cap_hang_thang'),
        ];
    }
}
