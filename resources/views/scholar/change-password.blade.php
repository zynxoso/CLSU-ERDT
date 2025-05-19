@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <!-- <a href="{{ route('scholar.settings') }}" class="text-blue-400 hover:text-blue-300 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Settings
            </a> -->
            <h1 class="text-2xl font-bold text-gray-900 mt-2">Change Your Password</h1>
            <p class="text-gray-500 mt-1">Update your account password</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 relative" role="alert">
                <p>{{ session('success') }}</p>
                <button class="absolute top-0 right-0 mt-4 mr-4 text-green-700 hover:text-green-900" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 relative" role="alert">
                <p>{{ session('error') }}</p>
                <button class="absolute top-0 right-0 mt-4 mr-4 text-red-700 hover:text-red-900" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <form action="{{ route('scholar.password.update') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password <span class="text-red-500">*</span></label>
                    <input type="password" id="current_password" name="current_password" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('current_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password <span class="text-red-500">*</span></label>
                    <input type="password" id="new_password" name="new_password" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <p class="text-xs text-gray-500 mt-1">Password must be at least 8 characters long.</p>
                    @error('new_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password <span class="text-red-500">*</span></label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('new_password_confirmation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-key mr-2"></i> Update Password
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-6 bg-yellow-50 p-4 rounded-lg border border-yellow-200">
            <h2 class="font-bold text-yellow-800 mb-2">Important Password Security Tips</h2>
            <ul class="list-disc pl-5 text-yellow-700">
                <li>Use a strong, unique password</li>
                <li>Include a mix of letters, numbers, and special characters</li>
                <li>Don't reuse passwords from other sites</li>
                <li>Never share your password with others</li>
                <li>Change your password periodically</li>
            </ul>
        </div>
    </div>
</div>
@endsection
