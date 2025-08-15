@extends('layouts.app')

@section('title', 'Application Timeline Management')

@section('content')
<div class="bg-gray-50 min-h-screen font-sans">
    <div class="container mx-auto px-6 py-8">
        <!-- Breadcrumb Navigation -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('admin.content-management.index') }}" class="text-blue-600 hover:text-blue-800">Content Management</a>
                </li>
                <li class="text-gray-500">/</li>
                <li class="text-gray-700 font-medium">Application Timeline</li>
            </ol>
        </nav>

        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold mb-2" style="color: rgb(23 23 23);">Application Timeline Management</h1>
                <p class="text-lg" style="color: rgb(64 64 64);">Manage application timeline items that appear on the public website</p>
            </div>
            <button onclick="openTimelineModal()" 
                    class="btn btn-primary inline-flex items-center px-6 py-3 rounded-lg font-medium transition-all duration-200 hover:shadow-md"
                    style="background-color: rgb(21 128 61); color: rgb(255 255 255);">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Timeline Item
            </button>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Timeline Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phase</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sort Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($timelines) && $timelines->count() > 0)
                            @foreach($timelines as $timeline)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $timeline->title }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($timeline->description, 60) }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-medium text-gray-900">{{ $timeline->phase }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $timeline->start_date ? $timeline->start_date->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $timeline->end_date ? $timeline->end_date->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $timeline->sort_order }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <button onclick="toggleTimelineStatus({{ $timeline->id }})" 
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full text-white transition-colors duration-200"
                                                style="background-color: {{ $timeline->is_active ? '#4CAF50' : '#757575' }}">
                                            {{ $timeline->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button onclick="editTimeline({{ $timeline->id }})" 
                                                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                                Edit
                                            </button>
                                            <button onclick="deleteTimeline({{ $timeline->id }})" 
                                                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center mt-5">
                                        <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8m-8 0H4a2 2 0 00-2 2v6a2 2 0 002 2h16a2 2 0 002-2v-6a2 2 0 00-2-2h-4"></path>
                                        </svg>
                                        <p class="text-lg font-medium text-gray-500">No timeline items found</p>
                                        <p class="text-sm text-gray-400">Create your first timeline item to get started</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Timeline Modal -->
        <div id="timelineModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Add Timeline Item</h3>
                        <button onclick="closeTimelineModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form id="timelineForm" method="POST">
                        @csrf
                        <input type="hidden" id="timelineId" name="timeline_id">
                        <input type="hidden" id="formMethod" name="_method">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                                <input type="text" id="title" name="title" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="phase" class="block text-sm font-medium text-gray-700 mb-2">Phase *</label>
                                <input type="text" id="phase" name="phase" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                                <input type="date" id="start_date" name="start_date"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                                <input type="date" id="end_date" name="end_date"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                                <input type="number" id="sort_order" name="sort_order" min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" id="is_active" name="is_active" value="1" checked
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeTimelineModal()" 
                                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                Save Timeline Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Breadcrumb styling */
nav ol li a:hover {
    text-decoration: underline;
}

/* Button hover effects */
.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}


</style>

<script>
    // Timeline Modal Functions
    function openTimelineModal(timelineData = null) {
        const modal = document.getElementById('timelineModal');
        const form = document.getElementById('timelineForm');
        const modalTitle = document.getElementById('modalTitle');
        
        if (timelineData) {
            // Edit mode
            modalTitle.textContent = 'Edit Timeline Item';
            form.action = `/admin/content-management/timeline/${timelineData.id}`;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('timelineId').value = timelineData.id;
            
            // Populate form fields
            document.getElementById('title').value = timelineData.title || '';
            document.getElementById('phase').value = timelineData.phase || '';
            document.getElementById('description').value = timelineData.description || '';
            document.getElementById('start_date').value = timelineData.start_date || '';
            document.getElementById('end_date').value = timelineData.end_date || '';
            document.getElementById('sort_order').value = timelineData.sort_order || 0;
            document.getElementById('is_active').checked = timelineData.is_active;
        } else {
            // Create mode
            modalTitle.textContent = 'Add Timeline Item';
            form.action = '/admin/content-management/timeline';
            document.getElementById('formMethod').value = '';
            resetTimelineForm();
        }
        
        modal.classList.remove('hidden');
    }
    
    function closeTimelineModal() {
        document.getElementById('timelineModal').classList.add('hidden');
        resetTimelineForm();
    }
    
    function resetTimelineForm() {
        document.getElementById('timelineForm').reset();
        document.getElementById('timelineId').value = '';
        document.getElementById('is_active').checked = true;
    }
    
    function editTimeline(timelineId) {
        // Fetch timeline data and open modal
        fetch(`/admin/content-management/timeline/${timelineId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    openTimelineModal(data.timeline);
                } else {
                    alert('Error loading timeline data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading timeline data');
            });
    }
    
    function toggleTimelineStatus(timelineId) {
        if (confirm('Are you sure you want to toggle the status of this timeline item?')) {
            fetch(`/admin/content-management/timeline/${timelineId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error updating timeline status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating timeline status');
            });
        }
    }
    
    function deleteTimeline(timelineId) {
        if (confirm('Are you sure you want to delete this timeline item? This action cannot be undone.')) {
            fetch(`/admin/content-management/timeline/${timelineId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error deleting timeline item');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting timeline item');
            });
        }
    }
    
    // Close modal when clicking outside
    document.getElementById('timelineModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeTimelineModal();
        }
    });
</script>
@endsection
