@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Document Verification</h1>
                <p class="mt-1 text-sm text-gray-600">Manage and verify scholar documents</p>
            </div>
        </div>

        <!-- Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center mr-4">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Pending</p>
                        <p class="text-xl font-bold text-gray-800" id="pending-count">{{ $pendingCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mr-4">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Verified</p>
                        <p class="text-xl font-bold text-gray-800" id="verified-count">{{ $verifiedCount }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 shadow">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mr-4">
                        <i class="fas fa-times text-red-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Rejected</p>
                        <p class="text-xl font-bold text-gray-800" id="rejected-count">{{ $rejectedCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg p-4 shadow">
            <form id="filter-form" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Uploaded" {{ request('status') == 'Uploaded' ? 'selected' : '' }}>Uploaded</option>
                        <option value="Verified" {{ request('status') == 'Verified' ? 'selected' : '' }}>Verified</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Document Type</label>
                    <select id="type" name="type" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Types</option>
                        <option value="Certificate" {{ request('type') == 'Certificate' ? 'selected' : '' }}>Certificate</option>
                        <option value="Transcript" {{ request('type') == 'Transcript' ? 'selected' : '' }}>Transcript</option>
                        <option value="ID" {{ request('type') == 'ID' ? 'selected' : '' }}>ID</option>
                        <option value="Receipt" {{ request('type') == 'Receipt' ? 'selected' : '' }}>Receipt</option>
                        <option value="Other" {{ request('type') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="scholar" class="block text-sm font-medium text-gray-700 mb-1">Scholar</label>
                    <input type="text" id="scholar" name="scholar" value="{{ request('scholar') }}" placeholder="Scholar Name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-end w-full sm:w-auto">
                    <button type="button" id="filter-button" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-filter mr-2" style="color: white !important;"></i> Filter
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

        <!-- Documents Grid -->
        <div id="documents-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @include('admin.documents._document_list')
        </div>

        <!-- Pagination -->
        <div class="mt-6" id="pagination-container">
            {{ $documents->links() }}
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButton = document.getElementById('filter-button');
        const filterForm = document.getElementById('filter-form');
        const documentsContainer = document.getElementById('documents-container');
        const paginationContainer = document.getElementById('pagination-container');
        const loadingIndicator = document.getElementById('loading-indicator');

        // Filter documents with AJAX
        filterButton.addEventListener('click', function() {
            filterDocuments();
        });

        // Also filter when enter is pressed in the filter fields
        document.querySelectorAll('#filter-form select, #filter-form input').forEach(element => {
            element.addEventListener('change', function() {
                filterDocuments();
            });

            element.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    filterDocuments();
                }
            });
        });

        // Function to filter documents
        function filterDocuments(page = 1) {
            // Show loading indicator
            loadingIndicator.classList.remove('hidden');
            documentsContainer.classList.add('opacity-50');

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
            fetch(`/admin/documents/filter?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    // Update counts
                    document.getElementById('pending-count').textContent = data.counts.pending;
                    document.getElementById('verified-count').textContent = data.counts.verified;
                    document.getElementById('rejected-count').textContent = data.counts.rejected;

                    // Update documents container
                    documentsContainer.innerHTML = data.html;

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
                    documentsContainer.classList.remove('opacity-50');
                });
        }

        // Setup pagination links
        function setupPaginationLinks() {
            document.querySelectorAll('#pagination-container a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const pageUrl = new URL(this.href);
                    const page = pageUrl.searchParams.get('page') || 1;
                    filterDocuments(page);
                });
            });
        }

        // Initial setup for pagination
        setupPaginationLinks();
    });
</script>
@endsection
