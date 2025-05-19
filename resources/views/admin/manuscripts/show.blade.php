@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manuscript Details</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.manuscripts.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
            @if(in_array($manuscript->status, ['Revision Requested', 'Under Review', 'Submitted']))
                <a href="{{ route('admin.manuscripts.edit', $manuscript->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
            @elseif($manuscript->status === 'Draft')
                <span class="px-4 py-2 bg-gray-200 text-gray-500 rounded-lg cursor-not-allowed shadow-sm" title="Draft manuscripts can only be edited by the scholar">
                    <i class="fas fa-lock mr-2" style="color: black;"></i>Edit (Restricted)
                </span>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h1 class="text-2xl font-bold mb-4">{{ $manuscript->title ?? '[Untitled Manuscript]' }}</h1>
                <div class="mb-4">
                    <span class="inline-flex justify-center min-w-[120px] px-3 py-1 text-sm leading-5 font-semibold rounded-full
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

                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Reference Number</h2>
                    <p class="text-gray-700">{{ $manuscript->reference_number }}</p>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Author</h2>
                    <p class="text-gray-700">{{ $manuscript->scholarProfile?->user?->name ?? 'N/A' }}</p>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Co-authors</h2>
                    <p class="text-gray-700">{{ $manuscript->co_authors ?: 'None' }}</p>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Type</h2>
                    <p class="text-gray-700">{{ $manuscript->manuscript_type }}</p>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Keywords</h2>
                    <p class="text-gray-700">{{ $manuscript->keywords ?: 'None' }}</p>
                </div>
            </div>

            <div>
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Abstract</h2>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <p class="text-gray-700 whitespace-pre-line">{{ Str::limit($manuscript->abstract, 500, '...') }}</p>
                        @if(strlen($manuscript->abstract) > 500)
                            <button class="text-blue-600 hover:text-blue-800 text-sm mt-2 show-more" data-target="abstract-full">Show more</button>
                            <div id="abstract-full" class="hidden mt-2">
                                <p class="text-gray-700 whitespace-pre-line">{{ $manuscript->abstract }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if($manuscript->admin_notes)
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Admin Notes</h2>
                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100">
                        <p class="text-gray-700 whitespace-pre-line">{{ Str::limit($manuscript->admin_notes, 300, '...') }}</p>
                        @if(strlen($manuscript->admin_notes) > 300)
                            <button class="text-blue-600 hover:text-blue-800 text-sm mt-2 show-more" data-target="notes-full">Show more</button>
                            <div id="notes-full" class="hidden mt-2">
                                <p class="text-gray-700 whitespace-pre-line">{{ $manuscript->admin_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Attached Documents</h2>
                    @if($manuscript->documents->count() > 0)
                        <div class="space-y-2">
                            @foreach($manuscript->documents as $document)
                                <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg border border-gray-100 hover:bg-gray-100 transition-colors duration-200">
                                    <span class="text-gray-700">{{ $document->title }}</span>
                                    <a href="{{ route('admin.documents.download', $document->id) }}"
                                       class="text-blue-600 hover:text-blue-700 p-2 rounded-full hover:bg-blue-50">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic">No documents attached</p>
                    @endif
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Timeline</h2>
                    <div class="space-y-4 bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-20 text-sm font-medium text-gray-500">Created</div>
                            <div class="ml-4 text-gray-700">{{ $manuscript->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-20 text-sm font-medium text-gray-500">Updated</div>
                            <div class="ml-4 text-gray-700">{{ $manuscript->updated_at->format('M d, Y h:i A') }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Status Actions -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Status</h2>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div class="flex flex-col space-y-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-24 text-sm font-medium text-gray-500">Current Status:</div>
                                <div class="ml-4">
                                    <span class="inline-flex justify-center px-3 py-1 text-sm font-semibold rounded-full
                                        @if($manuscript->status === 'Draft') bg-gray-600 text-white
                                        @elseif($manuscript->status === 'Submitted') bg-indigo-600 text-white
                                        @elseif($manuscript->status === 'Under Review') bg-yellow-500 text-white
                                        @elseif($manuscript->status === 'Revision Requested') bg-orange-500 text-white
                                        @elseif($manuscript->status === 'Accepted') bg-blue-600 text-white
                                        @elseif($manuscript->status === 'Rejected') bg-red-600 text-white
                                        @elseif($manuscript->status === 'Published') bg-green-600 text-white
                                        @endif">
                                        {{ $manuscript->status }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- <div class="pt-2">
                                <a href="{{ route('admin.manuscripts.edit', $manuscript->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                                    <i class="fas fa-edit mr-2" style="color: white;"></i> Edit Manuscript
                                </a>
                            </div> -->
                        </div>
                    </div>
                </div>
                
               
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const showMoreButtons = document.querySelectorAll('.show-more');

        showMoreButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const targetElement = document.getElementById(targetId);

                if (targetElement.classList.contains('hidden')) {
                    targetElement.classList.remove('hidden');
                    this.textContent = 'Show less';
                } else {
                    targetElement.classList.add('hidden');
                    this.textContent = 'Show more';
                }
            });
        });
    });
</script>
@endpush

@endsection
