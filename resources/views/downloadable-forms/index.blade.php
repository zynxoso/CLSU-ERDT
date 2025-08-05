@extends('layouts.app')

@section('title', 'Downloadable Forms')

@section('content')
<div class="container mx-auto py-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Downloadable Forms</h1>
            <p class="text-gray-600 mt-1">Access and download important forms and documents</p>
        </div>
        
        @if(auth()->check() && auth()->user()->role === 'super_admin')
            <div class="flex space-x-3">
                <a href="{{ route('super_admin.downloadable-forms.create') }}" 
                   class="bg-clsu-maroon hover:bg-red-700 text-white font-semibold py-2 px-4 rounded inline-flex items-center text-sm">
                    <svg class="w-4 h-4 mr-2 fill-none stroke-2" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Add New Form
                </a>
            </div>
        @endif
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white shadow-md rounded-lg p-4 mb-6">
        <form method="GET" action="{{ request()->routeIs('super_admin.*') ? route('super_admin.downloadable-forms.index') : route('downloadable-forms.public.index') }}" class="flex flex-wrap gap-4">
            <!-- Search -->
            <div class="flex-1 min-w-64">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search forms..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-clsu-maroon focus:border-clsu-maroon">
            </div>
            
            <!-- Category Filter -->
            <div class="min-w-48">
                <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-clsu-maroon focus:border-clsu-maroon">
                    <option value="">All Categories</option>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Search Button -->
            <button type="submit" class="bg-clsu-green hover:bg-green-700 text-white font-semibold py-2 px-6 rounded">
                Search
            </button>
            
            <!-- Clear Filters -->
            @if(request()->hasAny(['search', 'category']))
                <a href="{{ request()->routeIs('super_admin.*') ? route('super_admin.downloadable-forms.index') : route('downloadable-forms.public.index') }}" 
                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded">
                    Clear
                </a>
            @endif
        </form>
    </div>

    @if(auth()->check() && auth()->user()->role === 'super_admin')
        <!-- Bulk Actions (Super Admin Only) -->
        <div class="bg-white shadow-md rounded-lg p-4 mb-6" id="bulk-actions" style="display: none;">
            <form method="POST" action="{{ route('super_admin.downloadable-forms.bulk-action') }}" id="bulk-form">
                @csrf
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-gray-700">Bulk Actions:</span>
                    <select name="action" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="activate">Activate Selected</option>
                        <option value="deactivate">Deactivate Selected</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                    <button type="submit" class="bg-clsu-maroon hover:bg-red-700 text-white font-semibold py-2 px-4 rounded text-sm"
                            onclick="return confirm('Are you sure you want to perform this action?')">
                        Apply
                    </button>
                    <button type="button" onclick="clearSelection()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded text-sm">
                        Clear Selection
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Forms Grid -->
    @if($forms->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($forms as $form)
                <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-200">
                    @if(auth()->check() && auth()->user()->role === 'super_admin')
                        <!-- Checkbox for bulk actions -->
                        <div class="p-3 border-b border-gray-100">
                            <label class="flex items-center">
                                <input type="checkbox" name="forms[]" value="{{ $form->id }}" class="form-checkbox" onchange="toggleBulkActions()">
                                <span class="ml-2 text-sm text-gray-600">Select</span>
                            </label>
                        </div>
                    @endif
                    
                    <!-- Form Header -->
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $form->title }}</h3>
                                <span class="inline-block px-2 py-1 text-xs font-medium rounded-full 
                                    {{ $form->category === 'application' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $form->category === 'scholarship' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $form->category === 'research' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $form->category === 'administrative' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $form->category === 'academic' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                    {{ $form->category === 'other' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $categories[$form->category] ?? 'Other' }}
                                </span>
                            </div>
                            
                            @if(auth()->check() && auth()->user()->role === 'super_admin')
                                <div class="flex items-center space-x-1">
                                    <span class="inline-block w-3 h-3 rounded-full {{ $form->status ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                    <span class="text-xs text-gray-500">{{ $form->status ? 'Active' : 'Inactive' }}</span>
                                </div>
                            @endif
                        </div>
                        
                        @if($form->description)
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $form->description }}</p>
                        @endif
                        
                        <!-- Form Details -->
                        <div class="space-y-2 text-sm text-gray-500">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>{{ $form->filename }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 110-2h4z"></path>
                                </svg>
                                <span>{{ $form->formatted_file_size }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>{{ $form->download_count }} downloads</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            @if($form->status || (auth()->check() && auth()->user()->role === 'super_admin'))
                                <a href="{{ route('downloadable-forms.download', $form) }}" 
                                   class="bg-clsu-green hover:bg-green-700 text-white font-semibold py-2 px-4 rounded text-sm inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download
                                </a>
                            @else
                                <span class="text-gray-400 text-sm">Unavailable</span>
                            @endif
                            
                            @if(auth()->check() && auth()->user()->role === 'super_admin')
                                <div class="flex space-x-2">
                                    <a href="{{ route('super_admin.downloadable-forms.edit', $form) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('super_admin.downloadable-forms.toggle-status', $form) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-{{ $form->status ? 'red' : 'green' }}-600 hover:text-{{ $form->status ? 'red' : 'green' }}-800 text-sm">
                                            {{ $form->status ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('super_admin.downloadable-forms.destroy', $form) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm" 
                                                onclick="return confirm('Are you sure you want to delete this form?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $forms->appends(request()->query())->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white shadow-md rounded-lg p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No forms found</h3>
            <p class="text-gray-500 mb-4">
                @if(request()->hasAny(['search', 'category']))
                    No forms match your current search criteria.
                @else
                    There are no downloadable forms available at the moment.
                @endif
            </p>
            @if(request()->hasAny(['search', 'category']))
                <a href="{{ request()->routeIs('super_admin.*') ? route('super_admin.downloadable-forms.index') : route('downloadable-forms.public.index') }}" 
                   class="text-clsu-maroon hover:text-red-700 font-medium">
                    Clear filters
                </a>
            @endif
        </div>
    @endif
</div>

@if(auth()->check() && auth()->user()->role === 'super_admin')
<script>
function toggleBulkActions() {
    const checkboxes = document.querySelectorAll('input[name="forms[]"]');
    const checkedBoxes = document.querySelectorAll('input[name="forms[]"]:checked');
    const bulkActions = document.getElementById('bulk-actions');
    
    if (checkedBoxes.length > 0) {
        bulkActions.style.display = 'block';
        // Add checked form IDs to bulk form
        const bulkForm = document.getElementById('bulk-form');
        // Remove existing hidden inputs
        bulkForm.querySelectorAll('input[name="forms[]"]').forEach(input => input.remove());
        // Add new hidden inputs
        checkedBoxes.forEach(checkbox => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'forms[]';
            hiddenInput.value = checkbox.value;
            bulkForm.appendChild(hiddenInput);
        });
    } else {
        bulkActions.style.display = 'none';
    }
}

function clearSelection() {
    const checkboxes = document.querySelectorAll('input[name="forms[]"]');
    checkboxes.forEach(checkbox => checkbox.checked = false);
    toggleBulkActions();
}
</script>
@endif
@endsection