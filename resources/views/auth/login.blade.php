<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập hệ thống - HUCE Classroom Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --c-header: #1e3a5f;
            --c-sidebar: #2c4a7c;
            --c-accent: #f5a623;
            --c-page: #eef2f7;
        }

        body {
            background: linear-gradient(135deg, #1e3a5f 0%, #2c4a7c 50%, #4a77b4 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "Segoe UI", Arial, sans-serif;
            padding: 20px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
        }

        .login-header {
            background: var(--c-header);
            color: #ffffff;
            padding: 30px 24px;
            text-align: center;
        }

        .login-header h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
        }

        .login-header h2 span {
            color: var(--c-accent);
        }

        .login-header p {
            font-size: 14px;
            color: #d9e4f0;
            margin-bottom: 0;
        }

        .login-body {
            padding: 32px 28px;
        }

        .btn-huce {
            background: var(--c-header);
            border-color: var(--c-header);
            color: #ffffff;
            padding: 10px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-huce:hover {
            background: #152943;
            border-color: #152943;
            color: #ffffff;
        }

        .demo-accounts {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            padding: 14px;
            margin-top: 24px;
        }

        .demo-pill {
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .demo-pill:hover {
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <i class="bi bi-mortarboard-fill fs-1 text-warning mb-2 d-inline-block"></i>
        <h2>HUCE<span>.Classroom</span></h2>
        <p>Hệ thống Quản lý Lớp học & Sinh viên</p>
    </div>

    <div class="login-body">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Địa chỉ Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" id="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', 'admin@st.huce.edu.vn') }}"
                           placeholder="admin@st.huce.edu.vn" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" id="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           value="123456" placeholder="Mật khẩu (123456)" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                    <label class="form-check-label text-muted" for="remember">
                        Ghi nhớ đăng nhập
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-huce w-100 mb-3">
                <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập hệ thống
            </button>
        </form>

        {{-- Tài khoản test mặc định theo đề bài --}}
        <div class="demo-accounts">
            <div class="small fw-bold text-secondary mb-2 text-uppercase">
                <i class="bi bi-info-circle me-1"></i> Tài khoản mẫu kiểm thử:
            </div>
            <div class="d-flex flex-column gap-2">
                <button type="button" class="btn btn-sm btn-outline-primary text-start demo-pill" onclick="fillAccount('admin@st.huce.edu.vn', '123456')">
                    <strong>👑 Quản trị viên:</strong> <code>admin@st.huce.edu.vn</code> | Pass: <code>123456</code>
                </button>
                <button type="button" class="btn btn-sm btn-outline-success text-start demo-pill" onclick="fillAccount('teacher@st.huce.edu.vn', '123456')">
                    <strong>🎓 Giảng viên:</strong> <code>teacher@st.huce.edu.vn</code> | Pass: <code>123456</code>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function fillAccount(email, pass) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = pass;
    }
</script>
</body>
</html>
