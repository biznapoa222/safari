<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = AuditLog::with('user')
            ->when($request->filled('action'), fn($q) => $q->where('action', $request->action))
            ->when($request->filled('module'), fn($q) => $q->where('module', $request->module))
            ->when($request->filled('user_id'), fn($q) => $q->where('user_id', $request->user_id))
            ->latest()
            ->paginate(50)->withQueryString();

        return view('admin.audit-logs.index', [
            'logs' => $logs,
            'actions' => ['login', 'logout', 'create', 'edit', 'delete', 'approve', 'reject'],
            'modules' => AuditLog::distinct()->orderBy('module')->pluck('module'),
        ]);
    }
}
