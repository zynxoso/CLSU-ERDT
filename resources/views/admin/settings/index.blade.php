@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div style="background-color: #FAFAFA; min-height: 100vh; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">
    <div class="container mx-auto px-4 ">
        <div class="mb-6">
            <h1 class="text-3xl font-bold mb-2" style="color: #212121; font-size: 24px;">Settings</h1>
            <p class="mb-8" style="color: #424242; font-size: 15px;">Manage your account settings and preferences</p>
        </div>

        <!-- Security Reminder Card -->
        <div class="rounded-lg p-6 mb-6 border shadow-sm" style="background-color: white; border-color: #E0E0E0;">
            <div class="flex items-start">
                <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4 flex-shrink-0" style="background-color: #E8F5E8;">
                    <svg class="w-5 h-5" style="color: #2E7D32;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-medium mb-2" style="color: #2E7D32; font-size: 16px;">Security Reminder</h3>
                    <p class="text-sm leading-relaxed" style="color: #424242; font-size: 15px; line-height: 1.6;">
                        Your password expires every 90 days for security purposes. Change it regularly and use a strong, unique password to protect your account.
                    </p>
                </div>
            </div>
        </div>

        <!-- Settings Actions -->
        <div class="space-y-4">
            <div class="rounded-lg overflow-hidden border shadow-sm" style="background-color: white; border-color: #E0E0E0;">
                <div class="px-6 py-4 border-b" style="background-color: #F8F9FA; border-color: #E0E0E0;">
                    <h2 class="text-lg font-semibold" style="color: #212121; font-size: 18px;">Account Management</h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="mb-4 sm:mb-0">
                            <h3 class="text-base font-medium mb-1" style="color: #212121; font-size: 16px;">Password Management</h3>
                            <p class="text-sm" style="color: #757575; font-size: 14px;">Update your account password to maintain security</p>
                        </div>
                        <a href="{{ route('admin.password.change') }}"
                           class="inline-flex items-center px-5 py-3 rounded-lg"
                           style="background-color: #2E7D32; color: white; font-size: 15px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                            <svg class="w-5 h-5 mr-2" style="color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Change Password
                        </a>
                    </div>
                </div>
            </div>

            <!-- Additional Settings Placeholder -->
            <div class="rounded-lg overflow-hidden border shadow-sm" style="background-color: white; border-color: #E0E0E0;">
                <div class="px-6 py-4 border-b" style="background-color: #F8F9FA; border-color: #E0E0E0;">
                    <h2 class="text-lg font-semibold" style="color: #212121; font-size: 18px;">System Preferences</h2>
                </div>
                <div class="p-6">
                    <div class="text-center py-8">
                        <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4" style="background-color: #F8F9FA;">
                            <svg class="w-8 h-8" style="color: #757575;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium mb-2" style="color: #212121; font-size: 16px;">Additional Settings Coming Soon</h3>
                        <p style="color: #757575; font-size: 14px;">More system preference options will be available in future updates.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
