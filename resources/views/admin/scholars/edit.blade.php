@extends('layouts.app')

@section('title', 'Edit Scholar')

@section('content')
<div class="min-h-screen" style="background-color: #FAFAFA;">
    <div class="container mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold mt-2" style="color: #424242;">Edit Scholar: {{ $scholar->first_name }} {{ $scholar->last_name }}</h1>
        </div>
        <div class="bg-white rounded-lg p-6 border shadow-sm" style="border-color: #E0E0E0;">
            <form action="{{ route('admin.scholars.update', $scholar->id) }}" method="POST" id="scholar-edit-form">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4" style="color: #424242;">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium mb-1" style="color: #424242;">First Name <span style="color: #D32F2F;">*</span></label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $scholar->first_name) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                            @error('first_name')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium mb-1" style="color: #424242;">Last Name <span style="color: #D32F2F;">*</span></label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $scholar->last_name) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                            @error('last_name')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="middle_name" class="block text-sm font-medium mb-1" style="color: #424242;">Middle Name</label>
                            <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', $scholar->middle_name) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;">
                            @error('middle_name')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium mb-1" style="color: #424242;">Email Address <span style="color: #D32F2F;">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email', $scholar->user->email) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                            <p class="text-xs mt-1" style="color: #757575;">The scholar uses this email to login.</p>
                            @error('email')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="contact_number" class="block text-sm font-medium mb-1" style="color: #424242;">Phone Number <span style="color: #D32F2F;">*</span></label>
                            <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number', $scholar->contact_number) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                            @error('contact_number')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Address & Contact Information -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4" style="color: #424242;">Address & Contact Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium mb-1" style="color: #424242;">Street Address <span style="color: #D32F2F;">*</span></label>
                            <input type="text" id="address" name="address" value="{{ old('address', $scholar->address) }}" placeholder="Street number, building, barangay" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                            @error('address')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="city" class="block text-sm font-medium mb-1" style="color: #424242;">City/Municipality <span style="color: #D32F2F;">*</span></label>
                            <input type="text" id="city" name="city" value="{{ old('city', $scholar->city) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                            @error('city')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="province" class="block text-sm font-medium mb-1" style="color: #424242;">Province <span style="color: #D32F2F;">*</span></label>
                            <input type="text" id="province" name="province" value="{{ old('province', $scholar->province) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                            @error('province')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="postal_code" class="block text-sm font-medium mb-1" style="color: #424242;">Postal Code <span style="color: #D32F2F;">*</span></label>
                            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $scholar->postal_code) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                            @error('postal_code')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="country" class="block text-sm font-medium mb-1" style="color: #424242;">Country <span style="color: #D32F2F;">*</span></label>
                            <select id="country" name="country" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                                <option value="Philippines" {{ old('country', $scholar->country ?? 'Philippines') == 'Philippines' ? 'selected' : '' }}>Philippines</option>
                                <option value="Other" {{ old('country', $scholar->country) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('country')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Scholarship Details -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4" style="color: #424242;">Scholarship Details</h2>
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4" style="color: #424242;">University Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="university" class="block text-sm font-medium mb-1" style="color: #424242;">University <span style="color: #D32F2F;">*</span></label>
                                <input type="text" id="university" name="university" value="Central Luzon State University" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; background-color: #F5F5F5; --tw-ring-color: #2E7D32;" readonly required>
                                <p class="text-xs mt-1" style="color: #757575;">CLSU - Central Luzon State University</p>
                                @error('university')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="department" class="block text-sm font-medium mb-1" style="color: #424242;">Department <span style="color: #D32F2F;">*</span></label>
                                <input type="text" id="department" name="department" value="Department of Agricultural and Biosystems Engineering" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; background-color: #F5F5F5; --tw-ring-color: #2E7D32;" readonly required>
                                <p class="text-xs mt-1" style="color: #757575;">ABE Department at CLSU</p>
                                @error('department')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="program" class="block text-sm font-medium mb-1" style="color: #424242;">Program <span style="color: #D32F2F;">*</span></label>
                                <input type="text" id="program" name="program" value="Agricultural Engineering" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; background-color: #F5F5F5; --tw-ring-color: #2E7D32;" readonly required>
                                <p class="text-xs mt-1" style="color: #757575;">Agricultural Engineering Program</p>
                                @error('program')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="major" class="block text-sm font-medium mb-1" style="color: #424242;">Major/Specialization <span style="color: #D32F2F;">*</span></label>
                                <select id="major" name="major" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                                    <option value="">Select Major/Specialization</option>
                                    <option value="AB Machinery and Power Engineering" {{ old('major', $scholar->major) == 'AB Machinery and Power Engineering' ? 'selected' : '' }}>AB Machinery and Power Engineering</option>
                                    <option value="AB Land and Water Resources Engineering" {{ old('major', $scholar->major) == 'AB Land and Water Resources Engineering' ? 'selected' : '' }}>AB Land and Water Resources Engineering</option>
                                    <option value="AB Structures and Environment Engineering" {{ old('major', $scholar->major) == 'AB Structures and Environment Engineering' ? 'selected' : '' }}>AB Structures and Environment Engineering</option>
                                    <option value="AB Process Engineering" {{ old('major', $scholar->major) == 'AB Process Engineering' ? 'selected' : '' }}>AB Process Engineering</option>
                                </select>
                                <p class="text-xs mt-1" style="color: #757575;">Choose your specific area of specialization within ABE</p>
                                @error('major')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                    @enderror
                                </div>
                            <div>
                                <label for="degree_level" class="block text-sm font-medium mb-1" style="color: #424242;">Degree Level <span style="color: #D32F2F;">*</span></label>
                                <select id="degree_level" name="degree_level" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                                    <option value="Masteral" {{ old('degree_level', $scholar->degree_level) == 'Masteral' ? 'selected' : '' }}>Masteral</option>
                                    <option value="PhD" {{ old('degree_level', $scholar->degree_level) == 'PhD' ? 'selected' : '' }}>PhD</option>
                                </select>
                                @error('degree_level')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4" style="color: #424242;">Scholarship Status & Timeline</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="status" class="block text-sm font-medium mb-1" style="color: #424242;">Status <span style="color: #D32F2F;">*</span></label>
                                <select id="status" name="status" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                                    <option value="New" {{ old('status', $scholar->status) == 'New' ? 'selected' : '' }}>New</option>
                                    <option value="Ongoing" {{ old('status', $scholar->status) == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="On Extension" {{ old('status', $scholar->status) == 'On Extension' ? 'selected' : '' }}>On Extension</option>
                                    <option value="Graduated" {{ old('status', $scholar->status) == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                                    <option value="Terminated" {{ old('status', $scholar->status) == 'Terminated' ? 'selected' : '' }}>Terminated</option>
                                </select>
                                @error('status')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="start_date" class="block text-sm font-medium mb-1" style="color: #424242;">Start Date <span style="color: #D32F2F;">*</span></label>
                                <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $scholar->start_date ? (is_string($scholar->start_date) ? $scholar->start_date : $scholar->start_date->format('Y-m-d')) : '') }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                                @error('start_date')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="expected_completion_date" class="block text-sm font-medium mb-1" style="color: #424242;">Expected Completion Date <span style="color: #D32F2F;">*</span></label>
                                <input type="date" id="expected_completion_date" name="expected_completion_date" value="{{ old('expected_completion_date', $scholar->expected_completion_date ? (is_string($scholar->expected_completion_date) ? $scholar->expected_completion_date : $scholar->expected_completion_date->format('Y-m-d')) : '') }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                                @error('expected_completion_date')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="scholarship_duration" class="block text-sm font-medium mb-1" style="color: #424242;">Scholarship Duration (months) <span style="color: #D32F2F;">*</span></label>
                                <input type="number" id="scholarship_duration" name="scholarship_duration" value="{{ old('scholarship_duration', $scholar->scholarship_duration) }}" min="1" max="60" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                                @error('scholarship_duration')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="enrollment_type" class="block text-sm font-medium mb-1" style="color: #424242;">Enrollment Type <span style="color: #D32F2F;">*</span></label>
                                <select id="enrollment_type" name="enrollment_type" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                                    <option value="New" {{ old('enrollment_type', $scholar->enrollment_type) == 'New' ? 'selected' : '' }}>New</option>
                                    <option value="Lateral" {{ old('enrollment_type', $scholar->enrollment_type) == 'Lateral' ? 'selected' : '' }}>Lateral</option>
                                </select>
                                @error('enrollment_type')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="study_time" class="block text-sm font-medium mb-1" style="color: #424242;">Study Time <span style="color: #D32F2F;">*</span></label>
                                <select id="study_time" name="study_time" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;" required>
                                    <option value="Full-time" {{ old('study_time', $scholar->study_time) == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                                    <option value="Part-time" {{ old('study_time', $scholar->study_time) == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                                </select>
                                @error('study_time')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4" style="color: #424242;">Academic Background</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="bachelor_degree" class="block text-sm font-medium mb-1" style="color: #424242;">Bachelor's Degree</label>
                                <input type="text" id="bachelor_degree" name="bachelor_degree" value="BS in Agricultural and Biosystems Engineering" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; background-color: #F5F5F5; --tw-ring-color: #2E7D32;" readonly>
                                <p class="text-xs mt-1" style="color: #757575;">BSABE is the standard qualification for this program</p>
                                @error('bachelor_degree')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="bachelor_university" class="block text-sm font-medium mb-1" style="color: #424242;">Bachelor's University</label>
                                <input type="text" id="bachelor_university" name="bachelor_university" value="{{ old('bachelor_university', $scholar->bachelor_university) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: #E0E0E0; --tw-ring-color: #2E7D32;">
                                @error('bachelor_university')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('admin.scholars.show', $scholar->id) }}" class="px-4 py-2 text-white rounded-lg hover:opacity-90 mr-4 transition-all duration-200" style="background-color: #1976D2;">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Details
                    </a>
                    <button type="submit" class="px-6 py-2 text-white rounded-lg transition-all duration-200 hover:opacity-90" style="background-color: #2E7D32;">
                        <i class="fas fa-save mr-2"></i> Update Scholar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form submission with confirmation
        const form = document.getElementById('scholar-edit-form');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Update Scholar Information?',
                text: "This will update the scholar's information with the provided details.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2E7D32',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update scholar!',
                customClass: {
                    confirmButton: 'swal-confirm-button'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

<style>
/* Global Typography Improvements */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 15px;
    line-height: 1.6;
    color: #424242;
}

/* SweetAlert Custom Styling */
.swal-confirm-button {
    background-color: #2E7D32 !important;
    border: none !important;
    border-radius: 0.375rem !important;
    font-weight: 600 !important;
    padding: 0.6em 2em !important;
}

/* Enhanced Button Styling */
button, a {
    transition: all 0.2s ease-in-out;
}

/* Professional Shadow Effects */
.shadow-sm {
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
}
</style>
@endsection
