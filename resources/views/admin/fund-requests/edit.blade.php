@extends('layouts.app')

@section('title', 'Edit Fund Request')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/sweetalert2.min.js') }}"></script>
<script src="{{ asset('js/fund-request-validation.js') }}"></script>
<script src="{{ asset('js/admin-fund-request-validation.js') }}"></script>
@endpush

@section('content')
<style>
    /* Enhanced validation states */
    .validation-success {
        border-color: #4CAF50 !important;
        box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
    }
    
    .validation-warning {
        border-color: #F59E0B !important;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }
    
    .validation-error {
        border-color: #EF4444 !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
    
    /* Currency input styling */
    .currency-input {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-variant-numeric: tabular-nums;
    }
    
    /* Smooth transitions for all interactive elements */
    input, select, textarea {
        transition: all 0.2s ease-in-out;
    }
    
    /* Enhanced focus states */
    input:focus, select:focus, textarea:focus {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.15);
    }
</style>
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto">
        <!-- Header with Branding -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Edit Fund Request</h1>
                    <p class="text-gray-600 mt-1">Administrator Panel - Request #{{ $fundRequest->id }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.fund-requests.show', $fundRequest->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg flex items-center space-x-2">
                        <i class="fas fa-eye"></i>
                        <span>View</span>
                    </a>
                    <a href="{{ route('admin.fund-requests.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all duration-200 shadow-md hover:shadow-lg flex items-center space-x-2">
                        <i class="fas fa-times"></i>
                        <span>Cancel</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Display success message using SweetAlert2 -->
        @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#4CAF50'
                });
            });
        </script>
        @endif

        <!-- Display error message using SweetAlert2 -->
        @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#EF4444'
                });
            });
        </script>
        @endif

        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <!-- Status Information Box -->
            <div class="mb-5 p-4 bg-[#E3F2FD] border border-[#90CAF9] rounded-lg">
                <h3 class="text-lg font-semibold text-[#4A90E2] mb-2 flex items-center">
                    <i class="fas fa-info-circle mr-2" style="color: #4A90E2;"></i>
                    Request Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-[#1565C0]">
                    <div>
                        <p class="text-sm font-medium">Current Status:</p>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                            @if($fundRequest->status === 'Draft') style="background-color: #F5F5F5; color: #757575;"
                            @elseif($fundRequest->status === 'Submitted') style="background-color: rgba(74, 144, 226, 0.1); color: #4A90E2;"
                            @elseif($fundRequest->status === 'Under Review') style="background-color: rgba(255, 202, 40, 0.1); color: #FFCA28;"
                            @elseif($fundRequest->status === 'Approved') style="background-color: rgba(76, 175, 80, 0.1); color: #4CAF50;"
                            @elseif($fundRequest->status === 'Rejected') style="background-color: rgba(211, 47, 47, 0.1); color: #D32F2F;"
                            @endif>
                            {{ $fundRequest->status }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-medium">Scholar:</p>
                        <p class="text-sm">{{ $fundRequest->scholarProfile->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium">Created:</p>
                        <p class="text-sm">{{ $fundRequest->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.fund-requests.update', $fundRequest->id) }}" method="POST" id="admin-fund-request-edit-form">
                @csrf
                @method('PUT')

                <!-- Scholar Selection -->
                <div class="mb-6">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 rounded-full bg-[#4CAF50] text-white flex items-center justify-center text-lg font-semibold">1</div>
                        <h2 class="text-xl font-semibold text-gray-800 ml-3">Scholar Information</h2>
                    </div>

                    <div class="mb-6">
                        <label for="scholar_profile_id" class="block text-base font-medium text-gray-700 mb-2">
                            Scholar <span class="text-red-500">*</span>
                        </label>
                        <select id="scholar_profile_id" name="scholar_profile_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 pr-10 text-base focus:outline-none focus:ring-2 focus:ring-[#4CAF50] focus:border-[#4CAF50] transition-colors duration-200 bg-white appearance-none" required>
                            <option value="">Select Scholar</option>
                            @foreach($scholars as $scholar)
                            <option value="{{ $scholar->id }}" {{ $fundRequest->scholar_profile_id == $scholar->id ? 'selected' : '' }}>
                                {{ $scholar->user->name }} ({{ $scholar->student_id }})
                            </option>
                            @endforeach
                        </select>
                        @error('scholar_profile_id')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-times-circle mr-2"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Request Details -->
                <div class="mb-6">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 rounded-full bg-[#4CAF50] text-white flex items-center justify-center text-lg font-semibold">2</div>
                        <h2 class="text-xl font-semibold text-gray-800 ml-3">Request Details</h2>
                    </div>

                    <div class="mb-6">
                        <label for="request_type_id" class="block text-base font-medium text-gray-700 mb-2">
                            Request Type <span class="text-red-500">*</span>
                        </label>
                        <select id="request_type_id" name="request_type_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 pr-10 text-base focus:outline-none focus:ring-2 focus:ring-[#4CAF50] focus:border-[#4CAF50] transition-colors duration-200 bg-white appearance-none" required>
                            <option value="">Select Request Type</option>
                            @foreach($requestTypes as $type)
                            <option value="{{ $type->id }}" {{ $fundRequest->request_type_id == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('request_type_id')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-times-circle mr-2"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="amount" class="block text-base font-medium text-gray-700 mb-2">
                            Amount (₱) <span class="text-red-500">*</span>
                        </label>
                        <div class="inline-flex justify-between bg-gray-100 rounded border border-gray-200 w-full hover:border-[#4CAF50] focus-within:border-[#4CAF50] focus-within:ring-2 focus-within:ring-[#4CAF50]/20 transition-all duration-200">
                            <div class="inline bg-gray-200 py-2 px-4 text-gray-600 select-none rounded-l">₱</div>
                            <input type="text" id="amount" name="amount" value="{{ number_format($fundRequest->amount, 0) }}" required
                                   class="currency-input bg-transparent py-2 text-gray-600 px-4 focus:outline-none w-full text-base"
                                   placeholder="10,000">
                        </div>
                        @error('amount')
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i class="fas fa-times-circle mr-2"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="purpose" class="block text-base font-medium text-gray-700 mb-2">
                            Purpose <span class="text-red-500">*</span>
                        </label>
                        <textarea id="purpose" name="purpose" rows="4" required
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-[#4CAF50] focus:border-[#4CAF50] transition-colors duration-200"
                                  placeholder="Provide details about this fund request...">{{ old('purpose', $fundRequest->purpose) }}</textarea>
                        @error('purpose')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="justification" class="block text-base font-medium text-gray-700 mb-2">
                            Justification
                        </label>
                        <textarea id="justification" name="justification" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-[#4CAF50] focus:border-[#4CAF50] transition-colors duration-200"
                                  placeholder="Additional justification for this request...">{{ old('justification', $fundRequest->justification) }}</textarea>
                        @error('justification')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="expected_date" class="block text-base font-medium text-gray-700 mb-2">
                            Expected Date
                        </label>
                        <input type="date" id="expected_date" name="expected_date" 
                               value="{{ old('expected_date', $fundRequest->expected_date ? $fundRequest->expected_date->format('Y-m-d') : '') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-[#4CAF50] focus:border-[#4CAF50] transition-colors duration-200">
                        @error('expected_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="notes" class="block text-base font-medium text-gray-700 mb-2">
                            Admin Notes
                        </label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-[#4CAF50] focus:border-[#4CAF50] transition-colors duration-200"
                                  placeholder="Internal notes for this request...">{{ old('notes', $fundRequest->notes) }}</textarea>
                        @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.fund-requests.show', $fundRequest->id) }}" class="px-6 py-2.5 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg">
                        Cancel
                    </a>
                    <button type="submit" id="submit-button" class="px-6 py-2.5 bg-[#4CAF50] text-white rounded-lg hover:bg-[#45a049] transition-all duration-200 shadow-md hover:shadow-lg flex items-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-save"></i>
                        <span>Update Request</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Currency formatting
document.getElementById('amount').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^\d]/g, '');
    if (value) {
        value = parseInt(value).toLocaleString();
    }
    e.target.value = value;
});

// Enhanced form validation
document.getElementById('admin-fund-request-edit-form').addEventListener('submit', function(e) {
    const amount = document.getElementById('amount').value;
    const purpose = document.getElementById('purpose').value;
    const requestType = document.getElementById('request_type_id').value;
    const scholarId = document.getElementById('scholar_profile_id').value;
    
    // Basic validation
    if (!amount || !purpose || !requestType || !scholarId) {
        e.preventDefault();
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Validation Error',
                text: 'Please fill in all required fields.',
                icon: 'error',
                confirmButtonColor: '#EF4444'
            });
        } else {
            alert('Please fill in all required fields.');
        }
        return false;
    }
});
</script>
@endsection