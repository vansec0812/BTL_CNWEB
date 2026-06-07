<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Hệ thống quản lý hộ dân')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --admin-green: #0f5132;
            --admin-green-soft: #e9f5ef;
            --admin-blue: #0d6efd;
            --admin-bg: #f4f6f3;
        }

        body {
            background: var(--admin-bg);
        }

        .app-shell {
            min-height: 100vh;
        }

        .app-sidebar {
            background: #ffffff;
            border-right: 1px solid rgba(15, 81, 50, .12);
        }

        .brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--admin-green);
            color: #ffffff;
        }

        .nav-pills .nav-link {
            color: #405047;
        }

        .nav-pills .nav-link.active {
            background: var(--admin-green-soft);
            color: var(--admin-green);
            font-weight: 700;
        }

        .page-hero {
            background: linear-gradient(135deg, #0f5132, #146c43 58%, #0b5ed7);
        }

        .module-card {
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .module-card:hover {
            box-shadow: 0 .75rem 1.5rem rgba(20, 64, 45, .12);
            transform: translateY(-2px);
        }

        .stat-card {
            border: 0;
            box-shadow: 0 .25rem .75rem rgba(20, 64, 45, .08);
        }

        .stat-icon {
            color: var(--admin-green);
            font-size: 1.5rem;
        }

        /* Sidebar Submenu and Collapsible Dropdown Styling */
        .sidebar-divider {
            height: 1px;
            background: rgba(15, 81, 50, 0.08);
            margin: 0.75rem 0;
        }

        .nav-pills .nav-link {
            color: #405047;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.65rem 1rem;
            border-radius: 8px;
            transition: all 0.25s ease-in-out;
            font-weight: 550;
        }

        .nav-pills .nav-link:hover {
            background: rgba(15, 81, 50, 0.05);
            color: var(--admin-green);
        }

        .nav-pills .nav-link.active {
            background: var(--admin-green-soft);
            color: var(--admin-green);
            font-weight: 700;
        }

        /* Chevron Icon Smooth Rotation */
        .nav-link .chevron-icon {
            transition: transform 0.25s ease;
            font-size: 0.75rem;
        }

        .nav-link[aria-expanded="true"] .chevron-icon {
            transform: rotate(180deg);
        }

        /* Submenu container styles */
        .submenu-container {
            margin-left: 1.25rem;
            padding-left: 0.75rem;
            border-left: 1.5px dashed rgba(15, 81, 50, 0.12);
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            margin-top: 0.25rem;
            margin-bottom: 0.5rem;
        }

        .submenu-link {
            font-size: 0.85rem;
            color: #5c6c62;
            padding: 0.45rem 0.75rem;
            border-radius: 6px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease-in-out;
            font-weight: 500;
        }

        .submenu-link:hover {
            color: var(--admin-green);
            background: var(--admin-green-soft);
        }

        .submenu-link.active {
            color: var(--admin-green);
            background: var(--admin-green-soft);
            font-weight: 600;
        }

        .submenu-link.disabled-link {
            color: #a0b0a6;
            cursor: not-allowed;
            pointer-events: none;
            opacity: 0.7;
        }

        .submenu-link.disabled-link:hover {
            background: transparent;
            color: #a0b0a6;
        }

        .profile-link {
            transition: all 0.2s ease-in-out;
        }
        .profile-link:hover {
            opacity: 0.85;
            transform: scale(1.02);
        }
    </style>
</head>
<body>
    <div class="app-shell d-lg-flex">
        <aside class="app-sidebar shrink p-3 p-lg-4" style="width: 300px;">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-3 mb-4 text-decoration-none text-dark">
                <span class="brand-mark d-inline-flex align-items-center justify-content-center fw-bold">QO</span>
                <span>
                    <span class="d-block fw-bold">UBND Xã Quốc Oai</span>
                    <small class="text-secondary">Quản lý thông tin hộ dân</small>
                </span>
            </a>

            <nav class="nav nav-pills flex-column gap-1">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <span><i class="bi bi-house-door me-2"></i>Tổng quan điều hành</span>
                </a>
                
                @foreach ($modules as $module)
                    @php
                        $slug = $module['slug'];
                        $isHoTich = $slug === 'ho-tich-cu-tru';
                        $isAnSinh = $slug === 'an-sinh-y-te-giao-duc';
                        $isKinhTe = $slug === 'kinh-te-lao-dong';
                        $isNghiaVu = $slug === 'nghia-vu-an-ninh';
                        $isDatDai = $slug === 'dat-dai-ha-tang';
                        $isHeThong = $slug === 'he-thong-bao-cao';

                        $isModuleActive = request()->routeIs('modules.show') && request()->route('module') === $slug;
                        
                        $isActive = false;
                        $submenu = [];

                        if ($isHoTich) {
                            $isHoKhauActive = request()->routeIs('ho-khau.*');
                            $isNhanKhauActive = request()->routeIs('nhan-khau.*');
                            $isBienDongActive = request()->routeIs('bien-dong.*');
                            $isTamTruActive = request()->routeIs('tam-tru.*');
                            $isActive = $isModuleActive || $isHoKhauActive || $isNhanKhauActive || $isBienDongActive || $isTamTruActive;
                            
                            $submenu = [
                                ['title' => 'Tổng quan', 'url' => route('modules.show', 'ho-tich-cu-tru'), 'icon' => 'bi-speedometer2', 'active' => $isModuleActive],
                                ['title' => 'Danh sách sổ hộ khẩu', 'url' => route('ho-khau.index'), 'icon' => 'bi-journal-text', 'active' => request()->routeIs('ho-khau.index') || request()->routeIs('ho-khau.edit') || request()->routeIs('ho-khau.create')],
                                ['title' => 'Danh sách nhân khẩu', 'url' => route('nhan-khau.index'), 'icon' => 'bi-people', 'active' => request()->routeIs('nhan-khau.index') || request()->routeIs('nhan-khau.edit') || request()->routeIs('nhan-khau.create')],
                                ['title' => 'Biến động hộ khẩu', 'url' => route('bien-dong.index'), 'icon' => 'bi-arrow-left-right', 'active' => request()->routeIs('bien-dong.*')],
                                ['title' => 'Khai báo tạm trú / tạm vắng', 'url' => route('tam-tru.index'), 'icon' => 'bi-luggage', 'active' => request()->routeIs('tam-tru.*')],
                            ];
                        } elseif ($isKinhTe) {
                            $isHoSoActive = request()->routeIs('ho-so.*');
                            $isDoanhNghiepActive = request()->routeIs('doanh-nghiep.*');
                            $isKetNoiActive = request()->routeIs('ket-noi.*');
                            $isActive = $isModuleActive || $isHoSoActive || $isDoanhNghiepActive || $isKetNoiActive;
                            
                            $submenu = [
                                ['title' => 'Tổng quan', 'url' => route('modules.show', 'kinh-te-lao-dong'), 'icon' => 'bi-speedometer2', 'active' => $isModuleActive],
                                ['title' => 'Hồ sơ lao động', 'url' => route('ho-so.index'), 'icon' => 'bi-person-workspace', 'active' => $isHoSoActive],
                                ['title' => 'Doanh nghiệp & Hộ kinh doanh', 'url' => route('doanh-nghiep.index'), 'icon' => 'bi-building', 'active' => $isDoanhNghiepActive],
                                ['title' => 'Kết nối việc làm', 'url' => route('ket-noi.index'), 'icon' => 'bi-link-45deg', 'active' => $isKetNoiActive],
                            ];
                        } elseif ($isAnSinh) {
                            $isPolicyActive = request()->routeIs('doi-tuong-chinh-sach.*');
                            $isBaoTroActive = request()->routeIs('bao-tro-xa-hoi.*');
                            $isDotActive = request()->routeIs('dot-tro-cap.*');
                            $isActive = $isModuleActive || $isPolicyActive || $isBaoTroActive || $isDotActive;
                            
                            $submenu = [
                                ['title' => 'Tổng quan', 'url' => route('modules.show', 'an-sinh-y-te-giao-duc'), 'icon' => 'bi-speedometer2', 'active' => $isModuleActive],
                                ['title' => 'Đối tượng chính sách', 'url' => route('doi-tuong-chinh-sach.index'), 'icon' => 'bi-list-ul', 'active' => request()->routeIs('doi-tuong-chinh-sach.*')],
                                ['title' => 'Bảo trợ xã hội', 'url' => route('bao-tro-xa-hoi.index'), 'icon' => 'bi-shield', 'active' => request()->routeIs('bao-tro-xa-hoi.*')],
                                ['title' => 'Đợt trợ cấp & Quỹ từ thiện', 'url' => route('dot-tro-cap.index'), 'icon' => 'bi-gift', 'active' => $isDotActive],
                                ['title' => 'Theo dõi Y tế & Giáo dục', 'disabled' => true, 'icon' => 'bi-heart-pulse'],
                            ];
                        } elseif ($isNghiaVu) {
                            $isNghiaVuActive = request()->routeIs('nghia-vu-quan-su.*');
                            $isDanQuanActive = request()->routeIs('dan-quan-tu-ve.*');
                            $isActive = $isModuleActive || $isNghiaVuActive || $isDanQuanActive;
                            $submenu = [
                                ['title' => 'Tổng quan', 'url' => route('modules.show', 'nghia-vu-an-ninh'), 'icon' => 'bi-speedometer2', 'active' => $isModuleActive],
                                ['title' => 'Quản lý Nghĩa vụ quân sự', 'url' => route('nghia-vu-quan-su.index'), 'icon' => 'bi-person-check', 'active' => $isNghiaVuActive],
                                ['title' => 'Lực lượng dân quân tự vệ', 'url' => route('dan-quan-tu-ve.index'), 'icon' => 'bi-people-fill', 'active' => $isDanQuanActive],
                                ['title' => 'An ninh trật tự', 'disabled' => true, 'icon' => 'bi-shield-exclamation'],
                            ];
                        } elseif ($isDatDai) {
                            $isActive = $isModuleActive;
                            $submenu = [
                                ['title' => 'Tổng quan', 'url' => route('modules.show', 'dat-dai-ha-tang'), 'icon' => 'bi-speedometer2', 'active' => $isModuleActive],
                                ['title' => 'Đất đai & Tài sản', 'disabled' => true, 'icon' => 'bi-map'],
                                ['title' => 'Hạ tầng địa bàn', 'disabled' => true, 'icon' => 'bi-signpost-split'],
                                ['title' => 'Thuế & Phí địa phương', 'disabled' => true, 'icon' => 'bi-cash-coin'],
                            ];
                        } elseif ($isHeThong) {
                            $isActive = $isModuleActive || request()->routeIs('he-thong.rbac') || request()->routeIs('users.*');
                            $submenu = [
                                ['title' => 'Tổng quan', 'url' => route('modules.show', 'he-thong-bao-cao'), 'icon' => 'bi-speedometer2', 'active' => $isModuleActive],
                            ];
                            if (auth()->user()->can('manage_users')) {
                                $submenu[] = ['title' => 'Tài khoản cán bộ', 'url' => route('users.index'), 'icon' => 'bi-person-badge', 'active' => request()->routeIs('users.*')];
                            }
                            $submenu = array_merge($submenu, [
                                ['title' => 'Phân quyền (RBAC)', 'url' => route('he-thong.rbac'), 'icon' => 'bi-shield-lock', 'active' => request()->routeIs('he-thong.rbac')],
                                ['title' => 'Nhật ký hệ thống (Audit)', 'disabled' => true, 'icon' => 'bi-clock-history'],
                                ['title' => 'Dashboard & Biểu đồ', 'disabled' => true, 'icon' => 'bi-graph-up'],
                                ['title' => 'Bộ lọc động & Tìm kiếm', 'disabled' => true, 'icon' => 'bi-funnel'],
                                ['title' => 'Xuất báo cáo Excel', 'disabled' => true, 'icon' => 'bi-file-earmark-excel'],
                                ['title' => 'Xuất báo cáo PDF', 'disabled' => true, 'icon' => 'bi-file-earmark-pdf'],
                                ['title' => 'Cấu hình hệ thống', 'disabled' => true, 'icon' => 'bi-gear'],
                            ]);
                        }

                        $iconClass = 'bi-grid';
                        if ($isHoTich) {
                            $iconClass = 'bi-journal-text';
                        } elseif ($isKinhTe) {
                            $iconClass = 'bi-briefcase';
                        } elseif ($isAnSinh) {
                            $iconClass = 'bi-heart-pulse';
                        } elseif ($isNghiaVu) {
                            $iconClass = 'bi-shield-check';
                        } elseif ($isDatDai) {
                            $iconClass = 'bi-geo-alt';
                        } elseif ($isHeThong) {
                            $iconClass = 'bi-gear';
                        }
                    @endphp

                    <div class="nav-item">
                        <a class="nav-link {{ $isActive ? 'active' : '' }}" 
                           data-bs-toggle="collapse" 
                           href="#submenu-{{ $slug }}" 
                           role="button" 
                           aria-expanded="{{ $isActive ? 'true' : 'false' }}" 
                           aria-controls="submenu-{{ $slug }}">
                            <span>
                                <i class="bi {{ $iconClass }} me-2"></i>
                                {{ $module['title'] }}
                            </span>
                            <i class="bi bi-chevron-down chevron-icon"></i>
                        </a>
                        <div class="collapse {{ $isActive ? 'show' : '' }}" id="submenu-{{ $slug }}">
                            <div class="submenu-container">
                                @foreach($submenu as $item)
                                    @if(isset($item['disabled']) && $item['disabled'])
                                        <span class="submenu-link disabled-link">
                                            <i class="bi {{ $item['icon'] }}"></i> {{ $item['title'] }} <small class="text-muted">(Phát triển sau)</small>
                                        </span>
                                    @else
                                        <a class="submenu-link {{ $item['active'] ? 'active' : '' }}" href="{{ $item['url'] }}">
                                            <i class="bi {{ $item['icon'] }}"></i> {{ $item['title'] }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </nav>

            <div class="mt-4 rounded-3 bg-light border p-3 small text-secondary">
                <div class="fw-semibold text-dark">Môi trường hiện tại</div>
                <div>{{ app()->environment() }} / Laravel {{ app()->version() }}</div>
            </div>
        </aside>

        <main class="grow">
            <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top py-2">
                <div class="container-fluid px-3 px-lg-4">
                    <span class="navbar-brand mb-0 h1">@yield('page_title', 'Bảng điều khiển')</span>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topActions" aria-controls="topActions" aria-expanded="false" aria-label="Bật tắt thanh công cụ">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="topActions">
                        <div class="ms-auto d-flex align-items-center gap-3 pt-3 pt-lg-0">
                            @php
                                $allUsersForSwitcher = \App\Models\User::with('roles')->get();
                                $currentUser = auth()->user();
                            @endphp
                            @if($currentUser)
                                <form action="{{ route('switch-user') }}" method="POST" class="d-flex align-items-center gap-2">
                                    @csrf
                                    <label for="quick_user_select" class="small fw-semibold text-secondary text-nowrap mb-0">
                                        <i class="bi bi-arrow-left-right text-success me-1"></i>Chuyển vai trò:
                                    </label>
                                    <select name="user_id" id="quick_user_select" class="form-select form-select-sm" style="width: auto; max-width: 220px; border-radius: 8px;" onchange="this.form.submit()">
                                        @foreach($allUsersForSwitcher as $u)
                                            <option value="{{ $u->id }}" {{ $currentUser->id === $u->id ? 'selected' : '' }}>
                                                {{ $u->name }} ({{ $u->roles->first()?->name === 'admin' ? 'Admin' : ($u->roles->first()?->name === 'tu_phap' ? 'Tư pháp' : ($u->roles->first()?->name === 'lao_dong' ? 'Lao động' : ($u->roles->first()?->name === 'dia_chinh' ? 'Địa chính' : ($u->roles->first()?->name === 'quan_su' ? 'Quân sự' : 'Trưởng thôn')))) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </form>

                                <div class="vr text-secondary opacity-25 d-none d-lg-block" style="height: 24px;"></div>

                                <a href="{{ route('users.show', $currentUser->id) }}" class="d-flex align-items-center gap-2 text-decoration-none profile-link">
                                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center text-success fw-bold" style="width: 34px; height: 34px; font-size: 0.85rem;">
                                        {{ substr($currentUser->name, 0, 1) }}
                                    </div>
                                    <div class="text-start">
                                        <span class="d-block fw-bold small text-dark lh-sm">{{ $currentUser->name }}</span>
                                        <span class="badge bg-success bg-opacity-10 text-success fw-semibold" style="font-size: 0.65rem; padding: 2px 6px;">
                                            {{ $currentUser->roles->first()?->name === 'admin' ? 'Quản trị viên' : ($currentUser->roles->first()?->name === 'tu_phap' ? 'Cán bộ Tư pháp' : ($currentUser->roles->first()?->name === 'lao_dong' ? 'Cán bộ Lao động' : ($currentUser->roles->first()?->name === 'dia_chinh' ? 'Cán bộ Địa chính' : ($currentUser->roles->first()?->name === 'quan_su' ? 'Cán bộ Quân sự' : 'Trưởng thôn')))) }}
                                        </span>
                                    </div>
                                </a>

                                <div class="vr text-secondary opacity-25 d-none d-lg-block" style="height: 24px;"></div>

                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1" style="border-radius: 8px;" type="submit">
                                        <i class="bi bi-box-arrow-right"></i>
                                        <span>Đăng xuất</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </nav>

            <div class="container-fluid p-3 p-lg-4">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    @include('layouts._delete_confirm_modal')
</body>
</html>
