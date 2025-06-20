@extends('layouts.app')

@section('title', 'Edit Scholar')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mt-2">Edit Scholar: {{ $scholar->first_name }} {{ $scholar->last_name }}</h1>
        </div>
        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <form action="{{ route('admin.scholars.update', $scholar->id) }}" method="POST" id="scholar-edit-form">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $scholar->first_name) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200" required>
                            @error('first_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $scholar->last_name) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200" required>
                            @error('last_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                            <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', $scholar->middle_name) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                            @error('middle_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email', $scholar->email) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200" required>
                            <p class="text-xs text-gray-500 mt-1">The scholar uses this email to login.</p>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                            <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number', $scholar->contact_number) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" required>
                            @error('contact_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Address & Contact Information -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Address & Contact Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Street Address <span class="text-red-500">*</span></label>
                            <input type="text" id="address" name="address" value="{{ old('address', $scholar->address) }}" placeholder="Street number, building, barangay" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200" required>
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City/Municipality <span class="text-red-500">*</span></label>
                            <input type="text" id="city" name="city" value="{{ old('city', $scholar->city) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" required>
                            @error('city')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Province <span class="text-red-500">*</span></label>
                            <input type="text" id="province" name="province" value="{{ old('province', $scholar->province) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" required>
                            @error('province')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal Code <span class="text-red-500">*</span></label>
                            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $scholar->postal_code) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200" required>
                            @error('postal_code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country <span class="text-red-500">*</span></label>
                            <select id="country" name="country" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" required>
                                <option value="Philippines" {{ old('country', $scholar->country ?? 'Philippines') == 'Philippines' ? 'selected' : '' }}>Philippines</option>
                                <option value="Other" {{ old('country', $scholar->country) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('country')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Scholarship Details -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Scholarship Details</h2>
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">University Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="university" class="block text-sm font-medium text-gray-700 mb-1">University <span class="text-red-500">*</span></label>
                                <input type="text" id="university" name="university" value="Central Luzon State University" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 bg-gray-100" readonly required>
                                <p class="text-xs text-gray-500 mt-1">CLSU - Central Luzon State University</p>
                                @error('university')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department <span class="text-red-500">*</span></label>
                                <input type="text" id="department" name="department" value="Department of Agricultural and Biosystems Engineering" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 bg-gray-100" readonly required>
                                <p class="text-xs text-gray-500 mt-1">ABE Department at CLSU</p>
                                @error('department')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="program" class="block text-sm font-medium text-gray-700 mb-1">Program <span class="text-red-500">*</span></label>
                                <input type="text" id="program" name="program" value="Agricultural Engineering" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 bg-gray-100" readonly required>
                                <p class="text-xs text-gray-500 mt-1">Agricultural Engineering Program</p>
                                @error('program')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="major" class="block text-sm font-medium text-gray-700 mb-1">Major/Specialization <span class="text-red-500">*</span></label>
                                <select id="major" name="major" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" required>
                                    <option value="">Select Major/Specialization</option>
                                    <option value="Agricultural and Biosystems Engineering" {{ old('major', $scholar->major) == 'Agricultural and Biosystems Engineering' ? 'selected' : '' }}>Agricultural and Biosystems Engineering</option>
                                    <option value="Agricultural Machinery and Equipment" {{ old('major', $scholar->major) == 'Agricultural Machinery and Equipment' ? 'selected' : '' }}>Agricultural Machinery and Equipment</option>
                                    <option value="Irrigation and Water Management" {{ old('major', $scholar->major) == 'Irrigation and Water Management' ? 'selected' : '' }}>Irrigation and Water Management</option>
                                    <option value="Post-Harvest Technology" {{ old('major', $scholar->major) == 'Post-Harvest Technology' ? 'selected' : '' }}>Post-Harvest Technology</option>
                                    <option value="Agricultural Structures and Environment" {{ old('major', $scholar->major) == 'Agricultural Structures and Environment' ? 'selected' : '' }}>Agricultural Structures and Environment</option>
                                    <option value="Food Engineering" {{ old('major', $scholar->major) == 'Food Engineering' ? 'selected' : '' }}>Food Engineering</option>
                                    <option value="Renewable Energy Systems" {{ old('major', $scholar->major) == 'Renewable Energy Systems' ? 'selected' : '' }}>Renewable Energy Systems</option>
                                    <option value="Precision Agriculture" {{ old('major', $scholar->major) == 'Precision Agriculture' ? 'selected' : '' }}>Precision Agriculture</option>
                                    <option value="Agricultural Process Engineering" {{ old('major', $scholar->major) == 'Agricultural Process Engineering' ? 'selected' : '' }}>Agricultural Process Engineering</option>
                                    <option value="Soil and Water Engineering" {{ old('major', $scholar->major) == 'Soil and Water Engineering' ? 'selected' : '' }}>Soil and Water Engineering</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Choose your specific area of specialization within ABE</p>
                                @error('major')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="degree_level" class="block text-sm font-medium text-gray-700 mb-1">Degree Level <span class="text-red-500">*</span></label>
                                <select id="degree_level" name="degree_level" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200" required>
                                    <option value="Masteral" {{ old('degree_level', $scholar->degree_level) == 'Masteral' ? 'selected' : '' }}>Masteral</option>
                                    <option value="PhD" {{ old('degree_level', $scholar->degree_level) == 'PhD' ? 'selected' : '' }}>PhD</option>
                                </select>
                                @error('degree_level')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Scholarship Status & Timeline</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                                <select id="status" name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200" required>
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
                                <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $scholar->start_date ? (is_string($scholar->start_date) ? $scholar->start_date : $scholar->start_date->format('Y-m-d')) : '') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" required>
                                @error('start_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="expected_completion_date" class="block text-sm font-medium text-gray-700 mb-1">Expected Completion Date <span class="text-red-500">*</span></label>
                                <input type="date" id="expected_completion_date" name="expected_completion_date" value="{{ old('expected_completion_date', $scholar->expected_completion_date ? (is_string($scholar->expected_completion_date) ? $scholar->expected_completion_date : $scholar->expected_completion_date->format('Y-m-d')) : '') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" required>
                                @error('expected_completion_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="scholarship_duration" class="block text-sm font-medium text-gray-700 mb-1">Scholarship Duration (months) <span class="text-red-500">*</span></label>
                                <input type="number" id="scholarship_duration" name="scholarship_duration" value="{{ old('scholarship_duration', $scholar->scholarship_duration) }}" min="1" max="60" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200" required>
                                @error('scholarship_duration')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="enrollment_type" class="block text-sm font-medium text-gray-700 mb-1">Enrollment Type <span class="text-red-500">*</span></label>
                                <select id="enrollment_type" name="enrollment_type" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200" required>
                                    <option value="New" {{ old('enrollment_type', $scholar->enrollment_type) == 'New' ? 'selected' : '' }}>New</option>
                                    <option value="Lateral" {{ old('enrollment_type', $scholar->enrollment_type) == 'Lateral' ? 'selected' : '' }}>Lateral</option>
                                </select>
                                @error('enrollment_type')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="study_time" class="block text-sm font-medium text-gray-700 mb-1">Study Time <span class="text-red-500">*</span></label>
                                <select id="study_time" name="study_time" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200" required>
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
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Academic Background</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="bachelor_degree" class="block text-sm font-medium text-gray-700 mb-1">Bachelor's Degree</label>
                                <input type="text" id="bachelor_degree" name="bachelor_degree" value="BS in Agricultural and Biosystems Engineering" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 bg-gray-100" readonly>
                                <p class="text-xs text-gray-500 mt-1">BSABE is the standard qualification for this program</p>
                                @error('bachelor_degree')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="bachelor_university" class="block text-sm font-medium text-gray-700 mb-1">Bachelor's University</label>
                                <input type="text" id="bachelor_university" name="bachelor_university" value="Central Luzon State University" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 bg-gray-100" readonly>
                                <p class="text-xs text-gray-500 mt-1">CLSU - Central Luzon State University</p>
                                @error('bachelor_university')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="bachelor_graduation_year" class="block text-sm font-medium text-gray-700 mb-1">Bachelor's Graduation Year</label>
                                <input type="number" id="bachelor_graduation_year" name="bachelor_graduation_year" value="{{ old('bachelor_graduation_year', $scholar->bachelor_graduation_year) }}" min="1950" max="{{ date('Y') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                                @error('bachelor_graduation_year')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('admin.scholars.show', $scholar->id) }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 mr-4 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Details
                    </a>
                    <button type="submit" class="px-6 py-2 bg-red-700 text-white rounded-lg hover:bg-red-800 transition-colors duration-200">
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
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update scholar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
