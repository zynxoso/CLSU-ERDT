@extends('layouts.app')

@section('title', 'Edit Scholar')

@section('styles')
<style>
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-4px); }
    }

    .animate-pulse {
        animation: pulse 1s infinite;
    }

    .animate-bounce {
        animation: bounce 1s infinite;
    }

    .btn-transition {
        transition: all 0.3s ease;
    }

    .btn-transition:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    /* Ensure all button icons have white color */
    a.bg-yellow-600 i,
    a.bg-gray-600 i,
    a.bg-blue-500 i,
    button.bg-blue-500 i {
        color: white !important;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <div class="flex space-x-6 mb-4">
                <a href="{{ route('admin.scholars.edit', $scholar->id) }}"
                   class="group w-44 px-5 py-2.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 shadow-sm flex items-center justify-center transition-all duration-300 transform hover:scale-105 hover:shadow-md btn-transition">
                    <i class="fas fa-edit mr-2 text-white group-hover:animate-pulse"></i>
                    <span>Edit Scholar</span>
                </a>
                <a href="{{ route('admin.scholars.index') }}"
                   class="group w-44 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 shadow-sm flex items-center justify-center border border-gray-200 transition-all duration-300 transform hover:scale-105 hover:shadow-md btn-transition">
                    <i class="fas fa-arrow-left mr-2 group-hover:translate-x-[-3px] transition-transform duration-300"></i>
                    <span>Back to List</span>
                </a>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">Edit Scholar: {{ $scholar->first_name }} {{ $scholar->last_name }}</h1>
            <p class="text-gray-500 mt-1">ERDT PRISM: A Portal for a Responsive and Integrated Scholarship Management</p>
        </div>

        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <form action="{{ route('admin.scholars.update', $scholar->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $scholar->first_name) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('first_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $scholar->last_name) }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('last_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Scholarship Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="university" class="block text-sm font-medium text-gray-700 mb-1">University <span class="text-red-500">*</span></label>
                            <select id="university" name="university" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="Central Luzon State University" {{ old('university', $scholar->university) == 'Central Luzon State University' ? 'selected' : '' }}>Central Luzon State University (CLSU)</option>
                                <option value="University of the Philippines Los Baños" {{ old('university', $scholar->university) == 'University of the Philippines Los Baños' ? 'selected' : '' }}>University of the Philippines Los Baños</option>
                                <option value="Bulacan State University" {{ old('university', $scholar->university) == 'Bulacan State University' ? 'selected' : '' }}>Bulacan State University</option>
                                <option value="Nueva Ecija University of Science and Technology" {{ old('university', $scholar->university) == 'Nueva Ecija University of Science and Technology' ? 'selected' : '' }}>Nueva Ecija University of Science and Technology</option>
                                <option value="Other" {{ old('university', $scholar->university) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">CLSU - Central Luzon State University</p>
                            @error('university')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department <span class="text-red-500">*</span></label>
                            <select id="department" name="department" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="Engineering" {{ old('department', $scholar->department) == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                                <option value="Agricultural Engineering Department" {{ old('department', $scholar->department) == 'Agricultural Engineering Department' ? 'selected' : '' }}>Agricultural Engineering Department</option>
                                <option value="Department of Agricultural and Biosystems Engineering" {{ old('department', $scholar->department) == 'Department of Agricultural and Biosystems Engineering' ? 'selected' : '' }}>Department of Agricultural and Biosystems Engineering</option>
                                <option value="College of Engineering" {{ old('department', $scholar->department) == 'College of Engineering' ? 'selected' : '' }}>College of Engineering</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Department offering BSABE at CLSU</p>
                            @error('department')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="program" class="block text-sm font-medium text-gray-700 mb-1">Program <span class="text-red-500">*</span></label>
                            <select id="program" name="program" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="Master in Agricultural and Biosystems Engineering" {{ old('program', $scholar->program) == 'Master in Agricultural and Biosystems Engineering' ? 'selected' : '' }}>Master in Agricultural and Biosystems Engineering</option>
                                <option value="Master of Science in Agricultural and Biosystems Engineering" {{ old('program', $scholar->program) == 'Master of Science in Agricultural and Biosystems Engineering' ? 'selected' : '' }}>Master of Science in Agricultural and Biosystems Engineering</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">ABE Masteral Scholarship Program</p>
                            @error('program')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select id="status" name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="New" {{ old('status', $scholar->status) == 'New' ? 'selected' : '' }}>New</option>
                                <option value="Ongoing" {{ old('status', $scholar->status) == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="On Extension" {{ old('status', $scholar->status) == 'On Extension' ? 'selected' : '' }}>On Extension</option>
                                <option value="Graduated" {{ old('status', $scholar->status) == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                                <option value="Terminated" {{ old('status', $scholar->status) == 'Terminated' ? 'selected' : '' }}>Terminated</option>
                                <option value="Deferred Repayment" {{ old('status', $scholar->status) == 'Deferred Repayment' ? 'selected' : '' }}>Deferred Repayment</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date <span class="text-red-500">*</span></label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $scholar->start_date ? (is_string($scholar->start_date) ? $scholar->start_date : $scholar->start_date->format('Y-m-d')) : '') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('start_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="expected_completion_date" class="block text-sm font-medium text-gray-700 mb-1">Expected Completion Date <span class="text-red-500">*</span></label>
                            <input type="date" id="expected_completion_date" name="expected_completion_date" value="{{ old('expected_completion_date', $scholar->expected_completion_date ? (is_string($scholar->expected_completion_date) ? $scholar->expected_completion_date : $scholar->expected_completion_date->format('Y-m-d')) : '') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @error('expected_completion_date')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Additional Notes</h2>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea id="notes" name="notes" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes', $scholar->notes) }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Any additional information about the scholar. Special characters are allowed here.</p>
                        @error('notes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit"
                            class="group w-48 px-5 py-2.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 shadow-sm flex items-center justify-center transition-all duration-300 transform hover:scale-105 hover:shadow-md">
                        <i class="fas fa-save mr-2 text-white group-hover:animate-bounce"></i>
                        <span>Update Scholar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
