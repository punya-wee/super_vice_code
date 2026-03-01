<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>สมัครสมาชิก - ระบบจัดการผลผลิตทางการเกษตร</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
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