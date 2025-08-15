@extends('layouts.app')
@section('title', 'Profile')
@section('content')
    <div class="bg-gray-900 min-h-screen">
        <div class="container mx-auto px-4 py-6">
            <h1 class="text-2xl font-bold text-white mb-6">Profile Settings</h1>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
                        <div class="p-6">
                            <div class="flex flex-col items-center">
                                <div class="w-32 h-32 rounded-full bg-gray-700 flex items-center justify-center mb-4">
                                    @if (Auth::user()->profile_photo)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                                            alt="{{ Auth::user()->name }}" class="w-32 h-32 rounded-full object-cover">
                                    @else
                                        <i class="fas fa-user text-6xl text-gray-400"></i>
                                    @endif
                                </div>
                                <h2 class="text-xl font-bold text-white mb-1">{{ Auth::user()->name }}</h2>
                                <p class="text-gray-400 mb-4">{{ Auth::user()->email }}</p>
                                <form action="{{ route('admin.profile.photo') }}" method="POST"
                                    enctype="multipart/form-data" class="inline">
                                    @csrf
                                    <label for="profile-photo"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 cursor-pointer inline-block">
                                        <i class="fas fa-camera mr-2"></i> Change Photo
                                    </label>
                                    <input type="file" id="profile-photo" name="profile_photo" class="hidden"
                                        accept="image/*" onchange="this.form.submit()">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Personal Information -->
                    <div class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
                        <div class="bg-gray-900 px-6 py-4 border-b border-gray-700">
                            <h2 class="text-lg font-semibold text-white">Personal Information</h2>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('admin.profile.update') }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-400 mb-1">Full
                                            Name</label>
                                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                                            class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            required>
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-400 mb-1">Email
                                            Address</label>
                                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                            class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            required>
                                    </div>
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-400 mb-1">Phone
                                            Number</label>
                                        <input type="tel" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                                            class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label for="position"
                                            class="block text-sm font-medium text-gray-400 mb-1">Position</label>
                                        <input type="text" name="position"
                                            value="{{ old('position', Auth::user()->position) }}"
                                            class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        <i class="fas fa-save mr-2"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Security Settings -->
                    <div class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
                        <div class="bg-gray-900 px-6 py-4 border-b border-gray-700">
                            <h2 class="text-lg font-semibold text-white">Security Settings</h2>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('admin.profile.password') }}" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-400 mb-1">Current Password</label>
                                        <input type="password" name="current_password"
                                            class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-400 mb-1">New Password</label>
                                        <input type="password" name="new_password"
                                            class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-400 mb-1">Confirm New
                                            Password</label>
                                        <input type="password" name="new_password_confirmation"
                                            class="w-full bg-gray-700 border border-gray-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            required>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        <i class="fas fa-key mr-2"></i> Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Notification Preferences -->
                    <div class="bg-gray-800 rounded-lg overflow-hidden border border-gray-700">
                        <div class="bg-gray-900 px-6 py-4 border-b border-gray-700">
                            <h2 class="text-lg font-semibold text-white">Notification Preferences</h2>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('admin.profile.notifications') }}" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    @php
                                        $notifications = [
                                            'email_notifications' => 'Email Notifications',
                                            'fund_request_notifications' => 'Fund Request Notifications',
                                            'document_notifications' => 'Document Verification Notifications',
                                            'manuscript_notifications' => 'Manuscript Review Notifications',
                                        ];
                                    @endphp
                                    @foreach ($notifications as $key => $label)
                                        <div class="flex items-center">
                                            <input type="checkbox" name="{{ $key }}" value="1"
                                                {{ Auth::user()->$key ? 'checked' : '' }}
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-600 rounded">
                                            <label class="ml-2 block text-sm text-gray-300">{{ $label }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-6">
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                        <i class="fas fa-bell mr-2"></i> Save Preferences
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
