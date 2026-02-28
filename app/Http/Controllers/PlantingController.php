<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlantingPlan;
use App\Models\ActivityLog;
use Carbon\Carbon;

class PlantingController extends Controller
{
    private function wsId()
    {
        return session('current_workspace_id');
    }

    private function log($action, $desc, $type = 'info')
    {
        ActivityLog::create([
            'workspace_id' => $this->wsId(),
            'user_id' => auth()->id(),
            'action' => $action,
            'type' => $type,
            'description' => $desc,
        ]);
    }

    // GET /api/planting
    public function index(Request $request)
    {
        $query = PlantingPlan::where('workspace_id', $this->wsId());

        if ($request->filled('status') && $request->status !== 'ทั้งหมด') {
            $query->where('status', $request->status);
        }

        $plans = $query->orderBy('plant_date', 'desc')->get();

        $plans->transform(function ($p) {
            $p->plant_date_fmt = Carbon::parse($p->plant_date)->locale('th')->isoFormat('D MMM YYYY');
            $p->harvest_date_fmt = Carbon::parse($p->harvest_date)->locale('th')->isoFormat('D MMM YYYY');
            return $p;
        });

        // stats
        $wsId = $this->wsId();
        $stats = [
            'total' => PlantingPlan::where('workspace_id', $wsId)->count(),
            'planned' => PlantingPlan::where('workspace_id', $wsId)->where('status', 'วางแผนแล้ว')->count(),
            'growing' => PlantingPlan::where('workspace_id', $wsId)->where('status', 'กำลังปลูก')->count(),
            'harvested' => PlantingPlan::where('workspace_id', $wsId)->where('status', 'เก็บเกี่ยวแล้ว')->count(),
        ];

        return response()->json(['success' => true, 'data' => $plans, 'stats' => $stats]);
    }

    // GET /api/planting/upcoming (for overview)
    public function upcoming()
    {
        $plans = PlantingPlan::where('workspace_id', $this->wsId())
            ->where('harvest_date', '>=', now())
            ->orderBy('harvest_date')
            ->limit(5)
            ->get()
            ->map(function ($p) {
                $p->days_left = now()->diffInDays(Carbon::parse($p->harvest_date), false);
                $p->harvest_date_fmt = Carbon::parse($p->harvest_date)->locale('th')->isoFormat('D MMM YYYY');
                return $p;
            });

        return response()->json(['success' => true, 'data' => $plans]);
    }

    // POST /api/planting
    public function store(Request $request)
    {
        $v = $request->validate([
            'crop_name' => 'required|string|max:100',
            'category' => 'required|string',
            'plant_date' => 'required|date',
            'harvest_date' => 'required|date|after:plant_date',
            'area_rai' => 'required|numeric|min:0',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ], [
            'crop_name.required' => 'กรุณากรอกชื่อพืช',
            'plant_date.required' => 'กรุณากรอกวันที่ปลูก',
            'harvest_date.required' => 'กรุณากรอกวันที่เก็บเกี่ยว',
            'harvest_date.after' => 'วันเก็บเกี่ยวต้องหลังวันปลูก',
            'area_rai.required' => 'กรุณากรอกพื้นที่',
        ]);

        $plan = PlantingPlan::create([
            'workspace_id' => $this->wsId(),
            'created_by' => auth()->id(),
            ...$v,
        ]);

        $this->log('เพิ่ม', "เพิ่มแผนปลูก {$plan->crop_name} พื้นที่ {$plan->area_rai} ไร่", 'success');

        return response()->json(['success' => true, 'message' => 'เพิ่มแผนการปลูกสำเร็จ', 'plan' => $plan], 201);
    }

    // PUT /api/planting/{id}
    public function update(Request $request, $id)
    {
        $plan = PlantingPlan::where('id', $id)->where('workspace_id', $this->wsId())->firstOrFail();

        $v = $request->validate([
            'crop_name' => 'required|string|max:100',
            'category' => 'required|string',
            'plant_date' => 'required|date',
            'harvest_date' => 'required|date',
            'area_rai' => 'required|numeric|min:0',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $plan->update($v);
        $this->log('แก้ไข', "แก้ไขแผนปลูก {$plan->crop_name}", 'info');

        return response()->json(['success' => true, 'message' => 'อัปเดตสำเร็จ', 'plan' => $plan]);
    }

    // DELETE /api/planting/{id}
    public function destroy($id)
    {
        $plan = PlantingPlan::where('id', $id)->where('workspace_id', $this->wsId())->firstOrFail();
        $this->log('ลบ', "ลบแผนปลูก {$plan->crop_name}", 'alert');
        $plan->delete();

        return response()->json(['success' => true, 'message' => 'ลบแผนการปลูกสำเร็จ']);
    }
}
