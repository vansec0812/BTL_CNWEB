<?php

namespace App\Http\Controllers;

use App\Support\ModuleRegistry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BieuDoController extends Controller
{
    /**
     * Display the dashboard and charts.
     */
    public function index(Request $request)
    {
        $modules = ModuleRegistry::all();

        // 1. Tháp dân số (Population Pyramid)
        // Group by 10-year ranges: 0-9, 10-19, 20-29, 30-39, 40-49, 50-59, 60-69, 70-79, 80+
        $nhanKhaus = DB::table('nhan_khau')
            ->whereNull('deleted_at')
            ->where('trang_thai', '!=', 'da_mat')
            ->get(['ngay_sinh', 'gioi_tinh']);

        $pyramidData = [
            '0-9' => ['nam' => 0, 'nu' => 0],
            '10-19' => ['nam' => 0, 'nu' => 0],
            '20-29' => ['nam' => 0, 'nu' => 0],
            '30-39' => ['nam' => 0, 'nu' => 0],
            '40-49' => ['nam' => 0, 'nu' => 0],
            '50-59' => ['nam' => 0, 'nu' => 0],
            '60-69' => ['nam' => 0, 'nu' => 0],
            '70-79' => ['nam' => 0, 'nu' => 0],
            '80+' => ['nam' => 0, 'nu' => 0],
        ];

        foreach ($nhanKhaus as $nk) {
            $age = Carbon::parse($nk->ngay_sinh)->age;
            $gender = $nk->gioi_tinh === 'nu' ? 'nu' : 'nam';

            if ($age < 10) {
                $group = '0-9';
            } elseif ($age < 20) {
                $group = '10-19';
            } elseif ($age < 30) {
                $group = '20-29';
            } elseif ($age < 40) {
                $group = '30-39';
            } elseif ($age < 50) {
                $group = '40-49';
            } elseif ($age < 60) {
                $group = '50-59';
            } elseif ($age < 70) {
                $group = '60-69';
            } elseif ($age < 80) {
                $group = '70-79';
            } else {
                $group = '80+';
            }

            $pyramidData[$group][$gender]++;
        }

        $pyramidLabels = array_keys($pyramidData);
        $pyramidMale = [];
        $pyramidFemale = [];

        foreach ($pyramidData as $group => $counts) {
            // Male counts are negative to plot on the left side in pyramid layout
            $pyramidMale[] = -$counts['nam'];
            $pyramidFemale[] = $counts['nu'];
        }

        // 2. Tỷ lệ hộ nghèo (Poverty rate)
        $povertyRecords = DB::table('bao_tro_xa_hoi')
            ->whereIn('loai_bao_tro', ['ho_ngheo', 'ho_can_ngheo'])
            ->where('trang_thai', 'dang_huong')
            ->whereNull('deleted_at')
            ->get(['ho_khau_id', 'loai_bao_tro']);

        $hoNgheoIds = [];
        $hoCanNgheoIds = [];

        foreach ($povertyRecords as $record) {
            if ($record->ho_khau_id) {
                if ($record->loai_bao_tro === 'ho_ngheo') {
                    $hoNgheoIds[$record->ho_khau_id] = true;
                } elseif ($record->loai_bao_tro === 'ho_can_ngheo') {
                    $hoCanNgheoIds[$record->ho_khau_id] = true;
                }
            }
        }

        $hoNgheoCount = count($hoNgheoIds);
        $hoCanNgheoCount = count($hoCanNgheoIds);
        $totalHoKhau = DB::table('ho_khau')->whereNull('deleted_at')->count();
        $normalHoKhauCount = max(0, $totalHoKhau - $hoNgheoCount - $hoCanNgheoCount);

        // 3. Xu hướng lao động (Labor trend)
        $laborTrendData = DB::table('lao_dong')
            ->select('trang_thai_lao_dong', DB::raw('count(*) as total'))
            ->groupBy('trang_thai_lao_dong')
            ->get()
            ->pluck('total', 'trang_thai_lao_dong')
            ->toArray();

        $laborStatuses = [
            'co_viec_lam' => 'Có việc làm',
            'that_nghiep' => 'Thất nghiệp',
            'hoc_sinh_sinh_vien' => 'Học sinh/Sinh viên',
            'mat_suc_lao_dong' => 'Mất sức lao động',
            'nghi_huu' => 'Nghỉ hưu',
            'noi_tro' => 'Nội trợ',
            'chua_den_tuoi_lao_dong' => 'Chưa đến tuổi LĐ',
        ];

        $laborTrendLabels = [];
        $laborTrendValues = [];

        foreach ($laborStatuses as $key => $label) {
            $laborTrendLabels[] = $label;
            $laborTrendValues[] = $laborTrendData[$key] ?? 0;
        }

        // Additional summary metrics for the dashboard
        $totalNhanKhau = count($nhanKhaus);
        $totalLaoDong = DB::table('lao_dong')->count();
        $totalDoanhNghiep = DB::table('doanh_nghiep_ho_kinh_doanh')->count();

        // Calculate average age
        $ages = [];
        foreach ($nhanKhaus as $nk) {
            $ages[] = Carbon::parse($nk->ngay_sinh)->age;
        }
        $avgAge = count($ages) > 0 ? round(array_sum($ages), 1) / count($ages) : 0;
        $avgAge = round($avgAge, 1);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'message' => 'Lấy dữ liệu thống kê biểu đồ thành công.',
                'data' => [
                    'pyramid' => [
                        'labels' => $pyramidLabels,
                        'male' => $pyramidMale,
                        'female' => $pyramidFemale,
                    ],
                    'poverty' => [
                        'ho_ngheo' => $hoNgheoCount,
                        'ho_can_ngheo' => $hoCanNgheoCount,
                        'ho_binh_thuong' => $normalHoKhauCount,
                        'tong_so_ho' => $totalHoKhau,
                    ],
                    'labor' => [
                        'labels' => $laborTrendLabels,
                        'values' => $laborTrendValues,
                    ],
                    'metrics' => [
                        'tong_nhan_khau' => $totalNhanKhau,
                        'tong_lao_dong' => $totalLaoDong,
                        'tong_doanh_nghiep' => $totalDoanhNghiep,
                        'tuoi_trung_binh' => $avgAge,
                    ],
                ],
            ], 200);
        }

        $parentModule = ModuleRegistry::findBySlug('he-thong-bao-cao');

        return view('he-thong.dashboard_bieu_do', compact(
            'modules',
            'parentModule',
            'pyramidLabels',
            'pyramidMale',
            'pyramidFemale',
            'hoNgheoCount',
            'hoCanNgheoCount',
            'normalHoKhauCount',
            'totalHoKhau',
            'laborTrendLabels',
            'laborTrendValues',
            'totalNhanKhau',
            'totalLaoDong',
            'totalDoanhNghiep',
            'avgAge'
        ));
    }
}
