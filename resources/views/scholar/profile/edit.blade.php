@extends('layouts.app')

@section('title', $isNew ? 'Create Profile' : 'Edit Profile')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold" style="color: #212121; font-size: 28px;">{{ $isNew ? 'Create Your Profile' : 'Edit Your Profile' }}</h1>
            <p class="mt-2" style="color: #424242; font-size: 15px;">Complete your scholar profile information</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
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

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <form action="{{ route('scholar.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8" novalidate>
                @csrf

                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div>
                            <h2 class="text-xl font-bold" style="color: #212121; font-size: 20px;">Personal Information</h2>
                            <p class="text-sm" style="color: #424242; font-size: 14px;">Your basic personal details</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                        <!-- New Profile Photo Section with Alpine.js -->
                        <div class="md:col-span-2" x-data="profilePhotoUpload">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>

                            <!-- Enhanced Drag & Drop Zone -->
                            <div class="relative">
                                <!-- Main Upload Area -->
                                <div
                                    @click="triggerFileInput()"
                                    @dragover.prevent="dragOver = true"
                                    @dragleave.prevent="dragOver = false"
                                    @drop.prevent="handleDrop($event)"
                                    class="group relative h-24 w-24 mx-auto border-2 border-dashed rounded-lg cursor-pointer"
                                    :class="{
                                        'border-blue-500 bg-blue-50': dragOver,
                                        'border-gray-300 bg-gray-50 hover:border-blue-400 hover:bg-blue-50': !dragOver && !hasDisplayablePhoto,
                                        'border-gray-300 bg-white': !dragOver && hasDisplayablePhoto
                                    }"
                                >
                                    <!-- Photo Preview -->
                                    <template x-if="hasDisplayablePhoto">
                                        <div class="relative h-full w-full">
                                            <img
                                                x-bind:src="displayPhotoUrl"
                                                alt="Profile Photo"
                                                class="h-full w-full object-cover rounded-lg"
                                            >
                                            <!-- Overlay with edit option -->
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 rounded-lg flex items-center justify-center">
                                                <div class="opacity-0 group-hover:opacity-100 text-center">
                                                    <svg class="h-6 w-6 text-white mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    <span class="text-white text-xs font-medium">Change</span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Upload Placeholder -->
                                    <template x-if="!hasDisplayablePhoto">
                                        <div class="flex flex-col items-center justify-center h-full p-2 text-center">
                                            <svg class="h-8 w-8 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            <p class="text-xs text-gray-500 mb-1">
                                                <span x-text="dragOver ? 'Drop here' : 'Upload Photo'"></span>
                                            </p>
                                            <p class="text-xs text-gray-400">Max 2MB</p>
                                        </div>
                                    </template>

                                    <!-- Remove Photo Button -->
                                    <template x-if="hasDisplayablePhoto">
                                        <button
                                            @click.stop="removeActivePhoto()"
                                            type="button"
                                            class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full p-1 shadow-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                            style="background-color: #800020;"
                                        >
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: white;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </template>
                                </div>

                                <!-- File Input -->
                                <input
                                    type="file"
                                    id="profile_photo"
                                    name="profile_photo"
                                    accept="image/jpeg,image/png,image/gif"
                                    class="hidden"
                                    x-ref="photoInput"
                                    @change="updatePreview($event)"
                                >

                                <!-- Error Messages -->
                                <div x-show="errorMessage" class="mt-2">
                                    <div class="flex items-center gap-2 text-sm" style="color: #D32F2F;">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span x-text="errorMessage"></span>
                                    </div>
                                </div>

                                @error('profile_photo')
                                    <div class="flex items-center gap-2 mt-2 text-sm" style="color: #D32F2F;">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror

                                <!-- Upload Progress (if needed for future enhancement) -->
                                <div x-show="uploading" class="mt-2">
                                    <div class="bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" :style="`width: ${uploadProgress}%`"></div>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">Uploading... <span x-text="uploadProgress"></span>%</p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-3 flex flex-wrap justify-center gap-2">
                                    <button
                                        type="button"
                                        @click="triggerFileInput()"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                    >
                                        <svg class="-ml-1 mr-1 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <span x-text="hasDisplayablePhoto ? 'Change' : 'Select'"></span>
                                    </button>

                                    <template x-if="hasDisplayablePhoto">
                                        <button
                                            type="button"
                                            @click="removeActivePhoto()"
                                            class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-xs font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                            style="border-color: #800020; color: #800020; background-color: #fdf2f8;"
                                        >
                                            <svg class="-ml-1 mr-1 h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #800020;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Remove
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <!-- Hidden form field for remove photo flag -->
                            <input type="hidden" name="remove_photo" x-bind:value="removePhotoFlag ? '1' : '0'">
                        </div>
                        <!-- End New Profile Photo Section -->

                                                <!-- Personal Name Fields -->
                                                <div class="space-y-2">
                                                    <label for="first_name" class="flex items-center gap-2 text-sm font-semibold text-gray-800">
                                                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                        First Name
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Required</span>
                                                    </label>
                                                    <div class="relative group">
                                                        <input type="text"
                                                               id="first_name"
                                                               name="first_name"
                                                               value="{{ old('first_name', $scholarProfile->first_name ?? '') }}"
                                                               class="peer w-full bg-gray-50 border-2 @error('first_name') border-red-500 @else border-gray-200 @enderror rounded-xl px-4 py-3 text-gray-900 placeholder-gray-500
                                                                      focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:bg-white
                                                                      hover:border-gray-300"
                                                               required
                                                               pattern="[a-zA-Z\s\-\']*"
                                                               title="Only letters, spaces, hyphens, and apostrophes are allowed"
                                                               placeholder="Enter your first name">
                                                        @error('first_name')
                                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    @error('first_name')
                                                        <div class="flex items-center gap-2 mt-2 text-sm text-red-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <span>{{ $message }}</span>
                                                        </div>
                                                    @enderror
                                                    <div class="mt-1 text-sm text-gray-500">
                                                        Only letters, spaces, hyphens, and apostrophes are allowed
                                                    </div>
                                                </div>

                                                <div class="space-y-2">
                                                    <label for="middle_name" class="flex items-center gap-2 text-sm font-semibold text-gray-800">
                                                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                        Middle Name
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Optional</span>
                                                    </label>
                                                    <div class="relative group">
                                                        <input type="text"
                                                               id="middle_name"
                                                               name="middle_name"
                                                               value="{{ old('middle_name', $scholarProfile->middle_name ?? '') }}"
                                                               class="peer w-full bg-gray-50 border-2 @error('middle_name') border-red-500 @else border-gray-200 @enderror rounded-xl px-4 py-3 text-gray-900 placeholder-gray-500
                                                                      focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:bg-white
                                                                      hover:border-gray-300"
                                                               pattern="[a-zA-Z\s\-\']*"
                                                               title="Only letters, spaces, hyphens, and apostrophes are allowed"
                                                               placeholder="Enter your middle name">
                                                        @error('middle_name')
                                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    @error('middle_name')
                                                        <div class="flex items-center gap-2 mt-2 text-sm text-red-600">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <span>{{ $message }}</span>
                                                        </div>
                                                    @enderror
                                                    <div class="mt-1 text-sm text-gray-500">
                                                        Only letters, spaces, hyphens, and apostrophes are allowed
                                                    </div>
                                                </div>

                                                <div class="space-y-2">
                                                    <label for="last_name" class="flex items-center gap-2 text-sm font-semibold text-gray-800">
                                                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                        Last Name
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Required</span>
                                                    </label>
                                                    <div class="relative group">
                                                        <input type="text"
                                                               id="last_name"
                                                               name="last_name"
                                                               value="{{ old('last_name', $scholarProfile->last_name ?? '') }}"
                                                               class="peer w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-4 py-3 text-gray-900 placeholder-gray-500
                                                                      focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:bg-white
                                                                      hover:border-gray-300"
                                                               required
                                                               placeholder="Enter your last name">
                                                    </div>
                                                    @error('last_name')
                                                        <div class="flex items-center gap-2 mt-2 text-sm" style="color: #D32F2F;">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <span>{{ $message }}</span>
                                                        </div>
                                                    @enderror
                                                </div>

                        <!-- Birthdate Field -->
                        <div class="space-y-2">
                            <label for="birth_date" class="flex items-center gap-2 text-sm font-semibold text-gray-800">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Birthdate
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Required</span>
                            </label>
                            <div class="relative group">
                                <input type="date"
                                       id="birth_date"
                                       name="birth_date"
                                       value="{{ old('birth_date', $scholarProfile->birth_date ?? '') }}"
                                       class="peer w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-4 py-3 text-gray-900
                                              focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:bg-white
                                              invalid:border-red-400 invalid:ring-4 invalid:ring-red-400/10 invalid:text-red-600
                                              user-invalid:border-red-500 user-invalid:ring-4 user-invalid:ring-red-500/10
                                              valid:border-green-400 valid:ring-4 valid:ring-green-400/10
                                              hover:border-gray-300"
                                       required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                </div>
                            </div>
                            @error('birth_date')
                                <div class="flex items-center gap-2 mt-2 text-sm" style="color: #D32F2F;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Phone Number Field -->
                        <div class="space-y-2">
                            <label for="phone" class="flex items-center gap-2 text-sm font-semibold text-gray-800">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                Phone Number
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Required</span>
                            </label>
                            <div class="relative group">
                                <input type="tel"
                                       id="phone"
                                       name="phone"
                                       value="{{ old('phone', $scholarProfile->phone ?? '') }}"
                                       class="peer w-full bg-gray-50 border-2 @error('phone') border-red-500 @else border-gray-200 @enderror rounded-xl px-4 py-3 text-gray-900 placeholder-gray-500
                                              focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:bg-white
                                              hover:border-gray-300"
                                       required
                                       pattern="(\+?\d{1,3}[\s-]?)?(\(?\d{1,4}\)?[\s-]?)?\d{5,11}"
                                       title="Enter a valid phone number"
                                       placeholder="e.g., +63 912 345 6789">
                                @error('phone')
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                @enderror
                            </div>
                            @error('phone')
                                <div class="flex items-center gap-2 mt-2 text-sm text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                            <div class="mt-1 text-sm text-gray-500">
                                Enter a valid phone number (e.g., +63 912 345 6789)
                            </div>
                        </div>

                        <!-- Gender Field -->
                        <div class="space-y-2">
                            <label for="gender" class="flex items-center gap-2 text-sm font-semibold text-gray-800">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Gender
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Required</span>
                            </label>
                            <div class="relative group">
                                <select id="gender"
                                        name="gender"
                                        class="peer w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-4 py-3 text-gray-900
                                               focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:bg-white
                                               invalid:border-red-400 invalid:ring-4 invalid:ring-red-400/10 invalid:text-red-600
                                               user-invalid:border-red-500 user-invalid:ring-4 user-invalid:ring-red-500/10
                                               valid:border-green-400 valid:ring-4 valid:ring-green-400/10
                                               hover:border-gray-300"
                                        required>
                                    <option value="" class="text-gray-500">Select your gender...</option>
                                    <option value="Male" {{ (old('gender', $scholarProfile->gender ?? '') == 'Male') ? 'selected' : '' }} class="text-gray-900">Male</option>
                                    <option value="Female" {{ (old('gender', $scholarProfile->gender ?? '') == 'Female') ? 'selected' : '' }} class="text-gray-900">Female</option>
                                    <option value="Other" {{ (old('gender', $scholarProfile->gender ?? '') == 'Other') ? 'selected' : '' }} class="text-gray-900">Other</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400 peer-focus:text-emerald-500 peer-valid:text-green-500 peer-user-invalid:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                    </svg>
                                </div>
                            </div>
                            @error('gender')
                                <div class="flex items-center gap-2 mt-2 text-sm" style="color: #D32F2F;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Address Field -->
                        <div class="lg:col-span-2 space-y-2">
                            <label for="address" class="flex items-center gap-2 text-sm font-semibold text-gray-800">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Complete Address
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Required</span>
                            </label>
                            <div class="relative group">
                                <textarea id="address"
                                          name="address"
                                          rows="3"
                                          class="peer w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-4 py-3 text-gray-900 placeholder-gray-500
                                                 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:bg-white
                                                 invalid:border-red-400 invalid:ring-4 invalid:ring-red-400/10 invalid:text-red-600
                                                 user-invalid:border-red-500 user-invalid:ring-4 user-invalid:ring-red-500/10
                                                 valid:border-green-400 valid:ring-4 valid:ring-green-400/10
                                                 hover:border-gray-300"
                                          required
                                          placeholder="Enter your complete home address (Street, Barangay, City/Municipality, Province)">{{ old('address', $scholarProfile->address ?? '') }}</textarea>
                                <div class="absolute top-3 right-3">
                                    <svg class="w-5 h-5 text-gray-400 peer-focus:text-emerald-500 peer-valid:text-green-500 peer-user-invalid:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                            </div>
                            @error('address')
                                <div class="flex items-center gap-2 mt-2 text-sm" style="color: #D32F2F;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>


                    </div>
                </div>

                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Academic Information</h2>
                            <p class="text-sm text-gray-600">Your educational background and program details</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- University Field -->
                        <div class="space-y-2">
                            <label for="university" class="flex items-center gap-2 text-sm font-semibold text-gray-800">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                University
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Required</span>
                            </label>
                            <div class="relative group">
                                <input type="text"
                                       id="university"
                                       name="university"
                                       value="{{ old('university', $scholarProfile->university ?? 'Central Luzon State University') }}"
                                       class="peer w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-4 py-3 text-gray-900 placeholder-gray-500
                                              focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:bg-white
                                              invalid:border-red-400 invalid:ring-4 invalid:ring-red-400/10 invalid:text-red-600
                                              user-invalid:border-red-500 user-invalid:ring-4 user-invalid:ring-red-500/10
                                              valid:border-green-400 valid:ring-4 valid:ring-green-400/10
                                              disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed disabled:border-gray-300"
                                       required readonly>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="w-5 h-5 text-gray-400 peer-focus:text-blue-500 peer-valid:text-green-500 peer-invalid:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            @error('university')
                                <div class="flex items-center gap-2 mt-2 text-sm" style="color: #D32F2F;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Department Field -->
                        <div class="space-y-2">
                            <label for="department" class="flex items-center gap-2 text-sm font-semibold text-gray-800">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Department
                                <span class="text-xs text-gray-500">(Optional)</span>
                            </label>
                            <div class="relative group">
                                <input type="text"
                                       id="department"
                                       name="department"
                                       value="{{ old('department', $scholarProfile->department ?? '') }}"
                                       class="peer w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-4 py-3 text-gray-900 placeholder-gray-500
                                              focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:bg-white
                                              hover:border-gray-300
                                              placeholder-shown:border-gray-200"
                                       placeholder="e.g., Engineering Department">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 opacity-0 peer-focus:opacity-100 transition-opacity">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                            </div>
                            @error('department')
                                <div class="flex items-center gap-2 mt-2 text-sm" style="color: #D32F2F;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Program Field -->
                        <div class="space-y-2">
                            <label for="program" class="flex items-center gap-2 text-sm font-semibold text-gray-800">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Program
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Required</span>
                            </label>
                            <div class="relative group">
                                <input type="text"
                                       id="program"
                                       name="program"
                                       value="{{ old('program', $scholarProfile->program ?? '') }}"
                                       class="peer w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-4 py-3 text-gray-900 placeholder-gray-500
                                              focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:bg-white
                                              invalid:border-red-400 invalid:ring-4 invalid:ring-red-400/10 invalid:text-red-600
                                              user-invalid:border-red-500 user-invalid:ring-4 user-invalid:ring-red-500/10
                                              valid:border-green-400 valid:ring-4 valid:ring-green-400/10
                                              hover:border-gray-300"
                                       required
                                       placeholder="e.g., Agricultural and Biosystems Engineering">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="w-5 h-5 text-gray-400 peer-focus:text-blue-500 peer-valid:text-green-500 peer-user-invalid:text-red-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            @error('program')
                                <div class="flex items-center gap-2 mt-2 text-sm" style="color: #D32F2F;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Degree Level Field -->
                        <div class="space-y-2">
                            <label for="degree_level" class="flex items-center gap-2 text-sm font-semibold text-gray-800">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Degree Level
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Required</span>
                            </label>
                            <div class="relative group">
                                <select id="degree_level"
                                        name="degree_level"
                                        class="peer w-full bg-gray-50 border-2 @error('degree_level') border-red-500 @else border-gray-200 @enderror rounded-xl px-4 py-3 text-gray-900
                                               focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:bg-white
                                               hover:border-gray-300"
                                        required>
                                    <option value="" disabled {{ old('degree_level', $scholarProfile->degree_level ?? '') ? '' : 'selected' }}>Select your degree level</option>
                                    <option value="Masteral" {{ old('degree_level', $scholarProfile->degree_level ?? '') == 'Masteral' ? 'selected' : '' }}>Master's Degree</option>
                                    <option value="PhD" {{ old('degree_level', $scholarProfile->degree_level ?? '') == 'PhD' ? 'selected' : '' }}>PhD (Doctorate)</option>
                                </select>
                                @error('degree_level')
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                @enderror
                            </div>
                            @error('degree_level')
                                <div class="flex items-center gap-2 mt-2 text-sm text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Major Field -->
                        <div class="space-y-2">
                            <label for="major" class="flex items-center gap-2 text-sm font-semibold text-gray-800">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                Major/Specialization
                                <span class="text-xs text-gray-500">(Optional)</span>
                            </label>
                            <div class="relative group">
                                <select id="major"
                                        name="major"
                                        class="peer w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-4 py-3 text-gray-900
                                               focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:bg-white
                                               hover:border-gray-300">
                                    <option value="" class="text-gray-500">Select your specialization...</option>
                                    <option value="AB Machinery and Power Engineering" {{ (old('major', $scholarProfile->major ?? '') == 'AB Machinery and Power Engineering') ? 'selected' : '' }} class="text-gray-900">AB Machinery and Power Engineering</option>
                                    <option value="AB Land and Water Resources Engineering" {{ (old('major', $scholarProfile->major ?? '') == 'AB Land and Water Resources Engineering') ? 'selected' : '' }} class="text-gray-900">AB Land and Water Resources Engineering</option>
                                    <option value="AB Structures and Environment Engineering" {{ (old('major', $scholarProfile->major ?? '') == 'AB Structures and Environment Engineering') ? 'selected' : '' }} class="text-gray-900">AB Structures and Environment Engineering</option>
                                    <option value="AB Process Engineering" {{ (old('major', $scholarProfile->major ?? '') == 'AB Process Engineering') ? 'selected' : '' }} class="text-gray-900">AB Process Engineering</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400 peer-focus:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                    </svg>
                                </div>
                            </div>
                            @error('major')
                                <div class="flex items-center gap-2 mt-2 text-sm" style="color: #D32F2F;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Bachelor's Degree Field -->
                        <div class="space-y-2">
                            <label for="bachelor_degree" class="flex items-center gap-2 text-sm font-semibold text-gray-800">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                Bachelor's Degree
                                <span class="text-xs text-gray-500">(Optional)</span>
                            </label>
                            <div class="relative group">
                                <input type="text"
                                       id="bachelor_degree"
                                       name="bachelor_degree"
                                       value="{{ old('bachelor_degree', $scholarProfile->bachelor_degree ?? '') }}"
                                       class="peer w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-4 py-3 text-gray-900 placeholder-gray-500
                                              focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:bg-white
                                              hover:border-gray-300
                                              placeholder-shown:border-gray-200"
                                       placeholder="e.g., Bachelor of Science in Agricultural Engineering">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 opacity-0 peer-focus:opacity-100 transition-opacity">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                            </div>
                            @error('bachelor_degree')
                                <div class="flex items-center gap-2 mt-2 text-sm" style="color: #D32F2F;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Bachelor's University Field -->
                        <div class="space-y-2">
                            <label for="bachelor_university" class="flex items-center gap-2 text-sm font-semibold text-gray-800">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Bachelor's University
                                <span class="text-xs text-gray-500">(Optional)</span>
                            </label>
                            <div class="relative group">
                                <input type="text"
                                       id="bachelor_university"
                                       name="bachelor_university"
                                       value="{{ old('bachelor_university', $scholarProfile->bachelor_university ?? '') }}"
                                       class="peer w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-4 py-3 text-gray-900 placeholder-gray-500
                                              focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:bg-white
                                              hover:border-gray-300
                                              placeholder-shown:border-gray-200"
                                       placeholder="e.g., Central Luzon State University">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 opacity-0 peer-focus:opacity-100 transition-opacity">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                            </div>
                            @error('bachelor_university')
                                <div class="flex items-center gap-2 mt-2 text-sm" style="color: #D32F2F;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Scholarship Start Date Field -->
                        <div class="space-y-2">
                            <label for="start_date" class="flex items-center gap-2 text-sm font-semibold text-gray-800">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Scholarship Start Date
                                <span class="text-xs text-gray-500">(Optional)</span>
                            </label>
                            <div class="relative group">
                                <input type="date"
                                       id="start_date"
                                       name="start_date"
                                       value="{{ old('start_date', optional($scholarProfile?->start_date)->format('Y-m-d')) }}"
                                       class="peer w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-4 py-3 text-gray-900
                                              focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:bg-white
                                              hover:border-gray-300">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 opacity-0 peer-focus:opacity-100 transition-opacity">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            @error('start_date')
                                <div class="flex items-center gap-2 mt-2 text-sm" style="color: #D32F2F;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Expected Completion Date Field -->
                        <div class="space-y-2">
                            <label for="expected_completion_date" class="flex items-center gap-2 text-sm font-semibold text-gray-800">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                Expected Completion Date
                                <span class="text-xs text-gray-500">(Optional)</span>
                            </label>
                            <div class="relative group">
                                <input type="date"
                                       id="expected_completion_date"
                                       name="expected_completion_date"
                                       value="{{ old('expected_completion_date', optional($scholarProfile?->expected_completion_date)->format('Y-m-d')) }}"
                                       class="peer w-full bg-gray-50 border-2 border-gray-200 rounded-xl px-4 py-3 text-gray-900
                                              focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 focus:bg-white
                                              hover:border-gray-300">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 opacity-0 peer-focus:opacity-100 transition-opacity">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #22c55e;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                </div>
                            </div>
                            @error('expected_completion_date')
                                <div class="flex items-center gap-2 mt-2 text-sm" style="color: #D32F2F;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button Section -->
                <div class="flex flex-col sm:flex-row gap-4 justify-end items-center pt-6 border-t border-gray-200">
                    <div class="flex items-center gap-2 text-sm" style="color: #757575;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #2E7D32;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>All changes are saved automatically upon submission</span>
                    </div>

                    <div class="flex gap-3">
                        <!-- Cancel/Back Button -->
                        <a href="{{ route('scholar.profile') }}"
                           class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-gray-700 bg-white
                                  border-2 border-gray-200 rounded-xl shadow-sm
                                  hover:border-gray-300 hover:bg-gray-50 hover:shadow-md
                                  focus:outline-none focus:ring-4 focus:ring-gray-200 focus:border-gray-400">
                            Cancel
                        </a>

                        <!-- Submit Button -->
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-8 py-3 text-sm font-bold text-white
                                       rounded-xl shadow-lg focus:outline-none
                                       disabled:opacity-50 disabled:cursor-not-allowed"
                                style="background-color: #2E7D32;">
                            <span>{{ $isNew ? 'Create Profile' : 'Update Profile' }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('profilePhotoUpload', () => ({
            // Initial state
            initialPhoto: '{{ $scholarProfile && $scholarProfile->profile_photo ? asset('images/' . $scholarProfile->profile_photo) : '' }}',
            photoPreview: null,
            removePhotoFlag: false,
            dragOver: false,
            errorMessage: '',
            uploading: false,
            uploadProgress: 0,

            // Initialize component
            init() {
                this.photoPreview = this.initialPhoto;
            },

            // Computed properties
            get displayPhotoUrl() {
                return this.photoPreview;
            },

            get hasDisplayablePhoto() {
                return !!this.photoPreview;
            },

            // File validation
            validateFile(file) {
                this.errorMessage = '';

                // Check file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    this.errorMessage = 'File is too large. Maximum size is 2MB.';
                    return false;
                }

                // Check file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    this.errorMessage = 'Invalid file type. Only JPG, PNG, or GIF are allowed.';
                    return false;
                }

                return true;
            },

            // Handle file preview
            updatePreview(event) {
                const file = event.target.files[0];
                if (file && this.validateFile(file)) {
                    this.processFile(file);
                } else if (file) {
                    // Clear invalid file
                    event.target.value = null;
                }
            },

            // Process valid file
            processFile(file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                    this.removePhotoFlag = false;
                    this.errorMessage = '';
                };
                reader.readAsDataURL(file);
            },

            // Handle drag and drop
            handleDrop(event) {
                this.dragOver = false;
                const files = event.dataTransfer.files;

                if (files.length > 0) {
                    const file = files[0];
                    if (this.validateFile(file)) {
                        // Update the file input
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        this.$refs.photoInput.files = dataTransfer.files;

                        this.processFile(file);
                    }
                }
            },

            // Trigger file input
            triggerFileInput() {
                this.$refs.photoInput.click();
            },

            // Remove photo
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
