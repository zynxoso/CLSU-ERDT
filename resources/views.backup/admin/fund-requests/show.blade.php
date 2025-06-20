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
        background-color: #e0e5ec;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        border: 1px solid #d1d5db;
        overflow: hidden;
        transition: all 0.2s;
    }
    .info-card:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .info-label {
        color: #6b7280;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }
    .info-value {
        color: #1f2937;
        font-weight: 500;
    }
    .custom-bg {
        background-color: #f5f5f5;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto custom-bg min-h-screen">
    <!-- Header with Back Button -->
    <div class="flex items-center mb-6 pt-6">
        <!-- <a href="{{ url()->previous() }}" class="mr-4 text-gray-600 hover:text-gray-900 flex items-center justify-center h-8 w-8 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a> -->
        <h1 class="text-2xl font-bold" style="color: #1f2937;">Fund Request Details</h1>
    </div>

    <!-- Status Banner -->
    <div class="mb-6 rounded-lg overflow-hidden" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="flex items-start">
            <div class="flex-shrink-0 w-2"
                @if($fundRequest->status == 'Approved') style="background-color: #10b981;"
                @elseif($fundRequest->status == 'Rejected') style="background-color: #ef4444;"
                @elseif($fundRequest->status == 'Under Review') style="background-color: #f59e0b;"
                @else style="background-color: #6b7280;" @endif>
            </div>
            <div class="flex-1 p-4"
                @if($fundRequest->status == 'Approved') style="background-color: #10b981; color: white;"
                @elseif($fundRequest->status == 'Rejected') style="background-color: #ef4444; color: white;"
                @elseif($fundRequest->status == 'Under Review') style="background-color: #f59e0b; color: white;"
                @else style="background-color: #6b7280; color: white;" @endif>
                <div class="flex items-start">
                    <div class="flex-shrink-0 pt-0.5">
                        <i class="fas text-lg mr-3" style="color: rgba(255,255,255,0.9);"
                            @if($fundRequest->status == 'Approved') data-feather="check-circle"
                            @elseif($fundRequest->status == 'Rejected') data-feather="x-circle"
                            @elseif($fundRequest->status == 'Under Review') data-feather="search"
                            @else data-feather="clock" @endif>
                        </i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-base font-semibold mb-1" style="color: rgba(255,255,255,0.95);">
                            Request {{ $fundRequest->status }}
                        </h3>
                        <p class="text-sm" style="color: rgba(255,255,255,0.8);">
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
                <div class="px-6 py-4 border-b" style="border-color: #d1d5db;">
                    <h2 class="text-lg font-semibold" style="color: #1f2937;">Request Information</h2>
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
                        <div class="md:col-span-2 pt-4 border-t" style="border-color: #d1d5db;">
                            <p class="info-label">Admin Notes</p>
                            <p class="text-sm p-3 rounded-lg mt-1" style="color: #1f2937; background-color: #f3f4f6;">{{ $fundRequest->admin_notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Documents Section -->
            <div class="info-card">
                <div class="px-6 py-4 border-b" style="border-color: #d1d5db;">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-semibold" style="color: #1f2937;">Supporting Documents</h2>
                        <span class="px-2 py-1 text-xs font-medium rounded-full" style="background-color: #d1fae5; color: #065f46;">
                            {{ $fundRequest->documents->count() }} {{ Str::plural('Document', $fundRequest->documents->count()) }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    @if($fundRequest->documents->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($fundRequest->documents as $document)
                            <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors duration-150" style="border-color: #d1d5db; background-color: rgba(255,255,255,0.5);">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                    <div class="flex items-center min-w-0">
                                        <div class="flex-shrink-0 p-2 rounded-lg mr-3" style="background-color: #f3f4f6;">
                                            <i class="fas
                                                @if(in_array(pathinfo($document->file_name, PATHINFO_EXTENSION), ['pdf']))
                                                    fa-file-pdf
                                                @elseif(in_array(pathinfo($document->file_name, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                                    fa-file-image
                                                @elseif(in_array(pathinfo($document->file_name, PATHINFO_EXTENSION), ['doc', 'docx']))
                                                    fa-file-word
                                                @else
                                                    fa-file
                                                @endif
                                                text-lg" style="color: #10b981;"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium truncate" title="{{ $document->file_name }}" style="color: #1f2937;">
                                                {{ $document->file_name }}
                                            </p>
                                            <p class="text-xs" style="color: #6b7280;">
                                                {{ strtoupper(pathinfo($document->file_name, PATHINFO_EXTENSION)) }} •
                                                {{ number_format(filesize(storage_path('app/public/' . $document->file_path)) / 1024, 1) }} KB
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 flex space-x-2">
                                        <a href="{{ asset('storage/' . $document->file_path) }}"
                                           target="_blank"
                                           class="inline-flex items-center px-3 py-1.5 border text-xs font-medium rounded-md transition-colors duration-200"
                                           style="border-color: #d1d5db; color: #6b7280; background-color: white;"
                                           onmouseover="this.style.backgroundColor='#f3f4f6'"
                                           onmouseout="this.style.backgroundColor='white'">
                                            <i class="fas fa-eye mr-1.5" style="color: #6b7280;"></i> View
                                        </a>
                                        <a href="{{ asset('storage/' . $document->file_path) }}"
                                           download
                                           class="inline-flex items-center px-3 py-1.5 border-transparent text-xs font-medium rounded-md transition-colors duration-200"
                                           style="color: white; background-color: #10b981;"
                                           onmouseover="this.style.backgroundColor='#059669'"
                                           onmouseout="this.style.backgroundColor='#10b981'">
                                            <i class="fas fa-download mr-1.5" style="color: white;"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-folder-open text-4xl mb-3" style="color: #d1d5db;"></i>
                            <p style="color: #6b7280;">No supporting documents found for this request.</p>
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
                <div class="px-6 py-4 border-b" style="border-color: #d1d5db;">
                    <h2 class="text-lg font-semibold" style="color: #1f2937;">Actions</h2>
                </div>
                <div class="p-6 space-y-3">
                    <button onclick="document.getElementById('approve-modal').classList.remove('hidden')"
                            class="w-full flex items-center justify-center px-4 py-2 border-transparent rounded-md shadow-sm text-sm font-medium transition-colors duration-200"
                            style="color: white; background-color: #10b981;"
                            onmouseover="this.style.backgroundColor='#059669'"
                            onmouseout="this.style.backgroundColor='#10b981'">
                        <i class="fas fa-check-circle mr-2"></i> Approve Request
                    </button>
                    <button onclick="document.getElementById('reject-modal').classList.remove('hidden')"
                            class="w-full flex items-center justify-center px-4 py-2 border-transparent rounded-md shadow-sm text-sm font-medium transition-colors duration-200"
                            style="color: white; background-color: #ef4444;"
                            onmouseover="this.style.backgroundColor='#dc2626'"
                            onmouseout="this.style.backgroundColor='#ef4444'">
                        <i class="fas fa-times-circle mr-2"></i> Reject Request
                    </button>
                    <button onclick="document.getElementById('under-review-modal').classList.remove('hidden')"
                            class="w-full flex items-center justify-center px-4 py-2 rounded-md shadow-sm text-sm font-medium transition-colors duration-200"
                            style="color: #f59e0b; background-color: #fef3c7; border: 1px solid #f59e0b;"
                            onmouseover="this.style.backgroundColor='#fde68a'"
                            onmouseout="this.style.backgroundColor='#fef3c7'">
                        <i class="fas fa-search mr-2"></i> Set Under Review
                    </button>
                </div>
            </div>
            @endif

            <!-- Scholar Information Card -->
            <div class="info-card">
                <div class="px-6 py-4 border-b" style="border-color: #d1d5db;">
                    <h2 class="text-lg font-semibold" style="color: #1f2937;">Scholar Information</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 h-12 w-12 rounded-full flex items-center justify-center" style="background-color: #f3f4f6;">
                            @if($fundRequest->scholarProfile->user->profile_photo)
                                <img src="{{ asset('storage/' . $fundRequest->scholarProfile->user->profile_photo) }}"
                                     alt="{{ $fundRequest->scholarProfile->user->name }}"
                                     class="h-12 w-12 rounded-full">
                            @else
                                <i class="fas fa-user text-lg" style="color: #6b7280;"></i>
                            @endif
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium" style="color: #1f2937;">{{ $fundRequest->scholarProfile->user->name }}</p>
                            <p class="text-xs" style="color: #6b7280;">{{ $fundRequest->scholarProfile->user->email }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <p class="info-label">Program</p>
                            <p class="info-value">{{ $fundRequest->scholarProfile->program ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="info-label">Year Level</p>
                            <p class="info-value">{{ $fundRequest->scholarProfile->year_level ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="info-label">Contact Number</p>
                            <p class="info-value">{{ $fundRequest->scholarProfile->contact_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Request Timeline -->
            <div class="info-card">
                <div class="px-6 py-4 border-b" style="border-color: #d1d5db;">
                    <h2 class="text-lg font-semibold" style="color: #1f2937;">Request Timeline</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-2 h-2 rounded-full mt-2" style="background-color: #10b981;"></div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium" style="color: #1f2937;">Request Submitted</p>
                                <p class="text-xs" style="color: #6b7280;">{{ $fundRequest->created_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>

                        @if($fundRequest->status == 'Under Review')
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-2 h-2 rounded-full mt-2" style="background-color: #f59e0b;"></div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium" style="color: #1f2937;">Under Review</p>
                                <p class="text-xs" style="color: #6b7280;">{{ $fundRequest->updated_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($fundRequest->status == 'Approved')
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-2 h-2 rounded-full mt-2" style="background-color: #10b981;"></div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium" style="color: #1f2937;">Request Approved</p>
                                <p class="text-xs" style="color: #6b7280;">{{ $fundRequest->updated_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($fundRequest->status == 'Rejected')
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-2 h-2 rounded-full mt-2" style="background-color: #ef4444;"></div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium" style="color: #1f2937;">Request Rejected</p>
                                <p class="text-xs" style="color: #6b7280;">{{ $fundRequest->updated_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(Auth::user()->role == 'admin')
    @include('admin.fund-requests.modals.approve')
    @include('admin.fund-requests.modals.reject')
    @include('admin.fund-requests.modals.under-review')
@endif
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
