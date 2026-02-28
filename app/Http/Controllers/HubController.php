<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HubController extends Controller
{
    /**
     * Show hub page
     */
    public function index()
    {
        $userId = auth()->id();

        // Workspaces ที่เป็นเจ้าของ
        $ownedWorkspaces = DB::table('workspaces')
            ->where('owner_id', $userId)
            ->get();

        // Workspaces ที่เป็นสมาชิก (ไม่ใช่เจ้าของ)
        $memberWorkspaces = DB::table('workspace_members')
            ->join('workspaces', 'workspace_members.workspace_id', '=', 'workspaces.id')
            ->join('users as owner', 'workspaces.owner_id', '=', 'owner.id')
            ->where('workspace_members.user_id', $userId)
            ->whereNotIn('workspaces.id', $ownedWorkspaces->pluck('id')->toArray() ?: [0])
            ->select('workspaces.*', 'owner.full_name as owner_name', 'workspace_members.joined_at')
            ->get();

        // รวม workspaces ทั้งหมด
        $workspaces = $ownedWorkspaces->map(fn($w) => (array) $w + ['is_owner' => true]);

        return view('hub', [
            'workspaces' => $ownedWorkspaces,
            'memberWorkspaces' => $memberWorkspaces,
            'user' => auth()->user(),
        ]);
    }

    /**
     * Create new workspace
     */
    public function createWorkspace(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $workspace = DB::table('workspaces')->insertGetId([
                'name' => $validated['name'],
                'owner_id' => auth()->id(),
                'created_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'สร้าง Workspace สำเร็จ!',
                'workspaceId' => $workspace,
                'redirect' => '/hub'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Workspace creation error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Join existing workspace via invite code
     */
    public function joinWorkspace(Request $request)
    {
        $request->validate(['code' => 'required|string|min:4'], [
            'code.required' => 'กรุณากรอกรหัส Workspace',
            'code.min' => 'รหัสไม่ถูกต้อง',
        ]);

        $code = strtoupper(trim($request->code));

        // Decode invite code → workspace ID
        $wsId = (int) base_convert(strtolower($code), 36, 10) - 65536;

        if ($wsId <= 0) {
            return redirect()->route('hub')->with('error', 'รหัส Workspace ไม่ถูกต้อง');
        }

        $workspace = DB::table('workspaces')->where('id', $wsId)->first();

        if (!$workspace) {
            return redirect()->route('hub')->with('error', 'ไม่พบ Workspace - กรุณาตรวจสอบรหัสอีกครั้ง');
        }

        $userId = auth()->id();

        // ถ้าเป็นเจ้าของอยู่แล้ว → แค่ set session แล้วเข้า dashboard
        if ($workspace->owner_id == $userId) {
            session(['current_workspace_id' => $workspace->id, 'current_workspace_name' => $workspace->name]);
            return redirect()->route('dashboard')->with('success', "เข้าสู่ {$workspace->name} สำเร็จ");
        }

        // ตรวจสอบว่าเป็นสมาชิกอยู่แล้ว
        $existing = DB::table('workspace_members')
            ->where('workspace_id', $wsId)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            session(['current_workspace_id' => $workspace->id, 'current_workspace_name' => $workspace->name]);
            return redirect()->route('dashboard')->with('success', "เข้าสู่ {$workspace->name} สำเร็จ");
        }

        // เพิ่มเป็นสมาชิก
        DB::table('workspace_members')->insert([
            'workspace_id' => $wsId,
            'user_id' => $userId,
            'joined_at' => now(),
        ]);

        // Log activity
        try {
            DB::table('activity_logs')->insert([
                'workspace_id' => $wsId,
                'user_id' => $userId,
                'action' => auth()->user()->full_name . ' เข้าร่วม Workspace',
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
        }

        session(['current_workspace_id' => $workspace->id, 'current_workspace_name' => $workspace->name]);
        return redirect()->route('dashboard')->with('success', "เข้าร่วม {$workspace->name} สำเร็จ! 🎉");
    }
}
