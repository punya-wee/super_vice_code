<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>สมัครสมาชิก - ระบบจัดการผลผลิตทางการเกษตร</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Sarabun', sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #e0f2fe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        /* ── Card ── */
        .card {
            background: #fff;
            border-radius: 20px;
            padding: 2.5rem 2.25rem;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .08);
            animation: fadeUp .45s ease-out;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Back link ── */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            color: #64748b;
            font-size: .88rem;
            text-decoration: none;
            margin-bottom: 1.5rem;
            transition: color .2s;
        }

        .back-link:hover {
            color: #16a34a;
        }

        /* ── Logo / Header ── */
        .logo-wrap {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-ico {
            width: 56px;
            height: 56px;
            background: #dcfce7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin: 0 auto .85rem;
        }

        .logo-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -.5px;
        }

        .logo-sub {
            font-size: .88rem;
            color: #64748b;
            margin-top: .25rem;
        }

        /* ── Form ── */
        .fg {
            margin-bottom: 1.1rem;
        }

        .fg label {
            display: block;
            font-size: .88rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: .4rem;
        }

        .inp-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .inp-ico {
            position: absolute;
            left: .9rem;
            color: #94a3b8;
            font-size: 1rem;
            pointer-events: none;
            line-height: 1;
        }

        .inp {
            width: 100%;
            padding: .75rem 1rem .75rem 2.6rem;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: .95rem;
            font-family: 'Sarabun', sans-serif;
            color: #1e293b;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .inp::placeholder {
            color: #94a3b8;
        }

        .inp:focus {
            border-color: #16a34a;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, .12);
        }

        .inp.err {
            border-color: #ef4444;
        }

        .hint {
            font-size: .78rem;
            color: #94a3b8;
            margin-top: .3rem;
        }

        .err-msg {
            font-size: .8rem;
            color: #ef4444;
            margin-top: .3rem;
        }

        /* ── Submit ── */
        .btn-submit {
            width: 100%;
            padding: .85rem;
            background: #16a34a;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            font-family: 'Sarabun', sans-serif;
            cursor: pointer;
            transition: background .2s, transform .15s, box-shadow .2s;
            margin-top: .35rem;
        }

        .btn-submit:hover:not(:disabled) {
            background: #15803d;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(22, 163, 74, .3);
        }

        .btn-submit:disabled {
            opacity: .6;
            cursor: not-allowed;
        }

        /* ── Divider ── */
        .divider {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin: 1.5rem 0;
            color: #94a3b8;
            font-size: .85rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        /* ── Google button ── */
        .btn-google {
            width: 100%;
            padding: .75rem;
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: .95rem;
            font-weight: 600;
            font-family: 'Sarabun', sans-serif;
            color: #374151;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            transition: background .2s, border-color .2s, box-shadow .2s;
        }

        .btn-google:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .07);
        }

        .g-svg {
            width: 20px;
            height: 20px;
        }

        /* ── Login link ── */
        .login-link {
            text-align: center;
            font-size: .88rem;
            color: #64748b;
            margin-top: 1.25rem;
        }

        .login-link a {
            color: #16a34a;
            font-weight: 700;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* ── Info box ── */
        .info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-left: 4px solid #3b82f6;
            border-radius: 10px;
            padding: 1rem 1.1rem;
            font-size: .82rem;
            color: #1e40af;
            line-height: 1.65;
            margin-top: 1.5rem;
        }

        /* ── Toast ── */
        #toastBox {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: .75rem;
        }

        .toast {
            background: #fff;
            border-radius: 10px;
            padding: .85rem 1.1rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
            font-size: .9rem;
            min-width: 260px;
            animation: slideIn .3s ease-out;
            font-family: 'Sarabun', sans-serif;
        }

        .toast.success {
            border-left: 4px solid #16a34a;
        }

        .toast.error {
            border-left: 4px solid #ef4444;
        }

        .toast.warning {
            border-left: 4px solid #f59e0b;
        }

        @keyframes slideIn {
            from {
                transform: translateX(120%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @media(max-width:480px) {
            .card {
                padding: 2rem 1.5rem;
            }

            .logo-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <div id="toastBox"></div>

    <div class="card">
        <!-- Back link -->
        <a href="{{ route('login') }}" class="back-link">← กลับไปหน้าเข้าสู่ระบบ</a>

        <!-- Header -->
        <div class="logo-wrap">
            <div class="logo-ico">🌿</div>
            <h1 class="logo-title">สมัครสมาชิก</h1>
            <p class="logo-sub">สร้างบัญชีใหม่เพื่อเริ่มจัดการผลผลิตทางการเกษตร</p>
        </div>

        <!-- Laravel errors -->
        @if($errors->any())
            <div
                style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:.85rem 1rem;margin-bottom:1rem;font-size:.85rem;color:#b91c1c;">
                <strong>⚠️ กรุณาตรวจสอบข้อมูล:</strong>
                <ul style="margin:.35rem 0 0 1.2rem;">
                    @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form id="regForm" method="POST" action="{{ route('register') }}">
            @csrf

            <!-- ชื่อ-นามสกุล -->
            <div class="fg">
                <label for="full_name">ชื่อ-นามสกุล</label>
                <div class="inp-wrap">
                    <span class="inp-ico">👤</span>
                    <input type="text" id="full_name" name="full_name"
                        class="inp {{ $errors->has('full_name') ? 'err' : '' }}" placeholder="ชื่อของคุณ"
                        value="{{ old('full_name') }}" required autocomplete="name">
                </div>
                @error('full_name')<div class="err-msg">{{ $message }}</div>@enderror
            </div>

            <!-- อีเมล -->
            <div class="fg">
                <label for="email">อีเมล</label>
                <div class="inp-wrap">
                    <span class="inp-ico">✉️</span>
                    <input type="email" id="email" name="email" class="inp {{ $errors->has('email') ? 'err' : '' }}"
                        placeholder="your@email.com" value="{{ old('email') }}" required autocomplete="email">
                </div>
                @error('email')<div class="err-msg">{{ $message }}</div>@enderror
            </div>

            <!-- รหัสผ่าน -->
            <div class="fg">
                <label for="password">รหัสผ่าน</label>
                <div class="inp-wrap">
                    <span class="inp-ico">🔒</span>
                    <input type="password" id="password" name="password"
                        class="inp {{ $errors->has('password') ? 'err' : '' }}" placeholder="••••••••" required
                        autocomplete="new-password">
                </div>
                <div class="hint">รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร</div>
                @error('password')<div class="err-msg">{{ $message }}</div>@enderror
            </div>

            <!-- ยืนยันรหัสผ่าน -->
            <div class="fg">
                <label for="password_confirmation">ยืนยันรหัสผ่าน</label>
                <div class="inp-wrap">
                    <span class="inp-ico">🔒</span>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="inp {{ $errors->has('password_confirmation') ? 'err' : '' }}" placeholder="••••••••"
                        required autocomplete="new-password">
                </div>
                @error('password_confirmation')<div class="err-msg">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn-submit" id="regBtn">สมัครสมาชิก</button>
        </form>

        <!-- Divider -->
        <div class="divider"><span>หรือ</span></div>

        <!-- Google -->
        <a href="{{ route('auth.google') }}" class="btn-google">
            <svg class="g-svg" viewBox="0 0 24 24">
                <path fill="#4285F4"
                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                <path fill="#34A853"
                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                <path fill="#FBBC05"
                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                <path fill="#EA4335"
                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
            </svg>
            สมัครด้วย Google
        </a>

        <!-- Login link -->
        <div class="login-link">มีบัญชีอยู่แล้ว? <a href="{{ route('login') }}">เข้าสู่ระบบ</a></div>

        <!-- Info box -->
        <div class="info-box">
            💡 <strong>สำหรับการทดสอบ:</strong>
            กรอกข้อมูลอะไรก็ได้ เพื่อสมัครสมาชิก หรือคลิก <em>สมัครด้วย Google</em> เพื่อทดสอบ
        </div>
    </div>

    <script>
        function toast(msg, type = 'success') {
            var box = document.getElementById('toastBox');
            var t = document.createElement('div');
            t.className = 'toast ' + type;
            t.innerHTML = (type === 'success' ? '✅' : type === 'error' ? '❌' : '⚠️') + ' <span>' + msg + '</span>';
            box.appendChild(t);
            setTimeout(function () { t.remove(); }, 4000);
        }

        // client-side validation before submit
        document.getElementById('regForm').addEventListener('submit', function (e) {
            var pw = document.getElementById('password').value;
            var pw2 = document.getElementById('password_confirmation').value;
            if (pw.length < 6) {
                e.preventDefault();
                toast('รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร', 'warning');
                return;
            }
            if (pw !== pw2) {
                e.preventDefault();
                toast('รหัสผ่านไม่ตรงกัน', 'error');
                return;
            }
            var btn = document.getElementById('regBtn');
            btn.disabled = true;
            btn.textContent = 'กำลังสมัครสมาชิก...';
        });

        function googleReg() {
            toast('ฟังก์ชัน Google กำลังพัฒนา', 'warning');
        }

        // Show flash
        @if(session('success'))
            window.addEventListener('DOMContentLoaded', () => toast("{{ session('success') }}", 'success'));
        @endif
    </script>
</body>

</html>