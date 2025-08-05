@extends('layouts.app')

@section('title', 'Manuscript Details')

@section('content')
    <div class="mb-6">
        <!-- <a href="{{ route('scholar.manuscripts.index') }}" class="text-red-800 hover:text-red-900 transition-colors duration-200">
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
                        @if (in_array($manuscript->status, ['Draft', 'Revision Requested']))
                            <a href="{{ route('scholar.manuscripts.edit', $manuscript->id) }}"
                                class="px-3 py-1 bg-[#4CAF50] text-white rounded-lg hover:bg-[#43A047] cursor-pointer">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <form id="submitForm{{ $manuscript->id }}"
                                action="{{ route('scholar.manuscripts.submit', $manuscript->id) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('PUT')
                                <button type="button" onclick="confirmSubmit({{ $manuscript->id }})"
                                    class="px-3 py-1 bg-[#4CAF50] text-white rounded-lg hover:bg-[#43A047] cursor-pointer"
                                    title="Submit Manuscript">
                                    <i class="fas fa-paper-plane mr-1"></i> Submit
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-4">
                    <div class="mb-6">
                        <h3 class="font-bold text-gray-800 mb-3 text-sm uppercase tracking-wide">Abstract</h3>
                        <div class="text-gray-700 leading-relaxed">
                            @if (strlen($manuscript->abstract) > 400)
                                <div id="abstract-preview">
                                    {{ Str::limit($manuscript->abstract, 400, '...') }}
                                    <button
                                        class="inline-flex items-center text-[#4A90E2] hover:text-[#357ABD] text-sm mt-2 font-medium cursor-pointer show-more"
                                        data-target="abstract-full">
                                        <i class="fas fa-expand-alt mr-1 text-xs"></i>
                                        Show more
                                    </button>
                                </div>
                                <div id="abstract-full" class="hidden">
                                    {{ $manuscript->abstract }}
                                    <button
                                        class="inline-flex items-center text-[#4A90E2] hover:text-[#357ABD] text-sm mt-2 font-medium cursor-pointer show-less"
                                        data-target="abstract-preview">
                                        <i class="fas fa-compress-alt mr-1 text-xs"></i>
                                        Show less
                                    </button>
                                </div>
                            @else
                                {{ $manuscript->abstract }}
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="flex justify-between items-start">
                            <span class="font-bold text-gray-800 text-sm">Manuscript Type:</span>
                            <span class="text-gray-600 text-right">{{ $manuscript->manuscript_type }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="font-bold text-gray-800 text-sm">Status:</span>
                            <span
                                class="px-3 py-1 text-xs font-medium rounded-full
                            @if ($manuscript->status == 'Published') bg-[#4CAF50]/20 text-[#2E7D32] border border-[#4CAF50]/30
                            @elseif($manuscript->status == 'Accepted') bg-[#4CAF50]/20 text-[#2E7D32] border border-[#4CAF50]/30
                            @elseif($manuscript->status == 'Rejected') bg-red-200 text-red-900 border border-red-300
                            @elseif($manuscript->status == 'Under Review') bg-[#FFCA28]/25 text-[#975A16] border border-[#FFCA28]/30
                            @elseif($manuscript->status == 'Revision Requested') bg-[#FFCA28]/25 text-[#975A16] border border-[#FFCA28]/30
                            @elseif($manuscript->status == 'Submitted') bg-[#FFCA28]/25 text-[#975A16] border border-[#FFCA28]/30
                            @else bg-[#B0BEC5]/25 text-[#546E7A] border border-[#B0BEC5]/30 @endif">
                                {{ $manuscript->status }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="flex justify-between items-start">
                            <span class="font-bold text-gray-800 text-sm">Co-Authors:</span>
                            <span class="text-gray-600 text-right">{{ $manuscript->co_authors ?? 'None' }}</span>
                        </div>
                        <div class="mb-4">
                            {{-- Keywords display removed as per request --}}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div class="flex justify-between items-start">
                            <span class="font-bold text-gray-800 text-sm">Created On:</span>
                            <span
                                class="text-gray-600 text-right">{{ $manuscript->created_at->format('M d, Y, h:i A') }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="font-bold text-gray-800 text-sm">Last Updated:</span>
                            <span
                                class="text-gray-600 text-right">{{ $manuscript->updated_at->format('M d, Y, h:i A') }}</span>
                        </div>
                    </div>

                    @if ($manuscript->admin_notes)
                        <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                            <h3 class="font-bold text-amber-800 mb-2 flex items-center">
                                <i class="fas fa-sticky-note mr-2"></i>
                                Admin Notes
                            </h3>
                            <div class="text-amber-700">
                                @if (strlen($manuscript->admin_notes) > 250)
                                    <div id="notes-preview">
                                        {{ Str::limit($manuscript->admin_notes, 250, '...') }}
                                        <button
                                            class="inline-flex items-center text-[#4A90E2] hover:text-[#357ABD] text-sm mt-2 font-medium cursor-pointer show-more"
                                            data-target="notes-full">
                                            <i class="fas fa-expand-alt mr-1 text-xs"></i>
                                            Show more
                                        </button>
                                    </div>
                                    <div id="notes-full" class="hidden">
                                        {{ $manuscript->admin_notes }}
                                        <button
                                            class="inline-flex items-center text-[#4A90E2] hover:text-[#357ABD] text-sm mt-2 font-medium cursor-pointer show-less"
                                            data-target="notes-preview">
                                            <i class="fas fa-compress-alt mr-1 text-xs"></i>
                                            Show less
                                        </button>
                                    </div>
                                @else
                                    {{ $manuscript->admin_notes }}
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Review History -->
            <div
                class="bg-white rounded-lg border border-gray-200 shadow-sm {{ $manuscript->reviewComments && $manuscript->reviewComments->count() > 0 ? 'p-6' : 'p-4' }}">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-history mr-2 text-gray-600"></i>
                    Review History
                </h2>

                @if ($manuscript->reviewComments && $manuscript->reviewComments->count() > 0)
                    <div class="space-y-4">
                        @foreach ($manuscript->reviewComments as $comment)
                            <div class="border-l-4 border-red-200 pl-4 py-2 bg-gray-50 rounded-r-lg">
                                <div class="flex justify-between items-start mb-1">
                                    <p class="font-semibold text-gray-800 text-sm">{{ $comment->user->name }}</p>
                                    <span class="text-xs text-gray-500">{{ $comment->created_at->format('M d, Y') }}</span>
                                </div>
                                <p class="text-gray-700 text-sm leading-relaxed">{{ $comment->comment }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $comment->created_at->format('h:i A') }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="mb-4">
                            <i class="fas fa-clipboard-list text-4xl text-gray-300"></i>
                        </div>
                        <p class="text-gray-500 mb-2 font-medium">No reviews yet</p>
                        <p class="text-gray-400 text-sm mb-4">Reviews will appear here once submitted by reviewers</p>
                        @if ($manuscript->status == 'Under Review')
                            <div
                                class="inline-flex items-center px-3 py-2 bg-[#FFCA28]/10 text-[#975A16] rounded-lg text-sm">
                                <i class="fas fa-clock mr-2"></i>
                                Review in progress
                            </div>
                        @elseif(in_array($manuscript->status, ['Draft', 'Revision Requested']))
                            <div
                                class="inline-flex items-center px-3 py-2 bg-[#4A90E2]/10 text-[#4A90E2] rounded-lg text-sm border border-[#4A90E2]/20">
                                <i class="fas fa-edit mr-2 text-[#4A90E2]"></i>
                                Submit for review to receive feedback
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const showMoreButtons = document.querySelectorAll('.show-more');
                const showLessButtons = document.querySelectorAll('.show-less');

                showMoreButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const targetId = this.getAttribute('data-target');
                        const targetElement = document.getElementById(targetId);
                        const currentElement = this.closest('div');

                        if (targetElement) {
                            currentElement.classList.add('hidden');
                            targetElement.classList.remove('hidden');
                        }
                    });
                });

                showLessButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const targetId = this.getAttribute('data-target');
                        const targetElement = document.getElementById(targetId);
                        const currentElement = this.closest('div');

                        if (targetElement) {
                            currentElement.classList.add('hidden');
                            targetElement.classList.remove('hidden');
                        }
                    });
                });
            });
        </script>
    @endpush

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmSubmit(manuscriptId) {
            console.log('confirmSubmit called with ID:', manuscriptId);

            // Check if SweetAlert is loaded
            if (typeof Swal === 'undefined') {
                console.error('SweetAlert2 is not loaded');
                // Fallback to regular confirm
                if (confirm('Submit Manuscript? Once submitted, the manuscript will be final and cannot be edited.')) {
                    document.getElementById('submitForm' + manuscriptId).submit();
                }
                return;
            }

            Swal.fire({
                title: 'Submit Manuscript?',
                text: "Once submitted, the manuscript will be final and cannot be edited.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4CAF50',
                cancelButtonColor: '#dc2626',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('submitForm' + manuscriptId);
                    if (form) {
                        form.submit();
                    } else {
                        console.error('Form not found:', 'submitForm' + manuscriptId);
                    }
                }
            }).catch((error) => {
                console.error('SweetAlert error:', error);
            });
        }
    </script>

@endsection
