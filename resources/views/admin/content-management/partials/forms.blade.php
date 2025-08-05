<div class="tab-pane fade show active" id="forms" role="tabpanel">
    <!-- Search and Filter Bar -->
    <div class="row mb-3">
        <div class="col-md-12">
            <form method="GET" action="{{ route('admin.content-management.index') }}" class="d-flex gap-2">
                <input type="hidden" name="tab" value="forms">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search forms..." value="{{ $searchTerm }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <select name="filter_category" class="form-select" style="max-width: 150px;">
                    <option value="">All Categories</option>
                    <option value="application" {{ $filterCategory === 'application' ? 'selected' : '' }}>Application</option>
                    <option value="report" {{ $filterCategory === 'report' ? 'selected' : '' }}>Report</option>
                    <option value="request" {{ $filterCategory === 'request' ? 'selected' : '' }}>Request</option>
                    <option value="other" {{ $filterCategory === 'other' ? 'selected' : '' }}>Other</option>
                </select>
                <select name="filter_status" class="form-select" style="max-width: 150px;">
                    <option value="">All Status</option>
                    <option value="active" {{ $filterStatus === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $filterStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.content-management.index', ['tab' => 'forms']) }}" class="btn btn-secondary">Clear</a>
            </form>
        </div>
    </div>

    <!-- Add New Button -->
    <div class="row mb-3">
        <div class="col-md-12">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addFormModal">
                <i class="fas fa-plus"></i> Add New Form
            </button>
        </div>
    </div>

    <!-- Forms Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>File</th>
                    <th>Downloads</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($downloadableForms) && $downloadableForms->count() > 0)
                    @foreach($downloadableForms as $form)
                        <tr>
                            <td>
                                <strong>{{ $form->title }}</strong>
                                @if($form->description)
                                    <br><small class="text-muted">{{ Str::limit($form->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($form->category) }}</span>
                            </td>
                            <td>
                                @if($form->file_path)
                                    <a href="{{ Storage::url($form->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                    <br><small class="text-muted">{{ pathinfo($form->file_path, PATHINFO_EXTENSION) }}</small>
                                @else
                                    <span class="text-muted">No file</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $form->download_count ?? 0 }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $form->is_active ? 'success' : 'secondary' }}">
                                    {{ $form->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <small>{{ $form->created_at->format('M d, Y') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="editForm({{ $form->id }})" 
                                            data-bs-toggle="modal" data-bs-target="#editFormModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-{{ $form->is_active ? 'warning' : 'success' }}" 
                                            onclick="toggleStatus('forms', {{ $form->id }})">
                                        <i class="fas fa-{{ $form->is_active ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteItem('forms', {{ $form->id }}, '{{ addslashes($form->title) }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No downloadable forms found.</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(isset($downloadableForms) && $downloadableForms->hasPages())
        <div class="d-flex justify-content-center">
            {{ $downloadableForms->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Add Form Modal -->
<div class="modal fade" id="addFormModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.content-management.forms.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Category *</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="application">Application</option>
                                <option value="report">Report</option>
                                <option value="request">Request</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order" min="0">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="file" class="form-label">File *</label>
                            <input type="file" class="form-control" id="file" name="file" required>
                            <small class="form-text text-muted">Supported formats: PDF, DOC, DOCX, XLS, XLSX</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Create Form</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Form Modal -->
<div class="modal fade" id="editFormModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="editFormForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="edit_title" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_category" class="form-label">Category *</label>
                            <select class="form-select" id="edit_category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="application">Application</option>
                                <option value="report">Report</option>
                                <option value="request">Request</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_sort_order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="edit_sort_order" name="sort_order" min="0">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="edit_file" class="form-label">File</label>
                            <input type="file" class="form-control" id="edit_file" name="file">
                            <small class="form-text text-muted">Leave empty to keep current file</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Form</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editForm(id) {
    // Set the form action
    document.getElementById('editFormForm').action = `/admin/content-management/forms/${id}`;
    
    // In a real implementation, you'd fetch the form data and populate the form
    // fetch(`/admin/content-management/forms/${id}/edit`)
    //     .then(response => response.json())
    //     .then(data => {
    //         document.getElementById('edit_title').value = data.title;
    //         document.getElementById('edit_category').value = data.category;
    //         // ... populate other fields
    //     });
}
</script>