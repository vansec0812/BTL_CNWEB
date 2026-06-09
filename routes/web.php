<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BaoTroXaHoiController;
use App\Http\Controllers\BienDongHoKhauController;
use App\Http\Controllers\BieuDoController;
use App\Http\Controllers\DanQuanHoatDongController;
use App\Http\Controllers\DanQuanTuVeController;
use App\Http\Controllers\DoanhNghiepController;
use App\Http\Controllers\DoiTuongChinhSachController;
use App\Http\Controllers\DotTroCapController;
use App\Http\Controllers\HoKhauController;
use App\Http\Controllers\KetNoiViecLamController;
use App\Http\Controllers\LaoDongController;
use App\Http\Controllers\NghiaVuQuanSuController;
use App\Http\Controllers\NhanKhauController;
use App\Http\Controllers\TamTruTamVangController;
use App\Http\Controllers\UserController;
use App\Models\DotTroCap;
use App\Models\HoKhau;
use App\Models\LaoDong;
use App\Models\NghiaVuQuanSu;
use App\Support\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

$modules = [
    [
        'slug' => 'ho-tich-cu-tru',
        'title' => 'Hộ tịch & Cư trú',
        'short' => 'Cư trú',
        'owner' => 'Người 2',
        'color' => 'success',
        'view' => 'modules.ho-tich-cu-tru',
        'description' => 'Quản lý sổ hộ khẩu, nhân khẩu, biến động hộ và hồ sơ tạm trú, tạm vắng trên địa bàn xã.',
        'tables' => ['ho_khau', 'nhan_khau', 'bien_dong_ho_khau', 'tam_tru_tam_vang'],
        'features' => [
            'Danh sách hộ khẩu, chủ hộ, địa chỉ và số thành viên.',
            'Hồ sơ nhân khẩu với CCCD, ngày sinh, quan hệ với chủ hộ.',
            'Nghiệp vụ tách hộ, nhập hộ, chuyển đến, chuyển đi.',
            'Theo dõi tạm trú, tạm vắng, khai tử và thay đổi thông tin cá nhân.',
        ],
        'rows' => [
            ['Sổ hộ khẩu', 'Mã hộ, số sổ, chủ hộ, địa chỉ', 'Sẵn sàng nhập liệu'],
            ['Nhân khẩu', 'CCCD, dân tộc, tôn giáo, học vấn', 'Cần đối chiếu định kỳ'],
            ['Biến động hộ', 'Tách hộ, nhập hộ, chuyển khẩu', 'Theo dõi hồ sơ phát sinh'],
        ],
    ],
    [
        'slug' => 'kinh-te-lao-dong',
        'title' => 'Kinh tế, Lao động & Việc làm',
        'short' => 'Lao động',
        'owner' => 'Người 3',
        'color' => 'primary',
        'view' => 'modules.kinh-te-lao-dong',
        'description' => 'Theo dõi trạng thái lao động, ngành nghề, doanh nghiệp, hộ kinh doanh và nhu cầu kết nối việc làm.',
        'tables' => ['lao_dong', 'lich_su_cong_viec', 'doanh_nghiep_ho_kinh_doanh', 'ket_noi_viec_lam'],
        'features' => [
            'Quản lý trạng thái lao động: có việc làm, thất nghiệp, nghỉ hưu, học sinh/sinh viên.',
            'Theo dõi nơi làm việc, ngành nghề, làm xa và xuất khẩu lao động.',
            'Danh mục doanh nghiệp, hộ kinh doanh cá thể trên địa bàn.',
            'Kết nối người lao động với nhu cầu tuyển dụng địa phương.',
        ],
        'rows' => [
            ['Hồ sơ lao động', 'Nhân khẩu, nghề nghiệp, trạng thái', 'Đang mở rộng'],
            ['Doanh nghiệp', 'Cơ sở sản xuất, hộ kinh doanh', 'Cần cập nhật thường xuyên'],
            ['Kết nối việc làm', 'Nhu cầu tuyển dụng, ứng viên phù hợp', 'Dữ liệu mẫu'],
        ],
    ],
    [
        'slug' => 'an-sinh-y-te-giao-duc',
        'title' => 'An sinh xã hội, Y tế & Giáo dục',
        'short' => 'An sinh',
        'owner' => 'Người 4',
        'color' => 'info',
        'view' => 'modules.an-sinh-xa-hoi',
        'description' => 'Quản lý diện chính sách, bảo trợ xã hội, đợt trợ cấp, y tế cộng đồng và dữ liệu giáo dục cơ bản.',
        'tables' => ['doi_tuong_chinh_sach', 'bao_tro_xa_hoi', 'dot_tro_cap', 'chi_tiet_cap_phat_tro_cap', 'y_te_nhan_khau'],
        'features' => [
            'Danh sách thương binh, bệnh binh, thân nhân liệt sĩ và người có công.',
            'Theo dõi hộ nghèo, hộ cận nghèo, người khuyết tật, người già neo đơn.',
            'Tạo đợt trợ cấp, quà tặng và ghi nhận trạng thái đã nhận.',
            'Theo dõi bảo hiểm y tế, tiêm chủng và nhóm học sinh cần quan tâm.',
        ],
        'rows' => [
            ['Diện chính sách', 'Người có công, thân nhân, mức hỗ trợ', 'Cần rà soát'],
            ['Bảo trợ xã hội', 'Hộ nghèo, khuyết tật, neo đơn', 'Ưu tiên xử lý'],
            ['Đợt trợ cấp', 'Tên đợt, mức hỗ trợ, trạng thái nhận', 'Theo dõi theo tháng'],
        ],
    ],
    [
        'slug' => 'nghia-vu-an-ninh',
        'title' => 'Nghĩa vụ & An ninh quốc phòng',
        'short' => 'Quốc phòng',
        'owner' => 'Người 5',
        'color' => 'warning',
        'view' => 'modules.nghia-vu-an-ninh',
        'description' => 'Quản lý nghĩa vụ quân sự, dân quân tự vệ, danh sách rà soát và nhóm hồ sơ an ninh trật tự.',
        'tables' => ['nghia_vu_quan_su', 'dan_quan_tu_ve', 'dan_quan_hoat_dong'],
        'features' => [
            'Lọc danh sách nam công dân trong độ tuổi nghĩa vụ quân sự.',
            'Theo dõi trạng thái: đủ điều kiện, tạm hoãn, trúng tuyển, nhập ngũ, xuất ngũ.',
            'Quản lý lực lượng dân quân tự vệ và lịch sử hoạt động.',
            'Chuẩn bị khu vực theo dõi vi phạm hành chính, đối tượng quản lý đặc biệt.',
        ],
        'rows' => [
            ['Nghĩa vụ quân sự', 'Độ tuổi, sức khỏe, học vấn, trạng thái', 'Theo mùa tuyển quân'],
            ['Dân quân tự vệ', 'Lực lượng nòng cốt, lịch sử hoạt động', 'Theo kế hoạch xã'],
            ['An ninh trật tự', 'Hồ sơ quản lý đặc biệt', 'Trang khung chờ dữ liệu'],
        ],
    ],
    [
        'slug' => 'dat-dai-ha-tang',
        'title' => 'Đất đai, Hạ tầng & Tài sản hộ dân',
        'short' => 'Đất đai',
        'owner' => 'Người 5',
        'color' => 'secondary',
        'view' => 'modules.dat-dai-ha-tang',
        'description' => 'Quản lý thửa đất, tài sản hộ dân, địa bàn thôn/xóm và các khoản thuế phí địa phương.',
        'tables' => ['dat_dai_tai_san', 'thue_va_phi_dia_phuong'],
        'features' => [
            'Hồ sơ đất thổ cư, đất nông nghiệp theo hộ gia đình.',
            'Theo dõi số tờ, số thửa, loại đất, diện tích và tình trạng sử dụng.',
            'Liên kết hộ dân với địa bàn thôn, xóm, đội hoặc tuyến hạ tầng.',
            'Quản lý thuế đất, phí vệ sinh, quỹ khuyến học và khoản thu địa phương.',
        ],
        'rows' => [
            ['Đất đai tài sản', 'Số thửa, diện tích, loại đất', 'Cần đối chiếu hồ sơ giấy'],
            ['Hạ tầng địa bàn', 'Thôn, xóm, đội, tuyến đường', 'Khung mở rộng'],
            ['Thuế và phí', 'Loại khoản thu, số tiền, trạng thái', 'Theo dõi thu ngân sách'],
        ],
    ],
    [
        'slug' => 'he-thong-bao-cao',
        'title' => 'Hệ thống, Tiện ích & Báo cáo',
        'short' => 'Hệ thống',
        'owner' => 'Người 1',
        'color' => 'dark',
        'view' => 'modules.he-thong-tien-ich',
        'description' => 'Khung quản trị tài khoản, phân quyền, nhật ký thao tác, lọc động, xuất dữ liệu và dashboard phân tích.',
        'tables' => ['users', 'audit_logs'],
        'features' => [
            'Quản lý tài khoản cán bộ xã, vai trò và phạm vi thao tác.',
            'Nhật ký hệ thống ghi nhận người thực hiện, thời điểm và thay đổi dữ liệu.',
            'Bộ lọc động phục vụ thống kê dân cư, an sinh, lao động, đất đai.',
            'Khu vực chuẩn bị cho xuất Excel/PDF theo mẫu hành chính.',
        ],
        'rows' => [
            ['Tài khoản cán bộ', 'Người dùng, vai trò, trạng thái', 'Nền tảng hệ thống'],
            ['Nhật ký thao tác', 'Ai, lúc nào, làm gì, dữ liệu nào', 'Phục vụ kiểm tra'],
            ['Báo cáo tổng hợp', 'Bộ lọc, biểu đồ, xuất dữ liệu', 'Khung sẵn sàng'],
        ],
    ],
];

