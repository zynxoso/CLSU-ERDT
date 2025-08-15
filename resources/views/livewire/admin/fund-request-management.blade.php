<div class="max-w-7xl mx-auto bg-gray-50 min-h-screen space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Fund Requests</h1>
                <p class="mt-1 text-sm text-gray-600">Manage and process scholar fund requests</p>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if($successMessage)
            <div class="border-l-4 border-green-500 bg-green-50 p-4 mb-4 relative text-green-800" role="alert">
                <p class="font-bold">Success!</p>
                <p>{{ $successMessage }}</p>
                <button wire:click="$set('successMessage', '')" class="absolute top-0 right-0 mt-4 mr-4 text-green-600 hover:text-green-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if($errorMessage)
            <div class="border-l-4 border-red-500 bg-red-50 p-4 mb-4 relative text-red-800" role="alert">
                <p class="font-bold">Error!</p>
                <p>{{ $errorMessage }}</p>
                <button wire:click="$set('errorMessage', '')" class="absolute top-0 right-0 mt-4 mr-4 text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Filters -->
        <div class="p-4 rounded-lg shadow bg-white border border-gray-300">
            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-sm font-medium mb-1 text-gray-700">Status</label>
                    <select wire:model.live="status" class="w-full rounded-md px-3 py-2 focus:outline-none border border-gray-300 bg-white text-gray-600">
                        <option value="">All Statuses</option>
                        <option value="Submitted">Submitted</option>
                        <option value="Under Review">Under Review</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="scholar" class="block text-sm font-medium mb-1 text-gray-700">Scholar</label>
                    <input type="text" wire:model.live.debounce.300ms="scholar" placeholder="Scholar Name" class="w-full rounded-md px-3 py-2 focus:outline-none border border-gray-300 bg-white text-gray-600">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="date" class="block text-sm font-medium mb-1 text-gray-700">Start Date</label>
                    <input type="month" wire:model.live="date" class="w-full rounded-md px-3 py-2 focus:outline-none border border-gray-300 bg-white text-gray-600">
                </div>
                <div class="flex items-end w-full sm:w-auto">
                    <button type="button" wire:click="resetFilters" class="w-full sm:w-auto px-4 py-2 text-white rounded-lg bg-gray-500 hover:bg-gray-600 transition-colors">
                        <i class="fas fa-times mr-2"></i> Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading indicator -->
        <div wire:loading class="w-full">
            <div class="flex justify-center items-center py-8">
                <div class="rounded-full h-10 w-10 border-b-2" style="border-color: rgb(34 197 94);"></div>
            </div>
        </div>

        <!-- Fund Requests Table -->
        <div class="rounded-lg overflow-hidden shadow bg-white border border-gray-300" wire:loading.class="opacity-50">
            @if(count($fundRequests) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                 <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Request ID</th>
                                 <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Scholar</th>
                                 <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Date Requested</th>
                                 <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Documents</th>
                                 <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                 <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                             </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-300 bg-white">
                            @foreach($fundRequests as $request)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">FR-{{ $request->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center" style="background-color: rgba(76, 175, 80, 0.1);">
                                                @if($request->scholarProfile->profile_photo)
                                                    <img src="{{ asset('images/' . $request->scholarProfile->profile_photo) }}" alt="{{ $request->scholarProfile->user->name }}" class="h-8 w-8 rounded-full">
                                                @else
                                                    <i class="fas fa-user" style="color: rgb(34 197 94);"></i>
                                                @endif
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-600">{{ $request->scholarProfile->user->name }}</div>
                                                <div class="text-xs max-w-xs truncate text-gray-500">{{ Str::limit($request->scholarProfile->department, 25, '.....') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $request->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php $docCount = $request->documents->count(); @endphp
                                        @if($docCount > 0)
                                            <button type="button" wire:click="openDocumentModal({{ $request->id }})" class="flex items-center p-2 rounded-md shadow-sm bg-green-50 border border-green-500 hover:bg-green-100 transition-colors">
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center mr-2 bg-green-500">
                                                    <i class="fas fa-file-alt text-white"></i>
                                                </div>
                                                <span class="text-sm font-medium text-green-600">View Document</span>
                                            </button>
                                        @else
                                            <span class="text-sm p-2 rounded-md inline-block text-gray-500 bg-gray-100">No documents</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex justify-center">
                                            <span class="inline-flex justify-center items-center w-[100px] px-3 py-1.5 text-xs font-semibold rounded-full text-white
                                                @if($request->status == 'Approved') bg-green-500
                                                @elseif($request->status == 'Rejected') bg-red-500
                                                @elseif($request->status == 'Under Review') bg-yellow-500
                                                @elseif($request->status == 'Submitted') bg-blue-500
                                                @elseif($request->status == 'Completed') bg-purple-500
                                                @else bg-gray-500 @endif">
                                                {{ $request->status }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex justify-center items-center space-x-2">
                                            <a href="{{ route('admin.fund-requests.show', $request->id) }}"
                                                class="inline-flex items-center justify-center px-3 py-1 text-sm rounded-md transition-colors bg-blue-100 text-blue-600 hover:bg-blue-200"
                                                title="View Fund Request Details">
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
                                            @if(in_array($request->status, ['Submitted', 'Under Review']))
                                                <a href="{{ route('admin.fund-requests.edit', $request->id) }}"
                                                    class="inline-flex items-center justify-center px-3 py-1 text-sm rounded-md transition-colors bg-green-100 text-green-600 hover:bg-green-200"
                                                    title="Edit Fund Request">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                    Edit Request
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
                    <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4 bg-green-100">
                        <i class="fas fa-money-bill-wave text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-medium mb-2 text-gray-600">No Fund Requests Found</h3>
                    <p class="mb-6 text-gray-500">There are no fund requests matching your filter criteria.</p>
                    @if($status || $scholar || $date)
                        <button wire:click="resetFilters" class="px-4 py-2 text-white rounded-lg transition-colors bg-green-500 hover:bg-green-600">
                            Clear Filters
                        </button>
                    @endif
                </div>
            @endif
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $fundRequests->links() }}
        </div>
    </div>

    <!-- Document Preview Modal -->
    @if($showDocumentModal)
        <div class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center">
            <div class="rounded-lg shadow-xl w-full max-w-6xl max-h-[95vh] overflow-hidden bg-white">
                <div class="px-6 py-4 border-b border-gray-300 flex justify-between items-center sticky top-0 z-10 bg-gray-100">
                    <h3 class="text-lg font-semibold text-gray-700">{{ $modalTitle }}</h3>
                    <button type="button" wire:click="closeDocumentModal"
                            class="p-2 rounded-full text-red-600 hover:text-red-800 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="overflow-y-auto" style="max-height: calc(95vh - 70px);">
                    @if($loadingDocuments)
                        <div class="flex justify-center items-center h-32">
                            <div class="rounded-full h-10 w-10 border-b-2 border-green-500 animate-spin"></div>
                        </div>
                    @elseif(count($modalDocuments) > 0)
                        <div class="space-y-6 p-6">
                            @foreach($modalDocuments as $doc)
                                <div class="border border-gray-300 rounded-lg overflow-hidden bg-gray-50">
                                    <!-- Document Header -->
                                    <div class="px-4 py-3 border-b border-gray-300 flex items-center justify-between bg-gray-100">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 p-2 rounded-lg mr-3 bg-green-50">
                                                <i class="fas
                                                    @if(isset($doc['file_type']) && str_contains($doc['file_type'], 'pdf'))
                                                        fa-file-pdf
                                                    @elseif(isset($doc['file_type']) && str_contains($doc['file_type'], 'image'))
                                                        fa-file-image
                                                    @elseif(isset($doc['file_name']) && in_array(strtolower(pathinfo($doc['file_name'], PATHINFO_EXTENSION)), ['doc', 'docx']))
                                                        fa-file-word
                                                    @else
                                                        fa-file-alt
                                                    @endif
                                                    text-lg text-green-600"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-700">{{ $doc['file_name'] }}</p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $doc['file_type'] ?? 'Unknown type' }} •
                                                    @if(isset($doc['file_size']))
                                                        {{ number_format($doc['file_size'] / 1024, 1) }} KB
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.documents.view', $doc['id']) }}" target="_blank"
                                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-white transition-colors bg-blue-500 hover:bg-blue-600">
                                                <i class="fas fa-external-link-alt mr-1.5"></i> Open
                                            </a>
                                            <a href="{{ route('admin.documents.download', $doc['id']) }}"
                                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-white transition-colors bg-green-500 hover:bg-green-600">
                                                <i class="fas fa-download mr-1.5"></i> Download
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Document Preview -->
                                    <div class="p-4">
                                        @if(isset($doc['file_type']))
                                            @if(str_contains($doc['file_type'], 'pdf'))
                                                <!-- PDF Preview -->
                                                <div class="w-full h-[600px]">
                                                    <iframe
                                                        src="{{ route('admin.documents.view', $doc['id']) }}"
                                                        class="w-full h-full border border-gray-300 rounded"
                                                        frameborder="0">
                                                        <p>Your browser does not support PDFs.
                                                            <a href="{{ route('admin.documents.view', $doc['id']) }}" target="_blank">Click here to view the PDF</a>
                                                        </p>
                                                    </iframe>
                                                </div>
                                            @elseif(str_contains($doc['file_type'], 'image'))
                                                <!-- Image Preview -->
                                                <div class="text-center">
                                                    <img
                                                        src="{{ route('admin.documents.view', $doc['id']) }}"
                                                        alt="{{ $doc['file_name'] }}"
                                                        class="max-w-full h-auto rounded border border-gray-300 max-h-[600px]"
                                                        loading="lazy"
                                                    />
                                                </div>
                                            @elseif(in_array(strtolower(pathinfo($doc['file_name'], PATHINFO_EXTENSION)), ['doc', 'docx']))
                                                <!-- Word Document Preview -->
                                                <div class="text-center py-8">
                                                    <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4 bg-blue-50">
                                                        <i class="fas fa-file-word text-2xl text-blue-600"></i>
                                                    </div>
                                                    <h4 class="text-lg font-medium mb-2 text-gray-700">Word Document</h4>
                                                    <p class="mb-4 text-gray-500">Preview not available for Word documents.</p>
                                                    <a href="{{ route('admin.documents.view', $doc['id']) }}" target="_blank"
                                                       class="inline-flex items-center px-4 py-2 rounded-md text-white transition-colors bg-blue-500 hover:bg-blue-600">
                                                        <i class="fas fa-external-link-alt mr-2"></i> Open Document
                                                    </a>
                                                </div>
                                            @else
                                                <!-- Other File Types -->
                                                <div class="text-center py-8">
                                                    <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4 bg-purple-50">
                                                        <i class="fas fa-file text-2xl text-purple-600"></i>
                                                    </div>
                                                    <h4 class="text-lg font-medium mb-2 text-gray-700">{{ ucfirst(strtolower(pathinfo($doc['file_name'], PATHINFO_EXTENSION))) }} File</h4>
                                                    <p class="mb-4 text-gray-500">Preview not available for this file type.</p>
                                                    <div class="flex justify-center space-x-3">
                                                        <a href="{{ route('admin.documents.view', $doc['id']) }}" target="_blank"
                                                           class="inline-flex items-center px-4 py-2 rounded-md text-white transition-colors bg-blue-500 hover:bg-blue-600">
                                                            <i class="fas fa-external-link-alt mr-2"></i> Open File
                                                        </a>
                                                        <a href="{{ route('admin.documents.download', $doc['id']) }}"
                                                           class="inline-flex items-center px-4 py-2 rounded-md text-white transition-colors bg-green-500 hover:bg-green-600">
                                                            <i class="fas fa-download mr-2"></i> Download
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            <!-- Unknown File Type -->
                                            <div class="text-center py-8">
                                                <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4 bg-yellow-50">
                                                    <i class="fas fa-question-circle text-2xl text-yellow-500"></i>
                                                </div>
                                                <h4 class="text-lg font-medium mb-2 text-gray-700">Unknown File Type</h4>
                                                <p class="mb-4 text-gray-500">Unable to determine file type for preview.</p>
                                                <a href="{{ route('admin.documents.view', $doc['id']) }}" target="_blank"
                                                   class="inline-flex items-center px-4 py-2 rounded-md text-white transition-colors bg-blue-500 hover:bg-blue-600">
                                                    <i class="fas fa-external-link-alt mr-2"></i> Try to Open
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 px-6">
                            <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4 bg-green-50">
                                <i class="fas fa-folder-open text-2xl text-green-600"></i>
                            </div>
                            <h3 class="text-lg font-medium mb-2 text-gray-700">No Documents Found</h3>
                            <p class="text-gray-500">This fund request has no supporting documents.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
