<div class="min-h-screen">
    <div class="container mx-auto">


        <div class="bg-white border-b border-gray-200 shadow-sm mb-6">
            <div class="container mx-auto px-4 py-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">My Manuscripts</h1>
                    </div>
                    <a href="{{ route('scholar.manuscripts.create') }}" class="inline-flex items-center px-6 py-3 bg-[#4CAF50] hover:bg-[#43A047] text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        <span>New Manuscript</span>
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-[#4CAF50]/10 border-l-4 border-[#4CAF50] text-[#2E7D32] p-4 mb-4 relative" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-[#4CAF50] mr-2"></i>
                    <p class="font-bold">Success!</p>
                </div>
                <p class="mt-1">{!! session('success') !!}</p>
                <button class="absolute top-0 right-0 mt-4 mr-4 text-[#2E7D32] hover:text-[#1B5E20]" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-4 relative" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                    <p class="font-bold">Error!</p>
                </div>
                <p class="mt-1">{{ session('error') }}</p>
                <button class="absolute top-0 right-0 mt-4 mr-4 text-red-700 hover:text-red-900" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-lg p-4 mb-6 border border-gray-200 shadow-sm">
            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" wire:model.live="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#4CAF50] focus:border-[#4CAF50]">
                        <option value="">All Statuses</option>
                        <option value="Draft">Draft</option>
                        <option value="Submitted">Submitted</option>
                        <option value="Under Review">Under Review</option>
                        <option value="Revision Requested">Revision Requested</option>
                        <option value="Accepted">Accepted</option>
                        <option value="Published">Published</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="manuscript_type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select id="manuscript_type" wire:model.live="manuscript_type" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#4CAF50] focus:border-[#4CAF50]">
                        <option value="">All Types</option>
                        <option value="Outline">Outline</option>
                        <option value="Final">Final</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <div class="relative">
                        <input
                            type="text"
                            id="search"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search by title, abstract, co-authors, or keywords..."
                            class="w-full border border-gray-300 rounded-md pl-10 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#4CAF50] focus:border-[#4CAF50] placeholder-gray-500"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
                <div class="flex items-end">
                    <button
                        type="button"
                        wire:click="resetFilters"
                        class="px-4 py-2 bg-[#FFCA28] text-white rounded-lg hover:bg-[#FFB300] flex items-center space-x-2 transition-colors duration-200"
                    >
                        <i class="fas fa-undo-alt"></i>
                        <span>Reset</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading indicator -->
        <div wire:loading class="w-full">
            <div class="flex justify-center items-center py-8">
                <div class="animate-spin rounded-full h-10 w-10 border-4 border-[#4CAF50]/20 border-t-[#4CAF50]"></div>
            </div>
        </div>

        <!-- Manuscripts List -->
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm" wire:loading.class="opacity-50">
            @if(count($manuscripts) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($manuscripts as $manuscript)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        <div class="truncate max-w-xs cursor-help"
                                             title="{{ $manuscript->title }}"
                                             data-tooltip="{{ $manuscript->title }}">
                                            {{ Str::limit($manuscript->title, 50, '...') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $manuscript->manuscript_type }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $manuscript->updated_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 text-xs rounded-full font-medium
                                            @if($manuscript->status == 'Published') bg-[#4CAF50]/20 text-[#2E7D32] border border-[#4CAF50]/30
                                            @elseif($manuscript->status == 'Accepted') bg-[#4CAF50]/20 text-[#2E7D32] border border-[#4CAF50]/30
                                            @elseif($manuscript->status == 'Rejected') bg-red-200 text-red-900 border border-red-300
                                            @elseif($manuscript->status == 'Under Review') bg-[#FFCA28]/25 text-[#975A16] border border-[#FFCA28]/30
                                            @elseif($manuscript->status == 'Revision Requested') bg-orange-200 text-orange-900 border border-orange-300
                                            @elseif($manuscript->status == 'Submitted') bg-[#FFCA28]/25 text-[#975A16] border border-[#FFCA28]/30
                                            @else bg-[#B0BEC5]/25 text-[#546E7A] border border-[#B0BEC5]/30 @endif">
                                            {{ $manuscript->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('scholar.manuscripts.show', $manuscript->id) }}"
                                               class="text-[#4A90E2] hover:text-[#357ABD] flex items-center space-x-1 group">
                                                <span class="group-hover:underline">View</span>
                                            </a>
                                            @if($manuscript->status === 'Draft' || $manuscript->status === 'Revision Requested')
                                                <button
                                                    type="button"
                                                    wire:click="$dispatch('confirm-submit', { manuscriptId: {{ $manuscript->id }} })"
                                                    class="text-[#4CAF50] hover:text-[#43A047] flex items-center space-x-1 group"
                                                    title="Submit Manuscript"
                                                >
                                                    <span class="group-hover:underline">Submit</span>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto bg-[#4CAF50]/5 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-book text-2xl text-[#2E7D32]"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">No Manuscripts Yet</h3>
                    <p class="text-gray-600 mb-6">You haven't created any manuscripts yet.</p>
                    <a href="{{ route('scholar.manuscripts.create') }}" class="px-4 py-2 bg-[#4CAF50] hover:bg-[#43A047] text-white rounded-lg inline-flex items-center space-x-2">
                        <i class="fas fa-plus text-[#E8F5E9]"></i>
                        <span>Create Your First Manuscript</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($manuscripts->hasPages())
            <div class="mt-6">
                {{ $manuscripts->links() }}
            </div>
        @endif
    </div>

    <!-- SweetAlert2 for confirmation -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('confirm-submit', (event) => {
                Swal.fire({
                    title: 'Submit Manuscript?',
                    text: "Once submitted, the manuscript will be final and cannot be edited.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#16a34a',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirm'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.submitManuscript(event.manuscriptId);
                    }
                });
            });
        });
    </script>
</div>
