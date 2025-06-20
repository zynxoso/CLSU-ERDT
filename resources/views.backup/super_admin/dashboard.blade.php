@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

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
                <a href="{{ route('super_admin.password.change') }}"
                   class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Change Password
                </a>

                @if(!auth()->user()->must_change_password)
                <button type="button"
                        @click="dismissModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm">
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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

        <a href="{{ route('super_admin.system_configuration') }}" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-300">
            <div class="flex-shrink-0 mr-3">
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div>
                <h4 class="font-medium text-gray-900">System Configuration</h4>
                <p class="text-xs text-gray-500">Academic calendar & scholarship parameters</p>
            </div>
        </a>

        <a href="{{ route('super_admin.data_management') }}" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors duration-300">
            <div class="flex-shrink-0 mr-3">
                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                    </svg>
                </div>
            </div>
            <div>
                <h4 class="font-medium text-gray-900">Data Management</h4>
                <p class="text-xs text-gray-500">Backup, restore & import/export</p>
            </div>
        </a>
    </div>

    <!-- Website Management Section -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <h3 class="font-semibold text-gray-800 mb-4">Website Management</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('super_admin.website_management') }}" class="flex items-center p-3 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors duration-300">
                <div class="flex-shrink-0 mr-3">
                    <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Content Management</h4>
                    <p class="text-xs text-gray-500">Manage website content, announcements & faculty</p>
                </div>
            </a>

            <a href="{{ route('scholar-login') }}" target="_blank" class="flex items-center p-3 bg-teal-50 rounded-lg hover:bg-teal-100 transition-colors duration-300">
                <div class="flex-shrink-0 mr-3">
                    <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </div>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Preview Website</h4>
                    <p class="text-xs text-gray-500">View public scholar website</p>
                </div>
            </a>

            <a href="#" onclick="alert('Website analytics feature coming soon!')" class="flex items-center p-3 bg-pink-50 rounded-lg hover:bg-pink-100 transition-colors duration-300">
                <div class="flex-shrink-0 mr-3">
                    <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center text-pink-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Website Analytics</h4>
                    <p class="text-xs text-gray-500">View website traffic & engagement</p>
                </div>
            </a>
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
