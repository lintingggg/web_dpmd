<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', ActivityLog::class);

        $logs = ActivityLog::with('user')
            ->visibleTo(Auth::user())
            ->filter($request->only(['search']))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/ActivityLog/Index', [
            'logs' => $logs,
            'filters' => $request->only(['search']),
        ]);
    }
}
