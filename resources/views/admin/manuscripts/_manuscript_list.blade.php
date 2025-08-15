@if(count($manuscripts) > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y table-base">
            <thead class="table-header">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider table-header-text">Title</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider table-header-text">Scholar</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider table-header-text">Type</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider table-header-text">Fund Request</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider table-header-text">Last Updated</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider table-header-text">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider table-header-text">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y table-body">
                @foreach($manuscripts as $manuscript)
                    <tr class="table-row">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-full max-w-xs">
                                    <div class="text-sm truncate cursor-help manuscript-title"
                                         title="{{ $manuscript->title }}"
                                         data-tooltip="{{ $manuscript->title }}">
                                        {{ Str::limit($manuscript->title, 40, '...') }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium scholar-name">{{ $manuscript->user ? $manuscript->user->name : 'Unknown' }}</div>
                                    <div class="text-xs scholar-department">{{ Str::limit($manuscript->scholarProfile ? $manuscript->scholarProfile->department : 'N/A', 25, '.....') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $manuscript->manuscript_type == 'Final' ? 'bg-green-100 text-green-800' : 'bg-green-50 text-green-700' }}">
                                {{ $manuscript->manuscript_type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($manuscript->fundRequest)
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">
                                        <a href="{{ route('admin.fund-requests.show', $manuscript->fundRequest->id) }}" 
                                           class="text-green-600 hover:text-green-800">
                                            FR-{{ $manuscript->fundRequest->id }}
                                        </a>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $manuscript->fundRequest->requestType->name ?? 'N/A' }}
                                    </div>
                                </div>
                            @else
                                <span class="text-xs text-gray-400">Independent</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm table-date">{{ $manuscript->updated_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex justify-center">
                                <span class="inline-flex justify-center items-center w-[100px] px-3 py-1.5 text-xs font-semibold rounded-full
                                    @if($manuscript->status == 'Published') bg-green-600 text-white
                                    @elseif($manuscript->status == 'Accepted') bg-emerald-500 text-white
                                    @elseif($manuscript->status == 'Revision Requested') bg-amber-500 text-white
                                    @elseif($manuscript->status == 'Under Review') bg-blue-500 text-white
                                    @elseif($manuscript->status == 'Submitted') bg-indigo-500 text-white
                                    @elseif($manuscript->status == 'Rejected') bg-red-500 text-white
                                    @else bg-gray-500 text-white @endif">
                                    {{ $manuscript->status }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex justify-center items-center space-x-2">
                                <a href="{{ route('admin.manuscripts.show', $manuscript->id) }}"
                                    class="inline-flex items-center justify-center px-3 py-1 text-sm rounded-md transition-colors action-btn-view"
                                    title="View Manuscript Details">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    View Details
                                </a>
                                @if(in_array($manuscript->status, ['Submitted', 'Under Review', 'Revision Requested']))
                                    <a href="{{ route('admin.manuscripts.edit', $manuscript->id) }}"
                                        class="inline-flex items-center justify-center px-3 py-1 text-sm rounded-md transition-colors action-btn-edit"
                                        title="Edit Manuscript">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Edit
                                    </a>
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
        <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4 border shadow-sm empty-state-icon">
            <i class="fas fa-book text-2xl empty-state-icon-color"></i>
        </div>
        <h3 class="text-lg font-medium mb-2 empty-state-title">No Manuscripts Found</h3>
        <p class="mb-6 empty-state-text">There are no manuscripts matching your filter criteria.</p>
    </div>
@endif
