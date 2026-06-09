<?php

namespace App\Http\Controllers;

use App\Models\BaoTroXaHoi;
use App\Models\ChiTietCapPhatTroCap;
use App\Models\DoiTuongChinhSach;
use App\Models\DotTroCap;
use App\Models\HoKhau;
use App\Models\NhanKhau;
use App\Support\ModuleRegistry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DotTroCapController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'loai_tro_cap', 'trang_thai']);

        $query = DotTroCap::query()
            ->when($filters['q'] ?? null, function ($query, string $keyword): void {
                $query->where('ten_dot', 'like', "%{$keyword}%")
                    ->orWhere('mo_ta', 'like', "%{$keyword}%")
                    ->orWhere('nguon_kinh_phi', 'like', "%{$keyword}%");
            })
            ->when($filters['loai_tro_cap'] ?? null, fn ($query, string $value) => $query->where('loai_tro_cap', $value))
            ->when($filters['trang_thai'] ?? null, fn ($query, string $value) => $query->where('trang_thai', $value))
            ->latest();

        $dotTroCap = $query->paginate(10)->withQueryString();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách đợt trợ cấp thành công.',
                'data' => $dotTroCap,
                'stats' => $this->stats(),
            ], 200);
        }

        return view('an-sinh.dot-tro-cap.index', [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('an-sinh-y-te-giao-duc'),
            'records' => $dotTroCap,
            'filters' => $filters,
            'loaiTroCap' => DotTroCap::LOAI_TRO_CAP,
            'trangThai' => DotTroCap::TRANG_THAI,
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

        return view('an-sinh.dot-tro-cap.create', $formData);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_dot' => ['required', 'string', 'max:255'],
            'mo_ta' => ['nullable', 'string'],
            'loai_tro_cap' => ['required', Rule::in(array_keys(DotTroCap::LOAI_TRO_CAP))],
            'gia_tri_quy_doi' => ['nullable', 'integer', 'min:0'],
            'nguon_kinh_phi' => ['nullable', 'string', 'max:255'],
            'ngay_bat_dau_cap_phat' => ['required', 'date'],
            'ngay_ket_thuc_cap_phat' => ['nullable', 'date', 'after_or_equal:ngay_bat_dau_cap_phat'],
            'trang_thai' => ['required', Rule::in(array_keys(DotTroCap::TRANG_THAI))],
            'ghi_chu' => ['nullable', 'string'],
            'loai_bao_tro' => ['nullable', 'array'],
            'loai_bao_tro.*' => ['string', Rule::in(array_keys(BaoTroXaHoi::LOAI_BAO_TRO))],
            'loai_chinh_sach' => ['nullable', 'array'],
            'loai_chinh_sach.*' => ['string', Rule::in(array_keys(DoiTuongChinhSach::LOAI_CHINH_SACH))],
            'thon_xom' => ['nullable', 'array'],
            'thon_xom.*' => ['string'],
        ]);

        $loaiBaoTro = $validated['loai_bao_tro'] ?? [];
        $loaiChinhSach = $validated['loai_chinh_sach'] ?? [];
        $selectedThonXom = $validated['thon_xom'] ?? [];

        // Save target conditions as JSON
        $validated['dieu_kien_doi_tuong'] = [
            'loai_bao_tro' => $loaiBaoTro,
            'loai_chinh_sach' => $loaiChinhSach,
            'thon_xom' => $selectedThonXom,
        ];
        $validated['nguoi_tao_id'] = auth()->id();

        DB::beginTransaction();
        try {
            // Create campaign
            $dotTroCap = DotTroCap::create(collect($validated)->except(['loai_bao_tro', 'loai_chinh_sach', 'thon_xom'])->toArray());

            // Scan recipients
            $this->scanAndSaveRecipients($dotTroCap, $loaiBaoTro, $loaiChinhSach, $selectedThonXom);

            DB::commit();

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã tạo đợt trợ cấp mới và tự động quét danh sách đối tượng hưởng.',
                    'data' => $dotTroCap,
                ], 201);
            }

            return redirect()
                ->route('dot-tro-cap.index')
                ->with('status', 'Đã tạo đợt trợ cấp mới và tự động quét danh sách đối tượng hưởng.');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi tạo đợt trợ cấp: '.$e->getMessage(),
                ], 422);
            }

            return back()->withErrors(['error' => 'Lỗi tạo đợt trợ cấp: '.$e->getMessage()])->withInput();
        }
    }

    public function show(DotTroCap $dotTroCap, Request $request)
    {
        $filters = $request->only(['q', 'da_nhan']);

        $query = $dotTroCap->chiTietCapPhats()
            ->with(['hoKhau.chuHo', 'nhanKhau.hoKhau', 'nguoiXacNhan'])
            ->when($filters['q'] ?? null, function ($query, string $keyword): void {
                $query->where(function ($query) use ($keyword) {
                    $query->whereHas('nhanKhau', function ($query) use ($keyword) {
                        $query->where('ho_ten', 'like', "%{$keyword}%")
                            ->orWhere('cccd_cmnd', 'like', "%{$keyword}%");
                    })
                        ->orWhereHas('hoKhau.chuHo', function ($query) use ($keyword) {
                            $query->where('ho_ten', 'like', "%{$keyword}%");
                        })
                        ->orWhereHas('hoKhau', function ($query) use ($keyword) {
                            $query->where('so_so_ho_khau', 'like', "%{$keyword}%");
                        });
                });
            })
            ->when(isset($filters['da_nhan']) && $filters['da_nhan'] !== '', function ($query) use ($filters) {
                $query->where('da_nhan', (bool) $filters['da_nhan']);
            });

        $recipients = $query->paginate(15)->withQueryString();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy chi tiết đợt trợ cấp thành công.',
                'data' => [
                    'record' => $dotTroCap,
                    'recipients' => $recipients,
                ],
            ], 200);
        }

        // Data for manual additions form
        $allNhanKhau = NhanKhau::query()
            ->where('trang_thai', '!=', 'da_mat')
            ->orderBy('ho_ten')
            ->get(['id', 'ho_ten', 'cccd_cmnd']);

        $allHoKhau = HoKhau::query()
            ->where('trang_thai', 'hoat_dong')
            ->with('chuHo')
            ->get()
            ->map(function ($ho) {
                return [
                    'id' => $ho->id,
                    'label' => 'Sổ: '.$ho->so_so_ho_khau.' - Chủ hộ: '.($ho->chuHo?->ho_ten ?? 'Chưa xác định'),
                ];
            });

        return view('an-sinh.dot-tro-cap.show', [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('an-sinh-y-te-giao-duc'),
            'record' => $dotTroCap,
            'recipients' => $recipients,
            'filters' => $filters,
            'allNhanKhau' => $allNhanKhau,
            'allHoKhau' => $allHoKhau,
        ]);
    }

    public function edit(DotTroCap $dotTroCap, Request $request)
    {
        $dieuKien = $dotTroCap->dieu_kien_doi_tuong ?? [];
        $selectedBaoTro = $dieuKien['loai_bao_tro'] ?? [];
        $selectedChinhSach = $dieuKien['loai_chinh_sach'] ?? [];
        $selectedThonXom = $dieuKien['thon_xom'] ?? [];

        $formData = array_merge(
            $this->formData(),
            [
                'record' => $dotTroCap,
                'selectedBaoTro' => $selectedBaoTro,
                'selectedChinhSach' => $selectedChinhSach,
                'selectedThonXom' => $selectedThonXom,
            ]
        );

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy dữ liệu form chỉnh sửa thành công.',
                'data' => $formData,
            ], 200);
        }

        return view('an-sinh.dot-tro-cap.edit', $formData);
    }

    public function update(Request $request, DotTroCap $dotTroCap)
    {
        $validated = $request->validate([
            'ten_dot' => ['required', 'string', 'max:255'],
            'mo_ta' => ['nullable', 'string'],
            'loai_tro_cap' => ['required', Rule::in(array_keys(DotTroCap::LOAI_TRO_CAP))],
            'gia_tri_quy_doi' => ['nullable', 'integer', 'min:0'],
            'nguon_kinh_phi' => ['nullable', 'string', 'max:255'],
            'ngay_bat_dau_cap_phat' => ['required', 'date'],
            'ngay_ket_thuc_cap_phat' => ['nullable', 'date', 'after_or_equal:ngay_bat_dau_cap_phat'],
            'trang_thai' => ['required', Rule::in(array_keys(DotTroCap::TRANG_THAI))],
            'ghi_chu' => ['nullable', 'string'],
            'loai_bao_tro' => ['nullable', 'array'],
            'loai_bao_tro.*' => ['string', Rule::in(array_keys(BaoTroXaHoi::LOAI_BAO_TRO))],
            'loai_chinh_sach' => ['nullable', 'array'],
            'loai_chinh_sach.*' => ['string', Rule::in(array_keys(DoiTuongChinhSach::LOAI_CHINH_SACH))],
            'thon_xom' => ['nullable', 'array'],
            'thon_xom.*' => ['string'],
            'refresh_recipients' => ['nullable', 'boolean'],
        ]);

        $loaiBaoTro = $validated['loai_bao_tro'] ?? [];
        $loaiChinhSach = $validated['loai_chinh_sach'] ?? [];
        $selectedThonXom = $validated['thon_xom'] ?? [];

        $validated['dieu_kien_doi_tuong'] = [
            'loai_bao_tro' => $loaiBaoTro,
            'loai_chinh_sach' => $loaiChinhSach,
            'thon_xom' => $selectedThonXom,
        ];

        DB::beginTransaction();
        try {
            $dotTroCap->update(collect($validated)->except(['loai_bao_tro', 'loai_chinh_sach', 'thon_xom', 'refresh_recipients'])->toArray());

            // If the status is sap_dien_ra, or they explicitly request to re-scan recipients
            if ($dotTroCap->trang_thai === 'sap_dien_ra' || $request->has('refresh_recipients')) {
                // Delete previous entries that have not been received yet
                $dotTroCap->chiTietCapPhats()->where('da_nhan', false)->delete();
                // If they checked refresh, we delete even received ones just to completely rebuild the list (with caution)
                if ($request->has('refresh_recipients')) {
                    $dotTroCap->chiTietCapPhats()->delete();
                    $dotTroCap->update(['so_da_nhan' => 0]);
                }

                // Re-scan
                $this->scanAndSaveRecipients($dotTroCap, $loaiBaoTro, $loaiChinhSach, $selectedThonXom);
            }

            DB::commit();

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã cập nhật đợt trợ cấp thành công.',
                    'data' => $dotTroCap,
                ], 200);
            }

            return redirect()
                ->route('dot-tro-cap.index')
                ->with('status', 'Đã cập nhật đợt trợ cấp thành công.');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi cập nhật: '.$e->getMessage(),
                ], 422);
            }

            return back()->withErrors(['error' => 'Lỗi cập nhật: '.$e->getMessage()])->withInput();
        }
    }

    public function destroy(DotTroCap $dotTroCap, Request $request)
    {
        $dotTroCap->delete();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa đợt trợ cấp thành công.',
            ], 200);
        }

        return redirect()
            ->route('dot-tro-cap.index')
            ->with('status', 'Đã xóa đợt trợ cấp thành công.');
    }

    /**
     * Xác nhận đã nhận trợ cấp cho một cá nhân/hộ gia đình.
     */
    public function confirmReceipt(DotTroCap $dotTroCap, int $detailId, Request $request)
    {
        $detail = $dotTroCap->chiTietCapPhats()->findOrFail($detailId);

        if (! $detail->da_nhan) {
            DB::transaction(function () use ($dotTroCap, $detail) {
                $detail->update([
                    'da_nhan' => true,
                    'thoi_gian_nhan' => now(),
                    'nguoi_xac_nhan_id' => auth()->id(),
                ]);
                $dotTroCap->increment('so_da_nhan');
            });
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xác nhận nhận trợ cấp.',
                'data' => $detail,
            ], 200);
        }

        return back()->with('status', 'Đã xác nhận nhận trợ cấp.');
    }

    /**
     * Xác nhận nhận trợ cấp hàng loạt.
     */
    public function confirmReceiptBatch(DotTroCap $dotTroCap, Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng chọn ít nhất một đối tượng để xác nhận.',
                ], 422);
            }
            return back()->withErrors(['error' => 'Vui lòng chọn ít nhất một đối tượng để xác nhận.']);
        }

        DB::transaction(function () use ($dotTroCap, $ids) {
            $details = $dotTroCap->chiTietCapPhats()
                ->whereIn('id', $ids)
                ->where('da_nhan', false)
                ->get();

            foreach ($details as $detail) {
                $detail->update([
                    'da_nhan' => true,
                    'thoi_gian_nhan' => now(),
                    'nguoi_xac_nhan_id' => auth()->id(),
                ]);
                $dotTroCap->increment('so_da_nhan');
            }
        });

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xác nhận nhận trợ cấp cho '.count($ids).' đối tượng.',
            ], 200);
        }

        return back()->with('status', 'Đã xác nhận nhận trợ cấp cho '.count($ids).' đối tượng.');
    }

    /**
     * Thêm đối tượng nhận thủ công ngoài danh sách tự động quét.
     */
    public function addRecipient(DotTroCap $dotTroCap, Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['ho_khau', 'nhan_khau'])],
            'nhan_khau_id' => ['required_if:type,nhan_khau', 'nullable', 'integer', Rule::exists('nhan_khau', 'id')],
            'ho_khau_id' => ['required_if:type,ho_khau', 'nullable', 'integer', Rule::exists('ho_khau', 'id')],
            'so_suat' => ['required', 'integer', 'min:1'],
            'gia_tri_nhan' => ['nullable', 'integer', 'min:0'],
            'ghi_chu' => ['nullable', 'string'],
        ]);

        $type = $validated['type'];
        $nhanKhauId = $type === 'nhan_khau' ? $validated['nhan_khau_id'] : null;
        $hoKhauId = $type === 'ho_khau' ? $validated['ho_khau_id'] : null;

        // Check if already in the list
        $exists = $dotTroCap->chiTietCapPhats()
            ->when($nhanKhauId, fn ($q) => $q->where('nhan_khau_id', $nhanKhauId))
            ->when($hoKhauId, fn ($q) => $q->where('ho_khau_id', $hoKhauId))
            ->exists();

        if ($exists) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đối tượng này đã có trong danh sách nhận của đợt trợ cấp này.',
                ], 422);
            }
            return back()->withErrors(['error' => 'Đối tượng này đã có trong danh sách nhận của đợt trợ cấp này.']);
        }

        $detail = null;
        DB::transaction(function () use ($dotTroCap, $hoKhauId, $nhanKhauId, $validated, &$detail) {
            $detail = ChiTietCapPhatTroCap::create([
                'dot_tro_cap_id' => $dotTroCap->id,
                'ho_khau_id' => $hoKhauId,
                'nhan_khau_id' => $nhanKhauId,
                'so_suat' => $validated['so_suat'],
                'gia_tri_nhan' => $validated['gia_tri_nhan'] ?? $dotTroCap->gia_tri_quy_doi ?? 0,
                'da_nhan' => false,
                'ghi_chu' => $validated['ghi_chu'] ?? 'Thêm thủ công',
            ]);

            $dotTroCap->increment('tong_so_doi_tuong');
        });

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm đối tượng nhận trợ cấp thủ công.',
                'data' => $detail,
            ], 200);
        }

        return back()->with('status', 'Đã thêm đối tượng nhận trợ cấp thủ công.');
    }

    /**
     * Xóa một đối tượng khỏi danh sách nhận.
     */
    public function removeRecipient(DotTroCap $dotTroCap, int $detailId, Request $request)
    {
        $detail = $dotTroCap->chiTietCapPhats()->findOrFail($detailId);

        DB::transaction(function () use ($dotTroCap, $detail) {
            if ($detail->da_nhan) {
                $dotTroCap->decrement('so_da_nhan');
            }
            $dotTroCap->decrement('tong_so_doi_tuong');
            $detail->delete();
        });

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa đối tượng khỏi danh sách nhận.',
            ], 200);
        }

        return back()->with('status', 'Đã xóa đối tượng khỏi danh sách nhận.');
    }

    /**
     * Logic quét đối tượng và lưu vào DB.
     */
    private function scanAndSaveRecipients(DotTroCap $dotTroCap, array $loaiBaoTro, array $loaiChinhSach, array $selectedThonXom): void
    {
        $recipients = collect();

        // 1. Quét đối tượng bảo trợ xã hội
        if (! empty($loaiBaoTro)) {
            $baoTroRecords = BaoTroXaHoi::query()
                ->where('trang_thai', 'dang_huong')
                ->whereIn('loai_bao_tro', $loaiBaoTro)
                ->with(['hoKhau', 'nhanKhau.hoKhau'])
                ->get();

            foreach ($baoTroRecords as $record) {
                if ($record->isHoGiaDinh()) {
                    if ($record->ho_khau_id) {
                        $recipients->push([
                            'type' => 'ho_khau',
                            'id' => $record->ho_khau_id,
                            'thon_xom' => $record->hoKhau?->thon_xom,
                        ]);
                    }
                } else {
                    if ($record->nhan_khau_id) {
                        $recipients->push([
                            'type' => 'nhan_khau',
                            'id' => $record->nhan_khau_id,
                            'thon_xom' => $record->nhanKhau?->hoKhau?->thon_xom,
                        ]);
                    }
                }
            }
        }

        // 2. Quét đối tượng diện chính sách
        if (! empty($loaiChinhSach)) {
            $chinhSachRecords = DoiTuongChinhSach::query()
                ->where('trang_thai', 'dang_huong_che_do')
                ->whereIn('loai_chinh_sach', $loaiChinhSach)
                ->with('nhanKhau.hoKhau')
                ->get();

            foreach ($chinhSachRecords as $record) {
                if ($record->nhan_khau_id) {
                    $recipients->push([
                        'type' => 'nhan_khau',
                        'id' => $record->nhan_khau_id,
                        'thon_xom' => $record->nhanKhau?->hoKhau?->thon_xom,
                    ]);
                }
            }
        }

        // 3. Lọc theo thôn xóm nếu có
        if (! empty($selectedThonXom)) {
            $recipients = $recipients->filter(function ($item) use ($selectedThonXom) {
                return in_array($item['thon_xom'], $selectedThonXom, true);
            });
        }

        // 4. Loại bỏ trùng lặp (Dedup) và lưu
        $uniqueHoKhauIds = [];
        $uniqueNhanKhauIds = [];
        $insertedCount = 0;

        foreach ($recipients as $item) {
            $hoKhauId = $item['type'] === 'ho_khau' ? $item['id'] : null;
            $nhanKhauId = $item['type'] === 'nhan_khau' ? $item['id'] : null;

            if ($hoKhauId) {
                if (! in_array($hoKhauId, $uniqueHoKhauIds)) {
                    $uniqueHoKhauIds[] = $hoKhauId;

                    ChiTietCapPhatTroCap::create([
                        'dot_tro_cap_id' => $dotTroCap->id,
                        'ho_khau_id' => $hoKhauId,
                        'nhan_khau_id' => null,
                        'so_suat' => 1,
                        'gia_tri_nhan' => $dotTroCap->gia_tri_quy_doi ?? 0,
                        'da_nhan' => false,
                        'ghi_chu' => 'Được quét tự động',
                    ]);
                    $insertedCount++;
                }
            } elseif ($nhanKhauId) {
                if (! in_array($nhanKhauId, $uniqueNhanKhauIds)) {
                    $uniqueNhanKhauIds[] = $nhanKhauId;

                    ChiTietCapPhatTroCap::create([
                        'dot_tro_cap_id' => $dotTroCap->id,
                        'ho_khau_id' => null,
                        'nhan_khau_id' => $nhanKhauId,
                        'so_suat' => 1,
                        'gia_tri_nhan' => $dotTroCap->gia_tri_quy_doi ?? 0,
                        'da_nhan' => false,
                        'ghi_chu' => 'Được quét tự động',
                    ]);
                    $insertedCount++;
                }
            }
        }

        // Update stats
        $dotTroCap->update([
            'tong_so_doi_tuong' => $insertedCount,
        ]);
    }

    private function stats(): array
    {
        return [
            'tong_so' => DotTroCap::count(),
            'dang_thuc_hien' => DotTroCap::where('trang_thai', 'dang_thuc_hien')->count(),
            'hoan_thanh' => DotTroCap::where('trang_thai', 'hoan_thanh')->count(),
            'tong_quy_cap_phat' => DotTroCap::query()->sum(DB::raw('tong_so_doi_tuong * gia_tri_quy_doi')),
            'tong_da_trao' => ChiTietCapPhatTroCap::where('da_nhan', true)->sum('gia_tri_nhan'),
        ];
    }

    private function formData(): array
    {
        // Extract distinct village names
        $thonXoms = HoKhau::query()
            ->whereNotNull('thon_xom')
            ->where('thon_xom', '!=', '')
            ->distinct()
            ->orderBy('thon_xom')
            ->pluck('thon_xom')
            ->toArray();

        // Fallbacks if empty
        if (empty($thonXoms)) {
            $thonXoms = ['Thôn Đoàn Kết', 'Thôn Bình An', 'Thôn Phú Lợi', 'Thôn Hòa Bình'];
        }

        return [
            'modules' => ModuleRegistry::all(),
            'parentModule' => ModuleRegistry::findBySlug('an-sinh-y-te-giao-duc'),
            'loaiBaoTro' => BaoTroXaHoi::LOAI_BAO_TRO,
            'loaiChinhSach' => DoiTuongChinhSach::LOAI_CHINH_SACH,
            'loaiTroCap' => DotTroCap::LOAI_TRO_CAP,
            'trangThai' => DotTroCap::TRANG_THAI,
            'thonXoms' => $thonXoms,
        ];
    }
}
