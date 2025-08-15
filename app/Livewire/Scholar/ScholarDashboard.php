<?php

namespace App\Livewire\Scholar;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\ScholarProfile;
use App\Models\FundRequest;
use App\Models\Manuscript;
use App\Models\Document;
use App\Services\NotificationService;
use App\Services\FundRequestService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScholarDashboard extends Component
{
    // Dashboard data properties
    public $scholarProfile;
    public $scholarProgress = 0;
    public $daysRemaining = 0;
    public $totalFundRequests = 0;
    public $approvedFundRequests = 0;
    public $pendingFundRequests = 0;
    public $rejectedFundRequests = 0;
    public $totalManuscripts = 0;
    public $publishedManuscripts = 0;
    public $underReviewManuscripts = 0;
    public $totalDocuments = 0;
    public $approvedDocuments = 0;
    public $pendingDocuments = 0;
    public $unreadNotifications = 0;
    
    // Collections for recent data
    public $recentFundRequests;
    public $recentManuscripts;
    public $recentDocuments;
    public $recentNotifications;
    
    // Financial data
    public $totalAmountRequested = 0;
    public $totalAmountApproved = 0;
    public $totalAmountDisbursed = 0;
    
    // Refresh control
    public $lastRefresh;
    public $isLoading = false;

    public function mount(NotificationService $notificationService, FundRequestService $fundRequestService)
    {
        $this->loadDashboardData($notificationService, $fundRequestService);
        $this->lastRefresh = now()->format('M d, Y H:i:s');
    }

    public function loadDashboardData(NotificationService $notificationService, FundRequestService $fundRequestService)
    {
        $this->isLoading = true;
        
        $user = Auth::user();
        
        // Get scholar profile with optimized relations
        $this->scholarProfile = ScholarProfile::where('user_id', $user->id)->first();
        
        if (!$this->scholarProfile) {
            // Create a dummy object for the view if no profile exists
            $this->scholarProfile = new \stdClass();
            $this->scholarProfile->id = null;
            $this->scholarProfile->status = null;
            $this->scholarProfile->department = null;
            $this->scholarProfile->intended_university = null;
            $this->scholarProfile->start_date = null;
            $this->isLoading = false;
            return;
        }
        
        // Calculate scholarship progress and days remaining
        $this->calculateProgress();
        
        // Load fund requests data
        $this->loadFundRequestsData();
        
        // Load manuscripts data
        $this->loadManuscriptsData();
        
        // Load documents data
        $this->loadDocumentsData();
        
        // Load notifications data
        $this->loadNotificationsData($notificationService);
        
        // Load financial data
        $this->loadFinancialData($fundRequestService);
        
        $this->isLoading = false;
    }
    
    private function calculateProgress()
    {
        if (!$this->scholarProfile->id || !$this->scholarProfile->start_date) {
            $this->scholarProgress = 0;
            $this->daysRemaining = 0;
            return;
        }
        
        $startDate = Carbon::parse($this->scholarProfile->start_date);
        $currentDate = Carbon::now();
        
        // Assuming a typical scholarship duration of 4 years (1460 days)
        $totalDuration = 1460; // days
        $daysPassed = $startDate->diffInDays($currentDate);
        
        $this->scholarProgress = min(($daysPassed / $totalDuration) * 100, 100);
        $this->daysRemaining = max($totalDuration - $daysPassed, 0);
    }
    
    private function loadFundRequestsData()
    {
        if (!$this->scholarProfile->id) {
            return;
        }
        
        $fundRequestsQuery = FundRequest::where('scholar_profile_id', $this->scholarProfile->id);
        
        $this->totalFundRequests = $fundRequestsQuery->count();
        $this->approvedFundRequests = (clone $fundRequestsQuery)->where('status', 'approved')->count();
        $this->pendingFundRequests = (clone $fundRequestsQuery)->where('status', 'pending')->count();
        $this->rejectedFundRequests = (clone $fundRequestsQuery)->where('status', 'rejected')->count();
        
        // Get recent fund requests with relations
        $this->recentFundRequests = FundRequest::withBasicRelations()
            ->where('scholar_profile_id', $this->scholarProfile->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }
    
    private function loadManuscriptsData()
    {
        if (!$this->scholarProfile->id) {
            return;
        }
        
        $manuscriptsQuery = Manuscript::where('scholar_profile_id', $this->scholarProfile->id);
        
        $this->totalManuscripts = $manuscriptsQuery->count();
        $this->publishedManuscripts = (clone $manuscriptsQuery)->where('status', 'Published')->count();
        $this->underReviewManuscripts = (clone $manuscriptsQuery)->where('status', 'Under Review')->count();
        
        // Get recent manuscripts with relations
        $this->recentManuscripts = Manuscript::withBasicRelations()
            ->where('scholar_profile_id', $this->scholarProfile->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }
    
    private function loadDocumentsData()
    {
        if (!$this->scholarProfile->id) {
            return;
        }
        
        // Get documents from fund requests and manuscripts
        $fundRequestIds = FundRequest::where('scholar_profile_id', $this->scholarProfile->id)->pluck('id');
        $manuscriptIds = Manuscript::where('scholar_profile_id', $this->scholarProfile->id)->pluck('id');
        
        $documentsQuery = Document::where(function($query) use ($fundRequestIds, $manuscriptIds) {
            $query->whereIn('fund_request_id', $fundRequestIds)
                  ->orWhereIn('manuscript_id', $manuscriptIds);
        });
        
        $this->totalDocuments = $documentsQuery->count();
        $this->approvedDocuments = (clone $documentsQuery)->where('status', 'approved')->count();
        $this->pendingDocuments = (clone $documentsQuery)->where('status', 'pending')->count();
        
        // Get recent documents
        $this->recentDocuments = (clone $documentsQuery)
            ->with(['fundRequest', 'manuscript'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }
    
    private function loadNotificationsData(NotificationService $notificationService)
    {
        $user = Auth::user();
        $this->unreadNotifications = $notificationService->getUnreadCount($user->id);
        
        // Get recent notifications
        $this->recentNotifications = $notificationService->getRecentNotifications($user->id, 5);
    }
    
    private function loadFinancialData(FundRequestService $fundRequestService)
    {
        if (!$this->scholarProfile->id) {
            return;
        }
        
        $statistics = $fundRequestService->getScholarFundRequestStatistics($this->scholarProfile->id);
        
        $this->totalAmountRequested = $statistics['totalRequested'];
        $this->totalAmountApproved = $statistics['totalApproved'];
        
        // Calculate disbursed amount from approved requests with disbursements
        $fundRequests = FundRequest::where('scholar_profile_id', $this->scholarProfile->id)
            ->where('status', 'Approved')
            ->with('disbursements')
            ->get();
            
        $this->totalAmountDisbursed = $fundRequests->sum(function($request) {
            return $request->disbursements->sum('amount');
        });
    }
    
    public function refreshData()
    {
        $notificationService = app(NotificationService::class);
        $fundRequestService = app(FundRequestService::class);
        $this->loadDashboardData($notificationService, $fundRequestService);
        $this->lastRefresh = now()->format('M d, Y H:i:s');
        
        $this->dispatch('show-toast', [
            'type' => 'success',
            'title' => 'Dashboard Refreshed',
            'message' => 'Dashboard data has been updated successfully.'
        ]);
    }
    
    public function getStatusColorProperty()
    {
        if (!$this->scholarProfile || !isset($this->scholarProfile->status)) {
            return 'gray';
        }
        
        return match($this->scholarProfile->status) {
            'active' => 'green',
            'inactive' => 'red',
            'pending' => 'yellow',
            'graduated' => 'blue',
            default => 'gray'
        };
    }
    
    public function getProgressColorProperty()
    {
        if ($this->scholarProgress >= 75) {
            return 'green';
        } elseif ($this->scholarProgress >= 50) {
            return 'yellow';
        } elseif ($this->scholarProgress >= 25) {
            return 'orange';
        } else {
            return 'red';
        }
    }

    public function render()
    {
        return view('livewire.scholar.scholar-dashboard');
    }
}