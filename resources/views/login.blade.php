<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>เข้าสู่ระบบ - ระบบจัดการสัตตเกษตร</title>
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="login-card">
        <div class="logo-container">
            <span class="logo-icon">🍃</span>
        </div>

        <h1 class="login-title">ระบบจัดการสต็อกเกษตร</h1>
        <p class="login-subtitle">เข้าสู่ระบบเพื่อจัดการผลผลิตทางการเกษตร</p>

        <form id="loginForm" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">อีเมล</label>
                <div class="input-wrapper">
                    <input type="email" id="email" name="email" class="form-input" placeholder="your@email.com"
                        required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">รหัสผ่าน</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" class="form-input" placeholder="••••••••"
                        required>
                </div>
            </div>

            <button type="submit" class="btn-login" id="loginBtn">เข้าสู่ระบบ</button>
        </form>

        <div class="divider">
            <span>หรือ</span>
        </div>

        <a href="{{ route('auth.google') }}" class="btn-google">
            <svg class="google-icon" viewBox="0 0 24 24">
                <path fill="#4285F4"
                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                <path fill="#34A853"
                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                <path fill="#FBBC05"
                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                <path fill="#EA4335"
                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
            </svg>
            เข้าสู่ระบบด้วย Google
        </a>

        <div class="signup-link">
            ไม่มีบัญชีผู้ใช้? <a href="{{ route('register') }}">สมัครสมาชิก</a>
        </div>

        <div class="test-info">
            <div class="test-info-title">💡 สำหรับการทดสอบ</div>
            <div>ใช้อีเมลและรหัสผ่านเพื่อเข้าสู่ระบบ หรือคลิกปุ่ม Google เพื่อทดสอบการเข้าสู่ระบบด้วย Google</div>
        </div>
    </div>

    <script>
        // Form submit loading state
        document.getElementById('loginForm').addEventListener('submit', function () {
            const btn = document.getElementById('loginBtn');
            btn.disabled = true;
            btn.textContent = 'กำลังเข้าสู่ระบบ....';
        });

        // Flash error/success
        @if(session('error'))
            showFlash('{{ session('error') }}', '#ef4444');
        @endif
        @if(session('success'))
            showFlash('{{ session('success') }}', '#16a34a');
        @endif
        @if($errors->any())
            showFlash('{{ $errors->first() }}', '#ef4444');
        @endif

        function showFlash(msg, color) {
            var d = document.createElement('div');
            d.style.cssText = 'position:fixed;top:1rem;right:1rem;background:' + color + ';color:#fff;padding:.85rem 1.25rem;border-radius:10px;z-index:9999;font-family:Sarabun,sans-serif;font-weight:600;box-shadow:0 8px 24px rgba(0,0,0,.15);';
            d.textContent = msg;
            document.body.appendChild(d);
            setTimeout(function () { d.remove(); }, 4000);
        }
    </script>
</body>

</html>