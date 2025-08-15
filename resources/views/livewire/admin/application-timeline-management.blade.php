<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Application Timeline Management</h1>
            <p class="text-gray-600">Manage application timeline items that appear on the public website</p>
        </div>
        <a href="{{ route('super_admin.application_timeline.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition-colors duration-200 inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add Timeline Item
        </a>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Timeline Items -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <!-- Header Section with Filters -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Timeline Items</h2>
            </div>

            <!-- Filters Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           id="search"
                           placeholder="Search activities, dates..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Status</label>
                    <select wire:model.live="statusFilter"
                            id="status_filter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <!-- Clear Filters -->
                <div class="flex items-end">
                    <button wire:click="resetFilters"
                            class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded text-sm transition-colors duration-200">
                        Clear Filters
                    </button>
                </div>
            </div>
        </div>

        @if($timelines->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th wire:click="sortBy('activity')"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200">
                                <div class="flex items-center space-x-1">
                                    <span>Activity</span>
                                    @if($sortBy === 'activity')
                                        <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'transform rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('first_semester')"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200">
                                <div class="flex items-center space-x-1">
                                    <span>First Semester</span>
                                    @if($sortBy === 'first_semester')
                                        <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'transform rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('second_semester')"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200">
                                <div class="flex items-center space-x-1">
                                    <span>Second Semester</span>
                                    @if($sortBy === 'second_semester')
                                        <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'transform rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('sort_order')"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200">
                                <div class="flex items-center space-x-1">
                                    <span>Sort Order</span>
                                    @if($sortBy === 'sort_order')
                                        <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'transform rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($timelines as $timeline)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $timeline->activity }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700">{{ $timeline->first_semester }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700">{{ $timeline->second_semester }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700">{{ $timeline->sort_order }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button wire:click="toggleStatus({{ $timeline->id }})"
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-colors duration-200 {{ $timeline->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                        {{ $timeline->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('super_admin.application_timeline.edit', $timeline->id) }}"
                                       class="text-blue-600 hover:text-blue-900 transition-colors duration-200 inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <button wire:click="confirmDelete({{ $timeline->id }})"
                                            class="text-red-600 hover:text-red-900 transition-colors duration-200 inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($timelines->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $timelines->links() }}
            </div>
            @endif
        @else
            <div class="px-6 py-12 text-center">
                <div class="text-gray-400 text-lg mb-2">
                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 0H4a2 2 0 00-2 2v6a2 2 0 002 2h16a2 2 0 002-2v-6a2 2 0 00-2-2h-4" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Timeline Items Found</h3>
                <p class="text-gray-600 mb-4">
                    @if($search || $statusFilter)
                        No timeline items match your current filters. Try adjusting your search criteria.
                    @else
                        Get started by creating your first timeline item.
                    @endif
                </p>
                @if(!$search && !$statusFilter)
                <a href="{{ route('super_admin.application_timeline.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Timeline Item
                </a>
                @endif
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal && $timelineToDelete)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeDeleteModal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" wire:click.stop>
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Delete Timeline Item</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to delete <strong>{{ $timelineToDelete->activity }}</strong>? This action cannot be undone.
                    </p>
                </div>
                <div class="flex justify-center space-x-4 px-4 py-3">
                    <button wire:click="closeDeleteModal"
                            class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors duration-200">
                        Cancel
                    </button>
                    <button wire:click="deleteTimeline"
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors duration-200">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Loading Spinner -->
    <div wire:loading class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-4 rounded-lg shadow-lg">
            <div class="flex items-center space-x-2">
                <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-600">Loading...</span>
            </div>
        </div>
    </div>
</div>
