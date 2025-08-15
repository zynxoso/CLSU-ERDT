<div class="tab-pane fade show active" id="announcements" role="tabpanel">
    <!-- Search and Filter Bar -->
    <div class="row mb-3">
        <div class="col-md-12">
            <form method="GET" action="{{ route('admin.content-management.index') }}" class="d-flex gap-2">
                <input type="hidden" name="tab" value="announcements">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search announcements..." value="{{ $searchTerm }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <select name="filter_type" class="form-select" style="max-width: 200px;">
                    <option value="">All Types</option>
                    @if(isset($announcementTypes))
                        @foreach($announcementTypes as $type)
                            <option value="{{ $type }}" {{ $filterType === $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    @endif
                </select>
                <select name="filter_status" class="form-select" style="max-width: 150px;">
                    <option value="">All Status</option>
                    <option value="active" {{ $filterStatus === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $filterStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.content-management.index', ['tab' => 'announcements']) }}" class="btn btn-secondary">Clear</a>
            </form>
        </div>
    </div>

    <!-- Add New Button -->
    <div class="row mb-3">
        <div class="col-md-12">
            <button type="button" class="btn btn-success" onclick="openModal('addAnnouncementModal')">
                <i class="fas fa-plus"></i> Add New Announcement
            </button>
        </div>
    </div>

    <!-- Announcements Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th>Expires</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($announcements) && $announcements->count() > 0)
                    @foreach($announcements as $announcement)
                        <tr>
                            <td>
                                <strong>{{ $announcement->title }}</strong>
                                <br>
                                <small class="text-muted">{{ Str::limit($announcement->content, 100) }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($announcement->type) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $announcement->priority === 'high' ? 'danger' : ($announcement->priority === 'normal' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($announcement->priority) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $announcement->is_active ? 'success' : 'secondary' }}">
                                    {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $announcement->published_at ? $announcement->published_at->format('M d, Y') : 'Not set' }}</td>
                            <td>{{ $announcement->expires_at ? $announcement->expires_at->format('M d, Y') : 'No expiry' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="editAnnouncement({{ $announcement->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-{{ $announcement->is_active ? 'warning' : 'success' }}" 
                                            onclick="toggleStatus('announcements', {{ $announcement->id }})">
                                        <i class="fas fa-{{ $announcement->is_active ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteItem('announcements', {{ $announcement->id }}, '{{ addslashes($announcement->title) }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No announcements found.</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(isset($announcements) && $announcements->hasPages())
        <div class="d-flex justify-content-center">
            {{ $announcements->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Add Announcement Modal -->
<x-modal id="addAnnouncementModal" maxWidth="lg">
    <x-slot name="title">
        Add New Announcement
    </x-slot>
    
    <form method="POST" action="{{ route('admin.content-management.announcements.store') }}">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="title" name="title" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="type" name="type" required>
                        <option value="">Select Type</option>
                        <option value="general">General</option>
                        <option value="urgent">Urgent</option>
                        <option value="event">Event</option>
                        <option value="academic">Academic</option>
                    </select>
                </div>
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority *</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="priority" name="priority" required>
                        <option value="">Select Priority</option>
                        <option value="low">Low</option>
                        <option value="normal">Normal</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content *</label>
                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="content" name="content" rows="5" required></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1">Published Date</label>
                    <input type="datetime-local" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="published_at" name="published_at">
                </div>
                <div>
                    <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-1">Expires Date</label>
                    <input type="datetime-local" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="expires_at" name="expires_at">
                </div>
            </div>
        </div>
        
        <x-slot name="footer">
            <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500" onclick="closeModal('addAnnouncementModal')">Cancel</button>
            <button type="submit" class="ml-3 px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Create Announcement</button>
        </x-slot>
    </form>
</x-modal>

<!-- Edit Announcement Modal -->
<x-modal id="editAnnouncementModal" maxWidth="lg">
    <x-slot name="title">
        Edit Announcement
    </x-slot>
    
    <form method="POST" action="" id="editAnnouncementForm">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <label for="edit_title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_title" name="title" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="edit_type" class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_type" name="type" required>
                        <option value="">Select Type</option>
                        <option value="general">General</option>
                        <option value="urgent">Urgent</option>
                        <option value="event">Event</option>
                        <option value="academic">Academic</option>
                    </select>
                </div>
                <div>
                    <label for="edit_priority" class="block text-sm font-medium text-gray-700 mb-1">Priority *</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_priority" name="priority" required>
                        <option value="">Select Priority</option>
                        <option value="low">Low</option>
                        <option value="normal">Normal</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>
            <div>
                <label for="edit_content" class="block text-sm font-medium text-gray-700 mb-1">Content *</label>
                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_content" name="content" rows="5" required></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="edit_published_at" class="block text-sm font-medium text-gray-700 mb-1">Published Date</label>
                    <input type="datetime-local" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_published_at" name="published_at">
                </div>
                <div>
                    <label for="edit_expires_at" class="block text-sm font-medium text-gray-700 mb-1">Expires Date</label>
                    <input type="datetime-local" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_expires_at" name="expires_at">
                </div>
            </div>
        </div>
        
        <x-slot name="footer">
            <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500" onclick="closeModal('editAnnouncementModal')">Cancel</button>
            <button type="submit" class="ml-3 px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Update Announcement</button>
        </x-slot>
    </form>
</x-modal>

<script>
function editAnnouncement(id) {
    fetch(`/admin/content-management/announcements/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_title').value = data.title;
            document.getElementById('edit_type').value = data.type;
            document.getElementById('edit_priority').value = data.priority;
            document.getElementById('edit_content').value = data.content;
            document.getElementById('edit_published_at').value = data.published_at ? data.published_at.slice(0, 16) : '';
            document.getElementById('edit_expires_at').value = data.expires_at ? data.expires_at.slice(0, 16) : '';
            document.getElementById('editAnnouncementForm').action = `/admin/content-management/announcements/${id}`;
            openModal('editAnnouncementModal');
        })
        .catch(error => console.error('Error:', error));
}
</script>
