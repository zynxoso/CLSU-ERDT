<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CacheService;
use App\Models\Scholar;
use App\Models\FundRequest;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class CachedDashboardController extends Controller
{
    protected $cacheService;

    /**
     * Create a new controller instance.
     *
     * @param CacheService $cacheService
     * @return void
     */
    public function __construct(CacheService $cacheService)
    {
        $this->middleware('auth');
        $this->cacheService = $cacheService;
    }

    /**
     * Show the application dashboard with Redis-cached data.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get cached dashboard statistics
        $stats = $this->cacheService->getDashboardStats();
        
        // Get recent fund requests from cache
        $recentFundRequests = $this->cacheService->getRecentFundRequests(5);
        
        // Get activity logs (not cached as they change frequently)
        $activityLogs = ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();
            
        return view('admin.cached-dashboard', [
            'stats' => $stats,
            'recentFundRequests' => $recentFundRequests,
            'activityLogs' => $activityLogs,
            'user' => Auth::user(),
        ]);
    }
    
    /**
     * Clear all caches and redirect back to dashboard
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearCache()
    {
        $this->cacheService->clearScholarCaches();
        $this->cacheService->clearFundRequestCaches();
        $this->cacheService->clearDashboardCache();
        
        return redirect()->route('admin.dashboard')->with('success', 'All caches have been cleared successfully.');
    }
}
