<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHoKhauRequest;
use App\Http\Requests\UpdateHoKhauRequest;
use App\Models\HoKhau;
use App\Models\NhanKhau;
use App\Services\HoKhauService;
use App\Support\ModuleRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HoKhauController extends Controller
{
    public function __construct(
        private HoKhauService $hoKhauService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'thon_xom', 'phan_loai', 'trang_thai']);
        $perPage = $request->integer('per_page', 10);

        $records = $this->hoKhauService->getHoKhauList($filters, $perPage);

        return view('ho-tich.ho-khau.index', [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('ho-tich-cu-tru'),
            'records' => $records,
            'filters' => $filters,
            'phanLoai' => HoKhau::PHAN_LOAI,
            'trangThai' => HoKhau::TRANG_THAI,
            'stats' => $this->stats(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('ho-tich.ho-khau.create', $this->formData());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHoKhauRequest $request): RedirectResponse
    {
        $this->hoKhauService->createHoKhau($request->validated());

        return redirect()
            ->route('ho-khau.index')
            ->with('status', 'Đã tạo sổ hộ khẩu mới thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HoKhau $hoKhau): View
    {
        return view('ho-tich.ho-khau.show', $this->formData($hoKhau));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HoKhau $hoKhau): View
    {
        return view('ho-tich.ho-khau.edit', $this->formData($hoKhau));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHoKhauRequest $request, HoKhau $hoKhau): RedirectResponse
    {
        $this->hoKhauService->updateHoKhau($hoKhau, $request->validated());

        return redirect()
            ->route('ho-khau.index')
            ->with('status', 'Cập nhật sổ hộ khẩu thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HoKhau $hoKhau): RedirectResponse
    {
        $this->hoKhauService->deleteHoKhau($hoKhau);

        return redirect()
            ->route('ho-khau.index')
            ->with('status', 'Xóa sổ hộ khẩu thành công.');
    }

    /**
     * Get shared form data.
     */
    private function formData(?HoKhau $record = null): array
    {
        return [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('ho-tich-cu-tru'),
            'record' => $record,
            'nhanKhau' => NhanKhau::query()
                ->withTrashed()
                ->where(function ($query) use ($record): void {
                    $query->where(function ($query): void {
                        $query->whereNull('deleted_at')
                            ->where('trang_thai', '!=', 'da_mat');
                    });

                    if ($record?->chu_ho_nhan_khau_id) {
                        $query->orWhere('id', $record->chu_ho_nhan_khau_id);
                    }
                })
                ->orderBy('ho_ten')
                ->get(['id', 'ho_ten', 'cccd_cmnd', 'ngay_sinh']),
            'phanLoai' => HoKhau::PHAN_LOAI,
            'trangThai' => HoKhau::TRANG_THAI,
        ];
    }

    /**
     * Calculate stats for the dashboard.
     */
    private function stats(): array
    {
        return [
            'tong_so' => HoKhau::count(),
            'thuong_tru' => HoKhau::where('phan_loai', 'thuong_tru')->count(),
            'tam_tru' => HoKhau::where('phan_loai', 'tam_tru')->count(),
            'tam_vang' => HoKhau::where('phan_loai', 'tam_vang')->count(),
        ];
    }
}
