@extends('layouts.app')

@section('title', 'Fund Request Details')

@section('content')
<div class="min-h-screen">
    <div class="container mx-autO">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mt-2">Fund Request Details</h1>
        </div>

        <!-- Status Card -->
        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="w-12 h-12 rounded-full
                        @if($fundRequest->status == 'Approved') bg-green-500
                        @elseif($fundRequest->status == 'Rejected') bg-red-500
                        @elseif($fundRequest->status == 'Submitted') bg-yellow-500
                        @else bg-blue-500 @endif
                        flex items-center justify-center mr-4">
                        <i class="fas
                            @if($fundRequest->status == 'Approved') fa-check
                            @elseif($fundRequest->status == 'Rejected') fa-times
                            @elseif($fundRequest->status == 'Submitted') fa-clock
                            @else fa-edit @endif"
                            style="color: white !important;"></i>
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
                    @if($fundRequest->status == 'Draft')
                        <a href="{{ route('scholar.fund-requests.edit', $fundRequest->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors duration-300">
                            <i class="fas fa-edit mr-2" style="color: white !important;"></i> Edit Request
                        </a>
                        <form action="{{ route('scholar.fund-requests.submit', $fundRequest->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-300">
                                <i class="fas fa-paper-plane mr-2" style="color: white !important;"></i> Submit Request
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Fund Request Details -->
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm mb-6">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Request Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm text-gray-500 mb-1">Request ID</h3>
                        <p class="text-gray-800">FR-{{ $fundRequest->id }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-500 mb-1">Date Requested</h3>
                        <p class="text-gray-800">{{ $fundRequest->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-500 mb-1">Amount</h3>
                        <p class="text-xl font-bold text-gray-800">₱{{ number_format($fundRequest->amount, 2) }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-500 mb-1">Purpose</h3>
                        <p class="text-gray-800">{{ $fundRequest->purpose }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <h3 class="text-sm text-gray-500 mb-1">Details</h3>
                        <p class="text-gray-800">{{ $fundRequest->details ?? 'No additional details provided.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Feedback (if any) -->
        @if($fundRequest->admin_notes)
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm mb-6">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Admin Feedback</h2>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <h3 class="text-sm text-gray-500 mb-1">Reviewed By</h3>
                    <p class="text-gray-800">{{ $fundRequest->reviewedBy ? $fundRequest->reviewedBy->name : 'Not yet reviewed' }}</p>
                </div>
                <div>
                    <h3 class="text-sm text-gray-500 mb-1">Notes</h3>
                    <p class="text-gray-800">{{ $fundRequest->admin_notes }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Supporting Documents -->
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm mb-6">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Supporting Documents</h2>
            </div>
            <div class="p-6">
                @if($fundRequest->documents->isNotEmpty())
                    @foreach($fundRequest->documents as $document)
                        <div class="border border-gray-200 rounded-lg p-4 mb-4">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                                <div>
                                    <h3 class="text-sm text-gray-500 mb-1">Document Name</h3>
                                    <p class="text-gray-800 font-medium">{{ $document->file_name }}</p>
                                </div>
                                <div class="mt-2 md:mt-0">
                                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-300 inline-flex items-center">
                                        <i class="fas fa-external-link-alt mr-2" style="color: white !important;"></i> Open in New Tab
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Document Preview -->
                            <div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
                                @if(in_array(pathinfo($document->file_name, PATHINFO_EXTENSION), ['pdf']))
                                    <div class="bg-gray-100 p-4 text-center">
                                        <embed src="{{ asset('storage/' . $document->file_path) }}" type="application/pdf" width="100%" height="500px" />
                                    </div>
                                @elseif(in_array(pathinfo($document->file_name, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                    <div class="bg-gray-100 p-4 text-center">
                                        <img src="{{ asset('storage/' . $document->file_path) }}" alt="{{ $document->file_name }}" class="max-w-full h-auto mx-auto" style="max-height: 500px;">
                                    </div>
                                @else
                                    <div class="bg-gray-100 p-4 text-center">
                                        <p class="text-gray-600">Preview not available for this file type. Please click "Open in New Tab" to view the document.</p>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Document Status -->
                            <div class="mt-4">
                                <h3 class="text-sm text-gray-500 mb-1">Status</h3>
                                <p class="text-gray-800 {{ $document->is_verified ? 'text-green-600' : 'text-yellow-600' }}">
                                    {{ $document->is_verified ? 'Verified' : 'Pending Verification' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-6">
                        <p class="text-gray-500">No supporting documents have been uploaded for this fund request.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
