<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Hanya Super Admin yang boleh melihat log ini
        if (! auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $logs = ActivityLog::with('user')->latest()->paginate(20);

        return view('admin.activity_logs.index', compact('logs'));
    }
}
