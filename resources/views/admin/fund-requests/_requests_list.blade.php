@if(count($fundRequests) > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Request ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Scholar</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date Requested</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Documents</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y bg-white border-gray-300">
                @foreach($fundRequests as $request)
                    <tr class="transition-colors duration-150 hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">FR-{{ $request->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center bg-green-100">
                                    @if($request->scholarProfile->profile_photo)
                                        <img src="{{ asset('images/' . $request->scholarProfile->profile_photo) }}" alt="{{ $request->scholarProfile->user->name }}" class="h-8 w-8 rounded-full">
                                    @else
                                        <i class="fas fa-user text-green-600"></i>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-700">{{ $request->scholarProfile->user->name }}</div>
                                    <div class="text-xs max-w-xs truncate text-gray-500">{{ Str::limit($request->scholarProfile->department, 25, '.....') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $request->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php $docCount = $request->documents->count(); @endphp
                            @if($docCount > 0)
                                <button type="button" onclick="openDocumentModal({{ $request->id }})" class="flex items-center p-2 rounded-md transition-colors shadow-sm bg-green-100 border border-green-500 hover:bg-green-500 hover:shadow-lg group">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center mr-2 bg-green-600">
                                        <i class="fas fa-file-alt text-white"></i>
                                    </div>
                                    <span class="text-sm font-medium text-green-600 group-hover:text-white">View Document</span>
                                </button>
                            @else
                                <span class="text-sm p-2 rounded-md inline-block text-gray-500 bg-gray-100">No documents</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex justify-center">
                                <span class="inline-flex justify-center items-center w-[100px] px-3 py-1.5 text-xs font-semibold rounded-full
                                    @if($request->status == 'Approved') bg-green-600 text-white
                                    @elseif($request->status == 'Rejected') bg-red-700 text-white
                                    @elseif($request->status == 'Under Review') bg-yellow-400 text-yellow-800
                                    @elseif($request->status == 'Submitted') bg-blue-500 text-white
                                    @else bg-gray-500 text-white @endif">
                                    {{ $request->status }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.fund-requests.show', $request->id) }}" class="font-medium hover:underline transition-colors duration-150 text-blue-600" title="View Details">
                                View Details
                            </a>
                            @if($request->status == 'Submitted')
                                <a href="{{ route('admin.fund-requests.edit', $request->id) }}" class="mr-3 transition-colors duration-150 text-yellow-500 hover:text-yellow-600" title="Edit Request">
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
        <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4 bg-green-100">
            <i class="fas fa-money-bill-wave text-2xl text-green-600"></i>
        </div>
        <h3 class="text-lg font-medium mb-2 text-gray-700">No Fund Requests Found</h3>
        <p class="mb-6 text-gray-500">There are no fund requests matching your filter criteria.</p>
    </div>
@endif
