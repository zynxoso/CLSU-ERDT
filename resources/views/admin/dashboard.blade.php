@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 100)" class="space-y-8">
<!-- Welcome Section -->
<div class="mb-8 bg-white rounded-lg p-6 shadow-sm hover:shadow-md transition-all duration-300"
     :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-4'"
     class="transition-all duration-500 ease-out transform">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="text-sm text-gray-500">{{ now()->format('l, F j, Y') }}</p>
        </div>

    </div>
</div>

<!-- Stats Grid -->
<div class="mb-8"
     :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
     class="transition-all duration-500 delay-200 ease-out transform">
    <div class="flex justify-between items-center mb-4 border-b pb-2 border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Dashboard Overview</h2>
        <div x-data="{ showHelp: false }" class="relative">
            <button @mouseenter="showHelp = true" @mouseleave="showHelp = false" class="text-gray-400 hover:text-gray-600 focus:outline-none text-xs flex items-center">
                <span class="mr-1">Need help?</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>
            <div x-show="showHelp" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="fixed top-auto right-auto w-64 bg-white rounded-lg shadow-xl p-3 text-sm text-gray-600 z-[100]" style="position: absolute;" x-cloak>
                <p>These cards show key metrics at a glance. Click on any card to view more detailed information.</p>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Scholars -->
        <div class="bg-white rounded-lg shadow p-6 transition-all duration-300 hover:shadow-lg hover:bg-blue-50 cursor-pointer group relative"
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
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-50 mb-4 group-hover:bg-blue-100 transition-colors duration-300 transform group-hover:scale-110">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="text-2xl font-bold text-gray-900 mb-1 group-hover:text-blue-700 transition-colors duration-300" x-text="count">0</div>
            <div class="text-sm text-gray-500 group-hover:text-blue-600 transition-colors duration-300">Total Scholars</div>
            <div x-show="showTooltip" class="tooltip-popup" x-cloak>
                Total number of scholars enrolled in the system. Click to view the complete scholars list.
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="bg-white rounded-lg shadow p-6 transition-all duration-300 hover:shadow-lg hover:bg-yellow-50 cursor-pointer group relative"
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
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-yellow-50 mb-4 group-hover:bg-yellow-100 transition-colors duration-300 transform group-hover:scale-110">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-2xl font-bold text-gray-900 mb-1 group-hover:text-yellow-700 transition-colors duration-300" x-text="count">0</div>
            <div class="text-sm text-gray-500 group-hover:text-yellow-600 transition-colors duration-300">Pending Requests</div>
            <div x-show="showTooltip" class="tooltip-popup" x-cloak>
                Fund requests awaiting your review. Click to view and process these pending requests.
            </div>
        </div>

        <!-- Total Disbursed -->
        <div class="bg-white rounded-lg shadow p-6 transition-all duration-300 hover:shadow-lg hover:bg-green-50 cursor-pointer group relative"
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
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-green-50 mb-4 group-hover:bg-green-100 transition-colors duration-300 transform group-hover:scale-110">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="text-2xl font-bold text-gray-900 mb-1 group-hover:text-green-700 transition-colors duration-300" x-text="formattedCount">₱0.00</div>
            <div class="text-sm text-gray-500 group-hover:text-green-600 transition-colors duration-300">Total Disbursed</div>
            <div x-show="showTooltip" class="tooltip-popup" x-cloak>
                Total funds disbursed to scholars. Click to view financial reports and disbursement history.
            </div>
        </div>

        <!-- Completion Rate -->
        <div class="bg-white rounded-lg shadow p-6 transition-all duration-300 hover:shadow-lg hover:bg-indigo-50 cursor-pointer group relative"
             x-data="{ countLoaded: false, count: 0, showTooltip: false }"
             @mouseenter="showTooltip = true"
             @mouseleave="showTooltip = false"
             x-init="setTimeout(() => { 
                 countLoaded = true;
                 const targetValue = {{ $completionRate }};
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
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-indigo-50 mb-4 group-hover:bg-indigo-100 transition-colors duration-300 transform group-hover:scale-110">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <div class="text-2xl font-bold text-gray-900 mb-1 group-hover:text-indigo-700 transition-colors duration-300" x-text="count + '%'">0%</div>
            <div class="text-sm text-gray-500 group-hover:text-indigo-600 transition-colors duration-300">Completion Rate</div>
            <div x-show="showTooltip" class="tooltip-popup" x-cloak>
                Percentage of scholars successfully completing their program. Click to view detailed analytics.
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Section -->
<div class="mb-6"
     :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
     class="transition-all duration-500 delay-400 ease-out transform">
    <div class="flex justify-between items-center mb-4 border-b pb-2 border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Recent Activity</h2>
        <div x-data="{ showHelp: false }" class="relative">
            <button @mouseenter="showHelp = true" @mouseleave="showHelp = false" class="text-gray-400 hover:text-gray-600 focus:outline-none text-xs flex items-center">
                <span class="mr-1">Quick tips</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>
            <div x-show="showHelp" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="fixed top-auto right-auto mt-2 w-72 bg-white rounded-lg shadow-xl p-3 text-sm text-gray-600 z-[100]" style="position: absolute;" x-cloak>
                <p>This section shows the most recent fund requests and document uploads. Click on any item to view more details or take action.</p>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Fund Requests -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-all duration-300" x-data="{ showTip: false }">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Recent Fund Requests
                </h3>
                <div class="relative">
                    <button @mouseenter="showTip = true" @mouseleave="showTip = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                    <div x-show="showTip" class="tooltip-popup" x-cloak>
                        Click on a request to review details and approve/reject
                    </div>
                </div>
            </div>
            @if(count($recentFundRequests) > 0)
                <div class="space-y-4" x-data="{ shownItems: 0, showStatusLegend: false }" x-init="() => { 
                    for (let i = 0; i < {{ count($recentFundRequests) }}; i++) {
                        setTimeout(() => { shownItems++ }, 200 + (i * 100));
                    }
                }">
                    <div class="relative">
                        
                        <div x-show="showStatusLegend" class="fixed top-auto right-auto transform bg-white p-3 shadow-lg rounded text-xs z-[100] w-48" style="position: absolute;" x-cloak>
                            <p class="font-semibold mb-1">Status Legend:</p>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 rounded-full bg-yellow-100 mr-1"></span>
                                    <span>Under Review</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 rounded-full bg-green-100 mr-1"></span>
                                    <span>Approved</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 rounded-full bg-red-100 mr-1"></span>
                                    <span>Rejected</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 rounded-full bg-blue-100 mr-1"></span>
                                    <span>Submitted</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach($recentFundRequests as $index => $request)
                        <a href="{{ route('admin.fund-requests.show', $request->id) }}" class="block relative"
                           x-show="shownItems > {{ $index }}"
                           x-transition:enter="transition ease-out duration-300"
                           x-transition:enter-start="opacity-0 transform -translate-x-4"
                           x-transition:enter-end="opacity-100 transform translate-x-0">
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all duration-200 hover:shadow-md transform hover:scale-[1.01]">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $request->scholarProfile && $request->scholarProfile->user ? $request->scholarProfile->user->name : 'Unknown Scholar' }}</h4>
                                    <p class="text-sm text-gray-500">₱{{ number_format($request->amount, 2) }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $request->purpose }}</p>
                                </div>
                                <span class="px-3 py-1 text-xs font-medium rounded-full transition-all duration-300 transform hover:scale-110
                                    @if($request->status === 'Under Review') bg-yellow-100 text-yellow-800 animate-pulse
                                    @elseif($request->status === 'Approved') bg-green-100 text-green-800
                                    @elseif($request->status === 'Rejected') bg-red-100 text-red-800
                                    @elseif($request->status === 'Submitted') bg-blue-100 text-blue-800
                                    @elseif($request->status === 'Draft') bg-gray-100 text-gray-800
                                    @elseif($request->status === 'Disbursed') bg-indigo-100 text-indigo-800
                                    @else bg-gray-100 text-gray-800 @endif
                                    hover:shadow-md">
                                    {{ $request->status }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm">No recent fund requests.</p>
            @endif
            <div class="mt-4 text-right">
                <a href="{{ route('admin.fund-requests.index') }}" class="text-blue-600 hover:text-blue-800 text-sm flex items-center justify-end">
                    View all fund requests
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Recent Documents -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-all duration-300" x-data="{ showTip: false }">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-6-8h6M5 8h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V10a2 2 0 012-2z" />
                    </svg>
                    Recent Documents
                </h3>
                <div class="relative">
                    <button @mouseenter="showTip = true" @mouseleave="showTip = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                    <div x-show="showTip" class="tooltip-popup" x-cloak>
                        Click on a document to verify or review submission
                    </div>
                </div>
            </div>
            @if(count($recentDocuments) > 0)
                <div class="space-y-4" x-data="{ showStatusLegend: false }">
                    <div class="relative mb-2">
                        <div x-show="showStatusLegend" class="fixed top-auto right-auto transform bg-white p-3 shadow-lg rounded text-xs z-[100] w-48" style="position: absolute;" x-cloak>
                            <p class="font-semibold mb-1">Status Legend:</p>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 rounded-full bg-blue-100 mr-1"></span>
                                    <span>Uploaded</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 rounded-full bg-green-100 mr-1"></span>
                                    <span>Verified</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 rounded-full bg-red-100 mr-1"></span>
                                    <span>Rejected</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 rounded-full bg-yellow-100 mr-1"></span>
                                    <span>Pending</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach($recentDocuments as $index => $document)
                        <a href="{{ route('admin.documents.show', $document->id) }}" 
                           class="block transform transition-all duration-300 opacity-0"
                           x-data="{ show: false }"
                           x-init="setTimeout(() => { show = true }, {{ 200 + ($index * 150) }})"
                           x-bind:class="{ 'opacity-100 translate-x-0': show, 'opacity-0 -translate-x-4': !show }">
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all duration-200 hover:shadow-md transform hover:scale-[1.01]">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $document->title }}</h4>
                                    <p class="text-sm text-gray-500">{{ $document->scholarProfile && $document->scholarProfile->user ? $document->scholarProfile->user->name : 'Unknown Scholar' }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $document->created_at->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    @if($document->status == 'Uploaded')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors duration-200 transform hover:scale-110">{{ $document->status }}</span>
                                    @elseif($document->status == 'Verified')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 hover:bg-green-200 transition-colors duration-200 transform hover:scale-110">{{ $document->status }}</span>
                                    @elseif($document->status == 'Rejected')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 hover:bg-red-200 transition-colors duration-200 transform hover:scale-110">{{ $document->status }}</span>
                                    @elseif($document->status == 'Pending')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 hover:bg-yellow-200 transition-colors duration-200 animate-pulse transform hover:scale-110">{{ $document->status }}</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 hover:bg-gray-200 transition-colors duration-200 transform hover:scale-110">{{ $document->status }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm">No recent documents.</p>
            @endif
            <div class="mt-4 text-right">
                <a href="{{ route('admin.documents.index') }}" class="text-blue-600 hover:text-blue-800 text-sm flex items-center justify-end">
                    View all documents
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Scholars Overview -->
<div class="mb-6"
     :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
     class="transition-all duration-500 delay-600 ease-out transform">
    <div class="flex justify-between items-center mb-4 border-b pb-2 border-gray-200">
        <h2 class="text-xl font-semibold text-gray-800">Scholars Overview</h2>
        <div x-data="{ showHelp: false }" class="relative">
            <button @mouseenter="showHelp = true" @mouseleave="showHelp = false" class="text-gray-400 hover:text-gray-600 focus:outline-none text-xs flex items-center">
                <span class="mr-1">Understanding the data</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>
            <div x-show="showHelp" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="fixed top-auto right-auto mt-2 w-72 bg-white rounded-lg shadow-xl p-3 text-sm text-gray-600 z-[100]" style="position: absolute;" x-cloak>
                <p class="mb-2">This section provides an overview of scholar demographics and program distribution.</p>
                <p>Use this data to track program completion rates and identify trends in scholar enrollment.</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-all duration-300">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <div class="bg-purple-50 rounded-lg p-4 hover:shadow-md transition-all duration-300 transform hover:scale-[1.02] relative"
                 x-data="{ countLoaded: false, count: 0, showTooltip: false }"
                 @mouseenter="showTooltip = true"
                 @mouseleave="showTooltip = false"
                 x-init="setTimeout(() => { 
                     countLoaded = true;
                     const targetValue = {{ $activeScholars ?? $totalScholars }};
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
                 }, 1300)">
                <h3 class="text-lg font-semibold text-purple-700 mb-2">Active Scholars</h3>
                <div class="text-3xl font-bold text-purple-800" x-text="count">0</div>
                <p class="text-sm text-purple-600 mt-1">Currently enrolled in programs</p>
                <div x-show="showTooltip" class="tooltip-popup" x-cloak>
                    Total number of scholars currently active in the system
                </div>
            </div>

            <div class="bg-teal-50 rounded-lg p-4 hover:shadow-md transition-all duration-300 transform hover:scale-[1.02] relative"
                 x-data="{ countLoaded: false, count: 0, showTooltip: false }"
                 @mouseenter="showTooltip = true"
                 @mouseleave="showTooltip = false"
                 x-init="setTimeout(() => { 
                     countLoaded = true;
                     const targetValue = {{ $completingThisYear ?? 0 }};
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
                 }, 1500)">
                <h3 class="text-lg font-semibold text-teal-700 mb-2">Completing This Year</h3>
                <div class="text-3xl font-bold text-teal-800" x-text="count">0</div>
                <p class="text-sm text-teal-600 mt-1">Expected to graduate this year</p>
                <div x-show="showTooltip" class="tooltip-popup" x-cloak>
                    Scholars projected to complete their programs by the end of this calendar year
                </div>
            </div>

            <div class="bg-amber-50 rounded-lg p-4 hover:shadow-md transition-all duration-300 transform hover:scale-[1.02] relative"
                 x-data="{ countLoaded: false, count: 0, showTooltip: false }"
                 @mouseenter="showTooltip = true"
                 @mouseleave="showTooltip = false"
                 x-init="setTimeout(() => { 
                     countLoaded = true;
                     const targetValue = {{ $newScholars ?? 0 }};
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
                 }, 1700)">
                <h3 class="text-lg font-semibold text-amber-700 mb-2">New Enrollments</h3>
                <div class="text-3xl font-bold text-amber-800" x-text="count">0</div>
                <p class="text-sm text-amber-600 mt-1">Joined in the last 30 days</p>
                <div x-show="showTooltip" class="tooltip-popup" x-cloak>
                    New scholars who joined the program within the last 30 days
                </div>
            </div>
        </div>

        <div class="mt-4"
             x-data="{ shown: false, showHelp: false }"
             x-init="setTimeout(() => shown = true, 1900)"
             :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
             class="transition-all duration-500 ease-out transform">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-semibold text-gray-800">Scholar Distribution</h3>
                <div class="relative">
                    <button @mouseenter="showHelp = true" @mouseleave="showHelp = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                    <div x-show="showHelp" class="fixed top-auto right-auto bg-white p-3 rounded-lg shadow-lg z-[100] w-64 text-sm text-gray-600" style="position: absolute;" x-cloak>
                        <p>This chart shows the distribution of scholars across different programs. Hover over each program segment to see exact percentages.</p>
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                <!-- Programs Distribution -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">By Program</h4>
                    @if(count($programCounts ?? []) > 0)
                        <div class="h-8 bg-gray-200 rounded-full overflow-hidden">
                            <div class="flex h-full">
                                @php $colors = ['bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-red-500', 'bg-purple-500', 'bg-indigo-500']; @endphp
                                @foreach($programCounts as $index => $program)
                                    @if($index < 6)
                                        <div x-data="{ width: 0, showPercent: false }"
                                             x-init="setTimeout(() => { width = {{ $program->percentage }} }, 2000 + ({{ $index }} * 200))"
                                             :style="'width: ' + width + '%'"
                                             @mouseenter="showPercent = true"
                                             @mouseleave="showPercent = false"
                                             class="{{ $colors[$index % count($colors)] }} h-full transition-all duration-1000 ease-out relative"
                                             style="width: 0%">
                                            <div x-show="showPercent" class="program-tooltip" x-cloak>
                                                {{ $program->program }}: {{ $program->percentage }}%
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="flex flex-wrap text-xs mt-2 text-gray-600 justify-between">
                            @foreach($programCounts as $index => $program)
                                @if($index < 6)
                                    <span class="transition-all duration-500 ease-out"
                                          x-data="{ shown: false }"
                                          x-init="setTimeout(() => shown = true, 2200 + ({{ $index }} * 200))"
                                          :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-2'">
                                        {{ $program->program }} ({{ $program->percentage }}%)
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3 text-gray-500">
                            No program data available
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-6 text-center"
             x-data="{ shown: false }"
             x-init="setTimeout(() => shown = true, 2300)"
             :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
             class="transition-all duration-500 ease-out transform">
            <a href="{{ route('admin.scholars.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition-all ease-in-out duration-150 hover:scale-105 active:scale-95 transform relative"
               x-data="{ showTooltip: false }"
               @mouseenter="showTooltip = true"
               @mouseleave="showTooltip = false">
                View All Scholars
                <svg class="ml-2 w-4 h-4 transition-transform duration-300 transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
                <div x-show="showTooltip" class="tooltip-popup" x-cloak>
                    Access the complete list of scholars with detailed profiles
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Add the audit logs widget to the dashboard -->
<div class="col-span-12 xl:col-span-4">
    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-800">Recent Activity</h3>
            <a href="{{ route('admin.audit-logs.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="p-4">
            @if(count($recentLogs ?? []) > 0)
                <div class="overflow-hidden">
                    <ul class="divide-y divide-gray-200">
                        @foreach($recentLogs as $log)
                            <li class="py-3 px-2 hover:bg-gray-50 rounded-md transition-colors duration-150">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
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
                                            {{ $log->entity_type }}
                                            @if($log->entity_id)
                                                <span class="text-gray-500">#{{ $log->entity_id }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="py-6 text-center">
                    <div class="w-12 h-12 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-history text-xl text-gray-500"></i>
                    </div>
                    <p class="text-gray-500">No recent activity found</p>
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
