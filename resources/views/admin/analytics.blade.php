@extends('layouts.app')

@section('title', 'Admin Analytics')

@section('content')
<div class="analytics-dashboard">
    <div class="analytics-dashboard__header">
        <h1 class="analytics-dashboard__header-title">Admin Analytics Dashboard</h1>

        <div class="relative" x-data="{ open: false }">
            <button id="timeframe-btn" class="flex items-center px-4 py-2 bg-gray-800 rounded-lg text-gray-300 hover:bg-gray-700" @click="open = !open">
                <span>Last 30 Days</span>
                <i class="fas fa-chevron-down ml-2"></i>
            </button>

            <div id="timeframe-dropdown" class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-lg shadow-lg z-10 hidden" x-show="open" @click.away="open = false">
                <div class="py-1">
                    <a href="#" class="block px-4 py-2 text-gray-300 hover:bg-gray-700" data-value="7">Last 7 Days</a>
                    <a href="#" class="block px-4 py-2 text-gray-300 hover:bg-gray-700" data-value="30">Last 30 Days</a>
                    <a href="#" class="block px-4 py-2 text-gray-300 hover:bg-gray-700" data-value="90">Last 90 Days</a>
                    <a href="#" class="block px-4 py-2 text-gray-300 hover:bg-gray-700" data-value="365">Last Year</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-6">
        <!-- KPI Cards -->
        <div class="analytics-dashboard__kpi-grid">
            <div class="analytics-dashboard__kpi-card">
                <div class="analytics-dashboard__kpi-card-header">
                    <span class="analytics-dashboard__kpi-card-title">Total Scholars</span>
                    <i class="fas fa-user-graduate text-blue-500"></i>
                </div>
                <div class="analytics-dashboard__kpi-card-value">{{ $totalScholars ?? 0 }}</div>
                <div class="flex items-center mt-2">
                    <span class="analytics-dashboard__kpi-card-trend--up text-sm">
                        <i class="fas fa-arrow-up mr-1"></i> {{ $scholarsGrowth ?? '0%' }}
                    </span>
                    <span class="text-gray-400 text-sm ml-2">vs previous period</span>
                </div>
            </div>

            <div class="analytics-dashboard__kpi-card">
                <div class="analytics-dashboard__kpi-card-header">
                    <span class="analytics-dashboard__kpi-card-title">Total Disbursed</span>
                    <i class="fas fa-money-bill-wave text-green-500"></i>
                </div>
                <div class="analytics-dashboard__kpi-card-value">₱{{ number_format($totalDisbursed ?? 0) }}</div>
                <div class="flex items-center mt-2">
                    <span class="analytics-dashboard__kpi-card-trend--up text-sm">
                        <i class="fas fa-arrow-up mr-1"></i> {{ $disbursedGrowth ?? '0%' }}
                    </span>
                    <span class="text-gray-400 text-sm ml-2">vs previous period</span>
                </div>
            </div>

            <div class="analytics-dashboard__kpi-card">
                <div class="analytics-dashboard__kpi-card-header">
                    <span class="analytics-dashboard__kpi-card-title">Pending Requests</span>
                    <i class="fas fa-clock text-yellow-500"></i>
                </div>
                <div class="analytics-dashboard__kpi-card-value">{{ $pendingRequests ?? 0 }}</div>
                <div class="flex items-center mt-2">
                    <span class="analytics-dashboard__kpi-card-trend--down text-sm">
                        <i class="fas fa-arrow-down mr-1"></i> {{ $pendingGrowth ?? '0%' }}
                    </span>
                    <span class="text-gray-400 text-sm ml-2">vs previous period</span>
                </div>
            </div>

            <div class="analytics-dashboard__kpi-card">
                <div class="analytics-dashboard__kpi-card-header">
                    <span class="analytics-dashboard__kpi-card-title">Completion Rate</span>
                    <i class="fas fa-graduation-cap text-purple-500"></i>
                </div>
                <div class="analytics-dashboard__kpi-card-value">{{ $completionRate ?? 0 }}%</div>
                <div class="flex items-center mt-2">
                    <span class="analytics-dashboard__kpi-card-trend--up text-sm">
                        <i class="fas fa-arrow-up mr-1"></i> {{ $completionGrowth ?? '0%' }}
                    </span>
                    <span class="text-gray-400 text-sm ml-2">vs previous period</span>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="analytics-dashboard__charts-grid">
            <div class="analytics-dashboard__chart-card">
                <div class="analytics-dashboard__chart-card-header">
                    <h2 class="text-lg font-semibold text-gray-300">Fund Disbursement by Category</h2>
                </div>
                <div class="analytics-dashboard__chart-card-body">
                    <canvas id="disbursementChart"></canvas>
                </div>
            </div>

            <div class="analytics-dashboard__chart-card">
                <div class="analytics-dashboard__chart-card-header">
                    <h2 class="text-lg font-semibold text-gray-300">Scholar Distribution</h2>
                </div>
                <div class="analytics-dashboard__chart-card-body">
                    <canvas id="scholarChart"></canvas>
                </div>
            </div>

            <div class="analytics-dashboard__chart-card">
                <div class="analytics-dashboard__chart-card-header">
                    <h2 class="text-lg font-semibold text-gray-300">Monthly Fund Requests</h2>
                </div>
                <div class="analytics-dashboard__chart-card-body">
                    <canvas id="requestsChart"></canvas>
                </div>
            </div>

            <div class="analytics-dashboard__chart-card">
                <div class="analytics-dashboard__chart-card-header">
                    <h2 class="text-lg font-semibold text-gray-300">Completion Timeline</h2>
                </div>
                <div class="analytics-dashboard__chart-card-body">
                    <canvas id="completionChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/analytics.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sample data for charts - in production, this would come from the backend
        const disbursementData = {
            labels: ['Tuition', 'Research', 'Living Allowance', 'Books', 'Conference'],
            data: [2500000, 1800000, 3200000, 500000, 1200000],
            colors: [
                'rgba(59, 130, 246, 0.7)',
                'rgba(16, 185, 129, 0.7)',
                'rgba(239, 68, 68, 0.7)',
                'rgba(245, 158, 11, 0.7)',
                'rgba(139, 92, 246, 0.7)'
            ]
        };

        const scholarData = {
            labels: ['PhD', 'Masters', 'Undergraduate'],
            data: [{{ $phdScholars ?? 0 }}, {{ $mastersScholars ?? 0 }}, {{ $undergradScholars ?? 0 }}],
            colors: ['rgba(139, 92, 246, 0.7)', 'rgba(59, 130, 246, 0.7)', 'rgba(16, 185, 129, 0.7)']
        };

        const requestsData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [
                {
                    label: 'Submitted',
                    data: [45, 52, 38, 60, 55, 68, 72, 63, 58, 50, 55, 60],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Approved',
                    data: [40, 45, 35, 50, 48, 60, 65, 55, 50, 45, 48, 52],
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4
                },
                {
                    label: 'Rejected',
                    data: [5, 7, 3, 10, 7, 8, 7, 8, 8, 5, 7, 8],
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4
                }
            ]
        };

        const completionData = {
            labels: ['2018', '2019', '2020', '2021', '2022', '2023'],
            datasets: [
                {
                    label: 'Expected Completions',
                    data: [15, 18, 20, 25, 30, 35],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderDash: [5, 5],
                    tension: 0.4
                },
                {
                    label: 'Actual Completions',
                    data: [12, 15, 18, 22, 28, {{ $currentYearCompletions ?? 0 }}],
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4
                }
            ]
        };

        // Initialize charts
        initDisbursementChart(disbursementData);
        initScholarChart(scholarData);
        initRequestsChart(requestsData);
        initCompletionChart(completionData);

        function initDisbursementChart(data) {
            new Chart(document.getElementById('disbursementChart'), {
                type: 'pie',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.data,
                        backgroundColor: data.colors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        function initScholarChart(data) {
            new Chart(document.getElementById('scholarChart'), {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.data,
                        backgroundColor: data.colors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        function initRequestsChart(data) {
            new Chart(document.getElementById('requestsChart'), {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        function initCompletionChart(data) {
            new Chart(document.getElementById('completionChart'), {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
