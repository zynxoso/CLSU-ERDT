@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mt-2">Notifications</h1>
            <p class="text-gray-500 mt-1">View all your notifications and updates</p>
        </div>

        <div class="bg-white rounded-md p-6 border border-gray-200 shadow-sm">
            @if(count($notifications) > 0)
                <div class="space-y-4">
                    @foreach($notifications as $notification)
                        <div class="flex items-start p-4 rounded-lg border hover:bg-gray-50 transition-colors duration-200
                            @if(!$notification->is_read) border-l-4 border-l-blue-500 bg-blue-50 @else border-gray-200 @endif">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4 flex-shrink-0
                                @if($notification->type == 'profile_update') bg-blue-100
                                @elseif($notification->type == 'fund_request') bg-green-100
                                @elseif($notification->type == 'document') bg-purple-100
                                @elseif($notification->type == 'manuscript') bg-indigo-100
                                @else bg-gray-100 @endif">
                                @if($notification->type == 'profile_update')
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                @elseif($notification->type == 'fund_request')
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                @elseif($notification->type == 'document')
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                @elseif($notification->type == 'manuscript')
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center space-x-3">
                                        <h3 class="font-semibold text-gray-800 text-lg">{{ $notification->title }}</h3>
                                        <!-- Category Badge -->
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($notification->type == 'profile_update') bg-blue-100 text-blue-700
                                            @elseif($notification->type == 'fund_request') bg-green-100 text-green-700
                                            @elseif($notification->type == 'document') bg-purple-100 text-purple-700
                                            @elseif($notification->type == 'manuscript') bg-indigo-100 text-indigo-700
                                            @else bg-gray-100 text-gray-700 @endif">
                                            @if($notification->type == 'profile_update') Profile Update
                                            @elseif($notification->type == 'fund_request') Fund Request
                                            @elseif($notification->type == 'document') Document
                                            @elseif($notification->type == 'manuscript') Manuscript
                                            @else General @endif
                                        </span>
                                        @if(!$notification->is_read)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                                New
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-sm text-gray-500 flex-shrink-0">{{ $notification->created_at->format('M d, Y - h:i A') }}</span>
                                </div>
                                <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $notification->message }}</p>
                                @if($notification->link)
                                    <div class="mt-4">
                                        <a href="{{ $notification->link }}"
                                           class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors duration-200 font-medium">
                                            <span>View Details</span>
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">No Notifications</h3>
                    <p class="text-gray-500">You don't have any notifications at the moment. You'll be notified about updates to your profile, fund requests, documents, and manuscripts.</p>
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
