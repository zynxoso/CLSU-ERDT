<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FundRequest;
use App\Models\Document;
use App\Models\Manuscript;
use App\Models\Scholar;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $timeframe = 30; // Default to 30 days
        
        // Get analytics data
        $data = $this->getAnalyticsData($timeframe);
        
        return view('admin.analytics', $data);
    }
    
    public function getAnalyticsData($timeframe = 30)
    {
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays($timeframe);
        $previousStartDate = Carbon::now()->subDays($timeframe * 2);
        
        // Total scholars
        $totalScholars = User::whereHas('roles', function($query) {
                $query->where('name', 'scholar');
            })
            ->count();
            
        $previousTotalScholars = User::whereHas('roles', function($query) {
                $query->where('name', 'scholar');
            })
            ->where('created_at', '<', $startDate)
            ->count();
            
        $scholarsGrowth = $previousTotalScholars > 0 
            ? round((($totalScholars - $previousTotalScholars) / $previousTotalScholars) * 100) 
            : 0;
        
        // Total disbursed
        $totalDisbursed = FundRequest::where('status', 'Approved')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');
            
        $previousDisbursed = FundRequest::where('status', 'Approved')
            ->whereBetween('created_at', [$previousStartDate, $startDate])
            ->sum('amount');
            
        $disbursedGrowth = $previousDisbursed > 0 
            ? round((($totalDisbursed - $previousDisbursed) / $previousDisbursed) * 100) 
            : 0;
        
        // Pending requests
        $pendingRequests = FundRequest::where('status', 'Pending')
            ->count();
            
        $previousPendingRequests = FundRequest::where('status', 'Pending')
            ->where('created_at', '<', $startDate)
            ->count();
            
        $pendingGrowth = $previousPendingRequests > 0 
            ? round((($pendingRequests - $previousPendingRequests) / $previousPendingRequests) * 100) 
            : 0;
        
        // Completion rate
        $completedScholars = Scholar::where('status', 'Completed')
            ->count();
            
        $totalActiveScholars = Scholar::whereIn('status', ['Active', 'Completed'])
            ->count();
            
        $completionRate = $totalActiveScholars > 0 
            ? round(($completedScholars / $totalActiveScholars) * 100) 
            : 0;
            
        $previousCompletedScholars = Scholar::where('status', 'Completed')
            ->where('updated_at', '<', $startDate)
            ->count();
            
        $previousTotalActiveScholars = Scholar::whereIn('status', ['Active', 'Completed'])
            ->where('updated_at', '<', $startDate)
            ->count();
            
        $previousCompletionRate = $previousTotalActiveScholars > 0 
            ? round(($previousCompletedScholars / $previousTotalActiveScholars) * 100) 
            : 0;
            
        $completionGrowth = $previousCompletionRate > 0 
            ? $completionRate - $previousCompletionRate 
            : 0;
        
        // Scholar distribution by program
        $phdScholars = Scholar::where('program', 'PhD')->count();
        $mastersScholars = Scholar::where('program', 'Masters')->count();
        $undergradScholars = Scholar::where('program', 'Undergraduate')->count();
        
        // Current year completions
        $currentYearCompletions = Scholar::where('status', 'Completed')
            ->whereYear('updated_at', Carbon::now()->year)
            ->count();
        
        return [
            'totalScholars' => $totalScholars,
            'scholarsGrowth' => $scholarsGrowth,
            'totalDisbursed' => $totalDisbursed,
            'disbursedGrowth' => $disbursedGrowth,
            'pendingRequests' => $pendingRequests,
            'pendingGrowth' => $pendingGrowth,
            'completionRate' => $completionRate,
            'completionGrowth' => $completionGrowth,
            'phdScholars' => $phdScholars,
            'mastersScholars' => $mastersScholars,
            'undergradScholars' => $undergradScholars,
            'currentYearCompletions' => $currentYearCompletions
        ];
    }
    
    public function apiData(Request $request)
    {
        $timeframe = $request->input('timeframe', 30);
        
        // Get basic analytics data
        $data = $this->getAnalyticsData($timeframe);
        
        // Add chart data
        $data['disbursementData'] = $this->getDisbursementChartData($timeframe);
        $data['scholarData'] = $this->getScholarChartData();
        $data['requestsData'] = $this->getRequestsChartData($timeframe);
        $data['completionData'] = $this->getCompletionChartData();
        
        return response()->json($data);
    }
    
    private function getDisbursementChartData($timeframe)
    {
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays($timeframe);
        
        $categories = ['Tuition', 'Research', 'Living Allowance', 'Books', 'Conference'];
        $data = [];
        
        foreach ($categories as $category) {
            $amount = FundRequest::where('purpose', $category)
                ->where('status', 'Approved')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount');
                
            $data[] = $amount;
        }
        
        return [
            'labels' => $categories,
            'data' => $data,
            'colors' => [
                'rgba(59, 130, 246, 0.7)',
                'rgba(16, 185, 129, 0.7)',
                'rgba(239, 68, 68, 0.7)',
                'rgba(245, 158, 11, 0.7)',
                'rgba(139, 92, 246, 0.7)'
            ]
        ];
    }
    
    private function getScholarChartData()
    {
        $phdScholars = Scholar::where('program', 'PhD')->count();
        $mastersScholars = Scholar::where('program', 'Masters')->count();
        $undergradScholars = Scholar::where('program', 'Undergraduate')->count();
        
        return [
            'labels' => ['PhD', 'Masters', 'Undergraduate'],
            'data' => [$phdScholars, $mastersScholars, $undergradScholars],
            'colors' => ['rgba(139, 92, 246, 0.7)', 'rgba(59, 130, 246, 0.7)', 'rgba(16, 185, 129, 0.7)']
        ];
    }
    
    private function getRequestsChartData($timeframe)
    {
        $months = [];
        $submittedData = [];
        $approvedData = [];
        $rejectedData = [];
        
        // Get the last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M');
            
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();
            
            // Submitted requests for this month
            $submitted = FundRequest::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();
                
            // Approved requests for this month
            $approved = FundRequest::where('status', 'Approved')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();
                
            // Rejected requests for this month
            $rejected = FundRequest::where('status', 'Rejected')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();
                
            $submittedData[] = $submitted;
            $approvedData[] = $approved;
            $rejectedData[] = $rejected;
        }
        
        return [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'Submitted',
                    'data' => $submittedData,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'tension' => 0.4
                ],
                [
                    'label' => 'Approved',
                    'data' => $approvedData,
                    'borderColor' => 'rgb(16, 185, 129)',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'tension' => 0.4
                ],
                [
                    'label' => 'Rejected',
                    'data' => $rejectedData,
                    'borderColor' => 'rgb(239, 68, 68)',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'tension' => 0.4
                ]
            ]
        ];
    }
    
    private function getCompletionChartData()
    {
        $years = [];
        $expectedData = [];
        $actualData = [];
        
        // Get data for the last 6 years
        $currentYear = Carbon::now()->year;
        
        for ($i = 5; $i >= 0; $i--) {
            $year = $currentYear - $i;
            $years[] = (string)$year;
            
            // Expected completions (this would typically come from a plan or projection)
            // For demo purposes, we'll use a simple formula
            $expected = 15 + ($i * 4);
            
            // Actual completions
            $actual = Scholar::where('status', 'Completed')
                ->whereYear('updated_at', $year)
                ->count();
                
            $expectedData[] = $expected;
            $actualData[] = $actual;
        }
        
        return [
            'labels' => $years,
            'datasets' => [
                [
                    'label' => 'Expected Completions',
                    'data' => $expectedData,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderDash' => [5, 5],
                    'tension' => 0.4
                ],
                [
                    'label' => 'Actual Completions',
                    'data' => $actualData,
                    'borderColor' => 'rgb(16, 185, 129)',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'tension' => 0.4
                ]
            ]
        ];
    }
}