<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\ScholarProfile;
use App\Models\Document;
use App\Models\FundRequest;
use App\Models\Manuscript;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SuperAdminDashboard extends Component
{
    /**
     * Livewire listeners for real-time updates
     */
    protected $listeners = [
        'refreshData',
        'capacity-updated' => 'handleCapacityUpdate',
        'health-updated' => 'handleHealthUpdate',
        'notification-received' => 'handleNotificationReceived'
    ];

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
    public $lastUpdated;
    public $autoRefreshEnabled = true;
    public $refreshInterval = 30; // seconds
    public $systemHealth = [];
    public $capacityMetrics = [];

    public function mount()
    {
        $this->user = Auth::user();
        $this->lastRefresh = now()->format('H:i:s');
        $this->loadDashboardData();
        $this->checkSystemHealth();

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
        $scholars = ScholarProfile::withBasicRelations()->get();
        $this->totalScholars = $scholars->count();
        $this->pendingScholars = ScholarProfile::whereStatus('Pending')->count();
        $this->activeScholars = ScholarProfile::whereStatus('Active')->count();
        $this->recentScholars = ScholarProfile::withBasicRelations()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Load fund request statistics
        $fundRequests = FundRequest::withBasicRelations()->get();
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
        $manuscripts = Manuscript::withBasicRelations()->get();
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

    public function checkSystemHealth()
    {
        $this->systemHealth = [
            'database' => $this->checkDatabaseHealth(),
            'cache' => $this->checkCacheHealth(),
            'storage' => $this->checkStorageHealth(),
            'queue' => $this->checkQueueHealth()
        ];
    }

    private function checkDatabaseHealth()
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'healthy', 'message' => 'Connected'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Connection failed'];
        }
    }

    private function checkCacheHealth()
    {
        try {
            cache()->put('health_check', 'ok', 60);
            $result = cache()->get('health_check');
            return ['status' => $result === 'ok' ? 'healthy' : 'warning', 'message' => 'Cache operational'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Cache unavailable'];
        }
    }

    private function checkStorageHealth()
    {
        try {
            $diskSpace = disk_free_space(storage_path());
            $totalSpace = disk_total_space(storage_path());
            $usagePercent = round((($totalSpace - $diskSpace) / $totalSpace) * 100, 1);
            
            $status = $usagePercent > 90 ? 'error' : ($usagePercent > 75 ? 'warning' : 'healthy');
            return ['status' => $status, 'message' => "Usage: {$usagePercent}%"];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Storage check failed'];
        }
    }

    private function checkQueueHealth()
    {
        try {
            $failedJobs = DB::table('failed_jobs')->count();
            $status = $failedJobs > 10 ? 'warning' : 'healthy';
            return ['status' => $status, 'message' => "Failed jobs: {$failedJobs}"];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Queue check failed'];
        }
    }

    public function toggleAutoRefresh()
    {
        $this->autoRefreshEnabled = !$this->autoRefreshEnabled;
    }

    public function refreshData()
    {
        $this->loadDashboardData();
        $this->checkSystemHealth();
        $this->dispatch('dashboard-refreshed', ['timestamp' => now()->toISOString()]);
    }

    public function getCapacityMetrics()
    {
        return [
            'cpu_usage' => $this->getCpuUsage(),
            'memory_usage' => $this->getMemoryUsage(),
            'disk_usage' => $this->getDiskUsage(),
            'active_users' => $this->getActiveUsers(),
            'request_rate' => $this->getRequestRate()
        ];
    }

    private function getCpuUsage()
    {
        // Simulated CPU usage - in production, use system monitoring tools
        return rand(15, 85);
    }

    private function getMemoryUsage()
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = $this->parseBytes(ini_get('memory_limit'));
        return round(($memoryUsage / $memoryLimit) * 100, 1);
    }

    private function getDiskUsage()
    {
        $diskSpace = disk_free_space(storage_path());
        $totalSpace = disk_total_space(storage_path());
        return round((($totalSpace - $diskSpace) / $totalSpace) * 100, 1);
    }

    private function getActiveUsers()
    {
        return DB::table('sessions')
            ->where('last_activity', '>=', now()->subMinutes(30)->timestamp)
            ->count();
    }

    private function getRequestRate()
    {
        // Simulated request rate - in production, use application monitoring
        return rand(50, 200);
    }

    private function parseBytes($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $val = (int) $val;
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
     }

     public function render()
     {
         return view('livewire.admin.super-admin-dashboard');
     }

    public function dismissPasswordModal()
    {
        session(['password_warning_dismissed' => true]);
        $this->showPasswordModal = false;
    }

    /**
     * Handle capacity update from real-time services
     */
    public function handleCapacityUpdate($data)
    {
        if (isset($data['cpu_usage'])) {
            $this->capacityMetrics['cpu_usage'] = $data['cpu_usage'];
        }
        if (isset($data['memory_usage'])) {
            $this->capacityMetrics['memory_usage'] = $data['memory_usage'];
        }
        if (isset($data['disk_usage'])) {
            $this->capacityMetrics['disk_usage'] = $data['disk_usage'];
        }
        if (isset($data['active_users'])) {
            $this->capacityMetrics['active_users'] = $data['active_users'];
        }
        if (isset($data['request_rate'])) {
            $this->capacityMetrics['request_rate'] = $data['request_rate'];
        }
        
        $this->lastUpdated = now();
        
        // Dispatch browser event to update UI
        $this->dispatch('capacity-metrics-updated', $this->capacityMetrics);
    }

    /**
     * Handle health update from real-time services
     */
    public function handleHealthUpdate($data)
    {
        if (isset($data['database'])) {
            $this->systemHealth['database'] = $data['database'];
        }
        if (isset($data['cache'])) {
            $this->systemHealth['cache'] = $data['cache'];
        }
        if (isset($data['storage'])) {
            $this->systemHealth['storage'] = $data['storage'];
        }
        if (isset($data['queue'])) {
            $this->systemHealth['queue'] = $data['queue'];
        }
        
        $this->lastUpdated = now();
        
        // Dispatch browser event to update UI
        $this->dispatch('health-status-updated', $this->systemHealth);
    }

    /**
     * Handle notification received from real-time services
     */
    public function handleNotificationReceived($notification)
    {
        // Refresh notification count or handle specific notification logic
        $this->dispatch('notification-count-updated');
        
        // Log the notification for debugging
        Log::info('Real-time notification received in dashboard', [
            'notification' => $notification,
            'user_id' => Auth::id()
        ]);
    }
}
