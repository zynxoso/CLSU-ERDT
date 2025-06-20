@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Account Settings</h1>
            <p class="text-gray-500 mt-1">Manage your account preferences and security</p>
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

        <!-- Settings Content -->
        <div class=" mx-auto">

                 <!-- Security Tips -->
                <div class=" bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <h3 class="font-bold text-blue-800 mb-2">Account Security Tips</h3>
                    <ul class="list-disc pl-5 text-blue-700">
                        <li>Regularly update your password</li>
                        <li>Never share your account credentials with others</li>
                        <li>Be cautious when accessing your account on public computers</li>
                        <li>Always log out when you're done using the system</li>
                    </ul>
                </div>
                    
                    <div class="grid grid-cols-1 gap-4 mt-6">
                        <!-- Change Password Card -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-300 cursor-pointer" onclick="window.location.href='{{ route('scholar.password.change') }}'">
                            <div class="flex items-center mb-2">
                                <div class="bg-blue-100 p-3 rounded-full mr-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v-1l1-1 1-1-2.257-2.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <h3 class="font-medium text-gray-800">Change Password</h3>
                            </div>
                            <p class="text-gray-600 text-sm">Update your password to keep your account secure.</p>
                        </div>
                    </div>
                </div>                              
            </div>
        </div>
    </div>
</div>
@endsection
