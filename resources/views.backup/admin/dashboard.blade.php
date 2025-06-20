@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Password Change Modal -->
@if((auth()->user()->is_default_password || auth()->user()->must_change_password) && !session('password_warning_dismissed'))
<div x-data="{
    showModal: true,
    dismissModal() {
        this.showModal = false;
        @if(!auth()->user()->must_change_password)
        localStorage.setItem('password_warning_dismissed', 'true');
        @endif
    }
}"
x-show="showModal"
x-transition:enter="transition ease-out duration-300"
x-transition:enter-start="opacity-0"
x-transition:enter-end="opacity-100"
x-transition:leave="transition ease-in duration-200"
x-transition:leave-start="opacity-100"
x-transition:leave-end="opacity-0"
class="fixed inset-0 z-50 overflow-y-auto"
aria-labelledby="modal-title"
role="dialog"
aria-modal="true">

    <!-- Background overlay -->
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">

            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>

                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        @if(auth()->user()->must_change_password)
                            Password Change Required
                        @else
                            Security Notice
                        @endif
                    </h3>

                    <div class="mt-2">
                        <p class="text-sm text-gray-700">
                            @if(auth()->user()->must_change_password)
                                Your password has expired and must be changed immediately for security reasons.
                            @else
                                You are currently using a default password. For your account security, please change your password as soon as possible.
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <a href="{{ route('admin.profile.edit') }}"
                   class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-700 text-base font-medium text-white hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-all duration-200">
                    Change Password
                </a>

                @if(!auth()->user()->must_change_password)
                <button type="button"
                        @click="dismissModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:w-auto sm:text-sm transition-all duration-200">
                    Later
                </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<div x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 100)" class="space-y-6">
<!-- Welcome Section -->
<div class="mb-6 bg-gradient-to-r from-red-50 to-green-50 rounded-lg p-6 shadow-sm hover:shadow-md transition-all duration-300"
     :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-4'"
     class="transition-all duration-500 ease-out transform">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
        </div>
        <!-- <div class="hidden md:block">
            <img src="{{ asset('images/logo.png') }}" alt="CLSU Logo" class="h-12">
        </div> -->
    </div>
</div>

