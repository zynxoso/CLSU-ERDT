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
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                    <select id="purpose" name="purpose" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Purposes</option>
                        <option value="Living Allowance" {{ request('purpose') == 'Living Allowance' ? 'selected' : '' }}>Living Allowance</option>
                        <option value="Books" {{ request('purpose') == 'Books' ? 'selected' : '' }}>Books</option>
                        <option value="Conference" {{ request('purpose') == 'Conference' ? 'selected' : '' }}>Conference</option>
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
</script>
@endsection
