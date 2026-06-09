<?php

namespace App\Http\Controllers;

use App\Models\NhanKhau;
use App\Models\YTeNhanKhau;
use App\Support\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class YTeNhanKhauController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'loai_bhyt', 'tiem_chung', 'het_han']);

        $query = YTeNhanKhau::query()
            ->with('nhanKhau')
            ->when($filters['q'] ?? null, function ($query, string $keyword): void {
                $query->whereHas('nhanKhau', function ($query) use ($keyword): void {
                    $query->where('ho_ten', 'like', "%{$keyword}%")
                        ->orWhere('cccd_cmnd', 'like', "%{$keyword}%");
                })->orWhere('so_the_bhyt', 'like', "%{$keyword}%");
            })
            ->when($filters['loai_bhyt'] ?? null, fn ($query, string $value) => $query->where('loai_bhyt', $value))
            ->when(($filters['tiem_chung'] ?? null) === '1', fn ($query) => $query->where('hoan_thanh_tiem_chung_mo_rong', true))
            ->when(($filters['tiem_chung'] ?? null) === '0', fn ($query) => $query->where('hoan_thanh_tiem_chung_mo_rong', false))
            ->when(($filters['het_han'] ?? null) === '1', fn ($query) => $query->where('loai_bhyt', '!=', 'khong_co')->where('ngay_het_han_the_bhyt', '<', now()))
            ->latest();

        $records = $query->paginate(15)->withQueryString();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách hồ sơ y tế thành công.',
                'data'    => $records,
                'stats'   => $this->stats(),
            ], 200);
        }

        return view('an-sinh.y-te-nhan-khau.index', [
            'modules'      => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('an-sinh-y-te-giao-duc'),
            'records'      => $records,
            'filters'      => $filters,
            'loaiBhyt'     => YTeNhanKhau::LOAI_BHYT,
            'stats'        => $this->stats(),
        ]);
    }

    public function create(Request $request)
    {
        $formData = $this->formData();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy dữ liệu form tạo mới thành công.',
                'data'    => $formData,
            ], 200);
        }

        return view('an-sinh.y-te-nhan-khau.create', $formData);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['hoan_thanh_tiem_chung_mo_rong'] = $request->boolean('hoan_thanh_tiem_chung_mo_rong');

        // Nếu loại BHYT là 'khong_co' thì xóa thông tin thẻ
        if ($data['loai_bhyt'] === 'khong_co') {
            $data['so_the_bhyt']           = null;
            $data['ngay_cap_the_bhyt']     = null;
            $data['ngay_het_han_the_bhyt'] = null;
            $data['noi_dang_ky_kham_chua_benh'] = null;
        }

        $record = YTeNhanKhau::create($data);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã tạo hồ sơ y tế cho nhân khẩu.',
                'data'    => $record->load('nhanKhau'),
            ], 201);
        }

        return redirect()
            ->route('y-te-nhan-khau.index')
            ->with('status', 'Đã tạo hồ sơ y tế cho nhân khẩu.');
    }

    public function show(YTeNhanKhau $yTeNhanKhau, Request $request)
    {
        $yTeNhanKhau->load('nhanKhau');

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy chi tiết hồ sơ y tế thành công.',
                'data'    => $yTeNhanKhau,
            ], 200);
        }

        return view('an-sinh.y-te-nhan-khau.show', $this->formData($yTeNhanKhau));
    }

    public function edit(YTeNhanKhau $yTeNhanKhau, Request $request)
    {
        $formData = $this->formData($yTeNhanKhau);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy dữ liệu form chỉnh sửa thành công.',
                'data'    => $formData,
            ], 200);
        }

        return view('an-sinh.y-te-nhan-khau.edit', $formData);
    }

    public function update(Request $request, YTeNhanKhau $yTeNhanKhau)
    {
        $data = $this->validated($request, $yTeNhanKhau);
        $data['hoan_thanh_tiem_chung_mo_rong'] = $request->boolean('hoan_thanh_tiem_chung_mo_rong');

        if ($data['loai_bhyt'] === 'khong_co') {
            $data['so_the_bhyt']           = null;
            $data['ngay_cap_the_bhyt']     = null;
            $data['ngay_het_han_the_bhyt'] = null;
            $data['noi_dang_ky_kham_chua_benh'] = null;
        }

        $yTeNhanKhau->update($data);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật hồ sơ y tế.',
                'data'    => $yTeNhanKhau->fresh()->load('nhanKhau'),
            ], 200);
        }

        return redirect()
            ->route('y-te-nhan-khau.index')
            ->with('status', 'Đã cập nhật hồ sơ y tế.');
    }

    public function destroy(YTeNhanKhau $yTeNhanKhau, Request $request)
    {
        $yTeNhanKhau->delete();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xoá hồ sơ y tế.',
            ], 200);
        }

        return redirect()
            ->route('y-te-nhan-khau.index')
            ->with('status', 'Đã xoá hồ sơ y tế.');
    }

    private function validated(Request $request, ?YTeNhanKhau $record = null): array
    {
        $currentNhanKhauId = $record?->nhan_khau_id;

        return $request->validate([
            'nhan_khau_id' => [
                'required',
                'integer',
                Rule::exists('nhan_khau', 'id')->whereNull('deleted_at'),
                // Mỗi nhân khẩu chỉ có 1 hồ sơ y tế (unique trừ chính nó khi update)
                Rule::unique('y_te_nhan_khau', 'nhan_khau_id')->ignore($record?->id),
            ],
            'so_the_bhyt'                   => ['nullable', 'string', 'max:50'],
            'loai_bhyt'                     => ['required', Rule::in(array_keys(YTeNhanKhau::LOAI_BHYT))],
            'ngay_cap_the_bhyt'             => ['nullable', 'date'],
            'ngay_het_han_the_bhyt'         => ['nullable', 'date', 'after_or_equal:ngay_cap_the_bhyt'],
            'noi_dang_ky_kham_chua_benh'    => ['nullable', 'string', 'max:255'],
            'hoan_thanh_tiem_chung_mo_rong' => ['boolean'],
            'lich_su_tiem_chung'            => ['nullable', 'json'],
            'ghi_chu_suc_khoe'              => ['nullable', 'string'],
        ]);
    }

    private function formData(?YTeNhanKhau $record = null): array
    {
        // Những nhân khẩu chưa có hồ sơ y tế (hoặc chính nhân khẩu đang edit)
        $usedIds = YTeNhanKhau::pluck('nhan_khau_id')->toArray();

        if ($record) {
            $usedIds = array_filter($usedIds, fn ($id) => $id !== $record->nhan_khau_id);
        }

        $nhanKhau = NhanKhau::query()
            ->whereNotIn('id', $usedIds)
            ->whereNull('deleted_at')
            ->where('trang_thai', '!=', 'da_mat')
            ->orderBy('ho_ten')
            ->get(['id', 'ho_ten', 'cccd_cmnd', 'ngay_sinh']);

        // Nếu đang edit, đảm bảo nhân khẩu hiện tại có trong danh sách dù bị exclude
        if ($record && ! $nhanKhau->contains('id', $record->nhan_khau_id)) {
            $current = NhanKhau::withTrashed()->find($record->nhan_khau_id, ['id', 'ho_ten', 'cccd_cmnd', 'ngay_sinh']);
            if ($current) {
                $nhanKhau->prepend($current);
            }
        }

        return [
            'modules'      => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('an-sinh-y-te-giao-duc'),
            'record'       => $record,
            'nhanKhau'     => $nhanKhau,
            'loaiBhyt'     => YTeNhanKhau::LOAI_BHYT,
        ];
    }

    private function stats(): array
    {
        return [
            'tong_so'       => YTeNhanKhau::count(),
            'co_bhyt'       => YTeNhanKhau::where('loai_bhyt', '!=', 'khong_co')->count(),
            'het_han'       => YTeNhanKhau::where('loai_bhyt', '!=', 'khong_co')
                ->whereNotNull('ngay_het_han_the_bhyt')
                ->where('ngay_het_han_the_bhyt', '<', now())
                ->count(),
            'da_tiem_chung' => YTeNhanKhau::where('hoan_thanh_tiem_chung_mo_rong', true)->count(),
        ];
    }
}
