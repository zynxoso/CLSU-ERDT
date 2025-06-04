@extends('layouts.app')

@section('title', 'Fund Request Details')

@push('styles')
<style>
    .status-badge {
        @apply px-3 py-1 rounded-full text-sm font-medium;
    }
    .status-pending { @apply bg-yellow-300 text-yellow-900; }
    .status-approved { @apply bg-green-300 text-green-900; }
    .status-rejected { @apply bg-red-300 text-red-900; }
    .status-review { @apply bg-blue-300 text-blue-900; }
    .info-card {
        @apply bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-200 hover:shadow-md;
    }
    .info-label {
        @apply text-xs font-medium text-gray-500 uppercase tracking-wider mb-1;
    }
    .info-value {
        @apply text-gray-900 font-medium;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto">
    <!-- Header with Back Button -->
    <div class="flex items-center mb-6">
        <!-- <a href="{{ url()->previous() }}" class="mr-4 text-gray-600 hover:text-gray-900 flex items-center justify-center h-8 w-8 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a> -->
        <h1 class="text-2xl font-bold text-gray-900">Fund Request Details</h1>
    </div>

    <!-- Status Banner -->
    <div class="mb-6 rounded-lg overflow-hidden" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="flex items-start">
            <div class="flex-shrink-0 w-2"
                @if($fundRequest->status == 'Approved') style="background-color: #1a5f1a;"
                @elseif($fundRequest->status == 'Rejected') style="background-color: #7f1d1d;"
                @elseif($fundRequest->status == 'Under Review') style="background-color: #1e3a8a;"
                @else style="background-color: #854d0e;" @endif>
            </div>
            <div class="flex-1 p-4" 
                @if($fundRequest->status == 'Approved') style="background-color: #166534; color: #f0fdf4;"
                @elseif($fundRequest->status == 'Rejected') style="background-color: #991b1b; color: #fef2f2;"
                @elseif($fundRequest->status == 'Under Review') style="background-color: #1e40af; color: #eff6ff;"
                @else style="background-color: #854d0e; color: #fefce8;" @endif>
                <div class="flex items-start">
                    <div class="flex-shrink-0 pt-0.5">
                        <i class="fas text-lg mr-3"
                            @if($fundRequest->status == 'Approved') style="color: #86efac;"
                            @elseif($fundRequest->status == 'Rejected') style="color: #fca5a5;"
                            @elseif($fundRequest->status == 'Under Review') style="color: #93c5fd;"
                            @else style="color: #fde047;" @endif
                            @if($fundRequest->status == 'Approved') data-feather="check-circle"
                            @elseif($fundRequest->status == 'Rejected') data-feather="x-circle"
                            @elseif($fundRequest->status == 'Under Review') data-feather="search"
                            @else data-feather="clock" @endif>
                        </i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-base font-semibold mb-1" style="@if($fundRequest->status == 'Approved') color: #dcfce7;
                            @elseif($fundRequest->status == 'Rejected') color: #fee2e2;
                            @elseif($fundRequest->status == 'Under Review') color: #dbeafe;
                            @else color: #fef9c3; @endif">
                            Request {{ $fundRequest->status }}
                        </h3>
                        <p class="text-sm" style="@if($fundRequest->status == 'Approved') color: #bbf7d0;
                            @elseif($fundRequest->status == 'Rejected') color: #fecaca;
                            @elseif($fundRequest->status == 'Under Review') color: #bfdbfe;
                            @else color: #fef08a; @endif">
                            @if($fundRequest->status == 'Submitted')
                                Your request has been submitted and is pending review.
                            @elseif($fundRequest->status == 'Under Review')
                                Your request is currently being reviewed by our team.
                            @elseif($fundRequest->status == 'Approved')
                                Your request has been approved.
                            @else
                                Your request has been rejected.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Request Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Request Information Card -->
            <div class="info-card">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Request Information</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="info-label">Reference Number</p>
                            <p class="info-value">FR-{{ str_pad($fundRequest->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div>
                            <p class="info-label">Request Type</p>
                            <p class="info-value">{{ $fundRequest->requestType->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="info-label">Amount Requested</p>
                            <p class="info-value">₱{{ number_format($fundRequest->amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="info-label">Date Submitted</p>
                            <p class="info-value">{{ $fundRequest->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="info-label">Purpose</p>
                            <p class="info-value">{{ $fundRequest->purpose }}</p>
                        </div>
                        @if($fundRequest->admin_notes)
                        <div class="md:col-span-2 pt-4 border-t border-gray-100">
                            <p class="info-label">Admin Notes</p>
                            <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg mt-1">{{ $fundRequest->admin_notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Documents Section -->
            <div class="info-card">
                <div class="px-6 py-4 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-900">Supporting Documents</h2>
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                            {{ $fundRequest->documents->count() }} {{ Str::plural('Document', $fundRequest->documents->count()) }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    @if($fundRequest->documents->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($fundRequest->documents as $document)
                            <div class="border border-gray-100 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-150">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div class="flex items-center min-w-0">
                                        <div class="flex-shrink-0 bg-gray-300 p-2 rounded-lg mr-3">
                                            <i class="fas 
                                                @if(in_array(pathinfo($document->file_name, PATHINFO_EXTENSION), ['pdf']))
                                                    fa-file-pdf text-red-500
                                                @elseif(in_array(pathinfo($document->file_name, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                                    fa-file-image text-blue-500
                                                @elseif(in_array(pathinfo($document->file_name, PATHINFO_EXTENSION), ['doc', 'docx']))
                                                    fa-file-word text-blue-600
                                                @else
                                                    fa-file text-gray-500
                                                @endif
                                                text-lg"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate" title="{{ $document->file_name }}">
                                                {{ $document->file_name }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ strtoupper(pathinfo($document->file_name, PATHINFO_EXTENSION)) }} • 
                                                {{ number_format(filesize(storage_path('app/public/' . $document->file_path)) / 1024, 1) }} KB
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 flex space-x-2">
                                        <a href="{{ asset('storage/' . $document->file_path) }}" 
                                           target="_blank" 
                                           class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-eye mr-1.5" style="color: #374151;"></i> View
                                        </a>
                                        <a href="{{ asset('storage/' . $document->file_path) }}" 
                                           download
                                           class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-download mr-1.5" style="color: #fff;"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-folder-open text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">No supporting documents found for this request.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Actions & Timeline -->
        <div class="space-y-6">
            @if(Auth::user()->role == 'admin' && in_array($fundRequest->status, ['Submitted', 'Under Review']))
            <!-- Admin Actions Card -->
            <div class="info-card">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Request Actions</h2>
                </div>
                <div class="p-6 space-y-3">
                    @if($fundRequest->status == 'Submitted')
                    <button onclick="document.getElementById('under-review-modal').classList.remove('hidden')"
                            class="w-full flex items-center justify-between px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-150">
                        <div class="flex items-center">
                            <i class="fas fa-search mr-3 text-blue-100"></i>
                            <span class="font-medium text-white">Mark as Under Review</span>
                        </div>
                        <i class="fas fa-chevron-right text-blue-100"></i>
                    </button>
                    @endif
                    
                    <button onclick="document.getElementById('approve-modal').classList.remove('hidden')"
                            class="w-full flex items-center justify-between px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-150">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-3 text-green-100"></i>
                            <span class="font-medium text-white">Approve Request</span>
                        </div>
                        <i class="fas fa-chevron-right text-green-100"></i>
                    </button>

                    <button onclick="document.getElementById('reject-modal').classList.remove('hidden')"
                            class="w-full flex items-center justify-between px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-150">
                        <div class="flex items-center">
                            <i class="fas fa-times-circle mr-3 text-red-100"></i>
                            <span class="font-medium text-white">Reject Request</span>
                        </div>
                        <i class="fas fa-chevron-right text-red-100"></i>
                    </button>
                </div>
            </div>
            @endif

            <!-- Timeline Card -->
            <div class="info-card">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Request Timeline</h2>
                </div>
                <div class="p-6">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            <li class="relative pb-8">
                                <div class="relative flex items-start space-x-3">
                                    <div>
                                        <div class="relative px-1">
                                            <div class="h-8 w-8 bg-blue-400 rounded-full flex items-center justify-center ring-8 ring-white">
                                                <i class="fas fa-paper-plane text-blue-950"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5">
                                        <div class="flex justify-between">
                                            <p class="text-sm text-gray-700">Request submitted</p>
                                            <p class="text-xs text-gray-500">{{ $fundRequest->created_at->diffForHumans() }}</p>
                                        </div>
                                        <p class="text-xs text-gray-500">by {{ $fundRequest->user->name }}</p>
                                    </div>
                                </div>
                            </li>

                            @if($fundRequest->status == 'Under Review' || $fundRequest->status == 'Approved' || $fundRequest->status == 'Rejected')
                            <li class="relative pb-8">
                                <div class="relative flex items-start space-x-3">
                                    <div>
                                        <div class="relative px-1">
                                            <div class="h-8 w-8 bg-blue-400 rounded-full flex items-center justify-center ring-8 ring-white">
                                                <i class="fas fa-search text-blue-900"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5">
                                        <div class="flex justify-between">
                                            <p class="text-sm text-gray-700">Request under review</p>
                                            <p class="text-xs text-gray-500">{{ $fundRequest->updated_at->diffForHumans() }}</p>
                                        </div>
                                        <p class="text-xs text-gray-500">by Admin</p>
                                    </div>
                                </div>
                            </li>
                            @endif

                            @if($fundRequest->status == 'Approved')
                            <li class="relative">
                                <div class="relative flex items-start space-x-3">
                                    <div>
                                        <div class="relative px-1">
                                            <div class="h-8 w-8 bg-green-400 rounded-full flex items-center justify-center ring-8 ring-white">
                                                <i class="fas fa-check text-green-900"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5">
                                        <div class="flex justify-between">
                                            <p class="text-sm text-gray-700">Request approved</p>
                                            <p class="text-xs text-gray-500">{{ $fundRequest->updated_at->diffForHumans() }}</p>
                                        </div>
                                        @if($fundRequest->admin_notes)
                                        <p class="text-xs text-gray-500 mt-1">{{ $fundRequest->admin_notes }}</p>
                                        @endif
                                    </div>
                                </div>
                            </li>
                            @elseif($fundRequest->status == 'Rejected')
                            <li class="relative">
                                <div class="relative flex items-start space-x-3">
                                    <div>
                                        <div class="relative px-1">
                                            <div class="h-8 w-8 bg-red-400 rounded-full flex items-center justify-center ring-8 ring-white">
                                                <i class="fas fa-times text-red-900"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5">
                                        <div class="flex justify-between">
                                            <p class="text-sm text-gray-700">Request rejected</p>
                                            <p class="text-xs text-gray-500">{{ $fundRequest->updated_at->diffForHumans() }}</p>
                                        </div>
                                        @if($fundRequest->admin_notes)
                                        <p class="text-xs text-red-500 mt-1">{{ $fundRequest->admin_notes }}</p>
                                        @endif
                                    </div>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals (same as before but with improved styling) -->
@include('admin.fund-requests.modals.approve')
@include('admin.fund-requests.modals.reject')
@include('admin.fund-requests.modals.under-review')

@endsection

@push('scripts')
<script>
    // Close modals when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.classList.add('hidden');
        }
    });
</script>
@endpush