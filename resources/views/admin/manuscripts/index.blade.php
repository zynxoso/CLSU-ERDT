@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manuscripts</h1>
                <p class="mt-1 text-sm text-gray-600">Manage and review scholar manuscripts</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-4">
                <a href="{{ route('admin.manuscripts.export') }}" id="export-button" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 inline-flex items-center justify-center shadow-sm">
                    <i class="fas fa-file-excel mr-2" style="color: white !important;"></i> Export Excel
                </a>
                <a href="{{ route('admin.manuscripts.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 inline-flex items-center justify-center shadow-sm">
                    <i class="fas fa-plus mr-2" style="color: white !important;"></i> Add Manuscript
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg p-4 shadow">
            <form id="filter-form" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="Approved Outline" {{ request('status') == 'Approved Outline' ? 'selected' : '' }}>Approved Outline</option>
                        <option value="Submitted to Journal" {{ request('status') == 'Submitted to Journal' ? 'selected' : '' }}>Submitted to Journal</option>
                        <option value="Accepted" {{ request('status') == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="Published" {{ request('status') == 'Published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select id="category" name="category" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Categories</option>
                        <option value="Thesis" {{ request('category') == 'Thesis' ? 'selected' : '' }}>Thesis</option>
                        <option value="Dissertation" {{ request('category') == 'Dissertation' ? 'selected' : '' }}>Dissertation</option>
                        <option value="Journal Article" {{ request('category') == 'Journal Article' ? 'selected' : '' }}>Journal Article</option>
                        <option value="Conference Paper" {{ request('category') == 'Conference Paper' ? 'selected' : '' }}>Conference Paper</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="scholar" class="block text-sm font-medium text-gray-700 mb-1">Scholar</label>
                    <input type="text" id="scholar" name="scholar" value="{{ request('scholar') }}" placeholder="Scholar Name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Title</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Search by title" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
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

        <!-- Manuscripts Table -->
        <div id="manuscripts-container" class="bg-white rounded-lg overflow-hidden shadow border border-gray-200">
            @include('admin.manuscripts._manuscript_list')
        </div>

        <!-- Pagination -->
        <div class="mt-6" id="pagination-container">
            {{ $manuscripts->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButton = document.getElementById('filter-button');
        const filterForm = document.getElementById('filter-form');
        const manuscriptsContainer = document.getElementById('manuscripts-container');
        const paginationContainer = document.getElementById('pagination-container');
        const loadingIndicator = document.getElementById('loading-indicator');
        const exportButton = document.getElementById('export-button');
        const baseExportUrl = "{{ route('admin.manuscripts.export') }}";

        // Filter manuscripts with AJAX
        filterButton.addEventListener('click', function() {
            filterManuscripts();
        });

        // Also filter when select elements change
        document.querySelectorAll('#filter-form select').forEach(element => {
            element.addEventListener('change', function() {
                filterManuscripts();
            });
        });

        // Filter when enter is pressed in text inputs
        document.querySelectorAll('#filter-form input').forEach(element => {
            element.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    filterManuscripts();
                }
            });
        });

        // Update export button with current filters
        function updateExportUrl() {
            const formData = new FormData(filterForm);
            const params = new URLSearchParams();

            for (let [key, value] of formData.entries()) {
                if (value) {
                    params.append(key, value);
                }
            }

            const queryString = params.toString();

            if (queryString) {
                exportButton.href = `${baseExportUrl}?${queryString}`;
            } else {
                exportButton.href = baseExportUrl;
            }
        }

        // Initial update of export URL
        updateExportUrl();

        // Update export URL when filters change
        document.querySelectorAll('#filter-form select, #filter-form input').forEach(element => {
            element.addEventListener('change', updateExportUrl);
            element.addEventListener('keyup', updateExportUrl);
        });

        // Function to filter manuscripts
        function filterManuscripts(page = 1) {
            // Show loading indicator
            loadingIndicator.classList.remove('hidden');
            manuscriptsContainer.classList.add('opacity-50');

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

            // Update export button URL
            updateExportUrl();

            // Fetch filtered results
            fetch(`/admin/manuscripts/filter?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    // Update manuscripts container
                    manuscriptsContainer.innerHTML = data.html;

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
                    manuscriptsContainer.classList.remove('opacity-50');
                });
        }

        // Setup pagination links
        function setupPaginationLinks() {
            document.querySelectorAll('#pagination-container a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const pageUrl = new URL(this.href);
                    const page = pageUrl.searchParams.get('page') || 1;
                    filterManuscripts(page);
                });
            });
        }

        // Initial setup for pagination
        setupPaginationLinks();
    });
</script>
@endsection
