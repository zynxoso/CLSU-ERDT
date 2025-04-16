@extends('layouts.app')

@section('title', 'Document Details')

@section('content')
<div class="bg-slate-900 min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <a href="{{ route('scholar.documents.index') }}" class="text-blue-400 hover:text-blue-300">
                <i class="fas fa-arrow-left mr-2"></i> Back to Documents
            </a>
            <h1 class="text-2xl font-bold text-white mt-2">Document Details</h1>
        </div>

        <!-- Status Card -->
        <div class="bg-slate-800 rounded-lg p-4 border border-slate-700 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="w-12 h-12 rounded-full
                        @if($document->status == 'Verified') bg-green-500 bg-opacity-20
                        @elseif($document->status == 'Rejected') bg-red-500 bg-opacity-20
                        @else bg-yellow-500 bg-opacity-20 @endif
                        flex items-center justify-center mr-4">
                        <i class="fas
                            @if($document->status == 'Verified') fa-check text-green-400
                            @elseif($document->status == 'Rejected') fa-times text-red-400
                            @else fa-clock text-yellow-400 @endif"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Status</p>
                        <p class="text-lg font-bold
                            @if($document->status == 'Verified') text-green-400
                            @elseif($document->status == 'Rejected') text-red-400
                            @else text-yellow-400 @endif">
                            {{ $document->status }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    @if($document->status == 'Rejected')
                        <a href="{{ route('scholar.documents.edit', $document->id) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                            <i class="fas fa-edit mr-2"></i> Update Document
                        </a>
                    @endif
                    <a href="{{ route('scholar.documents.download', $document->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-download mr-2"></i> Download
                    </a>
                </div>
            </div>
        </div>

        <!-- Document Details -->
        <div class="bg-slate-800 rounded-lg overflow-hidden border border-slate-700 mb-6">
            <div class="bg-slate-900 px-6 py-4">
                <h2 class="text-lg font-semibold text-white">Document Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm text-gray-400 mb-1">Document ID</h3>
                        <p class="text-white">DOC-{{ $document->id }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-400 mb-1">Date Uploaded</h3>
                        <p class="text-white">{{ $document->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-400 mb-1">Title</h3>
                        <p class="text-white">{{ $document->title }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-400 mb-1">Document Type</h3>
                        <p class="text-white">{{ $document->category }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-400 mb-1">File Name</h3>
                        <p class="text-white">{{ $document->file_name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-400 mb-1">File Type</h3>
                        <p class="text-white">{{ $document->file_type }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <h3 class="text-sm text-gray-400 mb-1">Description</h3>
                        <p class="text-white">{{ $document->description ?? 'No description provided.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Feedback (if any) -->
        @if($document->admin_notes)
        <div class="bg-slate-800 rounded-lg overflow-hidden border border-slate-700 mb-6">
            <div class="bg-slate-900 px-6 py-4">
                <h2 class="text-lg font-semibold text-white">Admin Feedback</h2>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <h3 class="text-sm text-gray-400 mb-1">Reviewed By</h3>
                    <p class="text-white">{{ $document->verified_by ? $document->verifiedBy->name : 'Not yet reviewed' }}</p>
                </div>
                <div>
                    <h3 class="text-sm text-gray-400 mb-1">Feedback</h3>
                    <p class="text-white">{{ $document->admin_notes }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Document Preview -->
        <div class="bg-slate-800 rounded-lg overflow-hidden border border-slate-700">
            <div class="bg-slate-900 px-6 py-4">
                <h2 class="text-lg font-semibold text-white">Document Preview</h2>
            </div>
            <div class="p-6">
                @if(in_array(strtolower(pathinfo($document->file_name, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                    <img src="{{ asset('storage/' . $document->file_path) }}" alt="{{ $document->title }}" class="max-w-full h-auto mx-auto rounded-lg">
                @elseif(strtolower(pathinfo($document->file_name, PATHINFO_EXTENSION)) == 'pdf')
                    <div class="aspect-w-16 aspect-h-9">
                        <iframe src="{{ asset('storage/' . $document->file_path) }}" class="w-full h-96 border-0 rounded-lg" allowfullscreen></iframe>
                    </div>
                @else
                    <div class="text-center py-10 bg-slate-700 rounded-lg">
                        <i class="fas fa-file-alt text-4xl text-gray-400 mb-4"></i>
                        <p class="text-white">Preview not available for this file type.</p>
                        <a href="{{ route('scholar.documents.download', $document->id) }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-download mr-2"></i> Download File
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
