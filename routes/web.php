<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NghiaVuQuanSuController;


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
        'tables' => ['nghia_vu_quan_su', 'dan_quan_tu_ve'],
        'features' => [
            'Lọc danh sách nam công dân trong độ tuổi nghĩa vụ quân sự.',
            'Theo dõi trạng thái: đủ điều kiện, tạm hoãn, trúng tuyển, nhập ngũ, xuất ngũ.',
            'Quản lý lực lượng dân quân tự vệ và các đợt tập huấn.',
            'Chuẩn bị khu vực theo dõi vi phạm hành chính, đối tượng quản lý đặc biệt.',
        ],
        'rows' => [
            ['Nghĩa vụ quân sự', 'Độ tuổi, sức khỏe, học vấn, trạng thái', 'Theo mùa tuyển quân'],
            ['Dân quân tự vệ', 'Lực lượng nòng cốt, tập huấn, trực ban', 'Theo kế hoạch xã'],
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

Route::get('/', function () use ($modules) {
    $count = static function (string $table, int $fallback = 0): int {
        try {
            return DB::table($table)->count();
        } catch (\Throwable) {
            return $fallback;
        }
    };

    $sum = static function (string $table, string $column, int $fallback = 0): int {
        try {
            return (int) DB::table($table)->sum($column);
        } catch (\Throwable) {
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
        } catch (\Throwable) {
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

    return view($selected['view'] ?? 'modules.show', compact('modules', 'selected', 'metrics', 'stats'));
})->name('modules.show');

// Routes cho Module Quản lý Nghĩa vụ & An ninh quốc phòng (NVQS)
Route::prefix('api')->group(function () {
    Route::post('nghia-vu-quan-su/scan', [NghiaVuQuanSuController::class, 'scan'])->name('nghia-vu-quan-su.scan');
    Route::apiResource('nghia-vu-quan-su', NghiaVuQuanSuController::class);
});

