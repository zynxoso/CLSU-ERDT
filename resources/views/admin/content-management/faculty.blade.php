@extends('layouts.app')

@section('title', 'Faculty & Expertise Management')

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
                <li class="text-gray-700 font-medium">Faculty & Expertise</li>
            </ol>
        </nav>

        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold mb-2" style="color: #212121;">Faculty & Expertise Management</h1>
                <p class="text-lg" style="color: #424242;">Manage faculty profiles and expertise information displayed on the about page</p>
            </div>
            <button onclick="openFacultyModal()" 
                    class="btn btn-primary inline-flex items-center px-6 py-3 rounded-lg font-medium transition-all duration-200 hover:shadow-md"
                    style="background-color: #1976D2; color: white;">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Faculty Member
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

        <!-- Faculty Grid -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                @if(isset($facultyMembers) && $facultyMembers->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($facultyMembers as $faculty)
                            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 hover:shadow-md transition-shadow">
                                <!-- Faculty Photo -->
                                <div class="flex justify-center mb-4">
                                    @if($faculty->photo_path)
                                        <img src="{{ asset('experts/' . $faculty->photo_path) }}" 
                                             alt="{{ $faculty->name }}"
                                             class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md">
                                    @else
                                        <div class="w-24 h-24 rounded-full bg-gray-300 flex items-center justify-center border-4 border-white shadow-md">
                                            <i class="fas fa-user text-gray-500 text-2xl"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Faculty Info -->
                                <div class="text-center">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $faculty->name }}</h3>
                                    <p class="text-sm text-blue-600 font-medium mb-1">{{ $faculty->position }}</p>
                                    <p class="text-sm text-gray-600 mb-2">{{ $faculty->department }}</p>
                                    
                                    @if($faculty->specialization)
                                        <p class="text-xs text-gray-500 mb-3">{{ $faculty->specialization }}</p>
                                    @endif

                                    <!-- Status Badge -->
                                    <div class="mb-4">
                                        @if($faculty->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="w-2 h-2 bg-green-400 rounded-full mr-1"></span>
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <span class="w-2 h-2 bg-red-400 rounded-full mr-1"></span>
                                                Inactive
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex justify-center space-x-2">
                                        <button onclick="editFaculty({{ $faculty->id }})" 
                                                class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors text-sm">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </button>
                                        <button onclick="toggleFacultyStatus({{ $faculty->id }})" 
                                                class="px-3 py-1.5 {{ $faculty->is_active ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded-md transition-colors text-sm">
                                            <i class="fas fa-{{ $faculty->is_active ? 'pause' : 'play' }} mr-1"></i>{{ $faculty->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                        <button onclick="deleteFaculty({{ $faculty->id }})" 
                                                class="px-3 py-1.5 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors text-sm">
                                            <i class="fas fa-trash mr-1"></i>Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($facultyMembers->hasPages())
                        <div class="mt-6">
                            {{ $facultyMembers->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Faculty Members Found</h3>
                        <p class="text-gray-500 mb-4">{{ request('search') ? 'No faculty members match your search criteria.' : 'Get started by adding your first faculty member.' }}</p>
                        <button onclick="openFacultyModal()" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Add Faculty Member
                        </button>
                    </div>
                @endif
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

<!-- Faculty Modal -->
<div id="facultyModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex justify-between items-center pb-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Add Faculty Member</h3>
                <button onclick="closeFacultyModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="facultyForm" method="POST" enctype="multipart/form-data" class="mt-6">
                @csrf
                <input type="hidden" id="facultyId" name="faculty_id">
                <input type="hidden" id="formMethod" name="_method">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" id="name" name="name" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Position -->
                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Position *</label>
                        <input type="text" id="position" name="position" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                        <input type="text" id="department" name="department" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Specialization -->
                    <div>
                        <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">Specialization</label>
                        <input type="text" id="specialization" name="specialization"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="text" id="phone" name="phone"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                        <input type="number" id="sort_order" name="sort_order" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Photo -->
                    <div class="md:col-span-2">
                        <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">Photo</label>
                        <input type="file" id="photo" name="photo" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-sm text-gray-500 mt-1">Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB</p>
                    </div>

                    <!-- Bio -->
                    <div class="md:col-span-2">
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Biography</label>
                        <textarea id="bio" name="bio" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end space-x-3 pt-6 border-t mt-6">
                    <button type="button" onclick="closeFacultyModal()" 
                            class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <span id="submitText">Add Faculty Member</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openFacultyModal() {
    document.getElementById('facultyModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = 'Add Faculty Member';
    document.getElementById('submitText').textContent = 'Add Faculty Member';
    document.getElementById('facultyForm').action = '/admin/content-management/faculty';
    document.getElementById('formMethod').value = '';
    document.getElementById('facultyId').value = '';
    document.getElementById('facultyForm').reset();
}

function closeFacultyModal() {
    document.getElementById('facultyModal').classList.add('hidden');
}

function editFaculty(id) {
    // You would typically fetch faculty data via AJAX here
    // For now, we'll just open the modal in edit mode
    document.getElementById('facultyModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = 'Edit Faculty Member';
    document.getElementById('submitText').textContent = 'Update Faculty Member';
    document.getElementById('facultyForm').action = `/admin/content-management/faculty/${id}`;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('facultyId').value = id;
}

function toggleFacultyStatus(id) {
    if (confirm('Are you sure you want to change the status of this faculty member?')) {
        fetch(`/admin/content-management/faculty/${id}/toggle-status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to update status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    }
}

function deleteFaculty(id) {
    if (confirm('Are you sure you want to delete this faculty member? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/content-management/faculty/${id}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

// Close modal when clicking outside
document.getElementById('facultyModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeFacultyModal();
    }
});
</script>
@endsection