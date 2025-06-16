@extends('layouts.app')

@section('title', 'Add New Scholar')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto">
        <div class="mb-6">
            <!-- <a href="{{ route('admin.scholars.index') }}" class="text-blue-400 hover:text-blue-300">
                <i class="fas fa-arrow-left mr-2"></i> Back to Scholars
            </a> -->
            <h1 class="text-2xl font-bold text-gray-900 mt-2">Add New Scholar</h1>
            <!-- <p class="text-gray-500 mt-1">ERDT PRISM: A Portal for a Responsive and Integrated Scholarship Management</p> -->
        </div>

        <!-- Multi-step Progress Indicator -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-8 border border-blue-100 shadow-sm">
                <div class="text-center mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Scholar Registration Process</h3>
                    <p class="text-sm text-gray-600">Complete the steps below to register a new scholar</p>
                </div>

                <div class="flex items-center justify-between relative">
                    <!-- Step 1: Basic Information -->
                    <div class="flex flex-col items-center text-center relative z-10">
                        <div class="relative mb-4">
                            <div id="step-indicator-1" class="w-16 h-16 rounded-full bg-blue-600 text-white flex items-center justify-center text-xl font-bold shadow-lg transition-all duration-300 border-4 border-white">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="absolute -top-1 -right-1 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center opacity-0 transition-opacity duration-300" id="step-check-1">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="max-w-24">
                            <div class="text-sm font-semibold text-blue-600 mb-1">Basic Information</div>
                            <div class="text-xs text-gray-500">Personal Details</div>
                        </div>
                    </div>

                    <!-- Progress Line 1 -->
                    <div class="flex-1 mx-6 relative">
                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div id="progress-line-1" class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full transition-all duration-700 ease-in-out transform origin-left" style="width: 0%; transform: scaleX(0)"></div>
                        </div>
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                            <div class="w-3 h-3 bg-white border-2 border-gray-300 rounded-full transition-all duration-300" id="progress-dot-1"></div>
                        </div>
                    </div>

                    <!-- Step 2: Address & Contact -->
                    <div class="flex flex-col items-center text-center relative z-10">
                        <div class="relative mb-4">
                            <div id="step-indicator-2" class="w-16 h-16 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center text-xl font-bold shadow-lg transition-all duration-300 border-4 border-white">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="absolute -top-1 -right-1 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center opacity-0 transition-opacity duration-300" id="step-check-2">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="max-w-24">
                            <div class="text-sm font-semibold text-gray-500 mb-1">Address & Contact</div>
                            <div class="text-xs text-gray-400">Location Info</div>
                        </div>
                    </div>

                    <!-- Progress Line 2 -->
                    <div class="flex-1 mx-6 relative">
                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div id="progress-line-2" class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full transition-all duration-700 ease-in-out transform origin-left" style="width: 0%; transform: scaleX(0)"></div>
                        </div>
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                            <div class="w-3 h-3 bg-white border-2 border-gray-300 rounded-full transition-all duration-300" id="progress-dot-2"></div>
                        </div>
                    </div>

                    <!-- Step 3: Scholarship Details -->
                    <div class="flex flex-col items-center text-center relative z-10">
                        <div class="relative mb-4">
                            <div id="step-indicator-3" class="w-16 h-16 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center text-xl font-bold shadow-lg transition-all duration-300 border-4 border-white">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="absolute -top-1 -right-1 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center opacity-0 transition-opacity duration-300" id="step-check-3">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="max-w-24">
                            <div class="text-sm font-semibold text-gray-500 mb-1">Scholarship Details</div>
                            <div class="text-xs text-gray-400">Academic Info</div>
                        </div>
                    </div>

                    <!-- Progress Line 3 -->
                    <div class="flex-1 mx-6 relative">
                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div id="progress-line-3" class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full transition-all duration-700 ease-in-out transform origin-left" style="width: 0%; transform: scaleX(0)"></div>
                        </div>
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                            <div class="w-3 h-3 bg-white border-2 border-gray-300 rounded-full transition-all duration-300" id="progress-dot-3"></div>
                        </div>
                    </div>

                    <!-- Step 4: Review & Submit -->
                    <div class="flex flex-col items-center text-center relative z-10">
                        <div class="relative mb-4">
                            <div id="step-indicator-4" class="w-16 h-16 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center text-xl font-bold shadow-lg transition-all duration-300 border-4 border-white">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="absolute -top-1 -right-1 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center opacity-0 transition-opacity duration-300" id="step-check-4">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="max-w-24">
                            <div class="text-sm font-semibold text-gray-500 mb-1">Review & Submit</div>
                            <div class="text-xs text-gray-400">Final Check</div>
                        </div>
                    </div>
                </div>

                <!-- Progress Status Text -->
                <div class="mt-6 text-center">
                    <div id="progress-status" class="text-sm text-gray-600 font-medium">
                        Step 1 of 4: Enter basic information
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <form action="{{ route('admin.scholars.store') }}" method="POST" id="multi-step-scholar-form">
                @csrf
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <!-- Step 1: Basic Information -->
                <div id="step-1" class="step-content">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Step 1: Basic Information</h2>

                    <div class="mb-6 bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <h3 class="text-lg font-semibold text-blue-800 mb-2">
                            <i class="fas fa-info-circle mr-2" style="color: #1e40af;"></i> Scholar Login Information
                        </h3>
                        <p class="text-sm text-blue-700 mb-3">When you create a scholar account, the system will generate the following login credentials:</p>

                        <div class="flex flex-col md:flex-row md:items-center mb-2 p-3 bg-white rounded border border-blue-100">
                            <div class="font-medium text-blue-900 md:w-1/4">Login Email:</div>
                            <div class="md:w-3/4 text-gray-700">The email address you provide in the form below</div>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center p-3 bg-white rounded border border-blue-100">
                            <div class="font-medium text-blue-900 md:w-1/4">Default Password:</div>
                            <div class="md:w-3/4 flex items-center">
                                <code class="bg-gray-100 px-2 py-1 rounded text-gray-700 font-mono">CLSU-scholar123</code>
                                <button type="button" class="ml-2 px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 focus:outline-none text-sm" onclick="copyPassword()">
                                    <i class="fas fa-copy mr-1"></i> Copy
                                </button>
                            </div>
                        </div>

                        <p class="text-sm text-blue-700 mt-3">
                            <i class="fas fa-exclamation-triangle mr-1" style="color: #1e40af;"></i> Important: Please inform the scholar about their default password. They should change it after their first login.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('first_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('last_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                            <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('middle_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <p class="text-xs text-gray-500 mt-1">The scholar will use this email to login.</p>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                            <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('contact_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="button" id="next-step-1" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300">
                            Next: Address & Contact <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Address & Contact -->
                <div id="step-2" class="step-content hidden">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Step 2: Address & Contact Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Street Address <span class="text-red-500">*</span></label>
                            <input type="text" id="address" name="address" value="{{ old('address') }}" placeholder="Street number, building, barangay" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City/Municipality <span class="text-red-500">*</span></label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('city')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Province <span class="text-red-500">*</span></label>
                            <input type="text" id="province" name="province" value="{{ old('province') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('province')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal Code <span class="text-red-500">*</span></label>
                            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('postal_code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country <span class="text-red-500">*</span></label>
                            <select id="country" name="country" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="Philippines" {{ old('country', 'Philippines') == 'Philippines' ? 'selected' : '' }}>Philippines</option>
                                <option value="Other" {{ old('country') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('country')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="button" id="prev-step-2" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-300">
                            <i class="fas fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button type="button" id="next-step-2" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300">
                            Next: Scholarship Details <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Scholarship Details -->
                <div id="step-3" class="step-content hidden">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Step 3: Scholarship Details</h2>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">University Information</h3>
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
                                <label for="major" class="block text-sm font-medium text-gray-700 mb-1">Major/Specialization <span class="text-red-500">*</span></label>
                                <select id="major" name="major" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    <option value="">Select Major/Specialization</option>
                                    <option value="Agricultural and Biosystems Engineering" {{ old('major') == 'Agricultural and Biosystems Engineering' ? 'selected' : '' }}>Agricultural and Biosystems Engineering</option>
                                    <option value="Agricultural Machinery and Equipment" {{ old('major') == 'Agricultural Machinery and Equipment' ? 'selected' : '' }}>Agricultural Machinery and Equipment</option>
                                    <option value="Irrigation and Water Management" {{ old('major') == 'Irrigation and Water Management' ? 'selected' : '' }}>Irrigation and Water Management</option>
                                    <option value="Post-Harvest Technology" {{ old('major') == 'Post-Harvest Technology' ? 'selected' : '' }}>Post-Harvest Technology</option>
                                    <option value="Agricultural Structures and Environment" {{ old('major') == 'Agricultural Structures and Environment' ? 'selected' : '' }}>Agricultural Structures and Environment</option>
                                    <option value="Food Engineering" {{ old('major') == 'Food Engineering' ? 'selected' : '' }}>Food Engineering</option>
                                    <option value="Renewable Energy Systems" {{ old('major') == 'Renewable Energy Systems' ? 'selected' : '' }}>Renewable Energy Systems</option>
                                    <option value="Precision Agriculture" {{ old('major') == 'Precision Agriculture' ? 'selected' : '' }}>Precision Agriculture</option>
                                    <option value="Agricultural Process Engineering" {{ old('major') == 'Agricultural Process Engineering' ? 'selected' : '' }}>Agricultural Process Engineering</option>
                                    <option value="Soil and Water Engineering" {{ old('major') == 'Soil and Water Engineering' ? 'selected' : '' }}>Soil and Water Engineering</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Choose your specific area of specialization within ABE</p>
                                @error('major')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="degree_level" class="block text-sm font-medium text-gray-700 mb-1">Degree Level <span class="text-red-500">*</span></label>
                                <select id="degree_level" name="degree_level" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    <option value="Masteral" {{ old('degree_level') == 'Masteral' ? 'selected' : '' }}>Masteral</option>
                                    <option value="PhD" {{ old('degree_level') == 'PhD' ? 'selected' : '' }}>PhD</option>
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
                                <select id="status" name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    <option value="New" {{ old('status') == 'New' ? 'selected' : '' }}>New</option>
                                    <option value="Ongoing" {{ old('status') == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="On Extension" {{ old('status') == 'On Extension' ? 'selected' : '' }}>On Extension</option>
                                    <option value="Graduated" {{ old('status') == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                                    <option value="Terminated" {{ old('status') == 'Terminated' ? 'selected' : '' }}>Terminated</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                                <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                @error('start_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="expected_completion_date" class="block text-sm font-medium text-gray-700 mb-1">Expected Completion Date <span class="text-red-500">*</span></label>
                                <input type="date" id="expected_completion_date" name="expected_completion_date" value="{{ old('expected_completion_date') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                @error('expected_completion_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="scholarship_duration" class="block text-sm font-medium text-gray-700 mb-1">Scholarship Duration (months) <span class="text-red-500">*</span></label>
                                <input type="number" id="scholarship_duration" name="scholarship_duration" value="{{ old('scholarship_duration') }}" min="1" max="60" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                @error('scholarship_duration')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="enrollment_type" class="block text-sm font-medium text-gray-700 mb-1">Enrollment Type <span class="text-red-500">*</span></label>
                                <select id="enrollment_type" name="enrollment_type" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    <option value="New" {{ old('enrollment_type') == 'New' ? 'selected' : '' }}>New</option>
                                    <option value="Lateral" {{ old('enrollment_type') == 'Lateral' ? 'selected' : '' }}>Lateral</option>
                                </select>
                                @error('enrollment_type')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="study_time" class="block text-sm font-medium text-gray-700 mb-1">Study Time <span class="text-red-500">*</span></label>
                                <select id="study_time" name="study_time" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                    <option value="Full-time" {{ old('study_time') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                                    <option value="Part-time" {{ old('study_time') == 'Part-time' ? 'selected' : '' }}>Part-time</option>
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
                                <input type="text" id="bachelor_degree" name="bachelor_degree" value="BS in Agricultural and Biosystems Engineering" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" readonly>
                                <p class="text-xs text-gray-500 mt-1">BSABE is the standard qualification for this program</p>
                                @error('bachelor_degree')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="bachelor_university" class="block text-sm font-medium text-gray-700 mb-1">Bachelor's University</label>
                                <input type="text" id="bachelor_university" name="bachelor_university" value="Central Luzon State University" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" readonly>
                                <p class="text-xs text-gray-500 mt-1">CLSU - Central Luzon State University</p>
                                @error('bachelor_university')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="bachelor_graduation_year" class="block text-sm font-medium text-gray-700 mb-1">Bachelor's Graduation Year</label>
                                <input type="number" id="bachelor_graduation_year" name="bachelor_graduation_year" value="{{ old('bachelor_graduation_year') }}" min="1950" max="{{ date('Y') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('bachelor_graduation_year')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="button" id="prev-step-3" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-300">
                            <i class="fas fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button type="button" id="next-step-3" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300">
                            Next: Review & Submit <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 4: Review & Submit -->
                <div id="step-4" class="step-content hidden">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Step 4: Review & Submit</h2>

                    <div class="bg-gray-50 p-6 rounded-lg mb-6">
                        <h3 class="font-semibold text-gray-800 mb-4">Review Scholar Information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <h4 class="font-medium text-gray-700 border-b pb-1">Basic Information</h4>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Name:</span>
                                    <span id="review-name" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Email:</span>
                                    <span id="review-email" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Phone:</span>
                                    <span id="review-phone" class="font-medium">-</span>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <h4 class="font-medium text-gray-700 border-b pb-1">Address</h4>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Street:</span>
                                    <span id="review-address" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">City:</span>
                                    <span id="review-city" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Province:</span>
                                    <span id="review-province" class="font-medium">-</span>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <h4 class="font-medium text-gray-700 border-b pb-1">Scholarship Details</h4>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Degree Level:</span>
                                    <span id="review-degree" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Major:</span>
                                    <span id="review-major" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span id="review-status" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Start Date:</span>
                                    <span id="review-start-date" class="font-medium">-</span>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <h4 class="font-medium text-gray-700 border-b pb-1">Program Details</h4>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Duration:</span>
                                    <span id="review-duration" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Study Time:</span>
                                    <span id="review-study-time" class="font-medium">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Enrollment:</span>
                                    <span id="review-enrollment" class="font-medium">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <button type="button" id="prev-step-4" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-300">
                            <i class="fas fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300">
                            <i class="fas fa-user-plus mr-2 text-white" style="color: white !important;"></i> Create Scholar Account
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Multi-step form functionality
    const steps = ['step-1', 'step-2', 'step-3', 'step-4'];
    let currentStep = 0;

    function updateProgressIndicator() {
        const totalSteps = 4;

        // Update step indicators and progress lines
        for (let i = 1; i <= totalSteps; i++) {
            const stepIndicator = document.getElementById(`step-indicator-${i}`);
            const stepCheck = document.getElementById(`step-check-${i}`);
            const progressStatus = document.getElementById('progress-status');

            if (i <= currentStep + 1) {
                if (i < currentStep + 1) {
                    // Completed step - green with check mark
                    stepIndicator.classList.remove('bg-gray-300', 'text-gray-500', 'bg-blue-600');
                    stepIndicator.classList.add('bg-green-500', 'text-white');
                    stepCheck.classList.remove('opacity-0');
                    stepCheck.classList.add('opacity-100');

                    // Update text colors for completed steps
                    const stepContainer = stepIndicator.closest('.flex.flex-col');
                    const stepTitle = stepContainer.querySelector('.text-sm.font-semibold');
                    const stepSubtitle = stepContainer.querySelector('.text-xs');
                    stepTitle.classList.remove('text-gray-500', 'text-blue-600');
                    stepTitle.classList.add('text-green-600');
                    stepSubtitle.classList.remove('text-gray-400');
                    stepSubtitle.classList.add('text-green-500');
                } else {
                    // Current active step - blue
                    stepIndicator.classList.remove('bg-gray-300', 'text-gray-500', 'bg-green-500');
                    stepIndicator.classList.add('bg-blue-600', 'text-white');
                    stepCheck.classList.remove('opacity-100');
                    stepCheck.classList.add('opacity-0');

                    // Update text colors for active step
                    const stepContainer = stepIndicator.closest('.flex.flex-col');
                    const stepTitle = stepContainer.querySelector('.text-sm.font-semibold');
                    const stepSubtitle = stepContainer.querySelector('.text-xs');
                    stepTitle.classList.remove('text-gray-500', 'text-green-600');
                    stepTitle.classList.add('text-blue-600');
                    stepSubtitle.classList.remove('text-gray-400', 'text-green-500');
                    stepSubtitle.classList.add('text-gray-500');
                }
            } else {
                // Future inactive step - gray
                stepIndicator.classList.remove('bg-green-500', 'bg-blue-600', 'text-white');
                stepIndicator.classList.add('bg-gray-300', 'text-gray-500');
                stepCheck.classList.remove('opacity-100');
                stepCheck.classList.add('opacity-0');

                // Update text colors for inactive steps
                const stepContainer = stepIndicator.closest('.flex.flex-col');
                const stepTitle = stepContainer.querySelector('.text-sm.font-semibold');
                const stepSubtitle = stepContainer.querySelector('.text-xs');
                stepTitle.classList.remove('text-green-600', 'text-blue-600');
                stepTitle.classList.add('text-gray-500');
                stepSubtitle.classList.remove('text-gray-500', 'text-green-500');
                stepSubtitle.classList.add('text-gray-400');
            }
        }

        // Update progress lines with animations
        for (let i = 1; i <= totalSteps - 1; i++) {
            const progressLine = document.getElementById(`progress-line-${i}`);
            const progressDot = document.getElementById(`progress-dot-${i}`);

            if (i < currentStep + 1) {
                // Completed line - full progress with green color
                progressLine.style.width = '100%';
                progressLine.style.transform = 'scaleX(1)';
                if (progressDot) {
                    progressDot.classList.remove('border-gray-300');
                    progressDot.classList.add('border-green-500', 'bg-green-500');
                }
            } else if (i === currentStep + 1) {
                // Current line - partial progress with blue color
                progressLine.style.width = '50%';
                progressLine.style.transform = 'scaleX(0.5)';
                if (progressDot) {
                    progressDot.classList.remove('border-gray-300', 'border-green-500', 'bg-green-500');
                    progressDot.classList.add('border-blue-500', 'bg-blue-500');
                }
            } else {
                // Inactive line - no progress
                progressLine.style.width = '0%';
                progressLine.style.transform = 'scaleX(0)';
                if (progressDot) {
                    progressDot.classList.remove('border-blue-500', 'bg-blue-500', 'border-green-500', 'bg-green-500');
                    progressDot.classList.add('border-gray-300');
                }
            }
        }

        // Update status text
        const statusMessages = [
            'Step 1 of 4: Enter basic information',
            'Step 2 of 4: Add address and contact details',
            'Step 3 of 4: Configure scholarship details',
            'Step 4 of 4: Review and create scholar account'
        ];

        if (progressStatus && statusMessages[currentStep]) {
            progressStatus.textContent = statusMessages[currentStep];
        }
    }

    function showStep(stepIndex) {
        steps.forEach((stepId, index) => {
            const stepElement = document.getElementById(stepId);
            if (index === stepIndex) {
                stepElement.classList.remove('hidden');
            } else {
                stepElement.classList.add('hidden');
            }
        });
        currentStep = stepIndex;
        updateProgressIndicator();
    }

    function validateStep1() {
        const firstName = document.getElementById('first_name').value.trim();
        const lastName = document.getElementById('last_name').value.trim();
        const email = document.getElementById('email').value.trim();
        const contactNumber = document.getElementById('contact_number').value.trim();

        if (!firstName || !lastName || !email || !contactNumber) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please fill in all required fields in Step 1.',
            });
            return false;
        }
        return true;
    }

    function validateStep2() {
        const address = document.getElementById('address').value.trim();
        const city = document.getElementById('city').value.trim();
        const province = document.getElementById('province').value.trim();
        const postalCode = document.getElementById('postal_code').value.trim();

        if (!address || !city || !province || !postalCode) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please fill in all required fields in Step 2.',
            });
            return false;
        }
        return true;
    }

    function validateStep3() {
        const degreeLevel = document.getElementById('degree_level').value;
        const major = document.getElementById('major').value;
        const status = document.getElementById('status').value;
        const startDate = document.getElementById('start_date').value;
        const expectedCompletionDate = document.getElementById('expected_completion_date').value;
        const scholarshipDuration = document.getElementById('scholarship_duration').value;
        const enrollmentType = document.getElementById('enrollment_type').value;
        const studyTime = document.getElementById('study_time').value;

        if (!degreeLevel || !major || !status || !startDate || !expectedCompletionDate || !scholarshipDuration || !enrollmentType || !studyTime) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please fill in all required fields in Step 3.',
            });
            return false;
        }
        return true;
    }

    function updateReviewSection() {
        // Basic Information
        const firstName = document.getElementById('first_name').value;
        const middleName = document.getElementById('middle_name').value;
        const lastName = document.getElementById('last_name').value;
        const fullName = `${firstName} ${middleName ? middleName + ' ' : ''}${lastName}`;

        document.getElementById('review-name').textContent = fullName;
        document.getElementById('review-email').textContent = document.getElementById('email').value;
        document.getElementById('review-phone').textContent = document.getElementById('contact_number').value;

        // Address
        document.getElementById('review-address').textContent = document.getElementById('address').value;
        document.getElementById('review-city').textContent = document.getElementById('city').value;
        document.getElementById('review-province').textContent = document.getElementById('province').value;

        // Scholarship Details
        document.getElementById('review-degree').textContent = document.getElementById('degree_level').value;
        document.getElementById('review-major').textContent = document.getElementById('major').value;
        document.getElementById('review-status').textContent = document.getElementById('status').value;
        document.getElementById('review-start-date').textContent = document.getElementById('start_date').value;
        document.getElementById('review-duration').textContent = document.getElementById('scholarship_duration').value + ' months';
        document.getElementById('review-study-time').textContent = document.getElementById('study_time').value;
        document.getElementById('review-enrollment').textContent = document.getElementById('enrollment_type').value;
    }

    // Step navigation
    document.getElementById('next-step-1').addEventListener('click', function() {
        if (validateStep1()) {
            showStep(1);
        }
    });

    document.getElementById('prev-step-2').addEventListener('click', function() {
        showStep(0);
    });

    document.getElementById('next-step-2').addEventListener('click', function() {
        if (validateStep2()) {
            showStep(2);
        }
    });

    document.getElementById('prev-step-3').addEventListener('click', function() {
        showStep(1);
    });

    document.getElementById('next-step-3').addEventListener('click', function() {
        if (validateStep3()) {
            updateReviewSection();
            showStep(3);
        }
    });

    document.getElementById('prev-step-4').addEventListener('click', function() {
        showStep(2);
    });

    // Form submission with confirmation
    const form = document.getElementById('multi-step-scholar-form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Create Scholar Account?',
            text: "This will create a new scholar account with the provided information.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, create account!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create a hidden field with timestamp
                const timestampField = document.createElement('input');
                timestampField.type = 'hidden';
                timestampField.name = 'submission_time';
                timestampField.value = new Date().toISOString();
                form.appendChild(timestampField);

                form.submit();
            }
        });
    });
});

// Function to copy the default password to clipboard
function copyPassword() {
    const password = 'CLSU-scholar123';

    // Create a temporary element
    const tempElement = document.createElement('textarea');
    tempElement.value = password;
    document.body.appendChild(tempElement);

    // Select and copy the text
    tempElement.select();
    document.execCommand('copy');

    // Remove the temporary element
    document.body.removeChild(tempElement);

    // Show a tooltip or notification
    Swal.fire({
        icon: 'success',
        title: 'Copied!',
        text: 'Password copied to clipboard: ' + password,
        timer: 2000,
        showConfirmButton: false
    });
}
</script>
@endsection
