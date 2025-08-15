@extends('layouts.app')

@section('title', 'Create Fund Request')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/sweetalert2.min.js') }}"></script>
<script src="{{ asset('js/fund-request-validation.js') }}"></script>
@endpush

@section('content')
    <style>
        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .step-indicator {
            transition: all 0.3s ease;
        }

        .step-indicator.active {
            background-color: rgb(34 197 94);
            color: rgb(255 255 255);
        }

        .step-indicator.completed {
            background-color: rgb(34 197 94);
            color: rgb(255 255 255);
        }

        /* Enhanced validation states */
        .validation-success {
            border-color: rgb(34 197 94) !important;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }

        .validation-warning {
            border-color: rgb(245 158 11) !important;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        .validation-error {
            border-color: rgb(239 68 68) !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        /* Tooltip enhancements */
        [title]:hover {
            position: relative;
        }

        /* Currency input styling */
        .currency-input {
            font-family: theme(fontFamily.sans);
            font-variant-numeric: tabular-nums;
        }

        /* Smooth transitions for all interactive elements */
        input,
        select,
        textarea {
            transition: all 0.2s ease-in-out;
        }

        /* Enhanced focus states */
        input:focus,
        select:focus,
        textarea:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.15);
        }

        /* Loading state for validation */
        .validating {
            background-image: linear-gradient(45deg, transparent 25%, rgba(76, 175, 80, 0.1) 25%, rgba(76, 175, 80, 0.1) 50%, transparent 50%, transparent 75%, rgba(76, 175, 80, 0.1) 75%);
            background-size: 20px 20px;
            animation: loading-stripe 1s linear infinite;
        }

        @keyframes loading-stripe {
            0% {
                background-position: 0 0;
            }

            100% {
                background-position: 20px 0;
            }
        }
    </style>
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto">
            <!-- Header with Branding -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Create New Fund Request</h1>
                    </div>
                    <a href="{{ route('scholar.fund-requests.index') }}"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg flex items-center space-x-2">
                        <i class="fas fa-times"></i>
                        <span>Cancel</span>
                    </a>
                </div>
            </div>

            <!-- Display success message using SweetAlert2 -->
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        console.log('=== SUCCESS MESSAGE DEBUG ===');
                        console.log('Success message:', "{{ session('success') }}");

                        Swal.fire({
                            title: 'Success!',
                            text: "{{ session('success') }}",
                            icon: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: 'rgb(34 197 94)'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                console.log('Redirecting to fund requests index');
                                window.location.href = "{{ route('scholar.fund-requests.index') }}";
                            }
                        });
                    });
                </script>
            @endif

            <!-- Display error message using SweetAlert2 -->
            @if (session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        console.log('=== ERROR MESSAGE DEBUG ===');
                        console.log('Error message:', "{{ session('error') }}");

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

            <!-- Display validation errors -->
            @if ($errors->any())
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        console.log('=== VALIDATION ERRORS DEBUG ===');
                        const errors = @json($errors->all());
                        console.log('Validation errors:', errors);

                        let errorText = 'Please correct the following errors:\n\n';
                        errors.forEach((error, index) => {
                            errorText += `${index + 1}. ${error}\n`;
                        });

                        Swal.fire({
                            title: 'Validation Errors',
                            text: errorText,
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
                    <h3 class="text-lg font-semibold text-info-600 mb-2 flex items-center">
                        <i class="fas fa-info-circle mr-2 " style="color: rgb(29 78 216);"></i>
                        Important Information
                    </h3>
                    <div class="space-y-2 text-info-700">
                        <p class="text-base">Your fund request will be reviewed by an administrator after submission.</p>
                        <p class="text-base">Each request type has a maximum allowable amount based on your program level.
                        </p>
                    </div>
                </div>

                <form action="{{ route('scholar.fund-requests.store') }}" method="POST" enctype="multipart/form-data"
                    id="multi-step-form">
                    @csrf
                    <input type="hidden" name="status" value="Submitted">

                    <!-- Step 1: Request Details -->
                    <div id="step-1" class="step-content active">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-8 h-8 rounded-full bg-primary-500 text-white flex items-center justify-center text-lg font-semibold">
                                1</div>
                            <h2 class="text-xl font-semibold text-gray-800 ml-3">Request Details</h2>
                        </div>

                        <div class="mb-6">
                            <label for="request_type_id" class="block text-base font-medium text-gray-700 mb-2">
                                Request Type <span class="text-red-500">*</span>
                                <span class="ml-2 text-gray-400 cursor-help"
                                    title="Select the type of financial assistance you are requesting. Each type has specific entitlement limits and requirements based on your program level.">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </label>
                            <div class="relative">
                                <select id="request_type_id" name="request_type_id"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 pr-10 text-base focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 bg-white appearance-none"
                                    required>
                                    <option value="">Select Request Type</option>
                                    @foreach ($requestTypes as $type)
                                        <option value="{{ $type->id }}"
                                            {{ old('request_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}
                                        </option>
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
                            <!-- Request Type Info Display -->
                            <div id="request-type-info" class="text-xs text-blue-600 mt-2 font-medium h-auto hidden">
                                <i class="fas fa-info-circle mr-2"></i>
                                <span id="request-type-info-text"></span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="amount" class="block text-base font-medium text-gray-700 mb-2">
                                Amount (₱) <span class="text-red-500">*</span>
                                <span class="ml-2 text-gray-400 cursor-help"
                                    title="Enter the amount you are requesting. The system will validate against your program's entitlement limits and provide guidance on maximum allowable amounts.">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </label>
                            <div
                                class="inline-flex justify-between bg-gray-100 rounded border border-gray-200 w-full hover:border-primary-500 focus-within:border-primary-500 focus-within:ring-2 focus-within:ring-[rgb(76_175_80)]/20 transition-all duration-200">
                                <div class="inline bg-gray-200 py-2 px-4 text-gray-600 select-none rounded-l">₱</div>
                                <input type="text" id="amount" name="amount" value="{{ old('amount') }}" required
                                    class="currency-input bg-transparent py-2 text-gray-600 px-4 focus:outline-none w-full text-base"
                                    placeholder="10,000">

                            </div>
                            <div id="amount-limit-info"
                                class="text-sm text-info-600 mt-2 font-medium h-5 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                <span></span>
                            </div>
                            <div id="amount-warning"
                                class="text-sm text-warning-600 mt-2 font-medium h-5 hidden flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span id="amount-warning-text"></span>
                            </div>
                            <div id="amount-error"
                                class="text-sm text-red-500 mt-2 font-medium h-5 hidden flex items-center">
                                <i class="fas fa-times-circle mr-2"></i>
                                <span id="amount-error-text"></span>
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

                        <div class="flex justify-end">
                            <button type="button" id="next-step-1"
                                class="px-6 py-2.5 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center text-base">
                                Next: Documents <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Supporting Documents -->
                    <div id="step-2" class="step-content hidden">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-8 h-8 rounded-full bg-primary-500 text-white flex items-center justify-center text-lg font-semibold">
                                2</div>
                            <h2 class="text-xl font-semibold text-gray-800 ml-3">Supporting Documents</h2>
                        </div>

                        <!-- Document Requirements Display -->
                        <div id="document-requirements"
                            class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
                            <h4 class="text-base font-semibold text-blue-800 mb-2 flex items-center">
                                <i class="fas fa-file-alt mr-2"></i>
                                Required Supporting Documents
                            </h4>
                            <div id="requirements-list" class="text-sm text-blue-700">
                                <!-- Dynamic content will be inserted here -->
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="dropzone-file" class="block text-base font-medium text-gray-700 mb-2">
                                Supporting Documents <span class="text-gray-500">(Optional)</span>
                                <span class="ml-2 text-gray-400 cursor-help"
                                    title="Upload relevant documents such as registration forms, receipts, quotations, or other supporting materials. Only PDF files up to 5MB each are accepted. Maximum 5 files.">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </label>
                            <div class="flex items-center justify-center w-full">
                                <label for="dropzone-file"
                                    class="flex flex-col items-center justify-center w-full min-h-[8rem] sm:min-h-[12rem] md:min-h-[16rem] border-2 border-primary-500 border-dashed rounded-lg cursor-pointer bg-[rgb(232_245_233)] hover:bg-[rgb(200_230_201)] transition-colors duration-200 px-2 sm:px-6">
                                    <div class="flex flex-col items-center justify-center pt-4 sm:pt-5 pb-4 sm:pb-6 w-full">
                                        <svg class="w-10 h-10 sm:w-12 sm:h-12 mb-3 sm:mb-4 text-primary-500"
                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                        </svg>
                                        <p class="mb-2 text-sm sm:text-base text-primary-800 text-center"><span
                                                class="font-semibold">Click to upload</span> or drag and drop</p>
                                        <p class="text-xs sm:text-sm text-primary-700 text-center">PDF only (Max. 5MB each, up to 5 files)</p>
                                    </div>
                                    <input id="dropzone-file" name="documents[]" type="file" class="hidden"
                                        accept=".pdf" multiple>
                                </label>
                            </div>
                            <div id="selected-file-info" class="mt-4">
                                <div id="uploaded-files-header" class="hidden mb-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-medium text-gray-700">Uploaded Documents (<span id="file-count">0</span>/5)</span>
                                        <button type="button" id="clear-all-files" 
                                                class="text-red-500 hover:text-red-700 text-sm font-medium transition-colors duration-200">
                                            <i class="fas fa-trash mr-1"></i>Clear All
                                        </button>
                                    </div>
                                </div>
                                <div id="files-container"></div>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">Upload supporting documents like registration forms,
                                receipts, or other relevant files in PDF format only. You can upload up to 5 files.</p>
                        </div>

                        <!-- Data Privacy Agreement -->
                        <div class="mb-6 p-4 bg-info-50 border border-info-300 rounded-lg">
                            <div class="flex items-start">
                                <input type="checkbox" id="privacy-agreement-checkbox" name="privacy_agreement"
                                    class="mt-1 mr-3 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500"
                                    required>
                                <div class="flex-1">
                                    <label for="privacy-agreement-checkbox" class="text-base text-info-700">
                                        I agree to the <button type="button" id="open-privacy-modal"
                                            class="text-info-600 hover:text-info-700 underline transition-colors duration-150 font-medium">Data
                                            Privacy Agreement</button> for document uploads.
                                    </label>
                                    <div id="privacy-agreement-error" class="text-red-500 text-sm mt-1 hidden">
                                        You must agree to the Data Privacy Agreement to upload documents.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" id="prev-step-2"
                                class="px-6 py-2.5 bg-secondary-500 text-white rounded-lg hover:bg-secondary-600 transition-all duration-200 shadow-md hover:shadow-lg flex items-center text-base">
                                <i class="fas fa-arrow-left mr-2"></i> Previous
                            </button>
                            <button type="button" id="next-step-2"
                                class="px-6 py-2.5 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center text-base">
                                Next: Review <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Review & Submit -->
                    <div id="step-3" class="step-content hidden">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-8 h-8 rounded-full bg-primary-500 text-white flex items-center justify-center text-lg font-semibold">
                                3</div>
                            <h2 class="text-xl font-semibold text-gray-800 ml-3">Review & Submit</h2>
                        </div>

                        <div class="bg-neutral-100 rounded-lg p-6 mb-6 border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Review Your Request</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-base font-medium text-gray-600">Request Type:</span>
                                    <span id="review-request-type" class="text-base text-gray-800 font-semibold"></span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-base font-medium text-gray-600">Amount:</span>
                                    <div class="text-right">
                                        <span id="review-amount" class="text-base text-gray-800 font-semibold"></span>
                                        <span id="review-amount-max" class="text-sm text-primary-500 block"></span>
                                    </div>
                                </div>
                                <div class="py-2">
                                    <span class="text-base font-medium text-gray-600">Supporting Documents:</span>
                                    <div id="review-documents" class="mt-2 text-base text-gray-800">
                                        <div id="review-documents-list" class="space-y-2">
                                            <!-- Documents will be populated here -->
                                        </div>
                                        <div id="no-documents-message" class="flex items-center text-gray-500">
                                            <i class="fas fa-file-pdf text-gray-400 mr-2"></i>
                                            <span>No documents uploaded</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <button type="button" id="prev-step-3"
                                class="px-6 py-2.5 bg-secondary-500 text-white rounded-lg hover:bg-secondary-600 transition-all duration-200 shadow-md hover:shadow-lg flex items-center text-base">
                                <i class="fas fa-arrow-left mr-2"></i> Previous
                            </button>
                            <button type="submit" id="submit-button"
                                class="px-6 py-2.5 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center text-base disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                <i class="fas fa-paper-plane mr-2"></i> Submit Request
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Validation Modals -->
    @include('components.fund-request-validation-modal')

    <!-- Data Privacy Agreement Modal -->
    <div id="privacy-agreement-modal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden" role="dialog"
        aria-modal="true" aria-labelledby="privacy-modal-title">
        <div class="bg-white w-full max-w-2xl rounded-lg shadow-xl border border-gray-200">
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 bg-primary-500">
                <h3 id="privacy-modal-title" class="text-lg font-semibold text-white">Data Privacy Agreement</h3>
                <button type="button" id="close-privacy-modal-btn"
                    class="text-white hover:text-gray-200 focus:outline-none" aria-label="Close modal">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="max-h-[60vh] overflow-y-auto px-6 py-4">
                <div class="prose prose-sm max-w-none text-gray-800">
                    <p class="text-base mb-4">By uploading documents to the CLSU-ERDT Scholar Management System, you
                        acknowledge and agree to the following:</p>

                    <h4 class="font-semibold text-primary-800 mt-6 mb-2 text-base">1. Information Collection and Use</h4>
                    <p class="text-base">The documents you upload may contain personal information that will be collected
                        and processed for the purpose of evaluating and processing your fund request. This information will
                        be used solely for administrative purposes related to your scholarship.</p>

                    <h4 class="font-semibold text-primary-800 mt-6 mb-2 text-base">2. Data Storage and Security</h4>
                    <p class="text-base">Your uploaded documents will be stored securely within our system. We implement
                        appropriate technical and organizational measures to protect your personal information against
                        unauthorized access, alteration, disclosure, or destruction.</p>

                    <h4 class="font-semibold text-primary-800 mt-6 mb-2 text-base">3. Data Sharing</h4>
                    <p class="text-base">Your information may be shared with authorized personnel involved in the
                        scholarship administration process, including but not limited to administrators, evaluators, and
                        relevant institutional departments. Your information will not be shared with third parties outside
                        of this context without your consent, unless required by law.</p>

                    <h4 class="font-semibold text-primary-800 mt-6 mb-2 text-base">4. Retention Period</h4>
                    <p class="text-base">Your documents will be retained for the duration of your scholarship program and
                        for a reasonable period thereafter for record-keeping purposes, after which they will be securely
                        deleted or anonymized in accordance with our data retention policies.</p>

                    <h4 class="font-semibold text-primary-800 mt-6 mb-2 text-base">5. Your Rights</h4>
                    <p class="text-base">You have the right to access, correct, or request deletion of your personal
                        information. You may also withdraw your consent at any time, though this may affect our ability to
                        process your fund request.</p>

                    <h4 class="font-semibold text-primary-800 mt-6 mb-2 text-base">6. Contact Information</h4>
                    <p class="text-base">If you have any questions about this privacy agreement or our data handling
                        practices, please contact the CLSU-ERDT administration office.</p>
                </div>
            </div>
            <div class="flex justify-end gap-2 bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-lg">
                <button type="button" id="agree-privacy-btn"
                    class="px-6 py-2.5 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-all duration-200 shadow-md hover:shadow-lg flex items-center text-base">
                    <i class="fas fa-check mr-2"></i> I Agree
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {


            // Network status monitoring
            window.addEventListener('offline', function() {
                Swal.fire({
                    title: 'Network Error',
                    text: 'You appear to be offline. Please check your internet connection.',
                    icon: 'warning',
                    confirmButtonColor: 'rgb(245 158 11)'
                });
            });


            // Enhanced form submission handling with debugging
            const form = document.getElementById('multi-step-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    try {

                        // Validate required fields
                        const requestTypeId = document.getElementById('request_type_id').value;
                        const amountInput = document.getElementById('amount');
                        const privacyCheckbox = document.getElementById('privacy-agreement-checkbox');
                        const fileInput = document.getElementById('dropzone-file');



                        // Validate request type
                        if (!requestTypeId) {
                            Swal.fire({
                                title: 'Validation Error',
                                text: 'Please select a request type.',
                                icon: 'error',
                                confirmButtonColor: 'rgb(239 68 68)'
                            });
                            return;
                        }

                        // Validate amount
                        if (!amountInput.value) {
                            Swal.fire({
                                title: 'Validation Error',
                                text: 'Please enter an amount.',
                                icon: 'error',
                                confirmButtonColor: 'rgb(239 68 68)'
                            });
                            return;
                        }

                        // Process amount field
                        let finalAmount = '';
                        if (amountInput.dataset.rawValue) {
                            finalAmount = amountInput.dataset.rawValue;
                        } else if (amountInput.value.includes('₱')) {
                            finalAmount = amountInput.value.replace(/[₱,\s]/g, '');
                        } else {
                            finalAmount = amountInput.value.replace(/[^0-9.]/g, '');
                        }

                        // Validate final amount
                        const numericAmount = parseFloat(finalAmount);
                        if (isNaN(numericAmount) || numericAmount <= 0) {
                            Swal.fire({
                                title: 'Validation Error',
                                text: 'Please enter a valid amount greater than zero.',
                                icon: 'error',
                                confirmButtonColor: 'rgb(239 68 68)'
                            });
                            return;
                        }

                        // Set the clean amount value
                        amountInput.value = finalAmount;

                        // Validate file if uploaded
                        if (fileInput.files.length > 0) {
                            const file = fileInput.files[0];

                            if (file.type !== 'application/pdf') {
                                Swal.fire({
                                    title: 'File Error',
                                    text: 'Please upload only PDF files.',
                                    icon: 'error',
                                    confirmButtonColor: 'rgb(239 68 68)'
                                });
                                return;
                            }

                            const maxSize = 5 * 1024 * 1024; // 5MB
                            if (file.size > maxSize) {
                                Swal.fire({
                                    title: 'File Error',
                                    text: 'Please upload a PDF file smaller than 5MB.',
                                    icon: 'error',
                                    confirmButtonColor: 'rgb(239 68 68)'
                                });
                                return;
                            }

                            // Check privacy agreement if file is uploaded
                            if (!privacyCheckbox.checked) {
                                Swal.fire({
                                    title: 'Privacy Agreement Required',
                                    text: 'Please agree to the Data Privacy Agreement to upload documents.',
                                    icon: 'warning',
                                    confirmButtonColor: 'rgb(245 158 11)'
                                });
                                return;
                            }
                        }



                        // Show loading state
                        // Submit the form directly without loading indicator
                        this.submit();

                    } catch (error) {
                        Swal.fire({
                            title: 'Submission Error',
                            text: 'An unexpected error occurred. Please try again.',
                            icon: 'error',
                            confirmButtonColor: 'rgb(239 68 68)'
                        });
                    }
                });
            }

            // Enhanced monetary limits based on official ERDT scholarship entitlements
            const requestTypeLimits = {
                1: { // Tuition Fee
                    name: 'Tuition Fee',
                    masters: null, // Actual as billed
                    doctoral: null, // Actual as billed
                    description: 'Actual as billed by the university',
                    period: 'per semester',
                    footnote: 'Full tuition coverage as billed'
                },
                2: { // Learning Materials
                    name: 'Learning Materials',
                    masters: 15000,
                    doctoral: 20000,
                    description: 'Books, supplies, and learning materials allowance',
                    period: 'per semester',
                    footnote: 'For educational materials and supplies'
                },
                3: { // Transportation
                    name: 'Transportation',
                    masters: 8000,
                    doctoral: 10000,
                    description: 'Transportation allowance for academic activities',
                    period: 'per month',
                    footnote: 'For academic-related transportation'
                },
                4: { // Research Dissemination Grant
                    name: 'Research Dissemination Grant',
                    masters: 30000,
                    doctoral: 50000,
                    description: 'Grant for research publication and conference presentation',
                    period: 'per event',
                    footnote: 'For research publication and conference presentation'
                },
                5: { // Mentor's Fee
                    name: 'Mentor\'s Fee',
                    masters: 25000,
                    doctoral: 40000,
                    description: 'Payment for thesis/dissertation mentor',
                    period: 'per semester',
                    footnote: 'For thesis/dissertation mentorship'
                },
                6: { // Stipend
                    name: 'Stipend',
                    masters: 15000,
                    doctoral: 20000,
                    description: 'Monthly living allowance for scholars',
                    period: 'per month',
                    footnote: 'Monthly stipend for living expenses'
                },
                7: { // Thesis/Dissertation Grant
                    name: 'Thesis/Dissertation Grant',
                    masters: 60000,
                    doctoral: 100000,
                    description: 'Grant for thesis or dissertation expenses',
                    period: 'one-time',
                    footnote: 'For thesis/dissertation related expenses'
                },
                8: { // Research Equipment Grant
                    name: 'Research Equipment Grant',
                    masters: 225000,
                    doctoral: 475000,
                    description: 'Grant for research equipment and materials',
                    period: 'total program duration',
                    footnote: 'Includes Research Grant (₱114,000 Masters/₱253,000 Doctoral) and Research Dissemination (₱75,000 Masters/₱150,000 Doctoral). Subject to evaluation.',
                    subcategories: {
                        masters: {
                            research: 114000,
                            dissemination: 75000,
                            equipment: 36000
                        },
                        doctoral: {
                            research: 253000,
                            dissemination: 150000,
                            equipment: 72000
                        }
                    }
                },
                7: { // Research Dissemination Grant
                    name: 'Research Dissemination Grant',
                    masters: 75000,
                    doctoral: 150000,
                    description: 'Grant for research publication and presentation',
                    period: 'total program duration',
                    footnote: 'For conference attendance, publication fees, and research dissemination activities'
                },
                8: { // Mentor's Fee
                    name: "Mentor's Fee",
                    masters: 36000,
                    doctoral: 72000,
                    description: 'Payment for thesis/dissertation mentor',
                    period: 'total program duration',
                    footnote: 'Payment for academic supervision and mentoring'
                },
                9: { // Group Accident and Health Insurance - New addition
                    name: 'Group Accident and Health Insurance',
                    masters: null, // Premium amount
                    doctoral: null, // Premium amount
                    description: 'Premium for group accident and health insurance',
                    period: 'per academic year',
                    footnote: 'Actual premium as required'
                }
            };

            // Get user's department from authenticated user's scholar profile
            const userDepartment = '{{ auth()->user()->scholarProfile->department ?? '' }}';
            const userDegree = '{{ auth()->user()->scholarProfile->intended_degree ?? 'masters' }}';
            const isDoctoralProgram = userDegree.toLowerCase().includes('doctoral') ||
                userDegree.toLowerCase().includes('phd') ||
                userDegree.toLowerCase().includes('doctor');
            const programType = isDoctoralProgram ? 'doctoral' : 'masters';

            // Multi-step form functionality
            const steps = ['step-1', 'step-2', 'step-3'];
            let currentStep = 0;

            // Enhanced amount validation with detailed entitlement information
            function validateAmount() {
                const amountInput = document.getElementById('amount');
                const amountLimitInfo = document.getElementById('amount-limit-info');
                const amountWarning = document.getElementById('amount-warning');
                const amountError = document.getElementById('amount-error');
                const amountWarningText = document.getElementById('amount-warning-text');
                const amountErrorText = document.getElementById('amount-error-text');

                // If no request type is selected, clear all validation messages
                if (!requestTypeSelect.value) {
                    amountLimitInfo.textContent = '';
                    amountWarning.classList.add('hidden');
                    amountError.classList.add('hidden');
                    return true;
                }

                // If request type is selected but no amount, show limit information only
                if (!amountInput.value) {
                    const requestTypeId = parseInt(requestTypeSelect.value);
                    const limit = requestTypeLimits[requestTypeId];

                    if (limit) {
                        // Reset validation states
                        amountWarning.classList.add('hidden');
                        amountError.classList.add('hidden');
                        amountInput.classList.remove('validation-error', 'validation-warning',
                            'validation-success');
                        amountInput.classList.add('border-gray-300');

                        // Show limit information in request type section
                        const requestTypeInfo = document.getElementById('request-type-info');
                        const requestTypeInfoText = document.getElementById('request-type-info-text');
                        
                        if (limit.isTransportation) {
                            requestTypeInfoText.innerHTML = `
                                <strong>${limit.name}:</strong> ${limit.description}<br>
                                <em class="text-blue-500">${limit.footnote}</em>
                            `;
                        } else if (limit[programType] === null) {
                            requestTypeInfoText.innerHTML = `
                                <strong>${limit.name}:</strong> ${limit.description} (${limit.period})<br>
                                <em class="text-blue-500">${limit.footnote}</em>
                            `;
                        } else {
                            const limitAmount = limit[programType];
                            let limitDisplay =
                                `<strong>${limit.name}:</strong> Maximum ₱${limitAmount.toLocaleString()} (${limit.period}) for ${programType} program`;

                            if (limit.subcategories && limit.subcategories[programType]) {
                                const sub = limit.subcategories[programType];
                                limitDisplay +=
                                    `<br><small class="text-xs">Breakdown: Research ₱${sub.research.toLocaleString()}, Dissemination ₱${sub.dissemination.toLocaleString()}, Equipment ₱${sub.equipment.toLocaleString()}</small>`;
                            }

                            limitDisplay += `<br><em class="text-xs">${limit.footnote}</em>`;

                            requestTypeInfoText.innerHTML = limitDisplay;
                        }
                        
                        // Show the request type info section
                        requestTypeInfo.classList.remove('hidden');
                        
                        // Reset amount limit info to default
                        amountLimitInfo.innerHTML = '<i class="fas fa-info-circle mr-2"></i><span></span>';
                        amountLimitInfo.className = 'text-sm text-info-600 mt-2 font-medium h-5 flex items-center';
                    }
                    return true;
                }

                const requestTypeId = parseInt(requestTypeSelect.value);

                // Use raw value for validation if available, otherwise parse the current value
                const rawValue = amountInput.dataset.rawValue || amountInput.value.replace(/[₱,\s]/g, '');
                const amount = parseFloat(rawValue);
                const limit = requestTypeLimits[requestTypeId];

                if (!limit) {
                    amountLimitInfo.textContent = 'Request type not found';
                    amountError.classList.remove('hidden');
                    amountErrorText.textContent = 'Invalid request type selected';
                    return false;
                }

                // Reset states
                amountWarning.classList.add('hidden');
                amountError.classList.add('hidden');
                amountInput.classList.remove('validation-error', 'validation-warning', 'validation-success');
                amountInput.classList.add('border-gray-300');

                // Check for Master of Engineering program restrictions
                const isMasterOfEngineering = userDegree.toLowerCase().includes('master of engineering') ||
                    userDegree.toLowerCase().includes('m.eng');

                // Restricted grants for Master of Engineering
                const restrictedForMEng = [5, 6, 7, 8]; // Thesis/Dissertation, Research Support, Research Dissemination, Mentor's Fee

                if (isMasterOfEngineering && restrictedForMEng.includes(requestTypeId)) {
                    amountLimitInfo.textContent = `${limit.name}: Not applicable to Master of Engineering scholars`;
                    amountError.classList.remove('hidden');
                    amountErrorText.textContent =
                        'This grant type is not available for Master of Engineering degree';
                    amountInput.classList.remove('border-gray-300');
                    amountInput.classList.add('validation-error');
                    amountLimitInfo.className = 'text-xs text-red-600 mb-1 font-medium h-5';
                    return false;
                }

                // Special handling for Transportation Allowance
                if (limit.isTransportation) {
                    amountLimitInfo.innerHTML = `
                    <div class="text-xs text-blue-600 mb-1 font-medium">
                        <strong>${limit.name}:</strong> ${limit.description}<br>
                        <em class="text-blue-500">${limit.footnote}</em>
                    </div>
                `;
                    amountLimitInfo.className = 'text-xs text-blue-600 mb-1 font-medium h-auto';
                    amountInput.classList.remove('border-gray-300');
                    amountInput.classList.add('validation-success');
                    return true;
                }

                // Display limit information for fixed amounts
                if (limit[programType] === null) {
                    amountLimitInfo.innerHTML = `
                    <div class="text-xs text-blue-600 mb-1 font-medium">
                        <strong>${limit.name}:</strong> ${limit.description} (${limit.period})<br>
                        <em class="text-blue-500">${limit.footnote}</em>
                    </div>
                `;
                    amountLimitInfo.className = 'text-xs text-blue-600 mb-1 font-medium h-auto';
                    amountInput.classList.remove('border-gray-300');
                    amountInput.classList.add('validation-success');
                    return true;
                } else {
                    const limitAmount = limit[programType];

                    // Enhanced display with subcategory breakdown for Research Support Grant
                    let limitDisplay =
                        `<strong>${limit.name}:</strong> Maximum ₱${limitAmount.toLocaleString()} (${limit.period}) for ${programType} program`;

                    if (limit.subcategories && limit.subcategories[programType]) {
                        const sub = limit.subcategories[programType];
                        limitDisplay +=
                            `<br><small class="text-xs">Breakdown: Research ₱${sub.research.toLocaleString()}, Dissemination ₱${sub.dissemination.toLocaleString()}, Equipment ₱${sub.equipment.toLocaleString()}</small>`;
                    }

                    limitDisplay += `<br><em class="text-xs">${limit.footnote}</em>`;

                    // Validation logic with enhanced visual feedback
                    console.log('Validating amount against limit:', amount, 'vs', limitAmount);

                    if (amount > limitAmount) {
                        console.log('Amount exceeds limit');
                        amountError.classList.remove('hidden');
                        amountErrorText.textContent =
                            `Amount exceeds maximum entitlement of ₱${limitAmount.toLocaleString()} (${limit.period})`;
                        amountInput.classList.remove('border-gray-300');
                        amountInput.classList.add('validation-error');
                        amountLimitInfo.innerHTML =
                            `<div class="text-xs text-red-600 mb-1 font-medium">${limitDisplay}</div>`;
                        amountLimitInfo.className = 'text-xs text-red-600 mb-1 font-medium h-auto';
                        return false;
                    } else if (amount > limitAmount * 0.8) {
                        // Warning when approaching limit (80% of max)
                        console.log('Amount approaching limit (80%+)');
                        amountWarning.classList.remove('hidden');
                        amountWarningText.textContent =
                            `Amount is approaching the maximum entitlement (${((amount/limitAmount)*100).toFixed(1)}% of ₱${limitAmount.toLocaleString()} limit)`;
                        amountInput.classList.remove('border-gray-300');
                        amountInput.classList.add('validation-warning');
                        amountLimitInfo.innerHTML =
                            `<div class="text-xs text-amber-600 mb-1 font-medium">${limitDisplay}</div>`;
                        amountLimitInfo.className = 'text-xs text-amber-600 mb-1 font-medium h-auto';
                        return true;
                    } else {
                        console.log('Amount within acceptable range');
                        amountLimitInfo.innerHTML =
                            `<div class="text-xs text-primary-800 mb-1 font-medium">${limitDisplay}</div>`;
                        amountLimitInfo.className = 'text-xs text-primary-800 mb-1 font-medium h-auto';
                        amountInput.classList.remove('border-gray-300');
                        amountInput.classList.add('validation-success');
                    }
                    return true;
                }
            }

            // Enhanced input validation with debugging
            function validateInput(input, type) {
                console.log('=== INPUT VALIDATION DEBUG ===');
                console.log('Input type:', type);
                console.log('Original value:', input.value);

                const value = input.value.trim();

                switch (type) {
                    case 'amount':
                        // Remove any non-numeric characters except decimal point
                        const cleanValue = value.replace(/[^0-9.]/g, '');
                        console.log('Cleaned value:', cleanValue);

                        if (cleanValue !== value) {
                            console.log('Value was modified during cleaning');
                            input.value = cleanValue;
                        }

                        // Ensure only one decimal point
                        const parts = cleanValue.split('.');
                        if (parts.length > 2) {
                            console.log('Multiple decimal points found, fixing...');
                            input.value = parts[0] + '.' + parts.slice(1).join('');
                        }

                        // Limit to 2 decimal places
                        if (parts[1] && parts[1].length > 2) {
                            console.log('More than 2 decimal places, truncating...');
                            input.value = parts[0] + '.' + parts[1].substring(0, 2);
                        }

                        console.log('Final validated value:', input.value);
                        validateAmount();
                        break;
                }
            }

            // Event listeners for validation
            const requestTypeSelect = document.getElementById('request_type_id');
            const amountInput = document.getElementById('amount');

            // Document requirements data from backend
            const documentRequirements = @json(\App\Models\RequestType::DOCUMENT_REQUIREMENTS);

            // Function to display document requirements with debugging
            function displayDocumentRequirements(requestTypeName) {
                console.log('=== DOCUMENT REQUIREMENTS DEBUG ===');
                console.log('Request type name:', requestTypeName);
                console.log('Available requirements:', Object.keys(documentRequirements));

                const requirementsDiv = document.getElementById('document-requirements');
                const requirementsList = document.getElementById('requirements-list');

                if (!requestTypeName || !documentRequirements[requestTypeName]) {
                    console.log('No requirements found for request type, hiding requirements div');
                    requirementsDiv.classList.add('hidden');
                    return;
                }

                console.log('Requirements found:', documentRequirements[requestTypeName]);

                const requirements = documentRequirements[requestTypeName];
                let html = `
                <p class="mb-2"><strong>Required Documents:</strong></p>
                <ul class="list-disc list-inside space-y-1 mb-3">
            `;

                requirements.documents.forEach(doc => {
                    html += `<li>${doc}</li>`;
                });

                html += `
                </ul>
                <p class="text-xs text-blue-600 mb-2">
                    <strong>Submission Frequency:</strong> ${requirements.frequency}
                </p>
                <p class="text-xs text-blue-600">
                    <strong>Guidance:</strong> ${requirements.guidance}
                </p>
            `;

                requirementsList.innerHTML = html;
                requirementsDiv.classList.remove('hidden');
            }

            if (requestTypeSelect) {
                requestTypeSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const requestTypeName = selectedOption.text;
                    const requestTypeInfo = document.getElementById('request-type-info');

                    // Hide request type info if no selection
                    if (!this.value) {
                        requestTypeInfo.classList.add('hidden');
                    }

                    // Always validate amount when request type changes to show limits
                    validateAmount();
                    updateReviewSection();
                    displayDocumentRequirements(requestTypeName);
                });
            }

            if (amountInput) {
                amountInput.addEventListener('input', function() {
                    validateInput(this, 'amount');

                    // Store the current raw value for form submission
                    const cleanValue = this.value.replace(/[₱,\s]/g, '');
                    if (cleanValue && !isNaN(cleanValue)) {
                        this.dataset.rawValue = cleanValue;
                    }

                    // Add visual feedback during typing
                    this.classList.add('border-blue-400', 'ring-2', 'ring-blue-200');
                    setTimeout(() => {
                        this.classList.remove('border-blue-400', 'ring-2', 'ring-blue-200');
                    }, 300);

                    // Trigger validation when both request type and amount are present
                    if (requestTypeSelect && requestTypeSelect.value && this.value) {
                        validateAmount();
                    }

                    updateReviewSection();
                });

                // Add currency formatting on blur
                amountInput.addEventListener('blur', function() {
                    validateAmount();

                    if (this.value && !isNaN(this.value.replace(/[₱,\s]/g, ''))) {
                        // Clean the value first
                        const cleanValue = this.value.replace(/[₱,\s]/g, '');
                        const numValue = parseFloat(cleanValue);

                        // Store the raw numeric value for form submission
                        this.dataset.rawValue = cleanValue;

                        // Format with Philippine peso symbol and proper number formatting
                        // Only show decimals if the number has decimal places
                        const hasDecimals = cleanValue.includes('.') && parseFloat(cleanValue) % 1 !== 0;
                        const formatted = new Intl.NumberFormat('en-PH', {
                            style: 'currency',
                            currency: 'PHP',
                            minimumFractionDigits: hasDecimals ? 2 : 0,
                            maximumFractionDigits: 2
                        }).format(numValue);

                        this.value = formatted;

                        // Update review section after formatting
                        updateReviewSection();
                    }
                });

                // Remove formatting on focus for easier editing
                amountInput.addEventListener('focus', function() {
                    if (this.dataset.rawValue) {
                        this.value = this.dataset.rawValue;
                    } else if (this.value.includes('₱')) {
                        // Remove currency formatting
                        this.value = this.value.replace(/[₱,\s]/g, '');
                    }

                    // Update review section when focusing
                    updateReviewSection();
                });

                amountInput.addEventListener('paste', function(e) {
                    setTimeout(() => validateInput(this, 'amount'), 0);
                });
            }

            function showStep(stepIndex) {
                console.log('=== STEP NAVIGATION DEBUG ===');
                console.log('Showing step:', stepIndex + 1);
                console.log('Available steps:', steps);

                steps.forEach((stepId, index) => {
                    const stepElement = document.getElementById(stepId);
                    console.log(`Step ${index + 1} (${stepId}):`, index === stepIndex ? 'ACTIVE' :
                        'HIDDEN');

                    if (index === stepIndex) {
                        stepElement.classList.add('active');
                        stepElement.classList.remove('hidden');
                    } else {
                        stepElement.classList.remove('active');
                        stepElement.classList.add('hidden');
                    }
                });
                currentStep = stepIndex;
                console.log('Current step set to:', currentStep + 1);
            }

            function validateStep1() {
                console.log('=== STEP 1 VALIDATION DEBUG ===');

                const requestType = document.getElementById('request_type_id').value;
                const amount = document.getElementById('amount').value;

                console.log('Request type value:', requestType);
                console.log('Amount value:', amount);

                if (!requestType || !amount) {
                    console.error('Step 1 validation failed: Missing required fields');
                    console.log('Request type empty:', !requestType);
                    console.log('Amount empty:', !amount);

                    Swal.fire({
                        title: 'Validation Error',
                        text: 'Please fill in all required fields in Step 1.',
                        icon: 'warning',
                        confirmButtonColor: 'rgb(245 158 11)'
                    });
                    return false;
                }

                // Enhanced amount validation
                const amountValidation = validateAmount();
                console.log('Amount validation result:', amountValidation);

                if (!amountValidation) {
                    console.error('Step 1 validation failed: Amount validation failed');
                    Swal.fire({
                        title: 'Amount Error',
                        text: 'Please correct the amount before proceeding.',
                        icon: 'error',
                        confirmButtonColor: 'rgb(239 68 68)'
                    });
                    return false;
                }

                console.log('Step 1 validation passed');
                return true;
            }

            function updateReviewSection() {
                const requestTypeText = requestTypeSelect.options[requestTypeSelect.selectedIndex].text;
                const amountInput = document.getElementById('amount');
                const fileInput = document.getElementById('dropzone-file');

                document.getElementById('review-request-type').textContent = requestTypeText;

                // Get the raw numeric value, handling both formatted and unformatted input
                let rawAmount = amountInput.dataset.rawValue || amountInput.value;

                // If the value is formatted (contains currency symbols), clean it
                if (typeof rawAmount === 'string' && rawAmount.includes('₱')) {
                    rawAmount = rawAmount.replace(/[₱,\s]/g, '');
                }

                // Parse and validate the amount
                const numericAmount = parseFloat(rawAmount);
                let formattedAmount = '₱0.00';
                if (!isNaN(numericAmount) && numericAmount > 0) {
                    // Only show decimals if the number has decimal places
                    const hasDecimals = rawAmount.includes('.') && numericAmount % 1 !== 0;
                    formattedAmount = '₱' + numericAmount.toLocaleString('en-PH', {
                        minimumFractionDigits: hasDecimals ? 2 : 0,
                        maximumFractionDigits: 2
                    });
                }
                document.getElementById('review-amount').textContent = formattedAmount;

                // Update documents review section
                const reviewDocumentsList = document.getElementById('review-documents-list');
                const noDocumentsMessage = document.getElementById('no-documents-message');
                
                reviewDocumentsList.innerHTML = '';
                
                if (uploadedFiles.length > 0) {
                    noDocumentsMessage.style.display = 'none';
                    uploadedFiles.forEach(file => {
                        const docElement = document.createElement('div');
                        docElement.className = 'flex items-center text-gray-800';
                        docElement.innerHTML = `
                            <i class="fas fa-file-pdf text-primary-500 mr-2"></i>
                            <span class="font-medium">${file.name}</span>
                            <span class="text-sm text-gray-500 ml-2">(${(file.size / 1024).toFixed(1)} KB)</span>
                        `;
                        reviewDocumentsList.appendChild(docElement);
                    });
                } else {
                    noDocumentsMessage.style.display = 'flex';
                }
            }

            // Step navigation
            document.getElementById('next-step-1').addEventListener('click', function() {
                if (validateStep1()) {
                    showStep(1);
                }
            });

            document.getElementById('prev-step-2').addEventListener('click', function() {
                showStep(0);
            });

            document.getElementById('next-step-2').addEventListener('click', function() {
                updateReviewSection();
                showStep(2);
            });

            document.getElementById('prev-step-3').addEventListener('click', function() {
                showStep(1);
            });

            // Privacy Agreement Modal
            const privacyModal = document.getElementById('privacy-agreement-modal');
            const openPrivacyBtn = document.getElementById('open-privacy-modal');
            const closePrivacyBtn = document.getElementById('close-privacy-modal-btn');
            const agreePrivacyBtn = document.getElementById('agree-privacy-btn');
            const privacyCheckbox = document.getElementById('privacy-agreement-checkbox');
            const privacyError = document.getElementById('privacy-agreement-error');
            const fileInput = document.getElementById('dropzone-file');

            function openPrivacyModal() {
                console.log('=== PRIVACY MODAL DEBUG ===');
                console.log('Opening privacy modal');
                privacyModal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closePrivacyModal() {
                console.log('Closing privacy modal');
                privacyModal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            openPrivacyBtn.addEventListener('click', openPrivacyModal);
            closePrivacyBtn.addEventListener('click', closePrivacyModal);

            agreePrivacyBtn.addEventListener('click', function() {
                privacyCheckbox.checked = true;
                privacyError.classList.add('hidden');
                closePrivacyModal();
            });

            privacyModal.addEventListener('click', function(event) {
                if (event.target === privacyModal) {
                    closePrivacyModal();
                }
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && !privacyModal.classList.contains('hidden')) {
                    closePrivacyModal();
                }
            });

            // Privacy agreement handling for file uploads
            function checkPrivacyAgreement() {
                if (fileInput.files && fileInput.files.length > 0 && !privacyCheckbox.checked) {
                    openPrivacyModal();
                }
            }

            // Multiple file upload handling
            const filesContainer = document.getElementById('files-container');
            const uploadedFilesHeader = document.getElementById('uploaded-files-header');
            const fileCountSpan = document.getElementById('file-count');
            const clearAllBtn = document.getElementById('clear-all-files');
            let uploadedFiles = [];
            const maxFiles = 5;

            function getFileIcon(type) {
                if (type === 'application/pdf') {
                    return '<i class="fas fa-file-pdf text-red-500 text-lg mr-2"></i>';
                } else {
                    return '<i class="fas fa-file-alt text-gray-400 text-lg mr-2"></i>';
                }
            }

            function updateFileCount() {
                fileCountSpan.textContent = uploadedFiles.length;
                if (uploadedFiles.length > 0) {
                    uploadedFilesHeader.classList.remove('hidden');
                } else {
                    uploadedFilesHeader.classList.add('hidden');
                }
            }

            function removeFile(index) {
                uploadedFiles.splice(index, 1);
                updateFileDisplay();
                updateFileCount();
                
                // Update the file input
                const dt = new DataTransfer();
                uploadedFiles.forEach(file => dt.items.add(file));
                fileInput.files = dt.files;
            }

            function clearAllFiles() {
                uploadedFiles = [];
                fileInput.value = '';
                updateFileDisplay();
                updateFileCount();
            }

            function updateFileDisplay() {
                filesContainer.innerHTML = '';
                uploadedFiles.forEach((file, index) => {
                    const card = document.createElement('div');
                    card.className = 'p-3 bg-green-50 border border-green-200 rounded-lg';
                    card.innerHTML = `
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                ${getFileIcon(file.type)}
                                <span class="text-green-800 font-medium">${file.name}</span>
                                <span class="text-green-600 text-sm ml-2">(${(file.size / 1024).toFixed(1)} KB)</span>
                            </div>
                            <button type="button" onclick="removeFile(${index})" 
                                    class="text-red-500 hover:text-red-700 transition-colors duration-200">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                    filesContainer.appendChild(card);
                });
            }

            // Make removeFile function global
            window.removeFile = removeFile;

            if (clearAllBtn) {
                clearAllBtn.addEventListener('click', clearAllFiles);
            }

            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    console.log('=== MULTIPLE FILE UPLOAD DEBUG ===');
                    console.log('Files selected:', fileInput.files.length);

                    const newFiles = Array.from(fileInput.files);
                    const validFiles = [];

                    for (const file of newFiles) {
                        console.log('Processing file:', file.name, file.type, file.size);

                        // Check if we've reached the maximum number of files
                        if (uploadedFiles.length + validFiles.length >= maxFiles) {
                            Swal.fire({
                                title: 'Maximum Files Reached',
                                text: `You can only upload up to ${maxFiles} files.`,
                                icon: 'warning',
                                confirmButtonText: 'OK',
                                confirmButtonColor: 'rgb(245 158 11)'
                            });
                            break;
                        }

                        // Validate file type (PDF only)
                        if (file.type !== 'application/pdf') {
                            console.error('File validation failed: Invalid file type for', file.name);
                            Swal.fire({
                                title: 'Invalid File Type',
                                text: `"${file.name}" is not a PDF file. Please upload only PDF files.`,
                                icon: 'error',
                                confirmButtonText: 'OK',
                                confirmButtonColor: 'rgb(239 68 68)'
                            });
                            continue;
                        }

                        // Validate file size (5MB = 5 * 1024 * 1024 bytes)
                        const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                        if (file.size > maxSize) {
                            console.error('File validation failed: File too large for', file.name);
                            Swal.fire({
                                title: 'File Too Large',
                                text: `"${file.name}" is larger than 5MB. Please upload smaller files.`,
                                icon: 'error',
                                confirmButtonText: 'OK',
                                confirmButtonColor: 'rgb(239 68 68)'
                            });
                            continue;
                        }

                        // Check for duplicate files
                        const isDuplicate = uploadedFiles.some(existingFile => 
                            existingFile.name === file.name && existingFile.size === file.size
                        );
                        
                        if (isDuplicate) {
                            console.warn('Duplicate file detected:', file.name);
                            Swal.fire({
                                title: 'Duplicate File',
                                text: `"${file.name}" has already been uploaded.`,
                                icon: 'warning',
                                confirmButtonText: 'OK',
                                confirmButtonColor: 'rgb(245 158 11)'
                            });
                            continue;
                        }

                        validFiles.push(file);
                        console.log('File validation passed for:', file.name);
                    }

                    // Add valid files to the uploaded files array
                     uploadedFiles.push(...validFiles);
                     
                     // Update the file input with all uploaded files
                     const dt = new DataTransfer();
                     uploadedFiles.forEach(file => dt.items.add(file));
                     fileInput.files = dt.files;

                     updateFileDisplay();
                     updateFileCount();
                     
                     // Check privacy agreement if files are uploaded
                     checkPrivacyAgreement();
                });
            }

            // Form submission handler to ensure raw numeric value is sent
            const submitForm = document.querySelector('form');
            if (submitForm) {
                submitForm.addEventListener('submit', function(e) {
                    const amountInput = document.getElementById('amount');
                    if (amountInput) {
                        // Get the raw numeric value
                        let rawValue = amountInput.dataset.rawValue || amountInput.value;

                        // Clean any currency formatting
                        if (typeof rawValue === 'string' && rawValue.includes('₱')) {
                            rawValue = rawValue.replace(/[₱,\s]/g, '');
                        }

                        // Set the clean numeric value for form submission
                        amountInput.value = rawValue;
                    }
                });
            }

            // Initialize first step and review section
            showStep(0);

            // Only validate amount if both request type and amount have values (e.g., from old() input)
            const amountInputInit = document.getElementById('amount');
            if (requestTypeSelect && amountInputInit && requestTypeSelect.value && amountInputInit.value) {
                validateAmount();
            }

            updateReviewSection();
        });
    </script>

@endsection
