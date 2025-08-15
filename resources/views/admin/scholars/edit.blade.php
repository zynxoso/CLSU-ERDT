@extends('layouts.app')

@section('title', 'Edit Scholar')

@section('content')
<div class="min-h-screen" style="background-color: #FAFAFA;">
    <div class="container mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold mt-2" style="color: rgb(64 64 64);">Edit Scholar: {{ $scholar->first_name }} {{ $scholar->last_name }}</h1>
        </div>
        <div class="bg-white rounded-lg p-6 border shadow-sm" style="border-color: rgb(224 224 224);">
            <form action="{{ route('admin.scholars.update', $scholar->id) }}" method="POST" id="scholar-edit-form">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4" style="color: rgb(64 64 64);">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">First Name <span style="color: #D32F2F;">*</span></label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $scholar->first_name) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" required>
                            @error('first_name')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Last Name <span style="color: #D32F2F;">*</span></label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $scholar->last_name) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" required>
                            @error('last_name')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="middle_name" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Middle Name</label>
                            <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', $scholar->middle_name) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);">
                            @error('middle_name')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Email Address <span style="color: #D32F2F;">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email', $scholar->user->email) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" required>
                            <p class="text-xs mt-1" style="color: rgb(115 115 115);">The scholar uses this email to login.</p>
                            @error('email')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="contact_number" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Contact Number <span style="color: #D32F2F;">*</span></label>
                            <input type="tel" id="contact_number" name="contact_number" value="{{ old('contact_number', $scholar->contact_number) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" required>
                            @error('contact_number')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="gender" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Gender <span style="color: #D32F2F;">*</span></label>
                            <select id="gender" name="gender" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" required>
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender', $scholar->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $scholar->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender', $scholar->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                <option value="Prefer not to say" {{ old('gender', $scholar->gender) == 'Prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
                            </select>
                            @error('gender')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="suffix" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Suffix</label>
                            <input type="text" id="suffix" name="suffix" value="{{ old('suffix', $scholar->suffix) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" placeholder="Jr., Sr., III, etc.">
                            @error('suffix')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="birth_date" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Birth Date</label>
                            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', $scholar->birth_date ? $scholar->birth_date->format('Y-m-d') : '') }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);">
                            @error('birth_date')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                <!-- Address & Contact Information -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-4" style="color: rgb(64 64 64);">Address & Contact Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="street" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Street</label>
                            <input type="text" id="street" name="street" value="{{ old('street', $scholar->street) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" placeholder="Street address">
                            @error('street')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="village" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Village/Barangay</label>
                            <input type="text" id="village" name="village" value="{{ old('village', $scholar->village) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" placeholder="Village or Barangay">
                            @error('village')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="district" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">District</label>
                            <input type="text" id="district" name="district" value="{{ old('district', $scholar->district) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" placeholder="District">
                            @error('district')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="region" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Region</label>
                            <input type="text" id="region" name="region" value="{{ old('region', $scholar->region) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" placeholder="Region">
                            @error('region')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="town" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Town/City</label>
                            <input type="text" id="town" name="town" value="{{ old('town', $scholar->town) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" placeholder="Town or City">
                            @error('town')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="province" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Province <span style="color: #D32F2F;">*</span></label>
                            <input type="text" id="province" name="province" value="{{ old('province', $scholar->province) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" required>
                            @error('province')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="zipcode" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Zip Code</label>
                            <input type="text" id="zipcode" name="zipcode" value="{{ old('zipcode', $scholar->zipcode) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" placeholder="Postal code">
                            @error('zipcode')
                                <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Country <span style="color: #D32F2F;">*</span></label>
                            <select id="country" name="country" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" required>
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
                    <h2 class="text-xl font-semibold mb-4" style="color: rgb(64 64 64);">Scholarship Details</h2>
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4" style="color: rgb(64 64 64);">University Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="intended_university" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Intended University <span style="color: #D32F2F;">*</span></label>
                                <input type="text" id="intended_university" name="intended_university" value="{{ old('intended_university', $scholar->intended_university ?? 'Central Luzon State University') }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" required>
                                @error('intended_university')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="department" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Department <span style="color: #D32F2F;">*</span></label>
                                <input type="text" id="department" name="department" value="Department of Agricultural and Biosystems Engineering" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); background-color: #F5F5F5; --tw-ring-color: rgb(34 197 94);" readonly required>
                                <p class="text-xs mt-1" style="color: rgb(115 115 115);">ABE Department at CLSU</p>
                                @error('department')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="course" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Course <span style="color: #D32F2F;">*</span></label>
                                <input type="text" id="course" name="course" value="{{ old('course', $scholar->course ?? 'Agricultural Engineering') }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" required>
                                @error('course')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="entry_type" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Entry Type</label>
                <select id="entry_type" name="entry_type" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);">
                    <option value="">Select Entry Type</option>
                    <option value="NEW" {{ old('entry_type', $scholar->entry_type) == 'NEW' ? 'selected' : '' }}>New</option>
                    <option value="LATERAL" {{ old('entry_type', $scholar->entry_type) == 'LATERAL' ? 'selected' : '' }}>Lateral</option>
                </select>
                @error('entry_type')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="intended_degree" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Intended Degree</label>
                                <input type="text" id="intended_degree" name="intended_degree" value="{{ old('intended_degree', $scholar->intended_degree) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" placeholder="Intended degree">
                                @error('intended_degree')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="level" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Level</label>
                                <input type="text" id="level" name="level" value="{{ old('level', $scholar->level) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" placeholder="Academic level">
                                @error('level')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="major" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Major</label>
                                <input type="text" id="major" name="major" value="{{ old('major', $scholar->major) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" placeholder="Major field of study">
                                @error('major')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label for="thesis_dissertation_title" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Thesis/Dissertation Title</label>
                                <input type="text" id="thesis_dissertation_title" name="thesis_dissertation_title" value="{{ old('thesis_dissertation_title', $scholar->thesis_dissertation_title) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" placeholder="Thesis or dissertation title">
                                @error('thesis_dissertation_title')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="units_required" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Units Required</label>
                                <input type="number" id="units_required" name="units_required" value="{{ old('units_required', $scholar->units_required) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" placeholder="Total units required">
                                @error('units_required')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="units_earned_prior" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Units Earned Prior</label>
                                <input type="number" id="units_earned_prior" name="units_earned_prior" value="{{ old('units_earned_prior', $scholar->units_earned_prior) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" placeholder="Units earned previously">
                                @error('units_earned_prior')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="start_date" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Start Date</label>
                                <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $scholar->start_date) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);">
                                @error('start_date')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="actual_completion_date" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Actual Completion Date</label>
                                <input type="date" id="actual_completion_date" name="actual_completion_date" value="{{ old('actual_completion_date', $scholar->actual_completion_date) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);">
                                @error('actual_completion_date')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>



                        </div>
                    </div>
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4" style="color: rgb(64 64 64);">Scholarship Status & Timeline</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="status" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Status <span style="color: #D32F2F;">*</span></label>
                                <select id="status" name="status" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" required>
                                    <option value="Active" {{ old('status', $scholar->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Graduated" {{ old('status', $scholar->status) == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                                    <option value="Deferred" {{ old('status', $scholar->status) == 'Deferred' ? 'selected' : '' }}>Deferred</option>
                                    <option value="Dropped" {{ old('status', $scholar->status) == 'Dropped' ? 'selected' : '' }}>Dropped</option>
                                    <option value="Inactive" {{ old('status', $scholar->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>


                            <div>
                                <label for="scholarship_duration" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Scholarship Duration (months) <span style="color: #D32F2F;">*</span></label>
                                <input type="number" id="scholarship_duration" name="scholarship_duration" value="{{ old('scholarship_duration', $scholar->scholarship_duration) }}" min="1" max="60" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" required>
                                @error('scholarship_duration')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="study_time" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Study Time <span style="color: #D32F2F;">*</span></label>
                                <select id="study_time" name="study_time" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" required>
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
                        <h3 class="text-lg font-semibold mb-4" style="color: rgb(64 64 64);">Academic Background</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="course_completed" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">Course Completed</label>
                                <input type="text" id="course_completed" name="course_completed" value="{{ old('course_completed', $scholar->course_completed) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" placeholder="Previous course completed">
                                @error('course_completed')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="university_graduated" class="block text-sm font-medium mb-1" style="color: rgb(64 64 64);">University Graduated</label>
                                <input type="text" id="university_graduated" name="university_graduated" value="{{ old('university_graduated', $scholar->university_graduated) }}" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 transition-all duration-200" style="border-color: rgb(224 224 224); --tw-ring-color: rgb(34 197 94);" placeholder="Previous university">
                                @error('university_graduated')
                                    <p class="text-xs mt-1" style="color: #D32F2F;">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <a href="{{ route('admin.scholars.show', $scholar->id) }}" class="px-4 py-2 text-white rounded-lg hover:opacity-90 mr-4 transition-all duration-200" style="background-color: rgb(59 130 246);">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Details
                    </a>
                    <button type="submit" class="px-6 py-2 text-white rounded-lg transition-all duration-200 hover:opacity-90" style="background-color: rgb(34 197 94);">
                        <i class="fas fa-save mr-2"></i> Update Scholar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/sweetalert2.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Cache DOM element
        const form = document.getElementById('scholar-edit-form');
        
        // Optimized form submission with confirmation
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Streamlined SweetAlert configuration
            Swal.fire({
                title: 'Update Scholar Information?',
                text: "This will update the scholar's information with the provided details.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#22c55e',
                cancelButtonColor: '#dc2626',
                confirmButtonText: 'Yes, update scholar!'
            }).then(result => result.isConfirmed && form.submit());
        });
    });
</script>

<style>
/* Global Typography Improvements */
body {
    font-family: theme(fontFamily.sans);
    font-size: 15px;
    line-height: 1.6;
    color: rgb(64 64 64);
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
