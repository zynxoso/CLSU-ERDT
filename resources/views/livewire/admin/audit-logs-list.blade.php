<div>
    <!-- Filters -->
    <div class="bg-white rounded-lg p-4 mb-6 border border-gray-200 shadow-sm">
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="user" class="block text-sm font-medium text-gray-700 mb-1">User</label>
                    <input type="text" id="user" wire:model.debounce.300ms="user" placeholder="Search by name or email" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="action" class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                    <select id="action" wire:model="action" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Actions</option>
                        @foreach($actions as $actionOption)
                            <option value="{{ $actionOption }}">{{ ucfirst($actionOption) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="entityType" class="block text-sm font-medium text-gray-700 mb-1">Entity Type</label>
                    <select id="entityType" wire:model="entityType" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Entity Types</option>
                        @foreach($entityTypes as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="entityId" class="block text-sm font-medium text-gray-700 mb-1">Entity ID</label>
                    <input type="text" id="entityId" wire:model.debounce.300ms="entityId" placeholder="Entity ID" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label for="dateFrom" class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                    <input type="date" id="dateFrom" wire:model="dateFrom" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="dateTo" class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                    <input type="date" id="dateTo" wire:model="dateTo" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex items-end space-x-2">
                    <button wire:click="applyFilter" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-150 ease-in-out">
                        <i class="fas fa-filter mr-2" style="color: white !important;" ></i> Apply Filters
                    </button>
                    <button wire:click="clearFilters" class="px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition duration-150 ease-in-out">
                        <i class="fas fa-times mr-2" style="color: white !important;"></i> Clear
                    </button>
                </div>  
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="w-full flex justify-center items-center py-4">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
    </div>

    <!-- Audit Logs Table -->
    <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm" wire:loading.class.delay="opacity-50">
        @if(count($auditLogs) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entity</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" x-data="{ selectedRow: null }">
                        @foreach($auditLogs as $log)
                            <tr class="hover:bg-blue-50 transition-colors duration-150 cursor-pointer"
                                :class="{ 'bg-blue-50': selectedRow === {{ $log->id }} }"
                                @click="selectedRow = selectedRow === {{ $log->id }} ? null : {{ $log->id }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $log->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                            @if($log->user)
                                                <span class="text-sm font-medium text-gray-700">{{ substr($log->user->name, 0, 1) }}</span>
                                            @else
                                                <i class="fas fa-user text-gray-500"></i>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 truncate max-w-[150px]" title="{{ $log->user->name ?? 'System' }}">
                                                {{ $log->user ? Str::limit($log->user->name, 20, '...') : 'System' }}
                                            </div>
                                            @if($log->user)
                                                <div class="text-xs text-gray-500 truncate max-w-[150px]" title="{{ $log->user->email }}">
                                                    {{ Str::limit($log->user->email, 20, '...') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex justify-center">
                                        <span class="inline-flex justify-center items-center w-[100px] px-3 py-1.5 text-xs font-semibold rounded-full
                                            @if($log->action == 'create') bg-gray-600 text-white
                                            @elseif($log->action == 'update') bg-gray-500 text-white
                                            @elseif($log->action == 'delete') bg-gray-600 text-white
                                            @elseif($log->action == 'login') bg-gray-600 text-white
                                            @elseif($log->action == 'logout') bg-gray-600 text-white
                                            @else bg-gray-600 text-white @endif">
                                            {{ ucfirst($log->action) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div class="truncate max-w-xs" title="{{ $log->entity_type }}">
                                        {{ Str::limit($log->entity_type, 25, '...') }}
                                        @if($log->entity_id)
                                            <span class="text-gray-400">#{{ $log->entity_id }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $log->ip_address }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination - Light Mode Style -->
            <div class="px-6 py-4 bg-white border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        Showing {{ $auditLogs->firstItem() ?? 0 }} to {{ $auditLogs->lastItem() ?? 0 }} of {{ $auditLogs->total() }} results
                    </div>
                    <div>
                        @if ($auditLogs->hasPages())
                            <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
                                <div class="flex justify-between flex-1 sm:hidden">
                                    <span>
                                        @if ($auditLogs->onFirstPage())
                                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                                                {!! __('pagination.previous') !!}
                                            </span>
                                        @else
                                            <button wire:click="previousPage('page')" wire:loading.attr="disabled" rel="prev" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                                {!! __('pagination.previous') !!}
                                            </button>
                                        @endif
                                    </span>

                                    <span>
                                        @if ($auditLogs->hasMorePages())
                                            <button wire:click="nextPage('page')" wire:loading.attr="disabled" rel="next" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                                {!! __('pagination.next') !!}
                                            </button>
                                        @else
                                            <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                                                {!! __('pagination.next') !!}
                                            </span>
                                        @endif
                                    </span>
                                </div>

                                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                    <div>
                                        <span class="relative z-0 inline-flex rounded-md shadow-sm">
                                            {{-- Previous Page Link --}}
                                            @if ($auditLogs->onFirstPage())
                                                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                                    <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-l-md leading-5" aria-hidden="true">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </span>
                                            @else
                                                <button wire:click="previousPage('page')" rel="prev" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" aria-label="{{ __('pagination.previous') }}">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            @endif

                                            {{-- Pagination Elements --}}
                                            @php
                                                $currentPage = $auditLogs->currentPage();
                                                $lastPage = $auditLogs->lastPage();
                                                
                                                // Calculate the window of pages to show (max 5 pages)
                                                $window = 2; // Pages on each side of current page
                                                $startPage = max(1, $currentPage - $window);
                                                $endPage = min($lastPage, $currentPage + $window);
                                                
                                                // Adjust window to show 5 pages when possible
                                                if ($endPage - $startPage + 1 < 5 && $lastPage >= 5) {
                                                    if ($startPage == 1) {
                                                        $endPage = min($lastPage, 5);
                                                    } elseif ($endPage == $lastPage) {
                                                        $startPage = max(1, $lastPage - 4);
                                                    }
                                                }
                                            @endphp
                                            
                                            {{-- First Page Link --}}
                                            @if ($startPage > 1)
                                                <button wire:click="gotoPage(1, 'page')" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:bg-gray-50 focus:z-10 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150" aria-label="{{ __('Go to page 1') }}">
                                                    1
                                                </button>
                                                @if ($startPage > 2)
                                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5">...</span>
                                                @endif
                                            @endif
                                            
                                            {{-- Page Range --}}
                                            @for ($page = $startPage; $page <= $endPage; $page++)
                                                @if ($page == $currentPage)
                                                    <span aria-current="page">
                                                        <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-blue-600 border border-blue-600 cursor-default leading-5">{{ $page }}</span>
                                                    </span>
                                                @else
                                                    <button wire:click="gotoPage({{ $page }}, 'page')" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:bg-gray-50 focus:z-10 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                                        {{ $page }}
                                                    </button>
                                                @endif
                                            @endfor
                                            
                                            {{-- Last Page Link --}}
                                            @if ($endPage < $lastPage)
                                                @if ($endPage < $lastPage - 1)
                                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5">...</span>
                                                @endif
                                                <button wire:click="gotoPage({{ $lastPage }}, 'page')" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:bg-gray-50 focus:z-10 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150" aria-label="{{ __('Go to page :page', ['page' => $lastPage]) }}">
                                                    {{ $lastPage }}
                                                </button>
                                            @endif

                                            {{-- Next Page Link --}}
                                            @if ($auditLogs->hasMorePages())
                                                <button wire:click="nextPage('page')" rel="next" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150" aria-label="{{ __('pagination.next') }}">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            @else
                                                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                                    <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-r-md leading-5" aria-hidden="true">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </nav>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto bg-gray-500 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-history text-2xl text-black"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Audit Logs Found</h3>
                <p class="text-gray-500 mb-6">There are no audit logs matching your filter criteria.</p>
            </div>
        @endif
    </div>
</div>
