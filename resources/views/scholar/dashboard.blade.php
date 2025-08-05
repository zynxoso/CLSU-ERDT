@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Password Change Notice Modal -->
@if(session('show_password_modal'))
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
                        Welcome to CLSU-ERDT! 🎓
                    </h3>
                    <div class="mt-3">
                        <p class="text-sm text-gray-600">
                            For enhanced security of your account, we recommend changing your default password to a strong, unique password.
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
                <a href="{{ route('scholar.password.change') }}"
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
    
<!-- Header Section -->
<div class="bg-white border-b border-gray-200 shadow-sm">
    <div class="container mx-auto px-4 py-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-gray-600 mt-1">Welcome back, {{ Auth::user()->name }}</p>
            </div>
            <div class="flex items-center space-x-4">
            <!-- Unified Notification Dropdown -->
            <div class="relative" x-data="{ open: false, notificationCount: {{ count($notifications->where('is_read', false)) }} }">
                <button @click="open = !open" class="relative p-2.5 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span x-show="notificationCount > 0" class="notification-count-badge absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[1.25rem] h-5 text-xs font-bold leading-none text-white bg-red-600 rounded-full shadow-sm px-1" x-text="notificationCount"></span>
                </button>
                <!-- Mobile-First Responsive Dropdown -->
                <div x-show="open"
                     @click.away="open = false"
                     class="notification-dropdown absolute right-0 mt-2 w-screen max-w-sm sm:max-w-md lg:max-w-lg xl:max-w-xl
                            sm:w-96 md:w-[400px] lg:w-[450px] xl:w-[500px]
                            bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden z-50
                            -mr-4 sm:mr-0"
                     style="display: none;">
                    <!-- Header - Responsive -->
                    <div class="py-3 px-4 sm:px-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200 flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="text-base sm:text-lg font-bold text-gray-800">All Notifications</span>
                        </div>
                        <a href="{{ route('scholar.notifications') }}" class="text-xs sm:text-sm text-blue-600 hover:text-blue-800 flex items-center font-medium">
                            <span class="hidden sm:inline">View All</span>
                            <span class="sm:hidden">All</span>
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                    <!-- Notification List - Responsive Scrolling -->
                    <div class="notification-scroll max-h-[60vh] sm:max-h-[500px] overflow-y-auto">
                        @if(count($notifications) > 0)
                            @foreach($notifications->take(8) as $notification)
                                <div class="notification-item px-3 sm:px-4 py-3 border-b border-gray-100 {{ !$notification->is_read ? 'bg-blue-50 border-l-4 border-l-blue-500' : '' }}" x-data="{ expanded: false }">
                                    <div class="flex items-start space-x-2 sm:space-x-3">
                                        <!-- Icon - Responsive Size -->
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center
                                                @if($notification->type == 'profile_update') bg-blue-100
                                                @elseif($notification->type == 'fund_request') bg-green-100
                                                @elseif($notification->type == 'document') bg-purple-100
                                                @elseif($notification->type == 'manuscript') bg-indigo-100
                                                @else bg-gray-100 @endif">
                                                @if($notification->type == 'profile_update')
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                @elseif($notification->type == 'fund_request')
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                    </svg>
                                                @elseif($notification->type == 'document')
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                @elseif($notification->type == 'manuscript')
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- Content - Responsive Layout -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1 sm:gap-2">
                                                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-2 space-y-1 sm:space-y-0">
                                                    <h4 class="notification-title text-sm font-medium text-gray-900 truncate">{{ $notification->title }}</h4>
                                                    <!-- Category Badge - Responsive -->
                                                    <span class="notification-badge inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium w-fit
                                                        @if($notification->type == 'profile_update') bg-blue-100 text-blue-700
                                                        @elseif($notification->type == 'fund_request') bg-green-100 text-green-700
                                                        @elseif($notification->type == 'document') bg-purple-100 text-purple-700
                                                        @elseif($notification->type == 'manuscript') bg-indigo-100 text-indigo-700
                                                        @else bg-gray-100 text-gray-700 @endif">
                                                        @if($notification->type == 'profile_update') Profile
                                                        @elseif($notification->type == 'fund_request') Fund
                                                        @elseif($notification->type == 'document') Document
                                                        @elseif($notification->type == 'manuscript') Manuscript
                                                        @else General @endif
                                                    </span>
                                                </div>
                                                @if(!$notification->is_read)
                                                    <div class="w-2 h-2 bg-blue-600 rounded-full flex-shrink-0 mt-1 sm:mt-0"></div>
                                                @endif
                                            </div>
                                            <!-- Message - Responsive Text -->
                                            <p class="text-xs sm:text-sm text-gray-600 mt-1 leading-relaxed">
                                                {{ Str::limit($notification->message, 100) }}
                                            </p>
                                            <!-- Footer - Responsive Layout -->
                                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mt-2 gap-1 sm:gap-2">
                                                <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                                @if($notification->link)
                                                    <a href="{{ $notification->link }}" class="notification-link text-xs text-blue-600 hover:text-blue-800 font-medium w-fit">
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
                                <div class="notification-empty-icon w-12 h-12 sm:w-16 sm:h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-3 sm:mb-4">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-medium text-gray-800 mb-1">No notifications</h3>
                                <p class="notification-empty-text text-xs text-gray-500 px-2">You'll be notified about updates to your profile, fund requests, documents, and manuscripts.</p>
                            </div>
                        @endif
                    </div>
                    <!-- Footer - Responsive -->
                    @if(count($notifications->where('is_read', false)) > 0)
                        <div class="py-2 px-3 sm:px-4 bg-gray-50 border-t border-gray-200 text-center">
                            <a href="{{ route('scholar.notifications.mark-all-as-read') }}" class="text-xs sm:text-sm text-blue-600 hover:text-blue-800 font-medium">
                                <span class="hidden sm:inline">Mark all as read</span>
                                <span class="sm:hidden">Mark all read</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            <!-- User Profile Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none">
                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center border border-blue-100 shadow-sm">
                        <span class="text-blue-600 font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <span class="text-sm font-medium hidden md:block">{{ Auth::user()->name }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open"
                     @click.away="open = false"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg overflow-hidden z-50"
                     style="display: none;">
                    <a href="{{ route('scholar.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user mr-2 text-black" style="margin-right: 0.5rem; color: #6B7280;"></i> My Profile
                    </a>
                    <a href="{{ route('scholar.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-cog" style="margin-right: 0.5rem; color: #6B7280;"></i> Settings
                    </a>
                    <a href="{{ route('scholar.password.change') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-key mr-2 text-gray-500" style="margin-right: 0.5rem; color: #6B7280;"></i> Change Password
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2" style="margin-right: 0.5rem; color:#FF4842"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-8">

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <!-- Quick Actions -->
    <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
        <div class="flex items-center mb-3">
            <i class="fas fa-bolt text-yellow-500 mr-2"></i>
            <h2 class="text-base font-semibold text-gray-800">Quick Actions</h2>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('scholar.fund-requests.create') }}" class="bg-[#4CAF50] p-3 rounded-lg text-center border border-[#388E3C]">
                <i class="fas fa-money-bill-wave text-white text-xl mb-1"></i>
                <p class="text-white font-medium text-sm">Request Funds</p>
            </a>
            <a href="{{ route('scholar.profile.edit') }}" class="bg-[#FFCA28] p-3 rounded-lg text-center border border-[#FFB300]">
                <i class="fas fa-user-edit text-white text-xl mb-1"></i>
                <p class="text-white font-medium text-sm">Update Profile</p>
            </a>
        </div>
    </div>
    <!-- Fund Summary -->
    <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
        <div class="flex items-center mb-3">
            <i class="fas fa-chart-pie text-[#4CAF50] mr-2"></i>
            <h2 class="text-base font-semibold text-gray-800">Fund Summary</h2>
        </div>
        <div class="flex justify-between">
            <div class="text-center bg-gray-50 p-3 rounded-lg border border-gray-100 flex-1 mr-2">
                <p class="text-sm text-gray-600 mb-1">Approved Requests</p>
                <p class="text-lg font-bold text-[#4CAF50]">{{ $approvedRequests }}</p>
            </div>
            <div class="text-center bg-gray-50 p-3 rounded-lg border border-gray-100 flex-1 ml-2">
                <p class="text-sm text-gray-600 mb-1">Pending Requests</p>
                <p class="text-lg font-bold text-[#FFCA28]">{{ $pendingRequestsCount }}</p>
            </div>
        </div>
    </div>
</div>
<!-- Recent Activity -->
<div class="grid grid-cols-1 gap-6">
    <!-- Recent Fund Requests -->
    <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
            <h2 class="text-base font-semibold text-gray-800">Recent Fund Requests</h2>
        </div>
        <div class="p-4">
            @if(count($recentFundRequests) > 0)
                <div class="space-y-3">
                    @foreach($recentFundRequests->take(3) as $request)
                        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm" data-fund-request-id="{{ $request->id }}">
                            <div class="flex justify-between items-center mb-2">
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">{{ $request->purpose }}</p>
                                    <p class="text-xs text-gray-500">{{ $request->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-800">₱******</p>
                                    <span class="status-badge px-2.5 py-1 text-xs rounded-full font-medium
                                        @if($request->status == 'Approved') bg-[#4CAF50]/10 text-[#4CAF50] border border-[#4CAF50]/20
                                        @elseif($request->status == 'Rejected') bg-red-100 text-red-800 border border-red-200
                                        @else bg-[#FFCA28]/10 text-[#FFCA28] border border-[#FFCA28]/20 @endif">
                                        {{ $request->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('scholar.fund-requests.index') }}" class="text-[#4A90E2] text-sm font-medium inline-flex items-center">
                        View All Requests
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            @else
                <div class="text-center py-6">
                    <div class="w-12 h-12 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-file-invoice-dollar text-gray-400 text-lg"></i>
                    </div>
                    <p class="text-gray-500 text-sm">No fund requests yet.</p>
                    <a href="{{ route('scholar.fund-requests.create') }}" class="mt-2 inline-block text-[#4A90E2] text-sm font-medium">
                        Create your first request
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
<!-- Real-time Status Updates Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    async function fetchStatusUpdates() {
        try {
            const response = await fetch('/scholar/status-updates', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                credentials: 'same-origin' // Include cookies for authentication
            });
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            const data = await response.json();
            if (data.success && data.data.updates) {
                const timelineContainer = document.querySelector('.status-timeline');
                if (!timelineContainer) return;
                // Clear existing timeline
                timelineContainer.innerHTML = '';
                // Add new timeline entries
                data.data.updates.forEach(history => {
                    const timeAgo = timeSince(new Date(history.updated_at));
                    // Determine icon class based on status
                    let iconClass = 'fa-info-circle text-blue-500';
                    if (history.status.toLowerCase().includes('approved')) {
                        iconClass = 'fa-check-circle text-green-500';
                    } else if (history.status.toLowerCase().includes('rejected')) {
                        iconClass = 'fa-times-circle text-red-500';
                    } else if (history.status.toLowerCase().includes('pending')) {
                        iconClass = 'fa-clock text-yellow-500';
                    }
                    const entryDiv = document.createElement('div');
                    entryDiv.className = 'flex items-center space-x-3 mb-4';
                    entryDiv.innerHTML = `
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas ${iconClass} text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium">
                                ${history.status}
                                <span class="text-gray-400 font-normal">${timeAgo}</span>
                            </p>
                            ${history.notes ? `<p class="text-xs text-gray-500">${truncateText(history.notes, 50)}</p>` : ''}
                        </div>
                    `;
                    timelineContainer.appendChild(entryDiv);
                });
            }
        } catch (error) {
            console.error('Error fetching status updates:', error);
        }
    }
    // Helper function to format time since a date
    function timeSince(date) {
        const seconds = Math.floor((new Date() - date) / 1000);
        let interval = seconds / 31536000;
        if (interval > 1) return Math.floor(interval) + " years ago";
        interval = seconds / 2592000;
        if (interval > 1) return Math.floor(interval) + " months ago";
        interval = seconds / 86400;
        if (interval > 1) return Math.floor(interval) + " days ago";
        interval = seconds / 3600;
        if (interval > 1) return Math.floor(interval) + " hours ago";
        interval = seconds / 60;
        if (interval > 1) return Math.floor(interval) + " minutes ago";
        return Math.floor(seconds) + " seconds ago";
    }
    // Helper function to truncate text
    function truncateText(text, maxLength) {
        return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
    }
    // Check for updates every 30 seconds, but only when page is visible
    setInterval(() => {
        if (!document.hidden) {
            fetchStatusUpdates();
        }
    }, 30000);
    // Initial fetch after page load
    setTimeout(fetchStatusUpdates, 3000);
    // Also fetch updates when the page becomes visible again
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            fetchStatusUpdates();
        }
    });
});
</script>

<!-- Removed all animation and transition CSS -->
<style>
/* Only keep responsive and layout styles, remove all keyframes and animation classes */
.notification-title {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 100%;
}
@media (max-width: 640px) {
    .notification-title {
        max-width: calc(100% - 4rem);
    }
    .notification-count-badge {
        top: -0.25rem !important;
        right: -0.25rem !important;
        min-width: 1.25rem !important;
        height: 1.25rem !important;
        font-size: 0.625rem !important;
    }
    .notification-empty-icon {
        width: 3rem !important;
        height: 3rem !important;
    }
    .notification-empty-text {
        font-size: 0.75rem !important;
        padding: 0 1rem !important;
    }
}
.notification-scroll {
    -webkit-overflow-scrolling: touch;
    scroll-behavior: smooth;
}
.notification-item:focus-within {
    outline: 2px solid #3b82f6;
    outline-offset: -2px;
    background-color: #f8fafc;
}
</style>

</div> <!-- End container -->
@endsection
