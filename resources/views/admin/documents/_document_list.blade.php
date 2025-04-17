<div class="overflow shadow rounded-lg">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document</th>
                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholar</th>
                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($documents as $document)
                <tr class="hover:bg-blue-50 transition-colors duration-150">
                    <td class="px-4 py-3">
                        <div class="flex flex-col">
                            <div class="text-sm font-medium text-gray-900 truncate max-w-[200px]" title="{{ $document->file_name }}">
                                {{ Str::limit($document->file_name, 28, '...') }}
                            </div>
                            <div class="text-xs text-gray-500 truncate max-w-[200px]" title="{{ $document->description }}">
                                {{ Str::limit($document->description, 35, '...') }}
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center overflow-hidden shadow-sm border border-blue-100">
                                @if($document->user && $document->user->profile_photo)
                                    <img src="{{ asset('storage/' . $document->user->profile_photo) }}" alt="{{ $document->user->name }}" class="h-8 w-8 rounded-full object-cover">
                                @else
                                    <i class="fas fa-user text-blue-400 text-xs"></i>
                                @endif
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900 truncate max-w-[150px]" title="{{ $document->user ? $document->user->name : 'Unknown User' }}">
                                    {{ $document->user ? Str::limit($document->user->name, 20, '...') : 'Unknown User' }}
                                </div>
                                <div class="text-xs text-gray-500 truncate max-w-[150px]" title="{{ $document->scholarProfile ? $document->scholarProfile->program : 'No Program Info' }}">
                                    {{ $document->scholarProfile ? Str::limit($document->scholarProfile->program, 20, '...') : 'No Program Info' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $document->category }}</div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $document->created_at->format('M d, Y') }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="inline-flex justify-center min-w-[100px] px-2 py-1 text-xs leading-5 font-semibold rounded-full
                            @if($document->status == 'Verified') bg-green-100 text-green-800
                            @elseif($document->status == 'Rejected') bg-red-100 text-red-800
                            @elseif($document->status == 'Pending' || $document->status == 'Uploaded') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $document->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end items-center space-x-2">
                            <a href="{{ route('admin.documents.show', $document->id) }}" class="text-blue-400 hover:text-blue-300 transition-all hover:scale-110 inline-flex items-center justify-center h-7 w-7 bg-blue-50 rounded-full" title="View Document">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-gray-400 hover:text-gray-300 transition-all hover:scale-110 inline-flex items-center justify-center h-7 w-7 bg-gray-50 rounded-full" title="Download">
                                <i class="fas fa-download text-xs"></i>
                            </a>
                            @if($document->status == 'Pending' || $document->status == 'Uploaded')
                                <form action="{{ route('admin.documents.verify', $document->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="text-green-400 hover:text-green-300 transition-all hover:scale-110 inline-flex items-center justify-center h-7 w-7 bg-green-50 rounded-full" title="Verify Document">
                                        <i class="fas fa-check text-xs"></i>
                                    </button>
                                </form>
                                <button type="button"
                                        onclick="toggleRejectForm('{{ $document->id }}')"
                                        class="text-red-400 hover:text-red-300 transition-all hover:scale-110 inline-flex items-center justify-center h-7 w-7 bg-red-50 rounded-full"
                                        title="Reject Document">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            @endif
                        </div>

                        @if($document->status == 'Pending' || $document->status == 'Uploaded')
                            <div id="reject-form-{{ $document->id }}" class="hidden mt-2 px-3 py-3 bg-white rounded-md border border-gray-200 shadow-lg absolute z-10 right-0 w-64">
                                <form action="{{ route('admin.documents.reject', $document->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="text-xs font-medium text-gray-700 mb-2">Reason for rejection:</div>
                                    <textarea name="admin_notes" rows="3" placeholder="Please provide details..." class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm mb-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    <div class="flex justify-between">
                                        <button type="button" onclick="toggleRejectForm('{{ $document->id }}')" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-md text-xs hover:bg-gray-200 font-medium">Cancel</button>
                                        <button type="submit" class="px-3 py-1.5 bg-red-600 text-white rounded-md text-xs hover:bg-red-700 font-medium">Submit Rejection</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center">
                        <div class="max-w-sm mx-auto">
                            <div class="w-20 h-20 mx-auto bg-blue-50 rounded-full flex items-center justify-center mb-5 border border-blue-100 shadow-sm">
                                <i class="fas fa-file-alt text-3xl text-blue-400"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-3">No Documents Found</h3>
                            <p class="text-gray-600 mb-5 max-w-xs mx-auto">There are no documents matching your current filter criteria. Try adjusting your search parameters.</p>
                            <button onclick="window.location.href='{{ route('admin.documents.index') }}'" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm font-medium transition-colors">
                                <i class="fas fa-sync-alt mr-2 text-xs"></i> Reset Filters
                            </button>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    function toggleRejectForm(documentId) {
        const form = document.getElementById('reject-form-' + documentId);
        form.classList.toggle('hidden');
    }
</script>
