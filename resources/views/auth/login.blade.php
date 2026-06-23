<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập - Hệ thống quản lý hộ dân</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-green: #17683a;
            --brand-green-2: #2f8f58;
            --brand-blue: #2d7fa9;
            --surface: rgba(255, 255, 255, 0.9);
            --line: rgba(28, 55, 45, 0.12);
            --text: #22352d;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Outfit', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--text);
            background:
                linear-gradient(135deg, rgba(23, 104, 58, 0.94), rgba(45, 127, 169, 0.92)),
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.18), transparent 32%);
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .login-shell {
            width: min(960px, 100%);
            display: grid;
            grid-template-columns: minmax(280px, 0.9fr) minmax(320px, 1.1fr);
            background: var(--surface);
            border: 1px solid rgba(255, 255, 255, 0.48);
            border-radius: 22px;
            box-shadow: 0 28px 70px rgba(18, 54, 61, 0.28);
            overflow: hidden;
            backdrop-filter: blur(18px);
        }

        .brand-panel {
            min-height: 520px;
            padding: 52px 44px;
            background: rgba(248, 252, 250, 0.72);
            border-right: 1px solid var(--line);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 36px;
        }

        .brand-logo {
            width: 76px;
            height: 76px;
            border-radius: 22px;
            background: linear-gradient(135deg, var(--brand-green), var(--brand-green-2));
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 2.1rem;
            font-weight: 800;
            box-shadow: 0 16px 34px rgba(23, 104, 58, 0.24);
        }

        .brand-title {
            font-size: clamp(1.6rem, 2vw, 2rem);
            font-weight: 800;
            margin: 24px 0 10px;
        }

        .brand-copy {
            max-width: 340px;
            color: #617169;
            line-height: 1.65;
            margin: 0;
        }

        .brand-meta {
            display: grid;
            gap: 12px;
            color: #4f6259;
            font-size: 0.92rem;
        }

        .brand-meta span {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .brand-meta i {
            color: var(--brand-green);
            font-size: 1rem;
        }

        .form-panel {
            padding: 56px 52px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            width: fit-content;
            padding: 7px 12px;
            border-radius: 999px;
            background: rgba(23, 104, 58, 0.08);
            color: var(--brand-green);
            font-size: 0.82rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        h1 {
            font-size: clamp(1.9rem, 3vw, 2.45rem);
            font-weight: 800;
            margin: 0 0 10px;
        }

        .form-subtitle {
            color: #68776f;
            margin-bottom: 34px;
            line-height: 1.6;
        }

        .form-label {
            color: #304239;
            margin-bottom: 8px;
        }

        .input-group {
            border: 1.5px solid var(--line);
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.72);
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            overflow: hidden;
        }

        .input-group:focus-within {
            border-color: rgba(47, 143, 88, 0.9);
            box-shadow: 0 0 0 4px rgba(47, 143, 88, 0.13);
            background: #fff;
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
            min-height: 52px;
            padding: 12px 16px;
            color: var(--text);
        }

        .form-control:focus {
            box-shadow: none;
            background: transparent;
        }

        .btn-submit {
            min-height: 54px;
            border: 0;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--brand-green), var(--brand-green-2));
            color: #fff;
            font-weight: 800;
            box-shadow: 0 16px 28px rgba(23, 104, 58, 0.22);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-submit:hover,
        .btn-submit:focus {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 20px 34px rgba(23, 104, 58, 0.28);
        }

        .alert {
            border-radius: 14px;
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
                border-right: 0;
                border-bottom: 1px solid var(--line);
                gap: 20px;
            }

            .brand-logo {
                width: 62px;
                height: 62px;
                border-radius: 18px;
                font-size: 1.7rem;
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
