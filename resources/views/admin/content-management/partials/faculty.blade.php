<div class="tab-pane fade show active" id="faculty" role="tabpanel">
    <!-- Search and Filter Bar -->
    <div class="row mb-3">
        <div class="col-md-12">
            <form method="GET" action="{{ route('admin.content-management.index') }}" class="d-flex gap-2">
                <input type="hidden" name="tab" value="faculty">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search faculty members..." value="{{ $searchTerm }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <select name="filter_status" class="form-select" style="max-width: 150px;">
                    <option value="">All Status</option>
                    <option value="active" {{ $filterStatus === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $filterStatus === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.content-management.index', ['tab' => 'faculty']) }}" class="btn btn-secondary">Clear</a>
            </form>
        </div>
    </div>

    <!-- Add New Button -->
    <div class="row mb-3">
        <div class="col-md-12">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addFacultyModal">
                <i class="fas fa-plus"></i> Add New Faculty Member
            </button>
        </div>
    </div>

    <!-- Faculty Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($facultyMembers) && $facultyMembers->count() > 0)
                    @foreach($facultyMembers as $faculty)
                        <tr>
                            <td>
                                @if($faculty->photo)
                                    <img src="{{ Storage::url($faculty->photo) }}" alt="{{ $faculty->name }}" 
                                         class="rounded-circle" width="50" height="50" style="object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $faculty->name }}</strong>
                                @if($faculty->specialization)
                                    <br><small class="text-muted">{{ $faculty->specialization }}</small>
                                @endif
                            </td>
                            <td>{{ $faculty->position }}</td>
                            <td>{{ $faculty->department }}</td>
                            <td>
                                @if($faculty->email)
                                    <small><i class="fas fa-envelope"></i> {{ $faculty->email }}</small><br>
                                @endif
                                @if($faculty->phone)
                                    <small><i class="fas fa-phone"></i> {{ $faculty->phone }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $faculty->is_active ? 'success' : 'secondary' }}">
                                    {{ $faculty->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="editFaculty({{ $faculty->id }})" 
                                            data-bs-toggle="modal" data-bs-target="#editFacultyModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-{{ $faculty->is_active ? 'warning' : 'success' }}" 
                                            onclick="toggleStatus('faculty', {{ $faculty->id }})">
                                        <i class="fas fa-{{ $faculty->is_active ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteItem('faculty', {{ $faculty->id }}, '{{ addslashes($faculty->name) }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No faculty members found.</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(isset($facultyMembers) && $facultyMembers->hasPages())
        <div class="d-flex justify-content-center">
            {{ $facultyMembers->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Add Faculty Modal -->
<div class="modal fade" id="addFacultyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.content-management.faculty.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Faculty Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="position" class="form-label">Position *</label>
                            <input type="text" class="form-control" id="position" name="position" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="department" class="form-label">Department *</label>
                            <input type="text" class="form-control" id="department" name="department" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="specialization" class="form-label">Specialization</label>
                            <input type="text" class="form-control" id="specialization" name="specialization">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                            <small class="form-text text-muted">Max size: 2MB. Formats: JPEG, PNG, JPG, GIF</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order" min="0">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="bio" class="form-label">Biography</label>
                            <textarea class="form-control" id="bio" name="bio" rows="4"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Create Faculty Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Faculty Modal -->
<div class="modal fade" id="editFacultyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="editFacultyForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Faculty Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_name" class="form-label">Name *</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_position" class="form-label">Position *</label>
                            <input type="text" class="form-control" id="edit_position" name="position" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_department" class="form-label">Department *</label>
                            <input type="text" class="form-control" id="edit_department" name="department" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_specialization" class="form-label">Specialization</label>
                            <input type="text" class="form-control" id="edit_specialization" name="specialization">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="edit_phone" name="phone">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_photo" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="edit_photo" name="photo" accept="image/*">
                            <small class="form-text text-muted">Leave empty to keep current photo</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_sort_order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="edit_sort_order" name="sort_order" min="0">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="edit_bio" class="form-label">Biography</label>
                            <textarea class="form-control" id="edit_bio" name="bio" rows="4"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Faculty Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editFaculty(id) {
    // Set the form action
    document.getElementById('editFacultyForm').action = `/admin/content-management/faculty/${id}`;
    
    // In a real implementation, you'd fetch the faculty data and populate the form
    // fetch(`/admin/content-management/faculty/${id}/edit`)
    //     .then(response => response.json())
    //     .then(data => {
    //         document.getElementById('edit_name').value = data.name;
    //         document.getElementById('edit_position').value = data.position;
    //         // ... populate other fields
    //     });
}
</script>