@extends('layouts.app')

@section('title', 'Fund Requests')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">My Fund Requests</h1>
            <a href="{{ route('scholar.fund-requests.create') }}" class="px-4 py-2 bg-red-800 text-white rounded-lg hover:bg-red-900 transition-all duration-200 transform hover:scale-105 shadow-lg">
                <i class="fas fa-plus mr-2" style="color: white !important;"></i> New Request
            </a>
        </div>

        <!-- Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center mr-4 shadow-md">
                        <i class="fas fa-money-bill-wave" style="color: white !important;"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Requested</p>
                        <p class="text-xl font-bold text-gray-800">₱{{ number_format($totalRequested, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-green-600 flex items-center justify-center mr-4 shadow-md">
                        <i class="fas fa-check" style="color: white !important;"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Approved</p>
                        <p class="text-xl font-bold text-gray-800">₱{{ number_format($totalApproved, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-yellow-500 flex items-center justify-center mr-4 shadow-md">
                        <i class="fas fa-clock" style="color: white !important;"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Pending</p>
                        <p class="text-xl font-bold text-gray-800">₱{{ number_format($totalPending, 2) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-red-500 flex items-center justify-center mr-4 shadow-md">
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
        <div class="mt-4 bg-green-50 rounded-lg p-3 border border-green-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-green-600 flex items-center justify-center mr-3 shadow-md">
                        <i class="fas fa-file-alt text-sm" style="color: white !important;"></i>
                    </div>
                    <div class="mr-4">
                        <h4 class="text-sm font-semibold text-green-800">Document Requirements</h4>
                        <p class="text-xs text-green-700">Review required documents before submitting</p>
                    </div>
                </div>
                <button type="button" id="openDocRequirementsBtn" class="px-3 py-1.5 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-all duration-200 flex items-center whitespace-nowrap shadow-md hover:shadow-lg">
                     View Requirements
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg p-4 mb-6 border border-gray-200 shadow-sm mt-6">
            <form action="{{ route('scholar.fund-requests.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200">
                        <option value="">All Statuses</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                    <input type="month" id="date" name="date" value="{{ request('date') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200">
                </div>
                <div class="flex items-end w-full sm:w-auto">
                    <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-200 shadow-md hover:shadow-lg" >
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
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">FR-{{ $request->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $request->requestType->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">₱{{ number_format($request->amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $request->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full
                                            @if($request->status == 'Approved') bg-green-100 text-green-800
                                            @elseif($request->status == 'Rejected') bg-red-100 text-red-800
                                            @elseif($request->status == 'Under Review') bg-blue-100 text-blue-800
                                            @elseif($request->status == 'Submitted') bg-blue-100 text-blue-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ $request->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('scholar.fund-requests.show', $request->id) }}" class="text-blue-600 hover:text-blue-800 mr-3 transition-colors duration-150">
                                            <!-- <i class="fas fa-eye" style="color: black;"></i> -->
                                             View Request
                                        </a>
                                        @if($request->status == 'Pending')
                                            <a href="{{ route('scholar.fund-requests.edit', $request->id) }}" class="text-green-600 hover:text-green-800 mr-3 transition-colors duration-150">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('scholar.fund-requests.destroy', $request->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 transition-colors duration-150" onclick="return confirm('Are you sure you want to cancel this request?')">
                                                    <i class="fas fa-trash"></i>
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
                    <a href="{{ route('scholar.fund-requests.create') }}" class="px-4 py-2 bg-red-800 text-white rounded-lg hover:bg-red-900 transition-all duration-200 shadow-lg hover:shadow-xl">
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
                            <button type="button" id="closeDocRequirementsBtn" class="text-gray-400 hover:text-gray-600 transition-colors duration-150">
                                <i class="fas fa-times"></i>
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
                                    <i class="fas fa-info-circle mr-1.5 text-xs sm:text-sm"></i>
                                    Important Notes
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

<!-- Real-time Status Updates Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Store the last update data to avoid unnecessary updates
    let lastUpdateData = null;

    // Function to fetch status updates from server
    function fetchStatusUpdates() {
        const requestCards = document.querySelectorAll('[data-fund-request-id]');

        if (requestCards.length === 0) return;

        // Collect all fund request IDs
        const requestIds = Array.from(requestCards).map(card => card.dataset.fundRequestId);

        // Use silent fetch to avoid triggering the universal loading spinner
        window.universalLoading.silentFetch('/scholar/fund-requests/status-updates', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ request_ids: requestIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update each request card with new status information
                data.updates.forEach(update => {
                    // Find the corresponding card for this request
                    const requestCard = document.querySelector(`[data-fund-request-id="${update.request_id}"]`);
                    if (requestCard) {
                        // Update status badge
                        const statusBadge = requestCard.querySelector('.status-badge');
                        if (statusBadge) {
                            statusBadge.textContent = update.status;

                            // Remove all existing status classes
                            statusBadge.classList.remove(
                                'bg-green-100', 'text-green-800',
                                'bg-red-100', 'text-red-800',
                                'bg-purple-100', 'text-purple-800',
                                'bg-blue-100', 'text-blue-800',
                                'bg-yellow-100', 'text-yellow-800'
                            );

                            // Add appropriate class based on status
                            if (update.status === 'Approved') {
                                statusBadge.classList.add('bg-green-100', 'text-green-800');
                            } else if (update.status === 'Rejected') {
                                statusBadge.classList.add('bg-red-100', 'text-red-800');
                            } else if (update.status === 'Under Review') {
                                statusBadge.classList.add('bg-purple-100', 'text-purple-800');
                            } else if (update.status === 'Submitted') {
                                statusBadge.classList.add('bg-blue-100', 'text-blue-800');
                            } else {
                                statusBadge.classList.add('bg-yellow-100', 'text-yellow-800');
                            }
                        }

                        // Update last update time
                        const lastUpdateTime = requestCard.querySelector('.last-update-time');
                        if (lastUpdateTime) {
                            // Find the most recent entry for the current status
                            const currentStatusEntry = update.status_history.find(entry => entry.status === update.status);
                            if (currentStatusEntry) {
                                const updateDate = new Date(currentStatusEntry.created_at);
                                lastUpdateTime.textContent = timeSince(updateDate);
                            }
                        }

                        // Update progress bar if there are status history entries
                        if (update.status_history && update.status_history.length > 0) {
                            updateProgressBar(requestCard, update.status_history, update.status);
                        }
                    }
                });
            }
        })
        .catch(error => console.error('Error fetching status updates:', error));
    }

    // Function to update the progress bar based on status history
    function updateProgressBar(card, statusHistory, currentStatus) {
        // Define the status order for progress calculation
        const statusOrder = ['Draft', 'Submitted', 'Under Review', 'Approved'];

        // Find the highest status reached
        let highestStatusIndex = 0;

        // Check if current status is in our defined order
        const currentStatusIndex = statusOrder.indexOf(currentStatus);
        if (currentStatusIndex !== -1) {
            highestStatusIndex = currentStatusIndex;
        } else if (currentStatus === 'Rejected') {
            // If rejected, find the last status before rejection
            for (const entry of statusHistory) {
                const statusIndex = statusOrder.indexOf(entry.status);
                if (statusIndex > highestStatusIndex && entry.status !== 'Rejected') {
                    highestStatusIndex = statusIndex;
                }
            }
        }

        // Calculate progress percentage (excluding Draft status)
        const progressSteps = statusOrder.length - 1; // Exclude Draft
        const currentStep = highestStatusIndex === 0 ? 0 : highestStatusIndex - 1 + 1; // -1 to exclude Draft, +1 to count the current step
        const progressPercentage = (currentStep / progressSteps) * 100;

        // Update the progress bar
        const progressBar = card.querySelector('.progress-bar');
        if (progressBar) {
            // Set the custom property for animation
            progressBar.style.setProperty('--progress-width', `${progressPercentage}%`);

            // Reset animation
            progressBar.classList.remove('animating');
            void progressBar.offsetWidth; // Force reflow

            // Start animation only if not approved
            if (currentStatus !== 'Approved') {
                progressBar.classList.add('animating');
            }
        }

        // Update status icons
        const statusIcons = card.querySelectorAll('.status-icon-wrapper');
        statusIcons.forEach(iconWrapper => {
            const status = iconWrapper.dataset.status;
            const statusIndex = statusOrder.indexOf(status);
            const icon = iconWrapper.querySelector('.status-icon');
            const iconText = iconWrapper.querySelector('p');

            // Reset classes
            iconWrapper.classList.remove('active', 'completed');

            // If this status is completed
            if (statusIndex > 0 && statusIndex <= highestStatusIndex) {
                iconWrapper.classList.add('completed');
                icon.classList.add('completed');

                // Update icon color
                const iconElement = icon.querySelector('i');
                if (iconElement) {
                    iconElement.classList.remove('text-gray-500');
                    iconElement.classList.add('text-green-600');
                }
            }
            // If this is the current active status
            else if (statusIndex === highestStatusIndex) {
                iconWrapper.classList.add('active');
                icon.classList.add('active');

                // Set custom properties for the active icon
                if (status === 'Submitted') {
                    icon.style.setProperty('--active-bg-color', '#dbeafe');
                    icon.style.setProperty('--active-border-color', '#93c5fd');
                    icon.style.setProperty('--active-text-color', '#2563eb');
                } else if (status === 'Under Review') {
                    icon.style.setProperty('--active-bg-color', '#ede9fe');
                    icon.style.setProperty('--active-border-color', '#c4b5fd');
                    icon.style.setProperty('--active-text-color', '#7c3aed');
                } else if (status === 'Approved') {
                    icon.style.setProperty('--active-bg-color', '#dcfce7');
                    icon.style.setProperty('--active-border-color', '#86efac');
                    icon.style.setProperty('--active-text-color', '#16a34a');
                }

                // Update icon color
                const iconElement = icon.querySelector('i');
                if (iconElement) {
                    iconElement.classList.remove('text-gray-500');
                    if (status === 'Submitted') {
                        iconElement.classList.add('text-blue-600');
                    } else if (status === 'Under Review') {
                        iconElement.classList.add('text-purple-600');
                    } else if (status === 'Approved') {
                        iconElement.classList.add('text-green-600');
                    }
                }
            }
        });

        // Animate the shipping truck
        const shippingTruck = card.querySelector('.shipping-truck');
        if (shippingTruck) {
            // Set the truck position based on progress
            shippingTruck.style.setProperty('--truck-position', `${progressPercentage}%`);

            // Reset animation
            shippingTruck.classList.remove('animating', 'bounce');
            void shippingTruck.offsetWidth; // Force reflow

            // Start animation only if not approved
            if (currentStatus !== 'Approved') {
                shippingTruck.classList.add('animating');
            }

            // Add bounce effect at each completed status only if not approved
            if (currentStatus !== 'Approved') {
                statusIcons.forEach((iconWrapper, index) => {
                    if (iconWrapper.classList.contains('completed') || iconWrapper.classList.contains('active')) {
                        const iconPosition = (index / (statusIcons.length - 1)) * 100;

                        // Only bounce if the truck will pass this position
                        if (iconPosition <= progressPercentage) {
                            setTimeout(() => {
                                // Calculate when the truck should be at this position
                                const animationDuration = 2000; // 2s as defined in CSS
                                const bounceTime = (iconPosition / 100) * animationDuration;

                                setTimeout(() => {
                                    shippingTruck.classList.add('bounce');

                                    // Remove bounce after animation
                                    setTimeout(() => {
                                        shippingTruck.classList.remove('bounce');
                                    }, 500);
                                }, bounceTime);
                            }, 100); // Small delay to ensure animation has started
                        }
                    }
                });
            }
        }
    }

    // Helper function to format time since a date
    function timeSince(date) {
        // Ensure we have a valid date object
        if (!(date instanceof Date) || isNaN(date.getTime())) {
            return "just now"; // Return a default value for invalid dates
        }

        // Format the date in a more stable way
        const options = {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };

        return date.toLocaleDateString(undefined, options);
    }

    // Helper function to truncate text
    function truncateText(text, maxLength) {
        return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
    }

    // Check for updates less frequently (every 5 minutes) to reduce unnecessary reloads
    // Only poll when page is visible to improve performance
    setInterval(() => {
        if (!document.hidden) {
            fetchStatusUpdates();
        }
    }, 300000);

    // Also fetch updates when the page becomes visible again
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            fetchStatusUpdates();
        }
    });

    // Don't fetch immediately after page load - the server-rendered data is already current
    // This prevents the "reloading" effect mentioned in the issue
});
</script>

<style>
/* Progress Bar and Status Icons Animations */
@keyframes icon-pulse {
    0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.5); }
    70% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
    100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
}

@keyframes progress-bar-fill {
    from { width: 0%; }
    to { width: var(--progress-width, 100%); }
}

@keyframes truck-move-right {
    0% { left: 0%; opacity: 0; }
    10% { opacity: 1; }
    90% { opacity: 1; }
    100% { left: var(--truck-position, 100%); opacity: 1; }
}

@keyframes truck-bounce {
    0%, 100% { transform: translateY(0) translateX(-50%); }
    50% { transform: translateY(0) translateX(-50%) scale(1.2); }
}

@keyframes icon-activate {
    0% { transform: scale(1); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

.progress-bar.animating {
    --progress-width: 33%; /* Default value if not set by JS */
    animation: progress-bar-fill 1.5s ease-out forwards;
}

.status-icon.active {
    --active-bg-color: #2563eb; /* Default blue background (darker) */
    --active-border-color: #1d4ed8; /* Default blue border (darker) */
    --active-text-color: #ffffff; /* White text for contrast */
    background-color: var(--active-bg-color);
    border-color: var(--active-border-color);
    color: var(--active-text-color);
    animation: icon-activate 0.5s ease-out;
}

.status-icon.completed {
    background-color: #16a34a; /* Darker green */
    border-color: #166534; /* Even darker green for border */
    color: #ffffff; /* White text for contrast */
}

.status-icon-wrapper.active p {
    color: #1e40af;
    font-weight: 600;
}

.status-icon-wrapper.completed p {
    color: #16a34a;
    font-weight: 600;
}

.shipping-truck {
    --truck-position: 33%; /* Default value if not set by JS */
    transition: all 0.3s ease-in-out;
    position: absolute;
    top: 0;
    left: 0;
    transform: translateY(-50%) translateX(-50%);
}

.shipping-truck.animating {
    animation: truck-move-right 2s ease-in-out forwards;
}

.shipping-truck.bounce {
    animation: truck-bounce 0.5s ease-in-out;
}

.progress-container {
    height: 40px;
    margin-top: 20px;
    margin-bottom: 20px;
}
</style>
@endsection
