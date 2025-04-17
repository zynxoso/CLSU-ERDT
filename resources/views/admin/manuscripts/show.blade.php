@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex justify-between items-center">
        <div class="flex items-center">
            <a href="{{ route('admin.manuscripts.index') }}" class="text-blue-600 hover:text-blue-700">
                <i class="fas fa-arrow-left mr-2"></i>Back to Manuscripts
            </a>
        </div>
        <div class="flex space-x-4">
            @if(in_array($manuscript->status, ['draft', 'revision_required']))
                <a href="{{ route('admin.manuscripts.edit', $manuscript->id) }}"
                   class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
            @endif
            @if($manuscript->status === 'submitted')
                <form action="{{ route('admin.manuscripts.approve', $manuscript->id) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                        <i class="fas fa-check mr-2"></i>Approve
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h1 class="text-2xl font-bold mb-4">{{ $manuscript->title }}</h1>
                <div class="mb-4">
                    <span class="inline-block px-3 py-1 rounded-full text-sm
                        @if($manuscript->status === 'draft') bg-gray-200 text-gray-800
                        @elseif($manuscript->status === 'submitted') bg-blue-200 text-blue-800
                        @elseif($manuscript->status === 'under_review') bg-yellow-200 text-yellow-800
                        @elseif($manuscript->status === 'revision_required') bg-orange-200 text-orange-800
                        @elseif($manuscript->status === 'accepted') bg-green-200 text-green-800
                        @elseif($manuscript->status === 'rejected') bg-red-200 text-red-800
                        @elseif($manuscript->status === 'published') bg-purple-200 text-purple-800
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $manuscript->status)) }}
                    </span>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Reference Number</h2>
                    <p class="text-gray-700">{{ $manuscript->reference_number }}</p>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Author</h2>
                    <p class="text-gray-700">{{ $manuscript->scholarProfile->user->name }}</p>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Co-authors</h2>
                    <p class="text-gray-700">{{ $manuscript->co_authors ?: 'None' }}</p>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Type</h2>
                    <p class="text-gray-700">{{ ucfirst($manuscript->manuscript_type) }}</p>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Keywords</h2>
                    <p class="text-gray-700">{{ $manuscript->keywords }}</p>
                </div>
            </div>

            <div>
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Abstract</h2>
                    <div class="bg-gray-50 p-4 rounded-lg">
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
                    <div class="bg-yellow-50 p-4 rounded-lg">
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
                                <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                                    <span class="text-gray-700">{{ $document->title }}</span>
                                    <a href="{{ route('admin.documents.download', $document->id) }}"
                                       class="text-blue-600 hover:text-blue-700">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No documents attached</p>
                    @endif
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Timeline</h2>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-12 text-sm text-gray-500">Created</div>
                            <div class="ml-4 text-gray-700">{{ $manuscript->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-12 text-sm text-gray-500">Updated</div>
                            <div class="ml-4 text-gray-700">{{ $manuscript->updated_at->format('M d, Y h:i A') }}</div>
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
