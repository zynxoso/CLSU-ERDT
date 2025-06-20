@extends('layouts.app')

@section('title', 'Analytics')

@section('content')
<div class="bg-gray-900 min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold text-white mb-6">Analytics Dashboard</h1>

        <!-- Date Range Filter -->
        <div class="bg-gray-800 rounded-lg p-4 mb-6 border border-gray-700">
            <form action="{{ route('admin.analytics') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="start_date" class="block text-sm font-medium text-gray-400 mb-1">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ request('start_date', date('Y-m-d', strtotime('-1 year'))) }}" class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="end_date" class="block text-sm font-medium text-gray-400 mb-1">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ request('end_date', date('Y-m-d')) }}" class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-end w-full sm:w-auto">
                    <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-filter mr-2"></i> Apply Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-blue-500 bg-opacity-20 flex items-center justify-center mr-4">
                        <i class="fas fa-user-graduate text-blue-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Active Scholars</p>
                        <p class="text-2xl font-bold text-white">{{ $activeScholars }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Previous Period</span>
                        <span class="text-white">{{ $previousActiveScholars }}</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-1.5 mt-1">
                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ ($activeScholars / max($previousActiveScholars, 1)) * 100 }}%"></div>
                    </div>
                    <div class="flex justify-end text-xs mt-1">
                        @if($activeScholars > $previousActiveScholars)
                            <span class="text-green-400">+{{ $activeScholars - $previousActiveScholars }} ({{ round((($activeScholars - $previousActiveScholars) / max($previousActiveScholars, 1)) * 100, 1) }}%)</span>
                        @elseif($activeScholars < $previousActiveScholars)
                            <span class="text-red-400">-{{ $previousActiveScholars - $activeScholars }} ({{ round((($previousActiveScholars - $activeScholars) / max($previousActiveScholars, 1)) * 100, 1) }}%)</span>
                        @else
                            <span class="text-gray-400">No change</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-green-500 bg-opacity-20 flex items-center justify-center mr-4">
                        <i class="fas fa-money-bill-wave text-green-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Total Disbursed</p>
                        <p class="text-2xl font-bold text-white">₱{{ number_format($totalDisbursed, 0) }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Previous Period</span>
                        <span class="text-white">₱{{ number_format($previousTotalDisbursed, 0) }}</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-1.5 mt-1">
                        <div class="bg-green-600 h-1.5 rounded-full" style="width: {{ ($totalDisbursed / max($previousTotalDisbursed, 1)) * 100 }}%"></div>
                    </div>
                    <div class="flex justify-end text-xs mt-1">
                        @if($totalDisbursed > $previousTotalDisbursed)
                            <span class="text-green-400">+₱{{ number_format($totalDisbursed - $previousTotalDisbursed, 0) }} ({{ round((($totalDisbursed - $previousTotalDisbursed) / max($previousTotalDisbursed, 1)) * 100, 1) }}%)</span>
                        @elseif($totalDisbursed < $previousTotalDisbursed)
                            <span class="text-red-400">-₱{{ number_format($previousTotalDisbursed - $totalDisbursed, 0) }} ({{ round((($previousTotalDisbursed - $totalDisbursed) / max($previousTotalDisbursed, 1)) * 100, 1) }}%)</span>
                        @else
                            <span class="text-gray-400">No change</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-purple-500 bg-opacity-20 flex items-center justify-center mr-4">
                        <i class="fas fa-graduation-cap text-purple-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Completion Rate</p>
                        <p class="text-2xl font-bold text-white">{{ $completionRate }}%</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Previous Period</span>
                        <span class="text-white">{{ $previousCompletionRate }}%</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-1.5 mt-1">
                        <div class="bg-purple-600 h-1.5 rounded-full" style="width: {{ ($completionRate / max($previousCompletionRate, 1)) * 100 }}%"></div>
                    </div>
                    <div class="flex justify-end text-xs mt-1">
                        @if($completionRate > $previousCompletionRate)
                            <span class="text-green-400">+{{ $completionRate - $previousCompletionRate }}%</span>
                        @elseif($completionRate < $previousCompletionRate)
                            <span class="text-red-400">-{{ $previousCompletionRate - $completionRate }}%</span>
                        @else
                            <span class="text-gray-400">No change</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-yellow-500 bg-opacity-20 flex items-center justify-center mr-4">
                        <i class="fas fa-book text-yellow-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Publications</p>
                        <p class="text-2xl font-bold text-white">{{ $publicationsCount }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Previous Period</span>
                        <span class="text-white">{{ $previousPublicationsCount }}</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-1.5 mt-1">
                        <div class="bg-yellow-600 h-1.5 rounded-full" style="width: {{ ($publicationsCount / max($previousPublicationsCount, 1)) * 100 }}%"></div>
                    </div>
                    <div class="flex justify-end text-xs mt-1">
                        @if($publicationsCount > $previousPublicationsCount)
                            <span class="text-green-400">+{{ $publicationsCount - $previousPublicationsCount }} ({{ round((($publicationsCount - $previousPublicationsCount) / max($previousPublicationsCount, 1)) * 100, 1) }}%)</span>
                        @elseif($publicationsCount < $previousPublicationsCount)
                            <span class="text-red-400">-{{ $previousPublicationsCount - $publicationsCount }} ({{ round((($previousPublicationsCount - $publicationsCount) / max($previousPublicationsCount, 1)) * 100, 1) }}%)</span>
                        @else
                            <span class="text-gray-400">No change</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Scholar Distribution Chart -->
            <div class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
                <div class="bg-gray-900 px-6 py-4 border-b border-gray-700">
                    <h2 class="text-lg font-semibold text-white">Scholar Distribution</h2>
                </div>
                <div class="p-6">
                    <canvas id="scholarDistributionChart" height="300"></canvas>
                </div>
            </div>

            <!-- Fund Disbursement Chart -->
            <div class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
                <div class="bg-gray-900 px-6 py-4 border-b border-gray-700">
                    <h2 class="text-lg font-semibold text-white">Fund Disbursement Trend</h2>
                </div>
                <div class="p-6">
                    <canvas id="fundDisbursementChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- More Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Completion Rate by Program -->
            <div class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
                <div class="bg-gray-900 px-6 py-4 border-b border-gray-700">
                    <h2 class="text-lg font-semibold text-white">Completion Rate by Program</h2>
                </div>
                <div class="p-6">
                    <canvas id="completionRateChart" height="300"></canvas>
                </div>
            </div>

            <!-- Publications by Category -->
            <div class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
                <div class="bg-gray-900 px-6 py-4 border-b border-gray-700">
                    <h2 class="text-lg font-semibold text-white">Publications by Category</h2>
                </div>
                <div class="p-6">
                    <canvas id="publicationsCategoryChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Data Tables -->
        <div class="grid grid-cols-1 gap-6">
            <!-- Top Scholars -->
            <div class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
                <div class="bg-gray-900 px-6 py-4 border-b border-gray-700">
                    <h2 class="text-lg font-semibold text-white">Top Performing Scholars</h2>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-700">
                            <thead class="bg-gray-900">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Scholar</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Program</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">University</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Publications</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Progress</th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-800 divide-y divide-gray-700">
                                @foreach($topScholars as $scholar)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-700 flex items-center justify-center">
                                                    @if($scholar->user->profile_photo)
                                                        <img src="{{ asset('storage/' . $scholar->user->profile_photo) }}" alt="{{ $scholar->user->name }}" class="h-8 w-8 rounded-full">
                                                    @else
                                                        <i class="fas fa-user text-gray-400"></i>
                                                    @endif
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-white">{{ $scholar->user->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $scholar->program }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $scholar->university }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $scholar->publications_count }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="w-full bg-gray-700 rounded-full h-2.5">
                                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $scholar->progress }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-400 mt-1">{{ $scholar->progress }}%</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Set Chart.js defaults for dark theme
    Chart.defaults.color = '#9ca3af';
    Chart.defaults.borderColor = '#374151';

    // Scholar Distribution Chart
    const scholarDistributionCtx = document.getElementById('scholarDistributionChart').getContext('2d');
    const scholarDistributionChart = new Chart(scholarDistributionCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($scholarDistribution->pluck('program')) !!},
            datasets: [{
                data: {!! json_encode($scholarDistribution->pluck('count')) !!},
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });

    // Fund Disbursement Chart
    const fundDisbursementCtx = document.getElementById('fundDisbursementChart').getContext('2d');
    const fundDisbursementChart = new Chart(fundDisbursementCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($fundDisbursementTrend->pluck('month')) !!},
            datasets: [{
                label: 'Fund Disbursement',
                data: {!! json_encode($fundDisbursementTrend->pluck('amount')) !!},
                borderColor: 'rgba(16, 185, 129, 1)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Completion Rate Chart
    const completionRateCtx = document.getElementById('completionRateChart').getContext('2d');
    const completionRateChart = new Chart(completionRateCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($completionRateByProgram->pluck('program')) !!},
            datasets: [{
                label: 'Completion Rate (%)',
                data: {!! json_encode($completionRateByProgram->pluck('rate')) !!},
                backgroundColor: 'rgba(139, 92, 246, 0.8)',
                borderWidth: 0,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    });

    // Publications Category Chart
    const publicationsCategoryCtx = document.getElementById('publicationsCategoryChart').getContext('2d');
    const publicationsCategoryChart = new Chart(publicationsCategoryCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($publicationsByCategory->pluck('category')) !!},
            datasets: [{
                data: {!! json_encode($publicationsByCategory->pluck('count')) !!},
                backgroundColor: [
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(139, 92, 246, 0.8)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });
</script>
@endpush
@endsection
