@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <!-- Password Change Notice Modal -->
    @if (
        (auth()->user()->is_default_password || auth()->user()->must_change_password) &&
            !session('password_warning_dismissed'))
        <div x-data="{ showModal: true }" x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <!-- Background overlay with CLSU theme -->
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-50 backdrop-blur-sm transition-opacity" aria-hidden="true">
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <!-- Enhanced modal design with CLSU green theme -->
                <div x-show="showModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 border border-gray-100">
                    <div class="sm:flex sm:items-start">
                        <!-- Updated icon with consistent green theme -->
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10"
                            style="background-color: rgba(76, 175, 80, 0.1);">
                            <svg class="h-6 w-6" style="color: #4CAF50;" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <!-- Enhanced text content with CLSU styling -->
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-semibold text-gray-900" id="modal-title">
                                @if (auth()->user()->must_change_password)
                                    Password Change Required
                                @else
                                    Security Notice
                                @endif
                            </h3>
                            <div class="mt-3">
                                <p class="text-sm text-gray-600">
                                    @if (auth()->user()->must_change_password)
                                        Your password has expired and must be changed immediately for security reasons.
                                    @else
                                        For enhanced security of your account, we recommend changing your default password
                                        to a strong, unique password.
                                    @endif
                                </p>
                                <div class="mt-4 p-3 rounded-lg"
                                    style="background-color: rgba(76, 175, 80, 0.1); border: 1px solid #4CAF50;">
                                    <p class="text-sm" style="color: #4CAF50;">
                                        <span class="font-medium">Pro tip:</span> Use a combination of letters, numbers, and
                                        symbols to create a strong password that you'll remember.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Improved button design with CLSU green -->
                    <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse gap-3">
                        <a href="{{ route('admin.settings') }}"
                            class="w-full inline-flex justify-center items-center rounded-lg border border-transparent px-4 py-2.5 text-base font-medium text-white shadow-sm sm:w-auto sm:text-sm transition-colors duration-200"
                            style="background-color: #4CAF50;" onmouseover="this.style.backgroundColor='#43A047'"
                            onmouseout="this.style.backgroundColor='#4CAF50'" <svg class="w-4 h-4 mr-2" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Change Password
                        </a>
                        <button type="button" @click="showModal = false"
                            class="mt-3 w-full inline-flex justify-center items-center rounded-lg px-4 py-2.5 bg-white text-base font-medium shadow-sm sm:mt-0 sm:w-auto sm:text-sm transition-colors duration-200"
                            style="border: 1px solid #E0E0E0; color: #757575;"
                            onmouseover="this.style.backgroundColor='#F5F5F5'"
                            onmouseout="this.style.backgroundColor='white'" <svg class="w-4 h-4 mr-2" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
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
                <div class="flex items-center space-x-4">
                    <!-- Unified Notification Dropdown -->
                    <div class="relative" x-data="{ open: false, notificationCount: {{ count($notifications->where('is_read', false)) }} }">
                        <button @click="open = !open"
                            class="relative p-2.5 rounded-full focus:outline-none transition-colors duration-200"
                            style="color: #757575;"
                            onmouseover="this.style.color='#424242'; this.style.backgroundColor='#F5F5F5'"
                            onmouseout="this.style.color='#757575'; this.style.backgroundColor='transparent'" <svg
                            class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span x-show="notificationCount > 0"
                                class="notification-count-badge absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[1.25rem] h-5 text-xs font-bold leading-none text-white rounded-full shadow-sm px-1"
                                style="background-color: #D32F2F;" x-text="notificationCount"></span>
                        </button>
                        <!-- Mobile-First Responsive Dropdown -->
                        <div x-show="open" @click.away="open = false"
                            class="notification-dropdown absolute right-0 mt-2 w-screen max-w-sm sm:max-w-md lg:max-w-lg xl:max-w-xl
                            sm:w-96 md:w-[400px] lg:w-[450px] xl:w-[500px]
                            bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden z-50
                            -mr-4 sm:mr-0"
                            style="display: none;">
                            <!-- Header - Responsive -->
                            <div class="py-3 px-4 sm:px-6 border-b flex justify-between items-center"
                                style="background: linear-gradient(to right, rgba(76, 175, 80, 0.05), rgba(76, 175, 80, 0.1)); border-color: #E0E0E0;">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" style="color: #4CAF50;" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <span class="text-base sm:text-lg font-bold" style="color: #424242;">All
                                        Notifications</span>
                                </div>
                                <a href="{{ route('admin.notifications.index') }}"
                                    class="text-xs sm:text-sm flex items-center font-medium transition-colors duration-200"
                                    style="color: #4CAF50;" onmouseover="this.style.color='#43A047'"
                                    onmouseout="this.style.color='#4CAF50'" <span class="hidden sm:inline">View All</span>
                                    <span class="sm:hidden">All</span>
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 ml-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                            <!-- Notification List - Responsive Scrolling -->
                            <div class="notification-scroll max-h-[60vh] sm:max-h-[500px] overflow-y-auto">
                                @if (count($notifications) > 0)
                                    @foreach ($notifications->take(8) as $notification)
                                        <div class="notification-item px-3 sm:px-4 py-3 border-b {{ !$notification->is_read ? 'border-l-4' : '' }}"
                                            style="border-color: #F5F5F5; {{ !$notification->is_read ? 'background-color: rgba(76, 175, 80, 0.05); border-left-color: #4CAF50;' : '' }}"
                                            x-data="{ expanded: false }">
                                            <div class="flex items-start space-x-2 sm:space-x-3">
                                                <!-- Icon - Responsive Size -->
                                                <div class="flex-shrink-0">
                                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center"
                                                        style="@if ($notification->type == 'fund_request') background-color: rgba(76, 175, 80, 0.1);
                                                        @elseif($notification->type == 'document') background-color: rgba(156, 39, 176, 0.1);
                                                        @elseif($notification->type == 'manuscript') background-color: rgba(63, 81, 181, 0.1);
                                                        @else background-color: rgba(117, 117, 117, 0.1); @endif">
                                                        @if ($notification->type == 'fund_request')
                                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" style="color: #4CAF50;"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                                                </path>
                                                            </svg>
                                                        @elseif($notification->type == 'document')
                                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" style="color: #9C27B0;"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                                </path>
                                                            </svg>
                                                        @elseif($notification->type == 'manuscript')
                                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" style="color: #3F51B5;"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                                </path>
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" style="color: #757575;"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                                                </path>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                </div>
                                                <!-- Content - Responsive Layout -->
                                                <div class="flex-1 min-w-0">
                                                    <div
                                                        class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1 sm:gap-2">
                                                        <div
                                                            class="flex flex-col sm:flex-row sm:items-center sm:space-x-2 space-y-1 sm:space-y-0">
                                                            <h4 class="notification-title text-sm font-medium truncate"
                                                                style="color: #424242;">{{ $notification->title }}</h4>
                                                            <!-- Category Badge - Responsive -->
                                                            <span
                                                                class="notification-badge inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium w-fit"
                                                                style="@if ($notification->type == 'fund_request') background-color: rgba(76, 175, 80, 0.1); color: #4CAF50;
                                                                 @elseif($notification->type == 'document') background-color: rgba(156, 39, 176, 0.1); color: #7B1FA2;
                                                                 @elseif($notification->type == 'manuscript') background-color: rgba(63, 81, 181, 0.1); color: #303F9F;
                                                                 @else background-color: rgba(117, 117, 117, 0.1); color: #424242; @endif">
                                                                @if ($notification->type == 'fund_request')
                                                                    Fund
                                                                @elseif($notification->type == 'document')
                                                                    Document
                                                                @elseif($notification->type == 'manuscript')
                                                                    Manuscript
                                                                @else
                                                                    General
                                                                @endif
                                                            </span>
                                                        </div>
                                                        @if (!$notification->is_read)
                                                            <div class="w-2 h-2 rounded-full flex-shrink-0 mt-1 sm:mt-0"
                                                                style="background-color: #4CAF50;"></div>
                                                        @endif
                                                    </div>
                                                    <!-- Message - Responsive Text -->
                                                    <p class="text-xs sm:text-sm mt-1 leading-relaxed"
                                                        style="color: #757575;">
                                                        {{ Str::limit($notification->message, 100) }}
                                                    </p>
                                                    <!-- Footer - Responsive Layout -->
                                                    <div
                                                        class="flex flex-col sm:flex-row sm:justify-between sm:items-center mt-2 gap-1 sm:gap-2">
                                                        <span class="text-xs"
                                                            style="color: #9E9E9E;">{{ $notification->created_at->diffForHumans() }}</span>
                                                        @if ($notification->link)
                                                            <a href="{{ $notification->link }}"
                                                                class="notification-link text-xs font-medium w-fit transition-colors duration-200"
                                                                style="color: #4CAF50;"
                                                                onmouseover="this.style.color='#43A047'"
                                                                onmouseout="this.style.color='#4CAF50'" <span
                                                                class="hidden sm:inline">View Details →</span>
                                                                <span class="sm:hidden">View →</span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <!-- Empty State - Responsive -->
                                    <div class="px-4 py-6 sm:py-8 text-center">
                                        <div class="notification-empty-icon w-12 h-12 sm:w-16 sm:h-16 mx-auto rounded-full flex items-center justify-center mb-3 sm:mb-4"
                                            style="background-color: #F5F5F5;">
                                            <svg class="w-6 h-6 sm:w-8 sm:h-8" style="color: #9E9E9E;" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                                </path>
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-medium mb-1" style="color: #424242;">No notifications</h3>
                                        <p class="notification-empty-text text-xs px-2" style="color: #9E9E9E;">You'll be
                                            notified about fund requests, documents, and manuscripts from scholars.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center"
                            style="background-color: rgba(76, 175, 80, 0.1); border: 2px solid #4CAF50;">
                            <span class="text-lg font-bold" style="color: #4CAF50;">C</span>
                        </div>
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
                        <div class="flex items-center justify-center w-8 h-8 rounded-full"
                            style="background-color: rgba(76, 175, 80, 0.1);">
                            <svg class="w-4 h-4" style="color: #4CAF50;" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
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
                        <div class="flex items-center justify-center w-8 h-8 rounded-full"
                            style="background-color: rgba(255, 202, 40, 0.1);">
                            <svg class="w-4 h-4" style="color: #FFCA28;" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                        <div class="flex items-center justify-center w-8 h-8 rounded-full"
                            style="background-color: rgba(76, 175, 80, 0.1);">
                            <svg class="w-4 h-4" style="color: #4CAF50;" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-3xl font-bold" style="color: #4CAF50;">₱{{ number_format($totalDisbursed, 2) }}
                        </div>
                        <div class="mt-1 text-xs" style="color: #9E9E9E;">Funds released to scholars</div>
                    </div>
                </div>

                <!-- Total Scholars -->
                <div class="bg-white rounded-lg shadow p-4 cursor-pointer relative border" style="border-color: #E0E0E0;">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium" style="color: #757575;">Total Scholars</div>
                        <div class="flex items-center justify-center w-8 h-8 rounded-full"
                            style="background-color: rgba(76, 175, 80, 0.1);">
                            <svg class="w-4 h-4" style="color: #4CAF50;" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
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

        <!-- Recent Activity Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- Recent Fund Requests -->
            <div class="bg-white rounded-lg shadow overflow-hidden border" style="border-color: #E0E0E0;">
                <div class="flex justify-between items-center px-4 py-3 border-b" style="border-color: #F5F5F5;">
                    <h3 class="font-semibold" style="color: #424242;">Recent Fund Requests</h3>
                    <a href="{{ route('admin.fund-requests.index') }}"
                        class="text-xs flex items-center transition-colors duration-200" style="color: #4CAF50;"
                        onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                        View all
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>

                <div class="p-4">
                    @if (count($recentFundRequests) > 0)
                        <div class="space-y-4">
                            @foreach ($recentFundRequests as $index => $request)
                                <div class="border-b pb-4 last:border-0 last:pb-0" style="border-color: #F5F5F5;">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-3">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center"
                                                style="background-color: rgba(76, 175, 80, 0.1); color: #4CAF50;">
                                                <span
                                                    class="text-sm font-medium">{{ substr($request->scholarProfile && $request->scholarProfile->user ? $request->scholarProfile->user->name : 'U', 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="text-sm font-medium" style="color: #424242;">
                                                        {{ $request->scholarProfile && $request->scholarProfile->user ? $request->scholarProfile->user->name : 'Unknown Scholar' }}
                                                    </h4>
                                                    <p class="text-xs mt-1" style="color: #9E9E9E;">
                                                        ₱{{ number_format($request->amount, 2) }} •
                                                        {{ $request->created_at->format('M d, Y') }}</p>
                                                    <p class="text-xs mt-1 line-clamp-1" style="color: #757575;">
                                                        {{ $request->purpose }}</p>
                                                </div>
                                                <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full"
                                                    style="@if ($request->status === 'Under Review') background-color: #FFCA28; color: #975A16; @elseif($request->status === 'Approved') background-color: #4CAF50; color: white; @elseif($request->status === 'Rejected') background-color: #D32F2F; color: white; @elseif($request->status === 'Submitted') background-color: #4A90E2; color: white; @elseif($request->status === 'Draft') background-color: #757575; color: white; @elseif($request->status === 'Disbursed') background-color: #9C27B0; color: white; @else background-color: #757575; color: white; @endif">
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
                            <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center mb-3"
                                style="background-color: rgba(255, 202, 40, 0.1);">
                                <svg class="w-6 h-6" style="color: #FFCA28;" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
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
                    <a href="{{ route('admin.audit-logs.index') }}"
                        class="text-xs flex items-center transition-colors duration-200" style="color: #4CAF50;"
                        onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                        View all
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>

                <div class="p-4">
                    @if (count($recentLogs ?? []) > 0)
                        <div class="space-y-4">
                            @foreach ($recentLogs as $log)
                                <div class="border-b pb-4 last:border-0 last:pb-0" style="border-color: #F5F5F5;">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-3">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center"
                                                style="background-color: rgba(76, 175, 80, 0.1); color: #4CAF50;">
                                                @if ($log->user)
                                                    <span
                                                        class="text-sm font-medium">{{ substr($log->user->name, 0, 1) }}</span>
                                                @else
                                                    <span class="text-sm font-medium">S</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="text-sm font-medium" style="color: #424242;">
                                                        {{ $log->user ? $log->user->name : 'System' }}</h4>
                                                    <p class="text-xs mt-1" style="color: #9E9E9E;">
                                                        {{ $log->model_type }} @if ($log->model_id)
                                                            #{{ $log->model_id }}
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="flex items-center">
                                                    <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full"
                                                        style="@if ($log->action == 'create') background-color: #4CAF50; color: white; @elseif($log->action == 'update') background-color: #FFCA28; color: #975A16; @elseif($log->action == 'delete') background-color: #D32F2F; color: white; @elseif($log->action == 'login') background-color: #4A90E2; color: white; @elseif($log->action == 'logout') background-color: #9C27B0; color: white; @else background-color: #4A90E2; color: white; @endif">
                                                        {{ ucfirst($log->action) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="text-xs mt-1" style="color: #757575;">
                                                {{ $log->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center mb-3"
                                style="background-color: rgba(76, 175, 80, 0.1);">
                                <svg class="w-6 h-6" style="color: #4CAF50;" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
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

        [x-cloak] {
            display: none !important;
        }

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
