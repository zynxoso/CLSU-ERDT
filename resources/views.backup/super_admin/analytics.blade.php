@extends('layouts.app')

@section('title', 'Advanced Analytics')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Advanced Analytics</h1>
        <a href="{{ route('super_admin.dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded inline-flex items-center text-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    <!-- Analytics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 mr-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Total Users</div>
                    <div class="text-xl font-bold text-gray-900">{{ $users->count() }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 mr-3">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Total Disbursed</div>
                    <div class="text-xl font-bold text-gray-900">₱{{ number_format($fundsByType->sum(), 2) }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 mr-3">
                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Active Scholars</div>
                    <div class="text-xl font-bold text-gray-900">{{ $scholarsByStatus['Active'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 mr-3">
                    <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Pending Scholars</div>
                    <div class="text-xl font-bold text-gray-900">{{ $scholarsByStatus['Pending'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- User Distribution Chart -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">User Distribution</h2>
            </div>
            <div class="p-4">
                <div class="h-64 flex items-end justify-around">
                    @foreach($usersByRole as $role => $count)
                        <div class="flex flex-col items-center">
                            <div class="text-xs text-gray-500 mb-1">{{ $count }}</div>
                            <div class="w-16 bg-indigo-500 rounded-t-lg" style="height: {{ ($count / $users->count()) * 200 }}px;"></div>
                            <div class="text-sm font-medium text-gray-700 mt-2">{{ $role }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Monthly Disbursement Chart -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">Monthly Disbursements</h2>
            </div>
            <div class="p-4">
                <div class="h-64 flex items-end space-x-2 overflow-x-auto">
                    @foreach($monthlyData as $data)
                        <div class="flex flex-col items-center min-w-[40px]">
                            <div class="text-xs text-gray-500 mb-1">₱{{ number_format($data['disbursed'], 0) }}</div>
                            <div class="w-8 bg-green-500 rounded-t-lg" style="height: {{ ($data['disbursed'] > 0 ? ($data['disbursed'] / max(array_column($monthlyData, 'disbursed'))) * 180 : 0) }}px;"></div>
                            <div class="text-xs font-medium text-gray-700 mt-2 transform -rotate-45 origin-top-left">{{ $data['month'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Analytics -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- User Activity -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">User Activity</h2>
            </div>
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity Count</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($userActivity as $activity)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($activity->date)->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-2 bg-indigo-500 rounded" style="width: {{ min(($activity->count / 10) * 100, 100) }}%;"></div>
                                            <span class="ml-2 text-sm text-gray-700">{{ $activity->count }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Fund Distribution by Type -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">Fund Distribution by Type</h2>
            </div>
            <div class="p-4">
                <div class="space-y-4">
                    @foreach($fundsByType as $type => $amount)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium text-gray-700">{{ $type ?: 'Unspecified' }}</span>
                                <span class="text-sm text-gray-500">₱{{ number_format($amount, 2) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($amount / $fundsByType->sum()) * 100 }}%;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