<!-- Stats Grid -->
<div class="mb-6"
     :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
     class="transition-all duration-500 delay-200 ease-out transform">
    <div class="flex justify-between items-center mb-3">
        <h2 class="text-lg font-semibold text-gray-800">Dashboard Overview</h2>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <!-- Active Scholars -->
        <div class="bg-white rounded-lg shadow p-4 transition-all duration-300 hover:shadow-lg hover:bg-red-50 cursor-pointer group relative"
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
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-red-100 group-hover:bg-red-200 transition-colors duration-300">
                    <svg class="w-4 h-4 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-bold text-gray-900 group-hover:text-red-800 transition-colors duration-300" x-text="count">0</div>
                <div class="mt-1 text-xs text-gray-400">Currently enrolled students</div>
            </div>
            <div x-show="showTooltip" class="tooltip-popup" x-cloak>
                Number of currently active scholars in the system. Click to view the complete scholars list.
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="bg-white rounded-lg shadow p-4 transition-all duration-300 hover:shadow-lg hover:bg-yellow-50 cursor-pointer group relative"
             x-data="{ countLoaded: false, count: 0, showTooltip: false }"
             @mouseenter="showTooltip = true"
             @mouseleave="showTooltip = false"
             x-init="setTimeout(() => {
                 countLoaded = true;
                 const targetValue = {{ $pendingRequests }};
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
                <div class="text-sm font-medium text-gray-500">Pending Requests</div>
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100 group-hover:bg-yellow-200 transition-colors duration-300">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-bold text-gray-900 group-hover:text-yellow-700 transition-colors duration-300" x-text="count">0</div>
                <div class="mt-1 text-xs text-gray-400">Awaiting your review</div>
            </div>
            <div x-show="showTooltip" class="tooltip-popup" x-cloak>
                Fund requests awaiting your review. Click to view and process these pending requests.
            </div>
        </div>

        <!-- Total Disbursed -->
        <div class="bg-white rounded-lg shadow p-4 transition-all duration-300 hover:shadow-lg hover:bg-green-50 cursor-pointer group relative"
             x-data="{
                 countLoaded: false,
                 formattedCount: '₱0.00',
                 showTooltip: false,
                 animateCount: function() {
                     const targetValue = {{ $totalDisbursed }};
                     const duration = 2000;

                     if (targetValue === 0) {
                         this.formattedCount = '₱0.00';
                         return;
                     }

                     const formatter = new Intl.NumberFormat('en-US', {
                         style: 'currency',
                         currency: 'PHP',
                         minimumFractionDigits: 2,
                         maximumFractionDigits: 2
                     });

                     const startTime = performance.now();
                     const updateCount = (timestamp) => {
                         const elapsedTime = timestamp - startTime;
                         const progress = Math.min(elapsedTime / duration, 1);
                         const currentValue = progress * targetValue;

                         // Remove the currency symbol (₱) from the formatter and add it manually
                         this.formattedCount = formatter.format(currentValue).replace(/[^\d.,]/g, '');
                         this.formattedCount = '₱' + this.formattedCount;

                         if (progress < 1) {
                             requestAnimationFrame(updateCount);
                         } else {
                             this.formattedCount = '₱' + formatter.format(targetValue).replace(/[^\d.,]/g, '');
                         }
                     };

                     requestAnimationFrame(updateCount);
                 }
             }"
             @mouseenter="showTooltip = true"
             @mouseleave="showTooltip = false"
             x-init="setTimeout(() => animateCount(), 900)">
            <div class="flex items-center justify-between">
                <div class="text-sm font-medium text-gray-500">Total Disbursed</div>
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-green-100 group-hover:bg-green-200 transition-colors duration-300">
                    <svg class="w-4 h-4 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-bold text-gray-900 group-hover:text-green-800 transition-colors duration-300" x-text="formattedCount">₱0.00</div>
                <div class="mt-1 text-xs text-gray-400">Funds released to scholars</div>
            </div>
            <div x-show="showTooltip" class="tooltip-popup" x-cloak>
                Total funds disbursed to scholars. Click to view financial reports and disbursement history.
            </div>
        </div>

        <!-- Total Scholars -->
        <div class="bg-white rounded-lg shadow p-4 transition-all duration-300 hover:shadow-lg hover:bg-red-50 cursor-pointer group relative"
             x-data="{ countLoaded: false, count: 0, showTooltip: false }"
             @mouseenter="showTooltip = true"
             @mouseleave="showTooltip = false"
             x-init="setTimeout(() => {
                 countLoaded = true;
                 const targetValue = {{ $totalScholars ?? 0 }};
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
                <div class="text-sm font-medium text-gray-500">Total Scholars</div>
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-red-100 group-hover:bg-red-200 transition-colors duration-300">
                    <svg class="w-4 h-4 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-bold text-gray-900 group-hover:text-red-800 transition-colors duration-300" x-text="count">0</div>
                <div class="mt-1 text-xs text-gray-400">Total scholars in the system</div>
            </div>
            <div x-show="showTooltip" class="tooltip-popup" x-cloak>
                Total number of scholars in the CLSU-ERDT Agricultural and Biosystems Engineering program. Click to view details.
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Section -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <!-- Recent Fund Requests -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="flex justify-between items-center px-4 py-3 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Recent Fund Requests</h3>
            <a href="{{ route('admin.fund-requests.index') }}" class="text-red-700 hover:text-red-800 text-xs flex items-center transition-colors duration-200">
                View all
                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>

        <div class="p-4">
            @if(count($recentFundRequests) > 0)
                <div class="space-y-4">
                    @foreach($recentFundRequests as $index => $request)
                        <div class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-3">
                                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-700">
                                        <span class="text-sm font-medium">{{ substr($request->scholarProfile && $request->scholarProfile->user ? $request->scholarProfile->user->name : 'U', 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ $request->scholarProfile && $request->scholarProfile->user ? $request->scholarProfile->user->name : 'Unknown Scholar' }}</h4>
                                            <p class="text-xs text-gray-500 mt-1">₱{{ number_format($request->amount, 2) }} • {{ $request->created_at->format('M d, Y') }}</p>
                                            <p class="text-xs text-gray-400 mt-1 line-clamp-1">{{ $request->purpose }}</p>
                                        </div>
                                        <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full
                                            @if($request->status === 'Under Review') bg-yellow-100 text-yellow-800
                                            @elseif($request->status === 'Approved') bg-green-100 text-green-800
                                            @elseif($request->status === 'Rejected') bg-red-100 text-red-800
                                            @elseif($request->status === 'Submitted') bg-blue-100 text-blue-800
                                            @elseif($request->status === 'Draft') bg-gray-100 text-gray-800
                                            @elseif($request->status === 'Disbursed') bg-indigo-100 text-indigo-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $request->status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6">
                    <div class="w-12 h-12 mx-auto bg-red-50 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500">No recent fund requests found</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="flex justify-between items-center px-4 py-3 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Recent Activity</h3>
            <a href="{{ route('admin.audit-logs.index') }}" class="text-red-700 hover:text-red-800 text-xs flex items-center transition-colors duration-200">
                View all
                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>

        <div class="p-4">
            @if(count($recentLogs ?? []) > 0)
                <div class="space-y-4">
                    @foreach($recentLogs as $log)
                        <div class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-3">
                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-700">
                                        @if($log->user)
                                            <span class="text-sm font-medium">{{ substr($log->user->name, 0, 1) }}</span>
                                        @else
                                            <span class="text-sm font-medium">S</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ $log->user ? $log->user->name : 'System' }}</h4>
                                            <p class="text-xs text-gray-500 mt-1">{{ $log->model_type }} @if($log->model_id) #{{ $log->model_id }} @endif</p>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full
                                                @if($log->action == 'create') bg-green-100 text-green-800
                                                @elseif($log->action == 'update') bg-yellow-100 text-yellow-800
                                                @elseif($log->action == 'delete') bg-red-100 text-red-800
                                                @elseif($log->action == 'login') bg-blue-100 text-blue-800
                                                @elseif($log->action == 'logout') bg-purple-100 text-purple-800
                                                @else bg-blue-100 text-blue-800 @endif">
                                                {{ ucfirst($log->action) }}
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">{{ $log->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6">
                    <div class="w-12 h-12 mx-auto bg-green-50 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500">No recent activity found</p>
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

    .program-tooltip {
        position: absolute;
        top: -25px;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(51, 65, 85, 0.95);
        color: white;
        text-align: center;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.7rem;
        z-index: 100;
        white-space: nowrap;
        pointer-events: none;
        animation: fadeIn 0.3s;
    }

    .program-tooltip::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -4px;
        border-width: 4px;
        border-style: solid;
        border-color: rgba(51, 65, 85, 0.95) transparent transparent transparent;
    }
</style>
@endsection
@endsection
