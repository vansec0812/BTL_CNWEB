<?php

namespace App\Http\Controllers;

use App\Models\HoKhau;
use App\Models\ThueVaPhiDiaPhuong;
use App\Support\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreThueVaPhiDiaPhuongRequest;
use App\Http\Requests\UpdateThueVaPhiDiaPhuongRequest;

class ThueVaPhiDiaPhuongController extends Controller
{
    public function index(Request $request)
    {
        $query = ThueVaPhiDiaPhuong::with(['hoKhau.chuHo', 'nguoiThu']);

        if ($request->filled('loai_khoan_thu')) {
            $query->where('loai_khoan_thu', $request->get('loai_khoan_thu'));
        }

        if ($request->filled('nam')) {
            $query->where('nam', $request->get('nam'));
        } else {
            $query->where('nam', date('Y')); // Mặc định hiển thị năm hiện tại
        }

        if ($request->filled('trang_thai_thanh_toan')) {
            $query->where('trang_thai_thanh_toan', $request->get('trang_thai_thanh_toan'));
        }

        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->whereHas('hoKhau.chuHo', function($qBuilder) use ($q) {
                $qBuilder->where('ho_ten', 'like', "%{$q}%")
                         ->orWhere('cccd_cmnd', 'like', "%{$q}%");
            })->orWhereHas('hoKhau', function($qBuilder) use ($q) {
                $qBuilder->where('ma_ho', 'like', "%{$q}%");
            });
        }

        $records = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $stats = [
            'tong_phai_thu' => $query->sum('so_tien_phai_nop'),
            'tong_da_thu' => $query->sum('so_tien_da_nop'),
            'tong_chua_thu' => $query->sum('so_tien_phai_nop') - $query->sum('so_tien_da_nop'),
        ];

        return view('thue-va-phi.index', [
            'records' => $records,
            'stats' => $stats,
            'modules' => ModuleRegistry::all(),
        ]);
    }

    public function create()
    {
        $hoKhaus = HoKhau::with('chuHo')->where('trang_thai', 'hoat_dong')->get();
        return view('thue-va-phi.create', [
            'hoKhaus' => $hoKhaus,
            'modules' => ModuleRegistry::all(),
        ]);
    }

    public function store(StoreThueVaPhiDiaPhuongRequest $request)
    {
        $validated = $request->validated();

        $validated['trang_thai_thanh_toan'] = $this->determineStatus($validated['so_tien_phai_nop'], $validated['so_tien_da_nop']);
        
        if ($validated['so_tien_da_nop'] > 0) {
            $validated['ngay_nop_thuc_te'] = now()->toDateString();
            $validated['nguoi_thu_id'] = auth()->id();
        }

        ThueVaPhiDiaPhuong::create($validated);

        return redirect()->route('thue-va-phi.index')->with('status', 'Đã tạo khoản thu phí mới thành công!');
    }

    public function edit(ThueVaPhiDiaPhuong $thueVaPhi)
    {
        $hoKhaus = HoKhau::with('chuHo')->where('trang_thai', 'hoat_dong')->get();
        return view('thue-va-phi.edit', [
            'thueVaPhi' => $thueVaPhi,
            'hoKhaus' => $hoKhaus,
            'modules' => ModuleRegistry::all(),
        ]);
    }

    public function update(UpdateThueVaPhiDiaPhuongRequest $request, ThueVaPhiDiaPhuong $thueVaPhi)
    {
        $validated = $request->validated();

        $validated['trang_thai_thanh_toan'] = $this->determineStatus($validated['so_tien_phai_nop'], $validated['so_tien_da_nop']);

        if ($validated['so_tien_da_nop'] > 0 && !$thueVaPhi->ngay_nop_thuc_te) {
            $validated['ngay_nop_thuc_te'] = now()->toDateString();
            $validated['nguoi_thu_id'] = auth()->id();
        }

        $thueVaPhi->update($validated);

        return redirect()->route('thue-va-phi.index')->with('status', 'Cập nhật khoản thu thành công!');
    }

    public function destroy(ThueVaPhiDiaPhuong $thueVaPhi)
    {
        $thueVaPhi->delete();
        return redirect()->route('thue-va-phi.index')->with('status', 'Đã xóa khoản thu!');
    }

    public function generateThueDat()
    {
        // Thuật toán tính thuế theo Luật Xã Quốc Oai
        $nam = date('Y');
        $giaDat = 2000000; // 2,000,000 VND/m2 theo Quy định UBND TP Hà Nội áp dụng cho vùng ven/xã (Giá giả định mẫu)
        $thueSuat = 0.0003; // 0.03%

        $hoKhaus = HoKhau::with(['datDaiTaiSan' => function($q) {
            $q->where('loai_dat', 'dat_tho_cu'); // Chỉ thu đất thổ cư phi nông nghiệp
        }])->get();

        $count = 0;

        DB::beginTransaction();
        try {
            foreach ($hoKhaus as $ho) {
                $tongDienTich = $ho->datDaiTaiSan->sum('dien_tich_m2');
                
                if ($tongDienTich > 0) {
                    $soTienPhaiNop = round($tongDienTich * $giaDat * $thueSuat);
                    
                    $thue = ThueVaPhiDiaPhuong::firstOrNew([
                        'ho_khau_id' => $ho->id,
                        'nam' => $nam,
                        'loai_khoan_thu' => 'thue_dat_phi_nong_nghiep',
                    ]);

                    $thue->so_tien_phai_nop = $soTienPhaiNop;
                    // Không ghi đè số tiền đã nộp nếu người dân đã đóng
                    if (!$thue->exists) {
                        $thue->so_tien_da_nop = 0;
                        $thue->trang_thai_thanh_toan = 'chua_nop';
                    } else {
                        $thue->trang_thai_thanh_toan = $this->determineStatus($soTienPhaiNop, $thue->so_tien_da_nop);
                    }
                    $thue->ghi_chu = "Thuế sinh tự động. Diện tích: {$tongDienTich} m2. Đơn giá: " . number_format($giaDat) . " đ/m2. Mức thuế: 0.03%";
                    $thue->save();
                    $count++;
                }
            }
            DB::commit();
            return redirect()->route('thue-va-phi.index')->with('status', "Đã quét và tính thuế đất tự động cho {$count} hộ gia đình năm {$nam}!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra khi tính thuế: ' . $e->getMessage()]);
        }
    }

    private function determineStatus($phaiNop, $daNop)
    {
        if ($daNop >= $phaiNop) {
            return 'da_nop_du';
        } elseif ($daNop > 0) {
            return 'nop_mot_phan';
        }
        return 'chua_nop';
    }
}
