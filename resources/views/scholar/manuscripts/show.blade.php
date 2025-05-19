@extends('layouts.app')

@section('title', 'Manuscript Details')

@section('content')
<div class="mb-6">
    <!-- <a href="{{ route('scholar.manuscripts.index') }}" class="text-blue-600 hover:text-blue-900">
        <i class="fas fa-arrow-left mr-2"></i> Back to Manuscripts
    </a> -->
    <h1 class="text-2xl font-bold text-gray-800 mt-2">{{ $manuscript->title }}</h1>
    <p class="text-gray-600 mt-1">Reference #: {{ $manuscript->reference_number ?? 'Not assigned yet' }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Manuscript Details -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm mb-6">
            <div class="flex justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Manuscript Information</h2>
                <div class="flex space-x-2">
                    @if(in_array($manuscript->status, ['Draft', 'Revision Requested']))
                        <a href="{{ route('scholar.manuscripts.edit', $manuscript->id) }}" class="px-3 py-1 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <form action="{{ route('scholar.manuscripts.submit', $manuscript->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="return confirm('Are you sure you want to submit this manuscript for review?')">
                                <i class="fas fa-paper-plane mr-1"></i> Submit
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="border-t border-gray-200 pt-4">
                <h3 class="font-medium text-gray-700 mb-2">Abstract</h3>
                <div class="bg-gray-50 p-4 rounded-lg mb-4 text-gray-700">
                    {{ Str::limit($manuscript->abstract, 400, '...') }}
                    @if(strlen($manuscript->abstract) > 400)
                        <button class="text-blue-600 hover:text-blue-800 text-sm mt-2 show-more" data-target="abstract-full">Show more</button>
                        <div id="abstract-full" class="hidden mt-2">
                            {{ $manuscript->abstract }}
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <h3 class="font-medium text-gray-700 mb-1">Manuscript Type</h3>
                        <p class="text-gray-600">{{ $manuscript->manuscript_type }}</p>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-700 mb-1">Status</h3>
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($manuscript->status == 'Published') bg-green-100 text-green-800
                            @elseif($manuscript->status == 'Rejected') bg-red-100 text-red-800
                            @elseif($manuscript->status == 'Under Review') bg-yellow-100 text-yellow-800
                            @else bg-blue-100 text-blue-600 @endif">
                            {{ $manuscript->status }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <h3 class="font-medium text-gray-700 mb-1">Co-Authors</h3>
                        <p class="text-gray-600">{{ $manuscript->co_authors ?? 'None' }}</p>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-700 mb-1">Keywords</h3>
                        <p class="text-gray-600">{{ $manuscript->keywords ?? 'None' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <h3 class="font-medium text-gray-700 mb-1">Created On</h3>
                        <p class="text-gray-600">{{ $manuscript->created_at->format('M d, Y, h:i A') }}</p>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-700 mb-1">Last Updated</h3>
                        <p class="text-gray-600">{{ $manuscript->updated_at->format('M d, Y, h:i A') }}</p>
                    </div>
                </div>

                @if($manuscript->admin_notes)
                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <h3 class="font-medium text-yellow-800 mb-1">Admin Notes</h3>
                    <p class="text-yellow-700">{{ Str::limit($manuscript->admin_notes, 250, '...') }}</p>
                    @if(strlen($manuscript->admin_notes) > 250)
                        <button class="text-blue-600 hover:text-blue-800 text-sm mt-2 show-more" data-target="notes-full">Show more</button>
                        <div id="notes-full" class="hidden mt-2">
                            <p class="text-yellow-700">{{ $manuscript->admin_notes }}</p>
                        </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <!-- Review History -->
        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Review History</h2>

            @if($manuscript->reviewComments && $manuscript->reviewComments->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($manuscript->reviewComments as $comment)
                        <li class="py-3">
                            <p class="font-medium text-gray-800">{{ $comment->user->name }}</p>
                            <p class="text-gray-600 text-sm">{{ $comment->comment }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $comment->created_at->format('M d, Y, h:i A') }}</p>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-center py-6">
                    <p class="text-gray-500">No review comments yet</p>
                </div>
            @endif
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
