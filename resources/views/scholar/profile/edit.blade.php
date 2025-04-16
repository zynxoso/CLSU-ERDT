@extends('layouts.app')

@section('title', $isNew ? 'Create Profile' : 'Edit Profile')

@section('content')
<div class=" min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <a href="{{ route('scholar.profile') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i> Back to Profile
            </a>
            <h1 class="text-2xl font-bold text-gray-800 mt-2">{{ $isNew ? 'Create Your Profile' : 'Edit Your Profile' }}</h1>
        </div>

        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <form action="{{ route('scholar.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $scholarProfile->phone ?? '') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address <span class="text-red-500">*</span></label>
                            <input type="text" id="address" name="address" value="{{ old('address', $scholarProfile->address ?? '') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="profile_photo" class="block text-sm font-medium text-gray-700 mb-1">Profile Photo</label>
                            <input type="file" id="profile_photo" name="profile_photo" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('profile_photo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            @if($scholarProfile && $scholarProfile->profile_photo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $scholarProfile->profile_photo) }}" alt="Current Profile Photo" class="h-20 w-20 object-cover rounded">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Academic Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="university" class="block text-sm font-medium text-gray-700 mb-1">University <span class="text-red-500">*</span></label>
                            <input type="text" id="university" name="university" value="{{ old('university', $scholarProfile->university ?? 'Central Luzon State University') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <p class="text-xs text-gray-500 mt-1">CLSU - Central Luzon State University</p>
                            @error('university')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department <span class="text-red-500">*</span></label>
                            <input type="text" id="department" name="department" value="{{ old('department', $scholarProfile->department ?? 'Engineering') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('department')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="program" class="block text-sm font-medium text-gray-700 mb-1">Program <span class="text-red-500">*</span></label>
                            <select id="program" name="program" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="Master in Agricultural and Biosystems Engineering" {{ (old('program', $scholarProfile->program ?? '') == 'Master in Agricultural and Biosystems Engineering') ? 'selected' : '' }}>Master in Agricultural and Biosystems Engineering</option>
                                <option value="Master of Science in Agricultural and Biosystems Engineering" {{ (old('program', $scholarProfile->program ?? '') == 'Master of Science in Agricultural and Biosystems Engineering') ? 'selected' : '' }}>Master of Science in Agricultural and Biosystems Engineering</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">ABE Masteral Scholarship Program</p>
                            @error('program')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="major" class="block text-sm font-medium text-gray-700 mb-1">Bachelor's Degree</label>
                            <input type="text" id="major" name="major" value="{{ old('major', $scholarProfile->major ?? 'BS in Agricultural and Biosystems Engineering') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Bachelor of Science in Agricultural and Biosystems Engineering (BSABE)</p>
                            @error('major')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">Student ID <span class="text-red-500">*</span></label>
                            <input type="text" id="student_id" name="student_id" value="{{ old('student_id', $scholarProfile->student_id ?? '') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('student_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gpa" class="block text-sm font-medium text-gray-700 mb-1">Current GPA</label>
                            <input type="text" id="gpa" name="gpa" value="{{ old('gpa', $scholarProfile->gpa ?? '') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('gpa')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $scholarProfile->start_date ?? '') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('start_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="expected_completion_date" class="block text-sm font-medium text-gray-700 mb-1">Expected Completion Date</label>
                            <input type="date" id="expected_completion_date" name="expected_completion_date" value="{{ old('expected_completion_date', $scholarProfile->expected_completion_date ?? '') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('expected_completion_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Research Information</h2>

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="research_title" class="block text-sm font-medium text-gray-700 mb-1">Research Title</label>
                            <input type="text" id="research_title" name="research_title" value="{{ old('research_title', $scholarProfile->research_title ?? '') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('research_title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="research_area" class="block text-sm font-medium text-gray-700 mb-1">Research Area</label>
                            <select id="research_area" name="research_area" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Research Area</option>
                                <option value="Agricultural Machinery and Equipment" {{ (old('research_area', $scholarProfile->research_area ?? '') == 'Agricultural Machinery and Equipment') ? 'selected' : '' }}>Agricultural Machinery and Equipment</option>
                                <option value="Irrigation and Water Resources" {{ (old('research_area', $scholarProfile->research_area ?? '') == 'Irrigation and Water Resources') ? 'selected' : '' }}>Irrigation and Water Resources</option>
                                <option value="Food Processing and Post-Harvest Technology" {{ (old('research_area', $scholarProfile->research_area ?? '') == 'Food Processing and Post-Harvest Technology') ? 'selected' : '' }}>Food Processing and Post-Harvest Technology</option>
                                <option value="Environmental Engineering" {{ (old('research_area', $scholarProfile->research_area ?? '') == 'Environmental Engineering') ? 'selected' : '' }}>Environmental Engineering</option>
                                <option value="Sustainable Agriculture" {{ (old('research_area', $scholarProfile->research_area ?? '') == 'Sustainable Agriculture') ? 'selected' : '' }}>Sustainable Agriculture</option>
                                <option value="Renewable Energy" {{ (old('research_area', $scholarProfile->research_area ?? '') == 'Renewable Energy') ? 'selected' : '' }}>Renewable Energy</option>
                                <option value="Precision Agriculture" {{ (old('research_area', $scholarProfile->research_area ?? '') == 'Precision Agriculture') ? 'selected' : '' }}>Precision Agriculture</option>
                                <option value="Other" {{ (old('research_area', $scholarProfile->research_area ?? '') == 'Other') ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('research_area')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="research_abstract" class="block text-sm font-medium text-gray-700 mb-1">Research Abstract</label>
                            <textarea id="research_abstract" name="research_abstract" rows="4" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('research_abstract', $scholarProfile->research_abstract ?? '') }}</textarea>
                            @error('research_abstract')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="advisor" class="block text-sm font-medium text-gray-700 mb-1">Advisor</label>
                            <input type="text" id="advisor" name="advisor" value="{{ old('advisor', $scholarProfile->advisor ?? '') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('advisor')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i> {{ $isNew ? 'Create Profile' : 'Update Profile' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
