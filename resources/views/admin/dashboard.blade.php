@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Password Change Notice Modal -->
@if((auth()->user()->is_default_password || auth()->user()->must_change_password) && !session('password_warning_dismissed'))
<div x-data="{ showModal: true }"
     x-show="showModal"
     class="fixed inset-0 z-50 overflow-y-auto"
     aria-labelledby="modal-title"
     role="dialog"
     aria-modal="true">
    <!-- Background overlay with CLSU theme -->
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <!-- Enhanced modal design with CLSU green theme -->
        <div x-show="showModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 border border-gray-100">
            <div class="sm:flex sm:items-start">
                <!-- Updated icon with CLSU green -->
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-50 sm:mx-0 sm:h-10 sm:w-10">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <!-- Enhanced text content with CLSU styling -->
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-semibold text-gray-900" id="modal-title">
                        @if(auth()->user()->must_change_password)
                            Password Change Required
                        @else
                            Security Notice
                        @endif
                    </h3>
                    <div class="mt-3">
                        <p class="text-sm text-gray-600">
                            @if(auth()->user()->must_change_password)
                                Your password has expired and must be changed immediately for security reasons.
                            @else
                                For enhanced security of your account, we recommend changing your default password to a strong, unique password.
                            @endif
                        </p>
                        <div class="mt-4 p-3 bg-green-50 rounded-lg border border-green-100">
                            <p class="text-sm text-green-700">
                                <span class="font-medium">Pro tip:</span> Use a combination of letters, numbers, and symbols to create a strong password that you'll remember.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Improved button design with CLSU green -->
            <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse gap-3">
                <a href="{{ route('admin.settings') }}"
                   class="w-full inline-flex justify-center items-center rounded-lg border border-transparent px-4 py-2.5 bg-green-600 text-base font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:w-auto sm:text-sm transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Change Password
                </a>
                <button type="button"
                        @click="showModal = false"
                        class="mt-3 w-full inline-flex justify-center items-center rounded-lg border border-gray-200 px-4 py-2.5 bg-white text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    I'll do it later
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<div x-data="{ shown: true }" class="space-y-6">
<!-- Welcome Section -->
<div class="mb-6 bg-white rounded-lg p-6 shadow-sm" style="border: 1px solid #E0E0E0;">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold" style="color: #424242;">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="text-sm" style="color: #757575;">{{ now()->format('l, F j, Y') }}</p>
        </div>
        <div class="hidden md:block">
            <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: #E8F5E8; border: 2px solid #2E7D32;">
                <span class="text-lg font-bold" style="color: #2E7D32;">C</span>
            </div>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="mb-6">
    <div class="flex justify-between items-center mb-3">
        <h2 class="text-lg font-semibold" style="color: #424242;">Dashboard Overview</h2>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <!-- Active Scholars -->
        <div class="bg-white rounded-lg shadow p-4 cursor-pointer relative border" style="border-color: #E0E0E0;">
            <div class="flex items-center justify-between">
                <div class="text-sm font-medium" style="color: #757575;">Active Scholars</div>
                <div class="flex items-center justify-center w-8 h-8 rounded-full" style="background-color: #E8F5E8;">
                    <svg class="w-4 h-4" style="color: #2E7D32;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-bold" style="color: #424242;">{{ $totalScholars }}</div>
                <div class="mt-1 text-xs" style="color: #9E9E9E;">Currently enrolled students</div>
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="bg-white rounded-lg shadow p-4 cursor-pointer relative border" style="border-color: #E0E0E0;">
            <div class="flex items-center justify-between">
                <div class="text-sm font-medium" style="color: #757575;">Pending Requests</div>
                <div class="flex items-center justify-center w-8 h-8 rounded-full" style="background-color: #FFF8E1;">
                    <svg class="w-4 h-4" style="color: #FFCA28;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-bold" style="color: #FFCA28;">{{ $pendingRequests }}</div>
                <div class="mt-1 text-xs" style="color: #9E9E9E;">Awaiting your review</div>
            </div>
        </div>

        <!-- Total Disbursed -->
        <div class="bg-white rounded-lg shadow p-4 cursor-pointer relative border" style="border-color: #E0E0E0;">
            <div class="flex items-center justify-between">
                <div class="text-sm font-medium" style="color: #757575;">Total Disbursed</div>
                <div class="flex items-center justify-center w-8 h-8 rounded-full" style="background-color: #E8F5E8;">
                    <svg class="w-4 h-4" style="color: #2E7D32;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-bold" style="color: #2E7D32;">₱{{ number_format($totalDisbursed, 2) }}</div>
                <div class="mt-1 text-xs" style="color: #9E9E9E;">Funds released to scholars</div>
            </div>
        </div>

        <!-- Total Scholars -->
        <div class="bg-white rounded-lg shadow p-4 cursor-pointer relative border" style="border-color: #E0E0E0;">
            <div class="flex items-center justify-between">
                <div class="text-sm font-medium" style="color: #757575;">Total Scholars</div>
                <div class="flex items-center justify-center w-8 h-8 rounded-full" style="background-color: #E8F5E8;">
                    <svg class="w-4 h-4" style="color: #2E7D32;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-3xl font-bold" style="color: #424242;">{{ $totalScholars ?? 0 }}</div>
                <div class="mt-1 text-xs" style="color: #9E9E9E;">Total scholars in the system</div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Notifications -->
