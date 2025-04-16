@forelse($documents as $document)
    <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow">
        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-800 truncate">{{ $document->file_name }}</h3>
            <span class="px-2 py-1 text-xs rounded-full
                @if($document->status == 'Verified') bg-green-100 text-green-800
                @elseif($document->status == 'Rejected') bg-red-100 text-red-800
                @elseif($document->status == 'Pending' || $document->status == 'Uploaded') bg-yellow-100 text-yellow-800
                @else bg-gray-100 text-gray-600 @endif">
                {{ $document->status }}
            </span>
        </div>
        <div class="p-4">
            <div class="flex items-center mb-3">
                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                    @if($document->user && $document->user->profile_photo)
                        <img src="{{ asset('storage/' . $document->user->profile_photo) }}" alt="{{ $document->user->name }}" class="h-10 w-10 rounded-full">
                    @else
                        <i class="fas fa-user text-gray-600"></i>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-800">{{ $document->user ? $document->user->name : 'Unknown User' }}</p>
                    <p class="text-xs text-gray-500">{{ $document->scholarProfile ? $document->scholarProfile->program : 'No Program Info' }}</p>
                </div>
            </div>

            <div class="mb-3">
                <p class="text-sm text-gray-500 mb-1">Document Type:</p>
                <p class="text-sm text-gray-800">{{ $document->category }}</p>
            </div>

            <div class="mb-3">
                <p class="text-sm text-gray-500 mb-1">Description:</p>
                <p class="text-sm text-gray-800">{{ Str::limit($document->description, 100) }}</p>
            </div>

            <div class="mb-3">
                <p class="text-sm text-gray-500 mb-1">Submitted:</p>
                <p class="text-sm text-gray-800">{{ $document->created_at->format('M d, Y') }}</p>
            </div>

            <div class="flex justify-between mt-4">
                <a href="{{ route('admin.documents.show', $document->id) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                    <i class="fas fa-eye mr-1"></i> View
                </a>
                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="px-3 py-1 bg-gray-600 text-white rounded hover:bg-gray-700 text-sm">
                    <i class="fas fa-download mr-1"></i> Download
                </a>
            </div>

            @if($document->status == 'Pending' || $document->status == 'Uploaded')
                <div class="grid grid-cols-2 gap-2 mt-3">
                    <form action="{{ route('admin.documents.verify', $document->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="w-full px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                            <i class="fas fa-check mr-1"></i> Verify
                        </button>
                    </form>
                    <button type="button" class="w-full px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm"
                            onclick="document.getElementById('reject-form-{{ $document->id }}').classList.toggle('hidden')">
                        <i class="fas fa-times mr-1"></i> Reject
                    </button>
                </div>

                <form id="reject-form-{{ $document->id }}" action="{{ route('admin.documents.reject', $document->id) }}" method="POST" class="mt-3 hidden">
                    @csrf
                    @method('PUT')
                    <textarea name="admin_notes" rows="2" placeholder="Reason for rejection" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 text-sm mb-2"></textarea>
                    <button type="submit" class="w-full px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                        Submit Rejection
                    </button>
                </form>
            @endif
        </div>
    </div>
@empty
    <div class="col-span-full bg-white rounded-lg p-8 border border-gray-200 shadow text-center">
        <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-file-alt text-2xl text-gray-500"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-800 mb-2">No Documents Found</h3>
        <p class="text-gray-500 mb-6">There are no documents matching your filter criteria.</p>
    </div>
@endforelse
