@extends('layouts.app')

@section('title', 'Document Details')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <a href="{{ route('scholar.documents.index') }}"
               class="group w-44 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 shadow-sm flex items-center justify-center border border-gray-200 transition-all duration-300 transform hover:scale-105 hover:shadow-md">
                <i class="fas fa-arrow-left mr-2 group-hover:translate-x-[-3px] transition-transform duration-300"></i>
                <span>Back to Documents</span>
            </a>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">Document Details</h1>
        </div>

        <!-- Status Card -->
        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="w-12 h-12 rounded-full
                        @if($document->is_verified) bg-green-500
                        @else bg-yellow-500 @endif
                        flex items-center justify-center mr-4">
                        <i class="fas
                            @if($document->is_verified) fa-check
                            @else fa-clock @endif"
                            style="color: white !important;"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <p class="text-lg font-bold
                            @if($document->is_verified) text-green-600
                            @else text-yellow-600 @endif">
                            {{ $document->is_verified ? 'Verified' : 'Pending Verification' }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('scholar.documents.download', $document->id) }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-300">
                        <i class="fas fa-download mr-2" style="color: white !important;"></i> Download Document
                    </a>
                </div>
            </div>
        </div>

        <!-- Document Details -->
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm mb-6">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Document Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm text-gray-500 mb-1">Title</h3>
                        <p class="text-gray-800">{{ $document->title }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-500 mb-1">Category</h3>
                        <p class="text-gray-800">{{ $document->category }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-500 mb-1">File Name</h3>
                        <p class="text-gray-800">{{ $document->file_name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-500 mb-1">File Size</h3>
                        <p class="text-gray-800">{{ number_format($document->file_size / 1024, 2) }} KB</p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-500 mb-1">Upload Date</h3>
                        <p class="text-gray-800">{{ $document->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-500 mb-1">Verification Status</h3>
                        <p class="text-gray-800">
                            @if($document->is_verified)
                                <span class="text-green-600">Verified</span> on {{ $document->verified_at->format('M d, Y') }}
                            @else
                                <span class="text-yellow-600">Pending Verification</span>
                            @endif
                        </p>
                    </div>
                    @if($document->description)
                    <div class="md:col-span-2">
                        <h3 class="text-sm text-gray-500 mb-1">Description</h3>
                        <p class="text-gray-800">{{ $document->description }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Document Preview -->
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm mb-6">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Document Preview</h2>
            </div>
            <div class="p-6">
                @if(in_array(pathinfo($document->file_name, PATHINFO_EXTENSION), ['pdf']))
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <embed src="{{ asset('storage/' . $document->file_path) }}" type="application/pdf" width="100%" height="600px" />
                    </div>
                @elseif(in_array(pathinfo($document->file_name, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                    <div class="bg-gray-100 p-4 rounded-lg text-center">
                        <img src="{{ asset('storage/' . $document->file_path) }}" alt="{{ $document->title }}" class="max-w-full h-auto mx-auto" style="max-height: 600px;">
                    </div>
                @else
                    <div class="bg-gray-100 p-8 rounded-lg text-center">
                        <i class="fas fa-file-alt text-gray-400 text-5xl mb-4"></i>
                        <p class="text-gray-600">Preview not available for this file type.</p>
                        <a href="{{ route('scholar.documents.download', $document->id) }}" class="mt-4 inline-block px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-300">
                            <i class="fas fa-download mr-2" style="color: white !important;"></i> Download to View
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
