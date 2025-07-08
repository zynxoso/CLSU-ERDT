@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
<div class="container mx-auto">
    <div class="max-w-md mx-auto">
        <h2 class="text-2xl font-bold text-[#228B22] mb-1">Change Password</h2>
        <p class="text-sm text-gray-700 mb-8">Update your account password</p>

        @if (session('success'))
            <div class="mb-4 flex items-center gap-2 text-green-800">
                <svg class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 flex items-center gap-2 text-red-800">
                <svg class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif
        @if (session('warning'))
            <div class="mb-4 flex items-center gap-2 text-yellow-800">
                <svg class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium">{{ session('warning') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.password.update') }}" class="space-y-6">
            @csrf
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                    Current Password
                </label>
                <input type="password"
                       id="current_password"
                       name="current_password"
                       required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#228B22] focus:border-[#228B22] @error('current_password') border-red-500 @enderror">
                @error('current_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">
                    New Password
                </label>
                <input type="password"
                       id="new_password"
                       name="new_password"
                       required
                       minlength="8"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#228B22] focus:border-[#228B22] @error('new_password') border-red-500 @enderror"
                       oninput="checkPasswordStrength(this.value)">
                @error('new_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div id="password-strength" class="mt-1 text-xs font-semibold"></div>
                <p class="mt-1 text-xs text-gray-500">Password must be at least 8 characters long</p>
            </div>
            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                    Confirm New Password
                </label>
                <input type="password"
                       id="new_password_confirmation"
                       name="new_password_confirmation"
                       required
                       minlength="8"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#228B22] focus:border-[#228B22]">
            </div>
            <div class="flex items-center justify-between">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Update Password
                </button>
            </div>
        </form>

        <!-- Password Security Tips -->
        <div class="mt-8">
            <h3 class="text-sm font-semibold text-[#228B22] mb-2">Password Security Tips</h3>
            <ul class="text-xs text-gray-800 space-y-1 pl-4 border-l-4 border-[#FFD700]">
                <li>• Use a combination of uppercase and lowercase letters</li>
                <li>• Include numbers and special characters</li>
                <li>• Avoid using personal information</li>
                <li>• Don't reuse passwords from other accounts</li>
                <li>• Your password will expire every 90 days</li>
            </ul>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function checkPasswordStrength(password) {
    const strengthBar = document.getElementById('password-strength');
    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/)) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;
    let msg = '';
    let color = '';
    switch (strength) {
        case 0:
        case 1:
            msg = 'Very Weak'; color = '#dc2626'; break;
        case 2:
            msg = 'Weak'; color = '#f59e42'; break;
        case 3:
            msg = 'Moderate'; color = '#fbbf24'; break;
        case 4:
            msg = 'Strong'; color = '#22c55e'; break;
        case 5:
            msg = 'Very Strong'; color = '#16a34a'; break;
    }
    strengthBar.textContent = msg;
    strengthBar.style.color = color;
}
</script>
@endsection
