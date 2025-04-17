@extends('layouts.app')

@section('title', 'Document Details - Admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('admin.documents.index') }}" class="mr-2 flex items-center text-gray-600 hover:text-blue-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="ml-1">Back to Documents</span>
            </a>
        </div>


        <div x-data="{ showGuide: false }" class="relative">
            <button @mouseenter="showGuide = true" @mouseleave="showGuide = false"
                    class="flex items-center space-x-1 px-3 py-1 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium">Help</span>
            </button>
            <div x-show="showGuide"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 class="absolute right-0 top-full mt-2 w-72 bg-white rounded-lg shadow-xl p-4 text-sm text-gray-600 z-50"
                 style="min-width: 320px;" x-cloak>
                <h3 class="text-blue-700 font-semibold mb-2">Document Review Guide</h3>
                <ol class="list-decimal pl-5 space-y-2">
                    <li>Review document details and check if the document matches the requirements</li>
                    <li>Verify document ownership by checking scholar information</li>
                    <li>For valid documents, use the <span class="font-medium text-green-600">Verify</span> button</li>
                    <li>For invalid documents, use the <span class="font-medium text-red-600">Reject</span> button and provide a reason</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left column - Document actions -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Status Card -->
                <div class="p-4 border-b">
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">Document Status</h2>
                    @if($document->verified_at)
                        <div class="flex items-center px-4 py-3 bg-green-50 text-green-700 rounded-md">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Verified</span>
                        </div>
                    @elseif($document->rejection_reason)
                        <div class="flex items-center px-4 py-3 bg-red-50 text-red-700 rounded-md">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Rejected</span>
                        </div>
                    @else
                        <div class="flex items-center px-4 py-3 bg-yellow-50 text-yellow-700 rounded-md">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Pending Review</span>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="p-4 space-y-3">
                    <div x-data="{ showTooltip: false }" class="relative">
                        <a href="{{ route('admin.documents.download', $document->id) }}"
                           @mouseenter="showTooltip = true"
                           @mouseleave="showTooltip = false"
                           class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download Document
                        </a>
                        <div x-show="showTooltip"
                             class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap z-50"
                             x-cloak>
                            Download the document to review its contents
                        </div>
                    </div>

                    @if(!$document->verified_at && !$document->rejection_reason)
                        <div class="grid grid-cols-2 gap-3">
                            <div x-data="{ showTooltip: false }" class="relative">
                                <form action="{{ route('admin.documents.verify', $document->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            @mouseenter="showTooltip = true"
                                            @mouseleave="showTooltip = false"
                                            class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Verify
                                    </button>
                                </form>
                                <div x-show="showTooltip"
                                     class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap z-50"
                                     x-cloak>
                                    Mark this document as verified and valid
                                </div>
                            </div>

                            <div x-data="{ showTooltip: false, showForm: false }" class="relative">
                                <button @click="showForm = !showForm"
                                        @mouseenter="showTooltip = true"
                                        @mouseleave="showTooltip = false"
                                        class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Reject
                                </button>
                                <div x-show="showTooltip"
                                     class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap z-50"
                                     x-cloak>
                                    Reject this document and provide a reason
                                </div>
                            </div>
                        </div>

                        <!-- Rejection Form -->
                        <div x-data="{ showForm: false }" x-show="showForm" class="mt-4 p-4 bg-gray-50 rounded-md" x-cloak>
                            <h3 class="text-md font-medium text-gray-800 mb-2">Rejection Reason</h3>
                            <form action="{{ route('admin.documents.reject', $document->id) }}" method="POST">
                                @csrf
                                <textarea name="rejection_reason"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          rows="3"
                                          placeholder="Please provide a reason for rejecting this document"
                                          required></textarea>
                                <div class="mt-3 flex justify-end">
                                    <button type="button" @click="showForm = false" class="mr-2 px-4 py-2 text-gray-600 bg-white rounded-md border border-gray-300 hover:bg-gray-50">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-4 py-2 text-white bg-red-600 rounded-md hover:bg-red-700">
                                        Submit Rejection
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right column - Document information -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Scholar Information -->
                <div class="p-4 border-b relative" x-data="{ showTooltip: false }">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Scholar Information</h2>
                        <button @mouseenter="showTooltip = true" @mouseleave="showTooltip = false" class="text-gray-400 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                        <div x-show="showTooltip"
                             class="absolute top-0 right-0 mt-10 mr-4 w-64 p-2 bg-gray-800 text-white text-xs rounded shadow-lg z-50"
                             x-cloak>
                            Verify that this document belongs to the correct scholar by checking their information
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                        <div>
                            <p class="text-sm text-gray-500">Name</p>
                            <p class="font-medium">{{ $document->scholar->user->name ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Program</p>
                            <p class="font-medium">{{ $document->scholar->program->name ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Institution</p>
                            <p class="font-medium">{{ $document->scholar->institution->name ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium">{{ $document->scholar->user->email ?? 'Unknown' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Document Information -->
                <div class="p-4 border-b relative" x-data="{ showTooltip: false }">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Document Information</h2>
                        <button @mouseenter="showTooltip = true" @mouseleave="showTooltip = false" class="text-gray-400 hover:text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                        <div x-show="showTooltip"
                             class="absolute top-0 right-0 mt-10 mr-4 w-64 p-2 bg-gray-800 text-white text-xs rounded shadow-lg z-50"
                             x-cloak>
                            Review document details including type, date, and file information
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                        <div>
                            <p class="text-sm text-gray-500">Document ID</p>
                            <p class="font-medium">{{ $document->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Date Uploaded</p>
                            <p class="font-medium">{{ $document->created_at->format('F d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Title</p>
                            <p class="font-medium">{{ $document->title }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Document Type</p>
                            <p class="font-medium">{{ $document->document_type->name ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">File Name</p>
                            <p class="font-medium">{{ $document->file_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">File Size</p>
                            <p class="font-medium">{{ $document->file_size_formatted }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Description</p>
                            <p class="font-medium">{{ $document->description ?: 'No description provided' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Rejection Information (if rejected) -->
                @if($document->rejection_reason)
                <div class="p-4 border-b bg-red-50">
                    <h2 class="text-lg font-semibold text-red-800 mb-2">Rejection Information</h2>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <p class="text-sm text-red-700">Rejected At</p>
                            <p class="font-medium">{{ $document->rejected_at->format('F d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-red-700">Rejected By</p>
                            <p class="font-medium">{{ $document->rejectedBy->name ?? 'System' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-red-700">Reason for Rejection</p>
                            <p class="font-medium">{{ $document->rejection_reason }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Verification Information (if verified) -->
                @if($document->verified_at)
                <div class="p-4 border-b bg-green-50">
                    <h2 class="text-lg font-semibold text-green-800 mb-2">Verification Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-green-700">Verified At</p>
                            <p class="font-medium">{{ $document->verified_at->format('F d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-green-700">Verified By</p>
                            <p class="font-medium">{{ $document->verifiedBy->name ?? 'System' }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Help Panel -->
                <div class="p-4 bg-blue-50">
                    <h2 class="text-md font-semibold text-blue-800 mb-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Document Verification Guidelines
                    </h2>
                    <div class="text-sm text-blue-700 space-y-2">
                        <p><span class="font-semibold">Verification:</span> Ensure the document is valid, legible, and meets all requirements before verifying.</p>
                        <p><span class="font-semibold">Rejection:</span> Always provide a clear reason for rejection to help the scholar understand what needs to be fixed.</p>
                        <p><span class="font-semibold">Best Practices:</span> Compare the document against official records and check for inconsistencies or signs of tampering.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    [x-cloak] { display: none !important; }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .absolute {
        position: absolute;
    }

    .bottom-full {
        bottom: 100%;
    }

    .left-1\/2 {
        left: 50%;
    }

    .transform {
        transform: translateX(-50%);
    }

    .mb-2 {
        margin-bottom: 0.5rem;
    }

    .px-3 {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }

    .py-1 {
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
    }

    .bg-gray-800 {
        background-color: rgba(31, 41, 55, 0.95);
    }

    .text-white {
        color: white;
    }

    .text-xs {
        font-size: 0.75rem;
    }

    .rounded {
        border-radius: 0.25rem;
    }

    .whitespace-nowrap {
        white-space: nowrap;
    }

    .z-50 {
        z-index: 50;
    }
</style>
@endsection
