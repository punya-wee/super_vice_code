<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function enterWorkspace($id)
    {
        $workspace = DB::table('workspaces')
            ->where('id', $id)
            ->where('owner_id', auth()->id())
            ->first();

        if (!$workspace) {
            return redirect()->route('hub')->with('error', 'ไม่พบ Workspace');
        }

        session(['current_workspace_id' => $workspace->id, 'current_workspace_name' => $workspace->name]);
        return redirect()->route('dashboard');
    }

    public function enterMemberWorkspace($id)
    {
        // ตรวจสอบว่าเป็นสมาชิก
        $member = DB::table('workspace_members')
            ->where('workspace_id', $id)
            ->where('user_id', auth()->id())
            ->first();

        $workspace = DB::table('workspaces')->where('id', $id)->first();

        if (!$workspace || !$member) {
            return redirect()->route('hub')->with('error', 'ไม่มีสิทธิ์เข้าถึง Workspace นี้');
        }

        session(['current_workspace_id' => $workspace->id, 'current_workspace_name' => $workspace->name]);
        return redirect()->route('dashboard');
    }

    public function index()
    {
        if (!session('current_workspace_id')) {
            return redirect()->route('hub');
        }

        $wsId = session('current_workspace_id');
        $today = Carbon::today();

        // ─── Stock Items (products + inventory) ──────────────
        $stockItems = DB::table('products')
            ->leftJoin('inventory', 'products.id', '=', 'inventory.product_id')
            ->where('products.workspace_id', $wsId)
            ->select(
                'products.id',
                'products.name',
                'products.category',
                'products.unit',
                'products.description',
                'products.created_at',
                DB::raw('COALESCE(inventory.quantity, 0) as quantity'),
                DB::raw('COALESCE(inventory.min_stock_level, 0) as min_stock_level')
            )
            ->orderBy('products.created_at', 'desc')
            ->get()
            ->map(function ($p) {
                $p->is_low = $p->min_stock_level > 0 && $p->quantity < $p->min_stock_level;
                $p->status = $p->quantity <= 0 ? 'หมด' : ($p->is_low ? 'ใกล้หมด' : 'มีสต็อก');
                return $p;
            });

        $lowStockItems = $stockItems->where('is_low', true)->values();

        // ─── By Category ──────────────────────────────────────
        $byCategory = $stockItems->groupBy('category')->map(function ($items, $cat) {
            return [
                'category' => $cat ?: 'อื่นๆ',
                'count' => $items->count(),
                'total_qty' => round($items->sum('quantity'), 2),
            ];
        })->values();

        // ─── Schedules ────────────────────────────────────────
        $schedules = DB::table('schedules')
            ->leftJoin('products', 'schedules.product_id', '=', 'products.id')
            ->where('schedules.workspace_id', $wsId)
            ->select(
                'schedules.*',
                DB::raw('COALESCE(products.name, schedules.title) as crop_name'),
                'products.category'
            )
            ->orderBy('schedules.start_date', 'desc')
            ->get()
            ->map(function ($s) use ($today) {
                $start = Carbon::parse($s->start_date);
                $end = Carbon::parse($s->end_date);

                // ใช้สถานะจาก DB แต่เมื่อถึงเวลาสิ้นสุด (ผ่าน end_date ไปแล้ว) ให้นับว่าเก็บเกี่ยวแล้วอัตโนมัติ
                $dbStatus = $s->status ?? 'วางแผนแล้ว';
                if ($dbStatus !== 'เก็บเกี่ยวแล้ว' && (clone $end)->startOfDay()->lt($today)) {
                    $s->status = 'เก็บเกี่ยวแล้ว';
                    // อัปเดต DB แบบเงียบๆ เพื่อให้ข้อมูลตรงกัน (ทางเลือก)
                    DB::table('schedules')->where('id', $s->id)->update(['status' => 'เก็บเกี่ยวแล้ว']);
                } else {
                    $s->status = $dbStatus;
                }

                $s->days_left = max(0, $today->diffInDays($end, false));
                $s->start_date_fmt = $start->locale('th')->isoFormat('D MMM YYYY');
                $s->end_date_fmt = $end->locale('th')->isoFormat('D MMM YYYY');
                return $s;
            });

        $activeSchedules = $schedules->where('status', 'กำลังปลูก')->count();
        $upcomingHarvest = $schedules->where('status', 'กำลังปลูก')
            ->sortBy('end_date')->take(5)->values();

        // ─── Activity Logs ────────────────────────────────────
        $activityLogs = DB::table('activity_logs')
            ->leftJoin('users', 'activity_logs.user_id', '=', 'users.id')
            ->where('activity_logs.workspace_id', $wsId)
            ->select('activity_logs.*', 'users.full_name as user_name')
            ->orderBy('activity_logs.created_at', 'desc')
            ->limit(30)
            ->get()
            ->map(function ($log) {
                $log->created_at_fmt = Carbon::parse($log->created_at)
                    ->locale('th')->isoFormat('D MMM YYYY HH:mm');
                return $log;
            });

        // ─── External Prices ──────────────────────────────────
        $externalPrices = DB::table('external_prices')
            ->orderBy('price_date', 'desc')
            ->limit(60)
            ->get();

        // ─── Price Recommendations ────────────────────────────
        $priceRecs = DB::table('price_recommendations')
            ->where('workspace_id', $wsId)
            ->orderBy('generated_at', 'desc')
            ->get();

        // ─── Members ──────────────────────────────────────────
        $workspace = DB::table('workspaces')->where('id', $wsId)->first();
        $owner = DB::table('users')->where('id', $workspace->owner_id ?? 0)->first();
        $members = DB::table('workspace_members')
            ->join('users', 'workspace_members.user_id', '=', 'users.id')
            ->where('workspace_members.workspace_id', $wsId)
            ->select('users.id', 'users.full_name', 'users.email', 'workspace_members.joined_at')
            ->get()
            ->map(fn($m) => ['role' => 'สมาชิก'] + (array) $m);

        // ─── Workspace invite code (encode wsId to 6-char uppercase) ─
        $wsCode = strtoupper(str_pad(base_convert($wsId + 65536, 10, 36), 6, '0', STR_PAD_LEFT));
        $employeeCount = $members->count();
        $totalMembers = $employeeCount + 1; // +1 owner

        return view('dashboard', [
            'workspaceName' => session('current_workspace_name', 'Workspace'),
            'workspace' => $workspace,
            'wsCode' => $wsCode,
            'owner' => $owner,
            'totalMembers' => $totalMembers,
            'employeeCount' => $employeeCount,
            'stats' => [
                'total_products' => $stockItems->count(),
                'low_stock' => $lowStockItems->count(),
                'active_schedules' => $activeSchedules,
            ],
            'stockItems' => $stockItems,
            'lowStockItems' => $lowStockItems,
            'byCategory' => $byCategory,
            'schedules' => $schedules,
            'upcomingHarvest' => $upcomingHarvest,
            'activityLogs' => $activityLogs,
            'externalPrices' => $externalPrices,
            'priceRecs' => $priceRecs,
            'members' => $members,
            'allProducts' => DB::table('products')->where('workspace_id', $wsId)->get(),
        ]);
    }

    public function getStats()
    {
        return $this->index(); // ไม่ใช้ AJAX ตอนนี้
    }
}
