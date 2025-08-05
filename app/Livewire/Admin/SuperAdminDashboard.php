<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\ScholarProfile;
use App\Models\Document;
use App\Models\FundRequest;
use App\Models\Manuscript;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SuperAdminDashboard extends Component
{
    // Dashboard data properties
    public $adminUsers = 0;
    public $totalUsers = 0;
    public $activeSessions = 0;
    public $totalScholars = 0;
    public $pendingScholars = 0;
    public $activeScholars = 0;
    public $pendingFundRequests = 0;
    public $approvedRequests = 0;
    public $pendingDocuments = 0;
    public $completionRate = 0;
    public $completionsThisYear = 0;

    // Additional statistics for the second grid
    public $totalFundRequests = 0;
    public $totalDocuments = 0;
    public $totalManuscripts = 0;
    public $approvedDocuments = 0;
    public $publishedManuscripts = 0;
    public $scholarUsers = 0;

    // Collections for detailed data
    public $recentScholars;
    public $recentFundRequests;
    public $recentDocuments;
    public $recentManuscripts;
    // Program counts removed (program field removed)
    public $recentUsers;

    // User properties for password modal
    public $user;
    public $showPasswordModal = false;

    // Refresh control
    public $lastRefresh;

    public function mount()
    {
        $this->user = Auth::user();
        $this->lastRefresh = now()->format('H:i:s');
        $this->loadDashboardData();

        // Check if password modal should be shown
        $this->showPasswordModal = ($this->user->is_default_password || $this->user->must_change_password)
            && !session('password_warning_dismissed');
    }

    public function loadDashboardData()
    {
        // Load user statistics
        $allUsers = User::all();
        $this->adminUsers = $allUsers->where('role', 'admin')->count();
        $this->totalUsers = $allUsers->count();
        $this->scholarUsers = $allUsers->where('role', 'scholar')->count();
        $this->recentUsers = $allUsers->sortByDesc('created_at')->take(5);

        // Calculate active sessions (users with active sessions in last 30 minutes)
        $this->activeSessions = DB::table('sessions')
            ->where('last_activity', '>=', now()->subMinutes(30)->timestamp)
            ->count();

        // Load scholar statistics
        $scholars = ScholarProfile::all();
        $this->totalScholars = $scholars->count();
        $this->pendingScholars = ScholarProfile::whereStatus('Pending')->count();
        $this->activeScholars = ScholarProfile::whereStatus('Active')->count();
        $this->recentScholars = $scholars->sortByDesc('created_at')->take(5);

        // Load fund request statistics
        $fundRequests = FundRequest::all();
        $this->totalFundRequests = $fundRequests->count();
        $this->pendingFundRequests = $fundRequests->whereIn('status', [FundRequest::STATUS_SUBMITTED, FundRequest::STATUS_UNDER_REVIEW])->count();
        $this->approvedRequests = $fundRequests->where('status', FundRequest::STATUS_APPROVED)->count();
        $this->recentFundRequests = $fundRequests->sortByDesc('created_at')->take(3);

        // Load document statistics
        $documents = Document::all();
        $this->totalDocuments = $documents->count();
        $this->pendingDocuments = $documents->where('status', 'Uploaded')->count();
        $this->approvedDocuments = $documents->where('status', 'Approved')->count();
        $this->recentDocuments = $documents->sortByDesc('created_at')->take(5);

        // Load manuscript data
        $manuscripts = Manuscript::all();
        $this->totalManuscripts = $manuscripts->count();
        $this->publishedManuscripts = $manuscripts->where('status', 'Published')->count();
        $this->recentManuscripts = $manuscripts->sortByDesc('created_at')->take(5);

        // Calculate completion metrics
        $this->completionRate = $scholars->count() > 0
            ? round((ScholarProfile::whereStatus('Graduated')->count() / $scholars->count()) * 100)
            : 0;

        $this->completionsThisYear = ScholarProfile::whereStatus('Completed')
            ->whereYear('updated_at', now()->year)
            ->count();

        // Program distribution removed (program field removed)

        $this->lastRefresh = now()->format('H:i:s');
    }

    public function refreshData()
    {
        $this->loadDashboardData();
        $this->dispatch('data-refreshed');
        session()->flash('success', 'Dashboard data refreshed successfully!');
    }

    public function dismissPasswordModal()
    {
        session(['password_warning_dismissed' => true]);
        $this->showPasswordModal = false;
    }

    public function render()
    {
        return view('livewire.admin.super-admin-dashboard');
    }
}
