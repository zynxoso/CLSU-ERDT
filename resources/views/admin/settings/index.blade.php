@extends('layouts.admin-simple')

@section('title', 'Settings')

@push('styles')
<style>
    /* Inline critical CSS for faster rendering */
    .settings-container { min-height: 100vh; background-color: #f9fafb; }
    .settings-card { background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); }
    .settings-header { background-color: #f8f9fa; border-bottom: 1px solid #e5e7eb; }
    .btn-primary { background-color: #22c55e; color: #ffffff; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); }
    .btn-primary:hover { background-color: #16a34a; }
</style>
@endpush

@section('content')
<div class="settings-container font-sans">
    <div class="container mx-auto px-4">
        <div class="mb-6">
            <h1 class="text-3xl font-bold mb-2 text-gray-900">Settings</h1>
            <p class="mb-8 text-gray-600">Manage your account settings and preferences</p>
        </div>

        <!-- Security Reminder Card -->
        <div class="settings-card p-6 mb-6">
            <div class="flex items-start">
                <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4 flex-shrink-0 bg-green-100">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-medium mb-2 text-green-600">Security Reminder</h3>
                    <p class="text-sm leading-relaxed text-gray-600">
                        Your password expires every 90 days for security purposes. Change it regularly and use a strong, unique password to protect your account.
                    </p>
                </div>
            </div>
        </div>

        <!-- Settings Actions -->
        <div class="space-y-4">
            <div class="settings-card overflow-hidden">
                <div class="settings-header px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">Account Management</h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="mb-4 sm:mb-0">
                            <h3 class="text-base font-medium mb-1 text-gray-900">Password Management</h3>
                            <p class="text-sm text-gray-500">Update your account password to maintain security</p>
                        </div>
                        <a href="{{ route('admin.password.change') }}"
                           class="btn-primary inline-flex items-center px-5 py-3 rounded-lg font-medium transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

@push('scripts')
<script>
// Optimized JavaScript for settings page - only load what's necessary
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced button interaction for better UX
    const passwordButton = document.querySelector('a[href*="password.change"]');
    if (passwordButton) {
        passwordButton.addEventListener('click', function(e) {
            // Add loading state to button
            const originalText = this.innerHTML;
            this.innerHTML = '<svg class="w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Loading...';
            this.classList.add('opacity-75', 'cursor-not-allowed');
            
            // Restore button state if navigation fails
            setTimeout(() => {
                this.innerHTML = originalText;
                this.classList.remove('opacity-75', 'cursor-not-allowed');
            }, 5000);
        });
    }
    
    // Simple form validation enhancement (if needed in future)
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                setTimeout(() => submitButton.disabled = false, 3000);
            }
        });
    });
});
</script>
@endpush
