@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">My Profile</h1>
            <a href="{{ route('scholar.profile.edit') }}" class="px-4 py-2 bg-[#4A90E2] hover:bg-[#357ABD] text-white rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg flex items-center space-x-2">
                <i class="fas fa-edit text-[#E3F2FD]"></i>
                <span>Edit Profile</span>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Summary -->
            <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="p-6 text-center border-b border-gray-200">
                    <div class="w-28 h-28 rounded-full bg-gray-100 mx-auto mb-4 overflow-hidden">
                        @if($scholar->profile_photo)
                            <img src="{{ asset('images/' . $scholar->profile_photo) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-user text-4xl text-gray-500"></i>
                            </div>
                        @endif
                    </div>
                    <h2 class="text-2xl font-semibold text-gray-800 mb-1">{{ $user->name }}</h2>
                    <p class="text-lg text-gray-600">{{ $scholar->program }} Scholar</p>
                    <p class="text-gray-500 text-base mt-1">{{ $scholar->university }}</p>

                    <div class="mt-4 flex justify-center">
                        <span class="px-4 py-1.5 text-sm rounded-full font-medium
                            @if($scholar->status == 'Active') bg-[#4CAF50]/20 text-[#2E7D32] border border-[#4CAF50]/30
                            @elseif($scholar->status == 'Inactive') bg-red-200 text-red-900 border border-red-300
                            @elseif($scholar->status == 'Completed') bg-[#4A90E2]/20 text-[#1565C0] border border-[#4A90E2]/30
                            @else bg-[#FFCA28]/25 text-[#975A16] border border-[#FFCA28]/30 @endif">
                            {{ $scholar->status }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-base font-medium text-gray-700 mb-3">Contact Information</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-[#4A90E2] w-6"></i>
                                <span class="text-gray-700 ml-3 text-base">{{ $user->email }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone text-[#4A90E2] w-6"></i>
                                <span class="text-gray-700 ml-3 text-base">{{ $scholar->contact_number ?? $scholar->phone ?? 'Not provided' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-[#4A90E2] w-6"></i>
                                <span class="text-gray-700 ml-3 text-base">{{ $scholar->address ?? 'Not provided' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-base font-medium text-gray-700 mb-3">Personal Information</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-birthday-cake text-[#4A90E2] w-6"></i>
                                <span class="text-gray-700 ml-3 text-base">{{ $scholar->birth_date ? $scholar->birth_date->format('M d, Y') : 'Not provided' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-venus-mars text-[#4A90E2] w-6"></i>
                                <span class="text-gray-700 ml-3 text-base">{{ $scholar->gender ?? 'Not provided' }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-base font-medium text-gray-700 mb-3">Scholarship Details</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-graduation-cap text-[#4A90E2] w-6"></i>
                                <span class="text-gray-700 ml-3 text-base">{{ $scholar->degree_level ?? 'Not provided' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar text-[#4A90E2] w-6"></i>
                                <span class="text-gray-700 ml-3 text-base">Started: {{ $scholar->start_date ? $scholar->start_date->format('M d, Y') : 'Not provided' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-calendar-check text-[#4A90E2] w-6"></i>
                                <span class="text-gray-700 ml-3 text-base">Expected completion: {{ $scholar->expected_completion_date ? $scholar->expected_completion_date->format('M d, Y') : 'Not provided' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Academic Information -->
                <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Academic Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-[#4A90E2] mb-2">University</h4>
                                <p class="text-gray-800 text-base">{{ $scholar->university }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-[#4A90E2] mb-2">Department</h4>
                                <p class="text-gray-800 text-base">{{ $scholar->department ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-[#4A90E2] mb-2">Program</h4>
                                <p class="text-gray-800 text-base">{{ $scholar->program }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-[#4A90E2] mb-2">Degree Level</h4>
                                <p class="text-gray-800 text-base">{{ $scholar->degree_level ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-[#4A90E2] mb-2">Major/Specialization</h4>
                                <p class="text-gray-800 text-base">{{ $scholar->major ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-[#4A90E2] mb-2">Bachelor's Degree</h4>
                                <p class="text-gray-800 text-base">{{ $scholar->bachelor_degree ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-[#4A90E2] mb-2">Bachelor's University</h4>
                                <p class="text-gray-800 text-base">{{ $scholar->bachelor_university ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Research Information -->
                <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Research Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-[#4A90E2] mb-2">Research Title</h4>
                            <p class="text-gray-800 text-base">{{ $scholar->research_title ?? 'Not provided' }}</p>
                        </div>

                        @if($scholar->enrollment_type || $scholar->study_time || $scholar->scholarship_duration)
                        <div>
                            <h4 class="text-sm font-medium text-[#4A90E2] mb-3">Program Details</h4>
                            <div class="space-y-3">
                                @if($scholar->enrollment_type)
                                <div class="flex items-center">
                                    <i class="fas fa-user-graduate text-[#4A90E2] w-6"></i>
                                    <span class="text-gray-700 ml-3 text-base">Enrollment: {{ $scholar->enrollment_type }}</span>
                                </div>
                                @endif
                                @if($scholar->study_time)
                                <div class="flex items-center">
                                    <i class="fas fa-clock text-[#4A90E2] w-6"></i>
                                    <span class="text-gray-700 ml-3 text-base">Study Time: {{ $scholar->study_time }}</span>
                                </div>
                                @endif
                                @if($scholar->scholarship_duration)
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt text-[#4A90E2] w-6"></i>
                                    <span class="text-gray-700 ml-3 text-base">Duration: {{ $scholar->scholarship_duration }} months</span>
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

@if(session('status'))
<div x-data="{ show: true }"
     x-show="show"
     x-init="setTimeout(() => show = false, 3000)"
     class="fixed bottom-4 right-4 bg-[#4CAF50] text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2">
    <i class="fas fa-check-circle"></i>
    <span>{{ session('status') }}</span>
</div>
@endif

@endsection
