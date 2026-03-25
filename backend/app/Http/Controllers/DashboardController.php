<?php
namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;

class DashboardController extends Controller
{
    //
    public function stats()
    {
        $totalUsers = User::count();

        $usersByRole = User::selectRaw('roles.name as role, count(*) as count')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->groupBy('roles.name')
            ->get();

        $recentLogs = AuditLog::with('actor')
            ->latest()
            ->take(5)
            ->get();

        return response()->json([
            'total_users'   => $totalUsers,
            'users_by_role' => $usersByRole,
            'recent_logs'   => $recentLogs,
        ]);
    }
}
