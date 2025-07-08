<div>
    <!-- Filters -->
    <div class="rounded-lg p-4 mb-6 border shadow-sm" style="background-color: white; border-color: #E0E0E0;">
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="user" class="block text-sm font-medium mb-1" style="color: #424242; font-size: 15px;">User</label>
                    <input type="text" id="user" wire:model.debounce.300ms="user" placeholder="Search by name or email"
                           class="w-full border rounded-md px-3 py-2"
                           style="background-color: white; border-color: #E0E0E0; color: #424242; font-size: 15px;">
                </div>

                <div>
                    <label for="action" class="block text-sm font-medium mb-1" style="color: #424242; font-size: 15px;">Action</label>
                    <select id="action" wire:model="action"
                            class="w-full border rounded-md px-3 py-2"
                            style="background-color: white; border-color: #E0E0E0; color: #424242; font-size: 15px;">
                        <option value="">All Actions</option>
                        @foreach($actions as $actionOption)
                            <option value="{{ $actionOption }}">{{ ucfirst($actionOption) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label for="dateFrom" class="block text-sm font-medium mb-1" style="color: #424242; font-size: 15px;">Date From</label>
                    <input type="date" id="dateFrom" wire:model="dateFrom"
                           class="w-full border rounded-md px-3 py-2"
                           style="background-color: white; border-color: #E0E0E0; color: #424242; font-size: 15px;">
                </div>

                <div>
                    <label for="dateTo" class="block text-sm font-medium mb-1" style="color: #424242; font-size: 15px;">Date To</label>
                    <input type="date" id="dateTo" wire:model="dateTo"
                           class="w-full border rounded-md px-3 py-2"
                           style="background-color: white; border-color: #E0E0E0; color: #424242; font-size: 15px;">
                </div>

                <div class="flex items-end space-x-2">
                    <button wire:click="applyFilter"
                            class="flex-1 px-4 py-2 rounded-lg"
                            style="background-color: #2E7D32; color: white; font-size: 15px;">
                        <i class="fas fa-filter mr-2" style="color: white !important;" ></i> Apply Filters
                    </button>
                    <button wire:click="clearFilters"
                            class="px-4 py-2 rounded-lg"
                            style="background-color: #757575; color: white; font-size: 15px;">
                        <i class="fas fa-times mr-2" style="color: white !important;"></i> Clear
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="w-full flex justify-center items-center py-4">
        <div class="rounded-full h-8 w-8 border-b-2" style="border-color: #2E7D32;"></div>
    </div>

    <!-- Audit Logs Table -->
    <div class="rounded-lg overflow-hidden border shadow-sm" style="background-color: white; border-color: #E0E0E0;" wire:loading.class.delay="opacity-50">
        @if(count($auditLogs) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y" style="border-color: #E0E0E0;">
                    <thead style="background-color: #F8F9FA;">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Timestamp</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">User</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Action</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="background-color: white; border-color: #E0E0E0;" x-data="{ selectedRow: null }">
                        @foreach($auditLogs as $log)
                            <tr class="cursor-pointer"
                                :class="{ 'bg-blue-50': selectedRow === {{ $log->id }} }"
                                @click="selectedRow = selectedRow === {{ $log->id }} ? null : {{ $log->id }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #757575;">{{ $log->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #757575;">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center" style="background-color: #F8BBD0;">
                                            @if($log->user)
                                                <span class="text-sm font-medium" style="color: #2E7D32;">{{ substr($log->user->name, 0, 1) }}</span>
                                            @else
                                                <i class="fas fa-user" style="color: #757575;"></i>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium truncate max-w-[150px]" style="color: #212121;" title="{{ $log->user->name ?? 'System' }}">
                                                {{ $log->user ? Str::limit($log->user->name, 20, '...') : 'System' }}
                                            </div>
                                            @if($log->user)
                                                <div class="text-xs truncate max-w-[150px]" style="color: #757575;" title="{{ $log->user->email }}">
                                                    {{ Str::limit($log->user->email, 20, '...') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex justify-center">
                                        <span class="inline-flex justify-center items-center w-[100px] px-3 py-1.5 text-xs font-semibold rounded-full
                                            @if($log->action == 'create') " style="background-color: #E8F5E8; color: #1B5E20;"
                                            @elseif($log->action == 'update') " style="background-color: #FFF3C4; color: #F57F17;"
                                            @elseif($log->action == 'delete') " style="background-color: #FFEBEE; color: #B71C1C;"
                                            @elseif($log->action == 'login') " style="background-color: #E3F2FD; color: #0D47A1;"
                                            @elseif($log->action == 'logout') " style="background-color: #FCE4EC; color: #880E4F;"
                                            @else " style="background-color: #E3F2FD; color: #0D47A1;" @endif">
                                            {{ ucfirst($log->action) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #757575;">{{ $log->ip_address }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination - Professional Style -->
            <div class="px-6 py-4 border-t" style="background-color: #F8F9FA; border-color: #E0E0E0;">
                <div class="flex justify-between items-center">
                    <div class="text-sm" style="color: #757575;">
                        Showing {{ $auditLogs->firstItem() ?? 0 }} to {{ $auditLogs->lastItem() ?? 0 }} of {{ $auditLogs->total() }} results
                    </div>
                    <div>
                        @if ($auditLogs->hasPages())
                            <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
                                <div class="flex justify-between flex-1 sm:hidden">
                                    <span>
                                        @if ($auditLogs->onFirstPage())
                                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium cursor-default leading-5 rounded-md" style="color: #9E9E9E; background-color: white; border: 1px solid #E0E0E0;">
                                                {!! __('pagination.previous') !!}
                                            </span>
                                        @else
                                            <button wire:click="previousPage('page')" wire:loading.attr="disabled" rel="prev" class="relative inline-flex items-center px-4 py-2 text-sm font-medium leading-5 rounded-md" style="color: #424242; background-color: white; border: 1px solid #E0E0E0;">
                                                {!! __('pagination.previous') !!}
                                            </button>
                                        @endif
                                    </span>

                                    <span>
                                        @if ($auditLogs->hasMorePages())
                                            <button wire:click="nextPage('page')" wire:loading.attr="disabled" rel="next" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium leading-5 rounded-md" style="color: #424242; background-color: white; border: 1px solid #E0E0E0;">
                                                {!! __('pagination.next') !!}
                                            </button>
                                        @else
                                            <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium cursor-default leading-5 rounded-md" style="color: #9E9E9E; background-color: white; border: 1px solid #E0E0E0;">
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
                                                    <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium cursor-default rounded-l-md leading-5" style="color: #9E9E9E; background-color: white; border: 1px solid #E0E0E0;" aria-hidden="true">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </span>
                                            @else
                                                <button wire:click="previousPage('page')" rel="prev" class="relative inline-flex items-center px-2 py-2 text-sm font-medium rounded-l-md leading-5" style="color: #757575; background-color: white; border: 1px solid #E0E0E0;" aria-label="{{ __('pagination.previous') }}">
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
                                                <button wire:click="gotoPage(1, 'page')" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium leading-5" style="color: #424242; background-color: white; border: 1px solid #E0E0E0;" aria-label="{{ __('Go to page 1') }}">
                                                    1
                                                </button>
                                                @if ($startPage > 2)
                                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium cursor-default leading-5" style="color: #424242; background-color: white; border: 1px solid #E0E0E0;">...</span>
                                                @endif
                                            @endif

                                            {{-- Page Range --}}
                                            @for ($page = $startPage; $page <= $endPage; $page++)
                                                @if ($page == $currentPage)
                                                    <span aria-current="page">
                                                        <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium cursor-default leading-5" style="color: white; background-color: #2E7D32; border: 1px solid #2E7D32;">{{ $page }}</span>
                                                    </span>
                                                @else
                                                    <button wire:click="gotoPage({{ $page }}, 'page')" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium leading-5" style="color: #424242; background-color: white; border: 1px solid #E0E0E0;" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                                        {{ $page }}
                                                    </button>
                                                @endif
                                            @endfor

                                            {{-- Last Page Link --}}
                                            @if ($endPage < $lastPage)
                                                @if ($endPage < $lastPage - 1)
                                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium cursor-default leading-5" style="color: #424242; background-color: white; border: 1px solid #E0E0E0;">...</span>
                                                @endif
                                                <button wire:click="gotoPage({{ $lastPage }}, 'page')" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium leading-5" style="color: #424242; background-color: white; border: 1px solid #E0E0E0;" aria-label="{{ __('Go to page :page', ['page' => $lastPage]) }}">
                                                    {{ $lastPage }}
                                                </button>
                                            @endif

                                            {{-- Next Page Link --}}
                                            @if ($auditLogs->hasMorePages())
                                                <button wire:click="nextPage('page')" rel="next" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium rounded-r-md leading-5" style="color: #757575; background-color: white; border: 1px solid #E0E0E0;" aria-label="{{ __('pagination.next') }}">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            @else
                                                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                                    <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium cursor-default rounded-r-md leading-5" style="color: #9E9E9E; background-color: white; border: 1px solid #E0E0E0;" aria-hidden="true">
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
                <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4" style="background-color: #F8F9FA;">
                    <i class="fas fa-history text-2xl" style="color: #757575;"></i>
                </div>
                <h3 class="text-lg font-medium mb-2" style="color: #212121;">No Audit Logs Found</h3>
                <p class="mb-6" style="color: #757575;">There are no audit logs matching your filter criteria.</p>
            </div>
        @endif
    </div>
</div>
