<div>
    <!-- Filters -->
    <div class="bg-white border border-gray-300 rounded-lg p-4 mb-6 shadow-sm">
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="user" class="block text-sm font-medium text-gray-600 mb-1">User</label>
                    <input type="text" id="user" wire:model.debounce.300ms="user"
                        placeholder="Search by name or email"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-600">
                </div>

                <div>
                    <label for="action" class="block text-sm font-medium text-gray-600 mb-1">Action</label>
                    <select id="action" wire:model="action"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-600">
                        <option value="">All Actions</option>
                        @foreach ($actions as $actionOption)
                            <option value="{{ $actionOption }}">{{ ucfirst($actionOption) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label for="dateFrom" class="block text-sm font-medium text-gray-600 mb-1">Date From</label>
                    <input type="date" id="dateFrom" wire:model="dateFrom"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-600">
                </div>

                <div>
                    <label for="dateTo" class="block text-sm font-medium text-gray-600 mb-1">Date To</label>
                    <input type="date" id="dateTo" wire:model="dateTo"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-gray-600">
                </div>

                <div class="flex items-end space-x-2">
                    <button wire:click="applyFilter"
                        class="flex-1 bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800">
                        <i class="fas fa-filter mr-2"></i> Apply Filters
                    </button>
                    <button wire:click="clearFilters"
                        class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                        <i class="fas fa-times mr-2"></i> Clear
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="w-full flex justify-center items-center py-4">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-700"></div>
    </div>

    <!-- Audit Logs Table -->
    <div class="bg-white border border-gray-300 rounded-lg overflow-hidden shadow-sm"
        wire:loading.class.delay="opacity-50">
        @if (count($auditLogs) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Timestamp</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300">
                        @foreach ($auditLogs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $log->created_at->format('M d, Y H:i:s') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-8 w-8 bg-green-700 rounded-full flex items-center justify-center">
                                            @if ($log->user)
                                                <span
                                                    class="text-sm font-medium text-white">{{ substr($log->user->name, 0, 1) }}</span>
                                            @else
                                                <i class="fas fa-user text-gray-500"></i>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 truncate max-w-[150px]"
                                                title="{{ $log->user->name ?? 'System' }}">
                                                {{ $log->user ? Str::limit($log->user->name, 20, '...') : 'System' }}
                                            </div>
                                            @if ($log->user)
                                                <div class="text-xs text-gray-500 truncate max-w-[150px]"
                                                    title="{{ $log->user->email }}">
                                                    {{ Str::limit($log->user->email, 20, '...') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex justify-center">
                                        <span
                                            class="inline-flex justify-center items-center w-[100px] px-3 py-1.5 text-xs font-semibold rounded-full
                                            @if ($log->action == 'create') bg-green-100 text-green-800
                                            @elseif($log->action == 'update') bg-yellow-100 text-yellow-800
                                            @elseif($log->action == 'delete') bg-red-100 text-red-800
                                            @elseif($log->action == 'login') bg-blue-100 text-blue-800
                                            @elseif($log->action == 'logout') bg-pink-100 text-pink-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                            {{ ucfirst($log->action) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->ip_address }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-gray-50 border-t border-gray-300 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        Showing {{ $auditLogs->firstItem() ?? 0 }} to {{ $auditLogs->lastItem() ?? 0 }} of
                        {{ $auditLogs->total() }} results
                    </div>
                    @if ($auditLogs->hasPages())
                        <div class="flex space-x-1">
                            @if ($auditLogs->onFirstPage())
                                <span
                                    class="px-3 py-2 text-sm text-gray-400 bg-white border border-gray-300 rounded-md cursor-not-allowed">Previous</span>
                            @else
                                <button wire:click="previousPage"
                                    class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Previous</button>
                            @endif

                            @foreach ($auditLogs->getUrlRange(max(1, $auditLogs->currentPage() - 2), min($auditLogs->lastPage(), $auditLogs->currentPage() + 2)) as $page => $url)
                                @if ($page == $auditLogs->currentPage())
                                    <span
                                        class="px-3 py-2 text-sm text-white bg-green-700 border border-green-700 rounded-md">{{ $page }}</span>
                                @else
                                    <button wire:click="gotoPage({{ $page }})"
                                        class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50">{{ $page }}</button>
                                @endif
                            @endforeach

                            @if ($auditLogs->hasMorePages())
                                <button wire:click="nextPage"
                                    class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Next</button>
                            @else
                                <span
                                    class="px-3 py-2 text-sm text-gray-400 bg-white border border-gray-300 rounded-md cursor-not-allowed">Next</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-history text-2xl text-gray-500"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Audit Logs Found</h3>
                <p class="text-gray-500 mb-6">There are no audit logs matching your filter criteria.</p>
            </div>
        @endif
    </div>
</div>
