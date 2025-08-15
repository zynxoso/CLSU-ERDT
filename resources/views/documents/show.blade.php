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
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                    <i class="fas fa-file text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="text-lg font-bold text-blue-600">Available</p>
                </div>
            </div>

            <div class="flex space-x-2">
                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank"
                   class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    <i class="fas fa-download mr-1"></i> Download
                </a>
            </div>
        </div>
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


</div>
@endsection
