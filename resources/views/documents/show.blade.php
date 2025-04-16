@extends('layouts.app')

@section('title', 'Document Details')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.documents.index') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i> Back to Documents
            </a>
        @else
            <a href="{{ route('scholar.documents.index') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i> Back to Documents
            </a>
        @endif
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Document Details</h1>
    </div>

    <!-- Status Card -->
    <div class="bg-white rounded-lg p-4 border border-gray-200 shadow mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="w-12 h-12 rounded-full
                    @if($document->status == 'Verified') bg-green-100
                    @elseif($document->status == 'Rejected') bg-red-100
                    @else bg-yellow-100 @endif
                    flex items-center justify-center mr-4">
                    <i class="fas
                        @if($document->status == 'Verified') fa-check text-green-600
                        @elseif($document->status == 'Rejected') fa-times text-red-600
                        @else fa-clock text-yellow-600 @endif"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="text-lg font-bold
                        @if($document->status == 'Verified') text-green-600
                        @elseif($document->status == 'Rejected') text-red-600
                        @else text-yellow-600 @endif">
                        {{ $document->status }}
                    </p>
                </div>
            </div>

            <div class="flex space-x-2">
                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank"
                   class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    <i class="fas fa-download mr-1"></i> Download
                </a>

                @if(Auth::user()->role === 'admin' && ($document->status == 'Pending' || $document->status == 'Uploaded'))
                    <form action="{{ route('admin.documents.verify', $document->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            <i class="fas fa-check mr-1"></i> Verify
                        </button>
                    </form>

                    <button type="button"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                            onclick="document.getElementById('reject-form').classList.toggle('hidden')">
                        <i class="fas fa-times mr-1"></i> Reject
                    </button>
                @endif
            </div>
        </div>

        @if(Auth::user()->role === 'admin' && ($document->status == 'Pending' || $document->status == 'Uploaded'))
            <div id="reject-form" class="mt-4 hidden">
                <form action="{{ route('admin.documents.reject', $document->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-1">Reason for Rejection</label>
                        <textarea id="admin_notes" name="admin_notes" rows="3"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required></textarea>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Submit Rejection
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Document Details -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-lg font-medium text-gray-900">{{ $document->file_name }}</h2>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm text-gray-500 mb-1">Document ID</h3>
                    <p class="text-gray-900">DOC-{{ $document->id }}</p>
                </div>
                <div>
                    <h3 class="text-sm text-gray-500 mb-1">Date Uploaded</h3>
                    <p class="text-gray-900">{{ $document->created_at->format('M d, Y') }}</p>
                </div>
                @if($document->title)
                <div>
                    <h3 class="text-sm text-gray-500 mb-1">Title</h3>
                    <p class="text-gray-900">{{ $document->title }}</p>
                </div>
                @endif
                <div>
                    <h3 class="text-sm text-gray-500 mb-1">Document Type</h3>
                    <p class="text-gray-900">{{ $document->category }}</p>
                </div>
                <div>
                    <h3 class="text-sm text-gray-500 mb-1">File Name</h3>
                    <p class="text-gray-900">{{ $document->file_name }}</p>
                </div>
                <div>
                    <h3 class="text-sm text-gray-500 mb-1">File Size</h3>
                    <p class="text-gray-900">{{ $document->getFormattedFileSizeAttribute() }}</p>
                </div>
                <div class="md:col-span-2">
                    <h3 class="text-sm text-gray-500 mb-1">Description</h3>
                    <p class="text-gray-900">{{ $document->description ?? 'No description provided.' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Feedback (if any and if rejected) -->
    @if($document->status == 'Rejected' && $document->admin_notes)
    <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
        <div class="bg-red-50 border-b border-red-100 px-6 py-4">
            <h2 class="text-lg font-medium text-red-800">Rejection Reason</h2>
        </div>
        <div class="p-6">
            <p class="text-gray-900">{{ $document->admin_notes }}</p>
            @if($document->verified_at)
            <p class="text-sm text-gray-500 mt-4">Rejected on {{ $document->verified_at->format('M d, Y H:i') }}</p>
            @endif
        </div>
    </div>
    @endif

    <!-- Verification Information (if verified) -->
    @if($document->status == 'Verified' && $document->verified_at)
    <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
        <div class="bg-green-50 border-b border-green-100 px-6 py-4">
            <h2 class="text-lg font-medium text-green-800">Verification Information</h2>
        </div>
        <div class="p-6">
            <p class="text-gray-900">This document has been verified and is valid.</p>
            <p class="text-sm text-gray-500 mt-4">Verified on {{ $document->verified_at->format('M d, Y H:i') }}</p>
        </div>
    </div>
    @endif
</div>
@endsection
