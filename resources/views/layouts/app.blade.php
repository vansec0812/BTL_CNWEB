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
            --admin-neutral: #667085;
            --admin-neutral-soft: #f2f4f7;
            --admin-border: #d0d5dd;
            --admin-danger: #b42318;
            --admin-danger-soft: #fef3f2;
            --admin-bg: #f4f6f3;
        }

        body {
            background: var(--admin-bg);
            overflow-x: hidden;
        }

        .app-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: row;
            width: 100%;
            overflow-x: hidden;
        }

        /* Sidebar cố định chiều rộng, không co */
        .shrink {
            flex-shrink: 0;
        }

        /* Main content chiếm hết phần còn lại, min-width:0 ngăn overflow */
        .grow {
            flex: 1 1 0%;
            min-width: 0;
            overflow-x: hidden;
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
            background: linear-gradient(135deg, #0f5132, #146c43);
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

        /* ── Nút quay lại (dùng chung toàn ứng dụng) ── */
        .btn-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            color: #6c757d;
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .btn-back:hover {
            color: var(--admin-green);
            background-color: var(--admin-green-soft);
            border-color: rgba(15, 81, 50, 0.2);
            transform: translateX(-2px);
        }

        /* ── Nút hành động bảng (icon-only, dùng chung toàn ứng dụng) ── */
        .btn-action-view,
        .btn-action-edit,
        .btn-action-delete {
            width: 30px;
            height: 30px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
            font-size: 0.875rem;
        }
        .btn-action-view {
            background-color: var(--admin-neutral-soft);
            color: var(--admin-neutral);
            border: 1px solid var(--admin-border);
        }
        .btn-action-view:hover {
            background-color: var(--admin-neutral);
            color: #ffffff;
            border-color: var(--admin-neutral);
        }
        .btn-action-edit {
            background-color: var(--admin-green-soft);
            color: var(--admin-green);
            border: 1px solid rgba(15, 81, 50, 0.22);
        }
        .btn-action-edit:hover {
            background-color: var(--admin-green);
            color: #ffffff;
            border-color: var(--admin-green);
        }
        .btn-action-delete {
            background-color: var(--admin-danger-soft);
            color: var(--admin-danger);
            border: 1px solid rgba(180, 35, 24, 0.22);
        }
        .btn-action-delete:hover {
            background-color: var(--admin-danger);
            color: #ffffff;
            border-color: var(--admin-danger);
        }

        /* Global table palette: keep data screens calm with green + neutral only. */
        .table .badge,
        .card .table .badge,
        .table span[class*="badge bg-"],
        .table span[class*="badge text-bg-"] {
            background-color: var(--admin-green-soft) !important;
            border: 1px solid rgba(15, 81, 50, 0.14) !important;
            color: var(--admin-green) !important;
        }

        .table .text-primary,
        .table .text-success,
        .table .text-warning,
        .table .text-info,
        .table .text-danger,
        .stat-card .text-primary,
        .stat-card .text-success,
        .stat-card .text-warning,
        .stat-card .text-info,
        .stat-card .text-danger {
            color: var(--admin-green) !important;
        }

        .table .bg-primary,
        .table .bg-success,
        .table .bg-warning,
        .table .bg-info {
            background-color: var(--admin-green-soft) !important;
        }

        .table .btn-outline-primary,
        .table .btn-outline-success,
        .table .btn-outline-warning,
        .table .btn-outline-info,
        .card .btn-outline-primary,
        .card .btn-outline-success,
        .card .btn-outline-warning,
        .card .btn-outline-info {
            color: var(--admin-green) !important;
            border-color: rgba(15, 81, 50, 0.28) !important;
            background-color: #ffffff !important;
        }

        .table .btn-outline-primary:hover,
        .table .btn-outline-success:hover,
        .table .btn-outline-warning:hover,
        .table .btn-outline-info:hover,
        .card .btn-outline-primary:hover,
        .card .btn-outline-success:hover,
        .card .btn-outline-warning:hover,
        .card .btn-outline-info:hover {
            color: #ffffff !important;
            background-color: var(--admin-green) !important;
            border-color: var(--admin-green) !important;
        }

        .card .btn-success,
        .card .btn-primary,
        .card .btn-warning,
        .card .btn-info {
            color: #ffffff !important;
            background-color: var(--admin-green) !important;
            border-color: var(--admin-green) !important;
        }

        .card .btn-outline-danger,
        .table .btn-outline-danger {
            color: var(--admin-danger) !important;
            border-color: rgba(180, 35, 24, 0.32) !important;
            background-color: #ffffff !important;
        }

        .card .btn-outline-danger:hover,
        .table .btn-outline-danger:hover {
            color: #ffffff !important;
            background-color: var(--admin-danger) !important;
            border-color: var(--admin-danger) !important;
        }

        /* Unified admin surface */
        body {
            color: #24352d;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        :root {
            --semantic-primary: #175cd3;
            --semantic-primary-soft: #eff6ff;
            --semantic-primary-border: #bfdbfe;
            --semantic-success: #0f5132;
            --semantic-success-soft: #e9f5ef;
            --semantic-success-border: #b7dec9;
            --semantic-info: #0f766e;
            --semantic-info-soft: #ecfdf9;
            --semantic-info-border: #99f6e4;
            --semantic-warning: #9a6700;
            --semantic-warning-soft: #fff8db;
            --semantic-warning-border: #f5d56b;
            --semantic-danger: #b42318;
            --semantic-danger-soft: #fef3f2;
            --semantic-danger-border: #fecdca;
        }

        .app-sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .navbar {
            min-height: 64px;
        }

        .navbar-brand {
            color: #1f352b;
            font-size: 1rem;
            font-weight: 750;
        }

        .container-fluid.p-3,
        .container-fluid.p-lg-4 {
            max-width: 1680px;
        }

        .page-hero {
            border-radius: 8px !important;
            box-shadow: 0 12px 28px rgba(15, 81, 50, .12) !important;
        }

        .card {
            border-radius: 8px !important;
            border-color: rgba(15, 81, 50, .10) !important;
        }

        .card.shadow-sm,
        .stat-card {
            box-shadow: 0 8px 22px rgba(20, 64, 45, .07) !important;
        }

        .card-header,
        .card-footer {
            border-color: rgba(15, 81, 50, .10) !important;
        }

        .card-header {
            color: #24352d;
            font-weight: 700;
        }

        .form-label {
            color: #33483d;
            font-size: .88rem;
            font-weight: 650;
        }

        .form-control,
        .form-select {
            border-color: #d7ded9;
            border-radius: 8px;
            min-height: 40px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: rgba(15, 81, 50, .55);
            box-shadow: 0 0 0 .2rem rgba(15, 81, 50, .10);
        }

        .btn {
            border-radius: 8px;
            font-weight: 650;
        }

        .btn-success,
        .btn-primary {
            background-color: var(--admin-green) !important;
            border-color: var(--admin-green) !important;
            color: #fff !important;
        }

        .btn-success:hover,
        .btn-primary:hover {
            background-color: #0b3f27 !important;
            border-color: #0b3f27 !important;
        }

        .btn-outline-success,
        .btn-outline-primary,
        .btn-outline-secondary {
            background: #fff;
            border-color: rgba(15, 81, 50, .22);
            color: var(--admin-green);
        }

        .btn-outline-success:hover,
        .btn-outline-primary:hover,
        .btn-outline-secondary:hover {
            background: var(--admin-green);
            border-color: var(--admin-green);
            color: #fff;
        }

        .table {
            --bs-table-hover-bg: rgba(15, 81, 50, .035);
        }

        .table > :not(caption) > * > * {
            padding: .85rem .9rem;
            border-bottom-color: rgba(15, 81, 50, .09);
        }

        .table th,
        .table td {
            white-space: nowrap !important;
        }

        .table thead th,
        .table-light th {
            background: #f7faf8 !important;
            color: #47584f;
            font-size: .78rem;
            font-weight: 750;
            letter-spacing: 0;
            text-transform: uppercase;
        }

        .badge {
            border-radius: 999px;
            font-weight: 650;
            line-height: 1.1;
            padding: .38rem .58rem;
        }

        .alert {
            border-radius: 8px;
        }

        .progress {
            height: .55rem;
            border-radius: 999px;
            background: #e7eee9;
        }

        .progress-bar {
            background-color: var(--admin-green) !important;
        }

        .module-card {
            border-radius: 8px !important;
        }

        /* Softer semantic color system across all screens. */
        .text-primary {
            color: var(--semantic-primary) !important;
        }

        .text-success {
            color: var(--semantic-success) !important;
        }

        .text-info {
            color: var(--semantic-info) !important;
        }

        .text-warning,
        .text-warning-emphasis {
            color: var(--semantic-warning) !important;
        }

        .text-danger {
            color: var(--semantic-danger) !important;
        }

        .bg-primary,
        .text-bg-primary {
            background-color: var(--semantic-primary-soft) !important;
            color: var(--semantic-primary) !important;
        }

        .bg-success,
        .text-bg-success {
            background-color: var(--semantic-success-soft) !important;
            color: var(--semantic-success) !important;
        }

        .bg-info,
        .text-bg-info {
            background-color: var(--semantic-info-soft) !important;
            color: var(--semantic-info) !important;
        }

        .bg-warning,
        .text-bg-warning {
            background-color: var(--semantic-warning-soft) !important;
            color: var(--semantic-warning) !important;
        }

        .bg-danger,
        .text-bg-danger {
            background-color: var(--semantic-danger-soft) !important;
            color: var(--semantic-danger) !important;
        }

        .badge.bg-primary,
        .badge.text-bg-primary {
            border: 1px solid var(--semantic-primary-border) !important;
        }

        .badge.bg-success,
        .badge.text-bg-success {
            border: 1px solid var(--semantic-success-border) !important;
        }

        .badge.bg-info,
        .badge.text-bg-info {
            border: 1px solid var(--semantic-info-border) !important;
        }

        .badge.bg-warning,
        .badge.text-bg-warning {
            border: 1px solid var(--semantic-warning-border) !important;
        }

        .badge.bg-danger,
        .badge.text-bg-danger {
            border: 1px solid var(--semantic-danger-border) !important;
        }

        .badge.text-white {
            color: inherit !important;
        }

        .btn-warning,
        .btn-info {
            background-color: #ffffff !important;
            border-color: rgba(15, 81, 50, .22) !important;
            color: var(--admin-green) !important;
        }

        .btn-warning:hover,
        .btn-info:hover {
            background-color: var(--admin-green) !important;
            border-color: var(--admin-green) !important;
            color: #ffffff !important;
        }

        .btn-danger {
            background-color: var(--semantic-danger) !important;
            border-color: var(--semantic-danger) !important;
            color: #ffffff !important;
        }

        .btn-outline-warning,
        .btn-outline-info {
            background-color: #ffffff !important;
            border-color: rgba(15, 81, 50, .22) !important;
            color: var(--admin-green) !important;
        }

        .btn-outline-warning:hover,
        .btn-outline-info:hover {
            background-color: var(--admin-green) !important;
            border-color: var(--admin-green) !important;
            color: #ffffff !important;
        }

        .alert-success {
            background: #dff3e8;
            border: 1px solid #b7dec9;
            color: #0b3f27;
        }

        .alert-warning {
            background: var(--semantic-warning-soft);
            border: 1px solid var(--semantic-warning-border);
            color: var(--semantic-warning);
        }

        .alert-danger {
            background: var(--semantic-danger-soft);
            border: 1px solid var(--semantic-danger-border);
            color: var(--semantic-danger);
        }

        .stat-card {
            overflow: hidden;
            position: relative;
        }

        .stat-card::before {
            content: "";
            position: absolute;
            inset: 0 auto 0 0;
            width: 4px;
            background: var(--admin-green);
            opacity: .75;
        }

        .progress-bar,
        .progress-bar.bg-success,
        .progress-bar.bg-primary,
        .progress-bar.bg-info {
            background-color: var(--admin-green) !important;
            color: #ffffff !important;
        }

        .progress-bar.bg-warning {
            background-color: var(--semantic-warning) !important;
            color: #ffffff !important;
        }

        .progress-bar.bg-danger {
            background-color: var(--semantic-danger) !important;
            color: #ffffff !important;
        }

        .page-header {
            background: #ffffff;
            border: 1px solid rgba(15, 81, 50, .10);
            border-radius: 8px;
            box-shadow: 0 8px 22px rgba(20, 64, 45, .06);
            margin-bottom: 1.25rem;
            padding: 1.25rem;
        }

        .page-header .breadcrumb-trail {
            display: inline-flex;
            align-items: center;
            flex-wrap: wrap;
            gap: .4rem;
            width: fit-content;
            margin-bottom: .85rem;
            padding: .42rem .65rem;
            border: 1px solid rgba(15, 81, 50, .10);
            border-radius: 999px;
            background: #f7faf8;
            color: #667085;
            font-size: .84rem;
            font-weight: 650;
        }

        .breadcrumb-trail a {
            color: var(--admin-green);
            text-decoration: none;
        }

        .breadcrumb-trail a:hover {
            text-decoration: underline;
        }

        .breadcrumb-trail .separator {
            color: #9aa7a0;
            font-size: .75rem;
        }

        .page-header h1,
        .page-header h2 {
            color: #1f352b;
            font-weight: 800;
            letter-spacing: 0;
        }

        /* Legacy page breadcrumbs used across older screens. */
        main > .container-fluid > .mb-4 > .small.text-secondary.mb-1,
        main > .container-fluid > .d-flex.mb-4 .small.text-secondary.mb-1,
        main > .container-fluid > .d-flex.flex-wrap.mb-4 .small.text-secondary.mb-1 {
            display: inline-flex;
            align-items: center;
            flex-wrap: wrap;
            gap: .4rem;
            width: fit-content;
            margin-bottom: .75rem !important;
            padding: .42rem .65rem;
            border: 1px solid rgba(15, 81, 50, .10);
            border-radius: 999px;
            background: #f7faf8;
            color: #667085 !important;
            font-size: .84rem;
            font-weight: 650;
            line-height: 1.25;
        }

        main > .container-fluid > .mb-4 > .small.text-secondary.mb-1 a,
        main > .container-fluid > .d-flex.mb-4 .small.text-secondary.mb-1 a,
        main > .container-fluid > .d-flex.flex-wrap.mb-4 .small.text-secondary.mb-1 a {
            color: var(--admin-green) !important;
            font-weight: 750;
            text-decoration: none !important;
        }

        main > .container-fluid > .mb-4 > .small.text-secondary.mb-1 a:hover,
        main > .container-fluid > .d-flex.mb-4 .small.text-secondary.mb-1 a:hover,
        main > .container-fluid > .d-flex.flex-wrap.mb-4 .small.text-secondary.mb-1 a:hover {
            color: #0b3f27 !important;
            text-decoration: underline !important;
        }

        main > .container-fluid > .mb-4 > .small.text-secondary.mb-1 .mx-1,
        main > .container-fluid > .d-flex.mb-4 .small.text-secondary.mb-1 .mx-1,
        main > .container-fluid > .d-flex.flex-wrap.mb-4 .small.text-secondary.mb-1 .mx-1 {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: .95rem;
            margin-right: 0 !important;
            margin-left: 0 !important;
            color: transparent;
            position: relative;
        }

        main > .container-fluid > .mb-4 > .small.text-secondary.mb-1 .mx-1::after,
        main > .container-fluid > .d-flex.mb-4 .small.text-secondary.mb-1 .mx-1::after,
        main > .container-fluid > .d-flex.flex-wrap.mb-4 .small.text-secondary.mb-1 .mx-1::after {
            content: "\F285";
            color: #9aa7a0;
            font-family: "bootstrap-icons";
            font-size: .72rem;
            line-height: 1;
            position: absolute;
        }

        .form-section {
            padding: 1.15rem;
            border: 1px solid rgba(15, 81, 50, .10);
            border-radius: 8px;
            background: #ffffff;
        }

        .form-section + .form-section {
            margin-top: 1rem;
        }

        .form-section-title {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: 1rem;
            color: var(--admin-green);
            font-size: .94rem;
            font-weight: 800;
        }

        .form-section-title i {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: var(--admin-green-soft);
            color: var(--admin-green);
        }

        .choice-panel {
            height: 100%;
            padding: 1rem;
            border: 1px solid rgba(15, 81, 50, .10);
            border-radius: 8px;
            background: #f8fbf9;
        }

        .choice-panel .form-check {
            min-height: 2rem;
            display: flex;
            align-items: center;
            gap: .55rem;
            padding-left: 0;
        }

        .choice-panel .form-check-input {
            float: none;
            margin: 0;
            width: 2.4rem;
            height: 1.25rem;
        }

        .form-check-input:checked {
            background-color: var(--admin-green);
            border-color: var(--admin-green);
        }

        .form-control[readonly],
        .form-control:disabled,
        .form-select:disabled {
            background-color: #f1f5f3;
            color: #53645b;
            opacity: 1;
        }

        .labor-profile-form {
            overflow: hidden;
        }

        .labor-profile-form .card-body {
            background: #fbfdfb;
        }

        .labor-profile-form .card-body > .row > [class*="col-"]:not(.out-province-section):not(.export-section) {
            margin-bottom: .15rem;
        }

        .labor-profile-form .form-label {
            margin-bottom: .45rem;
        }

        .labor-profile-form .form-control,
        .labor-profile-form .form-select {
            background-color: #ffffff;
        }

        .labor-profile-form .card-footer {
            position: sticky;
            bottom: 0;
            z-index: 10;
            box-shadow: 0 -8px 18px rgba(20, 64, 45, .05);
        }

        @media (max-width: 991.98px) {
            .app-shell {
                display: block !important;
            }

            .app-sidebar {
                position: static;
                width: 100% !important;
                height: auto;
                border-right: 0;
                border-bottom: 1px solid rgba(15, 81, 50, .12);
            }

            .nav.nav-pills {
                max-height: 360px;
                overflow-y: auto;
            }

            .navbar {
                position: sticky;
                top: 0;
                z-index: 1020;
            }
        }
    </style>
</head>
<body>
    <div class="app-shell d-flex">
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

                        $isModuleActive = false;
                        
                        $isActive = false;
                        $submenu = [];

                        if ($isHoTich) {
                            $isHoKhauActive = request()->routeIs('ho-khau.*');
                            $isNhanKhauActive = request()->routeIs('nhan-khau.*');
                            $isBienDongActive = request()->routeIs('bien-dong.*');
                            $isTamTruActive = request()->routeIs('tam-tru.*');
                            $isActive = $isModuleActive || $isHoKhauActive || $isNhanKhauActive || $isBienDongActive || $isTamTruActive;
                            
                            $submenu = [
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
                                ['title' => 'Hồ sơ lao động', 'url' => route('ho-so.index'), 'icon' => 'bi-person-workspace', 'active' => $isHoSoActive],
                                ['title' => 'Doanh nghiệp & Hộ kinh doanh', 'url' => route('doanh-nghiep.index'), 'icon' => 'bi-building', 'active' => $isDoanhNghiepActive],
                                ['title' => 'Kết nối việc làm', 'url' => route('ket-noi.index'), 'icon' => 'bi-link-45deg', 'active' => $isKetNoiActive],
                            ];
                        } elseif ($isAnSinh) {
                            $isPolicyActive = request()->routeIs('doi-tuong-chinh-sach.*');
                            $isBaoTroActive = request()->routeIs('bao-tro-xa-hoi.*');
                            $isDotActive = request()->routeIs('dot-tro-cap.*');
                            $isYTeActive = request()->routeIs('y-te-nhan-khau.*');
                            $isActive = $isModuleActive || $isPolicyActive || $isBaoTroActive || $isDotActive || $isYTeActive;
                            
                            $submenu = [
                                ['title' => 'Đối tượng chính sách', 'url' => route('doi-tuong-chinh-sach.index'), 'icon' => 'bi-list-ul', 'active' => request()->routeIs('doi-tuong-chinh-sach.*')],
                                ['title' => 'Bảo trợ xã hội', 'url' => route('bao-tro-xa-hoi.index'), 'icon' => 'bi-shield', 'active' => request()->routeIs('bao-tro-xa-hoi.*')],
                                ['title' => 'Đợt trợ cấp & Quỹ từ thiện', 'url' => route('dot-tro-cap.index'), 'icon' => 'bi-gift', 'active' => $isDotActive],
                                ['title' => 'Theo dõi Y tế', 'url' => route('y-te-nhan-khau.index'), 'icon' => 'bi-heart-pulse', 'active' => request()->routeIs('y-te-nhan-khau.*')],
                            ];
                        } elseif ($isNghiaVu) {
                            $isNghiaVuActive = request()->routeIs('nghia-vu-quan-su.*');
                            $isDanQuanActive = request()->routeIs('dan-quan-tu-ve.*');
                            $isDanQuanHoatDongActive = request()->routeIs('dan-quan-hoat-dong.*');
                            $isAnNinhTratTuActive = request()->routeIs('an-ninh-trat-tu.*');
                            $isActive = $isModuleActive || $isNghiaVuActive || $isDanQuanActive || $isDanQuanHoatDongActive || $isAnNinhTratTuActive;
                            $submenu = [
                                ['title' => 'Quản lý Nghĩa vụ quân sự', 'url' => route('nghia-vu-quan-su.index'), 'icon' => 'bi-person-check', 'active' => $isNghiaVuActive],
                                ['title' => 'Lực lượng dân quân tự vệ', 'url' => route('dan-quan-tu-ve.index'), 'icon' => 'bi-people-fill', 'active' => $isDanQuanActive],
                                ['title' => 'Hoạt động dân quân', 'url' => route('dan-quan-hoat-dong.index'), 'icon' => 'bi-calendar-event', 'active' => $isDanQuanHoatDongActive],
                                ['title' => 'An ninh trật tự', 'url' => route('an-ninh-trat-tu.index'), 'icon' => 'bi-shield-exclamation', 'active' => $isAnNinhTratTuActive],
                            ];
                        } elseif ($isDatDai) {
                            $isDatDaiTaiSanActive = request()->routeIs('dat-dai-tai-san.*');
                            $isThueActive = request()->routeIs('thue-va-phi.*');
                            $isCoSoVatChatActive = request()->routeIs('co-so-vat-chat.*');
                            $isActive = $isModuleActive || $isDatDaiTaiSanActive || $isThueActive || $isCoSoVatChatActive;
                            $submenu = [
                                ['title' => 'Đất đai & Tài sản', 'url' => route('dat-dai-tai-san.index'), 'icon' => 'bi-map', 'active' => $isDatDaiTaiSanActive],
                                ['title' => 'Thuế & Phí địa phương', 'url' => route('thue-va-phi.index'), 'icon' => 'bi-cash-coin', 'active' => $isThueActive],
                                ['title' => 'Cơ sở vật chất', 'url' => route('co-so-vat-chat.index'), 'icon' => 'bi-building', 'active' => $isCoSoVatChatActive],
                            ];
                        } elseif ($isHeThong) {
                            $isActive = $isModuleActive || request()->routeIs('he-thong.rbac') || request()->routeIs('users.*') || request()->routeIs('audit-logs.*') || request()->routeIs('he-thong.dashboard-bieu-do') || request()->routeIs('he-thong.loc-dong');
                            $submenu = [];
                            if (auth()->user()->can('manage_users')) {
                                $submenu[] = ['title' => 'Tài khoản cán bộ', 'url' => route('users.index'), 'icon' => 'bi-person-badge', 'active' => request()->routeIs('users.*')];
                            }
                            if (auth()->user()->can('view_audit_logs')) {
                                $submenu[] = ['title' => 'Nhật ký hệ thống (Audit)', 'url' => route('audit-logs.index'), 'icon' => 'bi-clock-history', 'active' => request()->routeIs('audit-logs.*')];
                            }
                            if (auth()->user()->can('manage_users')) {
                                $submenu[] = ['title' => 'Phân quyền (RBAC)', 'url' => route('he-thong.rbac'), 'icon' => 'bi-shield-lock', 'active' => request()->routeIs('he-thong.rbac')];
                            }
                            $submenu = array_merge($submenu, [
                                ['title' => 'Dashboard & Biểu đồ', 'url' => route('he-thong.dashboard-bieu-do'), 'icon' => 'bi-graph-up', 'active' => request()->routeIs('he-thong.dashboard-bieu-do')],
                                ['title' => 'Bộ lọc động & Tìm kiếm', 'url' => route('he-thong.loc-dong'), 'icon' => 'bi-funnel', 'active' => request()->routeIs('he-thong.loc-dong')],
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
                                $currentUser = auth()->user();
                            @endphp
                            @if($currentUser)
                                <a href="{{ route('users.show', $currentUser->id) }}" class="d-flex align-items-center gap-2 text-decoration-none profile-link">
                                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center text-success fw-bold" style="width: 34px; height: 34px; font-size: 0.85rem;">
                                        {{ substr($currentUser->name, 0, 1) }}
                                    </div>
                                    <div class="text-start">
                                        <span class="d-block fw-bold small text-dark lh-sm">{{ $currentUser->name }}</span>
                                        <span class="badge bg-success bg-opacity-10 text-success fw-semibold" style="font-size: 0.65rem; padding: 2px 6px;">
                                            {{ $currentUser->roles->first()?->name === 'admin' ? 'Quản trị viên' : ($currentUser->roles->first()?->name === 'tu_phap' ? 'Cán bộ Tư pháp' : ($currentUser->roles->first()?->name === 'lao_dong' ? 'Cán bộ Lao động' : ($currentUser->roles->first()?->name === 'dia_chinh' ? 'Cán bộ Địa chính' : ($currentUser->roles->first()?->name === 'quan_su' ? 'Cán bộ Quân sự' : 'Cán bộ')))) }}
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
    @include('layouts._flash_toasts')
    @include('layouts._delete_confirm_modal')
</body>
</html>
