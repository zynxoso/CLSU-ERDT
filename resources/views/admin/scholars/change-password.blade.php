@extends('layouts.app')

@section('title', 'Change Scholar Password')

@section('styles')
<style>
    /* Global Typography Improvements */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 15px;
        line-height: 1.6;
        color: #424242;
    }

    .form-control:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.25);
    }

    .btn-transition {
        transition: all 0.3s ease;
    }

    .btn-transition:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection

@section('content')
<div class="min-h-screen" style="background-color: #FAFAFA;">
    <div class="container mx-auto px-4 py-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
            <div>
                <h1 class="text-2xl font-bold" style="color: #424242;">Change Scholar Password</h1>
                <p style="color: #757575;">Update password for {{ $scholar->first_name }} {{ $scholar->last_name }}</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('admin.scholars.show', $scholar->id) }}" class="px-4 py-2 text-white rounded-lg hover:opacity-90 text-center transition-all duration-300" style="background-color: #4A90E2;">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Scholar
                </a>
            </div>
        </div>

        <div class="max-w-2xl mx-auto">
            <div class="bg-white shadow-md rounded-lg overflow-hidden border" style="border-color: #E0E0E0;">
                <!-- Header with Scholar Info -->
                <div class="p-6 border-b" style="border-color: #E0E0E0; background-color: #F8F9FA;">
                    <div class="flex items-center">
                        <div class="h-16 w-16 rounded-full flex items-center justify-center mr-4 border" style="background-color: #F8BBD0; border-color: #F48FB1;">
                            @if($scholar->profile_photo)
                                <img src="{{ asset('images/' . $scholar->profile_photo) }}" alt="{{ $scholar->user->name }}" class="h-16 w-16 rounded-full object-cover">
                            @else
                                <i class="fas fa-user-graduate text-2xl" style="color: #C2185B;"></i>
                            @endif
                        </div>
                        <div>
                            <h2 class="text-xl font-bold" style="color: #424242;">{{ $scholar->first_name }} {{ $scholar->middle_name }} {{ $scholar->last_name }}</h2>
                            <p style="color: #757575;">{{ $scholar->user->email }}</p>
                            <span class="px-2 py-1 text-xs rounded-full mt-1 inline-block
                                @if($scholar->status == 'Active') text-white
                                @elseif($scholar->status == 'Inactive') text-white
                                @elseif($scholar->status == 'Completed') text-white
                                @else text-white @endif"
                                style="
                                @if($scholar->status == 'Active') background-color: #4CAF50; color: white;
                                @elseif($scholar->status == 'Inactive') background-color: #D32F2F; color: white;
                                @elseif($scholar->status == 'Completed') background-color: #4CAF50; color: white;
                                @else background-color: #FFCA28; color: #975A16; @endif">
                                {{ $scholar->status }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Change Password Form -->
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 p-4 rounded-lg border" style="background-color: #E8F5E9; border-color: #4CAF50; color: #4CAF50;">
                            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 p-4 rounded-lg border" style="background-color: #FFEBEE; border-color: #F44336; color: #C62828;">
                            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.scholars.update-password', $scholar->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Security Notice -->
                            <div class="p-4 rounded-lg border" style="background-color: #FFF3E0; border-color: #FF9800; color: #E65100;">
                                <div class="flex items-start">
                                    <i class="fas fa-shield-alt text-lg mr-3 mt-1"></i>
                                    <div>
                                        <h4 class="font-medium mb-2">Security Notice</h4>
                                        <ul class="text-sm space-y-1">
                                            <li>• This will change the scholar's login password</li>
                                            <li>• The scholar will be notified of this change</li>
                                            <li>• This action will be logged for security purposes</li>
                                            <li>• The scholar will not be required to change the password on next login</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium mb-2" style="color: #424242;">
                                    <i class="fas fa-key mr-2"></i>New Password
                                </label>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-opacity-50 @error('password') border-red-500 @enderror" 
                                       style="border-color: #E0E0E0; focus:border-color: #4CAF50;"
                                       placeholder="Enter new password (minimum 8 characters)"
                                       required>
                                @error('password')
                                    <p class="mt-2 text-sm" style="color: #D32F2F;">
                                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium mb-2" style="color: #424242;">
                                    <i class="fas fa-key mr-2"></i>Confirm New Password
                                </label>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-opacity-50" 
                                       style="border-color: #E0E0E0; focus:border-color: #4CAF50;"
                                       placeholder="Confirm new password"
                                       required>
                            </div>

                            <!-- Password Requirements -->
                            <div class="p-4 rounded-lg border" style="background-color: #F3E5F5; border-color: #9C27B0; color: #6A1B9A;">
                                <h4 class="font-medium mb-2">
                                    <i class="fas fa-info-circle mr-2"></i>Password Requirements
                                </h4>
                                <ul class="text-sm space-y-1">
                                    <li>• Minimum 8 characters long</li>
                                    <li>• Should contain a mix of letters, numbers, and symbols</li>
                                    <li>• Avoid using personal information</li>
                                    <li>• Should be unique and not previously used</li>
                                </ul>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 pt-4">
                                <button type="submit" 
                                        class="px-6 py-3 text-white rounded-lg hover:opacity-90 transition-all duration-300 btn-transition flex items-center justify-center" 
                                        style="background-color: #4CAF50;">
                                    <i class="fas fa-save mr-2"></i>
                                    Change Password
                                </button>
                                
                                <a href="{{ route('admin.scholars.show', $scholar->id) }}" 
                                   class="px-6 py-3 text-center rounded-lg border hover:opacity-90 transition-all duration-300 btn-transition" 
                                   style="border-color: #E0E0E0; color: #424242; background-color: #F5F5F5;">
                                    <i class="fas fa-times mr-2"></i>
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection