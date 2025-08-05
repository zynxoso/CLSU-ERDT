@extends('layouts.app')

@section('title', $isNew ? 'Create Profile' : 'Edit Profile')

@section('content')
<div class="min-h-screen bg-gray-50" x-data="multiStepForm()">
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-600 mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $isNew ? 'Create Scholar Profile' : 'Edit Profile' }}</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                {{ $isNew ? 'Complete your scholar profile to access all system features and submit requests.' : 'Update your information to keep your profile current and accurate.' }}
            </p>
        </div>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <template x-for="(step, index) in steps" :key="index">
                    <div class="flex items-center" :class="index < steps.length - 1 ? 'flex-1' : ''">
                        <div class="flex items-center justify-center w-10 h-10 border-2 transition-all duration-300"
                             :class="{
                                 'bg-green-600 border-green-600 text-white': index <= currentStep,
                                 'bg-gray-200 border-gray-300 text-gray-500': index > currentStep
                             }">
                            <span class="text-sm font-semibold" x-text="index + 1"></span>
                        </div>
                        <div class="ml-3 min-w-0" x-show="index < steps.length - 1">
                            <p class="text-sm font-medium text-gray-900" x-text="step.title"></p>
                        </div>
                        <div class="flex-1 h-0.5 mx-4" x-show="index < steps.length - 1"
                             :class="index < currentStep ? 'bg-green-600' : 'bg-gray-300'"></div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium">Please correct the following errors:</span>
                </div>
                <ul class="list-disc list-inside ml-6 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Error Message -->
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Form Container -->
        <div class="bg-white border border-gray-200 shadow-sm">
            <form action="{{ route('scholar.profile.update') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf

                <!-- Step 1: Personal Information -->
                <div x-show="currentStep === 0" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Personal Information</h2>
                        
                        <!-- Profile Photo Upload -->
                        <div class="mb-6" x-data="profilePhotoUpload()">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                            <div class="flex items-start space-x-6">
                                <!-- Photo Preview -->
                                <div class="relative">
                                    <div class="w-24 h-24 border-2 border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden"
                                         @dragover.prevent="dragOver = true"
                                         @dragleave.prevent="dragOver = false"
                                         @drop.prevent="handleDrop($event)"
                                         :class="dragOver ? 'border-green-500 bg-green-50' : 'border-gray-300'">
                                        <template x-if="hasDisplayablePhoto">
                                            <img :src="displayPhotoUrl" alt="Profile photo" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!hasDisplayablePhoto">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </template>
                                    </div>
                                    <button type="button" x-show="hasDisplayablePhoto" @click="removeActivePhoto()" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white text-xs flex items-center justify-center hover:bg-red-600">
                                        ×
                                    </button>
                                </div>
                                
                                <!-- Upload Controls -->
                                <div class="flex-1">
                                    <input type="file" x-ref="photoInput" @change="updatePreview($event)" accept="image/*" class="hidden" name="profile_photo">
                                    <input type="hidden" name="remove_photo" :value="removePhotoFlag ? '1' : '0'">
                                    
                                    <button type="button" @click="triggerFileInput()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        Choose Photo
                                    </button>
                                    
                                    <p class="mt-2 text-xs text-gray-500">JPG, PNG or GIF. Max size 2MB.</p>
                                    <p x-show="errorMessage" x-text="errorMessage" class="mt-1 text-xs text-red-600"></p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- First Name -->
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    First Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $scholarProfile->first_name ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('first_name') border-red-500 @enderror" placeholder="Enter your first name">
                                @error('first_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Middle Name -->
                            <div>
                                <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                                <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', $scholarProfile->middle_name ?? '') }}" class="w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('middle_name') border-red-500 @enderror" placeholder="Enter your middle name">
                                @error('middle_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Last Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $scholarProfile->last_name ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('last_name') border-red-500 @enderror" placeholder="Enter your last name">
                                @error('last_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Suffix -->
                            <div>
                                <label for="suffix" class="block text-sm font-medium text-gray-700 mb-2">Suffix</label>
                                <input type="text" id="suffix" name="suffix" value="{{ old('suffix', $scholarProfile->suffix ?? '') }}" class="w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('suffix') border-red-500 @enderror" placeholder="Jr., Sr., III, etc.">
                                @error('suffix')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Birth Date -->
                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Birth Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', optional($scholarProfile?->birth_date)->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('birth_date') border-red-500 @enderror">
                                @error('birth_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone', $scholarProfile->phone ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('phone') border-red-500 @enderror" placeholder="Enter your phone number">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div class="md:col-span-2">
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                    Gender <span class="text-red-500">*</span>
                                </label>
                                <select id="gender" name="gender" required class="w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('gender') border-red-500 @enderror">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender', $scholarProfile->gender ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $scholarProfile->gender ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender', $scholarProfile->gender ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Address Information -->
                <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Address Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                            <!-- Street -->
                            <div>
                                <label for="street" class="block text-sm font-medium text-gray-700 mb-2">Street</label>
                                <input type="text" id="street" name="street" value="{{ old('street', $scholarProfile->street ?? '') }}" class="w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('street') border-red-500 @enderror" placeholder="Enter street name">
                                @error('street')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Village -->
                            <div>
                                <label for="village" class="block text-sm font-medium text-gray-700 mb-2">Village/Barangay</label>
                                <input type="text" id="village" name="village" value="{{ old('village', $scholarProfile->village ?? '') }}" class="w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('village') border-red-500 @enderror" placeholder="Enter village/barangay">
                                @error('village')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- City -->
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                <input type="text" id="city" name="city" value="{{ old('city', $scholarProfile->city ?? '') }}" class="w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('city') border-red-500 @enderror" placeholder="e.g., Science City of Muñoz">
                                @error('city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- District -->
                            <div>
                                <label for="district" class="block text-sm font-medium text-gray-700 mb-2">District</label>
                                <input type="text" id="district" name="district" value="{{ old('district', $scholarProfile->district ?? '') }}" class="w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('district') border-red-500 @enderror" placeholder="Enter district">
                                @error('district')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Region -->
                            <div>
                                <label for="region" class="block text-sm font-medium text-gray-700 mb-2">Region</label>
                                <input type="text" id="region" name="region" value="{{ old('region', $scholarProfile->region ?? '') }}" class="w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('region') border-red-500 @enderror" placeholder="Enter region">
                                @error('region')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Province -->
                            <div>
                                <label for="province" class="block text-sm font-medium text-gray-700 mb-2">Province</label>
                                <input type="text" id="province" name="province" value="{{ old('province', $scholarProfile->province ?? '') }}" class="w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('province') border-red-500 @enderror" placeholder="Enter province">
                                @error('province')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Country -->
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                                <input type="text" id="country" name="country" value="{{ old('country', $scholarProfile->country ?? '') }}" class="w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('country') border-red-500 @enderror" placeholder="Enter country">
                                @error('country')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Academic Information -->
                <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Academic Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- University -->
                            <div class="md:col-span-2">
                                <label for="intended_university" class="block text-sm font-medium text-gray-700 mb-2">
                                    Intended University <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="intended_university" name="intended_university" value="{{ old('intended_university', $scholarProfile->intended_university ?? 'Central Luzon State University') }}" required class="w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('intended_university') border-red-500 @enderror">
                                @error('intended_university')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Department -->
                            <div>
                                <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                                <input type="text" id="department" name="department" value="Engineering" class="w-full px-3 py-2 border border-gray-300 bg-gray-100 text-gray-900 focus:outline-none @error('department') border-red-500 @enderror" readonly>
                                @error('department')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>





                            <!-- Major -->






                            <!-- Course Completed -->
                            <div>
                                <label for="course_completed" class="block text-sm font-medium text-gray-700 mb-2">Course Completed</label>
                                <select id="course_completed" name="course_completed" class="w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('course_completed') border-red-500 @enderror">
                                    <option value="">Select Course Completed</option>
                                    <option value="MS in Agricultural Engineering" {{ old('course_completed', $scholarProfile->course_completed ?? '') === 'MS in Agricultural Engineering' ? 'selected' : '' }}>MS in Agricultural Engineering</option>
                                    <option value="BS Agricultural and Biosystem Engineering" {{ old('course_completed', $scholarProfile->course_completed ?? '') === 'BS Agricultural and Biosystem Engineering' ? 'selected' : '' }}>BS Agricultural and Biosystem Engineering</option>
                                </select>
                                @error('course_completed')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- University Graduated -->
                            <div>
                                <label for="university_graduated" class="block text-sm font-medium text-gray-700 mb-2">University Graduated</label>
                                <input type="text" id="university_graduated" name="university_graduated" value="{{ old('university_graduated', $scholarProfile->university_graduated ?? '') }}" class="w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('university_graduated') border-red-500 @enderror" placeholder="Enter university graduated from">
                                @error('university_graduated')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Entry Type -->
                            <div>
                                <label for="entry_type" class="block text-sm font-medium text-gray-700 mb-2">Entry Type</label>
                                <select id="entry_type" name="entry_type" class="w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('entry_type') border-red-500 @enderror">
                                    <option value="">Select Entry Type</option>
                                    <option value="New" {{ old('entry_type', $scholarProfile->entry_type ?? '') === 'New' ? 'selected' : '' }}>New</option>
                                    <option value="Lateral" {{ old('entry_type', $scholarProfile->entry_type ?? '') === 'Lateral' ? 'selected' : '' }}>Lateral</option>
                                </select>
                                @error('entry_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>



                            <!-- Intended Degree -->
                            <div class="md:col-span-2">
                                <label for="intended_degree" class="block text-sm font-medium text-gray-700 mb-2">Intended Degree</label>
                                <select id="intended_degree" name="intended_degree" class="w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('intended_degree') border-red-500 @enderror" required>
                                    <option value="">Select Intended Degree</option>
                                    <option value="PHD in ABE" {{ old('intended_degree', $scholarProfile->intended_degree ?? '') == 'PHD in ABE' ? 'selected' : '' }}>PHD in ABE</option>
                                    <option value="MS in ABE" {{ old('intended_degree', $scholarProfile->intended_degree ?? '') == 'MS in ABE' ? 'selected' : '' }}>MS in ABE</option>
                                </select>
                                @error('intended_degree')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Thesis/Dissertation Title -->
                            <div class="md:col-span-2">
                                <label for="thesis_dissertation_title" class="block text-sm font-medium text-gray-700 mb-2">Thesis/Dissertation Title</label>
                                <textarea id="thesis_dissertation_title" name="thesis_dissertation_title" rows="3" class="w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('thesis_dissertation_title') border-red-500 @enderror" placeholder="Enter your thesis/dissertation title">{{ old('thesis_dissertation_title', $scholarProfile->thesis_dissertation_title ?? '') }}</textarea>
                                @error('thesis_dissertation_title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Units Required -->
                            <div>
                                <label for="units_required" class="block text-sm font-medium text-gray-700 mb-2">Units Required</label>
                                <input type="number" id="units_required" name="units_required" value="{{ old('units_required', $scholarProfile->units_required ?? '') }}" class="w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('units_required') border-red-500 @enderror" placeholder="Enter units required" min="0" step="0.5">
                                @error('units_required')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Units Earned Prior -->
                            <div>
                                <label for="units_earned_prior" class="block text-sm font-medium text-gray-700 mb-2">Units Earned Prior</label>
                                <input type="number" id="units_earned_prior" name="units_earned_prior" value="{{ old('units_earned_prior', $scholarProfile->units_earned_prior ?? '') }}" class="w-full px-3 py-2 border border-gray-300 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('units_earned_prior') border-red-500 @enderror" placeholder="Enter units earned prior" min="0" step="0.5">
                                @error('units_earned_prior')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Scholarship Details -->
                <div x-show="currentStep === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Scholarship Details</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">







                        </div>
                    </div>
                </div>

                <!-- Step 5: Review & Submit -->
                <div x-show="currentStep === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Review & Submit</h2>
                        
                        <div class="bg-gray-50 p-4 mb-6">
                            <p class="text-sm text-gray-600 mb-4">Please review all the information you've entered before submitting your profile.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <h3 class="font-medium text-gray-900 mb-2">Personal Information</h3>
                                    <ul class="text-gray-600">
                                        <li><span class="font-semibold">First Name:</span> {{ old('first_name', $scholarProfile->first_name ?? '') }}</li>
                                        <li><span class="font-semibold">Middle Name:</span> {{ old('middle_name', $scholarProfile->middle_name ?? '') }}</li>
                                        <li><span class="font-semibold">Last Name:</span> {{ old('last_name', $scholarProfile->last_name ?? '') }}</li>
                                        <li><span class="font-semibold">Suffix:</span> {{ old('suffix', $scholarProfile->suffix ?? '') }}</li>
                                        <li><span class="font-semibold">Birth Date:</span> {{ old('birth_date', optional($scholarProfile?->birth_date)->format('Y-m-d')) }}</li>
                                        <li><span class="font-semibold">Phone:</span> {{ old('phone', $scholarProfile->phone ?? '') }}</li>
                                        <li><span class="font-semibold">Gender:</span> {{ old('gender', $scholarProfile->gender ?? '') }}</li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900 mb-2">Address Information</h3>
                                    <ul class="text-gray-600">

                                        <li><span class="font-semibold">Street:</span> {{ old('street', $scholarProfile->street ?? '') }}</li>
                                        <li><span class="font-semibold">Village/Barangay:</span> {{ old('village', $scholarProfile->village ?? '') }}</li>
        
                                        
                                        <li><span class="font-semibold">District:</span> {{ old('district', $scholarProfile->district ?? '') }}</li>
                                        <li><span class="font-semibold">Region:</span> {{ old('region', $scholarProfile->region ?? '') }}</li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900 mb-2">Academic Information</h3>
                                    <ul class="text-gray-600">
                                        <li><span class="font-semibold">Intended University:</span> {{ old('intended_university', $scholarProfile->intended_university ?? '') }}</li>
                                        <li><span class="font-semibold">Department:</span> {{ old('department', $scholarProfile->department ?? '') }}</li>




                                        <li><span class="font-semibold">Course Completed:</span> {{ old('course_completed', $scholarProfile->course_completed ?? '') }}</li>
                                        <li><span class="font-semibold">University Graduated:</span> {{ old('university_graduated', $scholarProfile->university_graduated ?? '') }}</li>
                                        <li><span class="font-semibold">Entry Type:</span> {{ old('entry_type', $scholarProfile->entry_type ?? '') }}</li>

                                        <li><span class="font-semibold">Intended Degree:</span> {{ old('intended_degree', $scholarProfile->intended_degree ?? '') }}</li>
                                        <li><span class="font-semibold">Thesis/Dissertation Title:</span> {{ old('thesis_dissertation_title', $scholarProfile->thesis_dissertation_title ?? '') }}</li>
                                        <li><span class="font-semibold">Units Required:</span> {{ old('units_required', $scholarProfile->units_required ?? '') }}</li>
                                        <li><span class="font-semibold">Units Earned Prior:</span> {{ old('units_earned_prior', $scholarProfile->units_earned_prior ?? '') }}</li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900 mb-2">Scholarship Details</h3>
                                    <ul class="text-gray-600">

                                        <li><span class="font-semibold">Start Date:</span> {{ old('start_date', $scholarProfile->start_date ?? '') }}</li>
                                        <li><span class="font-semibold">Year Graduated:</span> {{ old('year_graduated', $scholarProfile->year_graduated ?? '') }}</li>
                                        <li><span class="font-semibold">Scholarship Status:</span> {{ old('scholarship_status', $scholarProfile->scholarship_status ?? '') }}</li>
                                        <li><span class="font-semibold">Remarks:</span> {{ old('remarks', $scholarProfile->remarks ?? '') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2 text-sm text-gray-600 mb-6">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>All changes are saved automatically upon submission</span>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between items-center p-6 border-t border-gray-200 bg-gray-50">
                    <button type="button" @click="previousStep()" x-show="currentStep > 0" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Previous
                    </button>
                    
                    <div class="flex gap-3">
                        <a href="{{ route('scholar.profile') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500">
                            Cancel
                        </a>
                        
                        <button type="button" @click="nextStep()" x-show="currentStep < steps.length - 1" class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            Next
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                        
                        <button type="submit" x-show="currentStep === steps.length - 1" class="inline-flex items-center px-8 py-2 border border-transparent text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            {{ $isNew ? 'Create Profile' : 'Update Profile' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('multiStepForm', () => ({
            currentStep: 0,
            steps: [
                { title: 'Personal Information', fields: ['first_name', 'last_name', 'birth_date', 'phone', 'gender'] },
                { title: 'Address Information', fields: [] },
                { title: 'Academic Information', fields: ['intended_university', 'department'] },
                { title: 'Scholarship Details', fields: [] },
                { title: 'Review & Submit', fields: [] }
            ],
            
            nextStep() {
                if (this.validateCurrentStep()) {
                    if (this.currentStep < this.steps.length - 1) {
                        this.currentStep++;
                    }
                }
            },
            
            previousStep() {
                if (this.currentStep > 0) {
                    this.currentStep--;
                }
            },
            
            validateCurrentStep() {
                const currentStepFields = this.steps[this.currentStep].fields;
                let isValid = true;
                
                currentStepFields.forEach(fieldName => {
                    const field = document.querySelector(`[name="${fieldName}"]`);
                    if (field && field.hasAttribute('required') && !field.value.trim()) {
                        field.focus();
                        isValid = false;
                        return false;
                    }
                });
                
                return isValid;
            }
        }));
        
        Alpine.data('profilePhotoUpload', () => ({
            initialPhoto: '{{ $scholarProfile && $scholarProfile->profile_photo ? asset('images/' . $scholarProfile->profile_photo) : '' }}',
            photoPreview: null,
            removePhotoFlag: false,
            dragOver: false,
            errorMessage: '',
            
            init() {
                this.photoPreview = this.initialPhoto;
            },
            
            get displayPhotoUrl() {
                return this.photoPreview;
            },
            
            get hasDisplayablePhoto() {
                return !!this.photoPreview;
            },
            
            validateFile(file) {
                this.errorMessage = '';
                
                if (file.size > 2 * 1024 * 1024) {
                    this.errorMessage = 'File is too large. Maximum size is 2MB.';
                    return false;
                }
                
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    this.errorMessage = 'Invalid file type. Only JPG, PNG, or GIF are allowed.';
                    return false;
                }
                
                return true;
            },
            
            updatePreview(event) {
                const file = event.target.files[0];
                if (file && this.validateFile(file)) {
                    this.processFile(file);
                } else if (file) {
                    event.target.value = null;
                }
            },
            
            processFile(file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                    this.removePhotoFlag = false;
                    this.errorMessage = '';
                };
                reader.readAsDataURL(file);
            },
            
            handleDrop(event) {
                this.dragOver = false;
                const files = event.dataTransfer.files;
                
                if (files.length > 0) {
                    const file = files[0];
                    if (this.validateFile(file)) {
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        this.$refs.photoInput.files = dataTransfer.files;
                        this.processFile(file);
                    }
                }
            },
            
            triggerFileInput() {
                this.$refs.photoInput.click();
            },
            
            removeActivePhoto() {
                this.photoPreview = '';
                this.$refs.photoInput.value = null;
                this.removePhotoFlag = true;
                this.errorMessage = '';
            }
        }));
    });
</script>
@endsection
