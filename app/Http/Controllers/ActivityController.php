<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    private function wsId()
    {
        return session('current_workspace_id');
    }

    // GET /api/activity
    public function index()
    {
        $logs = ActivityLog::where('workspace_id', $this->wsId())
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($log) {
                $user = User::find($log->user_id);
                $log->user_name = $user ? $user->full_name : 'Unknown';
                $log->created_at_fmt = $log->created_at->locale('th')->isoFormat('D MMM YYYY HH:mm');
                return $log;
            });

        return response()->json(['success' => true, 'data' => $logs]);
    }

    // GET /api/members
    public function members()
    {
        $wsId = $this->wsId();
        $workspace = DB::table('workspaces')->where('id', $wsId)->first();
        $owner = User::find($workspace->owner_id);

        $members = collect([
            [
                'id' => $owner->id,
                'name' => $owner->full_name,
                'email' => $owner->email,
                'role' => 'Owner',
                'joined_at' => optional($owner->created_at)->locale('th')->isoFormat('D MMM YYYY') ?? '-',
                'initials' => strtoupper(substr($owner->full_name, 0, 2)),
            ]
        ]);

        return response()->json(['success' => true, 'data' => $members]);
    }
}
