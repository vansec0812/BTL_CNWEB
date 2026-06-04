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
            --primary-gradient: linear-gradient(135deg, #0f5132, #198754, #0d6efd);
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.4);
            --text-dark: #2c3e35;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--primary-gradient);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-dark);
            padding: 20px;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-container {
            max-width: 1000px;
            width: 100%;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .brand-section {
            background: rgba(15, 81, 50, 0.05);
            border-right: 1px solid rgba(0, 0, 0, 0.05);
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .brand-logo {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            background: linear-gradient(135deg, #0f5132, #198754);
            color: white;
            font-size: 2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 10px 20px rgba(15, 81, 50, 0.2);
        }

        .form-section {
            padding: 40px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 18px;
            border: 1.5px solid rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: white;
            border-color: #198754;
            box-shadow: 0 0 0 4px rgba(25, 135, 84, 0.15);
        }

        .btn-submit {
            background: linear-gradient(135deg, #0f5132, #198754);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 8px 16px rgba(15, 81, 50, 0.2);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px rgba(15, 81, 50, 0.3);
            background: linear-gradient(135deg, #146c43, #198754);
        }

        .quick-user-card {
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 16px;
            padding: 12px 16px;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .quick-user-card:hover {
            background: white;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            border-color: #198754;
        }

        .role-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 20px;
        }

        .role-admin { background: rgba(13, 110, 253, 0.1); color: #0d6efd; }
        .role-tu_phap { background: rgba(25, 135, 84, 0.1); color: #198754; }
        .role-lao_dong { background: rgba(253, 126, 20, 0.1); color: #fd7e14; }
        .role-dia_chinh { background: rgba(108, 117, 125, 0.1); color: #6c757d; }
        .role-truong_thon { background: rgba(111, 66, 193, 0.1); color: #6f42c1; }
    </style>
</head>
<body>

<div class="login-container">
    <div class="glass-card">
        <div class="row g-0">
            <!-- Cột trái: Thương hiệu & Thông tin chung -->
            <div class="col-lg-5 brand-section">
                <div class="brand-logo">QO</div>
                <h4 class="fw-bold mb-1">UBND Xã Quốc Oai</h4>
                <p class="text-muted small mb-4">Hệ thống thông tin quản lý hộ dân cư & phân hệ chuyên trách</p>
                
                <hr class="w-100 my-4 opacity-10">
                
                <h6 class="fw-bold mb-3"><i class="bi bi-person-badge me-1"></i>Chọn nhanh tài khoản để test</h6>
                <div class="d-flex flex-column gap-2 w-100 px-lg-2">
                    @foreach($users as $user)
                        @php
                            $roleName = $user->roles->first()?->name ?? 'truong_thon';
                            $roleLabel = $user->roles->first()?->name === 'admin' ? 'Quản trị viên' : ($user->name);
                            $password = $roleName === 'admin' ? 'Admin@123456' : ($roleName === 'truong_thon' ? 'TruongThon@123' : 'CanBo@123456');
                        @endphp
                        <div class="quick-user-card" onclick="prefill('{{ $user->email }}', '{{ $password }}')">
                            <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-person-circle fs-5 text-success"></i>
                            </div>
                            <div class="text-start flex-grow-1">
                                <div class="fw-bold small">{{ $user->name }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $user->email }}</div>
                            </div>
                            <span class="role-badge role-{{ $roleName }}">
                                {{ $user->roles->first()?->name === 'admin' ? 'Admin' : ($user->roles->first()?->name === 'tu_phap' ? 'Tư pháp' : ($user->roles->first()?->name === 'lao_dong' ? 'Lao động' : ($user->roles->first()?->name === 'dia_chinh' ? 'Địa chính' : 'Trưởng thôn'))) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Cột phải: Form Đăng nhập -->
            <div class="col-lg-7 form-section d-flex flex-column justify-content-center">
                <div class="mb-4">
                    <h3 class="fw-bold">Đăng nhập hệ thống</h3>
                    <p class="text-secondary">Vui lòng nhập tài khoản cán bộ để tiếp tục thực hiện nghiệp vụ.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger border-0 rounded-3 mb-4" style="background: rgba(220, 53, 69, 0.1); color: #dc3545;">
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
                            <span class="input-group-text bg-transparent border-end-0" style="border-radius: 12px 0 0 12px; border: 1.5px solid rgba(0, 0, 0, 0.1); border-right: none;"><i class="bi bi-envelope text-muted"></i></span>
                            <input type="email" name="email" id="email" class="form-control border-start-0" style="border-radius: 0 12px 12px 0;" placeholder="ten_can_bo@ubnd-xa.vn" value="{{ old('email') }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">Mật khẩu</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0" style="border-radius: 12px 0 0 12px; border: 1.5px solid rgba(0, 0, 0, 0.1); border-right: none;"><i class="bi bi-lock text-muted"></i></span>
                            <input type="password" name="password" id="password" class="form-control border-start-0" style="border-radius: 0 12px 12px 0;" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-submit w-100 d-flex align-items-center justify-content-center gap-2">
                        <span>Đăng nhập</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function prefill(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
        
        // Add micro-animation effect to inputs
        const fields = [document.getElementById('email'), document.getElementById('password')];
        fields.forEach(f => {
            f.style.transform = 'scale(1.02)';
            setTimeout(() => f.style.transform = 'none', 150);
        });
    }
</script>
</body>
</html>
