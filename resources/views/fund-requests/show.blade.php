@extends('layouts.app')

@section('title', 'Fund Request Details')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('admin.fund-requests.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Fund Requests
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Fund Request Details</h1>
    </div>

    <!-- Status Card -->
    <div class="bg-white rounded-lg p-4 shadow mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="w-12 h-12 rounded-full
                    @if($fundRequest->status == 'Approved') bg-green-100
                    @elseif($fundRequest->status == 'Rejected') bg-red-100
                    @elseif($fundRequest->status == 'Submitted') bg-yellow-100
                    @else bg-blue-100 @endif
                    flex items-center justify-center mr-4">
                    <i class="fas
                        @if($fundRequest->status == 'Approved') fa-check text-green-600
                        @elseif($fundRequest->status == 'Rejected') fa-times text-red-600
                        @elseif($fundRequest->status == 'Submitted') fa-clock text-yellow-600
                        @else fa-edit text-blue-600 @endif"></i>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="text-lg font-bold
                        @if($fundRequest->status == 'Approved') text-green-600
                        @elseif($fundRequest->status == 'Rejected') text-red-600
                        @elseif($fundRequest->status == 'Submitted') text-yellow-600
                        @else text-blue-600 @endif">
                        {{ $fundRequest->status }}
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                @if(Auth::user()->role == 'admin' && in_array($fundRequest->status, ['Submitted', 'Under Review']))
                    <button type="button" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                        onclick="document.getElementById('approve-modal').classList.remove('hidden')">
                        <i class="fas fa-check mr-2"></i> Approve Request
                    </button>
                    <button type="button" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                        onclick="document.getElementById('reject-modal').classList.remove('hidden')">
                        <i class="fas fa-times mr-2"></i> Reject Request
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Request Details -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Request Information</h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Reference Number</p>
                    <p class="text-base font-medium">FR-{{ $fundRequest->id }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Amount</p>
                    <p class="text-base font-medium">₱{{ number_format($fundRequest->amount, 2) }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Request Type</p>
                    <p class="text-base font-medium">{{ $fundRequest->requestType->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Date Submitted</p>
                    <p class="text-base font-medium">{{ $fundRequest->created_at->format('F d, Y') }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500">Purpose</p>
                    <p class="text-base font-medium">{{ $fundRequest->purpose }}</p>
                </div>

                @if($fundRequest->admin_notes)
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500">Admin Notes</p>
                    <p class="text-base font-medium">{{ $fundRequest->admin_notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Scholar Information -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Scholar Information</h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Name</p>
                    <p class="text-base font-medium">{{ $fundRequest->scholarProfile->full_name ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">University</p>
                    <p class="text-base font-medium">{{ $fundRequest->scholarProfile->university ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Program</p>
                    <p class="text-base font-medium">{{ $fundRequest->scholarProfile->program ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Contact Number</p>
                    <p class="text-base font-medium">{{ $fundRequest->scholarProfile->contact_number ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="text-base font-medium">{{ $fundRequest->scholarProfile->user->email ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approve-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Approve Fund Request</h3>
        <p class="mb-4">Are you sure you want to approve this fund request?</p>

        <form action="{{ route('admin.fund-requests.approve', $fundRequest->id) }}" method="POST" class="mt-6">
            @csrf
            @method('PUT')

            <div class="flex justify-end gap-3">
                <button type="button" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                    onclick="document.getElementById('approve-modal').classList.add('hidden')">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    Approve
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="reject-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Reject Fund Request</h3>
        <p class="mb-4">Please provide a reason for rejecting this fund request:</p>

        <form action="{{ route('admin.fund-requests.reject', $fundRequest->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-1">Reason for Rejection</label>
                <textarea id="admin_notes" name="admin_notes" rows="4" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
                    onclick="document.getElementById('reject-modal').classList.add('hidden')">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Reject
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
