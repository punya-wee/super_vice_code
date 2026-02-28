<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>แดชบอร์ด - {{ $workspaceName }}</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Sarabun", sans-serif;
            background: #f0fdf4;
            color: #1e293b;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 1.5rem;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .nav-pill {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: .82rem;
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            color: #374151;
            cursor: pointer;
            border: none;
            font-family: Sarabun, sans-serif;
        }

        .nav-pill:hover {
            background: #e2e8f0;
        }

        .badge {
            background: #16a34a;
            color: #fff;
            border-radius: 6px;
            font-size: .72rem;
            font-weight: 700;
            padding: 1px 7px;
        }

        .avatar {
            width: 30px;
            height: 30px;
            background: #dcfce7;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .75rem;
            font-weight: 700;
            color: #16a34a;
        }

        .layout {
            display: flex;
            flex: 1;
        }

        .sidebar {
            width: 210px;
            background: #fff;
            border-right: 1px solid #e2e8f0;
            padding: 1rem 0;
            position: sticky;
            top: 56px;
            height: calc(100vh - 56px);
            overflow-y: auto;
            flex-shrink: 0;
        }

        .si {
            margin: 1px 8px;
        }

        .si button {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .6rem 1rem;
            font-size: .88rem;
            color: #374151;
            border: none;
            background: none;
            width: 100%;
            cursor: pointer;
            font-family: Sarabun, sans-serif;
            border-radius: 8px;
            transition: .15s;
            font-weight: 500;
            text-align: left;
        }

        .si button:hover {
            background: #f0fdf4;
            color: #16a34a;
        }

        .si.active button {
            background: #16a34a;
            color: #fff;
        }

        .content {
            flex: 1;
            padding: 1.75rem 2rem;
            overflow-y: auto;
        }

        .sec {
            display: none;
        }

        .sec.active {
            display: block;
        }

        .ph {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .ptitle {
            font-size: 1.25rem;
            font-weight: 800;
        }

        .psub {
            font-size: .82rem;
            color: #64748b;
            margin-top: .2rem;
        }

        .btn-g {
            background: #16a34a;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: .6rem 1.2rem;
            font-size: .88rem;
            font-weight: 600;
            cursor: pointer;
            font-family: Sarabun, sans-serif;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            transition: .2s;
        }

        .btn-g:hover {
            background: #15803d;
        }

        .btn-r {
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: .4rem .9rem;
            font-size: .82rem;
            font-weight: 600;
            cursor: pointer;
            font-family: Sarabun, sans-serif;
        }

        .btn-gray {
            background: #e2e8f0;
            color: #374151;
            border: none;
            border-radius: 6px;
            padding: .4rem .9rem;
            font-size: .82rem;
            font-weight: 600;
            cursor: pointer;
            font-family: Sarabun, sans-serif;
        }

        .krow {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .kcard {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .klbl {
            font-size: .78rem;
            color: #64748b;
            margin-bottom: .2rem;
        }

        .kval {
            font-size: 2rem;
            font-weight: 800;
            color: #16a34a;
            line-height: 1;
        }

        .kval.ora {
            color: #f97316;
        }

        .kval.blue {
            color: #3b82f6;
        }

        .ksub {
            font-size: .72rem;
            color: #94a3b8;
            margin-top: .3rem;
        }

        .kico {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .kico.b {
            background: #eff6ff;
        }

        .kico.r {
            background: #fff1f2;
        }

        .kico.g {
            background: #f0fdf4;
        }

        .charts-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .chart-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.25rem;
        }

        .chtitle {
            font-size: .9rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .brow {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.25rem;
        }

        .ctitle {
            font-size: .9rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .empty {
            text-align: center;
            padding: 2.5rem 1rem;
            color: #94a3b8;
            font-size: .85rem;
        }

        .hi {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f0fdf4;
            border-radius: 8px;
            padding: .7rem .9rem;
            margin-bottom: .5rem;
        }

        .hname {
            font-size: .88rem;
            font-weight: 600;
            color: #16a34a;
        }

        .hdate {
            font-size: .75rem;
            color: #64748b;
        }

        .days-b {
            background: #16a34a;
            color: #fff;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 700;
            padding: 3px 10px;
        }

        .toolbar {
            display: flex;
            gap: .75rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .srch {
            flex: 1;
            min-width: 160px;
            position: relative;
        }

        .srch input {
            width: 100%;
            padding: .6rem 1rem .6rem 2.4rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: .88rem;
            font-family: Sarabun, sans-serif;
            outline: none;
        }

        .srch input:focus {
            border-color: #16a34a;
        }

        .sico {
            position: absolute;
            left: .75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .sel {
            padding: .6rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: .88rem;
            font-family: Sarabun, sans-serif;
            outline: none;
            cursor: pointer;
            background: #fff;
        }

        .tbl {
            width: 100%;
            border-collapse: collapse;
        }

        .tbl th {
            text-align: left;
            padding: .7rem 1rem;
            font-size: .8rem;
            color: #64748b;
            font-weight: 600;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }

        .tbl td {
            padding: .7rem 1rem;
            font-size: .87rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .tbl tr:last-child td {
            border-bottom: none;
        }

        .tbl tr:hover td {
            background: #f8fafc;
        }

        .tag {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 6px;
            font-size: .75rem;
            font-weight: 700;
        }

        .tok {
            background: #dcfce7;
            color: #15803d;
        }

        .twarn {
            background: #fed7aa;
            color: #c2410c;
        }

        .tlow {
            background: #fee2e2;
            color: #b91c1c;
        }

        .tgray {
            background: #e2e8f0;
            color: #64748b;
        }

        .tblue {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .ibtn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: .95rem;
            padding: 3px 5px;
            border-radius: 4px;
            opacity: .65;
            transition: .2s;
        }

        .ibtn:hover {
            opacity: 1;
            background: #f1f5f9;
        }

        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            z-index: 200;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .overlay.open {
            display: flex;
        }

        .modal {
            background: #fff;
            border-radius: 16px;
            padding: 1.75rem;
            width: 520px;
            max-width: 95vw;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }

        .modal h3 {
            font-size: 1.1rem;
            font-weight: 800;
            margin-bottom: 1.25rem;
        }

        .fg {
            margin-bottom: 1rem;
        }

        .fg label {
            display: block;
            font-size: .82rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: .3rem;
        }

        .fg input,
        .fg select,
        .fg textarea {
            width: 100%;
            padding: .6rem .9rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: .88rem;
            font-family: Sarabun, sans-serif;
            outline: none;
        }

        .fg input:focus,
        .fg select:focus,
        .fg textarea:focus {
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, .1);
        }

        .fg-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .75rem;
        }

        .mfoot {
            display: flex;
            gap: .5rem;
            justify-content: flex-end;
            margin-top: 1.25rem;
        }

        .close-x {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: #64748b;
        }

        .toast {
            position: fixed;
            top: 1rem;
            right: 1rem;
            background: #16a34a;
            color: #fff;
            padding: .75rem 1.25rem;
            border-radius: 10px;
            font-size: .9rem;
            font-weight: 600;
            z-index: 999;
            display: none;
        }

        .toast.err {
            background: #ef4444;
        }

        .ps-plan {
            background: #e2e8f0;
            color: #374151;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
            padding: 2px 9px;
        }

        .ps-grow {
            background: #dcfce7;
            color: #15803d;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
            padding: 2px 9px;
        }

        .ps-done {
            background: #dbeafe;
            color: #1d4ed8;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
            padding: 2px 9px;
        }

        .price-krow {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .pkcard {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.1rem 1.25rem;
        }

        .pklbl {
            font-size: .78rem;
            color: #64748b;
            margin-bottom: .2rem;
        }

        .pkval {
            font-size: 1.6rem;
            font-weight: 800;
        }

        .pksub {
            font-size: .74rem;
            color: #94a3b8;
            margin-top: .2rem;
        }

        .vg {
            color: #16a34a;
        }

        .vb {
            color: #3b82f6;
        }

        .vo {
            color: #f97316;
        }

        .vr {
            color: #ef4444;
        }

        .tup {
            color: #16a34a;
            font-size: .78rem;
            font-weight: 600;
        }

        .tdown {
            color: #ef4444;
            font-size: .78rem;
            font-weight: 600;
        }

        .rec-card {
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1rem;
        }

        .recommend-info {
            background: #f0fdf4;
            border-left: 4px solid #16a34a;
        }

        .recommend-warn {
            background: #fff7ed;
            border-left: 4px solid #f97316;
        }

        .recommend-blue {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
        }

        .code-box {
            background: #fff;
            border: 2px dashed #16a34a;
            border-radius: 10px;
            padding: .6rem 1.75rem;
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: .2em;
            font-family: monospace;
            color: #15803d;
        }

        @media(max-width:900px) {

            .krow,
            .price-krow {
                grid-template-columns: 1fr 1fr;
            }

            .charts-row,
            .brow {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="toast" id="toast"></div>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div style="display:flex;align-items:center;gap:.75rem;">
            <div
                style="width:36px;height:36px;background:#dcfce7;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">
                🌿</div>
            <div>
                <div style="font-size:.95rem;font-weight:700;line-height:1.2;">ระบบจัดการผลผลิตทางการเกษตร</div>
                <div style="font-size:.72rem;color:#64748b;">วางแผนการเพาะปลูก วิเคราะห์ราคา และจัดการสต็อก</div>
            </div>
        </div>
        <div class="nav-right">
            <a href="{{ route('hub') }}" class="nav-pill">🏢 {{ $workspaceName }} <span class="badge">Owner</span></a>
            <div class="avatar">{{ strtoupper(substr(auth()->user()->full_name ?? 'U', 0, 2)) }}</div>
            <span style="font-size:.88rem;font-weight:600;">{{ auth()->user()->full_name ?? 'User' }}</span>
            <button class="nav-pill" style="color:#ef4444;" onclick="doLogout()">⬅ ออก</button>
        </div>
    </nav>
    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>

    @if(session('success'))
        <script>document.addEventLis        tener('DOMContentLoaded', () => showToast("{{ session('success') }}"))</script>
    @endif
    @if(session('error'))
    <script>document.addEventListener('DOMContentLoaded', () => showToast("{{ session('error') }}", true))</script>@endif

    <div class="layout">
        <aside class="sidebar">
            <ul style="list-style:none;">
                <li class="si active" id="nav-overview"><button onclick="show('overview')">📊 ภาพรวม</button></li>
                <li class="si" id="nav-stock"><button onclick="show('stock')">📦 จัดการสต็อก</button></li>
                <li class="si" id="nav-summary"><button onclick="show('summary')">📈 สรุปสต็อก</button></li>
                <li class="si" id="nav-planting"><button onclick="show('planting')">📅 ปฏิทินการปลูก</button></li>
                <li class="si" id="nav-price"><button onclick="show('price')">📉 วิเคราะห์ราคา</button></li>
                <li class="si" id="nav-compare"><button onclick="show('compare')">💰 เปรียบเทียบราคา</button></li>
                <li class="si" id="nav-recommend"><button onclick="show('recommend')">💡 คำแนะนำ</button></li>
                <li class="si" id="nav-members"><button onclick="show('members')">👥 สมาชิก</button></li>
                <li class="si" id="nav-history"><button onclick="show('history')">📋 ประวัติ</button></li>
            </ul>
        </aside>

        <main class="content">

            {{-- ===== ภาพรวม ===== --}}
            <section id="sec-overview" class="sec active">
                <div class="krow">
                    <div class="kcard">
                        <div>
                            <div class="klbl">จำนวนสินค้า</div>
                            <div class="kval">{{ $stats['total_products'] }}</div>
                            <div class="ksub">รายการในสต็อก</div>
                        </div>
                        <div class="kico b">📦</div>
                    </div>
                    <div class="kcard">
                        <div>
                            <div class="klbl">สินค้าใกล้หมด</div>
                            <div class="kval ora">{{ $stats['low_stock'] }}</div>
                            <div class="ksub">ต้องเติมสต็อก</div>
                        </div>
                        <div class="kico r">⚠️</div>
                    </div>
                    <div class="kcard">
                        <div>
                            <div class="klbl">กำลังปลูก</div>
                            <div class="kval blue">{{ $stats['active_schedules'] }}</div>
                            <div class="ksub">แปลงที่ดำเนินการอยู่</div>
                        </div>
                        <div class="kico g">🌱</div>
                    </div>
                </div>
                <div class="charts-row">
                    <div class="chart-card">
                        <div class="chtitle">📈 แนวโน้มราคาพืชผล</div><canvas id="overviewPriceChart"
                            height="200"></canvas>
                    </div>
                    <div class="chart-card">
                        <div class="chtitle">📊 จำนวนตามประเภทผลผลิต</div><canvas id="overviewProdChart"
                            height="200"></canvas>
                    </div>
                </div>
                <div class="brow">
                    <div class="card">
                        <div class="ctitle">⚠️ สินค้าใกล้หมด</div>
                        @forelse($lowStockItems as $p)
                            <div class="hi">
                                <div>
                                    <div class="hname">{{ $p->name }}</div>
                                    <div class="hdate">คงเหลือ {{ $p->quantity }} {{ $p->unit }}</div>
                                </div><span class="twarn tag">ใกล้หมด</span>
                            </div>
                        @empty
                            <div class="empty">ไม่มีสินค้าที่ใกล้หมด ✅</div>
                        @endforelse
                    </div>
                    <div class="card">
                        <div class="ctitle">📅 การเก็บเกี่ยวที่กำลังมาถึง</div>
                        @forelse($upcomingHarvest as $s)
                            <div class="hi">
                                <div>
                                    <div class="hname">{{ $s->crop_name }}</div>
                                    <div class="hdate">เก็บเกี่ยว: {{ $s->end_date_fmt }}</div>
                                </div><span class="days-b">{{ max(0, (int) $s->days_left) }} วัน</span>
                            </div>
                        @empty
                            <div class="empty">ไม่มีการเก็บเกี่ยวที่กำลังมาถึง</div>
                        @endforelse
                    </div>
                </div>
            </section>

            {{-- ===== จัดการสต็อก ===== --}}
            <section id="sec-stock" class="sec">
                <div class="ph">
                    <div>
                        <div class="ptitle">จัดการสต็อกผลผลิต</div>
                        <div class="psub">ตรวจสอบและจัดการข้อมูลผลผลิตทางการเกษตร</div>
                    </div>
                    <button class="btn-g" onclick="openModal('modalAddProduct')">＋ เพิ่มสินค้าใหม่</button>
                </div>
                <div class="card">
                    <div class="toolbar">
                        <div class="srch"><span class="sico">🔍</span><input type="text" id="stockSearch"
                                placeholder="ค้นหาสินค้า..." oninput="filterStock()"></div>
                        <select class="sel" id="stockCat" onchange="filterStock()">
                            <option value="">ทั้งหมด</option>
                            @foreach($byCategory as $cat)<option value="{{ $cat['category'] }}">{{ $cat['category'] }}
                            </option>@endforeach
                        </select>
                    </div>
                    <table class="tbl" id="stockTable">
                        <thead>
                            <tr>
                                <th>ชื่อสินค้า</th>
                                <th>หมวดหมู่</th>
                                <th>จำนวน</th>
                                <th>หน่วย</th>
                                <th>สถานะ</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stockItems as $p)
                                <tr data-name="{{ mb_strtolower($p->name) }}" data-cat="{{ $p->category }}">
                                    <td>{{ $p->name }}</td>
                                    <td>{{ $p->category ?: '-' }}</td>
                                    <td>{{ number_format($p->quantity, 0) }}</td>
                                    <td>{{ $p->unit }}</td>
                                    <td><span
                                            class="tag {{ $p->status === 'มีสต็อก' ? 'tok' : ($p->status === 'ใกล้หมด' ? 'twarn' : 'tlow') }}">{{ $p->status }}</span>
                                    </td>
                                    <td>
                                        <button class="ibtn"
                                            onclick="editProduct({{ $p->id }},'{{ addslashes($p->name) }}','{{ addslashes($p->category) }}','{{ $p->unit }}',{{ $p->quantity }},{{ $p->min_stock_level }},'{{ addslashes($p->description ?? '') }}')"
                                            title="แก้ไข">✏️</button>
                                        <button class="ibtn"
                                            onclick="deleteProduct({{ $p->id }},'{{ addslashes($p->name) }}')"
                                            title="ลบ">🗑️</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="empty">ยังไม่มีสินค้า กด "เพิ่มสินค้าใหม่"</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- ===== สรุปสต็อก ===== --}}
            <section id="sec-summary" class="sec">
                <div class="ph">
                    <div>
                        <div class="ptitle">สรุปภาพรวมสต็อกสินค้า</div>
                        <div class="psub">รายงานสรุปข้อมูลสินค้าคงคลังทั้งหมด</div>
                    </div>
                </div>
                <div class="krow">
                    <div class="kcard">
                        <div>
                            <div class="klbl">สินค้าทั้งหมด</div>
                            <div class="kval">{{ $stats['total_products'] }}</div>
                            <div class="ksub">รายการ</div>
                        </div>
                        <div class="kico b">📦</div>
                    </div>
                    <div class="kcard">
                        <div>
                            <div class="klbl">จำนวนรวม</div>
                            <div class="kval">{{ number_format($stockItems->sum('quantity'), 0) }}</div>
                            <div class="ksub">ทุกหมวดหมู่</div>
                        </div>
                        <div class="kico g">📊</div>
                    </div>
                    <div class="kcard">
                        <div>
                            <div class="klbl">สินค้าใกล้หมด</div>
                            <div class="kval ora">{{ $stats['low_stock'] }}</div>
                            <div class="ksub">รายการ</div>
                        </div>
                        <div class="kico r">⏰</div>
                    </div>
                </div>
                <div class="charts-row">
                    <div class="chart-card">
                        <div class="chtitle">จำนวนสินค้าตามหมวดหมู่</div><canvas id="sumBarChart" height="200"></canvas>
                    </div>
                    <div class="chart-card">
                        <div class="chtitle">สัดส่วนตามหมวดหมู่</div><canvas id="sumPieChart" height="200"></canvas>
                    </div>
                </div>
                <div class="card">
                    <div class="ctitle">สรุปตามหมวดหมู่</div>
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>หมวดหมู่</th>
                                <th>จำนวนรายการ</th>
                                <th>ปริมาณรวม</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($byCategory as $cat)
                                <tr>
                                    <td>{{ $cat['category'] }}</td>
                                    <td>{{ $cat['count'] }}</td>
                                    <td>{{ number_format($cat['total_qty'], 0) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="empty">ยังไม่มีข้อมูล</td>
                                </tr>
                            @endforelse
                            @if($byCategory->count() > 0)
                                <tr style="font-weight:800;border-top:2px solid #e2e8f0;background:#f8fafc;">
                                    <td>รวม</td>
                                    <td>{{ $stats['total_products'] }}</td>
                                    <td>{{ number_format($stockItems->sum('quantity'), 0) }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- ===== ปฏิทินการปลูก ===== --}}
            <section id="sec-planting" class="sec">
                <div class="ph">
                    <div>
                        <div class="ptitle">ปฏิทินการวางแผนการปลูก</div>
                        <div class="psub">วางแผนการปลูกตามฤดูกาลเพื่อผลผลิตที่ดีที่สุด</div>
                    </div>
                    <button class="btn-g" onclick="openModal('modalAddSchedule')">＋ เพิ่มแผนการปลูก</button>
                </div>

                {{-- ── Season Cards ── --}}
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.25rem;">

                    {{-- ฤดูฝน --}}
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:1.1rem;">
                        <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.6rem;">
                            <span style="font-size:1.5rem;">🌧️</span>
                            <div>
                                <div style="font-weight:700;font-size:.95rem;">ฤดูฝน</div>
                                <div style="font-size:.75rem;color:#64748b;">มิ.ย. – ก.ย.</div>
                            </div>
                        </div>
                        <div style="font-size:.75rem;color:#64748b;margin-bottom:.5rem;">พืชที่เหมาะสม:</div>
                        <div style="display:flex;flex-wrap:wrap;gap:.35rem;">
                            @foreach(['ข้าวเจ้า', 'ข้าวโพด', 'ฝ้าย', 'อ้อย'] as $c)
                                <span
                                    style="background:#dbeafe;color:#1d4ed8;border-radius:20px;font-size:.72rem;font-weight:600;padding:2px 9px;">{{ $c }}</span>
                            @endforeach
                        </div>
                    </div>

                    {{-- ฤดูหนาว --}}
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:1.1rem;">
                        <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.6rem;">
                            <span style="font-size:1.5rem;">❄️</span>
                            <div>
                                <div style="font-weight:700;font-size:.95rem;">ฤดูหนาว</div>
                                <div style="font-size:.75rem;color:#64748b;">พ.ย. – ก.พ.</div>
                            </div>
                        </div>
                        <div style="font-size:.75rem;color:#64748b;margin-bottom:.5rem;">พืชที่เหมาะสม:</div>
                        <div style="display:flex;flex-wrap:wrap;gap:.35rem;">
                            @foreach(['ถั่วเหลือง', 'มันฝรั่ง', 'กระเทียม', 'หอมหัวใหญ่'] as $c)
                                <span
                                    style="background:#e0e7ff;color:#4338ca;border-radius:20px;font-size:.72rem;font-weight:600;padding:2px 9px;">{{ $c }}</span>
                            @endforeach
                        </div>
                    </div>

                    {{-- ฤดูร้อน --}}
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:1.1rem;">
                        <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.6rem;">
                            <span style="font-size:1.5rem;">☀️</span>
                            <div>
                                <div style="font-weight:700;font-size:.95rem;">ฤดูร้อน</div>
                                <div style="font-size:.75rem;color:#64748b;">มี.ค. – พ.ค.</div>
                            </div>
                        </div>
                        <div style="font-size:.75rem;color:#64748b;margin-bottom:.5rem;">พืชที่เหมาะสม:</div>
                        <div style="display:flex;flex-wrap:wrap;gap:.35rem;">
                            @foreach(['มะเขือ', 'คะน้า', 'ผักโต', 'ผักตำลึง'] as $c)
                                <span
                                    style="background:#fef3c7;color:#92400e;border-radius:20px;font-size:.72rem;font-weight:600;padding:2px 9px;">{{ $c }}</span>
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- ── Filter Tabs + Table ── --}}
                <div class="card">
                    {{-- Tab filters --}}
                    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1rem;flex-wrap:wrap;">
                        <button id="ptab-all" onclick="filterPlanting('all')"
                            style="border:none;border-radius:20px;padding:4px 16px;font-size:.82rem;font-weight:700;cursor:pointer;background:#16a34a;color:#fff;font-family:Sarabun,sans-serif;">
                            ทั้งหมด ({{ count($schedules) }})
                        </button>
                        <button id="ptab-plan" onclick="filterPlanting('วางแผนแล้ว')"
                            style="border:1px solid #e2e8f0;border-radius:20px;padding:4px 16px;font-size:.82rem;font-weight:600;cursor:pointer;background:#fff;color:#374151;font-family:Sarabun,sans-serif;">
                            วางแผนแล้ว ({{ collect($schedules)->where('status', 'วางแผนแล้ว')->count() }})
                        </button>
                        <button id="ptab-grow" onclick="filterPlanting('กำลังปลูก')"
                            style="border:1px solid #e2e8f0;border-radius:20px;padding:4px 16px;font-size:.82rem;font-weight:600;cursor:pointer;background:#fff;color:#374151;font-family:Sarabun,sans-serif;">
                            กำลังปลูก ({{ collect($schedules)->where('status', 'กำลังปลูก')->count() }})
                        </button>
                        <button id="ptab-done" onclick="filterPlanting('เก็บเกี่ยวแล้ว')"
                            style="border:1px solid #e2e8f0;border-radius:20px;padding:4px 16px;font-size:.82rem;font-weight:600;cursor:pointer;background:#fff;color:#374151;font-family:Sarabun,sans-serif;">
                            เก็บเกี่ยวแล้ว ({{ collect($schedules)->where('status', 'เก็บเกี่ยวแล้ว')->count() }})
                        </button>
                    </div>

                    {{-- Table --}}
                    <table class="tbl" id="plantingTable">
                        <thead>
                            <tr>
                                <th>ชื่อพืช</th>
                                <th>วันที่ปลูก</th>
                                <th>วันที่เก็บเกี่ยว</th>
                                <th>หมายเหตุ</th>
                                <th>สถานะ</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $s)
                                <tr data-status="{{ $s->status }}">
                                    <td>
                                        <span style="margin-right:.4rem;">🌱</span>{{ $s->title }}
                                    </td>
                                    <td>{{ $s->start_date_fmt }}</td>
                                    <td>{{ $s->end_date_fmt }}</td>
                                    <td style="font-size:.8rem;color:#64748b;max-width:160px;">{{ $s->notes ?? '-' }}</td>
                                    <td>
                                        @if($s->status === 'วางแผนแล้ว')
                                            <span class="ps-plan">วางแผนแล้ว</span>
                                        @elseif($s->status === 'กำลังปลูก')
                                            <span class="ps-grow">กำลังปลูก</span>
                                        @else
                                            <span class="ps-done">เก็บเกี่ยวแล้ว</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="ibtn"
                                            onclick="editSchedule({{ $s->id }}, '{{ addslashes($s->title) }}', '{{ addslashes($s->category ?? '') }}', '{{ $s->start_date }}', '{{ $s->end_date }}', '{{ $s->area ?? '' }}', '{{ $s->expected_yield ?? '' }}', '{{ $s->status }}', '{{ addslashes($s->notes ?? '') }}')"
                                            title="แก้ไข">✏️</button>
                                        <button class="ibtn"
                                            onclick="deleteSchedule({{ $s->id }}, '{{ addslashes($s->title) }}')"
                                            title="ลบ">🗑️</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="empty">ยังไม่มีแผนการปลูก กด "เพิ่มแผนการปลูก"</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>


            {{-- ===== วิเคราะห์ราคา ===== --}}
            <section id="sec-price" class="sec">
                <div class="ph">
                    <div>
                        <div class="ptitle">วิเคราะห์ราคาและแนวโน้ม</div>
                        <div class="psub">ราคาจาก external_prices</div>
                    </div>
                </div>
                @if($externalPrices->count() === 0)
                    <div class="card">
                        <div class="empty">ยังไม่มีข้อมูลราคาใน external_prices</div>
                    </div>
                @else
                    <div class="price-krow">
                        <div class="pkcard">
                            <div class="pklbl">บันทึกราคาทั้งหมด</div>
                            <div class="pkval vg">{{ $externalPrices->count() }}</div>
                            <div class="pksub">รายการ</div>
                        </div>
                        <div class="pkcard">
                            <div class="pklbl">ราคาเฉลี่ย</div>
                            <div class="pkval vb">฿{{ number_format($externalPrices->avg('price'), 2) }}</div>
                            <div class="pksub">บาท</div>
                        </div>
                        <div class="pkcard">
                            <div class="pklbl">ราคาสูงสุด</div>
                            <div class="pkval vo">฿{{ number_format($externalPrices->max('price'), 2) }}</div>
                            <div class="pksub">บาท</div>
                        </div>
                        <div class="pkcard">
                            <div class="pklbl">ราคาต่ำสุด</div>
                            <div class="pkval vr">฿{{ number_format($externalPrices->min('price'), 2) }}</div>
                            <div class="pksub">บาท</div>
                        </div>
                    </div>
                    <div class="chart-card" style="margin-bottom:1.5rem;">
                        <div class="chtitle">แนวโน้มราคา</div><canvas id="priceTrendChart" height="160"></canvas>
                    </div>
                    <div class="card">
                        <div class="ctitle">รายการราคาล่าสุด</div>
                        <table class="tbl">
                            <thead>
                                <tr>
                                    <th>ตลาด</th>
                                    <th>ราคา (บาท)</th>
                                    <th>หน่วย</th>
                                    <th>ประเภทการขาย</th>
                                    <th>วันที่</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($externalPrices->take(20) as $ep)
                                    <tr>
                                        <td>{{ $ep->market_name ?? '-' }}</td>
                                        <td>฿{{ number_format($ep->price, 2) }}</td>
                                        <td>{{ $ep->unit ?? '-' }}</td>
                                        <td>{{ $ep->sell_type ?? '-' }}</td>
                                        <td>{{ $ep->price_date ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>

            {{-- ===== เปรียบเทียบราคา ===== --}}
            <section id="sec-compare" class="sec">
                <div class="ph">
                    <div>
                        <div class="ptitle">เปรียบเทียบราคา</div>
                        <div class="psub">เปรียบเทียบราคาสินค้าของคุณกับราคาตลาด</div>
                    </div>
                </div>
                @if($byCategory->count() > 0)
                    <div class="chart-card" style="margin-bottom:1.5rem;">
                        <div class="chtitle">สินค้าในสต็อกตามหมวดหมู่</div><canvas id="compareChart" height="200"></canvas>
                    </div>
                @endif
                <div class="card">
                    <div class="ctitle">ราคาคำแนะนำจากระบบ</div>
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>สินค้า</th>
                                <th>ราคาเฉลี่ย</th>
                                <th>ราคาล่าสุด</th>
                                <th>แนวโน้ม</th>
                                <th>คำแนะนำ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($priceRecs as $r)
                                <tr>
                                    <td>{{ $r->product_id }}</td>
                                    <td>฿{{ number_format($r->average_price, 2) }}</td>
                                    <td>฿{{ number_format($r->latest_price, 2) }}</td>
                                    <td>@if($r->trend === 'RISING')<span class="tup">↗
                                    ขึ้น</span>@elseif($r->trend === 'FALLING')<span class="tdown">↘
                                            ลง</span>@else<span style="color:#64748b;font-size:.78rem;">→ คงที่</span>@endif
                                    </td>
                                    <td style="font-size:.82rem;color:#64748b;">{{ $r->recommendation ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="empty">ยังไม่มีข้อมูลราคาคำแนะนำ</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- ===== คำแนะนำ ===== --}}
            <section id="sec-recommend" class="sec">
                <div class="ph">
                    <div>
                        <div class="ptitle">💡 คำแนะนำเพื่อเพิ่มผลผลิต</div>
                        <div class="psub">วิเคราะห์จากข้อมูลในระบบ</div>
                    </div>
                </div>
                @if($priceRecs->count() > 0)
                    @foreach($priceRecs as $r)
                        <div
                            class="rec-card {{ $r->trend === 'RISING' ? 'recommend-info' : ($r->trend === 'FALLING' ? 'recommend-warn' : 'recommend-blue') }}">
                            <div class="ctitle"
                                style="color:{{ $r->trend === 'RISING' ? '#16a34a' : ($r->trend === 'FALLING' ? '#f97316' : '#3b82f6') }};">
                                {{ $r->trend === 'RISING' ? '✅ ราคากำลังขึ้น' : ($r->trend === 'FALLING' ? '⚠️ ราคากำลังลง' : '📌 ราคาคงที่') }}
                            </div>
                            <p style="font-size:.9rem;line-height:1.8;color:#374151;">
                                {{ $r->recommendation ?? 'ไม่มีคำแนะนำเพิ่มเติม' }}</p>
                        </div>
                    @endforeach
                @else
                    @if($stats['low_stock'] > 0)
                        <div class="rec-card recommend-warn">
                            <div class="ctitle" style="color:#f97316;">⚠️ สินค้าใกล้หมด</div>
                            <p style="font-size:.9rem;line-height:1.8;">มีสินค้า {{ $stats['low_stock'] }} รายการที่ใกล้หมด
                                ควรเติมสต็อกโดยด่วน</p>
                    </div>@endif
                    @if($stats['active_schedules'] > 0)
                        <div class="rec-card recommend-info">
                            <div class="ctitle" style="color:#16a34a;">✅ กำลังดำเนินการ</div>
                            <p style="font-size:.9rem;line-height:1.8;">มี {{ $stats['active_schedules'] }}
                                แปลงที่กำลังเพาะปลูกอยู่ ดูแลรักษาให้สม่ำเสมอ</p>
                    </div>@endif
                    @if($stats['low_stock'] === 0 && $stats['active_schedules'] === 0 && $stats['total_products'] === 0)
                        <div class="card">
                            <div class="empty">เพิ่มข้อมูลสินค้าและแผนการปลูกเพื่อรับคำแนะนำ</div>
                    </div>@endif
                @endif
            </section>

            {{-- ===== สมาชิก ===== --}}
            <section id="sec-members" class="sec">
                <div class="ph">
                    <div>
                        <div class="ptitle">👥 สมาชิกใน Workspace</div>
                        <div class="psub">จัดการสมาชิกและดูรหัสเชิญ — {{ $workspaceName }}</div>
                    </div>
                </div>

                {{-- Invite Code Card --}}
                <div class="card"
                    style="margin-bottom:1rem;background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-color:#86efac;">
                    <div class="ctitle" style="color:#15803d;">🔑 รหัส Workspace</div>
                    <p style="font-size:.85rem;color:#64748b;margin-bottom:.75rem;">แชร์รหัสนี้ให้ผู้ที่ต้องการเข้าร่วม
                        → ไปที่ <strong>Hub → เข้าร่วม Workspace → กรอกรหัส</strong></p>
                    <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
                        <div class="code-box" id="wsCodeDisplay">{{ $wsCode ?? 'N/A' }}</div>
                        <button class="btn-g" onclick="copyCode()" id="copyBtn">📋 คัดลอกรหัส</button>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="krow" style="margin-bottom:1rem;">
                    <div class="kcard">
                        <div>
                            <div class="klbl">สมาชิกทั้งหมด</div>
                            <div class="kval">{{ $totalMembers }}</div>
                            <div class="ksub">คน</div>
                        </div>
                        <div class="kico b">👥</div>
                    </div>
                    <div class="kcard">
                        <div>
                            <div class="klbl">Owner</div>
                            <div class="kval">1</div>
                            <div class="ksub">เจ้าของ</div>
                        </div>
                        <div class="kico g">👑</div>
                    </div>
                    <div class="kcard">
                        <div>
                            <div class="klbl">Employee</div>
                            <div class="kval blue">{{ $employeeCount }}</div>
                            <div class="ksub">สมาชิก</div>
                        </div>
                        <div class="kico b">🧑‍🌾</div>
                    </div>
                </div>

                {{-- Member List --}}
                <div class="card">
                    <div class="ctitle">รายชื่อสมาชิก</div>
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>ชื่อ</th>
                                <th>อีเมล</th>
                                <th>บทบาท</th>
                                <th>เข้าร่วมเมื่อ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($owner)
                                <tr style="background:#f0fdf4;">
                                    <td>
                                        <div style="display:flex;align-items:center;gap:.6rem;">
                                            <div class="avatar">{{ strtoupper(substr($owner->full_name, 0, 2)) }}</div>
                                            <strong>{{ $owner->full_name }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $owner->email }}</td>
                                    <td><span class="badge">Owner</span></td>
                                    <td>{{ \Carbon\Carbon::parse($owner->created_at)->locale('th')->isoFormat('D MMM YYYY') }}
                                    </td>
                                </tr>
                            @endif
                            @forelse($members as $m)
                                @if(($m['id'] ?? null) !== ($owner->id ?? null))
                                    <tr>
                                        <td>
                                            <div style="display:flex;align-items:center;gap:.6rem;">
                                                <div class="avatar" style="background:#eff6ff;color:#3b82f6;">
                                                    {{ strtoupper(substr($m['full_name'], 0, 2)) }}</div>{{ $m['full_name'] }}
                                            </div>
                                        </td>
                                        <td>{{ $m['email'] }}</td>
                                        <td><span class="tblue tag">Employee</span></td>
                                        <td>{{ \Carbon\Carbon::parse($m['joined_at'])->locale('th')->isoFormat('D MMM YYYY') }}
                                        </td>
                                    </tr>
                                @endif
                            @empty
                            @endforelse
                            @if($employeeCount === 0)
                                <tr>
                                    <td colspan="4">
                                        <div class="empty">ยังไม่มีสมาชิก — แชร์รหัสด้านบนให้ผู้ร่วมงานใช้เข้าร่วม</div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- ===== ประวัติ ===== --}}
            <section id="sec-history" class="sec">
                <div class="ph">
                    <div>
                        <div class="ptitle">📋 ประวัติการเปลี่ยนแปลง</div>
                        <div class="psub">บันทึกกิจกรรมทั้งหมดใน Workspace</div>
                    </div>
                </div>
                <div class="card">
                    <table class="tbl">
                        <thead>
                            <tr>
                                <th>วันที่/เวลา</th>
                                <th>ผู้ดำเนินการ</th>
                                <th>กิจกรรม</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activityLogs as $log)
                                <tr>
                                    <td style="white-space:nowrap;font-size:.82rem;">{{ $log->created_at_fmt }}</td>
                                    <td>{{ $log->user_name ?? 'ระบบ' }}</td>
                                    <td>{{ $log->action }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="empty">ยังไม่มีประวัติการเปลี่ยนแปลง</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

        </main>
    </div>

    {{-- MODAL เพิ่มสินค้า --}}
    <div class="overlay" id="modalAddProduct" onclick="closeIfBg(event,this)">
        <div class="modal"><button class="close-x" onclick="closeModal('modalAddProduct')">✕</button>
            <h3>＋ เพิ่มสินค้าใหม่</h3>
            <form method="POST" action="{{ route('products.store') }}">@csrf
                <div class="fg-row">
                    <div class="fg"><label>ชื่อสินค้า *</label><input name="name" required></div>
                    <div class="fg"><label>หมวดหมู่ *</label><select name="category" required>
                            <option value="">-- เลือก --</option>
                            <option>ข้าว</option>
                            <option>ผลไม้</option>
                            <option>ผักสด</option>
                            <option>พืชผล</option>
                            <option>อื่นๆ</option>
                        </select></div>
                </div>
                <div class="fg-row">
                    <div class="fg"><label>จำนวน *</label><input name="quantity" type="number" min="0" step="0.01"
                            required></div>
                    <div class="fg"><label>หน่วย *</label><input name="unit" required placeholder="กิโลกรัม, หวี, ...">
                    </div>
                </div>
                <div class="fg"><label>จำนวนขั้นต่ำ (แจ้งเตือนใกล้หมด)</label><input name="min_stock" type="number"
                        min="0" step="0.01" placeholder="0 = ไม่แจ้งเตือน"></div>
                <div class="fg"><label>รายละเอียด</label><textarea name="description" rows="2"></textarea></div>
                <div class="mfoot"><button type="button" class="btn-gray"
                        onclick="closeModal('modalAddProduct')">ยกเลิก</button><button type="submit"
                        class="btn-g">บันทึก</button></div>
            </form>
        </div>
    </div>

    {{-- MODAL แก้ไขสินค้า --}}
    <div class="overlay" id="modalEditProduct" onclick="closeIfBg(event,this)">
        <div class="modal"><button class="close-x" onclick="closeModal('modalEditProduct')">✕</button>
            <h3>✏️ แก้ไขสินค้า</h3>
            <form method="POST" id="formEditProduct">@csrf
                <div class="fg-row">
                    <div class="fg"><label>ชื่อสินค้า *</label><input name="name" id="ep_name" required></div>
                    <div class="fg"><label>หมวดหมู่ *</label><select name="category" id="ep_cat" required>
                            <option value="">-- เลือก --</option>
                            <option>ข้าว</option>
                            <option>ผลไม้</option>
                            <option>ผักสด</option>
                            <option>พืชผล</option>
                            <option>อื่นๆ</option>
                        </select></div>
                </div>
                <div class="fg-row">
                    <div class="fg"><label>จำนวน *</label><input name="quantity" id="ep_qty" type="number" min="0"
                            step="0.01" required></div>
                    <div class="fg"><label>หน่วย *</label><input name="unit" id="ep_unit" required></div>
                </div>
                <div class="fg"><label>จำนวนขั้นต่ำ</label><input name="min_stock" id="ep_min" type="number" min="0"
                        step="0.01"></div>
                <div class="fg"><label>รายละเอียด</label><textarea name="description" id="ep_desc" rows="2"></textarea>
                </div>
                <div class="mfoot"><button type="button" class="btn-gray"
                        onclick="closeModal('modalEditProduct')">ยกเลิก</button><button type="submit"
                        class="btn-g">อัปเดต</button></div>
            </form>
        </div>
    </div>

    {{-- MODAL ลบสินค้า --}}
    <div class="overlay" id="modalDelProduct" onclick="closeIfBg(event,this)">
        <div class="modal" style="max-width:380px;text-align:center;">
            <div style="font-size:3rem;margin-bottom:.75rem;">🗑️</div>
            <h3 style="margin-bottom:.5rem;">ยืนยันการลบ</h3>
            <p id="delProductName" style="font-size:.9rem;color:#64748b;margin-bottom:1.5rem;"></p>
            <form method="POST" id="formDelProduct">@csrf
                <div class="mfoot" style="justify-content:center;"><button type="button" class="btn-gray"
                        onclick="closeModal('modalDelProduct')">ยกเลิก</button><button type="submit"
                        class="btn-r">ลบ</button></div>
            </form>
        </div>
    </div>

    {{-- MODAL เพิ่มแผนปลูก --}}
    <div class="overlay" id="modalAddSchedule" onclick="closeIfBg(event,this)">
        <div class="modal"><button class="close-x" onclick="closeModal('modalAddSchedule')">✕</button>
            <h3>เพิ่มแผนการปลูกใหม่</h3>
            <form method="POST" action="{{ route('schedules.store') }}">@csrf
                <div class="fg-row">
                    <div class="fg"><label>ชื่อพืช *</label><input name="title" placeholder="ระบุชื่อสิ่งที่จะปลูก" required></div>
                    <div class="fg"><label>หมวดหมู่ *</label><select name="category" required>
                        <option value="">เลือกหมวดหมู่</option>
                        <option>ข้าว</option><option>ผักสด</option><option>ผลไม้</option>
                        <option>พืชไร่</option><option>อ้อย</option><option>ถั่ว</option><option>อื่นๆ</option>
                    </select></div>
                </div>
                <div class="fg-row">
                    <div class="fg"><label>วันที่ปลูก *</label><input name="start_date" type="date" required></div>
                    <div class="fg"><label>วันที่เก็บเกี่ยว *</label><input name="end_date" type="date" required></div>
                </div>
                <div class="fg-row">
                    <div class="fg"><label>พื้นที่ (ไร่) *</label><input name="area" type="number" min="0" step="0.1" placeholder="0.0" required></div>
                    <div class="fg"><label>ผลผลิตประมาณ (กิโลกรัม) *</label><input name="expected_yield" type="number" min="0" step="0.1" placeholder="0.0" required></div>
                </div>
                <div class="fg"><label>สถานะ *</label><select name="status" required>
                    <option>วางแผนแล้ว</option>
                    <option>กำลังปลูก</option>
                    <option>เก็บเกี่ยวแล้ว</option>
                </select></div>
                <div class="fg"><label>หมายเหตุ</label><textarea name="notes" rows="2" placeholder="เพิ่มข้อมูลเพิ่มเติม เช่น สภาพดิน ปุ๋ยที่ใช้ เป็นต้น"></textarea></div>
                <div class="mfoot">
                    <button type="button" class="btn-gray" onclick="closeModal('modalAddSchedule')">ยกเลิก</button>
                    <button type="submit" class="btn-g">เพิ่มแผนการปลูก</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL แก้ไขแผนปลูก --}}
    <div class="overlay" id="modalEditSchedule" onclick="closeIfBg(event,this)">
        <div class="modal"><button class="close-x" onclick="closeModal('modalEditSchedule')">✕</button>
            <h3>✏️ แก้ไขแผนการปลูก</h3>
            <form method="POST" id="formEditSchedule">@csrf
                <div class="fg-row">
                    <div class="fg"><label>ชื่อพืช *</label><input name="title" id="es_title" required></div>
                    <div class="fg"><label>หมวดหมู่ *</label><select name="category" id="es_category" required>
                        <option value="">เลือกหมวดหมู่</option>
                        <option>ข้าว</option><option>ผักสด</option><option>ผลไม้</option>
                        <option>พืชไร่</option><option>อ้อย</option><option>ถั่ว</option><option>อื่นๆ</option>
                    </select></div>
                </div>
                <div class="fg-row">
                    <div class="fg"><label>วันที่ปลูก *</label><input name="start_date" id="es_start" type="date" required></div>
                    <div class="fg"><label>วันที่เก็บเกี่ยว *</label><input name="end_date" id="es_end" type="date" required></div>
                </div>
                <div class="fg-row">
                    <div class="fg"><label>พื้นที่ (ไร่) *</label><input name="area" id="es_area" type="number" min="0" step="0.1" required></div>
                    <div class="fg"><label>ผลผลิตประมาณ (กิโลกรัม) *</label><input name="expected_yield" id="es_yield" type="number" min="0" step="0.1" required></div>
                </div>
                <div class="fg"><label>สถานะ *</label><select name="status" id="es_status" required>
                    <option>วางแผนแล้ว</option>
                    <option>กำลังปลูก</option>
                    <option>เก็บเกี่ยวแล้ว</option>
                </select></div>
                <div class="fg"><label>หมายเหตุ</label><textarea name="notes" id="es_notes" rows="2"></textarea></div>
                <div class="mfoot">
                    <button type="button" class="btn-gray" onclick="closeModal('modalEditSchedule')">ยกเลิก</button>
                    <button type="submit" class="btn-g">อัปเดต</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL ลบแผนปลูก --}}
    <div class="overlay" id="modalDelSchedule" onclick="closeIfBg(event,this)">
        <div class="modal" style="max-width:380px;text-align:center;">
            <div style="font-size:3rem;margin-bottom:.75rem;">🗑️</div>
            <h3 style="margin-bottom:.5rem;">ยืนยันการลบ</h3>
            <p id="delScheduleName" style="font-size:.9rem;color:#64748b;margin-bottom:1.5rem;"></p>
            <form method="POST" id="formDelSchedule">@csrf
                <div class="mfoot" style="justify-content:center;"><button type="button" class="btn-gray"
                        onclick="closeModal('modalDelSchedule')">ยกเลิก</button><button type="submit"
                        class="btn-r">ลบ</button></div>
            </form>
        </div>
    </div>

    <script>
        // ── Filter Planting Table ──────────────────────────────
        function filterPlanting(status) {
            var rows = document.querySelectorAll('#plantingTable tbody tr[data-status]');
            rows.forEach(function(r) {
                r.style.display = (status === 'all' || r.dataset.status === status) ? '' : 'none';
            });
            // Update tab styles
            var tabs = { 'all':'ptab-all', 'วางแผนแล้ว':'ptab-plan', 'กำลังปลูก':'ptab-grow', 'เก็บเกี่ยวแล้ว':'ptab-done' };
            Object.keys(tabs).forEach(function(k) {
                var el = document.getElementById(tabs[k]);
                if (!el) return;
                if (k === status) {
                    el.style.background = '#16a34a'; el.style.color = '#fff'; el.style.border = 'none';
                } else {
                    el.style.background = '#fff'; el.style.color = '#374151'; el.style.border = '1px solid #e2e8f0';
                }
            });
        }

        var chartDone = {};
        function show(id) {
            document.querySelectorAll('.sec').forEach(s => s.classList.remove('active'));
            document.querySelectorAll('.si').forEach(s => s.classList.remove('active'));
            document.getElementById('sec-' + id).classList.add('active');
            document.getElementById('nav-' + id).classList.add('active');
            if (!chartDone[id]) { initChart(id); chartDone[id] = true; }
        }

        var catLabels = @json($byCategory->pluck('category'));
        var catCounts = @json($byCategory->pluck('count'));
        var catQtys = @json($byCategory->pluck('total_qty'));
        var priceLabels = @json($externalPrices->pluck('price_date'));
        var priceData = @json($externalPrices->pluck('price'));

        function initChart(id) {
            if (id === 'summary') {
                new Chart(document.getElementById('sumBarChart'), { type: 'bar', data: { labels: catLabels, datasets: [{ label: 'จำนวนรายการ', data: catCounts, backgroundColor: '#10B981', borderRadius: 6 }] }, options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } } });
                new Chart(document.getElementById('sumPieChart'), { type: 'pie', data: { labels: catLabels, datasets: [{ data: catQtys, backgroundColor: ['#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6'], borderWidth: 0 }] }, options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { font: { family: 'Sarabun' } } } } } });
            }
            if (id === 'price' && priceData.length > 0) {
                new Chart(document.getElementById('priceTrendChart'), { type: 'line', data: { labels: priceLabels.slice(0, 20).reverse(), datasets: [{ label: 'ราคา', data: priceData.slice(0, 20).reverse(), borderColor: '#3B82F6', tension: 0.4, fill: false }] }, options: { responsive: true, plugins: { legend: { display: false } } } });
            }
            if (id === 'compare' && catLabels.length > 0) {
                new Chart(document.getElementById('compareChart'), { type: 'bar', data: { labels: catLabels, datasets: [{ label: 'จำนวนในสต็อก', data: catQtys, backgroundColor: '#10B981', borderRadius: 6 }] }, options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { font: { family: 'Sarabun' } } } }, scales: { y: { beginAtZero: true } } } });
            }
        }

        window.addEventListener('DOMContentLoaded', () => {
            if (document.getElementById('overviewPriceChart')) {
                new Chart(document.getElementById('overviewPriceChart'), { type: 'line', data: { labels: priceLabels.slice(0, 12).reverse(), datasets: [{ label: 'ราคา', data: priceData.slice(0, 12).reverse(), borderColor: '#3B82F6', backgroundColor: 'rgba(59,130,246,.08)', tension: 0.4, fill: true }] }, options: { responsive: true, plugins: { legend: { display: false } } } });
            }
            if (document.getElementById('overviewProdChart') && catLabels.length > 0) {
                new Chart(document.getElementById('overviewProdChart'), { type: 'bar', data: { labels: catLabels, datasets: [{ label: 'จำนวน', data: catQtys, backgroundColor: '#10B981', borderRadius: 6 }] }, options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } } });
            }
            chartDone['overview'] = true;
            var sec = new URLSearchParams(location.search).get('section');
            if (sec) show(sec);
        });

        function openModal(id) { document.getElementById(id).classList.add('open'); }
        function closeModal(id) { document.getElementById(id).classList.remove('open'); }
        function closeIfBg(e, el) { if (e.target === el) closeModal(el.id); }

        function filterStock() {
            var q = document.getElementById('stockSearch').value.toLowerCase();
            var cat = document.getElementById('stockCat').value;
            document.querySelectorAll('#stockTable tbody tr').forEach(function (tr) {
                var n = tr.dataset.name || '', c = tr.dataset.cat || '';
                tr.style.display = (n.includes(q) && (!cat || c === cat)) ? '' : 'none';
            });
        }

        function editProduct(id, name, cat, unit, qty, min, desc) {
            document.getElementById('ep_name').value = name;
            document.getElementById('ep_cat').value = cat;
            document.getElementById('ep_unit').value = unit;
            document.getElementById('ep_qty').value = qty;
            document.getElementById('ep_min').value = min;
            document.getElementById('ep_desc').value = desc;
            document.getElementById('formEditProduct').action = '/products/' + id + '/update';
            openModal('modalEditProduct');
        }
        function deleteProduct(id, name) {
            document.getElementById('delProductName').textContent = 'คุณต้องการลบ "' + name + '" ใช่หรือไม่?';
            document.getElementById('formDelProduct').action = '/products/' + id + '/delete';
            openModal('modalDelProduct');
        }

        function editSchedule(id, title, category, start, end, area, expectedYield, status, notes) {
            document.getElementById('es_title').value = title;
            document.getElementById('es_category').value = category || '';
            document.getElementById('es_start').value = start;
            document.getElementById('es_end').value = end;
            document.getElementById('es_area').value = area || '';
            document.getElementById('es_yield').value = expectedYield || '';
            document.getElementById('es_status').value = status;
            document.getElementById('es_notes').value = notes;
            document.getElementById('formEditSchedule').action = '/schedules/' + id + '/update';
            openModal('modalEditSchedule');
        }
        function deleteSchedule(id, title) {
            document.getElementById('delScheduleName').textContent = 'คุณต้องการลบ "' + title + '" ใช่หรือไม่?';
            document.getElementById('formDelSchedule').action = '/schedules/' + id + '/delete';
            openModal('modalDelSchedule');
        }

        function copyCode() {
            var code = document.getElementById('wsCodeDisplay').textContent.trim();
            if (navigator.clipboard) {
                navigator.clipboard.writeText(code).then(function () {
                    var btn = document.getElementById('copyBtn');
                    btn.textContent = '✅ คัดลอกแล้ว!';
                    setTimeout(function () { btn.textContent = '📋 คัดลอกรหัส'; }, 2000);
                });
            } else {
                showToast('รหัส: ' + code);
            }
        }

        function doLogout() {
            if (confirm('ต้องการออกจากระบบหรือไม่?')) document.getElementById('logoutForm').submit();
        }

        function showToast(msg, isErr) {
            var t = document.getElementById('toast');
            t.textContent = msg;
            t.className = 'toast' + (isErr ? ' err' : '');
            t.style.display = 'block';
            setTimeout(() => { t.style.display = 'none'; }, 3500);
        }
    </script>
</body>

</html>