@extends('layouts.app')

@section('title', 'Fund Requests')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">My Fund Requests</h1>
            <a href="{{ route('scholar.fund-requests.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2" style="color: white !important;"></i> New Request
            </a>
        </div>

        <!-- Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center mr-4">
                        <i class="fas fa-money-bill-wave" style="color: white !important;"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Requested</p>
                        <p class="text-xl font-bold text-gray-800">₱{{ number_format($totalRequested, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-green-500 flex items-center justify-center mr-4">
                        <i class="fas fa-check" style="color: white !important;"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Approved</p>
                        <p class="text-xl font-bold text-gray-800">₱{{ number_format($totalApproved, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-yellow-500 flex items-center justify-center mr-4">
                        <i class="fas fa-clock" style="color: white !important;"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Pending</p>
                        <p class="text-xl font-bold text-gray-800">₱{{ number_format($totalPending, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-red-500 flex items-center justify-center mr-4">
                        <i class="fas fa-times" style="color: white !important;"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Rejected</p>
                        <p class="text-xl font-bold text-gray-800">₱{{ number_format($totalRejected, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

                <!-- Document Requirements Notice -->
               <div class="mt-4 bg-blue-50 rounded-lg p-3 border border-blue-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center mr-3">
                        <i class="fas fa-file-alt text-sm" style="color: white !important;"></i>
                    </div>
                    <div class="mr-4">
                        <h4 class="text-sm font-semibold text-blue-800">Document Requirements</h4>
                        <p class="text-xs text-blue-700">Review required documents before submitting</p>
                    </div>
                </div>
                <button type="button" id="openDocRequirementsBtn" class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors duration-300 flex items-center whitespace-nowrap">
                     View Requirements
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg p-4 mb-6 border border-gray-200 shadow-sm mt-6">
            <form action="{{ route('scholar.fund-requests.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                    <input type="month" id="date" name="date" value="{{ request('date') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-end w-full sm:w-auto">
                    <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" >
                        <i class="fas fa-filter mr-2 text-white" style="color: white !important;"></i> Filter
                    </button>
                </div>
            </form>
        </div>
        

        <!-- Fund Requests Table -->
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
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
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">FR-{{ $request->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $request->requestType->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">₱{{ number_format($request->amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $request->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full
                                            @if($request->status == 'Approved') bg-green-100 text-green-800
                                            @elseif($request->status == 'Rejected') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ $request->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('scholar.fund-requests.show', $request->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-eye" style="color: black;"></i>
                                        </a>
                                        @if($request->status == 'Pending')
                                            <a href="{{ route('scholar.fund-requests.edit', $request->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                                <i class="fas fa-edit" style="color: black;"></i>
                                            </a>
                                            <form action="{{ route('scholar.fund-requests.destroy', $request->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to cancel this request?')">
                                                    <i class="fas fa-trash" style="color: black;"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-money-bill-wave text-2xl text-gray-500"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">No Fund Requests Yet</h3>
                    <p class="text-gray-600 mb-6">You haven't created any fund requests yet.</p>
                    <a href="{{ route('scholar.fund-requests.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i> Create Your First Request
                    </a>
                </div>
            @endif
        </div>
        
        <!-- Document Requirements Modal -->
        <div id="docRequirementsModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen p-0 sm:p-4">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                
                <!-- Modal panel -->
                <div class="inline-block bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all w-full max-w-lg sm:max-w-2xl md:max-w-3xl mx-4 sm:mx-auto">
                    <div class="bg-white p-3 sm:p-4">
                        <div class="flex justify-between items-center border-b border-gray-200 pb-2 mb-3">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900" id="modal-title">
                                Required Documents by Request Type
                            </h3>
                            <button type="button" id="closeDocRequirementsBtn" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-times" style="color: black;"></i>
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
                            
                            <div class="mt-3 p-2 sm:p-3 bg-blue-50 rounded-lg border border-blue-200">
                                <h5 class="font-semibold text-blue-800 mb-1 flex items-center text-sm">
                                    <i class="fas fa-info-circle mr-1.5 text-xs sm:text-sm"></i>
                                    Important Notes
                                </h5>
                                <ul class="list-disc pl-5 text-blue-700 text-xs sm:text-sm space-y-0.5">
                                    <li>All documents must be in PDF, JPG, or PNG format</li>
                                    <li>Maximum file size: 10MB per document</li>
                                    <li>Submit original documents when possible</li>
                                    <li>Incomplete documentation may delay approval</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-3 py-2 sm:px-4 sm:py-3 flex justify-end">
                        <button type="button" id="closeModalBtn" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-3 py-1.5 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            I Understand
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal JavaScript -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('docRequirementsModal');
                const openBtn = document.getElementById('openDocRequirementsBtn');
                const closeBtn = document.getElementById('closeDocRequirementsBtn');
                const closeModalBtn = document.getElementById('closeModalBtn');
                
                // Show the modal
                function openModal() {
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                }
                
                // Hide the modal
                function closeModal() {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
                
                // Event listeners
                openBtn.addEventListener('click', openModal);
                closeBtn.addEventListener('click', closeModal);
                closeModalBtn.addEventListener('click', closeModal);
                
                // Close modal when clicking outside
                modal.addEventListener('click', function(event) {
                    if (event.target === modal) {
                        closeModal();
                    }
                });
                
                // Close modal with Escape key
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                        closeModal();
                    }
                });
                
                // Auto-show modal on first visit (using localStorage)
                if (!localStorage.getItem('docRequirementsModalShown')) {
                    // Small delay to ensure page is fully loaded
                    setTimeout(function() {
                        openModal();
                        localStorage.setItem('docRequirementsModalShown', 'true');
                    }, 500);
                }
            });
        </script>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $fundRequests->links() }}
        </div>
    </div>
</div>
@endsection
