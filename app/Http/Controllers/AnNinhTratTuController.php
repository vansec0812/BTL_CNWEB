<?php

namespace App\Http\Controllers;

use App\Models\AnNinhTratTu;
use App\Models\NhanKhau;
use App\Support\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnNinhTratTuController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'loai_doi_tuong', 'trang_thai']);

        $query = AnNinhTratTu::query()
            ->with(['nhanKhau.hoKhau'])
            ->when($filters['q'] ?? null, function ($query, string $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('ho_ten', 'like', "%{$keyword}%")
                        ->orWhere('cccd', 'like', "%{$keyword}%")
                        ->orWhere('dia_chi', 'like', "%{$keyword}%")
                        ->orWhere('co_quan_giai_quyet', 'like', "%{$keyword}%");
                });
            })
            ->when($filters['loai_doi_tuong'] ?? null, fn ($q, $val) => $q->where('loai_doi_tuong', $val))
            ->when($filters['trang_thai'] ?? null, fn ($q, $val) => $q->where('trang_thai', $val))
            ->orderBy('id', 'asc');

        $records = $query->paginate(10)->withQueryString();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách an ninh trật tự thành công.',
                'data' => $records,
                'stats' => $this->stats(),
            ], 200);
        }

        return view('nghia-vu-an-ninh.an-ninh-trat-tu.index', [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('nghia-vu-an-ninh'),
            'records' => $records,
            'filters' => $filters,
            'loaiDoiTuong' => AnNinhTratTu::LOAI_DOI_TUONG,
            'trangThai' => AnNinhTratTu::TRANG_THAI,
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

        return view('nghia-vu-an-ninh.an-ninh-trat-tu.create', $formData);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $record = AnNinhTratTu::create($data);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã tạo hồ sơ an ninh trật tự thành công.',
                'data' => $record->load('nhanKhau'),
            ], 201);
        }

        return redirect()
            ->route('an-ninh-trat-tu.index')
            ->with('status', 'Đã tạo hồ sơ an ninh trật tự thành công.');
    }

    public function show(AnNinhTratTu $anNinhTratTu, Request $request)
    {
        $anNinhTratTu->load(['nhanKhau.hoKhau']);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy chi tiết hồ sơ an ninh trật tự thành công.',
                'data' => $anNinhTratTu,
            ], 200);
        }

        return view('nghia-vu-an-ninh.an-ninh-trat-tu.show', $this->formData($anNinhTratTu));
    }

    public function edit(AnNinhTratTu $anNinhTratTu, Request $request)
    {
        $anNinhTratTu->load(['nhanKhau.hoKhau']);
        $formData = $this->formData($anNinhTratTu);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy dữ liệu form chỉnh sửa thành công.',
                'data' => $formData,
            ], 200);
        }

        return view('nghia-vu-an-ninh.an-ninh-trat-tu.edit', $formData);
    }

    public function update(Request $request, AnNinhTratTu $anNinhTratTu)
    {
        $data = $this->validated($request, $anNinhTratTu);

        $anNinhTratTu->update($data);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật hồ sơ an ninh trật tự thành công.',
                'data' => $anNinhTratTu->fresh()->load('nhanKhau'),
            ], 200);
        }

        return redirect()
            ->route('an-ninh-trat-tu.index')
            ->with('status', 'Cập nhật hồ sơ an ninh trật tự thành công.');
    }

    public function destroy(AnNinhTratTu $anNinhTratTu, Request $request)
    {
        $anNinhTratTu->delete();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa hồ sơ an ninh trật tự thành công.',
            ], 200);
        }

        return redirect()
            ->route('an-ninh-trat-tu.index')
            ->with('status', 'Xóa hồ sơ an ninh trật tự thành công.');
    }

    private function validated(Request $request, ?AnNinhTratTu $record = null): array
    {
        return $request->validate([
            'nhan_khau_id' => [
                'nullable',
                'integer',
                Rule::exists('nhan_khau', 'id')->whereNull('deleted_at'),
            ],
            'ho_ten' => [
                'required',
                'string',
                'max:255',
            ],
            'cccd' => [
                'nullable',
                'string',
                'max:20',
            ],
            'dia_chi' => [
                'nullable',
                'string',
                'max:255',
            ],
            'nhom_doi_tuong' => ['required', Rule::in(['vi_pham_hanh_chinh', 'quan_ly_dac_biet'])],
            'loai_doi_tuong' => ['required', 'string', 'max:100'],
            'co_quan_giai_quyet' => ['required', 'string', 'max:255'],
            'ngay_ghi_nhan' => ['required', 'date'],
            'noi_dung' => ['required', 'string'],
            'hinh_thuc_xu_ly' => ['nullable', 'string', 'max:255'],
            'so_tien_phat' => ['nullable', 'numeric', 'min:0'],
            'trang_thai' => ['required', Rule::in(array_keys(AnNinhTratTu::TRANG_THAI))],
        ]);
    }

    private function formData(?AnNinhTratTu $record = null): array
    {
        $nhanKhau = NhanKhau::query()
            ->with(['hoKhau'])
            ->whereNull('deleted_at')
            ->where('trang_thai', '!=', 'da_mat')
            ->orderBy('ho_ten')
            ->get(['id', 'ho_ten', 'cccd_cmnd', 'ngay_sinh', 'ho_khau_id']);

        return [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('nghia-vu-an-ninh'),
            'record' => $record,
            'nhanKhau' => $nhanKhau,
            'loaiDoiTuong' => AnNinhTratTu::LOAI_DOI_TUONG,
            'trangThai' => AnNinhTratTu::TRANG_THAI,
        ];
    }
    private function stats(): array
    {
        $tongSo = AnNinhTratTu::count();

        return [
            'tong_so' => $tongSo,
            'quan_ly_dac_biet' => AnNinhTratTu::where('nhom_doi_tuong', 'quan_ly_dac_biet')->count(),
            'vi_pham_hanh_chinh' => AnNinhTratTu::where('nhom_doi_tuong', 'vi_pham_hanh_chinh')->count(),
            'chua_chap_hanh' => AnNinhTratTu::where('trang_thai', 'chua_chap_hanh')->count(),
        ];
    }
}
