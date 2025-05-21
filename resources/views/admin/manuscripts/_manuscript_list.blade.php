@if(count($manuscripts) > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholar</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($manuscripts as $manuscript)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-full max-w-xs">
                                    <div class="text-sm text-gray-700 truncate" title="{{ $manuscript->title }}">{{ Str::limit($manuscript->title, 30, '.....') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                    @if($manuscript->user && $manuscript->user->profile_photo)
                                        <img src="{{ asset('storage/' . $manuscript->user->profile_photo) }}" alt="{{ $manuscript->user->name }}" class="h-8 w-8 rounded-full">
                                    @else
                                        <i class="fas fa-user text-gray-500"></i>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $manuscript->user ? $manuscript->user->name : 'Unknown' }}</div>
                                    <div class="text-xs text-gray-500">{{ Str::limit($manuscript->scholarProfile ? $manuscript->scholarProfile->program : 'N/A', 25, '.....') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $manuscript->updated_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex justify-center">
                                <span class="inline-flex justify-center items-center w-[100px] px-3 py-1.5 text-xs font-semibold rounded-full
                                    @if($manuscript->status == 'Published') bg-green-600 text-white
                                    @elseif($manuscript->status == 'Accepted') bg-blue-600 text-white
                                    @elseif($manuscript->status == 'Revision Requested') bg-orange-600 text-white
                                    @elseif($manuscript->status == 'Under Review') bg-yellow-500 text-white
                                    @elseif($manuscript->status == 'Submitted') bg-indigo-600 text-white
                                    @elseif($manuscript->status == 'Rejected') bg-red-600 text-white
                                    @else bg-gray-600 text-white @endif">
                                    {{ $manuscript->status }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <div class="flex items-center">
                                <a href="{{ route('admin.manuscripts.show', $manuscript->id) }}" class="text-blue-600 hover:text-blue-900 mr-3" title="View Details">
                                    <i class="fas fa-eye" style="color: black;"></i>
                                </a>
                                <!-- <a href="{{ route('admin.manuscripts.edit', $manuscript->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3" title="Edit Manuscript">
                                    <i class="fas fa-edit" style="color: #d97706;"></i>
                                </a> -->
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
            <i class="fas fa-book text-2xl text-gray-500"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-800 mb-2">No Manuscripts Found</h3>
        <p class="text-gray-500 mb-6">There are no manuscripts matching your filter criteria.</p>
    </div>
@endif
