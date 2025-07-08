<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ScholarProfile;
use App\Models\FundRequest;
use App\Models\Disbursement;
use App\Models\Manuscript;
use App\Models\AuditLog;
use App\Models\CustomNotification;
use App\Services\AuditService;
use App\Models\Document;

class HomeController extends Controller
{
    protected $auditService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AuditService $auditService)
    {
        $this->middleware('auth');
        $this->auditService = $auditService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()->role === 'scholar') {
            return $this->scholarDashboard();
        } else {
            return $this->adminDashboard();
        }
    }

    private function adminDashboard()
    {
        // Get counts for the stats grid
        $totalScholars = ScholarProfile::count();
        $pendingRequests = FundRequest::whereIn('status', ['Submitted', 'Under Review'])->count();
        $totalDisbursed = Disbursement::sum('amount');
        $completionRate = ScholarProfile::where('status', 'Completed')->count() > 0
            ? round((ScholarProfile::where('status', 'Completed')->count() / $totalScholars) * 100)
            : 0;

        // Get recent fund requests with their relationships
        $recentFundRequests = FundRequest::with(['scholarProfile.user', 'requestType'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get recent documents with their relationships
        $recentDocuments = Document::with(['scholarProfile.user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Additional scholar statistics
        $activeScholars = ScholarProfile::where('status', 'Active')->count();

        // Scholars expected to complete this year
        $currentYear = date('Y');
        $completingThisYear = ScholarProfile::whereYear('expected_completion_date', $currentYear)->count();

        // New scholars in the last 30 days
        $newScholars = ScholarProfile::whereDate('created_at', '>=', now()->subDays(30))->count();

        // Default empty collections
        $programCounts = collect([]);

        try {
            // Get program distribution
            if ($totalScholars > 0) {
                $programCounts = ScholarProfile::select('program')
                    ->selectRaw('count(*) as count')
                    ->whereNotNull('program')
                    ->where('program', '!=', '')
                    ->groupBy('program')
                    ->orderByDesc('count')
                    ->get()
                    ->map(function ($item) use ($totalScholars) {
                        $item->percentage = $totalScholars > 0 ? round(($item->count / $totalScholars) * 100) : 0;
                        return $item;
                    });
            }
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error generating dashboard statistics: ' . $e->getMessage());

            // If an error occurs, use empty collections
            $programCounts = collect([]);
        }

        $data = [
            'totalScholars' => $totalScholars,
            'pendingRequests' => $pendingRequests,
            'totalDisbursed' => $totalDisbursed,
            'completionRate' => $completionRate,
            'recentFundRequests' => $recentFundRequests,
            'recentDocuments' => $recentDocuments,
            'activeScholars' => $activeScholars,
            'completingThisYear' => $completingThisYear,
            'newScholars' => $newScholars,
            'programCounts' => $programCounts
        ];

        return view('admin.dashboard', $data);
    }

    private function scholarDashboard()
    {
        $user = Auth::user();
        $profile = $user->scholarProfile;

        // Get fund requests for the scholar
        $fundRequests = $profile ? $profile->fundRequests : collect([]);
        $recentFundRequests = $profile ? $profile->fundRequests()->latest()->limit(5)->get() : collect([]);

        // Count requests by status
        $pendingRequests = $fundRequests->where('status', 'Pending')->count();
        $approvedRequests = $fundRequests->where('status', 'Approved')->count();
        $rejectedRequests = $fundRequests->where('status', 'Rejected')->count();

        // Get documents for the scholar
        $documents = $profile ? $profile->documents : collect([]);
        $recentDocuments = $profile ? $profile->documents()->latest()->limit(5)->get() : collect([]);

        // Count documents by status
        $verifiedDocuments = $documents->where('status', 'Verified')->count();
        $pendingDocuments = $documents->where('status', 'Uploaded')->count();
        $rejectedDocuments = $documents->where('status', 'Rejected')->count();

        // Get manuscripts for the scholar
        $manuscripts = $profile ? $profile->manuscripts : collect([]);
        $recentManuscripts = $profile ? $profile->manuscripts()->latest()->limit(5)->get() : collect([]);

        // Get notifications from the CustomNotification model instead
        $notifications = CustomNotification::where('user_id', $user->id)
                                ->latest()
                                ->limit(5)
                                ->get();

        $data = [
            'user' => $user,
            'scholarProfile' => $profile,
            'fundRequests' => $fundRequests,
            'recentFundRequests' => $recentFundRequests,
            'pendingRequests' => $pendingRequests,
            'approvedRequests' => $approvedRequests,
            'rejectedRequests' => $rejectedRequests,
            'documents' => $documents,
            'recentDocuments' => $recentDocuments,
            'verifiedDocuments' => $verifiedDocuments,
            'pendingDocuments' => $pendingDocuments,
            'rejectedDocuments' => $rejectedDocuments,
            'manuscripts' => $manuscripts,
            'recentManuscripts' => $recentManuscripts,
            'submittedManuscripts' => $profile ? $profile->manuscripts()->where('status', 'submitted')->count() : 0,
            'reviewManuscripts' => $profile ? $profile->manuscripts()->where('status', 'under_review')->count() : 0,
            'notifications' => $notifications
        ];

        return view('scholar.dashboard', $data);
    }
}
