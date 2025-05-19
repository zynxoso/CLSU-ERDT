<div class="min-h-screen">
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-6 animate-fade-in">
            <h1 class="text-2xl font-bold text-gray-900">Manage Scholars</h1>
            <x-button
                href="{{ route('admin.scholars.create') }}"
                variant="primary"
                icon="fas fa-user-plus text-black"
                animate="true"
            >
                Add New Scholar
            </x-button>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 relative" role="alert">
                <p class="font-bold">Success!</p>
                <p>{!! session('success') !!}</p>
                <button class="absolute top-0 right-0 mt-4 mr-4 text-green-700 hover:text-green-900" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times" style="color: black;"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 relative" role="alert">
                <p class="font-bold">Error!</p>
                <p>{{ session('error') }}</p>
                <button class="absolute top-0 right-0 mt-4 mr-4 text-red-700 hover:text-red-900" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times" style="color: black;"></i>
                </button>
            </div>
        @endif

        <!-- Filters -->
        <div class="rounded-lg p-4 mb-6 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 animate-fade-in" style="animation-delay: 0.1s">
            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-sm font-medium text-gray-600 mb-1">Status</label>
                    <select id="status" wire:model.live="status" class="w-full border border-gray-200 rounded-md px-3 py-2 text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                        <option value="">All Statuses</option>
                        <option value="Active">Active</option>
                        <option value="Ongoing">Ongoing</option>
                        <option value="Completed">Completed</option>
                        <option value="Graduated">Graduated</option>
                        <option value="Terminated">Terminated</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="start_date" class="block text-sm font-medium text-gray-600 mb-1">Start Date</label>
                    <input type="month" id="start_date" wire:model.live="start_date_filter" class="w-full border border-gray-200 rounded-md px-3 py-2 text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200" min="2021-01" max="2025-12">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="search" class="block text-sm font-medium text-gray-600 mb-1">Search</label>
                    <div class="relative">
                        <input
                            type="text"
                            id="search"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Name, Email, or ID"
                            class="w-full border border-gray-200 rounded-md pl-10 pr-3 py-2 text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search" style="color: black;"></i>
                        </div>
                    </div>
                </div>
                <div class="flex items-end w-full sm:w-auto">
                    <x-button
                        type="button"
                        wire:click="filter"
                        variant="primary"
                        icon="fas fa-filter" style="color: white;"
                        animate="true"
                    >
                        Filter
                    </x-button>
                    <x-button
                        type="button"
                        wire:click="resetFilters"
                        variant="outline-secondary"
                        class="ml-2"
                        animate="true"
                    >
                        Reset
                    </x-button>
                </div>
            </div>
        </div>

        <!-- Loading indicator -->
        <div wire:loading class="w-full">
            <div class="flex justify-center items-center py-8">
                <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-200 border-t-blue-700"></div>
            </div>
        </div>

        <!-- Scholars Table -->
        <div class="rounded-lg overflow-hidden border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 animate-fade-in" style="animation-delay: 0.2s" wire:loading.class="opacity-50">
            @if(count($scholars) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead>
                            <tr>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Scholar</th>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Start Date</th>
                                <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>

                                <th scope="col" class="px-4 py-2 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach($scholars as $scholar)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center overflow-hidden shadow-sm border border-blue-100">
                                                @if($scholar->user->profile_photo)
                                                    <img src="{{ asset('storage/' . $scholar->user->profile_photo) }}" alt="{{ $scholar->user->name }}" class="h-8 w-8 rounded-full object-cover">
                                                @else
                                                    <i class="fas fa-user text-xs" style="color: black;"></i>
                                                @endif
                                            </div>
                                            <div class="ml-2">
                                                <div class="text-xs font-medium text-gray-600 truncate max-w-[120px]" title="{{ $scholar->user->name }}">{{ $scholar->user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-600">{{ $scholar->start_date ? date('M Y', strtotime($scholar->start_date)) : '-' }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full inline-flex items-center justify-center
                                            @if($scholar->status == 'Active' || $scholar->status == 'Ongoing') bg-green-100 text-green-800 border border-green-300
                                            @elseif($scholar->status == 'Inactive' || $scholar->status == 'Terminated') bg-red-100 text-red-800 border border-red-300
                                            @elseif($scholar->status == 'Completed' || $scholar->status == 'Graduated') bg-blue-100 text-blue-800 border border-blue-300
                                            @else bg-yellow-100 text-yellow-800 border border-yellow-300 @endif">
                                            @if($scholar->status == 'Active' || $scholar->status == 'Ongoing')
                                                <i class="fas fa-check-circle mr-1" style="color: #065f46;"></i>
                                            @elseif($scholar->status == 'Inactive' || $scholar->status == 'Terminated')
                                                <i class="fas fa-times-circle mr-1" style="color: #7f1d1d;"></i>
                                            @elseif($scholar->status == 'Completed' || $scholar->status == 'Graduated')
                                                <i class="fas fa-graduation-cap mr-1" style="color: #1e40af;"></i>
                                            @else
                                                <i class="fas fa-exclamation-circle mr-1" style="color: #92400e;"></i>
                                            @endif
                                            <span class="relative">{{ $scholar->status }}</span>
                                        </span>
                                    </td>

                                    <td class="px-2 py-2 whitespace-nowrap text-center">
                                        <div class="flex justify-center items-center space-x-3">
                                            <a href="{{ route('admin.scholars.show', $scholar->id) }}" class="text-blue-600 hover:text-blue-900 mr-3" title="View Scholar">
                                                <i class="fas fa-eye" style="color: black;"></i>
                                            </a>
                                            <a href="{{ route('admin.scholars.edit', $scholar->id) }}" class="text-blue-600 hover:text-blue-900 mr-3" title="Edit Scholar">
                                                <i class="fas fa-edit" style="color: black;"></i>
                                            </a>
                                            <!-- <button 
                                                type="button" 
                                                wire:click="$dispatch('openModal', { component: 'delete-confirmation-modal', arguments: { id: {{ $scholar->id }}, name: '{{ $scholar->full_name }}' } })"
                                                class="text-blue-600 hover:text-blue-900 mr-3"
                                                title="Delete Scholar"
                                            >
                                                <i class="fas fa-trash-alt" style="color: black;"></i>
                                            </button> -->
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12 animate-fade-in">
                    <div class="w-16 h-16 mx-auto bg-blue-50 rounded-full flex items-center justify-center mb-4 border border-blue-100 shadow-sm">
                        <i class="fas fa-user-graduate text-2xl" style="color: black;"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">No Scholars Found</h3>
                    <p class="text-gray-500 mb-6">There are no scholars matching your filter criteria.</p>
                    <x-button
                        href="{{ route('admin.scholars.create') }}"
                        variant="primary"
                        icon="fas fa-user-plus" style="color: black;"
                        animate="true"
                    >
                        Add New Scholar
                    </x-button>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        <div class="mt-6 animate-fade-in" style="animation-delay: 0.3s">
            {{ $scholars->links() }}
        </div>
    </div>

    <!-- Styles -->
    <style>
        /* Animations and transitions */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        /* Table row hover effect */
        tbody tr {
            transition: all 0.2s ease-in-out;
        }

        tbody tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* Button hover effects */
        .action-icon {
            transition: all 0.2s ease;
        }

        .action-icon:hover {
            transform: scale(1.2);
        }


    </style>
</div>
