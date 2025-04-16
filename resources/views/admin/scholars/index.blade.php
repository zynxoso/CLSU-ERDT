@extends('layouts.app')

@section('title', 'Manage Scholars')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6 animate-fade-in">
            <h1 class="text-2xl font-bold text-gray-900">Manage Scholars</h1>
            <x-button
                href="{{ route('admin.scholars.create') }}"
                variant="primary"
                icon="fas fa-user-plus text-white"
                animate="true"
            >
                Add New Scholar
            </x-button>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 relative" role="alert">
                <p class="font-bold">Success!</p>
                <p>{!! session('success') !!}</p>
                <button class="absolute top-0 right-0 mt-4 mr-4 text-green-700 hover:text-green-900" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 relative" role="alert">
                <p class="font-bold">Error!</p>
                <p>{{ session('error') }}</p>
                <button class="absolute top-0 right-0 mt-4 mr-4 text-red-700 hover:text-red-900" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Filters -->
        <div class="rounded-lg p-4 mb-6 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 animate-fade-in" style="animation-delay: 0.1s">
            <form id="filter-form" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-sm font-medium text-gray-600 mb-1">Status</label>
                    <select id="status" name="status" class="w-full border border-gray-200 rounded-md px-3 py-2 text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                        <option value="">All Statuses</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Ongoing" {{ request('status') == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Graduated" {{ request('status') == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                        <option value="Terminated" {{ request('status') == 'Terminated' ? 'selected' : '' }}>Terminated</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="program" class="block text-sm font-medium text-gray-600 mb-1">Program</label>
                    <select id="program" name="program" class="w-full border border-gray-200 rounded-md px-3 py-2 text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                        <option value="">All Programs</option>
                        <option value="PhD" {{ request('program') == 'PhD' ? 'selected' : '' }}>PhD</option>
                        <option value="Masters" {{ request('program') == 'Masters' ? 'selected' : '' }}>Masters</option>
                        <option value="Master in Agricultural and Biosystems Engineering" {{ request('program') == 'Master in Agricultural and Biosystems Engineering' ? 'selected' : '' }}>Master in ABE</option>
                        <option value="Master of Science in Agricultural and Biosystems Engineering" {{ request('program') == 'Master of Science in Agricultural and Biosystems Engineering' ? 'selected' : '' }}>MS in ABE</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="search" class="block text-sm font-medium text-gray-600 mb-1">Search</label>
                    <div class="relative">
                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Name, Email, or University"
                            class="w-full border border-gray-200 rounded-md pl-10 pr-3 py-2 text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200"
                        >
                    </div>
                </div>
                <div class="flex items-end w-full sm:w-auto">
                    <x-button
                        type="button"
                        id="filter-button"
                        variant="primary"
                        icon="fas fa-filter"
                        animate="true"
                    >
                        Filter
                    </x-button>
                </div>
            </form>
        </div>

        <!-- Loading indicator -->
        <div id="loading-indicator" class="hidden">
            <div class="flex justify-center items-center py-8">
                <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-200 border-t-blue-700"></div>
            </div>
        </div>

        <!-- Scholars Table -->
        <div id="scholars-container" class="rounded-lg overflow-hidden border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 animate-fade-in" style="animation-delay: 0.2s">
            @include('admin.scholars._scholar_list')
        </div>

        <!-- Pagination -->
        <div class="mt-6 animate-fade-in" style="animation-delay: 0.3s" id="pagination-container">
            {{ $scholars->links() }}
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<x-modal id="delete-confirmation-modal" maxWidth="sm">
    <x-slot name="title">
        <div class="flex items-center">
            <i class="fas fa-trash-alt text-red-500 mr-2"></i>
            <span>Delete Scholar</span>
        </div>
    </x-slot>

    <p class="mb-3">Are you sure you want to delete this scholar? This action cannot be undone.</p>
    <p class="font-semibold scholar-name mb-2"></p>

    <x-slot name="footer">
        <div class="flex justify-end space-x-3">
            <x-button
                variant="outline-secondary"
                size="sm"
                @click="open = false"
            >
                Cancel
            </x-button>

            <form id="delete-form" method="POST">
                @csrf
                @method('DELETE')
                <x-button
                    type="submit"
                    variant="danger"
                    size="sm"
                >
                    Delete
                </x-button>
            </form>
        </div>
    </x-slot>
</x-modal>
@endsection

@section('styles')
<style>
    /* Animations and transitions */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }

    /* Table row hover effect */
    #scholars-container tbody tr {
        transition: all 0.2s ease-in-out;
    }

    #scholars-container tbody tr:hover {
        background-color: rgba(59, 130, 246, 0.05);
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    /* Button hover effects */
    .action-icon {
        transition: all 0.2s ease;
    }

    .action-icon:hover {
        transform: scale(1.2);
    }

    /* Progress bar animation */
    @keyframes progressGrow {
        from { width: 0%; }
        to { width: var(--progress-width); }
    }

    .animate-progress {
        animation: progressGrow 1s ease-out forwards;
    }

    /* Skeleton loading placeholder */
    .skeleton-pulse {
        animation: pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        background: linear-gradient(90deg, rgba(226, 232, 240, 0.1) 25%, rgba(226, 232, 240, 0.3) 50%, rgba(226, 232, 240, 0.1) 75%);
        background-size: 200% 100%;
    }

    @keyframes pulse {
        0% { background-position: 0% 0; }
        100% { background-position: -200% 0; }
    }

    /* Fix for search input and icon */
    #search::placeholder {
        color: #9ca3af;
        opacity: 1;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButton = document.getElementById('filter-button');
        const filterForm = document.getElementById('filter-form');
        const scholarsContainer = document.getElementById('scholars-container');
        const paginationContainer = document.getElementById('pagination-container');
        const loadingIndicator = document.getElementById('loading-indicator');

        // Apply transition classes to table cells for better animation control
        applyTableAnimations();

        // Filter scholars with AJAX
        filterButton.addEventListener('click', function() {
            filterScholars();
        });

        // Also filter when select elements change
        document.querySelectorAll('#filter-form select').forEach(element => {
            element.addEventListener('change', function() {
                filterScholars();
            });
        });

        // Filter when enter is pressed in text inputs
        document.querySelectorAll('#filter-form input').forEach(element => {
            element.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    filterScholars();
                }
            });
        });

        // Function to filter scholars
        function filterScholars(page = 1) {
            // Add button loading state
            filterButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Filtering...';
            filterButton.disabled = true;

            // Show loading indicator
            loadingIndicator.classList.remove('hidden');
            scholarsContainer.classList.add('opacity-50');
            scholarsContainer.style.transition = 'opacity 0.3s ease';

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
            fetch(`/admin/scholars/filter?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    // Update scholars container with fade effect
                    scholarsContainer.style.opacity = '0';

                    setTimeout(() => {
                        // Update scholars container
                        scholarsContainer.innerHTML = data.html;

                        // Update pagination
                        paginationContainer.innerHTML = data.pagination;

                        // Restore opacity with transition
                        scholarsContainer.style.opacity = '1';

                        // Apply animations to the new content
                        applyTableAnimations();

                        // Setup pagination links
                        setupPaginationLinks();

                        // Setup delete confirmations
                        setupDeleteConfirmations();

                        // Show success toast
                        showToast('Scholars list updated successfully', 'success');
                    }, 300);
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Show error toast
                    showToast('An error occurred while filtering scholars', 'error');
                })
                .finally(() => {
                    // Hide loading indicator
                    loadingIndicator.classList.add('hidden');
                    scholarsContainer.classList.remove('opacity-50');

                    // Reset button state
                    filterButton.innerHTML = '<i class="fas fa-filter mr-2"></i> Filter';
                    filterButton.disabled = false;
                });
        }

        // Setup pagination links
        function setupPaginationLinks() {
            document.querySelectorAll('#pagination-container a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const pageUrl = new URL(this.href);
                    const page = pageUrl.searchParams.get('page') || 1;

                    // Scroll to top of container with smooth animation
                    scholarsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });

                    // Filter after small delay to allow scroll animation
                    setTimeout(() => {
                        filterScholars(page);
                    }, 300);
                });

                // Add hover animation to pagination links
                link.classList.add('transition-all', 'duration-200', 'hover:scale-110');
            });
        }

        // Apply animations to table elements
        function applyTableAnimations() {
            // Progress bars animation
            const progressBars = document.querySelectorAll('#scholars-container .bg-blue-600');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.setProperty('--progress-width', width);
                bar.style.width = '0';

                setTimeout(() => {
                    bar.classList.add('animate-progress');
                }, 100);
            });

            // Action icons animation
            const actionIcons = document.querySelectorAll('#scholars-container td:last-child a, #scholars-container td:last-child button');
            actionIcons.forEach(icon => {
                icon.classList.add('action-icon');
            });
        }

        // Setup delete confirmation modal
        function setupDeleteConfirmations() {
            document.querySelectorAll('.delete-scholar-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    const scholarId = this.dataset.id;
                    const scholarName = this.dataset.name;
                    const deleteUrl = `/admin/scholars/${scholarId}`;

                    // Update modal content
                    document.querySelector('#delete-confirmation-modal .scholar-name').textContent = scholarName;
                    document.querySelector('#delete-form').action = deleteUrl;

                    // Open modal
                    openModal('delete-confirmation-modal');
                });
            });
        }

        // Initial setup
        setupPaginationLinks();
        setupDeleteConfirmations();
    });
</script>
@endsection
