@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Scholar Profile</h1>
                    <p class="text-gray-600 mt-1">Manage and view your academic information</p>
                </div>
                <a href="{{ route('scholar.profile.edit') }}" 
                   class="inline-flex items-center px-6 py-3 bg-[#2E7D32] text-white font-medium rounded-lg 
                          hover:bg-[#1B5E20] transition-all duration-200 shadow-md hover:shadow-lg 
                          transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-[#2E7D32] focus:ring-offset-2">
                    <i class="fas fa-edit mr-2"></i>
                    <span>Edit Profile</span>
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">

        <!-- Profile Overview Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8 overflow-hidden">
            <div class="bg-gradient-to-r from-[#2E7D32] to-[#4CAF50] px-8 py-6">
                <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                    <!-- Profile Photo -->
                    <div class="relative">
                        <div class="w-32 h-32 rounded-full bg-white/20 backdrop-blur-sm border-4 border-white/30 overflow-hidden shadow-xl">
                            @if($scholar->profile_photo)
                                <img src="{{ asset('images/' . $scholar->profile_photo) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-user text-5xl text-white/70"></i>
                                </div>
                            @endif
                        </div>
                        <!-- Status Badge -->
                        <div class="absolute -bottom-2 -right-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                @if($scholar->status == 'Active') bg-green-100 text-green-800 border-2 border-green-200
                                @elseif($scholar->status == 'Inactive') bg-red-100 text-red-800 border-2 border-red-200
                                @elseif($scholar->status == 'Completed') bg-blue-100 text-blue-800 border-2 border-blue-200
                                @else bg-yellow-100 text-yellow-800 border-2 border-yellow-200 @endif">
                                <i class="fas fa-circle text-xs mr-1"></i>
                                {{ $scholar->status }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Basic Info -->
                    <div class="text-center md:text-left flex-1">
                        <h2 class="text-3xl font-bold text-white mb-2">
                            {{ $scholar->full_name }}
                        </h2>
                        <p class="text-xl text-white/90 mb-1">{{ $user->email }}</p>
                        <p class="text-lg text-white/80 mb-1">{{ $scholar->birth_date ? \Carbon\Carbon::parse($scholar->birth_date)->format('F j, Y') : 'Not specified' }}</p>
                        <p class="text-lg text-white/80 mb-1">{{ $scholar->academic_level ?? 'Academic Level Not Set' }}</p>
                        <p class="text-lg text-white/80 mb-4">{{ $scholar->gender ?? 'Not specified' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabbed Interface -->
        <div class="bg-white rounded-lg shadow border border-gray-200">
            <div class="p-6">
                <!-- Overview & Contact -->
                <div class="mb-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Scholar Details -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold mb-4">Scholar Details</h3>
                            
                            <div class="space-y-3">
                                <div class="border-b pb-2">
                                    <label class="text-sm font-medium text-gray-600">Intended University</label>
                                    <p class="text-gray-900">{{ $scholar->intended_university ?? 'Not specified' }}</p>
                                </div>
                                
                                <div class="border-b pb-2">
                                    <label class="text-sm font-medium text-gray-600">Intended Degree</label>
                                    <p class="text-gray-900">{{ $scholar->intended_degree ?? 'Not specified' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contact Information -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold mb-4">Contact Information</h3>
                            
                            <div class="space-y-3">
                                <div class="border-b pb-2">
                                    <label class="text-sm font-medium text-gray-600">Email Address</label>
                                    <p class="text-gray-900">{{ $user->email }}</p>
                                </div>
                                
                                @if($scholar->contact_number)
                                    <div class="border-b pb-2">
                                        <label class="text-sm font-medium text-gray-600">Phone Number</label>
                                        <p class="text-gray-900">{{ $scholar->contact_number }}</p>
                                    </div>
                                @endif
                                
                                @if($scholar->formatted_address)
                                    <div class="border-b pb-2">
                                        <label class="text-sm font-medium text-gray-600">Address</label>
                                        <p class="text-gray-900">{{ $scholar->formatted_address }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Information & Scholarship Timeline -->
                <div class="mb-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Academic Information -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold mb-4">Academic Information</h3>
                            
                            <div class="space-y-3">
                                <div class="border-b pb-2">
                                    <label class="text-sm font-medium text-gray-600">Department</label>
                                    <p class="text-gray-900">{{ $scholar->department ?? 'Not specified' }}</p>
                                </div>
                                
                                <div class="border-b pb-2">
                                    <label class="text-sm font-medium text-gray-600">Major/Specialization</label>
                                    <p class="text-gray-900">{{ $scholar->major ?? 'Not specified' }}</p>
                                </div>
                                
                                @if($scholar->entry_type)
                                    <div class="border-b pb-2">
                                        <label class="text-sm font-medium text-gray-600">Entry Type</label>
                                        <p class="text-gray-900">{{ $scholar->entry_type_display }}</p>
                                    </div>
                                @endif
                                
                                <div class="border-b pb-2">
                                    <label class="text-sm font-medium text-gray-600">University Graduated</label>
                                    <p class="text-gray-900">{{ $scholar->university_graduated ?? 'Not specified' }}</p>
                                </div>
                                
                                @if($scholar->course_completed)
                                    <div class="border-b pb-2">
                                        <label class="text-sm font-medium text-gray-600">Course Completed</label>
                                        <p class="text-gray-900">{{ $scholar->course_completed }}</p>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Academic Progress -->
                            @if($scholar->units_required || $scholar->units_earned_prior || $scholar->intended_degree)
                            <div class="mt-8">
                                <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Academic Progress</h4>
                                
                                @if($scholar->intended_degree)
                                    <div class="border-b pb-2">
                                        <label class="text-sm font-medium text-gray-600">Intended Degree</label>
                                        <p class="text-gray-900">{{ $scholar->intended_degree }}</p>
                                    </div>
                                @endif
                                
                                @if($scholar->units_required)
                                    <div class="border-b pb-2">
                                        <label class="text-sm font-medium text-gray-600">Units Required</label>
                                        <p class="text-gray-900">{{ $scholar->units_required }}</p>
                                    </div>
                                @endif
                                
                                @if($scholar->units_earned_prior)
                                    <div class="border-b pb-2">
                                        <label class="text-sm font-medium text-gray-600">Units Earned Prior</label>
                                        <p class="text-gray-900">{{ $scholar->units_earned_prior }}</p>
                                    </div>
                                @endif
                            </div>
                            @endif
                            
                            <!-- Thesis/Dissertation -->
                            @if($scholar->thesis_dissertation_title)
                            <div class="mt-8">
                                <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Thesis/Dissertation</h4>
                                
                                <div class="space-y-4">
                                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                                        <label class="text-xs font-medium text-purple-600 uppercase tracking-wide">Thesis/Dissertation Title</label>
                                        <p class="text-gray-900 font-medium mt-1">{{ $scholar->thesis_dissertation_title }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Scholarship Timeline -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold mb-4">Scholarship Timeline</h3>
                            
                            <div class="space-y-3">
                                @if($scholar->scholarship_type)
                                    <div class="border-b pb-2">
                                        <label class="text-sm font-medium text-gray-600">Scholarship Type</label>
                                        <p class="text-gray-900">{{ $scholar->scholarship_type }}</p>
                                    </div>
                                @endif
                                
                                @if($scholar->funding_source)
                                    <div class="border-b pb-2">
                                        <label class="text-sm font-medium text-gray-600">Funding Source</label>
                                        <p class="text-gray-900">{{ $scholar->funding_source }}</p>
                                    </div>
                                @endif
                                
                                @if($scholar->start_date)
                                    <div class="border-b pb-2">
                                        <label class="text-sm font-medium text-gray-600">Start Date</label>
                                        <p class="text-gray-900">{{ $scholar->start_date ? \Carbon\Carbon::parse($scholar->start_date)->format('F j, Y') : 'Not specified' }}</p>
                                    </div>
                                @endif
                                
                                @if($scholar->end_date)
                                    <div class="border-b pb-2">
                                        <label class="text-sm font-medium text-gray-600">End Date</label>
                                        <p class="text-gray-900">{{ $scholar->end_date ? \Carbon\Carbon::parse($scholar->end_date)->format('F j, Y') : 'Not specified' }}</p>
                                    </div>
                                @endif
                                
                                @if($scholar->actual_completion_date)
                                    <div class="border-b pb-2">
                                        <label class="text-sm font-medium text-gray-600">Actual Completion</label>
                                        <p class="text-gray-900">{{ \Carbon\Carbon::parse($scholar->actual_completion_date)->format('M Y') }}</p>
                                    </div>
                                @endif
                                
                                @if($scholar->enrollment_type)
                                    <div class="border-b pb-2">
                                        <label class="text-sm font-medium text-gray-600">Enrollment Type</label>
                                        <p class="text-gray-900">{{ $scholar->enrollment_type }}</p>
                                    </div>
                                @endif
                                
                                @if($scholar->study_time)
                                    <div class="border-b pb-2">
                                        <label class="text-sm font-medium text-gray-600">Study Time</label>
                                        <p class="text-gray-900">{{ $scholar->study_time }}</p>
                                    </div>
                                @endif
                                
                                @if($scholar->scholarship_duration)
                                    <div class="border-b pb-2">
                                        <label class="text-sm font-medium text-gray-600">Scholarship Duration</label>
                                        <p class="text-gray-900">{{ $scholar->scholarship_duration }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                @if($scholar->notes)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">Additional Notes</h3>
                    <div class="border-b pb-2">
                        <p class="text-gray-900">{{ $scholar->notes }}</p>
                    </div>
                </div>
                @endif
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