<div class="bg-white rounded-lg shadow-md border overflow-hidden" style="border-color: #E0E0E0;">
    <div class="flex justify-between items-center px-6 py-4 border-b" style="border-color: #E0E0E0;">
        <h3 class="font-semibold flex items-center" style="color: #424242;">
            <svg class="w-5 h-5 mr-2" style="color: #F8BBD0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5V3h0v14z" />
            </svg>
            Recent Notifications
        </h3>
        <a href="{{ route('admin.notifications.index') }}" class="text-sm inline-flex items-center transition-colors duration-200 font-medium hover:opacity-80" style="color: #2E7D32;">
            View all
            <svg class="w-4 h-4 ml-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
            </svg>
        </a>
    </div>

    <div class="p-6">
        @if(count($recentNotifications ?? []) > 0)
            <div class="space-y-4">
                @foreach($recentNotifications as $notification)
                    @php
                        $notificationData = $notification->data;
                        $isNewFundRequest = $notification->type === 'App\\Notifications\\NewFundRequestSubmitted';
                        $isNewManuscript = $notification->type === 'App\\Notifications\\NewManuscriptSubmitted';
                    @endphp
                    <div class="border-b pb-4 last:border-0 last:pb-0" style="border-color: #F5F5F5;">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-3">
                                @if($isNewFundRequest)
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center border" style="background-color: #FFF8E1; border-color: #FFCA28;">
                                        <svg class="w-5 h-5" style="color: #FFCA28; display: block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                @elseif($isNewManuscript)
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center border" style="background-color: #FCE4EC; border-color: #F8BBD0;">
                                        <svg class="w-5 h-5" style="color: #F8BBD0; display: block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center border" style="background-color: #F5F5F5; border-color: #E0E0E0;">
                                        <svg class="w-5 h-5" style="color: #757575; display: block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5V3h0v14z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-sm font-medium" style="color: #424242;">
                                            @if($isNewFundRequest)
                                                New Fund Request Submitted
                                            @elseif($isNewManuscript)
                                                New Manuscript Submitted
                                            @else
                                                Notification
                                            @endif
                                        </h4>
                                        <p class="text-sm mt-1" style="color: #616161;">
                                            @if($isNewFundRequest && isset($notificationData['scholar_name']))
                                                By <span class="font-medium" style="color: #2E7D32;">{{ $notificationData['scholar_name'] }}</span> •
                                                <span class="font-medium" style="color: #2E7D32;">₱{{ number_format($notificationData['amount'], 2) }}</span>
                                            @elseif($isNewManuscript && isset($notificationData['scholar_name']))
                                                By <span class="font-medium" style="color: #2E7D32;">{{ $notificationData['scholar_name'] }}</span> •
                                                <span style="color: #424242;">{{ Str::limit($notificationData['title'] ?? 'Manuscript', 40) }}</span>
                                            @else
                                                New submission received
                                            @endif
                                        </p>
                                    </div>
                                    @if(!$notification->read_at)
                                        <div class="w-2 h-2 rounded-full ml-2" style="background-color: #F8BBD0;"></div>
                                    @endif
                                </div>
                                <div class="flex justify-between items-center mt-2">
                                    <p class="text-xs" style="color: #9E9E9E;">{{ $notification->created_at->diffForHumans() }}</p>
                                    @if(isset($notificationData['action_url']))
                                        <a href="{{ $notificationData['action_url'] }}" class="text-xs font-medium transition-colors duration-200 hover:opacity-80" style="color: #2E7D32;">
                                            Review →
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4 border" style="background-color: #FCE4EC; border-color: #F8BBD0;">
                    <svg class="w-8 h-8" style="color: #F8BBD0; display: block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5V3h0v14z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium mb-2" style="color: #424242;">No recent notifications</h3>
                <p style="color: #616161;">You'll receive notifications when scholars submit fund requests and manuscripts.</p>
            </div>
        @endif
    </div>
