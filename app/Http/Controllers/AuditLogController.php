<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;
use App\Services\AuditService;
use App\Models\User;

// Ito ang controller na naghahandle ng pag-track ng mga ginagawa ng users sa system
class AuditLogController extends Controller
{
    protected $auditService;

    /**
     * Pag-setup ng controller
     *
     * - Tinitiyak na naka-login ang user
     * - Checking kung admin ang user
     * - Setting up ng audit service
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
     * Nagpapakita ng listahan ng lahat ng audit logs
     * - May filtering para sa madaling paghahanap
     * - May pagination para di mabigat sa system
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Kunin ang audit logs kasama ang user info
        $query = AuditLog::with('user');

        // Mga filters para sa paghahanap
        // Filter by user ID
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

    /**
     * Para sa pag-export ng audit logs sa CSV file
     * - Pwedeng i-download ng admin
     * - May kasamang filters din kagaya ng sa index
     */
    public function export(Request $request)
    {
        // Kunin muna lahat ng audit logs na kailangan
        $query = AuditLog::with('user');

        // Mga filters para sa pag-export
        // Filter by user ID
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

        // Function para gumawa ng CSV file
        $callback = function() use ($auditLogs) {
            // Gumawa ng bagong file
            $file = fopen('php://output', 'w');

            // Lagyan ng mga headers ang CSV
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
     * Nagpapakita ng detalye ng isang audit log
     * - Pwedeng makita ang eksaktong ginawa
     * - Kasama ang info ng user na gumawa
     * - Makikita ang dating values at bagong values
     *
     * @param  int  $id  ID ng audit log na gusto makita
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Kunin ang audit log base sa ID
        $auditLog = AuditLog::with('user')->findOrFail($id);

        // Ipakita ang view na may detalye ng audit log
        return view('admin.audit-logs.show', compact('auditLog'));
    }
}
