@extends('layouts.app')
@section('title', 'Create Fund Request')
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
        .currency-input {font-family: theme(fontFamily.sans);font-variant-numeric: tabular-nums;}
    </style>
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto">
            <!-- Header with Branding -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Create New Fund Request</h1>
                        <p class="text-gray-600 mt-1">Administrator Panel</p>
                    </div>
                    <a href="{{ route('admin.fund-requests.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg flex items-center space-x-2">
                        <i class="fas fa-times"></i>
                        <span>Cancel</span>
                    </a>
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
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('admin.fund-requests.index') }}";
                        }
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
                <!-- Important Information Box -->
                <div class="mb-5 p-4 bg-info-50 border border-info-300 rounded-lg">
                    <h3 class="text-lg font-semibold text-secondary-500 mb-2 flex items-center">
                        <i class="fas fa-info-circle mr-2 " style="color: #4A90E2;"></i>
                        Administrator Information
                    </h3>
                    <div class="space-y-2 text-info-700">
                        <p class="text-base">You are creating a fund request as an administrator.</p>
                        <p class="text-base">• You can only submit <strong>one request type at a time</strong></p>
                        <p class="text-base">• Duplicate active requests of the same type are not allowed</p>
                        <p class="text-base">• All validation rules apply to admin-created requests</p>
                    </div>
                </div>

                <form action="{{ route('admin.fund-requests.store') }}" method="POST" enctype="multipart/form-data" id="admin-fund-request-form">
                    @csrf
                    <input type="hidden" name="status" value="Submitted">

                    <!-- Request Details -->
                    <div class="mb-6">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 rounded-full bg-primary-500 text-white flex items-center justify-center text-lg font-semibold">1</div>
                            <h2 class="text-xl font-semibold text-gray-800 ml-3">Request Details</h2>
                        </div>

                        <div class="mb-6">
                            <label for="request_type_id" class="block text-base font-medium text-gray-700 mb-2">
                                Request Type <span class="text-red-500">*</span>
                                <span class="ml-2 text-gray-400 cursor-help" title="Select the type of financial assistance being requested.">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </label>
                            <div class="relative">
                                <select id="request_type_id" name="request_type_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 pr-10 text-base focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 bg-white appearance-none" required>
                                    <option value="">Select Request Type</option>
                                    @foreach($requestTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('request_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="request_type_id-error" class="text-red-500 text-sm mt-1 hidden flex items-center">
                                <i class="fas fa-times-circle mr-2"></i>
                                <span></span>
                            </div>
                            <div id="request_type_id-warning" class="text-yellow-600 text-sm mt-1 hidden flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span></span>
                            </div>
                            @error('request_type_id')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-times-circle mr-2"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="amount" class="block text-base font-medium text-gray-700 mb-2">
                                Amount (₱) <span class="text-red-500">*</span>
                                <span class="ml-2 text-gray-400 cursor-help" title="Enter the requested amount.">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </label>
                            <div class="inline-flex justify-between bg-gray-100 rounded border border-gray-200 w-full hover:border-primary-500 focus-within:border-primary-500 focus-within:ring-2 focus-within:ring-[#4CAF50]/20 transition-all duration-200">
                                <div class="inline bg-gray-200 py-2 px-4 text-gray-600 select-none rounded-l">₱</div>
                                <input type="text" id="amount" name="amount" value="{{ old('amount') }}" required
                                    class="currency-input bg-transparent py-2 text-gray-600 px-4 focus:outline-none w-full text-base"
                                    placeholder="10,000">
                            </div>
                            <div id="amount-error" class="text-red-500 text-sm mt-1 hidden flex items-center">
                                <i class="fas fa-times-circle mr-2"></i>
                                <span></span>
                            </div>
                            <div id="amount-warning" class="text-yellow-600 text-sm mt-1 hidden flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span></span>
                            </div>
                            @error('amount')
                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                    <i class="fas fa-times-circle mr-2"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-base font-medium text-gray-700 mb-2">
                                Description <span class="text-red-500">*</span>
                            </label>
                            <textarea id="description" name="description" rows="4" required
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200"
                                    placeholder="Provide details about this fund request...">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.fund-requests.index') }}" class="px-6 py-2.5 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg">
                            Cancel
                        </a>
                        <button type="submit" id="submit-button" class="px-6 py-2.5 bg-primary-500 text-white rounded-lg hover:bg-[#45a049] transition-all duration-200 shadow-md hover:shadow-lg flex items-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-save"></i>
                            <span>Create Request</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('components.fund-request-validation-modal')

    <script>
        document.getElementById('amount').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            if (value) {
                value = parseInt(value).toLocaleString();
            }
            e.target.value = value;
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize validation for admin form
            if (typeof FundRequestValidator !== 'undefined') {
                window.adminFundRequestValidator = new FundRequestValidator('admin-fund-request-form');
                
                // Override some settings for admin
                window.adminFundRequestValidator.validationRules.roleBasedValidation = true;
                window.adminFundRequestValidator.validationRules.requiredFields = ['request_type_id', 'amount', 'description'];
            }
        });
    </script>
@endsection
