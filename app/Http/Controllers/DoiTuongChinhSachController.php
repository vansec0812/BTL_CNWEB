<?php

namespace App\Http\Controllers;

use App\Models\DoiTuongChinhSach;
use App\Models\NhanKhau;
use App\Support\ModuleRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DoiTuongChinhSachController extends Controller
{
    public function index(Request $request): View
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

    public function create(): View
    {
        return view('an-sinh.doi-tuong-chinh-sach.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        DoiTuongChinhSach::create($this->validated($request));

        return redirect()
            ->route('doi-tuong-chinh-sach.index')
            ->with('status', 'Đã thêm hồ sơ diện chính sách.');
    }

    public function show(DoiTuongChinhSach $doiTuongChinhSach): View
    {
        return view('an-sinh.doi-tuong-chinh-sach.show', $this->formData($doiTuongChinhSach));
    }

    public function edit(DoiTuongChinhSach $doiTuongChinhSach): View
    {
        return view('an-sinh.doi-tuong-chinh-sach.edit', $this->formData($doiTuongChinhSach));
    }

    public function update(Request $request, DoiTuongChinhSach $doiTuongChinhSach): RedirectResponse
    {
        $doiTuongChinhSach->update($this->validated($request, $doiTuongChinhSach));

        return redirect()
            ->route('doi-tuong-chinh-sach.index')
            ->with('status', 'Đã cập nhật hồ sơ diện chính sách.');
    }

    public function destroy(DoiTuongChinhSach $doiTuongChinhSach): RedirectResponse
    {
        $doiTuongChinhSach->delete();

        return redirect()
            ->route('doi-tuong-chinh-sach.index')
            ->with('status', 'Đã lưu hồ sơ vào trạng thái đã xoá.');
    }

    private function validated(Request $request, ?DoiTuongChinhSach $record = null): array
    {
        $currentNhanKhauId = $record?->nhan_khau_id;

        return $request->validate([
            'nhan_khau_id' => [
                'required',
                'integer',
                Rule::exists('nhan_khau', 'id')->where(function ($query) use ($currentNhanKhauId): void {
                    $query->where(function ($query): void {
                        $query->whereNull('deleted_at')
                            ->where('trang_thai', '!=', 'da_mat');
                    });

                    if ($currentNhanKhauId) {
                        $query->orWhere('id', $currentNhanKhauId);
                    }
                }),
            ],
            'loai_chinh_sach' => ['required', Rule::in(array_keys(DoiTuongChinhSach::LOAI_CHINH_SACH))],
            'so_quyet_dinh_cong_nhan' => ['nullable', 'string', 'max:100'],
            'ngay_cong_nhan' => ['nullable', 'date'],
            'co_quan_cap' => ['nullable', 'string', 'max:255'],
            'ty_le_thuong_tat' => ['nullable', 'numeric', 'between:0,100'],
            'muc_tro_cap_hang_thang' => ['nullable', 'integer', 'min:0'],
            'trang_thai' => ['required', Rule::in(array_keys(DoiTuongChinhSach::TRANG_THAI))],
            'ghi_chu' => ['nullable', 'string'],
        ]);
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
