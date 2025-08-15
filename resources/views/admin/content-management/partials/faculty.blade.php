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
            <button type="button" class="btn btn-success" onclick="openModal('addFacultyModal')">
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
                                            onclick="editFaculty({{ $faculty->id }})">
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
<x-modal id="addFacultyModal" maxWidth="lg">
    <x-slot name="title">
        Add New Faculty Member
    </x-slot>
    
    <form method="POST" action="{{ route('admin.content-management.faculty.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="name" name="name" required>
                </div>
                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Position *</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="position" name="position" required>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department *</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="department" name="department" required>
                </div>
                <div>
                    <label for="specialization" class="block text-sm font-medium text-gray-700 mb-1">Specialization</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="specialization" name="specialization">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="email" name="email">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="phone" name="phone">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
                    <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="photo" name="photo" accept="image/*">
                    <small class="text-sm text-gray-500">Max size: 2MB. Formats: JPEG, PNG, JPG, GIF</small>
                </div>
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="sort_order" name="sort_order" min="0">
                </div>
            </div>
            <div>
                <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Biography</label>
                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="bio" name="bio" rows="4"></textarea>
            </div>
        </div>
        
        <x-slot name="footer">
            <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500" onclick="closeModal('addFacultyModal')">Cancel</button>
            <button type="submit" class="ml-3 px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Create Faculty Member</button>
        </x-slot>
    </form>
</x-modal>

<!-- Edit Faculty Modal -->
<x-modal id="editFacultyModal" maxWidth="lg">
    <x-slot name="title">
        Edit Faculty Member
    </x-slot>
    
    <form method="POST" id="editFacultyForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_name" name="name" required>
                </div>
                <div>
                    <label for="edit_position" class="block text-sm font-medium text-gray-700 mb-1">Position *</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_position" name="position" required>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="edit_department" class="block text-sm font-medium text-gray-700 mb-1">Department *</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_department" name="department" required>
                </div>
                <div>
                    <label for="edit_specialization" class="block text-sm font-medium text-gray-700 mb-1">Specialization</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_specialization" name="specialization">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_email" name="email">
                </div>
                <div>
                    <label for="edit_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_phone" name="phone">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="edit_photo" class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
                    <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_photo" name="photo" accept="image/*">
                    <small class="text-sm text-gray-500">Leave empty to keep current photo</small>
                </div>
                <div>
                    <label for="edit_sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_sort_order" name="sort_order" min="0">
                </div>
            </div>
            <div>
                <label for="edit_bio" class="block text-sm font-medium text-gray-700 mb-1">Biography</label>
                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_bio" name="bio" rows="4"></textarea>
            </div>
        </div>
        
        <x-slot name="footer">
            <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500" onclick="closeModal('editFacultyModal')">Cancel</button>
            <button type="submit" class="ml-3 px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Update Faculty Member</button>
        </x-slot>
    </form>
</x-modal>

<script>
function editFaculty(id) {
    fetch(`/admin/content-management/faculty/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_position').value = data.position;
            document.getElementById('edit_department').value = data.department;
            document.getElementById('edit_specialization').value = data.specialization || '';
            document.getElementById('edit_email').value = data.email || '';
            document.getElementById('edit_phone').value = data.phone || '';
            document.getElementById('edit_bio').value = data.bio || '';
            document.getElementById('edit_sort_order').value = data.sort_order || '';
            
            document.getElementById('editFacultyForm').action = `/admin/content-management/faculty/${id}`;
            openModal('editFacultyModal');
        })
        .catch(error => console.error('Error:', error));
}
</script>
