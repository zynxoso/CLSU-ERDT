<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;
use App\Services\AuditService;
use App\Models\User;

class AuditLogController extends Controller
{
    protected $auditService;

    /**
     * Create a new controller instance.
     *
     * @param AuditService $auditService
     * @return void
     */
    public function __construct(AuditService $auditService)
    {
        $this->middleware('auth');
        $this->middleware(\App\Http\Middleware\AdminMiddleware::class);
        $this->auditService = $auditService;
    }

    /**
     * Display a listing of audit logs.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        // Apply filters
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('user') && $request->user) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%')
                  ->orWhere('email', 'like', '%' . $request->user . '%');
            });
        }

        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        if ($request->has('model_type') && $request->model_type) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->has('model_id') && $request->model_id) {
            $query->where('model_id', $request->model_id);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Get unique entity types and actions for filters
        $entityTypes = AuditLog::distinct()->pluck('model_type')->filter()->sort()->values();
        $actions = AuditLog::distinct()->pluck('action')->filter()->sort()->values();
        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        $auditLogs = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.audit-logs.index', compact('auditLogs', 'entityTypes', 'actions', 'users'));
    }

    public function export(Request $request)
    {
        $query = AuditLog::with('user');

        // Apply filters
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('user') && $request->user) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%')
                  ->orWhere('email', 'like', '%' . $request->user . '%');
            });
        }

        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        if ($request->has('model_type') && $request->model_type) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->has('model_id') && $request->model_id) {
            $query->where('model_id', $request->model_id);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $auditLogs = $query->orderBy('created_at', 'desc')->get();

        // Generate CSV
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="audit_logs_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($auditLogs) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'ID',
                'Timestamp',
                'User',
                'Action',
                'Entity Type',
                'Entity ID',
                'IP Address',
                'Browser/Device',
                'Old Values',
                'New Values'
            ]);

            // Add data rows
            foreach ($auditLogs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user ? $log->user->name . ' (' . $log->user->email . ')' : 'System',
                    $log->action,
                    $log->model_type,
                    $log->model_id,
                    $log->ip_address,
                    $log->user_agent,
                    json_encode($log->old_values),
                    json_encode($log->new_values)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display the specified audit log.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $auditLog = AuditLog::with('user')->findOrFail($id);

        return view('admin.audit-logs.show', compact('auditLog'));
    }
}
