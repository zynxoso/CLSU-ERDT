@extends('layouts.app')

@section('title', 'Create Fund Request')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto">
        <div class="mb-6">
            <!-- <a href="{{ route('scholar.fund-requests') }}"
               class="group w-44 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 shadow-sm flex items-center justify-center border border-gray-200 transition-all duration-300 transform hover:scale-105 hover:shadow-md">
                <i class="fas fa-arrow-left mr-2 group-hover:translate-x-[-3px] transition-transform duration-300"></i>
                <span>Back to List</span>
            </a> -->
            <h1 class="text-2xl font-bold text-black mt-2">Create New Fund Request</h1>
        </div>

        <!-- Multi-step Progress Indicator -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-8 border border-green-100 shadow-sm">
                <div class="text-center mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Fund Request Submission</h3>
                    <p class="text-sm text-gray-600">Complete the steps below to submit your fund request</p>
                </div>

                <div class="flex items-center justify-between relative">
                    <!-- Step 1: Request Details -->
                    <div class="flex flex-col items-center text-center relative z-10">
                        <div class="relative mb-4">
                            <div id="step-indicator-1" class="w-16 h-16 rounded-full bg-blue-600 text-white flex items-center justify-center text-xl font-bold shadow-lg transition-all duration-300 border-4 border-white">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="absolute -top-1 -right-1 w-6 h-6 bg-green-600 rounded-full flex items-center justify-center opacity-0 transition-opacity duration-300" id="step-check-1">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="max-w-24">
                            <div class="text-sm font-semibold text-blue-600 mb-1">Request Details</div>
                            <div class="text-xs text-gray-500">Type & Amount</div>
                        </div>
                    </div>

                    <!-- Progress Line 1 -->
                    <div class="flex-1 mx-6 relative">
                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div id="progress-line-1" class="h-full bg-gradient-to-r from-blue-600 to-green-600 rounded-full transition-all duration-700 ease-in-out transform origin-left" style="width: 0%; transform: scaleX(0)"></div>
                        </div>
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                            <div class="w-3 h-3 bg-white border-2 border-gray-300 rounded-full transition-all duration-300" id="progress-dot-1"></div>
                        </div>
                    </div>

                    <!-- Step 2: Supporting Documents -->
                    <div class="flex flex-col items-center text-center relative z-10">
                        <div class="relative mb-4">
                            <div id="step-indicator-2" class="w-16 h-16 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center text-xl font-bold shadow-lg transition-all duration-300 border-4 border-white">
                                <i class="fas fa-upload"></i>
                            </div>
                            <div class="absolute -top-1 -right-1 w-6 h-6 bg-green-600 rounded-full flex items-center justify-center opacity-0 transition-opacity duration-300" id="step-check-2">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="max-w-24">
                            <div class="text-sm font-semibold text-gray-500 mb-1">Documents</div>
                            <div class="text-xs text-gray-400">Upload Files</div>
                        </div>
                    </div>

                    <!-- Progress Line 2 -->
                    <div class="flex-1 mx-6 relative">
                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div id="progress-line-2" class="h-full bg-gradient-to-r from-blue-600 to-green-600 rounded-full transition-all duration-700 ease-in-out transform origin-left" style="width: 0%; transform: scaleX(0)"></div>
                        </div>
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                            <div class="w-3 h-3 bg-white border-2 border-gray-300 rounded-full transition-all duration-300" id="progress-dot-2"></div>
                        </div>
                    </div>

                    <!-- Step 3: Review & Submit -->
                    <div class="flex flex-col items-center text-center relative z-10">
                        <div class="relative mb-4">
                            <div id="step-indicator-3" class="w-16 h-16 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center text-xl font-bold shadow-lg transition-all duration-300 border-4 border-white">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <div class="absolute -top-1 -right-1 w-6 h-6 bg-green-600 rounded-full flex items-center justify-center opacity-0 transition-opacity duration-300" id="step-check-3">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                        </div>
                        <div class="max-w-24">
                            <div class="text-sm font-semibold text-gray-500 mb-1">Review & Submit</div>
                            <div class="text-xs text-gray-400">Final Check</div>
                        </div>
                    </div>
                </div>

                <!-- Progress Status Text -->
                <div class="mt-6 text-center">
                    <div id="progress-status" class="text-sm text-gray-600 font-medium">
                        Step 1 of 3: Enter your request details
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <div class="mb-5 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">Important Information</h3>
                <p class="text-sm text-blue-700">Your fund request will be reviewed by an administrator after submission.</p>
                <p class="text-sm text-blue-700 mt-1">Please note that each request type has a maximum allowable amount based on your program level (Master's or Doctoral).</p>
                <p class="text-sm text-blue-700 mt-1"><strong>Eligibility:</strong> Fund requests are only available for PhD and Master's level scholars.</p>
            </div>

            <form action="{{ route('scholar.fund-requests.store') }}" method="POST" enctype="multipart/form-data" id="multi-step-form">
                @csrf

                <!-- Step 1: Request Details -->
                <div id="step-1" class="step-content">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Step 1: Request Details</h2>

                    <div class="mb-4">
                        <label for="request_type_id" class="block text-sm font-medium text-gray-700 mb-1">Request Type <span class="text-red-500">*</span></label>
                        <select id="request_type_id" name="request_type_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200" required>
                            <option value="">Select Request Type</option>
                            @foreach($requestTypes as $type)
                                <option value="{{ $type->id }}" {{ old('request_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('request_type_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount (₱) <span class="text-red-500">*</span></label>
                        <div class="relative mb-1">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
{{--                            <span class="text-gray-500 text-base">₱</span>--}}
                            </div>
                            <input type="number" id="amount" name="amount" value="{{ old('amount') }}" step="0.01" min="0" class="w-full border border-gray-300 rounded-md pl-7 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200" required>
                        </div>
                        <div id="amount-limit-info" class="text-xs text-blue-600 mb-1 font-medium h-5"></div>
                        @error('amount')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1">Purpose/Description <span class="text-red-500">*</span></label>
                        <textarea id="purpose" name="purpose" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200" placeholder="Please provide a detailed description of the purpose for this fund request..." required>{{ old('purpose') }}</textarea>
                        @error('purpose')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="button" id="next-step-1" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            Next: Documents <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Supporting Documents -->
                <div id="step-2" class="step-content hidden">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Step 2: Supporting Documents</h2>

                    <div class="mb-4">
                        <label for="dropzone-file" class="block text-sm font-medium text-gray-700 mb-1">Supporting Document <span class="text-gray-500">(Optional)</span></label>
                        <div class="flex items-center justify-center w-full">
                            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full min-h-[8rem] sm:min-h-[12rem] md:min-h-[16rem] border-2 border-green-300 border-dashed rounded-lg cursor-pointer bg-green-50 hover:bg-green-100 transition-colors duration-200 px-2 sm:px-6">
                                <div class="flex flex-col items-center justify-center pt-4 sm:pt-5 pb-4 sm:pb-6 w-full">
                                    <svg class="w-10 h-10 sm:w-12 sm:h-12 mb-3 sm:mb-4 text-green-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="mb-2 text-sm sm:text-base text-green-600 text-center"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs sm:text-sm text-green-500 text-center">PDF only (Max. 10MB)</p>
                                </div>
                                <input id="dropzone-file" name="document" type="file" class="hidden" accept=".pdf">
                            </label>
                        </div>
                        <div id="selected-file-info" class="mt-4"></div>
                        <p class="text-xs text-gray-500 mt-1">Upload supporting documents like registration forms, receipts, or other relevant files in PDF format only.</p>
                    </div>

                    <!-- Data Privacy Agreement -->
                    <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <div class="flex items-start">
                            <input type="checkbox" id="privacy-agreement-checkbox" name="privacy_agreement" class="mt-1 mr-3" required>
                            <div class="flex-1">
                                <label for="privacy-agreement-checkbox" class="text-sm text-gray-700">
                                    I agree to the <button type="button" id="open-privacy-modal" class="text-blue-600 hover:text-blue-800 underline transition-colors duration-150">Data Privacy Agreement</button> for document uploads.
                                </label>
                                <div id="privacy-agreement-error" class="text-red-500 text-xs mt-1 hidden">
                                    You must agree to the Data Privacy Agreement to upload documents.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <button type="button" id="prev-step-2" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all duration-200 shadow-md">
                            <i class="fas fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button type="button" id="next-step-2" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            Next: Review <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Review & Submit -->
                <div id="step-3" class="step-content hidden">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Step 3: Review & Submit</h2>

                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Review Your Request</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600">Request Type:</span>
                                <span id="review-request-type" class="text-sm text-gray-800"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-600">Amount:</span>
                                <span id="review-amount" class="text-sm text-gray-800 font-semibold"></span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-600">Purpose:</span>
                                <p id="review-purpose" class="text-sm text-gray-800 mt-1"></p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-600">Supporting Document:</span>
                                <p id="review-document" class="text-sm text-gray-800 mt-1"></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <button type="button" id="prev-step-3" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all duration-200 shadow-md">
                            <i class="fas fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button type="submit" class="px-6 py-2 bg-red-800 text-white rounded-lg hover:bg-red-900 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i class="fas fa-paper-plane mr-2"></i> Submit Request
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Data Privacy Agreement Modal -->
<div id="privacy-agreement-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Data Privacy Agreement</h3>
                <button type="button" id="close-privacy-modal-btn" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="max-h-[60vh] sm:max-h-[70vh] overflow-y-auto px-1 sm:px-2">
                <div class="prose prose-sm max-w-none text-gray-700">
                    <p class="mb-3">By uploading documents to the CLSU-ERDT Scholar Management System, you acknowledge and agree to the following:</p>

                    <h4 class="font-semibold text-gray-800 mt-4 mb-2">1. Information Collection and Use</h4>
                    <p>The documents you upload may contain personal information that will be collected and processed for the purpose of evaluating and processing your fund request. This information will be used solely for administrative purposes related to your scholarship.</p>

                    <h4 class="font-semibold text-gray-800 mt-4 mb-2">2. Data Storage and Security</h4>
                    <p>Your uploaded documents will be stored securely within our system. We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>

                    <h4 class="font-semibold text-gray-800 mt-4 mb-2">3. Data Sharing</h4>
                    <p>Your information may be shared with authorized personnel involved in the scholarship administration process, including but not limited to administrators, evaluators, and relevant institutional departments. Your information will not be shared with third parties outside of this context without your consent, unless required by law.</p>

                    <h4 class="font-semibold text-gray-800 mt-4 mb-2">4. Retention Period</h4>
                    <p>Your documents will be retained for the duration of your scholarship program and for a reasonable period thereafter for record-keeping purposes, after which they will be securely deleted or anonymized in accordance with our data retention policies.</p>

                    <h4 class="font-semibold text-gray-800 mt-4 mb-2">5. Your Rights</h4>
                    <p>You have the right to access, correct, or request deletion of your personal information. You may also withdraw your consent at any time, though this may affect our ability to process your fund request.</p>

                    <h4 class="font-semibold text-gray-800 mt-4 mb-2">6. Contact Information</h4>
                    <p>If you have any questions about this privacy agreement or our data handling practices, please contact the CLSU-ERDT administration office.</p>
                </div>
            </div>
            <div class="bg-gray-50 px-3 py-2 sm:px-4 sm:py-3 flex justify-end mt-4">
                <button type="button" id="agree-privacy-btn" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    I Agree
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Multi-step form functionality
    const steps = ['step-1', 'step-2', 'step-3'];
    let currentStep = 0;

    function updateProgressIndicator() {
        const totalSteps = 3;

        // Update step indicators and progress lines
        for (let i = 1; i <= totalSteps; i++) {
            const stepIndicator = document.getElementById(`step-indicator-${i}`);
            const stepCheck = document.getElementById(`step-check-${i}`);
            const progressStatus = document.getElementById('progress-status');

            if (i <= currentStep + 1) {
                if (i < currentStep + 1) {
                    // Completed step - green with check mark
                    stepIndicator.classList.remove('bg-gray-300', 'text-gray-500', 'bg-blue-600');
                    stepIndicator.classList.add('bg-green-500', 'text-white');
                    stepCheck.classList.remove('opacity-0');
                    stepCheck.classList.add('opacity-100');

                    // Update text colors for completed steps
                    const stepContainer = stepIndicator.closest('.flex.flex-col');
                    const stepTitle = stepContainer.querySelector('.text-sm.font-semibold');
                    const stepSubtitle = stepContainer.querySelector('.text-xs');
                    stepTitle.classList.remove('text-gray-500', 'text-blue-600');
                    stepTitle.classList.add('text-green-600');
                    stepSubtitle.classList.remove('text-gray-400');
                    stepSubtitle.classList.add('text-green-500');
                } else {
                    // Current active step - blue
                    stepIndicator.classList.remove('bg-gray-300', 'text-gray-500', 'bg-green-500');
                    stepIndicator.classList.add('bg-blue-600', 'text-white');
                    stepCheck.classList.remove('opacity-100');
                    stepCheck.classList.add('opacity-0');

                    // Update text colors for active step
                    const stepContainer = stepIndicator.closest('.flex.flex-col');
                    const stepTitle = stepContainer.querySelector('.text-sm.font-semibold');
                    const stepSubtitle = stepContainer.querySelector('.text-xs');
                    stepTitle.classList.remove('text-gray-500', 'text-green-600');
                    stepTitle.classList.add('text-blue-600');
                    stepSubtitle.classList.remove('text-gray-400', 'text-green-500');
                    stepSubtitle.classList.add('text-gray-500');
                }
            } else {
                // Future inactive step - gray
                stepIndicator.classList.remove('bg-green-500', 'bg-blue-600', 'text-white');
                stepIndicator.classList.add('bg-gray-300', 'text-gray-500');
                stepCheck.classList.remove('opacity-100');
                stepCheck.classList.add('opacity-0');

                // Update text colors for inactive steps
                const stepContainer = stepIndicator.closest('.flex.flex-col');
                const stepTitle = stepContainer.querySelector('.text-sm.font-semibold');
                const stepSubtitle = stepContainer.querySelector('.text-xs');
                stepTitle.classList.remove('text-green-600', 'text-blue-600');
                stepTitle.classList.add('text-gray-500');
                stepSubtitle.classList.remove('text-gray-500', 'text-green-500');
                stepSubtitle.classList.add('text-gray-400');
            }
        }

        // Update progress lines with animations
        for (let i = 1; i <= totalSteps - 1; i++) {
            const progressLine = document.getElementById(`progress-line-${i}`);
            const progressDot = document.getElementById(`progress-dot-${i}`);

            if (i < currentStep + 1) {
                // Completed line - full progress with green color
                progressLine.style.width = '100%';
                progressLine.style.transform = 'scaleX(1)';
                if (progressDot) {
                    progressDot.classList.remove('border-gray-300');
                    progressDot.classList.add('border-green-500', 'bg-green-500');
                }
            } else if (i === currentStep + 1) {
                // Current line - partial progress with blue color
                progressLine.style.width = '50%';
                progressLine.style.transform = 'scaleX(0.5)';
                if (progressDot) {
                    progressDot.classList.remove('border-gray-300', 'border-green-500', 'bg-green-500');
                    progressDot.classList.add('border-blue-500', 'bg-blue-500');
                }
            } else {
                // Inactive line - no progress
                progressLine.style.width = '0%';
                progressLine.style.transform = 'scaleX(0)';
                if (progressDot) {
                    progressDot.classList.remove('border-blue-500', 'bg-blue-500', 'border-green-500', 'bg-green-500');
                    progressDot.classList.add('border-gray-300');
                }
            }
        }

        // Update status text
        const statusMessages = [
            'Step 1 of 3: Enter your request details',
            'Step 2 of 3: Upload supporting documents',
            'Step 3 of 3: Review and submit your request'
        ];

        if (progressStatus && statusMessages[currentStep]) {
            progressStatus.textContent = statusMessages[currentStep];
        }
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
        updateProgressIndicator();
    }

    function validateStep1() {
        const requestType = document.getElementById('request_type_id').value;
        const amount = document.getElementById('amount').value;
        const purpose = document.getElementById('purpose').value;

        if (!requestType || !amount || !purpose) {
            alert('Please fill in all required fields in Step 1.');
            return false;
        }
        return true;
    }

    function updateReviewSection() {
        const requestTypeSelect = document.getElementById('request_type_id');
        const requestTypeText = requestTypeSelect.options[requestTypeSelect.selectedIndex].text;
        const amount = document.getElementById('amount').value;
        const purpose = document.getElementById('purpose').value;
        const fileInput = document.getElementById('dropzone-file');
        const fileName = fileInput.files.length > 0 ? fileInput.files[0].name : 'No document uploaded';

        document.getElementById('review-request-type').textContent = requestTypeText;
        document.getElementById('review-amount').textContent = '₱' + parseFloat(amount).toLocaleString();
        document.getElementById('review-purpose').textContent = purpose.substring(0, 100) + (purpose.length > 100 ? '...' : '');
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
        fileInput.addEventListener('change', function (e) {
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

    // Initialize first step
    showStep(0);
});
</script>

@endsection
