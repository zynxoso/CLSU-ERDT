@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('content')
<div x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 100)" class="space-y-6">
<!-- Welcome Section -->
<div class="mb-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-6 shadow-sm hover:shadow-md transition-all duration-300"
     :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-4'"
     class="transition-all duration-500 ease-out transform">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Welcome, Super Admin {{ Auth::user()->name }}!</h1>
            <p class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="mb-6"
     :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
     class="transition-all duration-500 delay-200 ease-out transform">
    <div class="flex justify-between items-center mb-3">
        <h2 class="text-lg font-semibold text-gray-800">System Overview</h2>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
        <!-- Active Scholars -->
        <div class="bg-white rounded-lg shadow p-4 transition-all duration-300 hover:shadow-lg hover:bg-blue-50 cursor-pointer group relative"
             x-data="{ countLoaded: false, count: 0, showTooltip: false }"
             @mouseenter="showTooltip = true"
             @mouseleave="showTooltip = false"
             x-init="setTimeout(() => {
                 countLoaded = true;
                 const targetValue = {{ $totalScholars }};
                 const duration = 2000;

                 if (targetValue === 0) {
                     count = 0;
                     return;
                 }

                 const startTime = performance.now();
                 const updateCount = (timestamp) => {
                     const elapsedTime = timestamp - startTime;
                     const progress = Math.min(elapsedTime / duration, 1);
                     count = Math.round(progress * targetValue);

                     if (progress < 1) {
                         requestAnimationFrame(updateCount);
                     } else {
                         count = targetValue; // Ensure final value is exact
                     }
                 };

                 requestAnimationFrame(updateCount);
             }, 500)">
            <div class="flex items-center justify-between">
                <div class="text-sm font-medium text-gray-500">Active Scholars</div>
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 group-hover:bg-blue-200 transition-colors duration-300">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-bold text-gray-900 group-hover:text-blue-700 transition-colors duration-300" x-text="count">0</div>
                <div class="mt-1 text-xs text-gray-400">Currently enrolled students</div>
            </div>
            <div x-show="showTooltip" class="tooltip-popup" x-cloak>
                Number of currently active scholars in the system. Click to view the complete scholars list.
            </div>
        </div>

        <!-- Admin Users -->
        <div class="bg-white rounded-lg shadow p-4 transition-all duration-300 hover:shadow-lg hover:bg-indigo-50 cursor-pointer group relative"
             x-data="{ countLoaded: false, count: 0, showTooltip: false }"
             @mouseenter="showTooltip = true"
             @mouseleave="showTooltip = false"
             x-init="setTimeout(() => {
                 countLoaded = true;
                 const targetValue = {{ $adminUsers }};
                 const duration = 2000;

                 if (targetValue === 0) {
                     count = 0;
                     return;
                 }

                 const startTime = performance.now();
                 const updateCount = (timestamp) => {
                     const elapsedTime = timestamp - startTime;
                     const progress = Math.min(elapsedTime / duration, 1);
                     count = Math.round(progress * targetValue);

                     if (progress < 1) {
                         requestAnimationFrame(updateCount);
                     } else {
                         count = targetValue; // Ensure final value is exact
                     }
                 };

                 requestAnimationFrame(updateCount);
             }, 700)">
            <div class="flex items-center justify-between">
                <div class="text-sm font-medium text-gray-500">Admin Users</div>
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 group-hover:bg-indigo-200 transition-colors duration-300">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-bold text-gray-900 group-hover:text-indigo-700 transition-colors duration-300" x-text="count">0</div>
                <div class="mt-1 text-xs text-gray-400">System administrators</div>
            </div>
            <div x-show="showTooltip" class="tooltip-popup" x-cloak>
                Number of admin users in the system. Click to manage admin users.
            </div>
        </div>


        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow p-4 transition-all duration-300 hover:shadow-lg hover:bg-purple-50 cursor-pointer group relative"
             x-data="{ countLoaded: false, count: 0, showTooltip: false }"
             @mouseenter="showTooltip = true"
             @mouseleave="showTooltip = false"
             x-init="setTimeout(() => {
                 countLoaded = true;
                 const targetValue = {{ $allUsers->count() ?? 0 }};
                 const duration = 2000;

                 if (targetValue === 0) {
                     count = 0;
                     return;
                 }

                 const startTime = performance.now();
                 const updateCount = (timestamp) => {
                     const elapsedTime = timestamp - startTime;
                     const progress = Math.min(elapsedTime / duration, 1);
                     count = Math.round(progress * targetValue);

                     if (progress < 1) {
                         requestAnimationFrame(updateCount);
                     } else {
                         count = targetValue; // Ensure final value is exact
                     }
                 };

                 requestAnimationFrame(updateCount);
             }, 1100)">
            <div class="flex items-center justify-between">
                <div class="text-sm font-medium text-gray-500">Total Users</div>
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-purple-100 group-hover:bg-purple-200 transition-colors duration-300">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-bold text-gray-900 group-hover:text-purple-700 transition-colors duration-300" x-text="count">0</div>
                <div class="mt-1 text-xs text-gray-400">Total users in the system</div>
            </div>
            <div x-show="showTooltip" class="tooltip-popup" x-cloak>
                Total number of users in the system including scholars, admins, and super admins. Click to manage users.
            </div>
        </div>
    </div>
