@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
<div class="bg-gray-50 min-h-screen font-sans">
    <div class="container mx-auto">

        <div class="bg-white border-b border-gray-200 shadow-sm mb-6">
            <div class="container mx-auto px-4 py-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Account Settings</h1>
                        <p class="text-gray-600 mt-1">Manage your account preferences and security</p>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed bottom-4 right-4 bg-primary-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Security Reminder Card -->
        <div class="rounded-lg p-6 mb-6 border shadow-sm" style="background-color: rgb(255 255 255); border-color: rgb(224 224 224);">
            <div class="flex items-start">
                <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4 flex-shrink-0" style="background-color: #E8F5E8;">
                    <svg class="w-5 h-5" style="color: rgb(21 128 61);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-medium mb-2" style="color: rgb(21 128 61); font-size: 16px;">Security Tips</h3>
                    <ul class="text-sm leading-relaxed space-y-2" style="color: rgb(64 64 64); font-size: 15px; line-height: 1.6;">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-primary-800 mr-2"></i>
                            Regularly update your password
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-primary-800 mr-2"></i>
                            Never share your account credentials with others
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-primary-800 mr-2"></i>
                            Be cautious when accessing your account on public computers
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle text-primary-800 mr-2"></i>
                            Always log out when you're done using the system
                        </li>
                    </ul>
                </div>
            </div>
                </div>

        <!-- Settings Actions -->
        <div class="space-y-4">
            <!-- Account Management -->
            <div class="rounded-lg overflow-hidden border shadow-sm" style="background-color: rgb(255 255 255); border-color: rgb(224 224 224);">
                <div class="px-6 py-4 border-b" style="background-color: #F8F9FA; border-color: rgb(224 224 224);">
                    <h2 class="text-lg font-semibold" style="color: rgb(23 23 23); font-size: 18px;">Account Management</h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="mb-4 sm:mb-0">
                            <h3 class="text-base font-medium mb-1" style="color: rgb(23 23 23); font-size: 16px;">Password Management</h3>
                            <p class="text-sm" style="color: rgb(115 115 115); font-size: 14px;">Update your account password to maintain security</p>
                        </div>
                        <a href="{{ route('scholar.password.change') }}"
                           class="inline-flex items-center px-5 py-3 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-green-700 hover:bg-green-800 focus:bg-green-800 text-white shadow-sm hover:shadow-md focus:shadow-md focus:ring-green-500"
                           style="font-size: 15px;">
                            <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Change Password
                        </a>
                    </div>
                </div>
                                </div>

        </div>
    </div>
</div>
@endsection
