<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập - Hệ thống quản lý hộ dân</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --admin-green: #0f5132;
            --admin-green-soft: #e9f5ef;
            --admin-neutral: #667085;
            --admin-border: #d0d5dd;
            --admin-bg: #f4f6f3;
            --admin-danger: #b42318;
            --admin-danger-soft: #fef3f2;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #24352d;
            background: var(--admin-bg);
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .login-shell {
            width: min(980px, 100%);
            display: grid;
            grid-template-columns: minmax(280px, .9fr) minmax(320px, 1.1fr);
            background: #fff;
            border: 1px solid rgba(15, 81, 50, .12);
            border-radius: 8px;
            box-shadow: 0 16px 40px rgba(20, 64, 45, .12);
            overflow: hidden;
        }

        .brand-panel {
            min-height: 500px;
            padding: 44px;
            background: linear-gradient(135deg, #0f5132, #146c43);
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 36px;
        }

        .brand-logo {
            width: 54px;
            height: 54px;
            border-radius: 8px;
            background: rgba(255, 255, 255, .14);
            border: 1px solid rgba(255, 255, 255, .28);
            display: grid;
            place-items: center;
            font-size: 1.35rem;
            font-weight: 800;
        }

        .brand-title {
            font-size: clamp(1.55rem, 2vw, 1.95rem);
            font-weight: 800;
            margin: 24px 0 10px;
        }

        .brand-copy {
            max-width: 340px;
            color: rgba(255, 255, 255, .78);
            line-height: 1.65;
            margin: 0;
        }

        .brand-meta {
            display: grid;
            gap: 12px;
            color: rgba(255, 255, 255, .84);
            font-size: 0.92rem;
        }

        .brand-meta span {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .brand-meta i {
            color: #fff;
            font-size: 1rem;
        }

        .form-panel {
            padding: 48px 52px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            width: fit-content;
            padding: 7px 11px;
            border-radius: 999px;
            background: var(--admin-green-soft);
            color: var(--admin-green);
            font-size: 0.82rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        h1 {
            color: #1f352b;
            font-size: clamp(1.75rem, 3vw, 2.2rem);
            font-weight: 800;
            margin: 0 0 10px;
        }

        .form-subtitle {
            color: var(--admin-neutral);
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .form-label {
            color: #304239;
            margin-bottom: 8px;
        }

        .input-group {
            border: 1px solid #d7ded9;
            border-radius: 8px;
            background: #fff;
            transition: border-color .2s ease, box-shadow .2s ease;
            overflow: hidden;
        }

        .input-group:focus-within {
            border-color: rgba(15, 81, 50, .55);
            box-shadow: 0 0 0 .2rem rgba(15, 81, 50, .10);
        }

        .input-group-text,
        .form-control {
            border: 0;
            background: transparent;
        }

        .input-group-text {
            color: #66766e;
            padding-left: 16px;
            padding-right: 8px;
        }

        .form-control {
            min-height: 44px;
            padding: 12px 16px;
            color: #24352d;
        }

        .form-control:focus {
            box-shadow: none;
            background: transparent;
        }

        .btn-submit {
            min-height: 46px;
            border: 1px solid var(--admin-green);
            border-radius: 8px;
            background: var(--admin-green);
            color: #fff;
            font-weight: 750;
            transition: background .2s ease, border-color .2s ease;
        }

        .btn-submit:hover,
        .btn-submit:focus {
            color: #fff;
            background: #0b3f27;
            border-color: #0b3f27;
        }

        .alert {
            border-radius: 8px;
        }

        @media (max-width: 860px) {
            body {
                padding: 16px;
                align-items: start;
            }

            .login-shell {
                grid-template-columns: 1fr;
            }

            .brand-panel {
                min-height: auto;
                padding: 34px 28px;
                gap: 20px;
            }

            .brand-logo {
                width: 48px;
                height: 48px;
                font-size: 1.2rem;
            }

            .brand-title {
                margin-top: 18px;
            }

            .brand-meta {
                display: none;
            }

            .form-panel {
                padding: 34px 28px;
            }
        }
    </style>
</head>
<body>
<main class="login-shell">
    <section class="brand-panel" aria-label="Thông tin hệ thống">
        <div>
            <div class="brand-logo">QO</div>
            <h2 class="brand-title">UBND Xã Quốc Oai</h2>
            <p class="brand-copy">
                Hệ thống thông tin quản lý hộ dân cư và các phân hệ nghiệp vụ chuyên trách.
            </p>
        </div>

        <div class="brand-meta">
            <span><i class="bi bi-shield-check"></i> Truy cập bảo mật theo vai trò cán bộ</span>
            <span><i class="bi bi-journal-check"></i> Đồng bộ dữ liệu cư trú, an sinh, lao động và quốc phòng</span>
            <span><i class="bi bi-clock-history"></i> Ghi nhận nhật ký thao tác trong quá trình xử lý</span>
        </div>
    </section>

    <section class="form-panel" aria-label="Biểu mẫu đăng nhập">
        <span class="form-kicker"><i class="bi bi-lock"></i> Cổng đăng nhập nội bộ</span>
        <h1>Đăng nhập hệ thống</h1>
        <p class="form-subtitle">
            Vui lòng sử dụng tài khoản cán bộ đã được cấp để tiếp tục.
        </p>

        @if($errors->any())
            <div class="alert alert-danger border-0 mb-4" style="background: rgba(220, 53, 69, 0.1); color: #b02a37;">
                <ul class="mb-0 list-unstyled">
                    @foreach($errors->all() as $error)
                        <li><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('login') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Địa chỉ Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" id="email" class="form-control" placeholder="ten_can_bo@ubnd-xa.vn" value="{{ old('email') }}" autocomplete="email" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label fw-semibold">Mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Nhập mật khẩu" autocomplete="current-password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-submit w-100 d-flex align-items-center justify-content-center gap-2">
                <span>Đăng nhập</span>
                <i class="bi bi-arrow-right"></i>
            </button>
        </form>
    </section>
</main>
</body>
</html>
