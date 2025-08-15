@extends('layouts.app')

@section('title', 'Downloadable Forms Management')

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
                <li class="text-gray-700 font-medium">Downloadable Forms</li>
            </ol>
        </nav>

        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold mb-2" style="color: rgb(23 23 23);">Downloadable Forms Management</h1>
                <p class="text-lg" style="color: rgb(64 64 64);">Upload and manage forms available for download by scholars and applicants</p>
            </div>
            <button onclick="openAddFormModal()" 
                    class="btn btn-primary inline-flex items-center px-6 py-3 rounded-lg font-medium transition-all duration-200 hover:shadow-md"
                    style="background-color: #7B1FA2; color: rgb(255 255 255);">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Upload Form
            </button>
        </div>


        <!-- Forms Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Form Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File Info</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($downloadableForms ?? [] as $form)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $form->title }}</div>
                                        @if($form->description)
                                            <div class="text-sm text-gray-500 mt-1">{{ Str::limit($form->description, 100) }}</div>
                                        @endif
                                        <div class="text-xs text-gray-400 mt-1">Order: {{ $form->sort_order ?? 0 }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($form->category === 'application') bg-blue-100 text-blue-800
                                        @elseif($form->category === 'research') bg-green-100 text-green-800
                                        @elseif($form->category === 'disbursement') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($form->category) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        @if($form->file_path)
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span>{{ basename($form->file_path) }}</span>
                                            </div>
                                            @if($form->file_size)
                                                <div class="text-xs text-gray-500 mt-1">{{ number_format($form->file_size / 1024, 2) }} KB</div>
                                            @endif
                                        @else
                                            <span class="text-gray-400">No file</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               class="sr-only peer" 
                                               {{ $form->is_active ? 'checked' : '' }}
                                               onchange="toggleFormStatus({{ $form->id }})">
                                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                    </label>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        @if($form->file_path)
                                            <a href="{{ Storage::url($form->file_path) }}" 
                                               target="_blank"
                                               class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                                Download
                                            </a>
                                        @endif
                                        <button onclick="openEditFormModal({{ $form->id }})" 
                                                class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                            Edit
                                        </button>
                                        <button onclick="deleteForm({{ $form->id }})" 
                                                class="text-red-600 hover:text-red-900 text-sm font-medium">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-lg font-medium">No forms found</p>
                                        <p class="text-sm">Get started by uploading your first form.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if(isset($downloadableForms) && $downloadableForms->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $downloadableForms->appends(request()->query())->links() }}
                </div>
            @endif
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



/* File upload styling */
input[type="file"]::-webkit-file-upload-button {
    background: #7B1FA2;
    color: rgb(255 255 255);
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    margin-right: 10px;
}

input[type="file"]::-webkit-file-upload-button:hover {
    background: #6A1B9A;
}
</style>

<!-- Form Modal -->
<div id="formModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Add New Form</h3>
                <button onclick="closeFormModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="formForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="formId" name="id">
                <input type="hidden" id="formMethod" name="_method" value="POST">
                
                <div class="space-y-4">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                        <input type="text" id="title" name="title" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                    </div>
                    
                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                        <select id="category" name="category" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Select Category</option>
                            <option value="application">Application</option>
                            <option value="research">Research</option>
                            <option value="disbursement">Disbursement</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <!-- File Upload -->
                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-700 mb-1">File</label>
                        <input type="file" id="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, DOC, DOCX, XLS, XLSX (Max: 10MB)</p>
                        <div id="currentFile" class="mt-2 hidden">
                            <p class="text-sm text-gray-600">Current file: <span id="currentFileName"></span></p>
                        </div>
                    </div>
                    
                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" id="sort_order" name="sort_order" min="0" value="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    
                    <!-- Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1" checked
                                   class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeFormModal()"
                            class="px-4 py-2 text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                        <span id="submitText">Save Form</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal functionality
    const formModal = document.getElementById('formModal');
    
    // Add form modal
    window.openAddFormModal = function() {
        document.getElementById('modalTitle').textContent = 'Add New Form';
        document.getElementById('formForm').reset();
        document.getElementById('formId').value = '';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('submitText').textContent = 'Save Form';
        document.getElementById('currentFile').classList.add('hidden');
        formModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    // Edit form modal
    window.openEditFormModal = function(formId) {
        document.getElementById('modalTitle').textContent = 'Edit Form';
        document.getElementById('formId').value = formId;
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('submitText').textContent = 'Update Form';
        
        // Fetch form data
         fetch(`/admin/content-management/forms/${formId}/edit`)
             .then(response => response.json())
            .then(data => {
                document.getElementById('title').value = data.title || '';
                document.getElementById('description').value = data.description || '';
                document.getElementById('category').value = data.category || '';
                document.getElementById('sort_order').value = data.sort_order || 0;
                document.getElementById('is_active').checked = data.is_active;
                
                if (data.file_path) {
                    document.getElementById('currentFileName').textContent = data.file_path.split('/').pop();
                    document.getElementById('currentFile').classList.remove('hidden');
                } else {
                    document.getElementById('currentFile').classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading form data');
            });
        
        formModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    window.closeFormModal = function() {
        formModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    window.toggleFormStatus = function(formId) {
         fetch(`/admin/content-management/forms/${formId}/toggle-status`, {
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
                alert('Error updating status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating status');
        });
    }
    
    window.deleteForm = function(formId) {
         if (confirm('Are you sure you want to delete this form? This action cannot be undone.')) {
             fetch(`/admin/content-management/forms/${formId}`, {
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
                    alert('Error deleting form');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting form');
            });
        }
    }
    
    // Handle form submission
    document.getElementById('formForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const formId = document.getElementById('formId').value;
        const method = document.getElementById('formMethod').value;
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        submitBtn.disabled = true;
        submitBtn.textContent = formId ? 'Updating...' : 'Saving...';
        
        let url = '/admin/content-management/forms';
         if (formId) {
             url = `/admin/content-management/forms/${formId}`;
         }
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeFormModal();
                location.reload();
            } else {
                alert(data.message || 'Error saving form');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving form');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    });
    
    // Close modal when clicking outside
    formModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeFormModal();
        }
    });
    

    
    // File validation
    const fileInput = document.getElementById('file');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const maxSize = 10 * 1024 * 1024; // 10MB
                const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
                
                if (file.size > maxSize) {
                    alert('File size must be less than 10MB');
                    this.value = '';
                    return;
                }
                
                if (!allowedTypes.includes(file.type)) {
                    alert('Please select a valid file type (PDF, DOC, DOCX, XLS, XLSX)');
                    this.value = '';
                    return;
                }
            }
        });
    }
});
</script>
@endsection
