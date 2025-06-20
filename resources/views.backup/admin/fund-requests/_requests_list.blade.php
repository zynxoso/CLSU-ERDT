@if(count($fundRequests) > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholar</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Requested</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documents</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($fundRequests as $request)
                    <tr class="hover:bg-red-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">FR-{{ $request->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                                    @if($request->scholarProfile->user->profile_photo)
                                        <img src="{{ asset('storage/' . $request->scholarProfile->user->profile_photo) }}" alt="{{ $request->scholarProfile->user->name }}" class="h-8 w-8 rounded-full">
                                    @else
                                        <i class="fas fa-user text-red-600"></i>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $request->scholarProfile->user->name }}</div>
                                    <div class="text-xs text-gray-500 max-w-xs truncate">{{ Str::limit($request->scholarProfile->program, 25, '.....') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $request->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php $docCount = $request->documents->count(); @endphp
                            @if($docCount > 0)
                                <button type="button" onclick="openDocumentModal({{ $request->id }})" class="flex items-center bg-green-100 hover:bg-green-200 p-2 rounded-md transition-colors border border-green-300 shadow-sm">
                                    <div class="w-8 h-8 rounded-full bg-green-600 flex items-center justify-center mr-2">
                                        <i class="fas fa-file-alt text-white"></i>
                                    </div>
                                    <span class="text-sm font-medium text-green-800">View Document</span>
                                </button>
                            @else
                                <span class="text-sm text-gray-500 p-2 bg-gray-100 rounded-md inline-block">No documents</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex justify-center">
                                <span class="inline-flex justify-center items-center w-[100px] px-3 py-1.5 text-xs font-semibold rounded-full
                                    @if($request->status == 'Approved') bg-green-600 text-white
                                    @elseif($request->status == 'Rejected') bg-red-600 text-white
                                    @elseif($request->status == 'Under Review') bg-blue-600 text-white
                                    @elseif($request->status == 'Submitted') bg-yellow-500 text-white
                                    @else bg-gray-500 text-white @endif">
                                    {{ $request->status }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.fund-requests.show', $request->id) }}" class="text-blue-600 hover:text-blue-800 mr-3 font-medium hover:underline transition-colors duration-150" title="View Details">
                                View Details
                            </a>
                            @if($request->status == 'Pending')
                                <a href="{{ route('admin.fund-requests.edit', $request->id) }}" class="text-yellow-600 hover:text-yellow-800 mr-3 transition-colors duration-150" title="Edit Request">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-12">
        <div class="w-16 h-16 mx-auto bg-red-50 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-money-bill-wave text-2xl text-red-600"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No Fund Requests Found</h3>
        <p class="text-gray-500 mb-6">There are no fund requests matching your filter criteria.</p>
    </div>
@endif
