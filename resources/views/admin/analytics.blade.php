@extends('layouts.app')

@section('title', 'Admin Analytics')

@section('content')
<div class="analytics-dashboard">
    <div class="analytics-dashboard__header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Admin Analytics Dashboard</h1>
                <p class="text-sm text-gray-600 mt-1">Comprehensive overview of system performance</p>
            </div>

            <div class="relative" x-data="{ open: false }">
                <button id="timeframe-btn" class="flex items-center px-4 py-2 bg-gray-800 rounded-lg text-gray-300 hover:bg-gray-700 w-full sm:w-auto justify-center" @click="open = !open">
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
    </div>

    <div class="container py-6">
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-600">Total Scholars</span>
                    <i class="fas fa-user-graduate text-blue-500 text-xl"></i>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-2">{{ $totalScholars ?? 0 }}</div>
                <div class="flex items-center">
                    <span class="inline-flex items-center text-sm text-green-600">
                        <i class="fas fa-arrow-up mr-1"></i> {{ $scholarsGrowth ?? '0%' }}
                    </span>
                    <span class="text-gray-400 text-sm ml-2">vs previous period</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-600">Total Disbursed</span>
                    <i class="fas fa-money-bill-wave text-green-500 text-xl"></i>
                </div>
                <div class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">₱{{ number_format($totalDisbursed ?? 0) }}</div>
                <div class="flex items-center">
                    <span class="inline-flex items-center text-sm text-green-600">
                        <i class="fas fa-arrow-up mr-1"></i> {{ $disbursedGrowth ?? '0%' }}
                    </span>
                    <span class="text-gray-400 text-sm ml-2">vs previous period</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-600">Pending Requests</span>
                    <i class="fas fa-clock text-yellow-500 text-xl"></i>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-2">{{ $pendingRequests ?? 0 }}</div>
                <div class="flex items-center">
                    <span class="inline-flex items-center text-sm text-red-600">
                        <i class="fas fa-arrow-down mr-1"></i> {{ $pendingGrowth ?? '0%' }}
                    </span>
                    <span class="text-gray-400 text-sm ml-2">vs previous period</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-600">Completion Rate</span>
                    <i class="fas fa-graduation-cap text-purple-500 text-xl"></i>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-2">{{ $completionRate ?? 0 }}%</div>
                <div class="flex items-center">
                    <span class="inline-flex items-center text-sm text-green-600">
                        <i class="fas fa-arrow-up mr-1"></i> {{ $completionGrowth ?? '0%' }}
                    </span>
                    <span class="text-gray-400 text-sm ml-2">vs previous period</span>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Fund Disbursement by Category</h2>
                </div>
                <div class="p-6">
                    <div class="relative h-64 sm:h-80">
                        <canvas id="disbursementChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Scholar Distribution</h2>
                </div>
                <div class="p-6">
                    <div class="relative h-64 sm:h-80">
                        <canvas id="scholarChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Monthly Fund Requests</h2>
                </div>
                <div class="p-6">
                    <div class="relative h-64 sm:h-80">
                        <canvas id="requestsChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Completion Timeline</h2>
                </div>
                <div class="p-6">
                    <div class="relative h-64 sm:h-80">
                        <canvas id="completionChart"></canvas>
                    </div>
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

        // Chart configuration with responsive options
        const responsiveOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: window.innerWidth < 640 ? 'bottom' : 'right',
                    labels: {
                        boxWidth: 12,
                        padding: window.innerWidth < 640 ? 10 : 20,
                        font: {
                            size: window.innerWidth < 640 ? 10 : 12
                        }
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        font: {
                            size: window.innerWidth < 640 ? 10 : 12
                        }
                    }
                },
                y: {
                    ticks: {
                        font: {
                            size: window.innerWidth < 640 ? 10 : 12
                        }
                    }
                }
            }
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
                    ...responsiveOptions,
                    plugins: {
                        ...responsiveOptions.plugins,
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ₱' + context.parsed.toLocaleString();
                                }
                            }
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
                options: responsiveOptions
            });
        }

        function initRequestsChart(data) {
            new Chart(document.getElementById('requestsChart'), {
                type: 'line',
                data: data,
                options: responsiveOptions
            });
        }

        function initCompletionChart(data) {
            new Chart(document.getElementById('completionChart'), {
                type: 'line',
                data: data,
                options: responsiveOptions
            });
        }

        // Handle window resize for chart responsiveness
        window.addEventListener('resize', function() {
            Chart.helpers.each(Chart.instances, function(instance) {
                instance.resize();
            });
        });
    });
</script>
@endsection
