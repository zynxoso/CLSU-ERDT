@extends('layouts.app')

@section('title', 'Create Manuscript')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Create New Manuscript</h1>
</div>

@if ($errors->any())
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <i class="fas fa-exclamation-circle text-red-500"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-red-800">There were {{ $errors->count() }} errors with your submission</h3>
            <div class="mt-2 text-sm text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endif

<div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
    <form id="manuscriptForm" action="{{ route('scholar.manuscripts.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirmSubmission(event)">
        @csrf

        <!-- Process Tracker -->
        <div class="mb-8">
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <div class="text-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Manuscript Submission Progress</h3>
                    <p class="text-sm text-gray-600">Complete all steps to submit your manuscript</p>
                </div>

                <div class="flex items-center justify-between relative">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center text-center flex-1">
                        <div class="relative">
                            <div id="step1Indicator" class="w-12 h-12 rounded-full bg-green-600 text-white flex items-center justify-center text-lg font-semibold shadow-lg transition-all duration-300 hover:bg-green-700">
                                1
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="text-sm font-semibold text-green-800">Data Privacy Agreement</div>
                            <div class="text-xs text-gray-500 mt-1">Terms & Conditions</div>
                        </div>
                    </div>

                    <!-- Progress Line 1 -->
                    <div class="flex-1 mx-4">
                        <div id="progressLine1" class="h-1 bg-gray-300 rounded-full relative overflow-hidden">
                            <div class="h-full bg-green-600 rounded-full transition-all duration-500 ease-in-out w-0"></div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex flex-col items-center text-center flex-1">
                        <div class="relative">
                            <div id="step2Indicator" class="w-12 h-12 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center text-lg font-semibold shadow-lg transition-all duration-300">
                                2
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="text-sm font-semibold text-gray-500">Manuscript Details</div>
                            <div class="text-xs text-gray-400 mt-1">Basic Information</div>
                        </div>
                    </div>

                    <!-- Progress Line 2 -->
                    <div class="flex-1 mx-4">
                        <div id="progressLine2" class="h-1 bg-gray-300 rounded-full relative overflow-hidden">
                            <div class="h-full bg-green-600 rounded-full transition-all duration-500 ease-in-out w-0"></div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex flex-col items-center text-center flex-1">
                        <div class="relative">
                            <div id="step3Indicator" class="w-12 h-12 rounded-full bg-gray-300 text-gray-500 flex items-center justify-center text-lg font-semibold shadow-lg transition-all duration-300">
                                3
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="text-sm font-semibold text-gray-500">Upload Manuscript</div>
                            <div class="text-xs text-gray-400 mt-1">File Upload</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 1: Data Privacy Agreement -->
        <div id="step1" class="step">
            <div class="mb-4">
                <p class="text-gray-700 mb-4">Please read and agree to the Data Privacy Agreement before proceeding.</p>
                <div class="border p-4 rounded-md bg-gray-50 text-sm text-gray-600 mb-4" style="max-height: 200px; overflow-y: auto;">
                    By submitting your manuscript, you consent to the collection, storage, and processing of your personal and manuscript data in accordance with our data privacy policies. Your information will be used solely for manuscript review and publication purposes and will not be shared with unauthorized third parties. You have the right to access, correct, or request deletion of your data at any time.
                </div>
                <div class="flex items-center">
                    <input id="privacy-agreement-checkbox" type="checkbox" class="h-4 w-4 text-primary-500 focus:ring-primary-500 border-gray-300 rounded">
                    <label for="privacy-agreement-checkbox" class="ml-2 block text-sm text-gray-700">
                        I agree to the Data Privacy Agreement
                    </label>
                </div>
                <p id="privacy-agreement-error" class="text-red-500 text-xs mt-1 hidden">You must agree to the Data Privacy Agreement to proceed.</p>
            </div>
            <div class="flex justify-end">
                <button type="button" id="nextToStep2" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                    Next
                </button>
            </div>
        </div>

        <!-- Step 2: Manuscript Details -->
        <div id="step2" class="step hidden">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                    Manuscript Title <span class="text-red-500">*</span>
                </label>
                <input type="text" id="title" name="title" value="{{ old('title') }}"
                    class="w-full bg-white border @error('title') border-red-300 @else border-gray-300 @enderror rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500" required>
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Enter the complete title of your manuscript</p>
            </div>

            <div class="mb-4">
                <label for="manuscript_type" class="block text-sm font-medium text-gray-700 mb-1">
                    Manuscript Type <span class="text-red-500">*</span>
                </label>
                <select id="manuscript_type" name="manuscript_type"
                    class="w-full bg-white border @error('manuscript_type') border-red-300 @else border-gray-300 @enderror rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500" required>
                    <option value="">Select Type</option>
                    <option value="Outline" {{ old('manuscript_type') == 'Outline' ? 'selected' : '' }}>Outline</option>
                    <option value="Final" {{ old('manuscript_type') == 'Final' ? 'selected' : '' }}>Final</option>
                </select>
                @error('manuscript_type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="co_authors" class="block text-sm font-medium text-gray-700 mb-1">
                    Co-Authors
                </label>
                <input type="text" id="co_authors" name="co_authors" value="{{ old('co_authors') }}"
                    class="w-full bg-white border @error('co_authors') border-red-300 @else border-gray-300 @enderror rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                @error('co_authors')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Separate multiple authors with commas</p>
            </div>

            <div class="mb-4">
                <label for="abstract" class="block text-sm font-medium text-gray-700 mb-1">
                    Abstract <span class="text-red-500">*</span>
                </label>
                <textarea id="abstract" name="abstract" rows="4"
                    class="w-full bg-white border @error('abstract') border-red-300 @else border-gray-300 @enderror rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500" required>{{ old('abstract') }}</textarea>
                @error('abstract')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Provide a brief summary of your manuscript (250-300 words recommended)</p>
            </div>

            <div class="flex justify-between">
                <button type="button" id="backToStep1" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition-colors duration-200">
                    Back
                </button>
                <button type="button" id="nextToStep3" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                    Next
                </button>
            </div>
        </div>

        <!-- Step 3: Upload Manuscript -->
        <div id="step3" class="step hidden">
            <div class="mb-4">
                <label for="dropzone-file" class="block text-sm font-medium text-gray-700 mb-1">
                    Upload Manuscript File (PDF) <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center justify-center w-full">
                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full min-h-[8rem] sm:min-h-[12rem] md:min-h-[16rem] border-2 border-green-300 border-dashed rounded-lg cursor-pointer bg-green-50 hover:bg-green-100 transition-colors duration-200 px-2 sm:px-6">
                        <div class="flex flex-col items-center justify-center pt-4 sm:pt-5 pb-4 sm:pb-6 w-full">
                            <svg class="w-10 h-10 sm:w-12 sm:h-12 mb-3 sm:mb-4 text-green-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="mb-2 text-sm sm:text-base text-green-800 text-center"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs sm:text-sm text-green-600 text-center">PDF Only (Max. 10MB)</p>
                        </div>
                        <input id="dropzone-file" name="file" type="file" class="hidden" accept=".pdf" required>
                    </label>
                </div>
                <div id="selected-file-info" class="mt-4"></div>
                <p class="text-xs text-gray-500 mt-1">Upload your manuscript file in PDF format. Maximum file size: 10MB.</p>
            </div>

            <div class="mb-4">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                    Additional Notes
                </label>
                <textarea id="notes" name="notes" rows="3"
                    class="w-full bg-white border @error('notes') border-red-300 @else border-gray-300 @enderror rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Include any additional information or special instructions about your manuscript</p>
            </div>

            <div class="flex justify-between">
                <button type="button" id="backToStep2" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                    Back
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-save mr-2"></i> Save Manuscript
                </button>
            </div>
        </div>
    </form>
</div>


<script>
    function confirmSubmission(event) {
        event.preventDefault();

        // Basic client-side validation before submission
        const fileInput = document.getElementById('dropzone-file');
        const titleInput = document.getElementById('title');
        const abstractInput = document.getElementById('abstract');
        const manuscriptTypeInput = document.getElementById('manuscript_type');

        let validationErrors = [];

        // Check required fields
        if (!titleInput.value.trim()) {
            validationErrors.push('Manuscript title is required');
        }

        if (!abstractInput.value.trim()) {
            validationErrors.push('Abstract is required');
        }

        if (!manuscriptTypeInput.value) {
            validationErrors.push('Manuscript type is required');
        }

        if (!fileInput.files || fileInput.files.length === 0) {
            validationErrors.push('Please upload a manuscript file');
        } else {
            // Basic file validation
            const file = fileInput.files[0];
            const fileExt = file.name.split('.').pop().toLowerCase();

            if (fileExt !== 'pdf') {
                validationErrors.push('Only PDF files are allowed');
            }

            if (file.size > 10485760) {
                validationErrors.push('File size must not exceed 10MB');
            }
        }

        if (validationErrors.length > 0) {
            Swal.fire({
                title: 'Validation Error',
                html: '<div class="text-left"><p class="mb-2">Please fix the following issues:</p><ul class="list-disc pl-5">' +
                      validationErrors.map(error => `<li>${error}</li>`).join('') + '</ul></div>',
                icon: 'error',
                confirmButtonColor: 'rgb(220 38 38)',
                confirmButtonText: 'OK'
            });
            return false;
        }

        Swal.fire({
            title: 'Submit Manuscript?',
            text: "This will submit your manuscript for review. Please ensure all information is correct.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: 'rgb(34 197 94)',
            cancelButtonColor: 'rgb(221 51 51)',
            confirmButtonText: 'Submit manuscript!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('manuscriptForm').submit();
            }
        });
        return false;
    }

    function updateProgressTracker(currentStep) {
        // Update step indicators
        for (let i = 1; i <= 3; i++) {
            const stepIndicator = document.getElementById(`step${i}Indicator`);
            const stepContainer = stepIndicator.closest('.flex-1');
            const stepTitle = stepContainer.querySelector('.text-sm');
            const stepSubtitle = stepContainer.querySelector('.text-xs');

            if (i < currentStep) {
                // Completed step - show checkmark with green background
                stepIndicator.classList.remove('bg-gray-300', 'text-gray-500', 'bg-green-600');
                stepIndicator.classList.add('bg-green-600', 'text-white');
                stepIndicator.innerHTML = '<i class="fas fa-check"></i>';
                stepTitle.classList.remove('text-gray-500', 'text-green-800');
                stepTitle.classList.add('text-green-800');
                stepSubtitle.classList.remove('text-gray-400');
                stepSubtitle.classList.add('text-gray-500');
            } else if (i === currentStep) {
                // Current active step - show number with green background
                stepIndicator.classList.remove('bg-gray-300', 'text-gray-500', 'bg-green-600');
                stepIndicator.classList.add('bg-green-600', 'text-white');
                stepIndicator.innerHTML = i.toString();
                stepTitle.classList.remove('text-gray-500', 'text-green-800');
                stepTitle.classList.add('text-green-800');
                stepSubtitle.classList.remove('text-gray-400');
                stepSubtitle.classList.add('text-gray-500');
            } else {
                // Future inactive step
                stepIndicator.classList.remove('bg-green-600', 'text-white');
                stepIndicator.classList.add('bg-gray-300', 'text-gray-500');
                stepIndicator.innerHTML = i.toString();
                stepTitle.classList.remove('text-green-800');
                stepTitle.classList.add('text-gray-500');
                stepSubtitle.classList.remove('text-gray-500');
                stepSubtitle.classList.add('text-gray-400');
            }
        }

        // Update progress lines
        for (let i = 1; i <= 2; i++) {
            const progressLine = document.getElementById(`progressLine${i}`);
            const progressBar = progressLine.querySelector('div');

            if (i < currentStep) {
                // Completed line - full progress
                progressBar.classList.remove('w-0');
                progressBar.classList.add('w-full');
            } else {
                // Inactive line - no progress
                progressBar.classList.remove('w-full');
                progressBar.classList.add('w-0');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');

        const nextToStep2 = document.getElementById('nextToStep2');
        const backToStep1 = document.getElementById('backToStep1');
        const nextToStep3 = document.getElementById('nextToStep3');
        const backToStep2 = document.getElementById('backToStep2');

        // Initialize progress tracker
        updateProgressTracker(1);

        nextToStep2.addEventListener('click', function () {
            const privacyCheckbox = document.getElementById('privacy-agreement-checkbox');
            const privacyError = document.getElementById('privacy-agreement-error');
            if (!privacyCheckbox.checked) {
                privacyError.classList.remove('hidden');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'You must agree to the Data Privacy Agreement to proceed.',
                });
                return;
            } else {
                privacyError.classList.add('hidden');
            }

            step1.classList.add('hidden');
            step2.classList.remove('hidden');

            // Update progress tracker
            updateProgressTracker(2);
        });

        backToStep1.addEventListener('click', function () {
            step2.classList.add('hidden');
            step1.classList.remove('hidden');

            // Update progress tracker
            updateProgressTracker(1);
        });

        nextToStep3.addEventListener('click', function () {
            // Validate required fields in step 2
            const title = document.getElementById('title').value.trim();
            const abstract = document.getElementById('abstract').value.trim();
            const manuscriptType = document.getElementById('manuscript_type').value;

            if (!title || !abstract || !manuscriptType) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please fill in all required fields in Step 2.',
                });
                return;
            }

            step2.classList.add('hidden');
            step3.classList.remove('hidden');

            // Update progress tracker
            updateProgressTracker(3);
        });

        backToStep2.addEventListener('click', function () {
            step3.classList.add('hidden');
            step2.classList.remove('hidden');

            // Update progress tracker
            updateProgressTracker(2);
        });

        // File upload preview with basic validation
        const fileInput = document.getElementById('dropzone-file');
        const infoDiv = document.getElementById('selected-file-info');

        fileInput.addEventListener('change', function () {
            infoDiv.innerHTML = '';
            if (fileInput.files && fileInput.files[0]) {
                const file = fileInput.files[0];
                const fileName = file.name;
                const fileSize = file.size;
                const fileSizeKB = (fileSize / 1024).toFixed(1);
                const fileSizeMB = (fileSize / 1024 / 1024).toFixed(2);
                const fileExt = fileName.split('.').pop().toLowerCase();

                let validationErrors = [];

                // Basic validation
                // 1. File extension validation
                if (fileExt !== 'pdf') {
                    validationErrors.push('Only PDF files are allowed');
                }

                // 2. File size validation (10MB = 10485760 bytes)
                if (fileSize > 10485760) {
                    validationErrors.push('File size exceeds 10MB limit');
                }

                let fileIcon = '<i class="fas fa-file-pdf text-green-600 text-2xl"></i>';
                let statusClass = '';
                let statusIcon = '';

                if (validationErrors.length > 0) {
                    statusClass = 'border-red-300 bg-red-50';
                    statusIcon = '<i class="fas fa-exclamation-triangle text-red-600"></i>';
                } else {
                    statusClass = 'border-green-300 bg-green-50';
                    statusIcon = '<i class="fas fa-check-circle text-green-600"></i>';
                }

                let validationMessagesHtml = '';
                if (validationErrors.length > 0) {
                    validationMessagesHtml += '<div class="mt-2 text-sm text-red-700"><strong>Errors:</strong><ul class="list-disc pl-5 mt-1">';
                    validationErrors.forEach(error => {
                        validationMessagesHtml += `<li>${error}</li>`;
                    });
                    validationMessagesHtml += '</ul></div>';
                }

                infoDiv.innerHTML = `
                    <div class="flex items-start space-x-4 border ${statusClass} rounded-lg p-4">
                        <div class="flex-shrink-0">${fileIcon}</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900 truncate">${fileName}</p>
                                    <p class="text-sm text-gray-600">Size: ${fileSizeMB} MB (${fileSizeKB} KB)</p>
                                    <p class="text-xs text-gray-500">Type: ${fileExt.toUpperCase()}</p>
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    ${statusIcon}
                                    <button type="button" id="remove-file" class="text-red-600 hover:text-red-800 font-semibold text-sm">Remove</button>
                                </div>
                            </div>
                            ${validationMessagesHtml}
                            ${validationErrors.length === 0 ? '<div class="mt-2 text-sm text-green-700"><i class="fas fa-check mr-1"></i>File is ready for upload</div>' : ''}
                        </div>
                    </div>
                `;

                const removeBtn = document.getElementById('remove-file');
                removeBtn.addEventListener('click', function () {
                    fileInput.value = '';
                    infoDiv.innerHTML = '';
                });

                // Disable submit button if there are validation errors
                const submitBtn = document.querySelector('button[type="submit"]');
                if (validationErrors.length > 0) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i> Fix File Issues First';
                } else {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Save Manuscript';
                }
            }
        });
    });
