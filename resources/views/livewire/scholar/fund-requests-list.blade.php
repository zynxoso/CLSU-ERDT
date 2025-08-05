<div class="min-h-screen">
    <div class="container mx-auto">
        <div class="bg-white border-b border-gray-200 shadow-sm mb-6">
            <div class="container mx-auto px-4 py-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">My Fund Request</h1>
                    </div>
                    <a href="{{ route('scholar.fund-requests.create') }}" class="inline-flex items-center px-6 py-3 bg-[#4CAF50] hover:bg-[#43A047] text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        <span>New Request</span>
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-[#4CAF50]/10 border-l-4 border-[#4CAF50] text-[#2E7D32] p-4 mb-4 relative" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-[#4CAF50] mr-2"></i>
                    <p class="font-bold">Success!</p>
                </div>
                <p class="mt-1">{!! session('success') !!}</p>
                <button class="absolute top-0 right-0 mt-4 mr-4 text-[#2E7D32] hover:text-[#1B5E20]" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-4 relative" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                    <p class="font-bold">Error!</p>
                </div>
                <p class="mt-1">{{ session('error') }}</p>
                <button class="absolute top-0 right-0 mt-4 mr-4 text-red-700 hover:text-red-900" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-[#4A90E2] flex items-center justify-center mr-4 shadow-md">
                        <i class="fas fa-money-bill-wave text-white"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Requested</p>
                        <p class="text-xl font-bold text-[#4A90E2]">₱{{ number_format($totalRequested, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-[#4CAF50] flex items-center justify-center mr-4 shadow-md">
                        <i class="fas fa-check text-white"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Approved</p>
                        <p class="text-xl font-bold text-[#4CAF50]">₱{{ number_format($totalApproved, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-[#FFCA28] flex items-center justify-center mr-4 shadow-md">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Pending</p>
                        <p class="text-xl font-bold text-[#FFCA28]">₱{{ number_format($totalPending, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-red-500 flex items-center justify-center mr-4 shadow-md">
                        <i class="fas fa-times text-white"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Rejected</p>
                        <p class="text-xl font-bold text-red-500">₱{{ number_format($totalRejected, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Requirements Notice -->
        <div class="mt-4 bg-[#4CAF50]/5 rounded-lg p-3 border border-[#4CAF50]/20 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-[#4CAF50] flex items-center justify-center mr-3 shadow-md">
                        <i class="fas fa-file-alt text-sm text-white"></i>
                    </div>
                    <div class="mr-4">
                        <h4 class="text-sm font-semibold text-[#2E7D32]">Document Requirements</h4>
                        <p class="text-xs text-[#388E3C]">Review required documents before submitting</p>
                    </div>
                </div>
                <button type="button" wire:click="$dispatch('open-doc-requirements')" class="px-3 py-1.5 bg-[#4CAF50] text-white text-sm rounded-lg hover:bg-[#43A047] flex items-center space-x-2 whitespace-nowrap shadow-md">
                    <i class="fas fa-eye text-white"></i>
                    <span>View Requirements</span>
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg p-4 mb-6 border border-gray-200 shadow-sm mt-6">
            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" wire:model.live="status" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#4CAF50] focus:border-[#4CAF50]">
                        <option value="">All Statuses</option>
                        <option value="Draft">Draft</option>
                        <option value="Submitted">Submitted</option>
                        <option value="Under Review">Under Review</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                    <input type="month" id="date" wire:model.live="date" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#4CAF50] focus:border-[#4CAF50]">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <div class="relative">
                        <input
                            type="text"
                            id="search"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Request ID, Amount, Purpose, Type"
                            class="w-full border border-gray-300 rounded-md pl-10 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#4CAF50] focus:border-[#4CAF50]"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
                <div class="flex items-end">
                    <button
                        type="button"
                        wire:click="resetFilters"
                        class="px-4 py-2 bg-[#FFCA28] text-white rounded-lg hover:bg-[#FFB300] flex items-center space-x-2 transition-colors duration-200"
                    >
                        <i class="fas fa-undo-alt"></i>
                        <span>Reset</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading indicator -->
        <div wire:loading class="w-full">
            <div class="flex justify-center items-center py-8">
                <div class="animate-spin rounded-full h-10 w-10 border-4 border-[#4CAF50]/20 border-t-[#4CAF50]"></div>
            </div>
        </div>

        <!-- Fund Requests Table -->
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm" wire:loading.class="opacity-50">
            @if(count($fundRequests) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Requested</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($fundRequests as $request)
                                <tr class="hover:bg-gray-50" data-fund-request-id="{{ $request->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">FR-{{ $request->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $request->requestType->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">₱{{ number_format($request->amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $request->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 text-xs rounded-full font-medium
                                            @if($request->status == 'Approved') bg-[#4CAF50]/20 text-[#2E7D32] border border-[#4CAF50]/30
                                            @elseif($request->status == 'Rejected') bg-red-200 text-red-900 border border-red-300
                                            @elseif($request->status == 'Under Review' || $request->status == 'Submitted') bg-[#FFCA28]/25 text-[#975A16] border border-[#FFCA28]/30
                                            @elseif($request->status == 'Completed') bg-purple-200 text-purple-900 border border-purple-300
                                            @elseif($request->status == 'Draft') bg-gray-200 text-gray-900 border border-gray-300
                                            @else bg-[#FFCA28]/25 text-[#975A16] border border-[#FFCA28]/30 @endif">
                                            {{ $request->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-3">
                                            <a href="{{ route('scholar.fund-requests.show', $request->id) }}" class="text-[#4A90E2] hover:text-[#357ABD] flex items-center space-x-1 group">
                                                <i class="fas fa-eye"></i>
                                                <span class="group-hover:underline">View Request</span>
                                            </a>
                                            @if($request->status == 'Draft')
                                                <a href="{{ route('scholar.fund-requests.edit', $request->id) }}" class="text-[#4CAF50] hover:text-[#43A047]">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button
                                                    type="button"
                                                    wire:click="$dispatch('confirm-delete', { requestId: {{ $request->id }} })"
                                                    class="text-red-500 hover:text-red-700"
                                                >
                                                    <i class="fas fa-trash"></i>
                                                </button>
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
                    <div class="w-16 h-16 mx-auto bg-[#4CAF50]/5 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-money-bill-wave text-2xl text-[#4CAF50]"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">No Fund Requests Yet</h3>
                    <p class="text-gray-600 mb-6">You haven't created any fund requests yet.</p>
                    <a href="{{ route('scholar.fund-requests.create') }}" class="px-4 py-2 text-white rounded-lg bg-[#4CAF50] hover:bg-[#43A047] inline-flex items-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>Create Your First Request</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($fundRequests->hasPages())
            <div class="mt-6">
                {{ $fundRequests->links() }}
            </div>
        @endif
    </div>

    <!-- Document Requirements Modal -->
    <div id="docRequirementsModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-0 sm:p-4">
            <!-- Modal panel -->
            <div class="inline-block bg-white rounded-lg border border-green-200 text-left overflow-hidden shadow-xl w-full max-w-lg sm:max-w-2xl md:max-w-3xl mx-4 sm:mx-auto">
                <div class="bg-white p-3 sm:p-4">
                    <div class="flex justify-between items-center border-b border-gray-200 pb-2 mb-3">
                        <h3 class="text-base sm:text-lg font-medium text-gray-900" id="modal-title">
                            Required Documents by Request Type
                        </h3>
                        <button type="button" id="closeDocRequirementsBtn" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times" style="color:black;"></i>
                        </button>
                    </div>
                    <div class="max-h-[60vh] sm:max-h-[70vh] overflow-y-auto px-1 sm:px-2">
                        <p class="text-sm text-gray-700 mb-3">Please ensure you submit the following documents based on your request type:</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <!-- Tuition Request Documents -->
                            <div class="bg-gray-50 p-2 sm:p-3 rounded-lg border border-gray-200">
                                <h5 class="font-semibold text-gray-800 mb-1 flex items-center text-sm">
                                    <div class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center mr-2">
                                        <i class="fas fa-graduation-cap text-xs" style="color: white !important;"></i>
                                    </div>
                                    Tuition
                                </h5>
                                <ul class="list-disc pl-5 text-gray-700 text-xs sm:text-sm space-y-0.5">
                                    <li>Official tuition fee statement/bill</li>
                                    <li>Proof of enrollment or registration</li>
                                    <li>Official receipt (if already paid)</li>
                                </ul>
                            </div>
                            <!-- Research Request Documents -->
                            <div class="bg-gray-50 p-2 sm:p-3 rounded-lg border border-gray-200">
                                <h5 class="font-semibold text-gray-800 mb-1 flex items-center text-sm">
                                    <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center mr-2">
                                        <i class="fas fa-flask text-xs" style="color: white !important;"></i>
                                    </div>
                                    Research Grant
                                </h5>
                                <ul class="list-disc pl-5 text-gray-700 text-xs sm:text-sm space-y-0.5">
                                    <li>Research proposal with budget</li>
                                    <li>Endorsement letter from adviser</li>
                                    <li>Quotations for equipment/materials</li>
                                    <li>Ethics clearance (if applicable)</li>
                                </ul>
                            </div>
                            <!-- Living Allowance Documents -->
                            <div class="bg-gray-50 p-2 sm:p-3 rounded-lg border border-gray-200">
                                <h5 class="font-semibold text-gray-800 mb-1 flex items-center text-sm">
                                    <div class="w-6 h-6 rounded-full bg-yellow-500 flex items-center justify-center mr-2">
                                        <i class="fas fa-home text-xs" style="color: white !important;"></i>
                                    </div>
                                    Living Allowance
                                </h5>
                                <ul class="list-disc pl-5 text-gray-700 text-xs sm:text-sm space-y-0.5">
                                    <li>Certificate of enrollment</li>
                                    <li>Signed monthly stipend form</li>
                                    <li>Progress report (if required)</li>
                                </ul>
                            </div>
                            <!-- Conference Documents -->
                            <div class="bg-gray-50 p-2 sm:p-3 rounded-lg border border-gray-200">
                                <h5 class="font-semibold text-gray-800 mb-1 flex items-center text-sm">
                                    <div class="w-6 h-6 rounded-full bg-purple-500 flex items-center justify-center mr-2">
                                        <i class="fas fa-chalkboard-teacher text-xs" style="color: white !important;"></i>
                                    </div>
                                    Conference/Dissemination
                                </h5>
                                <ul class="list-disc pl-5 text-gray-700 text-xs sm:text-sm space-y-0.5">
                                    <li>Conference acceptance letter</li>
                                    <li>Copy of accepted abstract/paper</li>
                                    <li>Conference registration details</li>
                                    <li>Travel itinerary (if applicable)</li>
                                </ul>
                            </div>
                            <!-- Books Documents -->
                            <div class="bg-gray-50 p-2 sm:p-3 rounded-lg border border-gray-200">
                                <h5 class="font-semibold text-gray-800 mb-1 flex items-center text-sm">
                                    <div class="w-6 h-6 rounded-full bg-red-500 flex items-center justify-center mr-2">
                                        <i class="fas fa-book text-xs" style="color: white !important;"></i>
                                    </div>
                                    Books & Materials
                                </h5>
                                <ul class="list-disc pl-5 text-gray-700 text-xs sm:text-sm space-y-0.5">
                                    <li>List of required books/materials</li>
                                    <li>Course syllabus showing materials</li>
                                    <li>Receipts (if already purchased)</li>
                                </ul>
                            </div>
                            <!-- Thesis/Dissertation Documents -->
                            <div class="bg-gray-50 p-2 sm:p-3 rounded-lg border border-gray-200">
                                <h5 class="font-semibold text-gray-800 mb-1 flex items-center text-sm">
                                    <div class="w-6 h-6 rounded-full bg-indigo-500 flex items-center justify-center mr-2">
                                        <i class="fas fa-file-alt text-xs" style="color: white !important;"></i>
                                    </div>
                                    Thesis/Dissertation
                                </h5>
                                <ul class="list-disc pl-5 text-gray-700 text-xs sm:text-sm space-y-0.5">
                                    <li>Approved thesis/dissertation proposal</li>
                                    <li>Endorsement from committee</li>
                                    <li>Detailed budget for expenses</li>
                                    <li>Timeline of research activities</li>
                                </ul>
                            </div>
                        </div>
                        <div class="mt-3 p-2 sm:p-3 bg-green-50 rounded-lg border border-green-200">
                            <h5 class="font-semibold text-green-800 mb-1 flex items-center text-sm">
                                <i class="fas fa-info-circle mr-1.5 text-xs sm:text-sm"></i> Important Notes
                            </h5>
                            <ul class="list-disc pl-5 text-green-700 text-xs sm:text-sm space-y-0.5">
                                <li>All documents must be in PDF, JPG, or PNG format</li>
                                <li>Maximum file size: 10MB per document</li>
                                <li>Submit original documents when possible</li>
                                <li>Incomplete documentation may delay approval</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-3 py-2 sm:px-4 sm:py-3 flex justify-end">
                    <button type="button" id="closeModalBtn" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-3 py-1.5 bg-green-600 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        I Understand
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 for confirmation -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            // Handle delete confirmation
            Livewire.on('confirm-delete', (event) => {
                Swal.fire({
                    title: 'Cancel Request?',
                    text: "Are you sure you want to cancel this fund request?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4CAF50',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, cancel it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.deleteFundRequest(event.requestId);
                    }
                });
            });
            // Handle document requirements modal
            Livewire.on('open-doc-requirements', () => {
                openModal();
            });
        });
        // Modal functionality
        const modal = document.getElementById('docRequirementsModal');
        const closeBtn = document.getElementById('closeDocRequirementsBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        function openModal() {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        function closeModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        closeBtn.addEventListener('click', closeModal);
        closeModalBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModal();
            }
        });
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
    </script>
</div>
