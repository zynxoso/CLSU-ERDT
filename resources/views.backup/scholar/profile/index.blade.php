@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class=" min-h-screen">
    <div class="container mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">My Profile</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Summary -->
            <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                <div class="p-6 text-center border-b border-gray-200">
                    <div x-data="{ photoName: null, photoPreview: null }" class="w-24 h-24 rounded-full bg-gray-100 mx-auto mb-4 overflow-hidden relative group">
                        <input type="file" class="hidden" x-ref="photo" @change="
                            photoName = $refs.photo.files[0].name;
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                photoPreview = e.target.result;
                            };
                            reader.readAsDataURL($refs.photo.files[0]);
                        ">

                        <label for="photo-upload-trigger" class="cursor-pointer">
                            <div @click="$refs.photo.click()" class="w-full h-full">
                                <template x-if="photoPreview">
                                    <img :src="photoPreview" alt="Profile Preview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!photoPreview">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-user text-3xl text-gray-500"></i>
                                        </div>
                                    @endif
                                </template>
                            </div>
                            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300" @click="$refs.photo.click()">
                                <i class="fas fa-camera text-white text-2xl"></i>
                            </div>
                        </label>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $user->name }}</h2>
                    <p class="text-gray-600">{{ $scholar->program }} Scholar</p>
                    <p class="text-gray-500 text-sm mt-1">{{ $scholar->university }}</p>

                    <div class="mt-4 flex justify-center">
                        <span class="px-3 py-1 text-xs rounded-full
                            @if($scholar->status == 'Active') bg-green-100 text-green-800
                            @elseif($scholar->status == 'Inactive') bg-red-100 text-red-800
                            @elseif($scholar->status == 'Completed') bg-blue-100 text-blue-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $scholar->status }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Contact Information</h3>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-gray-500 w-5"></i>
                                <span class="text-gray-700 ml-2">{{ $user->email }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone text-gray-500 w-5"></i>
                                <span class="text-gray-700 ml-2">{{ $scholar->contact_number ?? $scholar->phone ?? 'Not provided' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-gray-500 w-5"></i>
                                <span class="text-gray-700 ml-2">{{ $scholar->address ?? 'Not provided' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Personal Information</h3>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-birthday-cake text-gray-500 w-5"></i>
                                <span class="text-gray-700 ml-2">{{ $scholar->birth_date ? $scholar->birth_date->format('M d, Y') : 'Not provided' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-venus-mars text-gray-500 w-5"></i>
                                <span class="text-gray-700 ml-2">{{ $scholar->gender ?? 'Not provided' }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Scholarship Details</h3>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-graduation-cap text-gray-500 w-5"></i>
                                <span class="text-gray-700 ml-2">{{ $scholar->degree_level ?? 'Not provided' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-gray-500 w-5"></i>
                                <span class="text-gray-700 ml-2">Started: {{ $scholar->start_date ? $scholar->start_date->format('M d, Y') : 'Not provided' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar-check text-gray-500 w-5"></i>
                                <span class="text-gray-700 ml-2">Expected completion: {{ $scholar->expected_completion_date ? $scholar->expected_completion_date->format('M d, Y') : 'Not provided' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('scholar.profile.edit') }}" class="w-full block text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-edit mr-2"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Academic Information -->
                <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                    <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Academic Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">University</h4>
                                <p class="text-gray-800">{{ $scholar->university }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Department</h4>
                                <p class="text-gray-800">{{ $scholar->department ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Program</h4>
                                <p class="text-gray-800">{{ $scholar->program }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Degree Level</h4>
                                <p class="text-gray-800">{{ $scholar->degree_level ?? 'Not provided' }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Major/Specialization</h4>
                                <p class="text-gray-800">{{ $scholar->major ?? 'Not provided' }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Bachelor's Degree</h4>
                                <p class="text-gray-800">{{ $scholar->bachelor_degree ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Bachelor's University</h4>
                                <p class="text-gray-800">{{ $scholar->bachelor_university ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Research Information -->
                <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                    <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Research Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Research Title</h4>
                            <p class="text-gray-800">{{ $scholar->research_title ?? 'Not provided' }}</p>
                        </div>
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Research Abstract</h4>
                            <p class="text-gray-700">{{ $scholar->research_abstract ?? 'Not provided' }}</p>
                        </div>
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Research Notes</h4>
                            <p class="text-gray-800">{{ $scholar->notes ?? 'Not provided' }}</p>
                        </div>
                        @if($scholar->enrollment_type || $scholar->study_time || $scholar->scholarship_duration)
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Program Details</h4>
                            <div class="space-y-2">
                                @if($scholar->enrollment_type)
                                <div class="flex items-center">
                                    <i class="fas fa-user-graduate text-gray-500 w-5"></i>
                                    <span class="text-gray-700 ml-2">Enrollment: {{ $scholar->enrollment_type }}</span>
                                </div>
                                @endif
                                @if($scholar->study_time)
                                <div class="flex items-center">
                                    <i class="fas fa-clock text-gray-500 w-5"></i>
                                    <span class="text-gray-700 ml-2">Study Time: {{ $scholar->study_time }}</span>
                                </div>
                                @endif
                                @if($scholar->scholarship_duration)
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt text-gray-500 w-5"></i>
                                    <span class="text-gray-700 ml-2">Duration: {{ $scholar->scholarship_duration }} months</span>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
