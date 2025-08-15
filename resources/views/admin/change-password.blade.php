@extends('layouts.app')
@section('title', 'Change Password')
@section('content')
    <div class="container mx-auto">
        <div class="max-w-md mx-auto">
            <h2 class="text-2xl font-bold text-[#228B22] mb-1">Change Password</h2>
            <p class="text-sm text-gray-700 mb-8">Update your account password</p>

            @if (session('success'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-md text-green-800 text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-md text-red-800 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.password.update') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                        Current Password
                    </label>
                    <input type="password" id="current_password" name="current_password" required
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-[#228B22] @error('current_password') border-red-500 @else border-gray-300 @enderror">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">
                        New Password
                    </label>
                    <input type="password" id="new_password" name="new_password" required minlength="8"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-[#228B22] @error('new_password') border-red-500 @else border-gray-300 @enderror"
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
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                        minlength="8"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-[#228B22]">
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md">
                    Update Password
                </button>
            </form>

            <div class="mt-8 p-4 bg-gray-50 rounded-md">
                <h3 class="text-sm font-semibold text-[#228B22] mb-2">Password Requirements</h3>
                <ul class="text-xs text-gray-600 space-y-1">
                    <li>• At least 8 characters long</li>
                    <li>• Mix of uppercase and lowercase letters</li>
                    <li>• Include numbers and special characters</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function checkPasswordStrength(password) {
            const strengthBar = document.getElementById('password-strength');
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;

            const levels = ['Very Weak', 'Weak', 'Moderate', 'Strong', 'Very Strong'];
            const colors = ['#dc2626', '#f59e42', '#fbbf24', '#22c55e', '#16a34a'];

            strengthBar.textContent = levels[Math.max(0, strength - 1)] || '';
            strengthBar.style.color = colors[Math.max(0, strength - 1)] || '';
        }
    </script>
@endpush
