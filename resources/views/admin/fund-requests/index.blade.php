@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Fund Requests</h1>
                <p class="mt-1 text-sm text-gray-600">Manage and process scholar fund requests</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded-lg shadow">
            <form id="filter-form" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="Under Review" {{ request('status') == 'Under Review' ? 'selected' : '' }}>Under Review</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                    <select id="purpose" name="purpose" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Purposes</option>
                        <option value="Tuition" {{ request('purpose') == 'Tuition' ? 'selected' : '' }}>Tuition</option>
                        <option value="Learning Activity Materials" {{ request('purpose') == 'Learning Activity Materials' ? 'selected' : '' }}>Learning Activity Materials</option>
                        <option value="Connectivity Allowance" {{ request('purpose') == 'Connectivity Allowance' ? 'selected' : '' }}>Connectivity Allowance</option>
                        <option value="Thesis/Dissertation Outright Grant" {{ request('purpose') == 'Thesis/Dissertation Outright Grant' ? 'selected' : '' }}>Thesis/Dissertation Outright Grant</option>
                        <option value="Research Grant" {{ request('purpose') == 'Research Grant' ? 'selected' : '' }}>Research Grant</option>
                        <option value="Research Dissemination Grant" {{ request('purpose') == 'Research Dissemination Grant' ? 'selected' : '' }}>Research Dissemination Grant</option>
                        <option value="Mentor's Fee" {{ request('purpose') == "Mentor's Fee" ? 'selected' : '' }}>Mentor's Fee</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="scholar" class="block text-sm font-medium text-gray-700 mb-1">Scholar</label>
                    <input type="text" id="scholar" name="scholar" value="{{ request('scholar') }}" placeholder="Scholar Name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                    <input type="month" id="date" name="date" value="{{ request('date') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-end w-full sm:w-auto">
                    <button type="button" id="filter-button" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-filter mr-2"  style="color: white !important;"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Loading indicator -->
        <div id="loading-indicator" class="hidden">
            <div class="flex justify-center items-center py-8">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-700"></div>
            </div>
        </div>

        <!-- Fund Requests Table -->
        <div id="fund-requests-container" class="bg-white rounded-lg overflow-hidden shadow border border-gray-200">
            @include('admin.fund-requests._requests_list')
        </div>

        <!-- Pagination -->
        <div class="mt-6" id="pagination-container">
            {{ $fundRequests->links() }}
        </div>
    </div>
    
    <!-- Document Preview Modal -->
    <div id="document-preview-modal" class="fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
            <div class="bg-gray-100 px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 z-10">
                <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Documents</h3>
                <button type="button" onclick="closeDocumentModal()" class="text-black hover:text-gray-800">
                    <i class="fas fa-times" style="color: black;"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 70px);" id="document-container">
                <div class="flex justify-center items-center h-32">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-700"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButton = document.getElementById('filter-button');
        const filterForm = document.getElementById('filter-form');
        const fundRequestsContainer = document.getElementById('fund-requests-container');
        const paginationContainer = document.getElementById('pagination-container');
        const loadingIndicator = document.getElementById('loading-indicator');
        const documentModal = document.getElementById('document-preview-modal');
        const documentContainer = document.getElementById('document-container');
        const modalTitle = document.getElementById('modal-title');

        // Filter fund requests with AJAX
        filterButton.addEventListener('click', function() {
            filterFundRequests();
        });

        // Also filter when select elements change
        document.querySelectorAll('#filter-form select').forEach(element => {
            element.addEventListener('change', function() {
                filterFundRequests();
            });
        });

        // Filter when enter is pressed in text inputs
        document.querySelectorAll('#filter-form input').forEach(element => {
            element.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    filterFundRequests();
                }
            });
        });

        // Function to filter fund requests
        function filterFundRequests(page = 1) {
            // Show loading indicator
            loadingIndicator.classList.remove('hidden');
            fundRequestsContainer.classList.add('opacity-50');

            // Get all form data
            const formData = new FormData(filterForm);
            formData.append('page', page);

            // Convert FormData to URL params
            const params = new URLSearchParams();
            for (let [key, value] of formData.entries()) {
                params.append(key, value);
            }

            // Update URL without refreshing the page
            window.history.pushState({}, '', `${window.location.pathname}?${params.toString()}`);

            // Fetch filtered results
            fetch(`/admin/fund-requests/filter?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    // Update fund requests container
                    fundRequestsContainer.innerHTML = data.html;

                    // Update pagination
                    paginationContainer.innerHTML = data.pagination;

                    // Setup pagination links
                    setupPaginationLinks();
                })
                .catch(error => {
                    console.error('Error:', error);
                })
                .finally(() => {
                    // Hide loading indicator
                    loadingIndicator.classList.add('hidden');
                    fundRequestsContainer.classList.remove('opacity-50');
                });
        }

        // Setup pagination links
        function setupPaginationLinks() {
            document.querySelectorAll('#pagination-container a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const pageUrl = new URL(this.href);
                    const page = pageUrl.searchParams.get('page') || 1;
                    filterFundRequests(page);
                });
            });
        }

        // Initial setup for pagination
        setupPaginationLinks();
    });
    
    // Function to open document preview modal
    function openDocumentModal(requestId) {
        const modal = document.getElementById('document-preview-modal');
        const container = document.getElementById('document-container');
        const title = document.getElementById('modal-title');
        
        // Show modal
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        
        // Show loading indicator
        container.innerHTML = '<div class="flex justify-center items-center h-32"><div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-700"></div></div>';
        
        // Fetch documents for the fund request
        fetch(`/admin/fund-requests/${requestId}/documents`)
            .then(response => response.json())
            .then(data => {
                title.textContent = `Documents for Request #FR-${requestId}`;
                
                if (data.documents.length > 0) {
                    let html = '<div class="grid grid-cols-1 gap-6">';
                    
                    data.documents.forEach(doc => {
                        html += `
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Document Name</p>
                                        <p class="text-base font-medium">${doc.file_name}</p>
                                    </div>
                                    <div class="mt-2 md:mt-0">
                                        <a href="/storage/${doc.file_path}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 inline-flex items-center">
                                            <i class="fas fa-external-link-alt mr-2"></i> Open in New Tab
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Document Preview -->
                                <div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">`;
                                
                        const extension = doc.file_name.split('.').pop().toLowerCase();
                        
                        if (extension === 'pdf') {
                            html += `
                                <div class="bg-gray-100 p-4 text-center">
                                    <embed src="/storage/${doc.file_path}" type="application/pdf" width="100%" height="500px" />
                                </div>`;
                        } else if (['jpg', 'jpeg', 'png'].includes(extension)) {
                            html += `
                                <div class="bg-gray-100 p-4 text-center">
                                    <img src="/storage/${doc.file_path}" alt="${doc.file_name}" class="max-w-full h-auto mx-auto" style="max-height: 500px;">
                                </div>`;
                        } else {
                            html += `
                                <div class="bg-gray-100 p-4 text-center">
                                    <p class="text-gray-600">Preview not available for this file type. Please click "Open in New Tab" to view the document.</p>
                                </div>`;
                        }
                        
                        html += `
                                </div>
                                
                                <!-- Document Status -->
                                <div class="mt-4">
                                    <p class="text-sm text-gray-500">Status</p>
                                    <p class="text-base font-medium ${doc.is_verified ? 'text-green-600' : 'text-yellow-600'}">
                                        ${doc.is_verified ? 'Verified' : 'Pending Verification'}
                                    </p>
                                </div>
                            </div>
                        `;
                    });
                    
                    html += '</div>';
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<div class="text-center py-6"><p class="text-gray-500">No documents found for this fund request.</p></div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                container.innerHTML = '<div class="text-center py-6"><p class="text-red-500">Error loading documents. Please try again.</p></div>';
            });
    }
    
    // Function to close document preview modal
    function closeDocumentModal() {
        const modal = document.getElementById('document-preview-modal');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
</script>
@endsection
