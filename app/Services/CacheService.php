<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\ScholarProfile;
use App\Models\FundRequest;

class CacheService
{
    /**
     * Cache duration in seconds (10 minutes)
     */
    const CACHE_DURATION = 600;

    /**
     * Get all scholars with caching
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllScholars()
    {
        return Cache::remember('all_scholars', self::CACHE_DURATION, function () {
            return ScholarProfile::get();
        });
    }

    /**
     * Get scholar by ID with caching
     *
     * @param int $id
     * @return Scholar|null
     */
    public function getScholarById($id)
    {
        return Cache::remember('scholar_' . $id, self::CACHE_DURATION, function () use ($id) {
            return ScholarProfile::find($id);
        });
    }

    /**
     * Get recent fund requests with caching
     *
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecentFundRequests($limit = 5)
    {
        return Cache::remember('recent_fund_requests_' . $limit, self::CACHE_DURATION, function () use ($limit) {
            return FundRequest::with(['scholar'])->latest()->limit($limit)->get();
        });
    }

    /**
     * Get dashboard statistics with caching
     *
     * @return array
     */
    public function getDashboardStats()
    {
        return Cache::remember('dashboard_stats', self::CACHE_DURATION, function () {
            return [
                'total_scholars' => ScholarProfile::count(),
                'active_scholars' => ScholarProfile::whereStatus('Ongoing')->count(),
                'graduated_scholars' => ScholarProfile::whereStatus('Graduated')->count(),
                'total_fund_requests' => FundRequest::count(),
                'pending_fund_requests' => FundRequest::whereIn('status', [FundRequest::STATUS_SUBMITTED, FundRequest::STATUS_UNDER_REVIEW])->count(),
            ];
        });
    }

    /**
     * Clear all scholar-related caches
     *
     * @return void
     */
    public function clearScholarCaches()
    {
        Cache::forget('all_scholars');

        // Clear individual scholar caches
        $scholarIds = ScholarProfile::pluck('id')->toArray();
        foreach ($scholarIds as $id) {
            Cache::forget('scholar_' . $id);
        }
    }

    /**
     * Clear all fund request-related caches
     *
     * @return void
     */
    public function clearFundRequestCaches()
    {
        // Clear recent fund requests cache with different limits
        for ($i = 1; $i <= 10; $i++) {
            Cache::forget('recent_fund_requests_' . $i);
        }
    }

    /**
     * Clear dashboard statistics cache
     *
     * @return void
     */
    public function clearDashboardCache()
    {
        Cache::forget('dashboard_stats');
    }
}
