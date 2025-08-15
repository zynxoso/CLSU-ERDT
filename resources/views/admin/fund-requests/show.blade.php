@extends('layouts.app')

@section('title', 'Fund Request Details')
@push('styles')
    <style>
        .info-card{background-color:rgb(255 255 255);border-radius:0.75rem;box-shadow:0 1px 3px 0 rgba(0,0,0,0.1);border:1px solid #E0E0E0;overflow:hidden;}
        .info-label{color:rgb(115 115 115);font-size:0.75rem;font-weight:500;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.25rem;}
        .info-value{color:rgb(64 64 64);font-weight:500;}
        .custom-bg{background-color:#FAFAFA;}
    </style>
@endpush
@section('content')
    <div class="container mx-auto custom-bg min-h-screen">
        <!-- Header with Back Button -->
        <div class="flex items-center mb-6">
            <h1 class="text-2xl font-bold" style="color: rgb(23 23 23);">Fund Request Details</h1>
        </div>

        <!-- Status Banner -->
        <div class="mb-6 rounded-lg overflow-hidden" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-2"
                    @if($fundRequest->status == 'Approved') style="background-color: rgb(34 197 94);"
                    @elseif($fundRequest->status == 'Rejected') style="background-color: #D32F2F;"
                    @elseif($fundRequest->status == 'Under Review') style="background-color: rgb(251 191 36);"
                    @else style="background-color: rgb(115 115 115);" @endif>
                </div>
                <div class="flex-1 p-4"
                    @if($fundRequest->status == 'Approved') style="background-color: rgb(34 197 94); color: rgb(255 255 255);"
                    @elseif($fundRequest->status == 'Rejected') style="background-color: #D32F2F; color: rgb(255 255 255);"
                    @elseif($fundRequest->status == 'Under Review') style="background-color: rgb(251 191 36); color: #975A16;"
                    @else style="background-color: rgb(115 115 115); color: rgb(255 255 255);" @endif>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 pt-0.5">
                            <i class="fas text-lg mr-3
                                @if($fundRequest->status == 'Approved') fa-check-circle
                                @elseif($fundRequest->status == 'Rejected') fa-times-circle
                                @elseif($fundRequest->status == 'Under Review') fa-search
                                @else fa-clock @endif"
                                style="color: rgba(255,255,255,0.9);">
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
                    <div class="px-6 py-4 border-b" style="border-color: rgb(224 224 224);">
                        <h2 class="text-lg font-semibold" style="color: rgb(64 64 64);">Request Information</h2>
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
                            @if($fundRequest->rejection_reason)
                            <div class="md:col-span-2 pt-4 border-t" style="border-color: rgb(224 224 224);">
                                <p class="info-label">Rejection Reason</p>
                                <p class="text-sm p-3 rounded-lg mt-1" style="color: rgb(64 64 64); background-color: #F5F5F5;">{{ $fundRequest->rejection_reason }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Documents Section -->
                <div class="info-card">
                    <div class="px-6 py-4 border-b" style="border-color: rgb(224 224 224);">
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-semibold" style="color: rgb(64 64 64);">Supporting Documents</h2>
                            <span class="px-2 py-1 text-xs font-medium rounded-full" style="background-color: rgba(76, 175, 80, 0.1); color: rgb(34 197 94);">
                                {{ $fundRequest->documents->count() }} {{ Str::plural('Document', $fundRequest->documents->count()) }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($fundRequest->documents->isNotEmpty())
                            <div class="space-y-4">
                                @foreach($fundRequest->documents as $document)
                                <div class="border rounded-lg p-4" style="border-color: rgb(224 224 224); background-color: #FAFAFA;">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                        <div class="flex items-center min-w-0">
                                            <div class="flex-shrink-0 p-2 rounded-lg mr-3" style="background-color: rgb(34 197 94);">
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
                                                    text-lg text-white"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium truncate" title="{{ $document->file_name }}" style="color: rgb(64 64 64);">
                                                    {{ $document->file_name }}
                                                </p>
                                                <p class="text-xs" style="color: rgb(115 115 115);">
                                                    {{ strtoupper(pathinfo($document->file_name, PATHINFO_EXTENSION)) }} •
                                                    {{ number_format(filesize(storage_path('app/public/' . $document->file_path)) / 1024, 1) }} KB
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 flex space-x-2">
                                            <a href="{{ route('admin.documents.view', $document->id) }}"
                                            target="_blank"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md"
                                            style="color: rgb(255 255 255); background-color: rgb(59 130 246);">
                                                <i class="fas fa-eye mr-1.5" style="color: rgb(255 255 255);"></i> View
                                            </a>
                                            <a href="{{ route('admin.documents.download', $document->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md"
                                            style="color: rgb(255 255 255); background-color: rgb(34 197 94);">
                                                <i class="fas fa-download mr-1.5" style="color: rgb(255 255 255);"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-folder-open text-4xl mb-3" style="color: #E0E0E0;"></i>
                                <p style="color: rgb(115 115 115);">No supporting documents found for this request.</p>
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
                    <div class="px-6 py-4 border-b" style="border-color: rgb(224 224 224);">
                        <h2 class="text-lg font-semibold" style="color: rgb(64 64 64);">Actions</h2>
                    </div>
                    <div class="p-6">
                        <!-- Validation Info -->
                        <div class="mb-4 p-3 rounded-lg" style="background-color: #E3F2FD; border: 1px solid #90CAF9;">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle mr-2 mt-0.5" style="color: #4A90E2;"></i>
                                <div class="text-sm" style="color: #1565C0;">
                                    <p class="font-medium mb-1">Validation Status</p>
                                    <p>This request has passed all validation checks and is ready for review.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                        <button onclick="document.getElementById('approve-modal').classList.remove('hidden')"
                                class="w-full flex items-center justify-center px-4 py-2 rounded-md shadow-sm text-sm font-medium"
                                style="color: rgb(255 255 255); background-color: rgb(34 197 94);">
                            <i class="fas fa-check-circle mr-2"></i> Approve Request
                        </button>
                        <button onclick="document.getElementById('reject-modal').classList.remove('hidden')"
                                class="w-full flex items-center justify-center px-4 py-2 rounded-md shadow-sm text-sm font-medium"
                                style="color: rgb(255 255 255); background-color: #D32F2F;">
                            <i class="fas fa-times-circle mr-2"></i> Reject Request
                        </button>
                        <button onclick="document.getElementById('under-review-modal').classList.remove('hidden')"
                                class="w-full flex items-center justify-center px-4 py-2 rounded-md shadow-sm text-sm font-medium"
                                style="color: #975A16; background-color: rgb(251 191 36);">
                            <i class="fas fa-search mr-2"></i> Set Under Review
                        </button>
                    </div>
                </div>
                @endif

                <!-- Scholar Information Card -->
                <div class="info-card">
                    <div class="px-6 py-4 border-b" style="border-color: rgb(224 224 224);">
                        <h2 class="text-lg font-semibold" style="color: rgb(64 64 64);">Scholar Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 h-12 w-12 rounded-full flex items-center justify-center" style="background-color: rgb(34 197 94);">
                                @if($fundRequest->scholarProfile->profile_photo)
                                    <img src="{{ asset('images/' . $fundRequest->scholarProfile->profile_photo) }}"
                                        alt="{{ $fundRequest->scholarProfile->user->name }}"
                                        class="h-12 w-12 rounded-full">
                                @else
                                    <i class="fas fa-user text-lg text-white" ></i>
                                @endif
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium" style="color: rgb(64 64 64);">{{ $fundRequest->scholarProfile->user->name }}</p>
                                <p class="text-xs" style="color: rgb(115 115 115);">{{ $fundRequest->scholarProfile->user->email }}</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <p class="info-label">Department</p>
                            <p class="info-value">{{ $fundRequest->scholarProfile->department ?? 'N/A' }}</p>
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
                    <div class="px-6 py-4 border-b" style="border-color: rgb(224 224 224);">
                        <h2 class="text-lg font-semibold" style="color: rgb(64 64 64);">Request Timeline</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-2 h-2 rounded-full mt-2" style="background-color: rgb(59 130 246);"></div>
                                <div class="ml-4 flex-1">
                                    <p class="text-sm font-medium" style="color: rgb(64 64 64);">Request Submitted</p>
                                    <p class="text-xs" style="color: rgb(115 115 115);">{{ $fundRequest->created_at->format('M d, Y \a\t g:i A') }}</p>
                                </div>
                            </div>

                            @if($fundRequest->status == 'Under Review')
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-2 h-2 rounded-full mt-2" style="background-color: rgb(251 191 36);"></div>
                                <div class="ml-4 flex-1">
                                    <p class="text-sm font-medium" style="color: rgb(64 64 64);">Under Review</p>
                                    <p class="text-xs" style="color: rgb(115 115 115);">{{ $fundRequest->updated_at->format('M d, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                            @endif

                            @if($fundRequest->status == 'Approved')
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-2 h-2 rounded-full mt-2" style="background-color: rgb(34 197 94);"></div>
                                <div class="ml-4 flex-1">
                                    <p class="text-sm font-medium" style="color: rgb(64 64 64);">Request Approved</p>
                                    <p class="text-xs" style="color: rgb(115 115 115);">{{ $fundRequest->updated_at->format('M d, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                            @endif

                            @if($fundRequest->status == 'Rejected')
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-2 h-2 rounded-full mt-2" style="background-color: #D32F2F;"></div>
                                <div class="ml-4 flex-1">
                                    <p class="text-sm font-medium" style="color: rgb(64 64 64);">Request Rejected</p>
                                    <p class="text-xs" style="color: rgb(115 115 115);">{{ $fundRequest->updated_at->format('M d, Y \a\t g:i A') }}</p>
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
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/admin-fund-request-validation.js') }}"></script>
@endpush
