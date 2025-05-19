@extends('layouts.app')

@section('title', 'Edit Scholar')

@section('styles')
<style>
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-4px); }
    }

    .animate-pulse {
        animation: pulse 1s infinite;
    }

    .animate-bounce {
        animation: bounce 1s infinite;
    }

    .btn-transition {
        transition: all 0.3s ease;
    }

    .btn-transition:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }



    /* Ensure all button icons have white color */
    a.bg-gray-600 i,
    a.bg-blue-500 i,
    a.bg-blue-600 i,
    button.bg-blue-500 i {
        color: white !important;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('scholar-edit-form');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic validation
            const requiredFields = form.querySelectorAll('[required]');
            let hasErrors = false;
            let errorMessages = [];
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    hasErrors = true;
                    field.classList.add('border-red-500');
                    const fieldName = field.previousElementSibling.textContent.replace('*', '').trim();
                    errorMessages.push(`${fieldName} is required`);
                } else {
                    field.classList.remove('border-red-500');
                }
            });
            
            if (hasErrors) {
                Swal.fire({
                    title: 'Validation Error',
                    html: errorMessages.join('<br>'),
                    icon: 'error',
                    confirmButtonText: 'Fix Errors'
                });
                return;
            }
            
            // If validation passes, submit the form
            Swal.fire({
                title: 'Updating Scholar',
                text: 'Please wait while we update the scholar information...',
                icon: 'info',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    form.submit();
                }
            });
        });
        
        // Display validation errors from server-side if any
        @if($errors->any())
            const errorMessages = [];
            @foreach($errors->all() as $error)
                errorMessages.push('{{ $error }}');
            @endforeach
            
            Swal.fire({
                title: 'Validation Error',
                html: errorMessages.join('<br>'),
                icon: 'error',
                confirmButtonText: 'Fix Errors'
            });
        @endif
    });
</script>
@endsection

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto">
        <div class="mb-6">
            <!-- <div class="flex space-x-6 mb-4">
                <a href="{{ route('admin.scholars.index') }}"
                   class="group w-44 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 shadow-sm flex items-center justify-center border border-gray-200 transition-all duration-300 transform hover:scale-105 hover:shadow-md btn-transition">
                    <i class="fas fa-arrow-left mr-2 group-hover:translate-x-[-3px] transition-transform duration-300"></i>
                    <span>Back to List</span>
                </a>
            </div> -->
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Edit Scholar: {{ $scholar->first_name }} {{ $scholar->last_name }}</h1>
   
        </div>

        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
            <form id="scholar-edit-form" action="{{ route('admin.scholars.update', $scholar->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $scholar->first_name) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('first_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $scholar->last_name) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('last_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-1">Contact Number <span class="text-red-500">*</span></label>
                            <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number', $scholar->contact_number) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="e.g. +63 912 345 6789">
                            @error('contact_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address <span class="text-red-500">*</span></label>
                            <input type="text" id="address" name="address" value="{{ old('address', $scholar->address) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Scholarship Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="university" class="block text-sm font-medium text-gray-700 mb-1">University <span class="text-red-500">*</span></label>
                            <input type="text" id="university" name="university" value="Central Luzon State University" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" readonly required>
                            <p class="text-xs text-gray-500 mt-1">CLSU - Central Luzon State University</p>
                            @error('university')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department <span class="text-red-500">*</span></label>
                            <input type="text" id="department" name="department" value="Department of Agricultural and Biosystems Engineering" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" readonly required>
                            <p class="text-xs text-gray-500 mt-1">ABE Department at CLSU</p>
                            @error('department')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="program" class="block text-sm font-medium text-gray-700 mb-1">Program <span class="text-red-500">*</span></label>
                            <input type="text" id="program" name="program" value="Agricultural Engineering" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" readonly required>
                            <p class="text-xs text-gray-500 mt-1">Agricultural Engineering Program</p>
                            @error('program')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="degree_level" class="block text-sm font-medium text-gray-700 mb-1">Degree Level <span class="text-red-500">*</span></label>
                            <select id="degree_level" name="degree_level" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="Masteral" {{ old('degree_level', $scholar->degree_level) == 'Masteral' ? 'selected' : '' }}>Masteral</option>
                                <option value="PhD" {{ old('degree_level', $scholar->degree_level) == 'PhD' ? 'selected' : '' }}>PhD</option>
                            </select>
                            @error('degree_level')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select id="status" name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="New" {{ old('status', $scholar->status) == 'New' ? 'selected' : '' }}>New</option>
                                <option value="Ongoing" {{ old('status', $scholar->status) == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="On Extension" {{ old('status', $scholar->status) == 'On Extension' ? 'selected' : '' }}>On Extension</option>
                                <option value="Graduated" {{ old('status', $scholar->status) == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                                <option value="Terminated" {{ old('status', $scholar->status) == 'Terminated' ? 'selected' : '' }}>Terminated</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $scholar->start_date ? (is_string($scholar->start_date) ? $scholar->start_date : $scholar->start_date->format('Y-m-d')) : '') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('start_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="expected_completion_date" class="block text-sm font-medium text-gray-700 mb-1">Expected Completion Date <span class="text-red-500">*</span></label>
                            <input type="date" id="expected_completion_date" name="expected_completion_date" value="{{ old('expected_completion_date', $scholar->expected_completion_date ? (is_string($scholar->expected_completion_date) ? $scholar->expected_completion_date : $scholar->expected_completion_date->format('Y-m-d')) : '') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('expected_completion_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="scholarship_duration" class="block text-sm font-medium text-gray-700 mb-1">Scholarship Duration (months) <span class="text-red-500">*</span></label>
                            <input type="number" id="scholarship_duration" name="scholarship_duration" value="{{ old('scholarship_duration', $scholar->scholarship_duration) }}" min="1" max="60" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('scholarship_duration')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="enrollment_type" class="block text-sm font-medium text-gray-700 mb-1">Enrollment Type <span class="text-red-500">*</span></label>
                            <select id="enrollment_type" name="enrollment_type" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="New" {{ old('enrollment_type', $scholar->enrollment_type) == 'New' ? 'selected' : '' }}>New</option>
                                <option value="Lateral" {{ old('enrollment_type', $scholar->enrollment_type) == 'Lateral' ? 'selected' : '' }}>Lateral</option>
                            </select>
                            @error('enrollment_type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="study_time" class="block text-sm font-medium text-gray-700 mb-1">Study Time <span class="text-red-500">*</span></label>
                            <select id="study_time" name="study_time" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="Full-time" {{ old('study_time', $scholar->study_time) == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                                <option value="Part-time" {{ old('study_time', $scholar->study_time) == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                            </select>
                            @error('study_time')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">
                        Additional Notes
                    </h2>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea id="notes" name="notes" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes', $scholar->notes) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Any additional information about the scholar. Special characters are allowed here.</p>
                        @error('notes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="flex justify-between">
                    <div class="text-sm text-gray-600">
                        <span class="inline-flex items-center">
                            <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                            All fields marked with <span class="text-red-500 mx-1">*</span> are required
                        </span>
                    </div>
                    <button type="submit"
                            class="group w-48 px-5 py-2.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 shadow-sm flex items-center justify-center transition-all duration-300 transform hover:scale-105 hover:shadow-md">
                        <i class="fas fa-save mr-2 text-white"></i>
                        <span>Update Scholar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
