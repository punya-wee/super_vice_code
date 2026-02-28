<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    private function wsId()
    {
        return session('current_workspace_id');
    }

    private function log($action)
    {
        try {
            DB::table('activity_logs')->insert([
                'workspace_id' => $this->wsId(),
                'user_id' => auth()->id(),
                'action' => $action,
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
        }
    }

    // POST /schedules
    public function store(Request $request)
    {
        $v = $request->validate([
            'title' => 'required|string|max:150',
            'product_id' => 'nullable|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'notes' => 'nullable|string',
        ], [
            'title.required' => 'กรุณากรอกชื่อพืช/งาน',
            'start_date.required' => 'กรุณากรอกวันเริ่มต้น',
            'end_date.required' => 'กรุณากรอกวันสิ้นสุด',
            'end_date.after' => 'วันสิ้นสุดต้องหลังวันเริ่มต้น',
        ]);

        DB::table('schedules')->insert([
            'workspace_id' => $this->wsId(),
            'product_id' => $v['product_id'] ?? null,
            'title' => $v['title'],
            'start_date' => $v['start_date'],
            'end_date' => $v['end_date'],
            'notes' => $v['notes'] ?? null,
            'created_by' => auth()->id(),
            'created_at' => now(),
        ]);

        $this->log("เพิ่มแผนการปลูก: {$v['title']} ({$v['start_date']} → {$v['end_date']})");

        return redirect()->route('dashboard', ['section' => 'planting'])
            ->with('success', 'เพิ่มแผนการปลูกสำเร็จ!');
    }

    // POST /schedules/{id}/update
    public function update(Request $request, $id)
    {
        $schedule = DB::table('schedules')
            ->where('id', $id)->where('workspace_id', $this->wsId())->first();

        abort_if(!$schedule, 403);

        $v = $request->validate([
            'title' => 'required|string|max:150',
            'product_id' => 'nullable|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::table('schedules')->where('id', $id)->update([
            'title' => $v['title'],
            'product_id' => $v['product_id'] ?? null,
            'start_date' => $v['start_date'],
            'end_date' => $v['end_date'],
            'notes' => $v['notes'] ?? null,
        ]);

        $this->log("แก้ไขแผนการปลูก: {$v['title']}");

        return redirect()->route('dashboard', ['section' => 'planting'])
            ->with('success', 'อัปเดตแผนการปลูกสำเร็จ!');
    }

    // POST /schedules/{id}/delete
    public function destroy($id)
    {
        $schedule = DB::table('schedules')
            ->where('id', $id)->where('workspace_id', $this->wsId())->first();

        abort_if(!$schedule, 403);

        DB::table('schedules')->where('id', $id)->delete();
        $this->log("ลบแผนการปลูก: {$schedule->title}");

        return redirect()->route('dashboard', ['section' => 'planting'])
            ->with('success', 'ลบแผนการปลูกสำเร็จ!');
    }
}
