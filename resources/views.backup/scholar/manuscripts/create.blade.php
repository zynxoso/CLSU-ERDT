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
                            <div id="step1Indicator" class="w-12 h-12 rounded-full bg-red-800 text-white flex items-center justify-center text-lg font-semibold shadow-lg transition-all duration-300 hover:bg-red-900">
                                1
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="text-sm font-semibold text-red-800">Data Privacy Agreement</div>
                            <div class="text-xs text-gray-500 mt-1">Terms & Conditions</div>
                        </div>
                    </div>

                    <!-- Progress Line 1 -->
                    <div class="flex-1 mx-4">
                        <div id="progressLine1" class="h-1 bg-gray-300 rounded-full relative overflow-hidden">
                            <div class="h-full bg-green-600 rounded-full transition-all duration-500 ease-in-out" style="width: 0%"></div>
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
                            <div class="h-full bg-green-600 rounded-full transition-all duration-500 ease-in-out" style="width: 0%"></div>
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
                    <input id="privacy-agreement-checkbox" type="checkbox" class="h-4 w-4 text-red-800 focus:ring-red-700 border-gray-300 rounded">
                    <label for="privacy-agreement-checkbox" class="ml-2 block text-sm text-gray-700">
                        I agree to the Data Privacy Agreement
                    </label>
                </div>
                <p id="privacy-agreement-error" class="text-red-500 text-xs mt-1 hidden">You must agree to the Data Privacy Agreement to proceed.</p>
            </div>
            <div class="flex justify-end">
                <button type="button" id="nextToStep2" class="px-4 py-2 bg-red-800 text-white rounded-lg hover:bg-red-900 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
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
                    class="w-full bg-white border @error('title') border-red-300 @else border-gray-300 @enderror rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-700" required>
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
                    class="w-full bg-white border @error('manuscript_type') border-red-300 @else border-gray-300 @enderror rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-700" required>
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
                    class="w-full bg-white border @error('co_authors') border-red-300 @else border-gray-300 @enderror rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-700">
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
                    class="w-full bg-white border @error('abstract') border-red-300 @else border-gray-300 @enderror rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-700" required>{{ old('abstract') }}</textarea>
                @error('abstract')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Provide a brief summary of your manuscript (250-300 words recommended)</p>
            </div>

            <div class="flex justify-between">
                <button type="button" id="backToStep1" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition-colors duration-200">
                    Back
                </button>
                <button type="button" id="nextToStep3" class="px-4 py-2 bg-red-800 text-white rounded-lg hover:bg-red-900 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                    Next
                </button>
            </div>
        </div>

        <!-- Step 3: Upload Manuscript -->
        <div id="step3" class="step hidden">
            <div class="mb-4">
                <label for="dropzone-file" class="block text-sm font-medium text-gray-700 mb-1">Upload Manuscript File (PDF)</label>
                <div class="flex items-center justify-center w-full">
                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full min-h-[8rem] sm:min-h-[12rem] md:min-h-[16rem] border-2 border-red-300 border-dashed rounded-lg cursor-pointer bg-red-50 hover:bg-red-100 transition-colors duration-200 px-2 sm:px-6">
                        <div class="flex flex-col items-center justify-center pt-4 sm:pt-5 pb-4 sm:pb-6 w-full">
                            <svg class="w-10 h-10 sm:w-12 sm:h-12 mb-3 sm:mb-4 text-red-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="mb-2 text-sm sm:text-base text-red-700 text-center"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs sm:text-sm text-red-600 text-center">PDF (Max. 10MB)</p>
                        </div>
                        <input id="dropzone-file" name="file" type="file" class="hidden" accept=".pdf">
                    </label>
                </div>
                <div id="selected-file-info" class="mt-4"></div>
                <p class="text-xs text-gray-500 mt-1">Upload supporting documents like registration forms, receipts, or other relevant files.</p>
            </div>

            <div class="mb-4">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                    Additional Notes
                </label>
                <textarea id="notes" name="notes" rows="3"
                    class="w-full bg-white border @error('notes') border-red-300 @else border-gray-300 @enderror rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-700">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Include any additional information or special instructions</p>
            </div>

            <div class="flex justify-between">
                <button type="button" id="backToStep2" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                    Back
                </button>
                <button type="submit" class="px-4 py-2 bg-red-800 text-white rounded-lg hover:bg-red-900">
                    <i class="fas fa-save mr-2"></i> Save Manuscript
                </button>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmSubmission(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Save Manuscript as Draft?',
            text: "This will save your manuscript as a draft. You can edit and submit it for review later.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#7f1d1d',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, save as draft!'
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
                stepIndicator.classList.remove('bg-gray-300', 'text-gray-500', 'bg-red-800');
                stepIndicator.classList.add('bg-green-600', 'text-white');
                stepIndicator.innerHTML = '<i class="fas fa-check"></i>';
                stepTitle.classList.remove('text-gray-500', 'text-red-800');
                stepTitle.classList.add('text-green-600');
                stepSubtitle.classList.remove('text-gray-400');
                stepSubtitle.classList.add('text-gray-500');
            } else if (i === currentStep) {
                // Current active step - show number with red background
                stepIndicator.classList.remove('bg-gray-300', 'text-gray-500', 'bg-green-600');
                stepIndicator.classList.add('bg-red-800', 'text-white');
                stepIndicator.innerHTML = i.toString();
                stepTitle.classList.remove('text-gray-500', 'text-green-600');
                stepTitle.classList.add('text-red-800');
                stepSubtitle.classList.remove('text-gray-400');
                stepSubtitle.classList.add('text-gray-500');
            } else {
                // Future inactive step
                stepIndicator.classList.remove('bg-green-600', 'bg-red-800', 'text-white');
                stepIndicator.classList.add('bg-gray-300', 'text-gray-500');
                stepIndicator.innerHTML = i.toString();
                stepTitle.classList.remove('text-green-600', 'text-red-800');
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
                progressBar.style.width = '100%';
            } else {
                // Inactive line - no progress
                progressBar.style.width = '0%';
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

        const step1Indicator = document.getElementById('step1Indicator');
        const step2Indicator = document.getElementById('step2Indicator');
        const step3Indicator = document.getElementById('step3Indicator');

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

        // File upload preview
        const fileInput = document.getElementById('dropzone-file');
        const infoDiv = document.getElementById('selected-file-info');

        fileInput.addEventListener('change', function () {
            infoDiv.innerHTML = '';
            if (fileInput.files && fileInput.files[0]) {
                const file = fileInput.files[0];
                const fileName = file.name;
                const fileSize = (file.size / 1024).toFixed(1); // KB
                const fileExt = fileName.split('.').pop().toLowerCase();

                let fileIcon = '';
                if (['pdf'].includes(fileExt)) {
                    fileIcon = '<i class="fas fa-file-pdf text-red-600 text-2xl"></i>';
                } else if (['jpg', 'jpeg', 'png'].includes(fileExt)) {
                    fileIcon = '<i class="fas fa-file-image text-green-600 text-2xl"></i>';
                } else if (['doc', 'docx'].includes(fileExt)) {
                    fileIcon = '<i class="fas fa-file-word text-blue-600 text-2xl"></i>';
                } else {
                    fileIcon = '<i class="fas fa-file-alt text-gray-600 text-2xl"></i>';
                }

                infoDiv.innerHTML = `
                    <div class="flex items-center space-x-4 border border-red-300 rounded-lg p-3 bg-white">
                        <div>${fileIcon}</div>
                        <div class="flex-1">
                            <p class="font-semibold truncate">${fileName}</p>
                            <p class="text-xs text-gray-500">${fileSize} KB</p>
                        </div>
                        <button type="button" id="remove-file" class="text-red-600 hover:text-red-800 font-semibold">Remove</button>
                    </div>
                `;

                const removeBtn = document.getElementById('remove-file');
                removeBtn.addEventListener('click', function () {
                    fileInput.value = '';
                    infoDiv.innerHTML = '';
                });
            }
        });
    });
</script>
@endsection
