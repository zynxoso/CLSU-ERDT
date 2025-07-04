@extends('layouts.app')

@section('title', $isNew ? 'Create Profile' : 'Edit Profile')

@section('content')
<div class=" min-h-screen">
    <div class="container mx-auto">
        <div class="mb-6">
            <!-- <a href="{{ route('scholar.profile') }}" class="text-blue-600 hover:text-blue-800">
                <i class="fas fa-arrow-left mr-2"></i> Back to Profile
            </a> -->
            <h1 class="text-2xl font-bold text-gray-800 mt-2">{{ $isNew ? 'Create Your Profile' : 'Edit Your Profile' }}</h1>
        </div>

        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <form action="{{ route('scholar.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- New Profile Photo Section with Alpine.js -->
                        <div class="md:col-span-2" x-data="{
                            initialPhoto: '{{ $scholarProfile && $scholarProfile->profile_photo ? asset('storage/' . $scholarProfile->profile_photo) : '' }}',
                            photoPreview: null,
                            removePhotoFlag: false,
                            init() {
                                this.photoPreview = this.initialPhoto;
                            },
                            get displayPhotoUrl() {
                                return this.photoPreview;
                            },
                            get hasDisplayablePhoto() {
                                return !!this.photoPreview;
                            },
                            updatePreview(event) {
                                const file = event.target.files[0];
                                if (file) {
                                    if (file.size > 2 * 1024 * 1024) { // 2MB validation
                                        alert('File is too large. Maximum size is 2MB.');
                                        event.target.value = null;
                                        return;
                                    }
                                    if (!['image/jpeg', 'image/png', 'image/gif'].includes(file.type)) {
                                        alert('Invalid file type. Only JPG, PNG, or GIF are allowed.');
                                        event.target.value = null;
                                        return;
                                    }
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        this.photoPreview = e.target.result;
                                        this.removePhotoFlag = false;
                                    };
                                    reader.readAsDataURL(file);
                                }
                            },
                            triggerFileInput() {
                                this.$refs.photoInput.click();
                            },
                            removeActivePhoto() {
                                this.photoPreview = ''; // Clear the preview
                                this.$refs.photoInput.value = null; // Clear the file input field
                                this.removePhotoFlag = true; // Indicate that a photo (either existing or newly selected) should be removed
                            }
                        }">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                            <div class="mt-1 flex items-center space-x-6">
                                <!-- Profile Photo Preview & Upload Trigger -->
                                <div class="relative group">
                                    <div @click="triggerFileInput()"
                                         class="h-24 w-24 rounded-full overflow-hidden border-2 border-gray-300 bg-gray-100 flex items-center justify-center cursor-pointer
                                                hover:border-blue-500 transition-colors duration-200">
                                        <template x-if="hasDisplayablePhoto">
                                            <img x-bind:src="displayPhotoUrl" alt="Profile Photo" class="h-full w-full object-cover">
                                        </template>
                                        <template x-if="!hasDisplayablePhoto">
                                            <svg class="h-16 w-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.67 0 8.997 1.701 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </template>
                                        <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-full">
                                            <svg class="h-8 w-8 text-white mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="text-xs text-white" x-text="hasDisplayablePhoto ? 'Change' : 'Upload'"></span>
                                        </div>
                                    </div>
                                    <template x-if="hasDisplayablePhoto">
                                        <button @click="removeActivePhoto()" type="button"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1.5 shadow-md
                                                       hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-transform transform hover:scale-110">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </template>
                                </div>

                                <!-- Hidden File Input & Info -->
                                <div class="flex-1">
                                    <input type="file" id="profile_photo" name="profile_photo" accept="image/jpeg,image/png,image/gif"
                                           class="hidden" x-ref="photoInput" @change="updatePreview($event)">

                                    <button type="button" @click="triggerFileInput()"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white
                                                   hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                        <span x-text="hasDisplayablePhoto ? 'Change Photo' : 'Upload Photo'"></span>
                                    </button>

                                    <p class="mt-2 text-xs text-gray-500">
                                        JPG, PNG or GIF (max. 2MB).
                                    </p>
                                    @error('profile_photo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <input type="hidden" name="remove_photo" x-bind:value="removePhotoFlag ? '1' : '0'">
                        </div>
                        <!-- End New Profile Photo Section -->



                        <div>
                            <label for="birthdate" class="block text-sm font-medium text-gray-700 mb-1">Birthdate <span class="text-red-500">*</span></label>
                            <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate', $scholarProfile->birthdate ?? '') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('birthdate')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $scholarProfile->phone ?? '') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                            <select id="gender" name="gender" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select Gender</option>
                                <option value="Male" {{ (old('gender', $scholarProfile->gender ?? '') == 'Male') ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ (old('gender', $scholarProfile->gender ?? '') == 'Female') ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ (old('gender', $scholarProfile->gender ?? '') == 'Other') ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Complete Address <span class="text-red-500">*</span></label>
                            <textarea id="address" name="address" rows="2" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('address', $scholarProfile->address ?? '') }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>


                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Academic Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="university" class="block text-sm font-medium text-gray-700 mb-1">University <span class="text-red-500">*</span></label>
                            <input type="text" id="university" name="university" value="{{ old('university', $scholarProfile->university ?? 'Central Luzon State University') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required readonly>
                            @error('university')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                            <input type="text" id="department" name="department" value="{{ old('department', $scholarProfile->department ?? '') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('department')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="program" class="block text-sm font-medium text-gray-700 mb-1">Program <span class="text-red-500">*</span></label>
                            <input type="text" id="program" name="program" value="{{ old('program', $scholarProfile->program ?? '') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('program')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="degree_level" class="block text-sm font-medium text-gray-700 mb-1">Degree Level <span class="text-red-500">*</span></label>
                            <select id="degree_level" name="degree_level" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select Degree Level</option>
                                <option value="Master's" {{ (old('degree_level', $scholarProfile->degree_level ?? '') == "Master's") ? 'selected' : '' }}>Master's</option>
                                <option value="PhD" {{ (old('degree_level', $scholarProfile->degree_level ?? '') == 'PhD') ? 'selected' : '' }}>PhD</option>
                            </select>
                            @error('degree_level')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="major" class="block text-sm font-medium text-gray-700 mb-1">Major/Specialization</label>
                            <select id="major" name="major" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Major/Specialization</option>
                                <option value="Agricultural Machinery and Equipment" {{ (old('major', $scholarProfile->major ?? '') == 'Agricultural Machinery and Equipment') ? 'selected' : '' }}>Agricultural Machinery and Equipment</option>
                                <option value="Irrigation and Water Resources Engineering" {{ (old('major', $scholarProfile->major ?? '') == 'Irrigation and Water Resources Engineering') ? 'selected' : '' }}>Irrigation and Water Resources Engineering</option>
                                <option value="Food Processing and Post-Harvest Technology" {{ (old('major', $scholarProfile->major ?? '') == 'Food Processing and Post-Harvest Technology') ? 'selected' : '' }}>Food Processing and Post-Harvest Technology</option>
                                <option value="Environmental Engineering" {{ (old('major', $scholarProfile->major ?? '') == 'Environmental Engineering') ? 'selected' : '' }}>Environmental Engineering</option>
                                <option value="Sustainable Agriculture Systems" {{ (old('major', $scholarProfile->major ?? '') == 'Sustainable Agriculture Systems') ? 'selected' : '' }}>Sustainable Agriculture Systems</option>
                                <option value="Renewable Energy Systems" {{ (old('major', $scholarProfile->major ?? '') == 'Renewable Energy Systems') ? 'selected' : '' }}>Renewable Energy Systems</option>
                                <option value="Precision Agriculture" {{ (old('major', $scholarProfile->major ?? '') == 'Precision Agriculture') ? 'selected' : '' }}>Precision Agriculture</option>
                                <option value="Aquaculture Engineering" {{ (old('major', $scholarProfile->major ?? '') == 'Aquaculture Engineering') ? 'selected' : '' }}>Aquaculture Engineering</option>
                                <option value="Biosystems Engineering" {{ (old('major', $scholarProfile->major ?? '') == 'Biosystems Engineering') ? 'selected' : '' }}>Biosystems Engineering</option>
                                <option value="Other" {{ (old('major', $scholarProfile->major ?? '') == 'Other') ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('major')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bachelor_degree" class="block text-sm font-medium text-gray-700 mb-1">Bachelor's Degree</label>
                            <input type="text" id="bachelor_degree" name="bachelor_degree" value="{{ old('bachelor_degree', $scholarProfile->bachelor_degree ?? '') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('bachelor_degree')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bachelor_university" class="block text-sm font-medium text-gray-700 mb-1">Bachelor's University</label>
                            <input type="text" id="bachelor_university" name="bachelor_university" value="{{ old('bachelor_university', $scholarProfile->bachelor_university ?? '') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('bachelor_university')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Scholarship Start Date</label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $scholarProfile->start_date ? $scholarProfile->start_date->format('Y-m-d') : '') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('start_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="expected_completion_date" class="block text-sm font-medium text-gray-700 mb-1">Expected Completion Date</label>
                            <input type="date" id="expected_completion_date" name="expected_completion_date" value="{{ old('expected_completion_date', $scholarProfile->expected_completion_date ? $scholarProfile->expected_completion_date->format('Y-m-d') : '') }}" class="w-full bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('expected_completion_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- <div class="mb-6">
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
                </div> --}}

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
