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
    </style>
</head>
<body>
    <div class="app-shell d-lg-flex">
        <aside class="app-sidebar flex-shrink-0 p-3 p-lg-4" style="width: 300px;">
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
                        $isHoTich = $module['slug'] === 'ho-tich-cu-tru';
                        $isAnSinh = $module['slug'] === 'an-sinh-y-te-giao-duc';
                        
                        $isModuleActive = request()->routeIs('modules.show') && request()->route('module') === $module['slug'];
                        $isPolicyActive = request()->routeIs('doi-tuong-chinh-sach.*') && $module['slug'] === 'an-sinh-y-te-giao-duc';
                        $isHoKhauActive = request()->routeIs('ho-khau.*') && $module['slug'] === 'ho-tich-cu-tru';
                        $isNhanKhauActive = request()->routeIs('nhan-khau.*') && $module['slug'] === 'ho-tich-cu-tru';
                        
                        $isActive = $isModuleActive || ($isHoTich && ($isHoKhauActive || $isNhanKhauActive)) || ($isAnSinh && $isPolicyActive);
                    @endphp

                    @if ($isHoTich)
                        <div class="nav-item">
                            <a class="nav-link {{ $isActive ? 'active' : '' }}" 
                               data-bs-toggle="collapse" 
                               href="#submenu-{{ $module['slug'] }}" 
                               role="button" 
                               aria-expanded="{{ $isActive ? 'true' : 'false' }}" 
                               aria-controls="submenu-{{ $module['slug'] }}">
                                <span>
                                    <i class="bi bi-journal-text me-2"></i>
                                    {{ $module['title'] }}
                                </span>
                                <i class="bi bi-chevron-down chevron-icon"></i>
                            </a>
                            <div class="collapse {{ $isActive ? 'show' : '' }}" id="submenu-{{ $module['slug'] }}">
                                <div class="submenu-container">
                                    <a class="submenu-link {{ $isModuleActive ? 'active' : '' }}" href="{{ route('modules.show', $module['slug']) }}">
                                        <i class="bi bi-speedometer2"></i> Tổng quan
                                    </a>
                                    <a class="submenu-link {{ request()->routeIs('ho-khau.index') || request()->routeIs('ho-khau.edit') ? 'active' : '' }}" href="{{ route('ho-khau.index') }}">
                                        <i class="bi bi-journal-text"></i> Danh sách sổ hộ khẩu
                                    </a>
                                    <a class="submenu-link {{ request()->routeIs('ho-khau.create') ? 'active' : '' }}" href="{{ route('ho-khau.create') }}">
                                        <i class="bi bi-plus-lg"></i> Thêm mới hộ khẩu
                                    </a>
                                    <a class="submenu-link {{ request()->routeIs('nhan-khau.index') || request()->routeIs('nhan-khau.edit') ? 'active' : '' }}" href="{{ route('nhan-khau.index') }}">
                                        <i class="bi bi-people"></i> Danh sách nhân khẩu
                                    </a>
                                    <a class="submenu-link {{ request()->routeIs('nhan-khau.create') ? 'active' : '' }}" href="{{ route('nhan-khau.create') }}">
                                        <i class="bi bi-person-plus"></i> Thêm nhân khẩu mới
                                    </a>
                                    <span class="submenu-link disabled-link">
                                        <i class="bi bi-arrow-left-right"></i> Tách / Nhập hộ <small class="text-muted">(Phát triển sau)</small>
                                    </span>
                                    <span class="submenu-link disabled-link">
                                        <i class="bi bi-luggage"></i> Khai báo tạm trú / tạm vắng <small class="text-muted">(Phát triển sau)</small>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @elseif ($isAnSinh)
                        <div class="nav-item">
                            <a class="nav-link {{ $isActive ? 'active' : '' }}" 
                               data-bs-toggle="collapse" 
                               href="#submenu-{{ $module['slug'] }}" 
                               role="button" 
                               aria-expanded="{{ $isActive ? 'true' : 'false' }}" 
                               aria-controls="submenu-{{ $module['slug'] }}">
                                <span>
                                    <i class="bi bi-heart-pulse me-2"></i>
                                    {{ $module['title'] }}
                                </span>
                                <i class="bi bi-chevron-down chevron-icon"></i>
                            </a>
                            <div class="collapse {{ $isActive ? 'show' : '' }}" id="submenu-{{ $module['slug'] }}">
                                <div class="submenu-container">
                                    <a class="submenu-link {{ $isModuleActive ? 'active' : '' }}" href="{{ route('modules.show', $module['slug']) }}">
                                        <i class="bi bi-speedometer2"></i> Tổng quan
                                    </a>
                                    <a class="submenu-link {{ request()->routeIs('doi-tuong-chinh-sach.index') || request()->routeIs('doi-tuong-chinh-sach.edit') ? 'active' : '' }}" href="{{ route('doi-tuong-chinh-sach.index') }}">
                                        <i class="bi bi-list-ul"></i> Đối tượng chính sách
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        @php
                            $iconClass = 'bi-grid';
                            if ($module['slug'] === 'kinh-te-lao-dong') {
                                $iconClass = 'bi-briefcase';
                            } elseif ($module['slug'] === 'nghia-vu-an-ninh') {
                                $iconClass = 'bi-shield-check';
                            } elseif ($module['slug'] === 'dat-dai-ha-tang') {
                                $iconClass = 'bi-geo-alt';
                            } elseif ($module['slug'] === 'he-thong-bao-cao') {
                                $iconClass = 'bi-gear';
                            }
                        @endphp
                        <a class="nav-link {{ $isActive ? 'active' : '' }}" href="{{ route('modules.show', $module['slug']) }}">
                            <span>
                                <i class="bi {{ $iconClass }} me-2"></i>
                                {{ $module['title'] }}
                            </span>
                        </a>
                    @endif
                @endforeach
            </nav>

            <div class="mt-4 rounded-3 bg-light border p-3 small text-secondary">
                <div class="fw-semibold text-dark">Môi trường hiện tại</div>
                <div>{{ app()->environment() }} / Laravel {{ app()->version() }}</div>
            </div>
        </aside>

        <main class="flex-grow-1">
            <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
                <div class="container-fluid px-3 px-lg-4">
                    <span class="navbar-brand mb-0 h1">@yield('page_title', 'Bảng điều khiển')</span>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topActions" aria-controls="topActions" aria-expanded="false" aria-label="Bật tắt thanh công cụ">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="topActions">
                        <div class="ms-auto d-flex gap-2 pt-3 pt-lg-0">
                            <button class="btn btn-outline-success btn-sm" type="button">Xuất báo cáo</button>
                            <button class="btn btn-success btn-sm" type="button">Tạo hồ sơ</button>
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