</div>

<!-- Recent Activity Section -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <!-- Recent Fund Requests -->
    <div class="bg-white rounded-lg shadow overflow-hidden border" style="border-color: #E0E0E0;">
        <div class="flex justify-between items-center px-4 py-3 border-b" style="border-color: #F5F5F5;">
            <h3 class="font-semibold" style="color: #424242;">Recent Fund Requests</h3>
            <a href="{{ route('admin.fund-requests.index') }}" class="text-xs flex items-center transition-colors duration-200 hover:opacity-80" style="color: #2E7D32;">
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
                        <div class="border-b pb-4 last:border-0 last:pb-0" style="border-color: #F5F5F5;">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background-color: #E8F5E8; color: #2E7D32;">
                                        <span class="text-sm font-medium">{{ substr($request->scholarProfile && $request->scholarProfile->user ? $request->scholarProfile->user->name : 'U', 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="text-sm font-medium" style="color: #424242;">{{ $request->scholarProfile && $request->scholarProfile->user ? $request->scholarProfile->user->name : 'Unknown Scholar' }}</h4>
                                            <p class="text-xs mt-1" style="color: #9E9E9E;">₱{{ number_format($request->amount, 2) }} • {{ $request->created_at->format('M d, Y') }}</p>
                                            <p class="text-xs mt-1 line-clamp-1" style="color: #757575;">{{ $request->purpose }}</p>
                                        </div>
                                        <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full
                                            @if($request->status === 'Under Review') text-white @elseif($request->status === 'Approved') text-white @elseif($request->status === 'Rejected') text-white @elseif($request->status === 'Submitted') text-white @elseif($request->status === 'Draft') text-white @elseif($request->status === 'Disbursed') text-white @else text-white @endif"
                                            style="@if($request->status === 'Under Review') background-color: #FFCA28; @elseif($request->status === 'Approved') background-color: #2E7D32; @elseif($request->status === 'Rejected') background-color: #D32F2F; @elseif($request->status === 'Submitted') background-color: #1976D2; @elseif($request->status === 'Draft') background-color: #757575; @elseif($request->status === 'Disbursed') background-color: #7B1FA2; @else background-color: #757575; @endif">
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
                    <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center mb-3" style="background-color: #FFF8E1;">
                        <svg class="w-6 h-6" style="color: #FFCA28;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <p class="text-sm" style="color: #9E9E9E;">No recent fund requests found</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow overflow-hidden border" style="border-color: #E0E0E0;">
        <div class="flex justify-between items-center px-4 py-3 border-b" style="border-color: #F5F5F5;">
            <h3 class="font-semibold" style="color: #424242;">Recent Activity</h3>
            <a href="{{ route('admin.audit-logs.index') }}" class="text-xs flex items-center transition-colors duration-200 hover:opacity-80" style="color: #2E7D32;">
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
                        <div class="border-b pb-4 last:border-0 last:pb-0" style="border-color: #F5F5F5;">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background-color: #E8F5E8; color: #2E7D32;">
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
                                            <h4 class="text-sm font-medium" style="color: #424242;">{{ $log->user ? $log->user->name : 'System' }}</h4>
                                            <p class="text-xs mt-1" style="color: #9E9E9E;">{{ $log->model_type }} @if($log->model_id) #{{ $log->model_id }} @endif</p>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full text-white"
                                                style="@if($log->action == 'create') background-color: #2E7D32; @elseif($log->action == 'update') background-color: #FFCA28; @elseif($log->action == 'delete') background-color: #D32F2F; @elseif($log->action == 'login') background-color: #1976D2; @elseif($log->action == 'logout') background-color: #7B1FA2; @else background-color: #1976D2; @endif">
                                                {{ ucfirst($log->action) }}
                                            </span>
                                        </div>
                                    </div>
                                    <p class="text-xs mt-1" style="color: #757575;">{{ $log->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6">
                    <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center mb-3" style="background-color: #E8F5E8;">
                        <svg class="w-6 h-6" style="color: #2E7D32;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-sm" style="color: #9E9E9E;">No recent activity found</p>
                </div>
            @endif
        </div>
    </div>
</div>
</div>

@section('styles')
<style>
    /* Global Typography */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 15px;
        line-height: 1.6;
        color: #424242;
        background-color: #FAFAFA !important;
    }

    [x-cloak] { display: none !important; }

    /* Professional text truncation */
    .line-clamp-1 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
    }
</style>
@endsection
@endsection
