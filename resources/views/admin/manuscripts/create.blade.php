@extends('layouts.app')

@section('title', 'Create Manuscript')

@section('content')
<div style="background-color: #FAFAFA; min-height: 100vh; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">
<div class="space-y-6" x-data="manuscriptForm()">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold" style="color: #212121; font-size: 24px;">Manuscript Creation</h1>
            <p class="mt-1" style="color: #424242; font-size: 15px;">Create a new manuscript entry in the system step-by-step.</p>
        </div>
    </div>

    <!-- Step Indicator -->
    <div class="w-full rounded-full h-2.5 mb-6" style="background-color: #E0E0E0;">
        <div class="h-2.5 rounded-full" style="background-color: #4CAF50;" :style="`width: ${stepPercentage()}%`"></div>
    </div>
    <div class="flex justify-between mb-4 text-sm font-medium" style="color: #757575;">
        <span :class="{'font-bold': currentStep >= 1}" :style="currentStep >= 1 ? 'color: #4CAF50' : 'color: #757575'">Author Info</span>
        <span :class="{'font-bold': currentStep >= 2}" :style="currentStep >= 2 ? 'color: #4CAF50' : 'color: #757575'">Manuscript Details</span>
        <span :class="{'font-bold': currentStep >= 3}" :style="currentStep >= 3 ? 'color: #4CAF50' : 'color: #757575'">File & Status</span>
        <span :class="{'font-bold': currentStep === 4}" :style="currentStep === 4 ? 'color: #4CAF50' : 'color: #757575'">Review & Submit</span>
    </div>


    <!-- Form Content -->
    <div class="rounded-lg shadow-sm border p-6" style="background-color: white; border-color: #E0E0E0;">

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-4 px-4 py-3 rounded-lg" style="background-color: rgba(76, 175, 80, 0.1); color: #4CAF50; font-size: 15px;">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 px-4 py-3 rounded-lg" style="background-color: #FFEBEE; color: #D32F2F; font-size: 15px;">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        <form id="manuscript-form" action="{{ route('admin.manuscripts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-2">
            @csrf

            <!-- Step 1: Author Information -->
            <section x-show="isStep(1)" class="transition-all duration-300">
                <h2 class="text-lg font-semibold mb-4 flex items-center" style="color: #212121; font-size: 18px;">
                    <i class="fas fa-user-graduate mr-2" style="color: #4CAF50;"></i>
                    Author Information (Step 1 of 4)
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Scholar -->
                    <div>
                        <label for="scholar_id" class="block text-sm font-medium mb-2" style="color: #424242; font-size: 15px;">
                            Scholar <span style="color: #D32F2F;">*</span>
                        </label>
                        <select id="scholar_id" name="scholar_id" required class="w-full border rounded-lg px-4 py-2" style="border-color: #E0E0E0; font-size: 15px;">
                            <option value="">Select Scholar</option>
                            @foreach(\App\Models\ScholarProfile::with('user')->get() as $scholar)
                                <option value="{{ $scholar->id }}" {{ old('scholar_id') == $scholar->id ? 'selected' : '' }}>
                                    {{ $scholar->user->name }} ({{ $scholar->user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('scholar_id')
                            <p class="text-sm mt-1 flex items-center" style="color: #D32F2F; font-size: 14px;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Co-Authors -->
                    <div>
                        <label for="co_authors" class="block text-sm font-medium mb-2" style="color: #424242; font-size: 15px;">Co-Authors</label>
                        <input type="text" id="co_authors" name="co_authors" value="{{ old('co_authors') }}" class="w-full border rounded-lg px-4 py-2" style="border-color: #E0E0E0; font-size: 15px;" placeholder="Separate names with commas">
                        @error('co_authors')
                            <p class="text-sm mt-1 flex items-center" style="color: #D32F2F; font-size: 14px;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <!-- Step 2: Manuscript Information -->
            <section x-show="isStep(2)" class="transition-all duration-300">
                <h2 class="text-lg font-semibold mb-4 flex items-center" style="color: #212121; font-size: 18px;">
                    <i class="fas fa-file-alt mr-2" style="color: #4CAF50;"></i>
                    Manuscript Information (Step 2 of 4)
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium mb-2" style="color: #424242; font-size: 15px;">Manuscript Title <span style="color: #D32F2F;">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required class="w-full border rounded-lg px-4 py-2" style="border-color: #E0E0E0; font-size: 15px;" placeholder="Enter manuscript title">
                        @error('title')
                            <p class="text-sm mt-1 flex items-center" style="color: #D32F2F; font-size: 14px;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Abstract -->
                    <div class="md:col-span-2">
                        <label for="abstract" class="block text-sm font-medium mb-2" style="color: #424242; font-size: 15px;">Abstract <span style="color: #D32F2F;">*</span></label>
                        <textarea id="abstract" name="abstract" rows="5" required class="w-full border rounded-lg px-4 py-2 resize-vertical" style="border-color: #E0E0E0; font-size: 15px;" placeholder="Enter manuscript abstract">{{ old('abstract') }}</textarea>
                        @error('abstract')
                            <p class="text-sm mt-1 flex items-center" style="color: #D32F2F; font-size: 14px;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Manuscript Type -->
                    <div>
                        <label for="manuscript_type" class="block text-sm font-medium mb-2" style="color: #424242; font-size: 15px;">Manuscript Type <span style="color: #D32F2F;">*</span></label>
                        <select id="manuscript_type" name="manuscript_type" required class="w-full border rounded-lg px-4 py-2" style="border-color: #E0E0E0; font-size: 15px;">
                            <option value="">Select Type</option>
                            <option value="Outline" {{ old('manuscript_type') == 'Outline' ? 'selected' : '' }}>Outline</option>
                            <option value="Final" {{ old('manuscript_type') == 'Final' ? 'selected' : '' }}>Final</option>
                        </select>
                        @error('manuscript_type')
                            <p class="text-sm mt-1 flex items-center" style="color: #D32F2F; font-size: 14px;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <!-- Step 3: File and Status -->
            <section x-show="isStep(3)" class="transition-all duration-300">
                <h2 class="text-lg font-semibold mb-4 flex items-center" style="color: #212121; font-size: 18px;">
                    <i class="fas fa-upload mr-2" style="color: #4CAF50;"></i>
                    File and Status (Step 3 of 4)
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- File Upload -->
                    <div>
                        <label for="file" class="block text-sm font-medium mb-2" style="color: #424242; font-size: 15px;">Upload Manuscript File (PDF)</label>
                        <div class="relative">
                            <input type="file" id="file" name="file" accept=".pdf" class="w-full border rounded-lg px-4 py-2 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold" style="border-color: #E0E0E0; font-size: 15px; file:background-color: rgba(76, 175, 80, 0.1); file:color: #4CAF50;">
                        </div>
                        <p class="text-sm mt-1" style="color: #757575; font-size: 14px;"><i class="fas fa-info-circle mr-1" style="color: #4CAF50;"></i>Optional. Max size: 10MB</p>
                        @error('file')
                            <p class="text-sm mt-1 flex items-center" style="color: #D32F2F; font-size: 14px;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium mb-2" style="color: #424242; font-size: 15px;">Status <span style="color: #D32F2F;">*</span></label>
                        <select id="status" name="status" required class="w-full border rounded-lg px-4 py-2" style="border-color: #E0E0E0; font-size: 15px;">
                            <option value="Draft" {{ old('status', 'Draft') == 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="Submitted" {{ old('status') == 'Submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="Under Review" {{ old('status') == 'Under Review' ? 'selected' : '' }}>Under Review</option>
                            <option value="Revision Required" {{ old('status') == 'Revision Required' ? 'selected' : '' }}>Revision Required</option>
                            <option value="Accepted" {{ old('status') == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="Rejected" {{ old('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="Published" {{ old('status') == 'Published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <p class="text-sm mt-1 flex items-center" style="color: #D32F2F; font-size: 14px;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <!-- Step 4: Additional Information -->
            <section x-show="isStep(4)" class="transition-all duration-300">
                <h2 class="text-lg font-semibold mb-4 flex items-center" style="color: #212121; font-size: 18px;">
                    <i class="fas fa-sticky-note mr-2" style="color: #4CAF50;"></i>
                    Additional Information (Step 4 of 4)
                </h2>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="admin_notes" class="block text-sm font-medium mb-2" style="color: #424242; font-size: 15px;">Admin Notes</label>
                        <textarea id="admin_notes" name="admin_notes" rows="4" class="w-full border rounded-lg px-4 py-2 resize-vertical" style="border-color: #E0E0E0; font-size: 15px;" placeholder="Add any additional notes or comments">{{ old('admin_notes') }}</textarea>
                        @error('admin_notes')
                            <p class="text-sm mt-1 flex items-center" style="color: #D32F2F; font-size: 14px;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <!-- Form Actions -->
            <div class="border-t pt-6 mt-6" style="border-color: #E0E0E0;">
                <div class="flex justify-between items-center">
                    <!-- Previous Button -->
                    <button type="button" @click="prevStep()" x-show="currentStep > 1"
                           class="inline-flex items-center justify-center px-6 py-2 rounded-lg"
                           style="background-color: #757575; color: white; font-size: 15px;">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Previous
                    </button>
                    <!-- Spacer -->
                    <div x-show="currentStep === 1"></div>

                    <!-- Next Button -->
                    <button type="button" @click="nextStep()" x-show="currentStep < totalSteps"
                            class="inline-flex items-center justify-center px-6 py-2 rounded-lg"
                            style="background-color: #4CAF50; color: white; font-size: 15px;">
                        Next
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>

                    <!-- Submit Button -->
                    <button type="submit" x-show="currentStep === totalSteps"
                            class="inline-flex items-center justify-center px-6 py-2 rounded-lg focus:outline-none"
                            style="background-color: #4CAF50; color: white; font-size: 15px;">
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
function manuscriptForm() {
    return {
        currentStep: 1,
        totalSteps: 4,
        isStep(step) {
            return this.currentStep === step;
        },
        stepPercentage() {
            return (this.currentStep / this.totalSteps) * 100;
        },
        validateStep(step) {
            const fields = document.querySelectorAll(`[x-show="isStep(${step})"] [required]`);
            let isValid = true;
            fields.forEach(field => {
                // Clear previous custom errors
                field.parentElement.querySelector('.validation-error-message')?.remove();
                field.style.borderColor = '#E0E0E0';

                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#D32F2F';
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'validation-error-message text-sm mt-1 flex items-center';
                    errorDiv.style.color = '#D32F2F';
                    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i>This field is required.`;
                    field.parentElement.append(errorDiv);
                }
            });
            return isValid;
        },
        nextStep() {
            if (this.validateStep(this.currentStep)) {
                if (this.currentStep < this.totalSteps) {
                    this.currentStep++;
                }
            } else {
                // Optionally show a toast message for validation errors
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50';
                toast.style.backgroundColor = '#D32F2F';
                toast.style.color = 'white';
                toast.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i>Please fill in all required fields.';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            }
        },
        prevStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
            }
        },
    }
}

// File size/type validation
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                if (file.size > 10 * 1024 * 1024) { // 10MB
                    alert('File size exceeds 10MB limit.');
                    this.value = '';
                }
                if (file.type !== 'application/pdf') {
                    alert('Only PDF files are allowed.');
                    this.value = '';
                }
            }
        });
    }

    // Prevent form submission with Enter key unless it's the last step
    const form = document.getElementById('manuscript-form');
    form.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            const alpineData = form.closest('[x-data]').__x.$data;
            if (alpineData.currentStep !== alpineData.totalSteps) {
                event.preventDefault();
                alpineData.nextStep();
            }
        }
    });
});
</script>
@endsection
