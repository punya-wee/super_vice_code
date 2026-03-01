<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>เลือก Workspace - ระบบจัดการสัตตเกษตร</title>
    <link rel="stylesheet" href="{{ asset('css/hub.css') }}">
</head>

<body>

    <nav class="hub-navbar">
        <div class="hub-navbar-left">
            <div class="hub-logo">🌿</div>
            <div class="hub-brand">
                <div class="hub-brand-title">ระบบจัดการเกษตร</div>
                <div class="hub-brand-subtitle">สวัสดี, {{ auth()->user()->full_name ?? 'User' }}</div>
            </div>
        </div>

        <div class="hub-navbar-right">
            <a href="{{ route('profile.show') }}" class="profile-btn" style="text-decoration: none; cursor: pointer;"
                title="โปรไฟล์">
                <div class="avatar-small">{{ strtoupper(substr($user->full_name ?? 'U', 0, 1)) }}</div>
            </a>
            <button class="logout-btn-minimal" onclick="logout()">
                <span>➜</span> ออกจากระบบ
            </button>
        </div>
    </nav>

    <div class="hub-container">
        <div class="hub-header">
            <h1 class="hub-title">เลือก Workspace ของคุณ</h1>
            <p class="hub-subtitle">หรือสร้าง Workspace ใหม่เพื่อเริ่มต้นจัดการสต็อก</p>
        </div>

        <div class="workspace-grid">
            <div class="workspace-card-new create" onclick="openCreateWorkspaceModal()">
                <div class="icon-wrapper green">＋</div>
                <div class="card-info">
                    <h3>สร้าง Workspace ใหม่</h3>
                    <p>เริ่มต้นจัดการสต็อกของคุณเอง</p>
                </div>
                <div class="chevron">❯</div>
            </div>

            <div class="workspace-card-new join" onclick="openJoinModal()">
                <div class="icon-wrapper blue">🔗</div>
                <div class="card-info">
                    <h3>เข้าร่วม Workspace</h3>
                    <p>ใช้รหัสเพื่อเข้าร่วม Workspace ของผู้อื่น</p>
                </div>
                <div class="chevron">❯</div>
            </div>
        </div>

        @if($workspaces->count() > 0)
            <h3 style="margin: 2.5rem 0 1rem 0; font-size: 1.1rem; font-weight: 700;">🏢 Workspace ของคุณ</h3>
            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                @foreach($workspaces as $workspace)
                    <div class="workspace-card-new" style="border-color: #dbeafe; cursor: pointer;"
                        onclick="navigateTo('/workspace/{{ $workspace->id }}')">
                        <div class="icon-wrapper blue" style="background: #eff6ff;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                        </div>
                        <div class="card-info">
                            <h3>{{ $workspace->name }}</h3>
                            <p>สร้างเมื่อ {{ \Carbon\Carbon::parse($workspace->created_at)->format('d M Y') }} &nbsp;·&nbsp; <span style="background:#dcfce7;color:#15803d;border-radius:4px;padding:1px 8px;font-size:.75rem;font-weight:700;">Owner</span></p>
                        </div>
                        <div class="chevron">❯</div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(isset($memberWorkspaces) && $memberWorkspaces->count() > 0)
            <h3 style="margin: 2.5rem 0 1rem 0; font-size: 1.1rem; font-weight: 700;">🤝 Workspace ที่ได้รับเชิญ</h3>
            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                @foreach($memberWorkspaces as $ws)
                    <div class="workspace-card-new" style="border-color: #e0e7ff; cursor: pointer;" onclick="navigateTo('/workspace/member/{{ $ws->id }}')">
                        <div class="icon-wrapper" style="background:#f5f3ff;color:#7c3aed;font-size:1.1rem;">👥</div>
                        <div class="card-info">
                            <h3>{{ $ws->name }}</h3>
                            <p>เจ้าของ: {{ $ws->owner_name }} &nbsp;·&nbsp; เข้าร่วมเมื่อ {{ \Carbon\Carbon::parse($ws->joined_at)->format('d M Y') }} &nbsp;·&nbsp; <span style="background:#dbeafe;color:#1d4ed8;border-radius:4px;padding:1px 8px;font-size:.75rem;font-weight:700;">Member</span></p>
                        </div>
                        <div class="chevron">❯</div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($workspaces->count() === 0 && (!isset($memberWorkspaces) || $memberWorkspaces->count() === 0))
            <div class="empty-state-card">
                <div class="empty-icon-img">🏢</div>
                <h3>ยังไม่มี Workspace</h3>
                <p>สร้าง Workspace ใหม่หรือเข้าร่วม Workspace ที่มีอยู่</p>
            </div>
        @endif
    </div>

    <!-- Create Workspace Modal -->
    <div class="modal-overlay" id="createWorkspaceModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title">สร้าง Workspace ใหม่</h2>
                    <p class="modal-subtitle">ตั้งชื่อให้ Workspace ของคุณ...</p>
                </div>
                <button class="modal-close-btn" onclick="closeCreateWorkspaceModal()">✕</button>
            </div>
            <form id="createWorkspaceForm">
                <div class="modal-body">
                    <label class="modal-label">ชื่อ Workspace</label>
                    <input type="text" class="modal-input" id="workspaceName" placeholder="เช่น สัตว์เลี้ยงของฉัน"
                        required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal-btn-cancel" onclick="closeCreateWorkspaceModal()">ยกเลิก</button>
                    <button type="button" class="modal-btn-create" onclick="submitCreateWorkspace()">สร้าง
                        Workspace</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Join Workspace Modal -->
    <div class="modal-overlay" id="joinWorkspaceModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title">🔗 เข้าร่วม Workspace</h2>
                    <p class="modal-subtitle">กรอกรหัสที่ได้รับจากเจ้าของ Workspace</p>
                </div>
                <button class="modal-close-btn" onclick="closeJoinModal()">✕</button>
            </div>
            <form method="POST" action="{{ route('workspace.join') }}" id="joinWorkspaceForm">
                @csrf
                <div class="modal-body">
                    <label class="modal-label">รหัส Workspace</label>
                    <input type="text" name="code" id="joinCode" class="modal-input" placeholder="เช่น 01EKH"
                        maxlength="10" autocomplete="off"
                        style="font-family:monospace;font-size:1.3rem;font-weight:700;letter-spacing:.15em;text-transform:uppercase;text-align:center;"
                        oninput="this.value=this.value.toUpperCase()" required>
                    <p style="font-size:.8rem;color:#94a3b8;margin-top:.5rem;text-align:center;">รหัสอยู่ที่ Dashboard →
                        Sidebar → สมาชิก ของ Workspace ที่ต้องการเข้าร่วม</p>
                    @if($errors->has('code'))
                        <p style="color:#ef4444;font-size:.85rem;margin-top:.5rem;">{{ $errors->first('code') }}</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal-btn-cancel" onclick="closeJoinModal()">ยกเลิก</button>
                    <button type="submit" class="modal-btn-create" id="joinSubmitBtn">เข้าร่วม</button>
                </div>
            </form>
        </div>
    </div>

    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    {{-- Flash messages --}}
    @if(session('error'))
        <div id="flashMsg"
            style="position:fixed;top:20px;right:20px;background:#ef4444;color:#fff;padding:1rem 1.5rem;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,.15);z-index:2000;font-weight:600;">
            ❌ {{ session('error') }}
        </div>
        <script>setTimeout(() => document.getElementById('flashMsg')?.remove(), 4000);</script>
    @endif
    @if(session('success'))
        <div id="flashMsg"
            style="position:fixed;top:20px;right:20px;background:#22c55e;color:#fff;padding:1rem 1.5rem;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,.15);z-index:2000;font-weight:600;">
            ✅ {{ session('success') }}
        </div>
        <script>setTimeout(() => document.getElementById('flashMsg')?.remove(), 4000);</script>
    @endif

    <script>
        function navigateTo(url) {
            window.location.href = url;
        }

        function logout() {
            if (confirm('ต้องการออกจากระบบหรือไม่?')) {
                document.getElementById('logoutForm').submit();
            }
        }

        function openCreateWorkspaceModal() {
            document.getElementById('createWorkspaceModal').classList.add('active');
            document.getElementById('workspaceName').focus();
        }

        function closeCreateWorkspaceModal() {
            document.getElementById('createWorkspaceModal').classList.remove('active');
            document.getElementById('workspaceName').value = '';
        }

        function submitCreateWorkspace() {
            const workspaceName = document.getElementById('workspaceName').value.trim();

            if (!workspaceName) {
                showToast('กรุณาใส่ชื่อ Workspace', 'error');
                return;
            }

            const btn = event.target;
            btn.disabled = true;
            btn.textContent = 'กำลังสร้าง...';

            // Send to backend
            fetch('{{ route("workspace.create") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ name: workspaceName })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    btn.disabled = false;
                    btn.textContent = 'สร้าง Workspace';

                    if (data.success) {
                        closeCreateWorkspaceModal();
                        showToast('สร้าง Workspace สำเร็จ!', 'success');
                        setTimeout(() => {
                            window.location.href = data.redirect || '/hub';
                        }, 1500);
                    } else {
                        showToast(data.message || 'เกิดข้อผิดพลาด', 'error');
                    }
                })
                .catch(error => {
                    btn.disabled = false;
                    btn.textContent = 'สร้าง Workspace';
                    console.error('Error:', error);
                    showToast('เกิดข้อผิดพลาด: ' + error.message, 'error');
                });
        }

        // Close modal when clicking outside
        document.getElementById('createWorkspaceModal').addEventListener('click', function (e) {
            if (e.target === this) closeCreateWorkspaceModal();
        });
        document.getElementById('joinWorkspaceModal').addEventListener('click', function (e) {
            if (e.target === this) closeJoinModal();
        });

        // Join Workspace Modal
        function openJoinModal() {
            document.getElementById('joinWorkspaceModal').classList.add('active');
            setTimeout(() => document.getElementById('joinCode').focus(), 100);
        }
        function closeJoinModal() {
            document.getElementById('joinWorkspaceModal').classList.remove('active');
            document.getElementById('joinCode').value = '';
        }

        // Auto-open join modal if there were validation errors
        @if($errors->has('code'))
            window.addEventListener('DOMContentLoaded', () => openJoinModal());
        @endif

        // Enter key to submit
        document.getElementById('workspaceName').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                submitCreateWorkspace();
            }
        });

        // ส่วน Toast logic เดิม...
        function showToast(message, type = 'success') {
            const bgColor = type === 'success' ? '#22c55e' : '#ef4444';
            const toastHtml = `
                <div style="
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: ${bgColor};
                    color: white;
                    padding: 1rem 1.5rem;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    z-index: 2000;
                    animation: slideIn 0.3s ease-out;
                ">
                    ${message}
                </div>
                <style>
                    @keyframes slideIn {
                        from { transform: translateX(400px); opacity: 0; }
                        to { transform: translateX(0); opacity: 1; }
                    }
                </style>
            `;
            const toast = document.createElement('div');
            toast.innerHTML = toastHtml;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>
</body>

</html>