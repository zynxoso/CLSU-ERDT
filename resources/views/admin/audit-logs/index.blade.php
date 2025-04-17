@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')
<div class="bg-white min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Audit Logs</h1>
            <div>
                <a href="{{ route('admin.audit-logs.export') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-download mr-2"></i> Export CSV
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg p-4 mb-6 border border-gray-200 shadow-sm">
            <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="user" class="block text-sm font-medium text-gray-700 mb-1">User</label>
                        <input type="text" id="user" name="user" value="{{ request('user') }}" placeholder="Search by name or email" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="action" class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                        <select id="action" name="action" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Actions</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="entity_type" class="block text-sm font-medium text-gray-700 mb-1">Entity Type</label>
                        <select id="entity_type" name="entity_type" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Entity Types</option>
                            @foreach($entityTypes as $type)
                                <option value="{{ $type }}" {{ request('entity_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="entity_id" class="block text-sm font-medium text-gray-700 mb-1">Entity ID</label>
                        <input type="text" id="entity_id" name="entity_id" value="{{ request('entity_id') }}" placeholder="Entity ID" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                        <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                        <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-filter mr-2"></i> Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg p-4 mb-6 border border-gray-200 shadow-sm">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-sm font-medium text-gray-700 mr-2">Quick Filters:</span>
                <a href="{{ route('admin.audit-logs.index', ['action' => 'create']) }}" class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium hover:bg-green-200">
                    <i class="fas fa-plus-circle mr-1"></i> Create
                </a>
                <a href="{{ route('admin.audit-logs.index', ['action' => 'update']) }}" class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium hover:bg-yellow-200">
                    <i class="fas fa-edit mr-1"></i> Update
                </a>
                <a href="{{ route('admin.audit-logs.index', ['action' => 'delete']) }}" class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium hover:bg-red-200">
                    <i class="fas fa-trash mr-1"></i> Delete
                </a>
                <a href="{{ route('admin.audit-logs.index', ['action' => 'login']) }}" class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium hover:bg-blue-200">
                    <i class="fas fa-sign-in-alt mr-1"></i> Login
                </a>
                <a href="{{ route('admin.audit-logs.index', ['entity_type' => 'Manuscript']) }}" class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs font-medium hover:bg-indigo-200">
                    <i class="fas fa-book mr-1"></i> Manuscripts
                </a>
                <a href="{{ route('admin.audit-logs.index', ['entity_type' => 'ScholarProfile']) }}" class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium hover:bg-purple-200">
                    <i class="fas fa-user-graduate mr-1"></i> Scholars
                </a>
                <a href="{{ route('admin.audit-logs.index', ['entity_type' => 'FundRequest']) }}" class="px-3 py-1 bg-pink-100 text-pink-800 rounded-full text-xs font-medium hover:bg-pink-200">
                    <i class="fas fa-money-bill mr-1"></i> Fund Requests
                </a>
                <a href="{{ route('admin.audit-logs.index', ['entity_type' => 'Document']) }}" class="px-3 py-1 bg-cyan-100 text-cyan-800 rounded-full text-xs font-medium hover:bg-cyan-200">
                    <i class="fas fa-file mr-1"></i> Documents
                </a>
                <a href="{{ route('admin.audit-logs.index') }}" class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium hover:bg-gray-200">
                    <i class="fas fa-times-circle mr-1"></i> Clear Filters
                </a>
            </div>

            <div class="flex flex-wrap items-center gap-2 mt-3">
                <span class="text-sm font-medium text-gray-700 mr-2">Time Range:</span>
                <a href="{{ route('admin.audit-logs.index', ['date_from' => now()->subDay()->format('Y-m-d')]) }}"
                   class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium hover:bg-blue-200">
                    <i class="fas fa-clock mr-1"></i> Last 24 Hours
                </a>
                <a href="{{ route('admin.audit-logs.index', ['date_from' => now()->subDays(7)->format('Y-m-d')]) }}"
                   class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium hover:bg-blue-200">
                    <i class="fas fa-calendar-week mr-1"></i> Last 7 Days
                </a>
                <a href="{{ route('admin.audit-logs.index', ['date_from' => now()->subDays(30)->format('Y-m-d')]) }}"
                   class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium hover:bg-blue-200">
                    <i class="fas fa-calendar-alt mr-1"></i> Last 30 Days
                </a>
                <a href="{{ route('admin.audit-logs.index', ['date_from' => now()->subDays(90)->format('Y-m-d')]) }}"
                   class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium hover:bg-blue-200">
                    <i class="fas fa-calendar-alt mr-1"></i> Last 90 Days
                </a>
            </div>
        </div>

        <!-- Audit Logs Table -->
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
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
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
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
                                        <span class="px-2 py-1 text-xs rounded-full
                                            @if($log->action == 'create') bg-green-100 text-green-800
                                            @elseif($log->action == 'update') bg-yellow-100 text-yellow-800
                                            @elseif($log->action == 'delete') bg-red-100 text-red-800
                                            @elseif($log->action == 'login') bg-blue-100 text-blue-800
                                            @elseif($log->action == 'logout') bg-purple-100 text-purple-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                            {{ ucfirst($log->action) }}
                                        </span>
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" class="text-blue-600 hover:text-blue-800 flex items-center">
                                                <i class="fas fa-cog mr-1"></i> Actions
                                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                                            </button>
                                            <div x-show="open"
                                                 @click.away="open = false"
                                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 border border-gray-200"
                                                 x-transition:enter="transition ease-out duration-100"
                                                 x-transition:enter-start="transform opacity-0 scale-95"
                                                 x-transition:enter-end="transform opacity-100 scale-100">
                                                <div class="py-1">
                                                    <a href="{{ route('admin.audit-logs.show', $log->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        <i class="fas fa-eye mr-2 text-blue-600"></i> View Details
                                                    </a>
                                                    @if($log->entity_type && $log->entity_id)
                                                        @php
                                                            $entityRoute = null;
                                                            if($log->entity_type == 'Manuscript') {
                                                                $entityRoute = route('admin.manuscripts.show', $log->entity_id);
                                                            } elseif($log->entity_type == 'Document') {
                                                                $entityRoute = route('admin.documents.show', $log->entity_id);
                                                            } elseif($log->entity_type == 'ScholarProfile') {
                                                                $entityRoute = route('admin.scholars.show', $log->entity_id);
                                                            } elseif($log->entity_type == 'FundRequest') {
                                                                $entityRoute = route('admin.fund-requests.show', $log->entity_id);
                                                            }
                                                        @endphp

                                                        @if($entityRoute)
                                                            <a href="{{ $entityRoute }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                                <i class="fas fa-external-link-alt mr-2 text-green-600"></i> Go to {{ $log->entity_type }}
                                                            </a>
                                                        @endif
                                                    @endif

                                                    @if($log->user)
                                                        <a href="{{ route('admin.users.show', $log->user->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                            <i class="fas fa-user mr-2 text-purple-600"></i> View User Profile
                                                        </a>
                                                    @endif

                                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 filter-similar"
                                                       data-action="{{ $log->action }}" data-entity="{{ $log->entity_type }}">
                                                        <i class="fas fa-filter mr-2 text-yellow-600"></i> Filter Similar
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-history text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Audit Logs Found</h3>
                    <p class="text-gray-500 mb-6">There are no audit logs matching your filter criteria.</p>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $auditLogs->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle filter similar button clicks
        document.querySelectorAll('.filter-similar').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const action = this.getAttribute('data-action');
                const entity = this.getAttribute('data-entity');

                // Set form values
                if (action) {
                    document.getElementById('action').value = action;
                }

                if (entity) {
                    document.getElementById('entity_type').value = entity;
                }

                // Submit the filter form
                document.querySelector('form').submit();
            });
        });

        // Add visual feedback for quick actions buttons
        const actionButtons = document.querySelectorAll('.actions-dropdown .action-btn');
        actionButtons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.classList.add('bg-gray-50');
            });

            button.addEventListener('mouseleave', function() {
                this.classList.remove('bg-gray-50');
            });
        });
    });
</script>
@endpush

@section('styles')
<style>
    .dropdown-appear {
        opacity: 0;
        transform: translateY(-10px);
        transition: opacity 0.2s ease-out, transform 0.2s ease-out;
    }

    .dropdown-appear.show {
        opacity: 1;
        transform: translateY(0);
    }

    .action-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.15rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }
</style>
@endsection

@endsection
