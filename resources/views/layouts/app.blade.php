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
    </style>
</head>
<body>
    <div class="app-shell d-lg-flex">
        <aside class="app-sidebar flex-shrink-0 p-3 p-lg-4" style="width: 300px;">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-3 mb-4 text-decoration-none text-dark">
                <span class="brand-mark d-inline-flex align-items-center justify-content-center fw-bold">X</span>
                <span>
                    <span class="d-block fw-bold">UBND Xã Quốc Oai</span>
                    <small class="text-secondary">Quản lý thông tin hộ dân</small>
                </span>
            </a>

            <nav class="nav nav-pills flex-column gap-1">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    Tổng quan điều hành
                </a>
                @foreach ($modules as $module)
                    <a class="nav-link {{ request()->routeIs('modules.show') && request()->route('module') === $module['slug'] ? 'active' : '' }}" href="{{ route('modules.show', $module['slug']) }}">
                        {{ $module['title'] }}
                    </a>
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
</body>
</html>