</script>

<!-- Troubleshooting Guide Modal -->
<x-modal id="troubleshootingModal" title="File Upload Troubleshooting Guide" size="lg">
    <div class="max-h-96 overflow-y-auto">
        <div class="space-y-4">
            <div class="bg-red-50 border border-red-200 rounded-md p-4">
                <h4 class="font-semibold text-red-800 mb-2">🚫 Common Upload Errors</h4>
                <div class="space-y-2 text-sm text-red-700">
                    <div>
                        <strong>Error:</strong> "File upload blocked due to security policy violations"
                        <br><strong>Solution:</strong> Your PDF file failed security validation. Try these steps:
                        <ul class="list-disc pl-5 mt-1">
                            <li>Re-save your document as a new PDF using "Save as PDF" or "Export as PDF"</li>
                            <li>Ensure the file is not password protected</li>
                            <li>Use a simple filename with only letters, numbers, and hyphens</li>
                            <li>Avoid using online PDF converters - use your word processor's built-in PDF export</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                <h4 class="font-semibold text-yellow-800 mb-2">⚠️ File Format Issues</h4>
                <div class="space-y-2 text-sm text-yellow-700">
                    <div>
                        <strong>Problem:</strong> "MIME type does not match file extension"
                        <br><strong>Solution:</strong> The file may be corrupted or renamed. Create a fresh PDF:
                        <ul class="list-disc pl-5 mt-1">
                            <li>Open your document in Microsoft Word, Google Docs, or similar</li>
                            <li>Use "File → Save As" or "File → Download As" → PDF</li>
                            <li>Don't rename files from .doc to .pdf manually</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                <h4 class="font-semibold text-blue-800 mb-2">✅ Best Practices</h4>
                <div class="text-sm text-blue-700">
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Use Microsoft Word:</strong> File → Save As → PDF</li>
                        <li><strong>Use Google Docs:</strong> File → Download → PDF Document (.pdf)</li>
                        <li><strong>Use LibreOffice:</strong> File → Export as PDF</li>
                        <li><strong>Filename:</strong> Use "My_Research_Paper.pdf" format</li>
                        <li><strong>Size:</strong> Keep under 10MB (compress images if needed)</li>
                        <li><strong>Content:</strong> Ensure no embedded scripts or macros</li>
                    </ul>
                </div>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-md p-4">
                <h4 class="font-semibold text-green-800 mb-2">🔧 Still Having Issues?</h4>
                <div class="text-sm text-green-700">
                    <p class="mb-2">If you continue to experience upload problems:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Try using a different browser (Chrome, Firefox, Safari)</li>
                        <li>Clear your browser cache and cookies</li>
                        <li>Disable browser extensions temporarily</li>
                        <li>Check your internet connection stability</li>
                        <li>Contact technical support with the specific error message</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-modal>

<script>
// Troubleshooting modal functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add troubleshooting link to the form
    const troubleshootingLink = document.createElement('div');
    troubleshootingLink.className = 'mt-4 text-center';
    troubleshootingLink.innerHTML = `
        <button type="button" id="showTroubleshooting" class="text-secondary-500 hover:text-secondary-600 text-sm underline">
            <i class="fas fa-question-circle mr-1"></i>
            Having trouble uploading? Click here for help
        </button>
    `;

    // Insert after the form
    const form = document.getElementById('manuscriptForm');
    form.parentNode.insertBefore(troubleshootingLink, form.nextSibling);

    // Show modal using standardized modal system
    document.getElementById('showTroubleshooting').addEventListener('click', function() {
        openModal('troubleshootingModal');
    });
});
</script>

@endsection
