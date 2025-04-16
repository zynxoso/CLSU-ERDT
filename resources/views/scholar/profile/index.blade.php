@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class=" min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">My Profile</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Summary -->
            <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                <div class="p-6 text-center border-b border-gray-200">
                    <div class="w-24 h-24 rounded-full bg-gray-100 mx-auto mb-4 overflow-hidden">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-user text-3xl text-gray-500"></i>
                            </div>
                        @endif
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
                                <span class="text-gray-700 ml-2">{{ $scholar->phone ?? 'Not provided' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-gray-500 w-5"></i>
                                <span class="text-gray-700 ml-2">{{ $scholar->address ?? 'Not provided' }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Scholarship Details</h3>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt text-gray-500 w-5"></i>
                                <span class="text-gray-700 ml-2">Started: {{ $scholar->start_date ? date('M d, Y', strtotime($scholar->start_date)) : 'Not provided' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-graduation-cap text-gray-500 w-5"></i>
                                <span class="text-gray-700 ml-2">Expected Completion: {{ $scholar->expected_completion_date ? date('M d, Y', strtotime($scholar->expected_completion_date)) : 'Not provided' }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-user-tie text-gray-500 w-5"></i>
                                <span class="text-gray-700 ml-2">Advisor: {{ $scholar->advisor ?? 'Not assigned' }}</span>
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
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Major</h4>
                                <p class="text-gray-800">{{ $scholar->major ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Student ID</h4>
                                <p class="text-gray-800">{{ $scholar->student_id ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Current GPA</h4>
                                <p class="text-gray-800">{{ $scholar->gpa ?? 'Not provided' }}</p>
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
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Research Area</h4>
                            <p class="text-gray-800">{{ $scholar->research_area ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Research Abstract</h4>
                            <p class="text-gray-700">{{ $scholar->research_abstract ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Scholarship Progress -->
                <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                    <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Scholarship Progress</h3>
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Overall Progress</h4>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $scholarProgress }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-600">
                                <span>{{ $scholarProgress }}% Complete</span>
                                <span>{{ $daysRemaining }} days remaining</span>
                            </div>
                        </div>

                        <div class="space-y-4 mt-6">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <h4 class="text-sm font-medium text-gray-700">Coursework</h4>
                                    <span class="text-xs text-gray-600">{{ $courseworkProgress }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $courseworkProgress }}%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between mb-1">
                                    <h4 class="text-sm font-medium text-gray-700">Research</h4>
                                    <span class="text-xs text-gray-600">{{ $researchProgress }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection