<?php

namespace App\Http\Controllers;

use App\Models\BaoTroXaHoi;
use App\Models\HoKhau;
use App\Models\NhanKhau;
use App\Support\ModuleRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BaoTroXaHoiController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'loai_bao_tro', 'trang_thai', 'doi_tuong']);

        $query = BaoTroXaHoi::query()
            ->with(['hoKhau', 'nhanKhau'])
            ->when($filters['q'] ?? null, function ($query, string $keyword): void {
                $query->where(function ($query) use ($keyword): void {
                    $query->where('so_quyet_dinh', 'like', "%{$keyword}%")
                        ->orWhere('dang_khuyet_tat', 'like', "%{$keyword}%")
                        ->orWhereHas('nhanKhau', function ($query) use ($keyword): void {
                            $query->where('ho_ten', 'like', "%{$keyword}%")
                                ->orWhere('cccd_cmnd', 'like', "%{$keyword}%");
                        })
                        ->orWhereHas('hoKhau', function ($query) use ($keyword): void {
                            $query->where('so_so_ho_khau', 'like', "%{$keyword}%")
                                ->orWhere('ma_ho', 'like', "%{$keyword}%")
                                ->orWhere('dia_chi_thuong_tru', 'like', "%{$keyword}%");
                        });
                });
            })
            ->when($filters['loai_bao_tro'] ?? null, fn ($query, string $value) => $query->where('loai_bao_tro', $value))
            ->when($filters['trang_thai'] ?? null, fn ($query, string $value) => $query->where('trang_thai', $value))
            ->when(($filters['doi_tuong'] ?? null) === 'ho_khau', fn ($query) => $query->whereNotNull('ho_khau_id'))
            ->when(($filters['doi_tuong'] ?? null) === 'nhan_khau', fn ($query) => $query->whereNotNull('nhan_khau_id'))
            ->latest();

        return view('an-sinh.bao-tro-xa-hoi.index', [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('an-sinh-y-te-giao-duc'),
            'records' => $query->paginate(10)->withQueryString(),
            'filters' => $filters,
            'loaiBaoTro' => BaoTroXaHoi::LOAI_BAO_TRO,
            'trangThai' => BaoTroXaHoi::TRANG_THAI,
            'stats' => $this->stats(),
        ]);
    }

    public function create(): View
    {
        return view('an-sinh.bao-tro-xa-hoi.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        BaoTroXaHoi::create($this->validated($request));

        return redirect()
            ->route('bao-tro-xa-hoi.index')
            ->with('status', 'Đã thêm hồ sơ bảo trợ xã hội.');
    }

    public function show(BaoTroXaHoi $baoTroXaHoi): View
    {
        return view('an-sinh.bao-tro-xa-hoi.show', $this->formData($baoTroXaHoi));
    }

    public function edit(BaoTroXaHoi $baoTroXaHoi): View
    {
        return view('an-sinh.bao-tro-xa-hoi.edit', $this->formData($baoTroXaHoi));
    }

    public function update(Request $request, BaoTroXaHoi $baoTroXaHoi): RedirectResponse
    {
        $baoTroXaHoi->update($this->validated($request, $baoTroXaHoi));

        return redirect()
            ->route('bao-tro-xa-hoi.index')
            ->with('status', 'Đã cập nhật hồ sơ bảo trợ xã hội.');
    }

    public function destroy(BaoTroXaHoi $baoTroXaHoi): RedirectResponse
    {
        $baoTroXaHoi->delete();

        return redirect()
            ->route('bao-tro-xa-hoi.index')
            ->with('status', 'Đã lưu hồ sơ bảo trợ vào trạng thái đã xoá.');
    }

    private function validated(Request $request, ?BaoTroXaHoi $record = null): array
    {
        $currentHoKhauId = $record?->ho_khau_id;
        $currentNhanKhauId = $record?->nhan_khau_id;

        $data = $request->validate([
            'loai_bao_tro' => ['required', Rule::in(array_keys(BaoTroXaHoi::LOAI_BAO_TRO))],
            'ho_khau_id' => [
                'nullable',
                'integer',
                Rule::exists('ho_khau', 'id')->where(function ($query) use ($currentHoKhauId): void {
                    $query->where(function ($query): void {
                        $query->whereNull('deleted_at')
                            ->where('trang_thai', 'hoat_dong');
                    });

                    if ($currentHoKhauId) {
                        $query->orWhere('id', $currentHoKhauId);
                    }
                }),
            ],
            'nhan_khau_id' => [
                'nullable',
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
            'muc_do_khuyet_tat' => ['required', Rule::in(array_keys(BaoTroXaHoi::MUC_DO_KHUYET_TAT))],
            'dang_khuyet_tat' => ['nullable', 'string', 'max:255'],
            'so_quyet_dinh' => ['nullable', 'string', 'max:100'],
            'ngay_bat_dau_huong' => ['nullable', 'date'],
            'ngay_ket_thuc_huong' => ['nullable', 'date', 'after_or_equal:ngay_bat_dau_huong'],
            'muc_tro_cap_hang_thang' => ['nullable', 'integer', 'min:0'],
            'trang_thai' => ['required', Rule::in(array_keys(BaoTroXaHoi::TRANG_THAI))],
            'ghi_chu' => ['nullable', 'string'],
        ]);

        $errors = [];
        $isHouseholdType = in_array($data['loai_bao_tro'], BaoTroXaHoi::LOAI_THEO_HO, true);

        if ($isHouseholdType && empty($data['ho_khau_id'])) {
            $errors['ho_khau_id'] = 'Loại hộ nghèo/cận nghèo phải chọn sổ hộ khẩu.';
        }

        if (! $isHouseholdType && empty($data['nhan_khau_id'])) {
            $errors['nhan_khau_id'] = 'Loại bảo trợ cá nhân phải chọn nhân khẩu.';
        }

        if (! empty($data['ho_khau_id']) && ! empty($data['nhan_khau_id'])) {
            $errors['ho_khau_id'] = 'Mỗi hồ sơ chỉ được gắn với một hộ khẩu hoặc một nhân khẩu.';
            $errors['nhan_khau_id'] = 'Mỗi hồ sơ chỉ được gắn với một hộ khẩu hoặc một nhân khẩu.';
        }

        if (($data['loai_bao_tro'] ?? null) === 'nguoi_khuyet_tat') {
            if (($data['muc_do_khuyet_tat'] ?? 'khong_ap_dung') === 'khong_ap_dung') {
                $errors['muc_do_khuyet_tat'] = 'Người khuyết tật phải chọn mức độ khuyết tật.';
            }

            if (blank($data['dang_khuyet_tat'] ?? null)) {
                $errors['dang_khuyet_tat'] = 'Người khuyết tật phải nhập dạng khuyết tật.';
            }
        }

        if ($errors) {
            throw ValidationException::withMessages($errors);
        }

        if ($isHouseholdType) {
            $data['nhan_khau_id'] = null;
        } else {
            $data['ho_khau_id'] = null;
        }

        if ($data['loai_bao_tro'] !== 'nguoi_khuyet_tat') {
            $data['muc_do_khuyet_tat'] = 'khong_ap_dung';
            $data['dang_khuyet_tat'] = null;
        }

        return $data;
    }

    private function formData(?BaoTroXaHoi $record = null): array
    {
        return [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('an-sinh-y-te-giao-duc'),
            'record' => $record,
            'hoKhau' => HoKhau::query()
                ->withTrashed()
                ->where(function ($query) use ($record): void {
                    $query->where(function ($query): void {
                        $query->whereNull('deleted_at')
                            ->where('trang_thai', 'hoat_dong');
                    });

                    if ($record?->ho_khau_id) {
                        $query->orWhere('id', $record->ho_khau_id);
                    }
                })
                ->orderBy('so_so_ho_khau')
                ->get(['id', 'so_so_ho_khau', 'ma_ho', 'dia_chi_thuong_tru', 'thon_xom']),
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
            'loaiBaoTro' => BaoTroXaHoi::LOAI_BAO_TRO,
            'mucDoKhuyetTat' => BaoTroXaHoi::MUC_DO_KHUYET_TAT,
            'trangThai' => BaoTroXaHoi::TRANG_THAI,
        ];
    }

    private function stats(): array
    {
        return [
            'tong_so' => BaoTroXaHoi::count(),
            'dang_huong' => BaoTroXaHoi::where('trang_thai', 'dang_huong')->count(),
            'ho_ngheo_can_ngheo' => BaoTroXaHoi::whereIn('loai_bao_tro', BaoTroXaHoi::LOAI_THEO_HO)->count(),
            'tong_tro_cap' => BaoTroXaHoi::where('trang_thai', 'dang_huong')->sum('muc_tro_cap_hang_thang'),
        ];
    }
}
