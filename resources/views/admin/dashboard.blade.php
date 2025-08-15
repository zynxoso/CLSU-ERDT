@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <!-- Password Change Notice Modal -->
    @if ((auth()->user()->is_default_password || auth()->user()->must_change_password) && !session('password_warning_dismissed'))
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
                            <svg class="h-6 w-6" style="color: rgb(34 197 94);" fill="none" viewBox="0 0 24 24"
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
                                    <p class="text-sm" style="color: rgb(34 197 94);">
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
                            style="background-color: rgb(34 197 94);" onmouseover="this.style.backgroundColor='#43A047'"
                            onmouseout="this.style.backgroundColor='#4CAF50'" <svg class="w-4 h-4 mr-2" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Change Password
                        </a>
                        <button type="button" @click="showModal = false"
                            class="mt-3 w-full inline-flex justify-center items-center rounded-lg px-4 py-2.5 bg-white text-base font-medium shadow-sm sm:mt-0 sm:w-auto sm:text-sm transition-colors duration-200"
                            style="border: 1px solid #E0E0E0; color: rgb(115 115 115);"
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
        <div class="mb-6 bg-white rounded-xl p-6 shadow-md border border-green-200 clsu-gradient-bg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white border-b-2 border-white pb-2 inline-block">Welcome back, {{ Auth::user()->name }}!</h1>
                    <p class="text-base mt-2 text-white">{{ now()->format('l, F j, Y') }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Unified Notification Dropdown -->
                    <div class="relative" x-data="{ 
                        open: false, 
                        notificationCount: {{ count($notifications->where('is_read', false)) }},
                        
                        async updateNotificationCount() {
                            try {
                                const response = await fetch('{{ route('admin.notifications.unread-count') }}');
                                if (response.ok) {
                                    const data = await response.json();
                                    this.notificationCount = data.count;
                                }
                            } catch (error) {
                                console.warn('Failed to fetch notification count:', error.message);
                            }
                        },

                        showMessage(message, type = 'success') {
                            const messageDiv = document.createElement('div');
                            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
                            messageDiv.className = `fixed top-4 right-4 ${bgColor} text-white px-4 py-2 rounded-lg shadow-lg z-50`;
                            messageDiv.textContent = message;
                            document.body.appendChild(messageDiv);
                            setTimeout(() => messageDiv.remove(), 3000);
                        }
                    }" x-init="setInterval(() => updateNotificationCount(), 30000)">
                        <button @click="open = !open"
                            class="relative p-2.5 rounded-full focus:outline-none transition-colors duration-200 text-white hover:bg-white hover:text-green-500">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <!-- Notification Badge -->
                            <span x-show="notificationCount > 0"
                                class="notification-count-badge absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[1.25rem] h-5 text-xs font-bold leading-none rounded-full shadow-sm px-1 notification-badge"
                                x-text="notificationCount"></span>
                        </button>
                        <!-- Mobile-First Responsive Dropdown -->
                        <div x-show="open" @click.away="open = false"
                            class="notification-dropdown absolute right-0 mt-2 responsive-max-w-dropdown
                            bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden z-50
                            -mr-4 sm:mr-0">
                            <!-- Header - Responsive -->
                            <div class="py-3 px-4 sm:px-6 border-b flex justify-between items-center clsu-gradient-bg clsu-border-green">
                                <div class="flex items-center space-x-2">
                                    <svg class="responsive-icon-sm clsu-primary-color" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <span class="text-base sm:text-lg font-bold clsu-secondary-color">All
                                        Notifications</span>
                                </div>
                                <a href="{{ route('admin.notifications.index') }}"
                                    class="responsive-text-sm flex items-center font-medium transition-colors duration-200 clsu-primary-color clsu-hover-primary">
                                    <span class="hidden sm:inline">View All</span>
                                    <span class="sm:hidden">All</span>
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 ml-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                            <!-- Notification List - Responsive Scrolling -->
                            <div class="notification-scroll responsive-max-h-scroll overflow-y-auto">
                                @if (count($notifications) > 0)
                                    @foreach ($notifications->take(8) as $notification)
                                        <div class="notification-item responsive-px-sm py-3 border-b {{ !$notification->is_read ? 'border-l-4 notification-item-unread' : '' }} clsu-border">
                                            <div class="flex items-start responsive-space-x-sm">
                                                <!-- Icon - Responsive Size -->
                                                <div class="flex-shrink-0">
                                                    <div class="responsive-avatar-sm rounded-full flex items-center justify-center clsu-primary-bg-light">
                                                        @if ($notification->type == 'fund_request')
                                                            <svg class="responsive-icon-sm clsu-primary-color"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                                                </path>
                                                            </svg>
                                                        @elseif($notification->type == 'document')
                                                            <svg class="responsive-icon-sm clsu-primary-color"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                                </path>
                                                            </svg>
                                                        @elseif($notification->type == 'manuscript')
                                                            <svg class="responsive-icon-sm clsu-primary-color"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                                </path>
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4 sm:w-5 sm:h-5 clsu-primary-color"
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
                                                        class="responsive-flex-col-sm-row responsive-justify-between-sm responsive-items-start-sm-center responsive-gap-sm">
                                                        <div
                                                            class="responsive-flex-col-sm-row responsive-items-start-sm-center sm:space-x-2 space-y-1 sm:space-y-0">
                                                            <h4 class="notification-title text-sm font-medium truncate clsu-secondary-color">{{ $notification->title }}</h4>
                                                            <!-- Category Badge - Responsive -->
                                                            <span
                                                                class="notification-badge inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium w-fit"
                                                                style="@if ($notification->type == 'fund_request') background-color: rgba(76, 175, 80, 0.1); color: rgb(34 197 94);
                                                                 @elseif($notification->type == 'document') background-color: rgba(156, 39, 176, 0.1); color: #7B1FA2;
                                                                 @elseif($notification->type == 'manuscript') background-color: rgba(63, 81, 181, 0.1); color: #303F9F;
                                                                 @else background-color: rgba(117, 117, 117, 0.1); color: rgb(64 64 64); @endif">
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
                                                            <div class="w-2 h-2 rounded-full flex-shrink-0 mt-1 sm:mt-0 notification-unread-indicator"></div>
                                                        @endif
                                                    </div>
                                                    <!-- Message - Responsive Text -->
                                                    <p class="responsive-text-sm mt-1 leading-relaxed clsu-muted-color">
                                                        {{ Str::limit($notification->message, 100) }}
                                                    </p>
                                                    <!-- Footer - Responsive Layout -->
                                                    <div
                                                        class="responsive-flex-col-sm-row responsive-justify-between-sm responsive-items-start-sm-center mt-2 responsive-gap-sm">
                                                        <span class="text-xs clsu-light-color">{{ $notification->created_at->diffForHumans() }}</span>
                                                        @if ($notification->link)
                                                            <a href="{{ $notification->link }}"
                                                                class="notification-link text-xs font-medium w-fit transition-colors duration-200 clsu-primary-color clsu-hover-primary">
                                                                <span class="hidden sm:inline">View Details →</span>
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
                                        <div class="notification-empty-icon w-12 h-12 sm:w-16 sm:h-16 mx-auto rounded-full flex items-center justify-center mb-3 sm:mb-4 clsu-hover-bg">
                                            <svg class="w-6 h-6 sm:w-8 sm:h-8 clsu-light-color" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                                </path>
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-medium mb-1 clsu-secondary-color">No notifications</h3>
                                        <p class="notification-empty-text text-xs px-2 clsu-light-color">You'll be
                                            notified about fund requests, documents, and manuscripts from scholars.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="w-14 h-14 rounded-full flex items-center justify-center ring-2 ring-green-500 shadow-lg clsu-avatar-gradient">
                            <span class="text-xl font-bold text-white">C</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="mb-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Active Scholars -->
                <div class="bg-white rounded-xl shadow-md p-6 cursor-pointer relative border border-green-200 hover:shadow-lg transition-all duration-200 hover:border-green-300 clsu-card-gradient">
                    <div class="flex items-center justify-between">
                        <div class="text-base font-semibold clsu-muted-color">Active Scholars</div>
                        <div class="flex items-center justify-center w-10 h-10 rounded-full clsu-primary-bg shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-4xl font-bold clsu-secondary-color">{{ $totalScholars }}</div>
                        <div class="mt-2 text-sm clsu-muted-color">Currently enrolled students</div>
                    </div>
                </div>

                <!-- Pending Requests -->
                <div class="bg-white rounded-xl shadow-md p-6 cursor-pointer relative border border-green-200 hover:shadow-lg transition-all duration-200 hover:border-green-300 clsu-card-gradient">
                    <div class="flex items-center justify-between">
                        <div class="text-base font-semibold clsu-muted-color">Pending Requests</div>
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-400 shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-4xl font-bold clsu-primary-color">{{ $pendingRequests }}</div>
                        <div class="mt-2 text-sm clsu-muted-color">Awaiting your review</div>
                    </div>
                </div>

                <!-- Active Manuscripts -->
                <div class="bg-white rounded-xl shadow-md p-6 cursor-pointer relative border border-green-200 hover:shadow-lg transition-all duration-200 hover:border-green-300 clsu-card-gradient">
                    <div class="flex items-center justify-between">
                        <div class="text-base font-semibold clsu-muted-color">Active Manuscripts</div>
                        <div class="flex items-center justify-center w-10 h-10 rounded-full clsu-primary-bg shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-4xl font-bold clsu-primary-color">{{ $activeManuscripts ?? 0 }}
                        </div>
                        <div class="mt-2 text-sm clsu-muted-color">Under review and processing</div>
                    </div>
                </div>

                <!-- Total Scholars -->
                <div class="bg-white rounded-xl shadow-md p-6 cursor-pointer relative border border-green-200 hover:shadow-lg transition-all duration-200 hover:border-green-300 clsu-card-gradient">
                    <div class="flex items-center justify-between">
                        <div class="text-base font-semibold clsu-muted-color">Total Scholars</div>
                        <div class="flex items-center justify-center w-10 h-10 rounded-full clsu-primary-bg shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="text-4xl font-bold clsu-secondary-color">{{ $totalScholars ?? 0 }}</div>
                        <div class="mt-2 text-sm clsu-muted-color">Total scholars in the system</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Recent Fund Requests -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-green-200">
                <div class="flex justify-between items-center px-6 py-4 border-b border-green-100" style="background: linear-gradient(135deg, rgba(76, 175, 80, 0.05), rgba(139, 195, 74, 0.03));">
                    <h3 class="text-lg font-bold text-gray-900">Recent Fund Requests</h3>
                    <a href="{{ route('admin.fund-requests.index') }}"
                        class="text-sm flex items-center font-medium text-green-600 hover:text-green-700 transition-colors duration-200">
                        View all
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>

                <div class="p-4">
                    @if (count($recentFundRequests) > 0)
                        <div class="space-y-4">
                            @foreach ($recentFundRequests as $index => $request)
                                <div class="border-b pb-4 last:border-0 last:pb-0 clsu-border">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-3">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center clsu-primary-bg-light">
                                                <span
                                                    class="text-sm font-medium clsu-primary-color">{{ substr($request->scholarProfile && $request->scholarProfile->user ? $request->scholarProfile->user->name : 'U', 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="text-sm font-medium clsu-secondary-color">
                                                        {{ $request->scholarProfile && $request->scholarProfile->user ? $request->scholarProfile->user->name : 'Unknown Scholar' }}
                                                    </h4>
                                                    <p class="text-xs mt-1 clsu-light-color">
                                                        ₱{{ number_format($request->amount, 2) }} •
                                                        {{ $request->created_at->format('M d, Y') }}</p>
                                                    <p class="text-xs mt-1 line-clamp-1 clsu-muted-color">
                                                        {{ $request->purpose }}</p>
                                                </div>
                                                <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full @if ($request->status === 'Under Review') status-under-review @elseif($request->status === 'Approved') status-approved @elseif($request->status === 'Rejected') status-rejected @elseif($request->status === 'Submitted') status-submitted @elseif($request->status === 'Disbursed') status-disbursed @else status-default @endif">
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
                            <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center mb-3 clsu-hover-bg">
                                <svg class="w-6 h-6 clsu-light-color" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <p class="text-sm clsu-light-color">No recent fund requests found</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-green-200">
                <div class="flex justify-between items-center px-6 py-4 border-b border-green-100" style="background: linear-gradient(135deg, rgba(76, 175, 80, 0.05), rgba(139, 195, 74, 0.03));">
                    <h3 class="text-lg font-bold text-gray-900">Recent Activity</h3>
                    <a href="{{ route('admin.audit-logs.index') }}"
                        class="text-sm flex items-center font-medium text-green-600 hover:text-green-700 transition-colors duration-200">
                        View all
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>

                <div class="p-4">
                    @if (count($recentLogs ?? []) > 0)
                        <div class="space-y-4">
                            @foreach ($recentLogs as $log)
                                <div class="border-b pb-4 last:border-0 last:pb-0 clsu-border">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-3">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center clsu-primary-bg-light">
                                                @if ($log->user)
                                                    <span
                                                        class="text-sm font-medium clsu-primary-color">{{ substr($log->user->name, 0, 1) }}</span>
                                                @else
                                                    <span class="text-sm font-medium clsu-primary-color">S</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="text-sm font-medium clsu-secondary-color">
                                                        {{ $log->user ? $log->user->name : 'System' }}</h4>
                                                    <p class="text-xs mt-1 clsu-light-color">
                                                        {{ $log->model_type }} @if ($log->model_id)
                                                            #{{ $log->model_id }}
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="flex items-center">
                                                    <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full @if ($log->action == 'create') action-create @elseif($log->action == 'update') action-update @elseif($log->action == 'delete') action-delete @elseif($log->action == 'login') action-login @elseif($log->action == 'logout') action-logout @else action-login @endif">
                                                        {{ ucfirst($log->action) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="text-xs mt-1 clsu-muted-color">
                                                {{ $log->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center mb-3 clsu-primary-bg-light">
                                <svg class="w-6 h-6 clsu-primary-color" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-sm clsu-light-color">No recent activity found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@section('styles')
    <style>
        /* Base Styles */
        body { font-family: theme(fontFamily.sans); font-size: 15px; line-height: 1.6; color: #404040; background-color: #FAFAFA !important; }
        [x-cloak] { display: none !important; }
        .line-clamp-1 { overflow: hidden; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; }
        
        /* Color System */
        .clsu-primary-color { color: #22C55E; }
        .clsu-secondary-color { color: #404040; }
        .clsu-muted-color { color: #737373; }
        .clsu-light-color { color: #9E9E9E; }
        .clsu-primary-bg { background-color: #4CAF50; }
        .clsu-primary-bg-light { background-color: rgba(76, 175, 80, 0.1); }
        
        /* Gradient System */
        .clsu-gradient-bg { background: linear-gradient(135deg, #4CAF50 0%, #45A049 100%); }
        .clsu-card-gradient { background: linear-gradient(135deg, rgba(76, 175, 80, 0.05), rgba(255, 255, 255, 1)); }
        .clsu-avatar-gradient { background: linear-gradient(135deg, #4CAF50, #66BB6A); }
        
        /* Notification System */
        .notification-badge { background-color: #D32F2F; color: #FFFFFF; }
        .notification-unread-indicator { background-color: #22C55E; }
        .notification-item-unread { background-color: rgba(76, 175, 80, 0.05); border-left-color: #22C55E; }
        
        /* Badge System - Status */
        .status-pending { background-color: #FEF3C7; color: #92400E; }
        .status-under-review { background-color: #FBBF24; color: #975A16; }
        .status-approved { background-color: #22C55E; color: #FFFFFF; }
        .status-rejected { background-color: #D32F2F; color: #FFFFFF; }
        .status-submitted { background-color: #3B82F6; color: #FFFFFF; }
        .status-disbursed { background-color: #9C27B0; color: #FFFFFF; }
        .status-default { background-color: #737373; color: #FFFFFF; }
        
        /* Badge System - Actions */
        .action-create { background-color: #22C55E; color: #FFFFFF; }
        .action-update { background-color: #FBBF24; color: #975A16; }
        .action-delete { background-color: #D32F2F; color: #FFFFFF; }
        .action-login { background-color: #3B82F6; color: #FFFFFF; }
        .action-logout { background-color: #9C27B0; color: #FFFFFF; }
        
        /* Responsive Layout System */
        .responsive-flex-col-sm-row { @apply flex flex-col sm:flex-row; }
        .responsive-justify-between-sm { @apply sm:justify-between; }
        .responsive-items-start-sm-center { @apply items-start sm:items-center; }
        .responsive-space-x-sm { @apply space-x-2 sm:space-x-3; }
        .responsive-gap-sm { @apply gap-1 sm:gap-2; }
        .responsive-text-sm { @apply text-xs sm:text-sm; }
        .responsive-icon-sm { @apply w-4 h-4 sm:w-5 sm:h-5; }
        .responsive-avatar-sm { @apply w-8 h-8 sm:w-10 sm:h-10; }
        .responsive-px-sm { @apply px-3 sm:px-4; }
        .responsive-max-w-dropdown { @apply w-screen max-w-sm sm:max-w-md lg:max-w-lg xl:max-w-xl sm:w-96 md:w-[400px] lg:w-[450px] xl:w-[500px]; }
        .responsive-max-h-scroll { @apply max-h-[60vh] sm:max-h-[500px]; }
        
        /* Interactive States */
        .clsu-hover-primary:hover { color: #43A047; }
        .clsu-hover-bg:hover { background-color: #F5F5F5; }
        
        /* Border System */
        .clsu-border { border-color: #F5F5F5; }
        .clsu-border-green { border-color: #E0E0E0; }
    </style>
@endsection
@endsection
