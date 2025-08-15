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
            <button type="button" class="btn btn-success" onclick="openModal('addFormModal')">
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
                                            onclick="editForm({{ $form->id }})">
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
<x-modal id="addFormModal" maxWidth="lg">
    <x-slot name="title">
        Add New Form
    </x-slot>
    
    <form method="POST" action="{{ route('admin.content-management.forms.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="title" name="title" required>
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="description" name="description" rows="3"></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="category" name="category" required>
                        <option value="">Select Category</option>
                        <option value="application">Application</option>
                        <option value="report">Report</option>
                        <option value="request">Request</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="sort_order" name="sort_order" min="0">
                </div>
            </div>
            <div>
                <label for="file" class="block text-sm font-medium text-gray-700 mb-1">File *</label>
                <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="file" name="file" required>
                <small class="text-sm text-gray-500">Supported formats: PDF, DOC, DOCX, XLS, XLSX</small>
            </div>
        </div>
        
        <x-slot name="footer">
            <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500" onclick="closeModal('addFormModal')">Cancel</button>
            <button type="submit" class="ml-3 px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">Create Form</button>
        </x-slot>
    </form>
</x-modal>

<!-- Edit Form Modal -->
<x-modal id="editFormModal" maxWidth="lg">
    <x-slot name="title">
        Edit Form
    </x-slot>
    
    <form method="POST" id="editFormForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <label for="edit_title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_title" name="title" required>
            </div>
            <div>
                <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_description" name="description" rows="3"></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="edit_category" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_category" name="category" required>
                        <option value="">Select Category</option>
                        <option value="application">Application</option>
                        <option value="report">Report</option>
                        <option value="request">Request</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label for="edit_sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_sort_order" name="sort_order" min="0">
                </div>
            </div>
            <div>
                <label for="edit_file" class="block text-sm font-medium text-gray-700 mb-1">File (Optional - leave empty to keep current file)</label>
                <input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-transparent" id="edit_file" name="file">
                <small class="text-sm text-gray-500">Supported formats: PDF, DOC, DOCX, XLS, XLSX</small>
            </div>
        </div>
        
        <x-slot name="footer">
            <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500" onclick="closeModal('editFormModal')">Cancel</button>
            <button type="submit" class="ml-3 px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Update Form</button>
        </x-slot>
    </form>
</x-modal>

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
    //         document.getElementById('edit_description').value = data.description;
    //         document.getElementById('edit_sort_order').value = data.sort_order;
    //         // ... populate other fields
    //     });
    
    // Open the modal
    openModal('editFormModal');
}
</script>
