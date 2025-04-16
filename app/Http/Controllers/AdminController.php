<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScholarProfile;
use App\Models\Document;
use App\Models\FundRequest;
use App\Models\Manuscript;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\AuditLog;
use App\Services\AuditService;

class AdminController extends Controller
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
        // Use an anonymous middleware for role checking to avoid the 'admin' string that might be causing issues
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Unauthorized action. You do not have the required role.');
            }
            return $next($request);
        });
        $this->auditService = $auditService;
    }

    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Get scholars data
        $scholars = ScholarProfile::all();
        $totalScholars = $scholars->count();
        $pendingScholars = $scholars->where('status', 'Pending')->count();
        $activeScholars = $scholars->where('status', 'Active')->count();
        $recentScholars = $scholars->sortByDesc('created_at')->take(5);

        // Get documents data
        $documents = Document::all();
        $pendingDocuments = $documents->where('status', 'Uploaded')->count();
        $recentDocuments = $documents->sortByDesc('created_at')->take(5);

        // Get fund requests data
        $fundRequests = FundRequest::all();
        $pendingFundRequests = $fundRequests->where('status', 'Pending')->count();
        $approvedRequests = $fundRequests->where('status', 'Approved')->count();
        $recentRequests = $fundRequests->sortByDesc('created_at')->take(5);
        $recentFundRequests = $recentRequests; // Alias for the view
        $pendingRequests = $pendingFundRequests + $pendingDocuments; // Total of all pending requests
        $totalDisbursed = $fundRequests->where('status', 'Approved')->sum('amount');

        // Calculate disbursements for the current month
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $disbursedThisMonth = $fundRequests->where('status', 'Approved')
            ->filter(function ($request) use ($currentMonth, $currentYear) {
                $approvedDate = \Carbon\Carbon::parse($request->updated_at);
                return $approvedDate->month == $currentMonth && $approvedDate->year == $currentYear;
            })->sum('amount');

        // Get manuscripts data
        $manuscripts = Manuscript::all();
        $recentManuscripts = $manuscripts->sortByDesc('created_at')->take(5);

        // Completion metrics
        $completionRate = $scholars->count() > 0 ? round(($scholars->where('status', 'Completed')->count() / $scholars->count()) * 100) : 0;
        $completionsThisYear = $scholars->where('status', 'Completed')
            ->filter(function($scholar) use ($currentYear) {
                return \Carbon\Carbon::parse($scholar->updated_at)->year == $currentYear;
            })->count();

        // Scholar activity and notifications (placeholders)
        $notifications = collect([]);
        $recentScholarActivity = collect([]);

        // Fetch recent audit logs
        $recentLogs = AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'user',
            'scholars',
            'totalScholars',
            'pendingScholars',
            'activeScholars',
            'recentScholars',
            'fundRequests',
            'pendingFundRequests',
            'pendingRequests',
            'approvedRequests',
            'recentRequests',
            'recentFundRequests',
            'totalDisbursed',
            'disbursedThisMonth',
            'documents',
            'pendingDocuments',
            'recentDocuments',
            'manuscripts',
            'recentManuscripts',
            'completionRate',
            'completionsThisYear',
            'notifications',
            'recentScholarActivity',
            'recentLogs'
        ));
    }

    /**
     * Show the admin analytics page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function analytics()
    {
        $user = Auth::user();

        // Get scholars data
        $scholars = ScholarProfile::all();
        $scholarsByStatus = [
            'Active' => $scholars->where('status', 'Active')->count(),
            'Pending' => $scholars->where('status', 'Pending')->count(),
            'Completed' => $scholars->where('status', 'Completed')->count(),
            'Inactive' => $scholars->where('status', 'Inactive')->count(),
        ];

        // Get monthly fund data for the last 12 months
        $monthlyData = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('M Y');
            $startOfMonth = $date->startOfMonth();
            $endOfMonth = $date->endOfMonth();

            $disbursed = FundRequest::where('status', 'Approved')
                ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            $requested = FundRequest::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            $monthlyData->push([
                'month' => $month,
                'disbursed' => $disbursed,
                'requested' => $requested,
            ]);
        }

        // Get fund request data by type
        $fundRequests = FundRequest::all();
        $fundsByType = $fundRequests->groupBy('request_type')->map->sum('amount');

        // Get document statistics
        $documents = Document::all();
        $documentsByType = $documents->groupBy('document_type')->map->count();

        return view('admin.analytics', compact(
            'user',
            'scholarsByStatus',
            'monthlyData',
            'fundsByType',
            'documentsByType'
        ));
    }
}
