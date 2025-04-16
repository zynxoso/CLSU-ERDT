@extends('layouts.app')

@section('title', 'Scholar Dashboard')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Welcome, {{ Auth::user()->name }}</h1>

<!-- Status Overview -->
<div class="bg-white rounded-lg p-6 mb-6 border border-gray-200 shadow-sm">
    <div class="flex flex-col md:flex-row items-center">
        <div class="w-full md:w-1/2 mb-6 md:mb-0 md:pr-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Scholarship Progress</h2>
            <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
                <div class="bg-blue-600 h-4 rounded-full" style="width: {{ $scholarProgress }}%"></div>
            </div>
            <div class="flex justify-between text-sm text-gray-600">
                <span>{{ $scholarProgress }}% Complete</span>
                <span>{{ $daysRemaining }} days remaining</span>
            </div>

            <div class="mt-4">
                <p class="text-gray-700 mb-2">
                    <span class="font-medium">Program:</span> {{ $scholar->program }}
                </p>
                <p class="text-gray-700 mb-2">
                    <span class="font-medium">Status:</span>
                    <span class="px-2 py-1 text-xs rounded-full
                        @if($scholar->status == 'Active') bg-green-100 text-green-800
                        @elseif($scholar->status == 'Inactive') bg-red-100 text-red-800
                        @elseif($scholar->status == 'Completed') bg-blue-100 text-blue-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ $scholar->status }}
                    </span>
                </p>
                <p class="text-gray-700">
                    <span class="font-medium">Expected Completion:</span> {{ $scholar->expected_completion_date ? date('M d, Y', strtotime($scholar->expected_completion_date)) : 'Not set' }}
                </p>
            </div>
        </div>

        <div class="w-full md:w-1/2 md:pl-6 md:border-l md:border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('scholar.fund-requests.create') }}" class="bg-white hover:bg-gray-50 p-4 rounded-lg text-center border border-gray-200 shadow-sm transition-all hover:shadow">
                    <i class="fas fa-money-bill-wave text-blue-600 text-2xl mb-2"></i>
                    <p class="text-gray-700 text-sm">Request Funds</p>
                </a>
                <a href="{{ route('scholar.documents.create') }}" class="bg-white hover:bg-gray-50 p-4 rounded-lg text-center border border-gray-200 shadow-sm transition-all hover:shadow">
                    <i class="fas fa-file-upload text-green-600 text-2xl mb-2"></i>
                    <p class="text-gray-700 text-sm">Upload Document</p>
                </a>
                <a href="{{ route('scholar.manuscripts.create') }}" class="bg-white hover:bg-gray-50 p-4 rounded-lg text-center border border-gray-200 shadow-sm transition-all hover:shadow">
                    <i class="fas fa-book text-purple-600 text-2xl mb-2"></i>
                    <p class="text-gray-700 text-sm">Add Manuscript</p>
                </a>
                <a href="{{ route('scholar.profile.edit') }}" class="bg-white hover:bg-gray-50 p-4 rounded-lg text-center border border-gray-200 shadow-sm transition-all hover:shadow">
                    <i class="fas fa-user-edit text-yellow-600 text-2xl mb-2"></i>
                    <p class="text-gray-700 text-sm">Update Profile</p>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity and Notifications -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Recent Activity -->
    <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Recent Activity</h2>
        </div>
        <div class="p-6">
            @if(count($recentActivities) > 0)
                <div class="space-y-4">
                    @foreach($recentActivities as $activity)
                        <div class="flex items-start">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-4">
                                @if($activity->type == 'fund_request')
                                    <i class="fas fa-money-bill-wave text-blue-600"></i>
                                @elseif($activity->type == 'document')
                                    <i class="fas fa-file-alt text-green-600"></i>
                                @elseif($activity->type == 'manuscript')
                                    <i class="fas fa-book text-purple-600"></i>
                                @else
                                    <i class="fas fa-bell text-yellow-600"></i>
                                @endif
                            </div>
                            <div>
                                <p class="text-gray-700">{{ $activity->description }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">View All Activity</a>
                </div>
            @else
                <div class="text-center py-6">
                    <p class="text-gray-500">No recent activity to display.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Notifications -->
    <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Notifications</h2>
            <span class="px-2 py-1 text-xs bg-blue-600 text-white rounded-full">{{ count($notifications) }}</span>
        </div>
        <div class="p-6">
            @if(count($notifications) > 0)
                <div class="space-y-4">
                    @foreach($notifications as $notification)
                        <div class="flex items-start {{ $notification->read_at ? 'opacity-60' : '' }}">
                            <div class="w-10 h-10 rounded-full {{ $notification->read_at ? 'bg-gray-100' : 'bg-blue-100' }} flex items-center justify-center mr-4">
                                <i class="fas fa-bell {{ $notification->read_at ? 'text-gray-600' : 'text-blue-600' }}"></i>
                            </div>
                            <div>
                                <p class="text-gray-700">{{ $notification->data['message'] }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">View All Notifications</a>
                </div>
            @else
                <div class="text-center py-6">
                    <p class="text-gray-500">No notifications to display.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Fund Request Summary and Document Status -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Fund Request Summary -->
    <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Fund Request Summary</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                    <p class="text-sm text-gray-600 mb-1">Total Approved</p>
                    <p class="text-xl font-bold text-gray-800">₱{{ number_format($totalApproved, 2) }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                    <p class="text-sm text-gray-600 mb-1">Pending Requests</p>
                    <p class="text-xl font-bold text-gray-800">{{ $pendingRequestsCount }}</p>
                </div>
            </div>

            <h3 class="text-sm font-medium text-gray-600 mb-2">Recent Requests</h3>
            @if(count($recentFundRequests) > 0)
                <div class="space-y-3">
                    @foreach($recentFundRequests as $request)
                        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm flex justify-between items-center">
                            <div>
                                <p class="text-gray-700">{{ $request->purpose }}</p>
                                <p class="text-xs text-gray-500">{{ $request->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-800 font-medium">₱{{ number_format($request->amount, 2) }}</p>
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($request->status == 'Approved') bg-green-100 text-green-800
                                    @elseif($request->status == 'Rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $request->status }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('scholar.fund-requests.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All Requests</a>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-gray-500">No fund requests yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Document Status -->
    <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Document Status</h2>
        </div>
        <div class="p-6">
            <div class="flex justify-between mb-6">
                <div class="text-center">
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                    <p class="text-sm text-gray-600">Verified</p>
                    <p class="text-xl font-bold text-gray-800">{{ $verifiedDocumentsCount }}</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-xl font-bold text-gray-800">{{ $pendingDocumentsCount }}</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-times text-red-600"></i>
                    </div>
                    <p class="text-sm text-gray-600">Rejected</p>
                    <p class="text-xl font-bold text-gray-800">{{ $rejectedDocumentsCount }}</p>
                </div>
            </div>

            <h3 class="text-sm font-medium text-gray-600 mb-2">Recent Documents</h3>
            @if(count($recentDocuments) > 0)
                <div class="space-y-3">
                    @foreach($recentDocuments as $document)
                        <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-file-alt text-gray-600"></i>
                                </div>
                                <div>
                                    <p class="text-gray-700">{{ $document->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $document->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($document->status == 'Verified') bg-green-100 text-green-800
                                @elseif($document->status == 'Rejected') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ $document->status }}
                            </span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('scholar.documents.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All Documents</a>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-gray-500">No documents uploaded yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Additional styling can be added here */
</style>
@endpush

@push('scripts')
<script>
    // Add any JavaScript for additional interactivity here
</script>
@endpush
