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
    
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                            <div class="mt-1 flex items-center space-x-4">
                                <!-- Profile Photo Preview -->
                                <div class="relative group">
                                    <div class="h-24 w-24 rounded-full overflow-hidden border-2 border-gray-300 bg-gray-100 flex items-center justify-center">
                                        @if($scholarProfile && $scholarProfile->profile_photo)
                                            <img id="profile-photo-preview" src="{{ asset('storage/' . $scholarProfile->profile_photo) }}" alt="Current Profile Photo" class="h-full w-full object-cover">
                                        @else
                                            <svg id="default-avatar" class="h-16 w-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.67 0 8.997 1.701 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        @endif
                                        <div id="upload-overlay" class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-full">
                                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    @if($scholarProfile && $scholarProfile->profile_photo)
                                        <button type="button" onclick="removePhoto()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>

                                <!-- Upload Button -->
                                <div class="flex-1">
                                    <div class="relative">
                                        <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewPhoto(event)">
                                        <label for="profile_photo" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 cursor-pointer">
                                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $scholarProfile && $scholarProfile->profile_photo ? 'Change Photo' : 'Upload Photo' }}
                                        </label>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">
                                        JPG, PNG or GIF (max. 2MB)
                                    </p>
                                    @error('profile_photo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <input type="hidden" name="remove_photo" id="remove_photo" value="0">
                        </div>

                        @push('scripts')
                        <script>
                            function previewPhoto(event) {
                                const input = event.target;
                                const preview = document.getElementById('profile-photo-preview');
                                const defaultAvatar = document.getElementById('default-avatar');
                                const file = input.files[0];
                                
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        if (!preview) {
                                            const img = document.createElement('img');
                                            img.id = 'profile-photo-preview';
                                            img.className = 'h-full w-full object-cover';
                                            img.alt = 'Profile Photo Preview';
                                            input.closest('.flex').querySelector('.relative').insertBefore(img, input);
                                            if (defaultAvatar) defaultAvatar.style.display = 'none';
                                        } else {
                                            preview.src = e.target.result;
                                        }
                                    };
                                    reader.readAsDataURL(file);
                                    document.getElementById('remove_photo').value = '0';
                                }
                            }

                            function removePhoto() {
                                const preview = document.getElementById('profile-photo-preview');
                                const defaultAvatar = document.getElementById('default-avatar');
                                const fileInput = document.getElementById('profile_photo');
                                
                                if (preview) {
                                    preview.remove();
                                }
                                
                                if (defaultAvatar) {
                                    defaultAvatar.style.display = 'block';
                                }
                                
                                if (fileInput) {
                                    fileInput.value = '';
                                }
                                
                                document.getElementById('remove_photo').value = '1';
                            }
                        </script>
                        @endpush

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
