@extends('layouts.app')

@section('title', 'Redis Cached Dashboard')

@section('content')
<div x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 100)" class="space-y-6">
    <!-- Welcome Section with Redis Indicator -->
    <div class="mb-6 bg-gradient-to-r from-blue-50 to-red-50 rounded-lg p-6 shadow-sm hover:shadow-md transition-all duration-300"
         :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-4'"
         class="transition-all duration-500 ease-out transform">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Redis Cached Dashboard</h1>
                <p class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
                <div class="mt-2 flex items-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        Redis Cached
                    </span>
                    <form action="{{ route('admin.clear-cache') }}" method="POST" class="ml-3">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Clear Cache
                        </button>
                    </form>
                </div>
            </div>
            <div class="hidden md:block">
                <img src="{{ asset('images/logo.png') }}" alt="CLSU Logo" class="h-12">
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="mb-6"
         :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
         class="transition-all duration-500 delay-200 ease-out transform">
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-lg font-semibold text-gray-800">Dashboard Overview (Redis Cached)</h2>
            <div class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                <span class="inline-flex items-center">
                    <svg class="w-3 h-3 mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                    </svg>
                    Cache refreshes every 10 minutes
                </span>
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <!-- Total Scholars -->
            <div class="bg-white rounded-lg shadow p-4 transition-all duration-300 hover:shadow-lg hover:bg-blue-50 cursor-pointer group relative">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Scholars</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_scholars'] }}</p>
                    </div>
                    <div class="p-2 bg-blue-100 rounded-lg group-hover:bg-blue-200 transition-colors">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="flex items-center">
                        <div class="text-xs text-gray-500">Active: {{ $stats['active_scholars'] }}</div>
                        <div class="mx-2 text-gray-300">|</div>
                        <div class="text-xs text-gray-500">Graduated: {{ $stats['graduated_scholars'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Fund Requests -->
            <div class="bg-white rounded-lg shadow p-4 transition-all duration-300 hover:shadow-lg hover:bg-purple-50 cursor-pointer group relative">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Fund Requests</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total_fund_requests'] }}</p>
                    </div>
                    <div class="p-2 bg-purple-100 rounded-lg group-hover:bg-purple-200 transition-colors">
                        <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="flex items-center">
                        <div class="text-xs text-gray-500">Pending: {{ $stats['pending_fund_requests'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="mb-4"
         :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
         class="transition-all duration-500 delay-400 ease-out transform">
        <div class="flex justify-between items-center mb-2">
            <h2 class="text-base font-semibold text-gray-800">Recent Fund Requests (Redis Cached)</h2>
            <div class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                <span class="inline-flex items-center">
                    <svg class="w-3 h-3 mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                    </svg>
                    Cached Data
                </span>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @if(count($recentFundRequests) > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($recentFundRequests as $index => $request)
                        <div class="p-3 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-sm font-medium text-blue-700">{{ substr($request->scholar->first_name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-800">
                                            {{ $request->scholar->first_name }} {{ $request->scholar->last_name }}
                                            <span class="px-2 py-0.5 text-xs rounded-full ml-2
                                                @if($request->status == 'Pending') bg-yellow-100 text-yellow-800
                                                @elseif($request->status == 'Approved') bg-green-100 text-green-800
                                                @elseif($request->status == 'Rejected') bg-red-100 text-red-800
                                                @else bg-blue-100 text-blue-800 @endif">
                                                {{ $request->status }}
                                            </span>
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $request->created_at->diffForHumans() }}</p>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $request->purpose }} - ₱{{ number_format($request->amount, 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-3 text-center">
                    <div class="w-8 h-8 mx-auto bg-blue-50 rounded-full flex items-center justify-center mb-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <p class="text-gray-500">No fund requests found</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Activity Logs Section (Not Cached) -->
    <div class="mb-4">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-3 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-sm font-semibold text-gray-800">Recent Activity (Not Cached)</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Real-time Data
                </span>
            </div>
            @if(count($activityLogs) > 0)
                <div class="max-h-80 overflow-y-auto">
                    <ul class="divide-y divide-gray-100">
                        @foreach($activityLogs as $log)
                            <li class="p-3 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        @if($log->user)
                                            <span class="text-sm font-medium text-blue-700">{{ substr($log->user->name, 0, 1) }}</span>
                                        @else
                                            <i class="fas fa-user text-gray-600"></i>
                                        @endif
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-800">
                                                {{ $log->user ? $log->user->name : 'System' }}
                                                <span class="px-2 py-0.5 text-xs rounded-full ml-2
                                                    @if($log->action == 'create') bg-green-100 text-green-800
                                                    @elseif($log->action == 'update') bg-yellow-100 text-yellow-800
                                                    @elseif($log->action == 'delete') bg-red-100 text-red-800
                                                    @elseif($log->action == 'login') bg-blue-100 text-blue-800
                                                    @elseif($log->action == 'logout') bg-purple-100 text-purple-800
                                                    @else bg-blue-100 text-blue-800 @endif">
                                                    {{ ucfirst($log->action) }}
                                                </span>
                                            </p>
                                            <p class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ $log->model_type }}
                                            @if($log->model_id)
                                                <span class="text-gray-500">#{{ $log->model_id }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="mt-4 text-center">
                    <div class="w-12 h-12 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-history text-xl text-gray-500"></i>
                    </div>
                    <p class="text-gray-500">No recent activity found</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
@endsection
