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
                   class="inline-flex items-center px-6 py-3 bg-[rgb(46_125_50)] text-white font-medium rounded-lg 
                          hover:bg-[rgb(27_94_32)] transition-all duration-200 shadow-md hover:shadow-lg 
                          transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-[rgb(46_125_50)] focus:ring-offset-2">
                    <i class="fas fa-edit mr-2"></i>
                    <span>Edit Profile</span>
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">

        <!-- Profile Overview Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-8 overflow-hidden">
            <div class="p-8">
                <!-- Scholar Information -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 mb-1">
                                {{ $scholar->full_name }}
                            </h1>
                            <p class="text-gray-600">{{ $user->email }}</p>
                        </div>
                        <!-- Status Badge -->
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                            @if($scholar->status == 'Active') bg-green-600 text-white
                            @elseif($scholar->status == 'Inactive') bg-red-50 text-red-700 ring-1 ring-red-200
                            @elseif($scholar->status == 'Completed') bg-blue-50 text-blue-700 ring-1 ring-blue-200
                            @else bg-green-50 text-green-700 ring-1 ring-green-200 @endif">
                            <div class="w-2 h-2 rounded-full mr-2
                                @if($scholar->status == 'Active') bg-white
                                @elseif($scholar->status == 'Inactive') bg-red-500
                                @elseif($scholar->status == 'Completed') bg-blue-500
                                @else bg-green-500 @endif"></div>
                            {{ $scholar->status }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-400 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Birth Date</p>
                                <p class="font-medium text-gray-900">{{ $scholar->birth_date ? \Carbon\Carbon::parse($scholar->birth_date)->format('M j, Y') : 'Not specified' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-purple-400 rounded-lg flex items-center justify-center">
                                <i class="fas fa-graduation-cap text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Academic Level</p>
                                <p class="font-medium text-gray-900">{{ $scholar->level ?? 'Not Set' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-400 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Gender</p>
                                <p class="font-medium text-gray-900">{{ $scholar->gender ?? 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabbed Interface -->
        <div class="bg-white rounded-lg shadow border border-gray-200">
            <div class="p-6">
                <!-- Personal & Contact Information -->
                <div class="mb-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Academic Information -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold mb-4">Academic Information</h3>
                            
                            <div class="space-y-3">
                                <div class="border-b pb-2">
                                    <label class="text-sm font-medium text-gray-600">Scholar ID</label>
                                    <p class="text-gray-900">{{ $scholar->id ?? 'Not assigned' }}</p>
                                </div>
                                
                                <div class="border-b pb-2">
                                    <label class="text-sm font-medium text-gray-600">Academic Level</label>
                                    <p class="text-gray-900">{{ $scholar->level ?? 'Not specified' }}</p>
                                </div>
                                
                                <div class="border-b pb-2">
                                    <label class="text-sm font-medium text-gray-600">Research Field</label>
                                    <p class="text-gray-900">{{ $scholar->research_field ?? 'Not specified' }}</p>
                                </div>
                                
                                <div class="border-b pb-2">
                                    <label class="text-sm font-medium text-gray-600">Intended University</label>
                                    <p class="text-gray-900">{{ $scholar->intended_university ?? 'Not specified' }}</p>
                                </div>
                                
                                <div class="border-b pb-2">
                                    <label class="text-sm font-medium text-gray-600">Enrollment Date</label>
                                    <p class="text-gray-900">{{ $scholar->created_at ? $scholar->created_at->format('F j, Y') : 'Not available' }}</p>
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
                                
                                @if($scholar->street || $scholar->village || $scholar->town || $scholar->district || $scholar->region || $scholar->province || $scholar->zipcode || $scholar->country)
                                    <div class="border-b pb-2">
                                        <label class="text-sm font-medium text-gray-600">Address</label>
                                        <div class="text-gray-900 space-y-1">
                                            @if($scholar->street)
                                                <p><span class="font-medium">Street:</span> {{ $scholar->street }}</p>
                                            @endif
                                            @if($scholar->village)
                                                <p><span class="font-medium">Village/Barangay:</span> {{ $scholar->village }}</p>
                                            @endif
                                            @if($scholar->town)
                                                <p><span class="font-medium">Town/City:</span> {{ $scholar->town }}</p>
                                            @endif
                                            @if($scholar->district)
                                                <p><span class="font-medium">District:</span> {{ $scholar->district }}</p>
                                            @endif
                                            @if($scholar->region)
                                                <p><span class="font-medium">Region:</span> {{ $scholar->region }}</p>
                                            @endif
                                            @if($scholar->province)
                                                <p><span class="font-medium">Province:</span> {{ $scholar->province }}</p>
                                            @endif
                                            @if($scholar->zipcode)
                                                <p><span class="font-medium">Zip Code:</span> {{ $scholar->zipcode }}</p>
                                            @endif
                                            @if($scholar->country)
                                                <p><span class="font-medium">Country:</span> {{ $scholar->country }}</p>
                                            @endif
                                        </div>
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
     class="fixed bottom-4 right-4 bg-primary-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2">
    <i class="fas fa-check-circle"></i>
    <span>{{ session('status') }}</span>
</div>
@endif



@endsection
