@if(count($fundRequests) > 0)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y" style="border-color: #E0E0E0;">
            <thead style="background-color: #F5F5F5;">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Request ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Scholar</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Date Requested</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Documents</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y" style="background-color: white; border-color: #E0E0E0;">
                @foreach($fundRequests as $request)
                    <tr class="transition-colors duration-150" onmouseover="this.style.backgroundColor='#F5F5F5'" onmouseout="this.style.backgroundColor='white'">
                        <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #424242;">FR-{{ $request->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center" style="background-color: rgba(76, 175, 80, 0.1);">
                                    @if($request->scholarProfile->profile_photo)
                                        <img src="{{ asset('images/' . $request->scholarProfile->profile_photo) }}" alt="{{ $request->scholarProfile->user->name }}" class="h-8 w-8 rounded-full">
                                    @else
                                        <i class="fas fa-user" style="color: #4CAF50;"></i>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium" style="color: #424242;">{{ $request->scholarProfile->user->name }}</div>
                                    <div class="text-xs max-w-xs truncate" style="color: #757575;">{{ Str::limit($request->scholarProfile->department, 25, '.....') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #424242;">{{ $request->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php $docCount = $request->documents->count(); @endphp
                            @if($docCount > 0)
                                <button type="button" onclick="openDocumentModal({{ $request->id }})" class="flex items-center p-2 rounded-md transition-colors shadow-sm" style="background-color: rgba(76, 175, 80, 0.1); border: 1px solid #4CAF50;" onmouseover="this.style.backgroundColor='#4CAF50'; this.style.boxShadow='0 2px 8px 0 rgba(76,175,80,0.15)'" onmouseout="this.style.backgroundColor='rgba(76, 175, 80, 0.1)'; this.style.boxShadow='none'">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center mr-2" style="background-color: #4CAF50;">
                                        <i class="fas fa-file-alt text-white"></i>
                                    </div>
                                    <span class="text-sm font-medium" style="color: #4CAF50;">View Document</span>
                                </button>
                            @else
                                <span class="text-sm p-2 rounded-md inline-block" style="color: #757575; background-color: #F5F5F5;">No documents</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex justify-center">
                                <span class="inline-flex justify-center items-center w-[100px] px-3 py-1.5 text-xs font-semibold rounded-full"
                                    @if($request->status == 'Approved') style="background-color: #4CAF50; color: white;"
                                    @elseif($request->status == 'Rejected') style="background-color: #D32F2F; color: white;"
                                    @elseif($request->status == 'Under Review') style="background-color: #FFCA28; color: #975A16;"
                                    @elseif($request->status == 'Submitted') style="background-color: #4A90E2; color: white;"
                                    @elseif($request->status == 'Draft') style="background-color: #757575; color: white;"
                                    @else style="background-color: #757575; color: white;" @endif>
                                    {{ $request->status }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.fund-requests.show', $request->id) }}" class="font-medium hover:underline transition-colors duration-150" style="color: #4A90E2;" onmouseover="this.style.color='#4A90E2'" onmouseout="this.style.color='#4A90E2'" title="View Details">
                                View Details
                            </a>
                            @if($request->status == 'Draft')
                                <a href="{{ route('admin.fund-requests.edit', $request->id) }}" class="mr-3 transition-colors duration-150" style="color: #FFCA28;" onmouseover="this.style.color='#FFB300'" onmouseout="this.style.color='#FFCA28'" title="Edit Request">
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
        <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4" style="background-color: rgba(76, 175, 80, 0.1);">
            <i class="fas fa-money-bill-wave text-2xl" style="color: #4CAF50;"></i>
        </div>
        <h3 class="text-lg font-medium mb-2" style="color: #424242;">No Fund Requests Found</h3>
        <p class="mb-6" style="color: #757575;">There are no fund requests matching your filter criteria.</p>
    </div>
@endif