// Routes cho Guest (Chưa đăng nhập)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);

    Route::prefix('api')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('api.login.show');
        Route::post('login', [AuthController::class, 'login'])->name('api.login');
    });
});

// Routes cho Auth (Đã đăng nhập)
Route::middleware('auth')->group(function () use ($modules) {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('switch-user', [AuthController::class, 'switchUser'])->name('switch-user');

    Route::get('/', function () use ($modules) {
        $count = static function (string $table, int $fallback = 0): int {
            try {
                return DB::table($table)->count();
            } catch (Throwable) {
                return $fallback;
            }
        };

        $sum = static function (string $table, string $column, int $fallback = 0): int {
            try {
                return (int) DB::table($table)->sum($column);
            } catch (Throwable) {
                return $fallback;
            }
        };

        $stats = [
            ['label' => 'Hộ khẩu', 'value' => $count('ho_khau', 10), 'note' => 'Hộ đang quản lý', 'variant' => 'success'],
            ['label' => 'Nhân khẩu', 'value' => $count('nhan_khau', 33), 'note' => 'Công dân trong địa bàn', 'variant' => 'primary'],
            ['label' => 'Hồ sơ tạm trú', 'value' => $count('tam_tru_tam_vang', 1), 'note' => 'Theo dõi cư trú tạm thời', 'variant' => 'info'],
            ['label' => 'Thuế phí', 'value' => number_format($sum('thue_va_phi_dia_phuong', 'so_tien', 0), 0, ',', '.'), 'note' => 'Tổng phát sinh đã ghi nhận', 'variant' => 'warning'],
        ];

        $tasks = [
            ['title' => 'Xác minh danh sách chủ hộ mới', 'meta' => 'Hộ khẩu / Nhân khẩu', 'priority' => 'Cao'],
            ['title' => 'Đối chiếu đợt trợ cấp tháng này', 'meta' => 'Bảo trợ xã hội', 'priority' => 'Trung bình'],
            ['title' => 'Cập nhật trạng thái thu phí địa phương', 'meta' => 'Thuế và phí', 'priority' => 'Cao'],
            ['title' => 'Kiểm tra hồ sơ tạm vắng quá hạn', 'meta' => 'Cư trú', 'priority' => 'Thấp'],
        ];

        return view('welcome', compact('modules', 'stats', 'tasks'));
    })->name('dashboard');

    Route::get('/modules/{module}', function (string $module) use ($modules) {
        $selected = collect($modules)->firstWhere('slug', $module);

        abort_unless($selected, 404);

        $count = static function (string $table): int {
            try {
                return DB::table($table)->count();
            } catch (Throwable) {
                return 0;
            }
        };

        $metrics = collect($selected['tables'])
            ->map(fn (string $table): array => [
                'table' => $table,
                'label' => str_replace('_', ' ', $table),
                'count' => $count($table),
            ])
            ->all();

        $stats = [];
        $extraData = [];

        if ($module === 'ho-tich-cu-tru') {
            $stats = [
                'so_ho_khau' => $count('ho_khau'),
                'nhan_khau' => $count('nhan_khau'),
                'tam_tru' => $count('tam_tru_tam_vang'),
                'bien_dong' => $count('bien_dong_ho_khau'),
            ];

            $extraData['dsHoKhau'] = HoKhau::query()
                ->with('chuHo')
                ->latest()
                ->limit(10)
                ->get()
                ->map(function ($ho) {
                    $ho->chu_ho_ten = $ho->chuHo?->ho_ten;

                    return $ho;
                });
        } elseif ($module === 'nghia-vu-an-ninh') {
            $stats = [
                'nghia_vu_quan_su' => DB::table('nghia_vu_quan_su')->count(),
                'dan_quan_tu_ve' => DB::table('dan_quan_tu_ve')->count(),
                'du_dieu_kien' => DB::table('nghia_vu_quan_su')->where('trang_thai_nvqs', 'du_dieu_kien')->count(),
                'tam_hoan' => DB::table('nghia_vu_quan_su')->where('trang_thai_nvqs', 'tam_hoan')->count(),
            ];

            $extraData['dsNVQS'] = NghiaVuQuanSu::query()
                ->with(['nhanKhau.hoKhau'])
                ->latest()
                ->limit(10)
                ->get();
        } elseif ($module === 'kinh-te-lao-dong') {
            $stats = [
                'lao_dong' => $count('lao_dong'),
                'doanh_nghiep' => $count('doanh_nghiep_ho_kinh_doanh'),
                'ket_noi_viec_lam' => $count('ket_noi_viec_lam'),
                'xuat_khau_lao_dong' => DB::table('lao_dong')->where('xuat_khau_lao_dong', true)->count(),
            ];

            $extraData['dsLaoDong'] = LaoDong::query()
                ->with(['nhanKhau'])
                ->latest()
                ->limit(10)
                ->get()
                ->map(function ($ld) {
                    $ld->ho_ten = $ld->nhanKhau?->ho_ten;

                    return $ld;
                });
        } elseif ($module === 'an-sinh-y-te-giao-duc') {
            $stats = [
                'doi_tuong_chinh_sach' => $count('doi_tuong_chinh_sach'),
                'bao_tro_xa_hoi' => $count('bao_tro_xa_hoi'),
                'dot_tro_cap' => $count('dot_tro_cap'),
                'y_te' => $count('y_te_nhan_khau'),
            ];

            $extraData['dsDotTroCap'] = DotTroCap::query()
                ->latest()
                ->limit(10)
                ->get();
        }

        return view($selected['view'] ?? 'modules.show', array_merge(
            compact('modules', 'selected', 'metrics', 'stats'),
            $extraData
        ));
    })->name('modules.show');

    // --- Phân hệ Nghĩa vụ & An ninh quốc phòng ---
    // Nghiệp vụ thay đổi/thao tác (Chỉ cán bộ Quân sự và Admin được làm)
    Route::middleware('can:manage_nghia_vu')->group(function () {
        Route::resource('nghia-vu-an-ninh/nghia-vu-quan-su', NghiaVuQuanSuController::class)
            ->except(['index', 'show'])
            ->parameters(['nghia-vu-quan-su' => 'nghiaVuQuanSu'])
            ->names('nghia-vu-quan-su');

        Route::get('nghia-vu-an-ninh/nghia-vu-quan-su/scan-preview', [NghiaVuQuanSuController::class, 'scanPreview'])->name('nghia-vu-quan-su.scan-preview');
        Route::post('nghia-vu-an-ninh/nghia-vu-quan-su/scan-store', [NghiaVuQuanSuController::class, 'scanStore'])->name('nghia-vu-quan-su.scan-store');

        // Dân quân tự vệ (Lực lượng nòng cốt, tập huấn, trực ban)
        Route::resource('nghia-vu-an-ninh/dan-quan-tu-ve', DanQuanTuVeController::class)
            ->except(['index', 'show'])
            ->parameters(['dan-quan-tu-ve' => 'danQuanTuVe'])
            ->names('dan-quan-tu-ve');

        Route::resource('nghia-vu-an-ninh/dan-quan-hoat-dong', DanQuanHoatDongController::class)
            ->except(['index', 'show'])
            ->parameters(['dan-quan-hoat-dong' => 'danQuanHoatDong'])
            ->names('dan-quan-hoat-dong');

    });

    // API endpoints cho Hộ tịch & Cư trú (Biến động & Tạm trú/Tạm vắng & Hộ khẩu & Nhân khẩu)
    Route::prefix('api')->group(function () {
        Route::middleware('can:manage_ho_khau')->group(function () {
            Route::apiResource('ho-tich/ho-khau', HoKhauController::class)
                ->except(['index', 'show'])
                ->parameters(['ho-khau' => 'hoKhau'])
                ->names('api.ho-khau');

            Route::apiResource('ho-tich/bien-dong', BienDongHoKhauController::class)
                ->except(['index', 'show'])
                ->parameters(['bien-dong' => 'bienDong'])
                ->names('api.bien-dong');

            Route::apiResource('ho-tich/tam-tru', TamTruTamVangController::class)
                ->except(['index', 'show'])
                ->parameters(['tam-tru' => 'tamTruTamVang'])
                ->names('api.tam-tru');
        });

        Route::middleware('can:manage_nhan_khau')->group(function () {
            Route::apiResource('ho-tich/nhan-khau', NhanKhauController::class)
                ->except(['index', 'show'])
                ->parameters(['nhan-khau' => 'nhanKhau'])
                ->names('api.nhan-khau');
        });

        Route::apiResource('ho-tich/ho-khau', HoKhauController::class)
            ->only(['index', 'show'])
            ->parameters(['ho-khau' => 'hoKhau'])
            ->names('api.ho-khau');

        Route::apiResource('ho-tich/nhan-khau', NhanKhauController::class)
            ->only(['index', 'show'])
            ->parameters(['nhan-khau' => 'nhanKhau'])
            ->names('api.nhan-khau');

        Route::apiResource('ho-tich/bien-dong', BienDongHoKhauController::class)
            ->only(['index', 'show'])
            ->parameters(['bien-dong' => 'bienDong'])
            ->names('api.bien-dong');

        Route::apiResource('ho-tich/tam-tru', TamTruTamVangController::class)
            ->only(['index', 'show'])
            ->parameters(['tam-tru' => 'tamTruTamVang'])
            ->names('api.tam-tru');

        // API cho An sinh xã hội
        Route::middleware('can:manage_an_sinh')->group(function () {
            Route::apiResource('an-sinh/doi-tuong-chinh-sach', DoiTuongChinhSachController::class)
                ->except(['index', 'show'])
                ->parameters(['doi-tuong-chinh-sach' => 'doiTuongChinhSach'])
                ->names('api.doi-tuong-chinh-sach');

            Route::apiResource('an-sinh/bao-tro-xa-hoi', BaoTroXaHoiController::class)
                ->except(['index', 'show'])
                ->parameters(['bao-tro-xa-hoi' => 'baoTroXaHoi'])
                ->names('api.bao-tro-xa-hoi');

            Route::apiResource('an-sinh/dot-tro-cap', DotTroCapController::class)
                ->except(['index', 'show'])
                ->parameters(['dot-tro-cap' => 'dotTroCap'])
                ->names('api.dot-tro-cap');

            Route::post('an-sinh/dot-tro-cap/{dotTroCap}/confirm/{detailId}', [DotTroCapController::class, 'confirmReceipt'])->name('api.dot-tro-cap.confirm');
            Route::post('an-sinh/dot-tro-cap/{dotTroCap}/confirm-batch', [DotTroCapController::class, 'confirmReceiptBatch'])->name('api.dot-tro-cap.confirm-batch');
            Route::post('an-sinh/dot-tro-cap/{dotTroCap}/add-recipient', [DotTroCapController::class, 'addRecipient'])->name('api.dot-tro-cap.add-recipient');
            Route::delete('an-sinh/dot-tro-cap/{dotTroCap}/remove-recipient/{detailId}', [DotTroCapController::class, 'removeRecipient'])->name('api.dot-tro-cap.remove-recipient');
        });

        Route::apiResource('an-sinh/doi-tuong-chinh-sach', DoiTuongChinhSachController::class)
            ->only(['index', 'show'])
            ->parameters(['doi-tuong-chinh-sach' => 'doiTuongChinhSach'])
            ->names('api.doi-tuong-chinh-sach');

        Route::apiResource('an-sinh/bao-tro-xa-hoi', BaoTroXaHoiController::class)
            ->only(['index', 'show'])
            ->parameters(['bao-tro-xa-hoi' => 'baoTroXaHoi'])
            ->names('api.bao-tro-xa-hoi');

        Route::apiResource('an-sinh/dot-tro-cap', DotTroCapController::class)
            ->only(['index', 'show'])
            ->parameters(['dot-tro-cap' => 'dotTroCap'])
            ->names('api.dot-tro-cap');

        // API cho Quản lý Cán bộ (Users)
        Route::middleware('can:manage_users')->group(function () {
            Route::apiResource('he-thong/users', UserController::class)
                ->except(['show'])
                ->names('api.users');
            Route::post('he-thong/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
                ->name('api.users.toggle-status');
        });
        Route::get('he-thong/users/{user}', [UserController::class, 'show'])
            ->name('api.users.show');

        // API cho Dân quân tự vệ
        Route::middleware('can:manage_nghia_vu')->group(function () {
            Route::apiResource('nghia-vu-an-ninh/dan-quan-tu-ve', DanQuanTuVeController::class)
                ->except(['index', 'show'])
                ->parameters(['dan-quan-tu-ve' => 'danQuanTuVe'])
                ->names('api.dan-quan-tu-ve');
        });

        Route::apiResource('nghia-vu-an-ninh/dan-quan-tu-ve', DanQuanTuVeController::class)
            ->only(['index', 'show'])
            ->parameters(['dan-quan-tu-ve' => 'danQuanTuVe'])
            ->names('api.dan-quan-tu-ve');

        // API cho Kinh tế, Lao động & Việc làm
        Route::middleware('can:manage_lao_dong')->group(function () {
            Route::apiResource('lao-dong/ho-so', LaoDongController::class)
                ->except(['index', 'show'])
                ->parameters(['ho-so' => 'hoSo'])
                ->names('api.ho-so');

            Route::apiResource('lao-dong/doanh-nghiep', DoanhNghiepController::class)
                ->except(['index', 'show'])
                ->parameters(['doanh-nghiep' => 'doanhNghiep'])
                ->names('api.doanh-nghiep');

            Route::apiResource('lao-dong/ket-noi', KetNoiViecLamController::class)
                ->except(['index', 'show'])
                ->parameters(['ket-noi' => 'ketNoi'])
                ->names('api.ket-noi');
        });

        Route::middleware('can:view_lao_dong')->group(function () {
            Route::apiResource('lao-dong/ho-so', LaoDongController::class)
                ->only(['index', 'show'])
                ->parameters(['ho-so' => 'hoSo'])
                ->names('api.ho-so');

            Route::apiResource('lao-dong/doanh-nghiep', DoanhNghiepController::class)
                ->only(['index', 'show'])
                ->parameters(['doanh-nghiep' => 'doanhNghiep'])
                ->names('api.doanh-nghiep');

            Route::apiResource('lao-dong/ket-noi', KetNoiViecLamController::class)
                ->only(['index', 'show'])
                ->parameters(['ket-noi' => 'ketNoi'])
                ->names('api.ket-noi');
        });

        // API cho Xác thực (Đã đăng nhập)
        Route::post('logout', [AuthController::class, 'logout'])->name('api.logout');
        Route::post('switch-user', [AuthController::class, 'switchUser'])->name('api.switch-user');
    });

    // Đọc danh sách và chi tiết (Tất cả cán bộ được xem chéo)
    Route::resource('nghia-vu-an-ninh/nghia-vu-quan-su', NghiaVuQuanSuController::class)
        ->only(['index', 'show'])
        ->parameters(['nghia-vu-quan-su' => 'nghiaVuQuanSu'])
        ->names('nghia-vu-quan-su');

    Route::resource('nghia-vu-an-ninh/dan-quan-tu-ve', DanQuanTuVeController::class)
        ->only(['index', 'show'])
        ->parameters(['dan-quan-tu-ve' => 'danQuanTuVe'])
        ->names('dan-quan-tu-ve');

    Route::resource('nghia-vu-an-ninh/dan-quan-hoat-dong', DanQuanHoatDongController::class)
        ->only(['index', 'show'])
        ->parameters(['dan-quan-hoat-dong' => 'danQuanHoatDong'])
        ->names('dan-quan-hoat-dong');

    // API phục vụ autocomplete
    Route::get('api/nghia-vu-quan-su/eligible-citizens', [NghiaVuQuanSuController::class, 'eligibleCitizens'])
        ->name('nghia-vu-quan-su.eligible-citizens')
        ->middleware('can:view_nghia_vu');

    Route::get('api/dan-quan-hoat-dong/eligible-militia', [DanQuanHoatDongController::class, 'eligibleMilitia'])
        ->name('dan-quan-hoat-dong.eligible-militia')
        ->middleware('can:view_nghia_vu');

    // --- Phân hệ Hộ tịch & Cư trú (Hộ khẩu & Nhân khẩu) ---
    // Web endpoints phục vụ giao diện Blade
    Route::middleware('can:manage_ho_khau')->group(function () {
        Route::resource('ho-tich/ho-khau', HoKhauController::class)
            ->except(['index', 'show'])
            ->parameters(['ho-khau' => 'hoKhau'])
            ->names('ho-khau');

        Route::resource('ho-tich/bien-dong', BienDongHoKhauController::class)
            ->except(['index', 'show'])
            ->parameters(['bien-dong' => 'bienDong'])
            ->names('bien-dong');

        Route::resource('ho-tich/tam-tru', TamTruTamVangController::class)
            ->except(['index', 'show'])
            ->parameters(['tam-tru' => 'tamTruTamVang'])
            ->names('tam-tru');
    });

    Route::middleware('can:manage_nhan_khau')->group(function () {
        Route::resource('ho-tich/nhan-khau', NhanKhauController::class)
            ->except(['index', 'show'])
            ->parameters(['nhan-khau' => 'nhanKhau'])
            ->names('nhan-khau');
    });

    Route::resource('ho-tich/ho-khau', HoKhauController::class)
        ->only(['index', 'show'])
        ->parameters(['ho-khau' => 'hoKhau'])
        ->names('ho-khau');

    Route::resource('ho-tich/nhan-khau', NhanKhauController::class)
        ->only(['index', 'show'])
        ->parameters(['nhan-khau' => 'nhanKhau'])
        ->names('nhan-khau');

    Route::resource('ho-tich/bien-dong', BienDongHoKhauController::class)
        ->only(['index', 'show'])
        ->parameters(['bien-dong' => 'bienDong'])
        ->names('bien-dong');

    Route::resource('ho-tich/tam-tru', TamTruTamVangController::class)
        ->only(['index', 'show'])
        ->parameters(['tam-tru' => 'tamTruTamVang'])
        ->names('tam-tru');

    // --- Phân hệ An sinh xã hội (Đối tượng chính sách, Bảo trợ xã hội, Đợt trợ cấp) ---
    // Nghiệp vụ thay đổi/thao tác (Chỉ cán bộ Lao động và Admin được làm)
    Route::middleware('can:manage_an_sinh')->group(function () {
        Route::resource('an-sinh/doi-tuong-chinh-sach', DoiTuongChinhSachController::class)
            ->except(['index', 'show'])
            ->parameters(['doi-tuong-chinh-sach' => 'doiTuongChinhSach'])
            ->names('doi-tuong-chinh-sach');

        Route::resource('an-sinh/bao-tro-xa-hoi', BaoTroXaHoiController::class)
            ->except(['index', 'show'])
            ->parameters(['bao-tro-xa-hoi' => 'baoTroXaHoi'])
            ->names('bao-tro-xa-hoi');

        Route::resource('an-sinh/dot-tro-cap', DotTroCapController::class)
            ->except(['index', 'show'])
            ->parameters(['dot-tro-cap' => 'dotTroCap'])
            ->names('dot-tro-cap');

        Route::post('an-sinh/dot-tro-cap/{dotTroCap}/confirm/{detailId}', [DotTroCapController::class, 'confirmReceipt'])->name('dot-tro-cap.confirm');
        Route::post('an-sinh/dot-tro-cap/{dotTroCap}/confirm-batch', [DotTroCapController::class, 'confirmReceiptBatch'])->name('dot-tro-cap.confirm-batch');
        Route::post('an-sinh/dot-tro-cap/{dotTroCap}/add-recipient', [DotTroCapController::class, 'addRecipient'])->name('dot-tro-cap.add-recipient');
        Route::delete('an-sinh/dot-tro-cap/{dotTroCap}/remove-recipient/{detailId}', [DotTroCapController::class, 'removeRecipient'])->name('dot-tro-cap.remove-recipient');
    });

    // Đọc danh sách và chi tiết (Tất cả cán bộ được xem chéo)
    Route::resource('an-sinh/doi-tuong-chinh-sach', DoiTuongChinhSachController::class)
        ->only(['index', 'show'])
        ->parameters(['doi-tuong-chinh-sach' => 'doiTuongChinhSach'])
        ->names('doi-tuong-chinh-sach');

    Route::resource('an-sinh/bao-tro-xa-hoi', BaoTroXaHoiController::class)
        ->only(['index', 'show'])
        ->parameters(['bao-tro-xa-hoi' => 'baoTroXaHoi'])
        ->names('bao-tro-xa-hoi');

    Route::resource('an-sinh/dot-tro-cap', DotTroCapController::class)
        ->only(['index', 'show'])
        ->parameters(['dot-tro-cap' => 'dotTroCap'])
        ->names('dot-tro-cap');

    // --- Phân hệ Kinh tế, Lao động & Việc làm ---
    // Nghiệp vụ thay đổi/thao tác (Chỉ cán bộ Lao động/Admin được làm)
    Route::middleware('can:manage_lao_dong')->group(function () {
        Route::resource('lao-dong/ho-so', LaoDongController::class)
            ->except(['index', 'show'])
            ->parameters(['ho-so' => 'hoSo'])
            ->names('ho-so');

        Route::resource('lao-dong/doanh-nghiep', DoanhNghiepController::class)
            ->except(['index', 'show'])
            ->parameters(['doanh-nghiep' => 'doanhNghiep'])
            ->names('doanh-nghiep');

        Route::resource('lao-dong/ket-noi', KetNoiViecLamController::class)
            ->except(['index', 'show'])
            ->parameters(['ket-noi' => 'ketNoi'])
            ->names('ket-noi');

        // AJAX endpoints phục vụ auto-matching (được phép chỉnh sửa/tạo)
        Route::get('api/lao-dong/ho-so/{laoDong}/jobs', [KetNoiViecLamController::class, 'getJobsForLabor'])
            ->name('lao-dong.ho-so.jobs');
        Route::get('api/lao-dong/doanh-nghiep/{doanhNghiep}/labors', [KetNoiViecLamController::class, 'getLaborsForJob'])
            ->name('lao-dong.doanh-nghiep.labors');
    });

    // Đọc danh sách và chi tiết (Tất cả cán bộ được xem chéo)
    Route::middleware('can:view_lao_dong')->group(function () {
        Route::resource('lao-dong/ho-so', LaoDongController::class)
            ->only(['index', 'show'])
            ->parameters(['ho-so' => 'hoSo'])
            ->names('ho-so');

        Route::resource('lao-dong/doanh-nghiep', DoanhNghiepController::class)
            ->only(['index', 'show'])
            ->parameters(['doanh-nghiep' => 'doanhNghiep'])
            ->names('doanh-nghiep');

        Route::resource('lao-dong/ket-noi', KetNoiViecLamController::class)
            ->only(['index', 'show'])
            ->parameters(['ket-noi' => 'ketNoi'])
            ->names('ket-noi');
    });

    // --- Phân quyền RBAC ---
    Route::get('/he-thong/phan-quyen', function () {
        $modules = ModuleRegistry::all();
        $roles = Role::all();
        $permissions = Permission::all();

        $groupedPermissions = [
            'Hệ thống & Người dùng' => $permissions->filter(fn ($p) => in_array($p->name, ['manage_users', 'view_audit_logs'])),
            'Hộ tịch & Cư trú' => $permissions->filter(fn ($p) => str_contains($p->name, 'ho_khau') || str_contains($p->name, 'nhan_khau')),
            'Kinh tế & Lao động' => $permissions->filter(fn ($p) => str_contains($p->name, 'lao_dong')),
            'An sinh xã hội' => $permissions->filter(fn ($p) => str_contains($p->name, 'an_sinh')),
            'Nghĩa vụ quân sự' => $permissions->filter(fn ($p) => str_contains($p->name, 'nghia_vu')),
            'Đất đai & Thuế phí' => $permissions->filter(fn ($p) => str_contains($p->name, 'dat_dai')),
        ];

        return view('he-thong.rbac', compact('modules', 'roles', 'permissions', 'groupedPermissions'));
    })->name('he-thong.rbac');

    Route::post('/he-thong/phan-quyen/toggle', function (Request $request) {
        if (! auth()->user()->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền thực hiện hành động này.'], 403);
        }

        $roleId = $request->input('role_id');
        $permissionId = $request->input('permission_id');

        $role = Role::findOrFail($roleId);
        $permission = Permission::findOrFail($permissionId);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        if ($role->hasPermissionTo($permission)) {
            if ($role->name === 'admin' && in_array($permission->name, ['manage_users', 'view_audit_logs'])) {
                return response()->json(['success' => false, 'message' => 'Không thể huỷ quyền hệ thống cốt lõi của vai trò Admin.'], 400);
            }
            $role->revokePermissionTo($permission);
            $active = false;
        } else {
            $role->givePermissionTo($permission);
            $active = true;
        }

        return response()->json([
            'success' => true,
            'active' => $active,
            'message' => 'Cập nhật quyền thành công.',
        ]);
    })->name('he-thong.rbac.toggle');

    // --- Quản lý Cán bộ ---
    Route::middleware('can:manage_users')->group(function () {
        Route::resource('he-thong/users', UserController::class)->except(['show'])->names('users');
        Route::post('he-thong/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });

    Route::get('he-thong/users/{user}', [UserController::class, 'show'])->name('users.show');

    // --- Nhật ký hệ thống ---
    Route::middleware('can:view_audit_logs')->group(function () {
        Route::get('he-thong/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('he-thong/audit-logs/{auditLog}', [AuditLogController::class, 'show'])->name('audit-logs.show');
    });

    // --- Dashboard & Biểu đồ trực quan ---
    Route::get('he-thong/dashboard-bieu-do', [BieuDoController::class, 'index'])->name('he-thong.dashboard-bieu-do');
});
