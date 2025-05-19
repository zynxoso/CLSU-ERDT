@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <div class="flex space-x-6 mb-4">
                <a href="{{ route('scholar.dashboard') }}"
                   class="group w-44 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 shadow-sm flex items-center justify-center border border-gray-200 transition-all duration-300 transform hover:scale-105 hover:shadow-md btn-transition">
                    <i class="fas fa-arrow-left mr-2 group-hover:translate-x-[-3px] transition-transform duration-300"></i>
                    <span>Back to Dashboard</span>
                </a>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">Notifications</h1>
            <p class="text-gray-500 mt-1">View all your notifications and updates</p>
        </div>

        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            @if(count($notifications) > 0)
                <div class="space-y-6">
                    @foreach($notifications as $notification)
                        <div class="flex items-start p-4 rounded-lg border border-gray-100 hover:bg-gray-50 transition-colors duration-200">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4"
                                 style="background-color: {{ $notification->type == 'profile_update' ? '#E1F0FF' : '#F0F4FD' }}">
                                @if($notification->type == 'profile_update')
                                    <i class="fas fa-user-edit text-blue-600 text-lg"></i>
                                @elseif($notification->type == 'document')
                                    <i class="fas fa-file-alt text-green-600 text-lg"></i>
                                @elseif($notification->type == 'fund_request')
                                    <i class="fas fa-money-bill-wave text-yellow-600 text-lg"></i>
                                @else
                                    <i class="fas fa-bell text-gray-600 text-lg"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between">
                                    <h3 class="font-semibold text-gray-800 text-lg">{{ $notification->title }}</h3>
                                    <span class="text-sm text-gray-500">{{ $notification->created_at->format('M d, Y - h:i A') }}</span>
                                </div>
                                <p class="text-gray-700 mt-2">{{ $notification->message }}</p>
                                @if($notification->link)
                                    <div class="mt-3">
                                        <a href="{{ $notification->link }}" 
                                           class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-md hover:bg-blue-100 transition-colors duration-200">
                                            <span>View Details</span>
                                            <i class="fas fa-chevron-right ml-2 text-xs"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bell-slash text-gray-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">No Notifications</h3>
                    <p class="text-gray-500">You don't have any notifications at the moment.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-transition {
        transition: all 0.3s ease;
    }

    .btn-transition:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
</style>
@endpush
