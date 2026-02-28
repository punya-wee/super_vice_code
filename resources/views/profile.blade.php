<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>โปรไฟล์ - ระบบจัดการสัตตเกษตร</title>
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        :root {
            --primary-green: #22C55E;
            --light-green: #F0FDF4;
            --text-dark: #1F2937;
            --text-gray: #6B7280;
            --bg-body: #F8FAFC;
        }

        body {
            background: var(--bg-body);
            margin: 0;
            font-family: 'Sarabun', sans-serif; /* หรือ font ที่คุณใช้ */
            color: var(--text-dark);
        }

        /* Navbar */
        .profile-navbar {
            background: white;
            padding: 0.75rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f0f0f0;
        }

        .profile-navbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .brand-logo {
            width: 32px;
            height: 32px;
            background: var(--light-green);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-green);
        }

        .logout-link {
            text-decoration: none;
            color: var(--text-dark);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Container Layout */
        .profile-container {
            max-width: 1100px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        /* Main Content Grid */
        .profile-grid-top {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            border: 1px solid #f1f5f9;
        }

        /* Left Side: Avatar Card */
        .profile-avatar-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .avatar-circle {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: var(--light-green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
            color: var(--primary-green);
            margin-bottom: 1.5rem;
            border: 2px solid white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .profile-info-text h2 { margin: 0; font-size: 1.4rem; }
        .profile-info-text p { color: var(--text-gray); margin: 0.25rem 0; font-size: 0.95rem; }

        /* Right Side: Form Card */
        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .btn-edit-inline {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 0.4rem 1rem;
            border-radius: 6px;
            font-size: 0.85rem;
            cursor: pointer;
        }

        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text-dark); }
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #fcfcfc;
            box-sizing: border-box;
        }

        /* Bottom Stats Section */
        .stats-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
        }
        
        /* Custom Colors for Stats */
        .stat-blue { background: #F0F7FF; }
        .stat-green { background: #F0FDF4; }
        .stat-purple { background: #FAF5FF; }

        .stat-label { font-size: 0.85rem; color: var(--text-gray); margin-bottom: 0.5rem; }
        .stat-value { font-size: 1.1rem; font-weight: 700; color: #1e293b; }

        /* Security Section */
        .security-card {
            margin-top: 1.5rem;
            background: #FFF7ED;
            border: 1px solid #FED7AA;
            border-radius: 16px;
            padding: 1.5rem;
        }

        .security-title { font-weight: 700; color: #9A3412; margin-bottom: 0.75rem; display: block; }
        .security-warning { color: #C2410C; font-size: 0.9rem; line-height: 1.5; }
        .warning-text-red { color: #DC2626; margin-top: 1rem; display: flex; align-items: center; gap: 0.5rem; font-weight: 500; }

        @media (max-width: 850px) {
            .profile-grid-top, .stats-section { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <nav class="profile-navbar">
        <div class="profile-navbar-left">
            <button class="back-btn" onclick="goBack()" style="background:none; border:none; cursor:pointer; font-size:1.2rem;">←</button>
            <div class="brand-logo">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L6.66,19.7C7.14,19.87 7.64,20 8,20C19,20 22,3 22,3C21,5 14,5.25 9,6.25C4,7.25 2,11.5 2,13.5C2,15.5 3.75,17.25 3.75,17.25C7,11 8,9 17,8Z"/></svg>
            </div>
            <div>
                <div style="font-weight: 700; font-size: 0.95rem;">โปรไฟล์</div>
                <div style="font-size: 0.75rem; color: #94a3b8;">จัดการข้อมูลส่วนตัวของคุณ</div>
            </div>
        </div>
        <a href="#" class="logout-link" onclick="logout()">
            <span style="border: 1px solid #e2e8f0; padding: 4px 10px; border-radius: 6px;">
                🚪 ออกจากระบบ
            </span>
        </a>
    </nav>

    <div class="profile-container">
        
        <div class="profile-grid-top">
            <div class="card profile-avatar-card">
                <div class="avatar-circle">
                    {{ strtoupper(substr($user->full_name, 0, 1)) }}
                </div>
                <div class="profile-info-text" style="text-align: center;">
                    <h2>{{ $user->full_name }}</h2>
                    <p>{{ $user->email }}</p>
                </div>
            </div>

            <div class="card">
                <div class="form-header">
                    <h3 style="margin:0; font-size: 1.1rem;">ข้อมูลส่วนตัว</h3>
                    <button class="btn-edit-inline" id="editBtn" onclick="toggleEditMode()">แก้ไข</button>
                </div>
                
                <form id="profileForm" action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="form-label">ชื่อ</label>
                        <input type="text" name="full_name" class="form-control" id="fullNameInput" value="{{ $user->full_name }}" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">อีเมล</label>
                        <input type="email" name="email" class="form-control" id="emailInput" value="{{ $user->email }}" readonly>
                        <small style="color:#94a3b8; font-size: 0.75rem;" id="emailNote">ไม่สามารถเปลี่ยนแปลงอีเมลได้</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">URL รูปโปรไฟล์</label>
                        <input type="text" name="avatar_url" class="form-control" id="avatarUrlInput" placeholder="https://example.com/photo.jpg" value="{{ $profile->avatar_url ?? '' }}" readonly>
                        <small style="color:#94a3b8; font-size: 0.75rem;">ใส่ URL รูปภาพของคุณ (ไม่บังคับ)</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">เบอร์โทรศัพท์</label>
                        <input type="tel" name="phone" class="form-control" id="phoneInput" placeholder="089-123-4567" value="{{ $profile->phone ?? '' }}" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ที่อยู่</label>
                        <textarea name="address" class="form-control" id="addressInput" placeholder="บ้านเลขที่ ... ซอย ... ถนน ..." style="resize: vertical; min-height: 80px;" readonly>{{ $profile->address ?? '' }}</textarea>
                    </div>
                    <div style="display:none;" id="saveButtonContainer">
                        <button type="submit" style="background: var(--primary-green); color: white; padding: 0.75rem 2rem; border: none; border-radius: 10px; cursor: pointer; font-weight: 500; width: 100%;">บันทึกการเปลี่ยนแปลง</button>
                    </div>
                </form>
            </div>
        </div>

        <h3 style="font-size: 1.1rem; margin: 2rem 0 1rem 0;">สถิติบัญชี</h3>
        <div class="stats-section">
            <div class="stat-card stat-green">
                <div class="stat-label">วันที่สร้างบัญชี</div>
                <div class="stat-value">1 มีนาคม 2569</div>
            </div>
            <div class="stat-card stat-blue">
                <div class="stat-label">สถานะบัญชี</div>
                <div class="stat-value">ใช้งานอยู่</div>
            </div>
            <div class="stat-card stat-purple">
                <div class="stat-label">วิธีการเข้าสู่ระบบ</div>
                <div class="stat-value">อีเมล</div>
            </div>
        </div>

        <div class="security-card">
            <span class="security-title">ความปลอดภัย</span>
            <p class="security-warning">ข้อมูลของคุณถูกเก็บไว้ในเครื่องของคุณเท่านั้น และจะไม่ถูกส่งไปยังเซิร์ฟเวอร์ใดๆ</p>
            <div class="warning-text-red">
                ⚠️ หากคุณลบข้อมูลเบราว์เซอร์หรือ localStorage ข้อมูลทั้งหมดจะหายไปอย่างถาวร
            </div>
        </div>

    </div>

    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>

    <script>
        let isEditMode = false;

        function goBack() { 
            window.history.back(); 
        }

        function toggleEditMode() {
            const fullNameInput = document.getElementById('fullNameInput');
            const emailInput = document.getElementById('emailInput');
            const avatarUrlInput = document.getElementById('avatarUrlInput');
            const phoneInput = document.getElementById('phoneInput');
            const addressInput = document.getElementById('addressInput');
            const editBtn = document.getElementById('editBtn');
            const saveButtonContainer = document.getElementById('saveButtonContainer');
            const emailNote = document.getElementById('emailNote');

            if (!isEditMode) {
                // Enable edit mode
                fullNameInput.removeAttribute('readonly');
                avatarUrlInput.removeAttribute('readonly');
                phoneInput.removeAttribute('readonly');
                addressInput.removeAttribute('readonly');
                editBtn.textContent = 'ยกเลิก';
                editBtn.style.background = '#fee2e2';
                editBtn.style.color = '#991b1b';
                saveButtonContainer.style.display = 'block';
                emailNote.textContent = 'อีเมลไม่สามารถเปลี่ยนแปลงได้';
                isEditMode = true;
            } else {
                // Disable edit mode
                fullNameInput.setAttribute('readonly', '');
                avatarUrlInput.setAttribute('readonly', '');
                phoneInput.setAttribute('readonly', '');
                addressInput.setAttribute('readonly', '');
                editBtn.textContent = 'แก้ไข';
                editBtn.style.background = '#f8fafc';
                editBtn.style.color = '';
                saveButtonContainer.style.display = 'none';
                isEditMode = false;
            }
        }

        function logout() { 
            if(confirm('ยืนยันการออกจากระบบ?')) {
                document.getElementById('logoutForm').submit();
            }
        }
    </script>
</body>
</html>