@extends('layouts.app')

@section('title', 'Edit Manuscript')

@section('content')
<div class="bg-gray-50 min-h-screen font-sans">
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between">
        <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Manuscript</h1>
                <p class="mt-1 text-gray-600 text-sm">Update manuscript information and details</p>
        </div>
        <div class="mt-4 md:mt-0 flex gap-3">
                <a href="{{ route('admin.manuscripts.show', $manuscript->id) }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg transition-colors bg-green-600 text-white text-sm hover:bg-green-700">
                <i class="fas fa-eye mr-2"></i>
                View Manuscript
            </a>
        </div>
    </div>
    <!-- Form Content -->
        <div class="rounded-lg shadow-sm border p-6" style="background-color: rgb(255 255 255); border-color: rgb(224 224 224);">
        <form action="{{ route('admin.manuscripts.update', $manuscript->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Manuscript Information Section -->
            <div>
                    <h2 class="text-lg font-semibold mb-4 flex items-center" style="color: rgb(23 23 23); font-size: 18px;">
                        <i class="fas fa-file-text mr-2" style="color: rgb(34 197 94);"></i>
                    Manuscript Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium mb-2" style="color: rgb(64 64 64); font-size: 15px;">
                                Manuscript Title <span style="color: #D32F2F;">*</span>
                        </label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title', $manuscript->title) }}"
                                class="w-full border rounded-lg px-4 py-2 transition-all"
                                style="border-color: rgb(224 224 224); font-size: 15px;"
                            placeholder="Enter manuscript title"
                            required
                                onfocus="this.style.borderColor='#4CAF50'; this.style.boxShadow='0 0 0 2px rgba(76, 175, 80, 0.2)'"
                                onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none'"
                        >
                        @error('title')
                                <p class="text-sm mt-1 flex items-center" style="color: #D32F2F; font-size: 14px;">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Abstract -->
                    <div class="md:col-span-2">
                            <label for="abstract" class="block text-sm font-medium mb-2" style="color: rgb(64 64 64); font-size: 15px;">
                                Abstract <span style="color: #D32F2F;">*</span>
                        </label>
                        <textarea
                            id="abstract"
                            name="abstract"
                            rows="5"
                                class="w-full border rounded-lg px-4 py-2 transition-all resize-vertical"
                                style="border-color: rgb(224 224 224); font-size: 15px;"
                            placeholder="Enter manuscript abstract"
                            required
                                onfocus="this.style.borderColor='#4CAF50'; this.style.boxShadow='0 0 0 2px rgba(76, 175, 80, 0.2)'"
                                onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none'"
                        >{{ old('abstract', $manuscript->abstract) }}</textarea>
                        @error('abstract')
                                <p class="text-sm mt-1 flex items-center" style="color: #D32F2F; font-size: 14px;">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Manuscript Type -->
                    <div>
                            <label for="manuscript_type" class="block text-sm font-medium mb-2" style="color: rgb(64 64 64); font-size: 15px;">
                                Manuscript Type <span style="color: #D32F2F;">*</span>
                        </label>
                        <select
                            id="manuscript_type"
                            name="manuscript_type"
                                class="w-full border rounded-lg px-4 py-2 transition-all"
                                style="border-color: rgb(224 224 224); font-size: 15px;"
                            required
                                onfocus="this.style.borderColor='#4CAF50'; this.style.boxShadow='0 0 0 2px rgba(76, 175, 80, 0.2)'"
                                onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none'"
                        >
                            <option value="">Select Type</option>
                            <option value="Outline" {{ old('manuscript_type', $manuscript->manuscript_type) == 'Outline' ? 'selected' : '' }}>Outline</option>
                            <option value="Final" {{ old('manuscript_type', $manuscript->manuscript_type) == 'Final' ? 'selected' : '' }}>Final</option>
                        </select>
                        @error('manuscript_type')
                                <p class="text-sm mt-1 flex items-center" style="color: #D32F2F; font-size: 14px;">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                            <label for="status" class="block text-sm font-medium mb-2" style="color: rgb(64 64 64); font-size: 15px;">
                                Status <span style="color: #D32F2F;">*</span>
                        </label>
                        <select
                            id="status"
                            name="status"
                                class="w-full border rounded-lg px-4 py-2 transition-all"
                                style="border-color: rgb(224 224 224); font-size: 15px;"
                            required
                                onfocus="this.style.borderColor='#4CAF50'; this.style.boxShadow='0 0 0 2px rgba(76, 175, 80, 0.2)'"
                                onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none'"
                        >

                            <option value="Submitted" {{ old('status', $manuscript->status) == 'Submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="Under Review" {{ old('status', $manuscript->status) == 'Under Review' ? 'selected' : '' }}>Under Review</option>
                            <option value="Revision Requested" {{ old('status', $manuscript->status) == 'Revision Requested' ? 'selected' : '' }}>Revision Requested</option>
                            <option value="Accepted" {{ old('status', $manuscript->status) == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="Rejected" {{ old('status', $manuscript->status) == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="Published" {{ old('status', $manuscript->status) == 'Published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                                <p class="text-sm mt-1 flex items-center" style="color: #D32F2F; font-size: 14px;">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Rejection Reason (only show if status is rejected) -->
                    <div id="rejection-reason-field" style="display: none;">
                            <label for="rejection_reason" class="block text-sm font-medium mb-2" style="color: rgb(64 64 64); font-size: 15px;">
                                Rejection Reason
                        </label>
                        <textarea
                            id="rejection_reason"
                            name="rejection_reason"
                            rows="3"
                                class="w-full border rounded-lg px-4 py-2 transition-all"
                                style="border-color: rgb(224 224 224); font-size: 15px;"
                            placeholder="Provide reason for rejection..."
                                onfocus="this.style.borderColor='#4CAF50'; this.style.boxShadow='0 0 0 2px rgba(76, 175, 80, 0.2)'"
                                onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none'"
                        >{{ old('rejection_reason', $manuscript->rejection_reason) }}</textarea>
                        @error('rejection_reason')
                                <p class="text-sm mt-1 flex items-center" style="color: #D32F2F; font-size: 14px;">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Co-Authors -->
                    <div class="md:col-span-2">
                            <label for="co_authors" class="block text-sm font-medium mb-2" style="color: rgb(64 64 64); font-size: 15px;">
                            Co-Authors
                        </label>
                        <input
                            type="text"
                            id="co_authors"
                            name="co_authors"
                            value="{{ old('co_authors', $manuscript->co_authors) }}"
                                class="w-full border rounded-lg px-4 py-2 transition-all"
                                style="border-color: rgb(224 224 224); font-size: 15px;"
                            placeholder="Separate names with commas (e.g., John Doe, Jane Smith)"
                                onfocus="this.style.borderColor='#4CAF50'; this.style.boxShadow='0 0 0 2px rgba(76, 175, 80, 0.2)'"
                                onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none'"
                        >
                        @error('co_authors')
                                <p class="text-sm mt-1 flex items-center" style="color: #D32F2F; font-size: 14px;">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Author Information Section -->
                <div class="border-t pt-6" style="border-color: rgb(224 224 224);">
                    <h2 class="text-lg font-semibold mb-4 flex items-center" style="color: rgb(23 23 23); font-size: 18px;">
                        <i class="fas fa-user-graduate mr-2" style="color: rgb(34 197 94);"></i>
                    Author Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                            <label class="block text-sm font-medium mb-2" style="color: rgb(64 64 64); font-size: 15px;">
                            Scholar
                        </label>
                            <div class="w-full border rounded-lg px-4 py-2" style="border-color: rgb(224 224 224); background-color: #F8F9FA;">
                            <div class="flex items-center">
                                    <i class="fas fa-user-circle mr-2" style="color: rgb(115 115 115);"></i>
                                    <span style="color: rgb(64 64 64);">{{ $manuscript->scholarProfile->user->name ?? 'N/A' }}</span>
                                @if($manuscript->scholarProfile && $manuscript->scholarProfile->user)
                                        <span class="ml-2 text-sm" style="color: rgb(115 115 115);">({{ $manuscript->scholarProfile->user->email }})</span>
                                @endif
                            </div>
                        </div>
                            <p class="text-sm mt-1" style="color: rgb(115 115 115); font-size: 14px;">
                                <i class="fas fa-info-circle mr-1" style="color: rgb(34 197 94);"></i>
                            Scholar cannot be changed after creation
                        </p>
                    </div>
                </div>
            </div>

            <!-- Current File Section -->
            @if($manuscript->file_path)
                <div class="border-t pt-6" style="border-color: rgb(224 224 224);">
                    <h2 class="text-lg font-semibold mb-4 flex items-center" style="color: rgb(23 23 23); font-size: 18px;">
                        <i class="fas fa-file-pdf mr-2" style="color: #D32F2F;"></i>
                    Current File
                </h2>

                    <div class="rounded-lg p-4 mb-4" style="background-color: #F8F9FA; border: 1px solid #E0E0E0;">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                                <i class="fas fa-file-pdf mr-3 text-lg" style="color: #D32F2F;"></i>
                            <div>
                                    <p class="text-sm font-medium" style="color: rgb(23 23 23);">Current manuscript file</p>
                                    <p class="text-xs" style="color: rgb(115 115 115);">{{ basename($manuscript->file_path) }}</p>
                                </div>
                        </div>
                        <a href="{{ route('admin.manuscripts.download', $manuscript->id) }}"
                               class="inline-flex items-center px-3 py-1 rounded-md transition-colors text-sm"
                               style="background-color: #E3F2FD; color: #4A90E2;"
                               onmouseover="this.style.backgroundColor='#BBDEFB'"
                               onmouseout="this.style.backgroundColor='#E3F2FD'">
                            <i class="fas fa-download mr-1"></i>
                            Download
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- File Upload Section -->
                <div class="border-t pt-6" style="border-color: rgb(224 224 224);">
                    <h2 class="text-lg font-semibold mb-4 flex items-center" style="color: rgb(23 23 23); font-size: 18px;">
                        <i class="fas fa-upload mr-2" style="color: rgb(34 197 94);"></i>
                    {{ $manuscript->file_path ? 'Replace File' : 'Upload File' }}
                </h2>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                            <label for="file" class="block text-sm font-medium mb-2" style="color: rgb(64 64 64); font-size: 15px;">
                            {{ $manuscript->file_path ? 'Upload New Manuscript File (PDF)' : 'Upload Manuscript File (PDF)' }}
                        </label>
                        <div class="relative">
                            <input
                                type="file"
                                id="file"
                                name="file"
                                accept=".pdf"
                                    class="w-full border rounded-lg px-4 py-2 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold"
                                    style="border-color: rgb(224 224 224); font-size: 15px; file:background-color: rgba(76, 175, 80, 0.1); file:color: rgb(34 197 94);"
                                    onfocus="this.style.borderColor='#4CAF50'; this.style.boxShadow='0 0 0 2px rgba(76, 175, 80, 0.2)'"
                                    onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none'"
                            >
                        </div>
                            <p class="text-sm mt-1" style="color: rgb(115 115 115); font-size: 14px;">
                                <i class="fas fa-info-circle mr-1" style="color: rgb(34 197 94);"></i>
                            {{ $manuscript->file_path ? 'Upload a new PDF file to replace the current one (optional). Maximum file size: 10MB' : 'Upload a PDF file (optional). Maximum file size: 10MB' }}
                        </p>
                        @error('file')
                                <p class="text-sm mt-1 flex items-center" style="color: #D32F2F; font-size: 14px;">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Information Section -->
                <div class="border-t pt-6" style="border-color: rgb(224 224 224);">
                    <h2 class="text-lg font-semibold mb-4 flex items-center" style="color: rgb(23 23 23); font-size: 18px;">
                        <i class="fas fa-sticky-note mr-2" style="color: rgb(251 191 36);"></i>
                    Additional Information
                </h2>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                            <label for="admin_notes" class="block text-sm font-medium mb-2" style="color: rgb(64 64 64); font-size: 15px;">
                            Admin Notes
                        </label>
                        <textarea
                            id="admin_notes"
                            name="admin_notes"
                            rows="4"
                                class="w-full border rounded-lg px-4 py-2 transition-all resize-vertical"
                                style="border-color: rgb(224 224 224); font-size: 15px;"
                            placeholder="Add any additional notes or comments about this manuscript"
                                onfocus="this.style.borderColor='#4CAF50'; this.style.boxShadow='0 0 0 2px rgba(76, 175, 80, 0.2)'"
                                onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none'"
                        >{{ old('admin_notes', $manuscript->admin_notes) }}</textarea>
                        @error('admin_notes')
                                <p class="text-sm mt-1 flex items-center" style="color: #D32F2F; font-size: 14px;">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Metadata Section -->
                <div class="border-t pt-6" style="border-color: rgb(224 224 224);">
                    <h2 class="text-lg font-semibold mb-4 flex items-center" style="color: rgb(23 23 23); font-size: 18px;">
                        <i class="fas fa-info-circle mr-2" style="color: rgb(115 115 115);"></i>
                    Manuscript Metadata
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="rounded-lg p-4" style="background-color: #F8F9FA; border: 1px solid #E0E0E0;">
                        <div class="flex items-center">
                                <i class="fas fa-calendar-plus mr-2" style="color: #4A90E2;"></i>
                            <div>
                                    <p class="text-xs" style="color: rgb(115 115 115);">Created</p>
                                    <p class="text-sm font-medium" style="color: rgb(23 23 23);">{{ $manuscript->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-lg p-4" style="background-color: #F8F9FA; border: 1px solid #E0E0E0;">
                        <div class="flex items-center">
                                <i class="fas fa-calendar-edit mr-2" style="color: rgb(34 197 94);"></i>
                            <div>
                                    <p class="text-xs" style="color: rgb(115 115 115);">Last Updated</p>
                                    <p class="text-sm font-medium" style="color: rgb(23 23 23);">{{ $manuscript->updated_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-lg p-4" style="background-color: #F8F9FA; border: 1px solid #E0E0E0;">
                        <div class="flex items-center">
                                <i class="fas fa-hashtag mr-2" style="color: rgb(34 197 94);"></i>
                            <div>
                                    <p class="text-xs" style="color: rgb(115 115 115);">Manuscript ID</p>
                                    <p class="text-sm font-medium" style="color: rgb(23 23 23);">#{{ $manuscript->id }}</p>
                                </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
                <div class="border-t pt-6" style="border-color: rgb(224 224 224);">
                <div class="flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('admin.manuscripts.show', $manuscript->id) }}"
                           class="inline-flex items-center justify-center px-6 py-2 rounded-lg transition-colors manuscript-action-link"
                           style="background-color: rgb(115 115 115); color: rgb(255 255 255); font-size: 15px;"
                           onmouseover="this.style.backgroundColor='#616161'"
                           onmouseout="this.style.backgroundColor='#757575'">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit"
                                class="inline-flex items-center justify-center px-6 py-2 rounded-lg transition-colors focus:outline-none"
                                style="background-color: rgb(34 197 94); color: rgb(255 255 255); font-size: 15px;"
                                onmouseover="this.style.backgroundColor='#43A047'"
                                onmouseout="this.style.backgroundColor='#4CAF50'"
                                onfocus="this.style.boxShadow='0 0 0 2px rgba(46, 125, 50, 0.2)'"
                                onblur="this.style.boxShadow='none'">
                        <i class="fas fa-save mr-2"></i>
                        Update Manuscript
                    </button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced form validation
    const form = document.querySelector('form');
    const requiredFields = form.querySelectorAll('[required]');

    // Real-time validation feedback
    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            validateField(this);
        });

        field.addEventListener('input', function() {
            if (this.classList.contains('border-red-500')) {
                validateField(this);
            }
        });
    });

    function validateField(field) {
        const isValid = field.value.trim() !== '';

        if (isValid) {
            field.style.borderColor = '#4CAF50';
        } else {
            field.style.borderColor = '#D32F2F';
        }
    }

    // File upload validation
    const fileInput = document.getElementById('file');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                // Check file size (10MB limit)
                const maxSize = 10 * 1024 * 1024; // 10MB in bytes
                if (file.size > maxSize) {
                    alert('File size must be less than 10MB');
                    this.value = '';
                    return;
                }

                // Check file type
                if (file.type !== 'application/pdf') {
                    alert('Please select a PDF file');
                    this.value = '';
                    return;
                }
            }
        });
    }

    // Form submission handling
    form.addEventListener('submit', function(event) {
        let isValid = true;

        // Validate all required fields
        requiredFields.forEach(field => {
            if (field.value.trim() === '') {
                validateField(field);
                isValid = false;
            }
        });

        if (!isValid) {
            event.preventDefault();

            // Scroll to first invalid field
            const firstInvalid = form.querySelector('[style*="border-color: #D32F2F"]');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalid.focus();
            }

            // Show error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50';
            errorDiv.style.backgroundColor = '#D32F2F';
            errorDiv.style.color = 'white';
            errorDiv.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i>Please fill in all required fields';
            document.body.appendChild(errorDiv);

            setTimeout(() => {
                errorDiv.remove();
            }, 5000);
        }
    });

    // Universal loading removed - no longer needed

    // Show/hide rejection reason field based on status
    const statusSelect = document.getElementById('status');
    const rejectionReasonField = document.getElementById('rejection-reason-field');
    
    function toggleRejectionReason() {
        if (statusSelect.value === 'Rejected') {
            rejectionReasonField.style.display = 'block';
        } else {
            rejectionReasonField.style.display = 'none';
        }
    }
    
    // Check initial state
    toggleRejectionReason();
    
    // Listen for changes
    statusSelect.addEventListener('change', toggleRejectionReason);
});
</script>
@endsection
