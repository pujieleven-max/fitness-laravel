<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // Filter Action (INSERT, UPDATE, DELETE, etc.)
        if ($request->filled('action')) {
            $query->where('action', strtoupper($request->action));
        }

        // Filter User
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter Module
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        // Filter Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Keyword Search
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('subject_id', 'like', "%{$search}%");
            });
        }

        // Quick Statistics
        $totalActivities = ActivityLog::count();
        $insertCount = ActivityLog::where('action', 'INSERT')->count();
        $updateCount = ActivityLog::where('action', 'UPDATE')->count();
        $deleteCount = ActivityLog::where('action', 'DELETE')->count();

        $activities = $query->paginate(20)->withQueryString();

        $users = User::orderBy('name')->get(['id', 'name', 'role']);
        $modules = [
            'Suplemen',
            'Member',
            'Paket Gym',
            'Transaksi POS',
            'Booking',
            'Trainer',
            'Kelas Gym',
            'Pengguna',
            'Pengaturan',
            'Autentikasi',
            'Kategori',
            'Mutasi Stok',
        ];

        return view('activities.index', compact(
            'activities',
            'users',
            'modules',
            'totalActivities',
            'insertCount',
            'updateCount',
            'deleteCount'
        ));
    }

    /**
     * Show activity detail (JSON for modal / audit inspect).
     */
    public function show(ActivityLog $activity)
    {
        $activity->load('user');

        return response()->json([
            'id' => $activity->id,
            'action' => $activity->action,
            'module' => $activity->module,
            'description' => $activity->description,
            'user_name' => $activity->user_name,
            'user_role' => $activity->user_role,
            'ip_address' => $activity->ip_address ?? '-',
            'user_agent' => $activity->user_agent ?? '-',
            'created_at' => $activity->created_at->format('d M Y, H:i:s'),
            'time_ago' => $activity->created_at->diffForHumans(),
            'old_values' => $activity->old_values,
            'new_values' => $activity->new_values,
            'badge' => $activity->getActionBadge(),
        ]);
    }
}

