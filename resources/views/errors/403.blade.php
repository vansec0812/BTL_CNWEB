<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Không có quyền truy cập</title>
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

        .error-shell {
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

        .error-panel {
            padding: 48px 52px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .error-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            width: fit-content;
            padding: 7px 14px;
            border-radius: 999px;
            background: var(--admin-danger-soft);
            color: var(--admin-danger);
            font-size: 0.82rem;
            font-weight: 700;
            margin-bottom: 24px;
        }

        .error-icon {
            font-size: 4rem;
            color: var(--admin-danger);
            margin-bottom: 16px;
            animation: shield-shake 1.5s ease-in-out infinite;
        }

        h1 {
            color: #1f352b;
            font-size: clamp(1.5rem, 2.5vw, 1.85rem);
            font-weight: 800;
            margin: 0 0 12px;
        }

        .error-message {
            color: var(--admin-neutral);
            margin-bottom: 30px;
            line-height: 1.6;
            max-width: 400px;
        }

        .btn-home {
            min-height: 44px;
            border: 1px solid var(--admin-green);
            border-radius: 8px;
            background: var(--admin-green);
            color: #fff;
            font-weight: 700;
            transition: all .2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 24px;
        }

        .btn-home:hover,
        .btn-home:focus {
            color: #fff;
            background: #0b3f27;
            border-color: #0b3f27;
            transform: translateY(-1px);
        }

        .btn-back {
            min-height: 44px;
            border: 1px solid var(--admin-border);
            border-radius: 8px;
            background: #fff;
            color: #475467;
            font-weight: 600;
            transition: all .2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 24px;
        }

        .btn-back:hover,
        .btn-back:focus {
            background: #f9fafb;
            color: #344054;
            border-color: #d0d5dd;
            transform: translateY(-1px);
        }

        @keyframes shield-shake {
            0%, 100% { transform: rotate(0deg); }
            15% { transform: rotate(-6deg); }
            30% { transform: rotate(5deg); }
            45% { transform: rotate(-3deg); }
            60% { transform: rotate(2deg); }
            75% { transform: rotate(-1deg); }
        }

        @media (max-width: 860px) {
            body {
                padding: 16px;
                align-items: start;
            }

            .error-shell {
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

            .error-panel {
                padding: 40px 24px;
            }
        }
    </style>
</head>
<body>
<main class="error-shell">
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

    <section class="error-panel" aria-label="Thông báo lỗi">
        <span class="error-badge"><i class="bi bi-exclamation-triangle-fill"></i> Lỗi truy cập</span>
        <div class="error-icon">
            <i class="bi bi-shield-slash-fill"></i>
        </div>
        <h1>Không có quyền truy cập</h1>
        <p class="error-message">
            Tài khoản của bạn không có đủ thẩm quyền để truy cập phân hệ hoặc thực hiện hành động này. Vui lòng liên hệ với Quản trị viên hệ thống để biết thêm chi tiết.
        </p>

        <div class="d-flex flex-wrap gap-2 justify-content-center">
            <a href="javascript:history.back()" class="btn-back d-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i>
                <span>Quay lại</span>
            </a>
            <a href="{{ url('/') }}" class="btn-home d-flex align-items-center gap-2">
                <i class="bi bi-house-door"></i>
                <span>Trang chủ</span>
            </a>
        </div>
    </section>
</main>
</body>
</html>
