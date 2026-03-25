<?php
namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();

        // Only admins can see logs
        if ($user->role->name !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $query = AuditLog::with('actor')->latest();

        // 🔍 Search by actor name
        if ($request->filled('search')) {
            $query->whereHas('actor', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Optional: filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Pagination
        $logs = $query->paginate(10);

        return response()->json($logs);
    }
    //
}
