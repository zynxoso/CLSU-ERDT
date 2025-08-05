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
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
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
                                            onclick="editAnnouncement({{ $announcement->id }})" 
                                            data-bs-toggle="modal" data-bs-target="#editAnnouncementModal">
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
<div class="modal fade" id="addAnnouncementModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.content-management.announcements.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Announcement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Type *</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="general">General</option>
                                <option value="urgent">Urgent</option>
                                <option value="event">Event</option>
                                <option value="academic">Academic</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="priority" class="form-label">Priority *</label>
                            <select class="form-select" id="priority" name="priority" required>
                                <option value="">Select Priority</option>
                                <option value="low">Low</option>
                                <option value="normal">Normal</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="content" class="form-label">Content *</label>
                            <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="published_at" class="form-label">Published Date</label>
                            <input type="datetime-local" class="form-control" id="published_at" name="published_at">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="expires_at" class="form-label">Expires Date</label>
                            <input type="datetime-local" class="form-control" id="expires_at" name="expires_at">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Create Announcement</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Announcement Modal -->
<div class="modal fade" id="editAnnouncementModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="editAnnouncementForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Announcement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="edit_title" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_type" class="form-label">Type *</label>
                            <select class="form-select" id="edit_type" name="type" required>
                                <option value="general">General</option>
                                <option value="urgent">Urgent</option>
                                <option value="event">Event</option>
                                <option value="academic">Academic</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_priority" class="form-label">Priority *</label>
                            <select class="form-select" id="edit_priority" name="priority" required>
                                <option value="low">Low</option>
                                <option value="normal">Normal</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="edit_content" class="form-label">Content *</label>
                            <textarea class="form-control" id="edit_content" name="content" rows="5" required></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_published_at" class="form-label">Published Date</label>
                            <input type="datetime-local" class="form-control" id="edit_published_at" name="published_at">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_expires_at" class="form-label">Expires Date</label>
                            <input type="datetime-local" class="form-control" id="edit_expires_at" name="expires_at">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Announcement</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editAnnouncement(id) {
    // This would typically fetch announcement data via AJAX
    // For now, we'll set the form action
    document.getElementById('editAnnouncementForm').action = `/admin/content-management/announcements/${id}`;
    
    // In a real implementation, you'd fetch the announcement data and populate the form
    // fetch(`/admin/content-management/announcements/${id}/edit`)
    //     .then(response => response.json())
    //     .then(data => {
    //         document.getElementById('edit_title').value = data.title;
    //         document.getElementById('edit_type').value = data.type;
    //         // ... populate other fields
    //     });
}
</script>