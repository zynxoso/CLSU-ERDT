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
    /* Currency input styling */
    .currency-input {
        font-family: theme(fontFamily.sans);
        font-variant-numeric: tabular-nums;
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
                    confirmButtonColor: 'rgb(34 197 94)'
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
                    confirmButtonColor: 'rgb(239 68 68)'
                });
            });
        </script>
        @endif

        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <!-- Status Information Box -->
            <div class="mb-5 p-4 bg-info-50 border border-info-300 rounded-lg">
                <h3 class="text-lg font-semibold text-secondary-500 mb-2 flex items-center">
                    <i class="fas fa-info-circle mr-2" style="color: #4A90E2;"></i>
                    Request Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-info-700">
                    <div>
                        <p class="text-sm font-medium">Current Status:</p>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                            @if($fundRequest->status === 'Submitted') style="background-color: rgba(74, 144, 226, 0.1); color: #4A90E2;"
                            @elseif($fundRequest->status === 'Under Review') style="background-color: rgba(255, 202, 40, 0.1); color: rgb(251 191 36);"
                            @elseif($fundRequest->status === 'Approved') style="background-color: rgba(76, 175, 80, 0.1); color: rgb(34 197 94);"
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
                        <div class="w-8 h-8 rounded-full bg-primary-500 text-white flex items-center justify-center text-lg font-semibold">1</div>
                        <h2 class="text-xl font-semibold text-gray-800 ml-3">Scholar Information</h2>
                    </div>

                    <div class="mb-6">
                        <label for="scholar_profile_id" class="block text-base font-medium text-gray-700 mb-2">
                            Scholar <span class="text-red-500">*</span>
                        </label>
                        <select id="scholar_profile_id" name="scholar_profile_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 pr-10 text-base focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 bg-white appearance-none" required>
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
                        <div class="w-8 h-8 rounded-full bg-primary-500 text-white flex items-center justify-center text-lg font-semibold">2</div>
                        <h2 class="text-xl font-semibold text-gray-800 ml-3">Request Details</h2>
                    </div>

                    <div class="mb-6">
                        <label for="request_type_id" class="block text-base font-medium text-gray-700 mb-2">
                            Request Type <span class="text-red-500">*</span>
                        </label>
                        <select id="request_type_id" name="request_type_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 pr-10 text-base focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 bg-white appearance-none" required>
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
                        <div class="inline-flex justify-between bg-gray-100 rounded border border-gray-200 w-full hover:border-primary-500 focus-within:border-primary-500 focus-within:ring-2 focus-within:ring-[#4CAF50]/20 transition-all duration-200">
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
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
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
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
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
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200">
                        @error('expected_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="notes" class="block text-base font-medium text-gray-700 mb-2">
                            Admin Notes
                        </label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
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
                    <button type="submit" id="submit-button" class="px-6 py-2.5 bg-primary-500 text-white rounded-lg hover:bg-[#45a049] transition-all duration-200 shadow-md hover:shadow-lg flex items-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
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


</script>
@endsection
