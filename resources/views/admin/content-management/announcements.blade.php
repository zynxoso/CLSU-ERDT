@extends('layouts.app')

@section('title', 'Announcements Management')

@section('content')
<div style="background-color: #FAFAFA; min-height: 100vh; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">
    <div class="container mx-auto px-6 py-8">
        <!-- Breadcrumb Navigation -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('admin.content-management.index') }}" class="text-blue-600 hover:text-blue-800">Content Management</a>
                </li>
                <li class="text-gray-500">/</li>
                <li class="text-gray-700 font-medium">Announcements</li>
            </ol>
        </nav>

        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold mb-2" style="color: #212121;">Announcements Management</h1>
                <p class="text-lg" style="color: #424242;">Create and manage announcements for scholars and visitors</p>
            </div>
            <button onclick="openAnnouncementModal()" 
                    class="btn btn-primary inline-flex items-center px-6 py-3 rounded-lg font-medium transition-all duration-200 hover:shadow-md"
                    style="background-color: #2E7D32; color: white;">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Announcement
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

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.content-management.index', ['tab' => 'announcements']) }}" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-64">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Announcements</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                               placeholder="Search by title or content..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="min-w-48">
                        <label for="filter_type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select id="filter_type" name="filter_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Types</option>
                            <option value="general" {{ request('filter_type') == 'general' ? 'selected' : '' }}>General</option>
                            <option value="application" {{ request('filter_type') == 'application' ? 'selected' : '' }}>Application</option>
                            <option value="scholarship" {{ request('filter_type') == 'scholarship' ? 'selected' : '' }}>Scholarship</option>
                            <option value="event" {{ request('filter_type') == 'event' ? 'selected' : '' }}>Event</option>
                            <option value="urgent" {{ request('filter_type') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>
                    <div class="min-w-48">
                        <label for="filter_status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="filter_status" name="filter_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Status</option>
                            <option value="1" {{ request('filter_status') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('filter_status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                        <a href="{{ route('admin.content-management.index', ['tab' => 'announcements']) }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                            <i class="fas fa-times mr-2"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Announcements Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($announcements) && $announcements->count() > 0)
                            @foreach($announcements as $announcement)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $announcement->title }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($announcement->content, 60) }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full text-white"
                                              style="background-color: {{ getTypeColor($announcement->type) }}">
                                            {{ ucfirst($announcement->type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-medium text-gray-900">{{ $announcement->priority }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button onclick="toggleAnnouncementStatus({{ $announcement->id }})" 
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full text-white transition-colors duration-200"
                                                style="background-color: {{ $announcement->is_active ? '#4CAF50' : '#757575' }}">
                                            {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $announcement->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button onclick="editAnnouncement({{ $announcement->id }})" 
                                                    class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                                Edit
                                            </button>
                                            <button onclick="deleteAnnouncement({{ $announcement->id }})" 
                                                    class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition-colors">
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                        </svg>
                                        <p class="text-lg font-medium text-gray-500">No announcements found</p>
                                        <p class="text-sm text-gray-400">Create your first announcement to get started</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Announcement Modal -->
<div id="announcementModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Add New Announcement</h3>
                <button onclick="closeAnnouncementModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="announcementForm" method="POST">
                @csrf
                <input type="hidden" id="announcementId" name="announcement_id">
                <input type="hidden" id="formMethod" name="_method" value="POST">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                        <input type="text" id="title" name="title" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                        <select id="type" name="type" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Type</option>
                            <option value="general">General</option>
                            <option value="application">Application</option>
                            <option value="scholarship">Scholarship</option>
                            <option value="event">Event</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                        <select id="priority" name="priority" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Priority</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div>
                        <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="is_active" name="is_active"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                    <textarea id="content" name="content" rows="6" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Enter announcement content..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAnnouncementModal()"
                            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Save Announcement
                    </button>
                </div>
            </form>
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

/* Card styling */
.bg-white {
    transition: all 0.3s ease;
}
</style>

<script>
function openAnnouncementModal() {
    document.getElementById('announcementModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = 'Add New Announcement';
    document.getElementById('announcementForm').action = '/admin/content-management/announcements';
    document.getElementById('formMethod').value = 'POST';
    resetForm();
}

function closeAnnouncementModal() {
    document.getElementById('announcementModal').classList.add('hidden');
    resetForm();
}

function resetForm() {
    document.getElementById('announcementForm').reset();
    document.getElementById('announcementId').value = '';
}

function editAnnouncement(id) {
    fetch(`/admin/content-management/announcements/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const announcement = data.announcement;
                document.getElementById('announcementId').value = announcement.id;
                document.getElementById('title').value = announcement.title;
                document.getElementById('type').value = announcement.type;
                document.getElementById('priority').value = announcement.priority;
                document.getElementById('content').value = announcement.content;
                document.getElementById('is_active').value = announcement.is_active ? '1' : '0';
                
                document.getElementById('modalTitle').textContent = 'Edit Announcement';
                document.getElementById('announcementForm').action = `/admin/content-management/announcements/${id}`;
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('announcementModal').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading announcement data');
        });
}

function toggleAnnouncementStatus(id) {
    if (confirm('Are you sure you want to change the status of this announcement?')) {
        fetch(`/admin/content-management/announcements/${id}/toggle-status`, {
            method: 'PATCH',
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
                alert('Error updating announcement status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating announcement status');
        });
    }
}

function deleteAnnouncement(id) {
    if (confirm('Are you sure you want to delete this announcement? This action cannot be undone.')) {
        fetch(`/admin/content-management/announcements/${id}`, {
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
                alert('Error deleting announcement');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting announcement');
        });
    }
}

// Helper function for type colors (you may need to define this in your controller or as a global function)
function getTypeColor(type) {
    const colors = {
        'general': '#6B7280',
        'application': '#3B82F6',
        'scholarship': '#10B981',
        'event': '#F59E0B',
        'urgent': '#EF4444'
    };
    return colors[type] || '#6B7280';
}
</script>
@endsection