</div>

    <!-- Super Admin Actions -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <h3 class="font-semibold text-gray-800 mb-4">Super Admin Actions</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ route('super_admin.user_management') }}" class="flex items-center p-3 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors duration-300">
            <div class="flex-shrink-0 mr-3">
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <h4 class="font-medium text-gray-900">User Management</h4>
                <p class="text-xs text-gray-500">Manage all users and roles</p>
            </div>
        </a>

        <a href="{{ route('super_admin.system_settings') }}" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-300">
            <div class="flex-shrink-0 mr-3">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <h4 class="font-medium text-gray-900">System Settings</h4>
                <p class="text-xs text-gray-500">Configure system parameters</p>
            </div>
        </a>
    </div>
</div>

<!-- Recent Activity Section -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <!-- Recent Users -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="flex justify-between items-center px-4 py-3 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Recent Users</h3>
            <a href="{{ route('super_admin.user_management') }}" class="text-blue-600 hover:text-blue-800 text-xs flex items-center">
                View all
                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>

        <div class="p-4">
            @if(count($recentUsers) > 0)
                <div class="space-y-4">
                    @foreach($recentUsers as $user)
                        <div class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-500">
                                        <span class="text-sm font-medium">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ $user->name }}</h4>
                                            <p class="text-xs text-gray-500 mt-1">{{ $user->email }}</p>
                                        </div>
                                        <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full
                                            @if($user->role === 'super_admin') bg-purple-100 text-purple-800
                                            @elseif($user->role === 'admin') bg-blue-100 text-blue-800
                                            @elseif($user->role === 'scholar') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">Created {{ $user->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6">
                    <div class="w-12 h-12 mx-auto bg-indigo-50 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500">No recent users found</p>
                </div>
            @endif
        </div>
    </div>

</div>
</div>

@section('styles')
<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    [x-cloak] { display: none !important; }

    .tooltip {
        position: relative;
    }

    .tooltip-text {
        visibility: hidden;
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(51, 65, 85, 0.95);
        color: white;
        text-align: center;
        padding: 5px 10px;
        border-radius: 6px;
        width: max-content;
        max-width: 250px;
        font-size: 0.75rem;
        z-index: 100;
        opacity: 0;
        transition: opacity 0.3s;
        pointer-events: none;
    }

    .tooltip-text::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: rgba(51, 65, 85, 0.95) transparent transparent transparent;
    }

    .tooltip:hover .tooltip-text {
        visibility: visible;
        opacity: 1;
        animation: fadeIn 0.3s;
    }

    .tooltip-popup {
        position: fixed;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(51, 65, 85, 0.95);
        color: white;
        text-align: center;
        padding: 5px 10px;
        border-radius: 6px;
        width: max-content;
        max-width: 200px;
        font-size: 0.75rem;
        z-index: 100;
        pointer-events: none;
        animation: fadeIn 0.3s;
    }

    .tooltip-popup::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: rgba(51, 65, 85, 0.95) transparent transparent transparent;
    }
</style>
@endsection
@endsection
