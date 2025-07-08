@extends('layouts.app')

@section('title', 'Create Manuscript')

@section('content')
<div style="background-color: #FAFAFA; min-height: 100vh; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between">
        <div>
                <h1 class="text-2xl font-bold" style="color: #212121; font-size: 24px;">Manuscript Creation</h1>
                <p class="mt-1" style="color: #424242; font-size: 15px;">Create a new manuscript entry in the system</p>
            </div>
    </div>

    <!-- Form Content -->
        <div class="rounded-lg shadow-sm border p-6" style="background-color: white; border-color: #E0E0E0;">
        <form action="{{ route('admin.manuscripts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-2">
            @csrf

            <!-- Manuscript Information Section -->
            <div>
                    <h2 class="text-lg font-semibold mb-4 flex items-center" style="color: #212121; font-size: 18px;">
                    Manuscript Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium mb-2" style="color: #424242; font-size: 15px;">
                                Manuscript Title <span style="color: #D32F2F;">*</span>
                        </label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title') }}"
                                class="w-full border rounded-lg px-4 py-2"
                                style="border-color: #E0E0E0; font-size: 15px;"
                            placeholder="Enter manuscript title"
                            required
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
                            <label for="abstract" class="block text-sm font-medium mb-2" style="color: #424242; font-size: 15px;">
                                Abstract <span style="color: #D32F2F;">*</span>
                        </label>
                        <textarea
                            id="abstract"
                            name="abstract"
                            rows="5"
                                class="w-full border rounded-lg px-4 py-2 resize-vertical"
                                style="border-color: #E0E0E0; font-size: 15px;"
                            placeholder="Enter manuscript abstract"
                            required
                        >{{ old('abstract') }}</textarea>
                        @error('abstract')
                                <p class="text-sm mt-1 flex items-center" style="color: #D32F2F; font-size: 14px;">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Manuscript Type -->
                    <div>
                            <label for="manuscript_type" class="block text-sm font-medium mb-2" style="color: #424242; font-size: 15px;">
                                Manuscript Type <span style="color: #D32F2F;">*</span>
                        </label>
                        <select
                            id="manuscript_type"
                            name="manuscript_type"
                                class="w-full border rounded-lg px-4 py-2"
                                style="border-color: #E0E0E0; font-size: 15px;"
                            required
                        >
                            <option value="">Select Type</option>
                            <option value="Outline" {{ old('manuscript_type') == 'Outline' ? 'selected' : '' }}>Outline</option>
                            <option value="Final" {{ old('manuscript_type') == 'Final' ? 'selected' : '' }}>Final</option>
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
                            <label for="status" class="block text-sm font-medium mb-2" style="color: #424242; font-size: 15px;">
                                Status <span style="color: #D32F2F;">*</span>
                        </label>
                        <select
                            id="status"
                            name="status"
                                class="w-full border rounded-lg px-4 py-2"
                                style="border-color: #E0E0E0; font-size: 15px;"
                            required
                        >
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="submitted" {{ old('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="under_review" {{ old('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="revision_required" {{ old('status') == 'revision_required' ? 'selected' : '' }}>Revision Required</option>
                            <option value="accepted" {{ old('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                                <p class="text-sm mt-1 flex items-center" style="color: #D32F2F; font-size: 14px;">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Co-Authors -->
                    <div class="md:col-span-2">
                            <label for="co_authors" class="block text-sm font-medium mb-2" style="color: #424242; font-size: 15px;">
                            Co-Authors
                        </label>
                        <input
                            type="text"
                            id="co_authors"
                            name="co_authors"
                            value="{{ old('co_authors') }}"
                                class="w-full border rounded-lg px-4 py-2"
                                style="border-color: #E0E0E0; font-size: 15px;"
                            placeholder="Separate names with commas (e.g., John Doe, Jane Smith)"
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
                <div class="border-t pt-6" style="border-color: #E0E0E0;">
                    <h2 class="text-lg font-semibold mb-4 flex items-center" style="color: #212121; font-size: 18px;">
                        <i class="fas fa-user-graduate mr-2" style="color: #2E7D32;"></i>
                    Author Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                            <label for="scholar_id" class="block text-sm font-medium mb-2" style="color: #424242; font-size: 15px;">
                                Scholar <span style="color: #D32F2F;">*</span>
                        </label>
                        <select
                            id="scholar_id"
                            name="scholar_id"
                                class="w-full border rounded-lg px-4 py-2"
                                style="border-color: #E0E0E0; font-size: 15px;"
                            required
                        >
                            <option value="">Select Scholar</option>
                            @foreach(\App\Models\ScholarProfile::with('user')->get() as $scholar)
                                <option value="{{ $scholar->id }}" {{ old('scholar_id') == $scholar->id ? 'selected' : '' }}>
                                    {{ $scholar->user->name }} ({{ $scholar->user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('scholar_id')
                                <p class="text-sm mt-1 flex items-center" style="color: #D32F2F; font-size: 14px;">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- File Upload Section -->
                <div class="border-t pt-6" style="border-color: #E0E0E0;">
                    <h2 class="text-lg font-semibold mb-4 flex items-center" style="color: #212121; font-size: 18px;">
                        <i class="fas fa-upload mr-2" style="color: #F8BBD0;"></i>
                    File Upload
                </h2>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                            <label for="file" class="block text-sm font-medium mb-2" style="color: #424242; font-size: 15px;">
                            Upload Manuscript File (PDF)
                        </label>
                        <div class="relative">
                            <input
                                type="file"
                                id="file"
                                name="file"
                                accept=".pdf"
                                    class="w-full border rounded-lg px-4 py-2 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold"
                                    style="border-color: #E0E0E0; font-size: 15px; file:background-color: #E8F5E8; file:color: #2E7D32;"
                            >
                        </div>
                            <p class="text-sm mt-1" style="color: #757575; font-size: 14px;">
                                <i class="fas fa-info-circle mr-1" style="color: #2E7D32;"></i>
                            Upload a PDF file (optional). Maximum file size: 10MB
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
                <div class="border-t pt-6" style="border-color: #E0E0E0;">
                    <h2 class="text-lg font-semibold mb-4 flex items-center" style="color: #212121; font-size: 18px;">
                        <i class="fas fa-sticky-note mr-2" style="color: #FFCA28;"></i>
                    Additional Information
                </h2>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                            <label for="admin_notes" class="block text-sm font-medium mb-2" style="color: #424242; font-size: 15px;">
                            Admin Notes
                        </label>
                        <textarea
                            id="admin_notes"
                            name="admin_notes"
                            rows="4"
                                class="w-full border rounded-lg px-4 py-2 resize-vertical"
                                style="border-color: #E0E0E0; font-size: 15px;"
                            placeholder="Add any additional notes or comments about this manuscript"
                        >{{ old('admin_notes') }}</textarea>
                        @error('admin_notes')
                                <p class="text-sm mt-1 flex items-center" style="color: #D32F2F; font-size: 14px;">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
                <div class="border-t pt-6" style="border-color: #E0E0E0;">
                <div class="flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('admin.manuscripts.index') }}"
                           class="inline-flex items-center justify-center px-6 py-2 rounded-lg"
                           style="background-color: #757575; color: white; font-size: 15px;">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit"
                                class="inline-flex items-center justify-center px-6 py-2 rounded-lg focus:outline-none"
                                style="background-color: #2E7D32; color: white; font-size: 15px;">
                        <i class="fas fa-save mr-2"></i>
                        Create Manuscript
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
            field.style.borderColor = '#2E7D32';
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

            // Scroll to first invalid field instantly
            const firstInvalid = form.querySelector('[style*="border-color: #D32F2F"]');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'auto', block: 'center' });
                firstInvalid.focus();
            }

            // Show error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50';
            errorDiv.style.backgroundColor = '#D32F2F';
            errorDiv.style.color = 'white';
            errorDiv.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i>Please fill in all required fields';
            document.body.appendChild(errorDiv);

            // Remove error instantly (no fade-out)
            setTimeout(() => {
                errorDiv.remove();
            }, 5000);
        }
    });

    // Prevent universal loader for manuscript action links
    document.addEventListener('click', function(event) {
        if (event.target.closest('.manuscript-action-link')) {
            if (window.universalLoading) {
                window.universalLoading.skipNext();
            }
        }
    });

    // Prevent universal loader for form submissions on manuscript pages
    document.addEventListener('submit', function(event) {
        if (event.target.closest('form')) {
            if (window.universalLoading) {
                window.universalLoading.skipNext();
            }
        }
    });
});
</script>
@endsection
