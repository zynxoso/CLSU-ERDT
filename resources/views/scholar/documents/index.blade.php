@extends('layouts.app')

@section('title', 'My Documents')

@section('content')
<div class=" min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">My Documents</h1>
            <a href="{{ route('scholar.documents.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i> Upload Document
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg p-4 mb-6 border border-gray-200 shadow-sm">
            <form action="{{ route('scholar.documents.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Verified" {{ request('status') == 'Verified' ? 'selected' : '' }}>Verified</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Document Type</label>
                    <select id="type" name="type" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Types</option>
                        <option value="Certificate" {{ request('type') == 'Certificate' ? 'selected' : '' }}>Certificate</option>
                        <option value="Transcript" {{ request('type') == 'Transcript' ? 'selected' : '' }}>Transcript</option>
                        <option value="ID" {{ request('type') == 'ID' ? 'selected' : '' }}>ID</option>
                        <option value="Receipt" {{ request('type') == 'Receipt' ? 'selected' : '' }}>Receipt</option>
                        <option value="Other" {{ request('type') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                    <input type="month" id="date" name="date" value="{{ request('date') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-end w-full sm:w-auto">
                    <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Documents Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($documents as $document)
                <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-800 truncate">{{ $document->title }}</h3>
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($document->status == 'Verified') bg-green-100 text-green-800
                            @elseif($document->status == 'Rejected') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $document->status }}
                        </span>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                                <i class="fas fa-file-alt text-gray-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-700">{{ $document->type }}</p>
                                <p class="text-xs text-gray-500">{{ $document->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <div class="mb-3">
                            <p class="text-sm text-gray-500 mb-1">Description:</p>
                            <p class="text-sm text-gray-700">{{ Str::limit($document->description, 100) }}</p>
                        </div>

                        @if($document->admin_notes)
                            <div class="mb-3 p-2 bg-gray-100 rounded-lg">
                                <p class="text-sm text-gray-500 mb-1">Feedback:</p>
                                <p class="text-sm text-gray-700">{{ $document->admin_notes }}</p>
                            </div>
                        @endif

                        <div class="flex justify-between mt-4">
                            <a href="{{ route('scholar.documents.show', $document->id) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                <i class="fas fa-eye mr-1"></i> View
                            </a>
                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm">
                                <i class="fas fa-download mr-1"></i> Download
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-lg p-8 border border-gray-200 shadow-sm text-center">
                    <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-file-alt text-2xl text-gray-500"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">No Documents Found</h3>
                    <p class="text-gray-600 mb-6">You haven't uploaded any documents yet or none match your filter criteria.</p>
                    <a href="{{ route('scholar.documents.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i> Upload Document
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $documents->links() }}
        </div>
    </div>
</div>
@endsection
