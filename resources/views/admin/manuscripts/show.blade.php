@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <!-- Header with Back Button and Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <div class="flex items-center">
                <!-- <a href="{{ route('admin.manuscripts.index') }}" 
                   class="mr-4 p-2 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors duration-200">
                    <i class="fas fa-arrow-left text-gray-600"></i>
                </a> -->
                <div>
                    <h1 class="text-2xl md:text-2xl font-bold text-gray-800 whitespace-nowrap">Manuscript Details</h1>
                    <p class="text-gray-500">Reference: {{ $manuscript->reference_number }}</p>
                </div>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-2 w-full md:w-auto">
            @if(in_array($manuscript->status, ['Revision Requested', 'Under Review', 'Submitted']))
                <a href="{{ route('admin.manuscripts.edit', $manuscript->id) }}" 
                   class="flex items-center px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white rounded-lg transition-colors duration-200">
                    <i class="fas fa-edit mr-2 text-blue-100"></i> Edit Manuscript
                </a>
            @elseif($manuscript->status === 'Draft')
                <span class="flex items-center px-4 py-2 bg-gray-300 text-gray-600 rounded-lg cursor-not-allowed">
                    <i class="fas fa-lock mr-2 text-gray-500"></i> Edit (Restricted)
                </span>
            @endif
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Manuscript Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex justify-between items-start">
                        <h2 class="text-xl font-semibold text-gray-800">{{ $manuscript->title ?? '[Untitled Manuscript]' }}</h2>
                        <span class="px-3 py-1 text-sm font-medium rounded-full
                            @if($manuscript->status === 'Draft') bg-gray-100 text-gray-800
                            @elseif($manuscript->status === 'Submitted') bg-indigo-100 text-indigo-800
                            @elseif($manuscript->status === 'Under Review') bg-yellow-100 text-yellow-800
                            @elseif($manuscript->status === 'Revision Requested') bg-orange-100 text-orange-800
                            @elseif($manuscript->status === 'Accepted') bg-blue-100 text-blue-800
                            @elseif($manuscript->status === 'Rejected') bg-red-100 text-red-800
                            @elseif($manuscript->status === 'Published') bg-green-100 text-green-800
                            @endif">
                            {{ $manuscript->status }}
                        </span>
                    </div>
                    <p class="text-gray-500 mt-2">Type: {{ $manuscript->manuscript_type }}</p>
                </div>

                <!-- Abstract Section -->
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-medium text-gray-800 mb-3 flex items-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-400 text-blue-700 mr-3">
                            <i class="fas fa-file-alt text-sm"></i>
                        </span>
                        Abstract
                    </h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700 whitespace-pre-line">{{ $manuscript->abstract }}</p>
                    </div>
                </div>

                <div class="mb-4">
                    {{-- Keywords display removed as per request --}}
                </div>

                <!-- Documents Section -->
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-3 flex items-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full mr-3" style="background-color: #10b981;">
                            <i class="fas fa-paperclip text-sm text-white"></i>
                        </span>
                        Attached Documents
                    </h3>
                    @if($manuscript->documents->count() > 0)
                        <div class="space-y-2">
                            @foreach($manuscript->documents as $document)
                                <div class="flex items-center justify-between bg-gray-50 hover:bg-gray-100 p-3 rounded-lg border border-gray-100 transition-colors duration-200">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-pdf text-red-500 text-xl mr-3"></i>
                                        <span class="text-gray-700">{{ $document->title }}</span>
                                    </div>
                                    <a href="{{ route('admin.documents.download', $document->id) }}"
                                       class="p-2 rounded-full hover:bg-gray-200 transition-colors duration-200"
                                       title="Download document">
                                        <i class="fas fa-download text-gray-500 hover:text-blue-600"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">No documents attached</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Author Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full mr-3" style="background-color: #60a5fa;">
                            <i class="fas fa-user text-sm text-white"></i>
                        </span>
                        Author Information
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">Author</p>
                            <p class="font-medium text-gray-800">{{ $manuscript->scholarProfile?->user?->name ?? 'N/A' }}</p>
                        </div>
                        @if($manuscript->co_authors)
                        <div>
                            <p class="text-sm text-gray-500">Co-authors</p>
                            <p class="text-gray-700">{{ $manuscript->co_authors }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4 flex items-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full mr-3" style="background-color: #f59e0b;">
                            <i class="fas fa-history text-sm text-white"></i>
                        </span>
                        Timeline
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center mr-3 mt-0.5" style="background-color: #60a5fa;">
                                <i class="fas fa-plus text-blue-100 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Created</p>
                                <p class="text-sm text-gray-500">{{ $manuscript->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-3 mt-0.5" style="background-color: #60a5fa;">
                                <i class="fas fa-edit text-gray-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Last Updated</p>
                                <p class="text-sm text-gray-500">{{ $manuscript->updated_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Notes Card -->
            @if($manuscript->admin_notes)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-3 flex items-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full mr-3" style="background-color: #ef4444;">
                            <i class="fas fa-sticky-note text-sm text-white"></i>
                        </span>
                        Admin Notes
                    </h3>
                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100">
                        <p class="text-gray-700 whitespace-pre-line">{{ $manuscript->admin_notes }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .status-badge {
        @apply px-3 py-1 text-sm font-medium rounded-full;
    }
    .status-draft { @apply bg-gray-100 text-gray-800; }
    .status-submitted { @apply bg-indigo-100 text-indigo-800; }
    .status-review { @apply bg-yellow-100 text-yellow-800; }
    .status-revision { @apply bg-orange-100 text-orange-800; }
    .status-accepted { @apply bg-blue-100 text-blue-800; }
    .status-rejected { @apply bg-red-100 text-red-800; }
    .status-published { @apply bg-green-100 text-green-800; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add any necessary JavaScript here
    });
</script>
@endpush

@endsection