@extends('layouts.app')

@section('title', 'Create Fund Request')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto">
        <!-- Header with Branding -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Create New Fund Request</h1>
                    <p class="text-gray-600 text-base mt-1">CLSU-ERDT Scholar Management System</p>
                </div>
                <a href="{{ route('scholar.fund-requests.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg flex items-center space-x-2">
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
                    confirmButtonColor: '#4CAF50'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('scholar.fund-requests.index') }}";
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
                    confirmButtonColor: '#EF4444'
                });
            });
        </script>
        @endif

        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <!-- Important Information Box -->
            <div class="mb-5 p-4 bg-[#E3F2FD] border border-[#90CAF9] rounded-lg">
                <h3 class="text-lg font-semibold text-[#1976D2] mb-2 flex items-center">
                    <i class="fas fa-info-circle mr-2 " style="color: #1976D2;"></i>
                    Important Information
                </h3>
                <div class="space-y-2 text-[#1565C0]">
                    <p class="text-base">Your fund request will be reviewed by an administrator after submission.</p>
                    <p class="text-base">Each request type has a maximum allowable amount based on your program level (Master's or Doctoral).</p>
                    <p class="text-base"><strong>Eligibility:</strong> Fund requests are only available for PhD and Master's level scholars.</p>
                </div>
            </div>

            <form action="{{ route('scholar.fund-requests.store') }}" method="POST" enctype="multipart/form-data" id="multi-step-form">
                @csrf
                <input type="hidden" name="status" value="Submitted">

                <!-- Step 1: Request Details -->
                <div id="step-1" class="step-content">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 rounded-full bg-[#4CAF50] text-white flex items-center justify-center text-lg font-semibold">1</div>
                        <h2 class="text-xl font-semibold text-gray-800 ml-3">Request Details</h2>
                    </div>

                    <div class="mb-6">
                        <label for="request_type_id" class="block text-base font-medium text-gray-700 mb-2">Request Type <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select id="request_type_id" name="request_type_id" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-base focus:outline-none focus:ring-2 focus:ring-[#4CAF50] focus:border-[#4CAF50] transition-colors duration-200 bg-white" required>
                                <option value="">Select Request Type</option>
                                @foreach($requestTypes as $type)
                                <option value="{{ $type->id }}" {{ old('request_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                        @error('request_type_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="amount" class="block text-base font-medium text-gray-700 mb-2">Amount (₱) <span class="text-red-500">*</span></label>
                        <div class="inline-flex justify-between bg-gray-100 rounded border border-gray-200 w-full hover:border-[#4CAF50] focus-within:border-[#4CAF50] focus-within:ring-2 focus-within:ring-[#4CAF50]/20 transition-all duration-200">
                            <div class="inline bg-gray-200 py-2 px-4 text-gray-600 select-none rounded-l">₱</div>
                            <input type="number" id="amount" name="amount" value="{{ old('amount') }}" step="0.01" min="0" max="999999999"
                                   class="bg-transparent py-2 text-gray-600 px-4 focus:outline-none w-full text-base"
                                   placeholder="10,000"
                                   required>
                            <div class="inline bg-gray-200 py-2 px-4 text-gray-600 select-none rounded-r">.00</div>
                        </div>
                        <div id="amount-limit-info" class="text-sm text-[#1976D2] mt-2 font-medium h-5 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span></span>
                        </div>
                        <div id="amount-warning" class="text-sm text-[#F57C00] mt-2 font-medium h-5 hidden flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span id="amount-warning-text"></span>
                        </div>
                        <div id="amount-error" class="text-sm text-red-500 mt-2 font-medium h-5 hidden flex items-center">
                            <i class="fas fa-times-circle mr-2"></i>
                            <span id="amount-error-text"></span>
                        </div>
                        @error('amount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="button" id="next-step-1" class="px-6 py-2.5 bg-[#4CAF50] text-white rounded-lg hover:bg-[#43A047] transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center text-base">
                            Next: Documents <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Supporting Documents -->
                <div id="step-2" class="step-content hidden">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 rounded-full bg-[#4CAF50] text-white flex items-center justify-center text-lg font-semibold">2</div>
                        <h2 class="text-xl font-semibold text-gray-800 ml-3">Supporting Documents</h2>
                    </div>

                    <div class="mb-6">
                        <label for="dropzone-file" class="block text-base font-medium text-gray-700 mb-2">Supporting Document <span class="text-gray-500">(Optional)</span></label>
                        <div class="flex items-center justify-center w-full">
                            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full min-h-[8rem] sm:min-h-[12rem] md:min-h-[16rem] border-2 border-[#4CAF50] border-dashed rounded-lg cursor-pointer bg-[#E8F5E9] hover:bg-[#C8E6C9] transition-colors duration-200 px-2 sm:px-6">
                                <div class="flex flex-col items-center justify-center pt-4 sm:pt-5 pb-4 sm:pb-6 w-full">
                                    <svg class="w-10 h-10 sm:w-12 sm:h-12 mb-3 sm:mb-4 text-[#4CAF50]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                    </svg>
                                    <p class="mb-2 text-sm sm:text-base text-[#2E7D32] text-center"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs sm:text-sm text-[#388E3C] text-center">PDF only (Max. 10MB)</p>
                                </div>
                                <input id="dropzone-file" name="document" type="file" class="hidden" accept=".pdf">
                            </label>
                        </div>
                        <div id="selected-file-info" class="mt-4"></div>
                        <p class="text-sm text-gray-600 mt-2">Upload supporting documents like registration forms, receipts, or other relevant files in PDF format only.</p>
                    </div>

                    <!-- Data Privacy Agreement -->
                    <div class="mb-6 p-4 bg-[#E3F2FD] border border-[#90CAF9] rounded-lg">
                        <div class="flex items-start">
                            <input type="checkbox" id="privacy-agreement-checkbox" name="privacy_agreement" class="mt-1 mr-3 h-4 w-4 text-[#4CAF50] border-gray-300 rounded focus:ring-[#4CAF50]" required>
                            <div class="flex-1">
                                <label for="privacy-agreement-checkbox" class="text-base text-[#1565C0]">
                                    I agree to the <button type="button" id="open-privacy-modal" class="text-[#1976D2] hover:text-[#1565C0] underline transition-colors duration-150 font-medium">Data Privacy Agreement</button> for document uploads.
                                </label>
                                <div id="privacy-agreement-error" class="text-red-500 text-sm mt-1 hidden">
                                    You must agree to the Data Privacy Agreement to upload documents.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <button type="button" id="prev-step-2" class="px-6 py-2.5 bg-[#4A90E2] text-white rounded-lg hover:bg-[#357ABD] transition-all duration-200 shadow-md hover:shadow-lg flex items-center text-base">
                            <i class="fas fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button type="button" id="next-step-2" class="px-6 py-2.5 bg-[#4CAF50] text-white rounded-lg hover:bg-[#43A047] transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center text-base">
                            Next: Review <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Review & Submit -->
                <div id="step-3" class="step-content hidden">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 rounded-full bg-[#4CAF50] text-white flex items-center justify-center text-lg font-semibold">3</div>
                        <h2 class="text-xl font-semibold text-gray-800 ml-3">Review & Submit</h2>
                    </div>

                    <div class="bg-[#F5F5F5] rounded-lg p-6 mb-6 border border-gray-200">
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
                                    <span id="review-amount-max" class="text-sm text-[#4CAF50] block"></span>
                                </div>
                            </div>
                            <div class="py-2">
                                <span class="text-base font-medium text-gray-600">Supporting Document:</span>
                                <div id="review-document" class="mt-2 flex items-center text-base text-gray-800">
                                    <i class="fas fa-file-pdf text-[#4CAF50] mr-2"></i>
                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <button type="button" id="prev-step-3" class="px-6 py-2.5 bg-[#4A90E2] text-white rounded-lg hover:bg-[#357ABD] transition-all duration-200 shadow-md hover:shadow-lg flex items-center text-base">
                            <i class="fas fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-[#4CAF50] text-white rounded-lg hover:bg-[#43A047] transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center text-base">
                            <i class="fas fa-paper-plane mr-2"></i> Submit Request
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Data Privacy Agreement Modal -->
<div id="privacy-agreement-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden" role="dialog" aria-modal="true" aria-labelledby="privacy-modal-title">
    <div class="bg-white w-full max-w-2xl rounded-lg shadow-xl border border-gray-200">
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 bg-[#4CAF50]">
            <h3 id="privacy-modal-title" class="text-lg font-semibold text-white">Data Privacy Agreement</h3>
            <button type="button" id="close-privacy-modal-btn" class="text-white hover:text-gray-200 focus:outline-none" aria-label="Close modal">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="max-h-[60vh] overflow-y-auto px-6 py-4">
            <div class="prose prose-sm max-w-none text-gray-800">
                <p class="text-base mb-4">By uploading documents to the CLSU-ERDT Scholar Management System, you acknowledge and agree to the following:</p>

                <h4 class="font-semibold text-[#2E7D32] mt-6 mb-2 text-base">1. Information Collection and Use</h4>
                <p class="text-base">The documents you upload may contain personal information that will be collected and processed for the purpose of evaluating and processing your fund request. This information will be used solely for administrative purposes related to your scholarship.</p>

                <h4 class="font-semibold text-[#2E7D32] mt-6 mb-2 text-base">2. Data Storage and Security</h4>
                <p class="text-base">Your uploaded documents will be stored securely within our system. We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>

                <h4 class="font-semibold text-[#2E7D32] mt-6 mb-2 text-base">3. Data Sharing</h4>
                <p class="text-base">Your information may be shared with authorized personnel involved in the scholarship administration process, including but not limited to administrators, evaluators, and relevant institutional departments. Your information will not be shared with third parties outside of this context without your consent, unless required by law.</p>

                <h4 class="font-semibold text-[#2E7D32] mt-6 mb-2 text-base">4. Retention Period</h4>
                <p class="text-base">Your documents will be retained for the duration of your scholarship program and for a reasonable period thereafter for record-keeping purposes, after which they will be securely deleted or anonymized in accordance with our data retention policies.</p>

                <h4 class="font-semibold text-[#2E7D32] mt-6 mb-2 text-base">5. Your Rights</h4>
                <p class="text-base">You have the right to access, correct, or request deletion of your personal information. You may also withdraw your consent at any time, though this may affect our ability to process your fund request.</p>

                <h4 class="font-semibold text-[#2E7D32] mt-6 mb-2 text-base">6. Contact Information</h4>
                <p class="text-base">If you have any questions about this privacy agreement or our data handling practices, please contact the CLSU-ERDT administration office.</p>
            </div>
        </div>
        <div class="flex justify-end gap-2 bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-lg">
            <button type="button" id="agree-privacy-btn" class="px-6 py-2.5 bg-[#4CAF50] text-white rounded-lg hover:bg-[#43A047] transition-all duration-200 shadow-md hover:shadow-lg flex items-center text-base">
                <i class="fas fa-check mr-2"></i> I Agree
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form submission handling
        const form = document.getElementById('multi-step-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Show loading state
                Swal.fire({
                    title: 'Submitting Fund Request',
                    text: 'Please wait while we process your request...',
                    icon: 'info',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit the form
                this.submit();
            });
        }

        // Enhanced monetary limits based on scholarship privileges
        const requestTypeLimits = {
            1: { // Tuition Fee
                name: 'Tuition Fee',
                masters: null, // Actual as billed
                doctoral: null, // Actual as billed
                description: 'Full tuition coverage as billed by the university'
            },
            2: { // Stipend
                name: 'Living Allowance/Stipend',
                masters: 30000,
                doctoral: 38000,
                description: 'Monthly living allowance for scholars'
            },
            3: { // Learning Materials and Connectivity Allowance
                name: 'Learning Materials',
                masters: 20000,
                doctoral: 20000,
                description: 'Allowance for books, materials, and connectivity'
            },
            4: { // Transportation Allowance - FIXED: Added missing validation
                name: 'Transportation Allowance',
                masters: 15000,
                doctoral: 15000,
                description: 'Monthly transportation allowance'
            },
            5: { // Thesis/Dissertation Outright Grant
                name: 'Thesis/Dissertation Grant',
                masters: 60000,
                doctoral: 100000,
                description: 'Grant for thesis or dissertation expenses'
            },
            6: { // Research Support Grant - Equipment
                name: 'Research Grant',
                masters: 225000,
                doctoral: 475000,
                description: 'Grant for research equipment and materials'
            },
            7: { // Research Dissemination Grant
                name: 'Research Dissemination',
                masters: 75000,
                doctoral: 150000,
                description: 'Grant for conference attendance and publication'
            },
            8: { // Mentor's Fee
                name: "Mentor's Fee",
                masters: 36000,
                doctoral: 72000,
                description: 'Payment for thesis/dissertation mentor'
            }
        };

        // Get user's program level (assuming it's available in the page)
        // This should be populated from the backend
        const userProgram = '{{ auth()->user()->scholarProfile->program ?? "masters" }}';
        const isDoctoralProgram = userProgram.toLowerCase().includes('doctoral') ||
                                 userProgram.toLowerCase().includes('phd') ||
                                 userProgram.toLowerCase().includes('doctor');
        const programType = isDoctoralProgram ? 'doctoral' : 'masters';

        // Multi-step form functionality
        const steps = ['step-1', 'step-2', 'step-3'];
        let currentStep = 0;

        // Enhanced amount validation
        function validateAmount() {
            const requestTypeSelect = document.getElementById('request_type_id');
            const amountInput = document.getElementById('amount');
            const amountLimitInfo = document.getElementById('amount-limit-info');
            const amountWarning = document.getElementById('amount-warning');
            const amountError = document.getElementById('amount-error');
            const amountWarningText = document.getElementById('amount-warning-text');
            const amountErrorText = document.getElementById('amount-error-text');

            if (!requestTypeSelect.value || !amountInput.value) {
                amountLimitInfo.textContent = '';
                amountWarning.classList.add('hidden');
                amountError.classList.add('hidden');
                return true;
            }

            const requestTypeId = parseInt(requestTypeSelect.value);
            const amount = parseFloat(amountInput.value);
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
            amountInput.classList.remove('border-red-500', 'border-amber-500');
            amountInput.classList.add('border-gray-300');

            // Display limit information
            if (limit[programType] === null) {
                amountLimitInfo.textContent = `${limit.name}: ${limit.description}`;
                amountLimitInfo.className = 'text-xs text-blue-600 mb-1 font-medium h-5';
                return true;
            } else {
                const limitAmount = limit[programType];
                amountLimitInfo.textContent = `${limit.name}: Maximum ₱${limitAmount.toLocaleString()} for ${programType} program`;

                // Validation logic
                if (amount > limitAmount) {
                    amountError.classList.remove('hidden');
                    amountErrorText.textContent = `Amount exceeds maximum limit of ₱${limitAmount.toLocaleString()}`;
                    amountInput.classList.remove('border-gray-300');
                    amountInput.classList.add('border-red-500');
                    amountLimitInfo.className = 'text-xs text-red-600 mb-1 font-medium h-5';
                    return false;
                } else if (amount > limitAmount * 0.8) {
                    // Warning when approaching limit (80% of max)
                    amountWarning.classList.remove('hidden');
                    amountWarningText.textContent = `Amount is approaching the maximum limit (${((amount/limitAmount)*100).toFixed(1)}% of limit)`;
                    amountInput.classList.remove('border-gray-300');
                    amountInput.classList.add('border-amber-500');
                    amountLimitInfo.className = 'text-xs text-amber-600 mb-1 font-medium h-5';
                } else {
                    amountLimitInfo.className = 'text-xs text-green-600 mb-1 font-medium h-5';
                    amountInput.classList.remove('border-gray-300');
                    amountInput.classList.add('border-green-500');
                }
                return true;
            }
        }

        // Enhanced input validation
        function validateInput(input, type) {
            const value = input.value.trim();

            switch(type) {
                case 'amount':
                    // Remove any non-numeric characters except decimal point
                    const cleanValue = value.replace(/[^0-9.]/g, '');
                    if (cleanValue !== value) {
                        input.value = cleanValue;
                    }

                    // Ensure only one decimal point
                    const parts = cleanValue.split('.');
                    if (parts.length > 2) {
                        input.value = parts[0] + '.' + parts.slice(1).join('');
                    }

                    // Limit to 2 decimal places
                    if (parts[1] && parts[1].length > 2) {
                        input.value = parts[0] + '.' + parts[1].substring(0, 2);
                    }

                    validateAmount();
                    break;
            }
        }

        // Event listeners for validation
        const requestTypeSelect = document.getElementById('request_type_id');
        const amountInput = document.getElementById('amount');

        if (requestTypeSelect) {
            requestTypeSelect.addEventListener('change', function() {
                validateAmount();
                updateReviewSection();
            });
        }

        if (amountInput) {
            amountInput.addEventListener('input', function() {
                validateInput(this, 'amount');
                updateReviewSection();
            });
            amountInput.addEventListener('blur', validateAmount);
            amountInput.addEventListener('paste', function(e) {
                setTimeout(() => validateInput(this, 'amount'), 0);
            });
        }

        function showStep(stepIndex) {
            steps.forEach((stepId, index) => {
                const stepElement = document.getElementById(stepId);
                if (index === stepIndex) {
                    stepElement.classList.remove('hidden');
                } else {
                    stepElement.classList.add('hidden');
                }
            });
            currentStep = stepIndex;
        }

        function validateStep1() {
            const requestType = document.getElementById('request_type_id').value;
            const amount = document.getElementById('amount').value;

            if (!requestType || !amount) {
                alert('Please fill in all required fields in Step 1.');
                return false;
            }

            // Enhanced amount validation
            if (!validateAmount()) {
                alert('Please correct the amount before proceeding.');
                return false;
            }

            return true;
        }

        function updateReviewSection() {
            const requestTypeSelect = document.getElementById('request_type_id');
            const requestTypeText = requestTypeSelect.options[requestTypeSelect.selectedIndex].text;
            const amount = document.getElementById('amount').value;
            const fileInput = document.getElementById('dropzone-file');
            const fileName = fileInput.files.length > 0 ? fileInput.files[0].name : 'No document uploaded';

            document.getElementById('review-request-type').textContent = requestTypeText;
            document.getElementById('review-amount').textContent = '₱' + parseFloat(amount).toLocaleString();
            document.getElementById('review-document').textContent = fileName;
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
            privacyModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closePrivacyModal() {
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

        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                if (fileInput.files && fileInput.files[0] && !privacyCheckbox.checked) {
                    openPrivacyModal();
                }
            });
        }

        // File upload handling
        const infoDiv = document.getElementById('selected-file-info');

        function getFileIcon(type) {
            if (type === 'application/pdf') {
                return '<i class="fas fa-file-pdf text-red-500 text-2xl mr-2"></i>';
            } else {
                return '<i class="fas fa-file-alt text-gray-400 text-2xl mr-2"></i>';
            }
        }

        function clearFile() {
            fileInput.value = '';
            infoDiv.innerHTML = '';
        }

        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                infoDiv.innerHTML = '';
                if (fileInput.files && fileInput.files[0]) {
                    const file = fileInput.files[0];
                    const card = document.createElement('div');
                    card.className = 'flex flex-col sm:flex-row items-center gap-2 sm:gap-4 bg-white border border-blue-200 rounded-lg p-2 sm:p-3 shadow transition-all duration-300 animate-fade-in w-full overflow-x-auto';
                    card.style.marginBottom = '0.5rem';
                    card.innerHTML = `
                    <span>${getFileIcon(file.type)}</span>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-800 text-sm">${file.name}</div>
                        <div class="text-xs text-gray-500">${(file.size/1024).toFixed(1)} KB</div>
                    </div>
                    <button type="button" title="Remove file" class="remove-file-btn text-gray-400 hover:text-red-500 p-1 rounded transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-red-300">
                        <i class="fas fa-times"></i>
                    </button>
                `;

                    const removeBtn = card.querySelector('.remove-file-btn');
                    removeBtn.addEventListener('click', clearFile);

                    infoDiv.appendChild(card);
                }
            });
        }

        // Initialize first step, validation and review section
        showStep(0);
        validateAmount();
        updateReviewSection();
    });
</script>

@endsection
