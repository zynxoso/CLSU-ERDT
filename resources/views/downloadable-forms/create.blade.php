@extends('layouts.app')

@section('title', 'Add New Form')

@section('content')
<div class="container mx-auto py-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add New Form</h1>
            <p class="text-gray-600 mt-1">Upload a new downloadable form</p>
        </div>
        
        <a href="{{ route('super_admin.downloadable-forms.index') }}" 
           class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded inline-flex items-center text-sm">
            <svg class="w-4 h-4 mr-2 fill-none stroke-2" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Forms
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <form method="POST" action="{{ route('super_admin.downloadable-forms.store') }}" enctype="multipart/form-data" class="p-6" novalidate autocomplete="off">
            @csrf
            
            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm sm:text-base font-medium text-gray-700 mb-3 sm:mb-2 leading-relaxed">
                    Form Title <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title') }}"
                       class="w-full px-4 py-4 sm:px-3 sm:py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-clsu-maroon text-base min-h-[44px] touch-manipulation @error('title') border-red-500 @enderror"
                       placeholder="Enter form title"
                       required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm sm:text-base font-medium text-gray-700 mb-3 sm:mb-2 leading-relaxed">
                    Description
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          class="w-full px-4 py-4 sm:px-3 sm:py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-clsu-maroon text-base min-h-[100px] touch-manipulation @error('description') border-red-500 @enderror"
                          placeholder="Enter form description (optional)">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div class="mb-6">
                <label for="category" class="block text-sm sm:text-base font-medium text-gray-700 mb-3 sm:mb-2 leading-relaxed">
                    Category <span class="text-red-500">*</span>
                </label>
                <select id="category" 
                        name="category" 
                        class="w-full px-4 py-4 sm:px-3 sm:py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-clsu-maroon text-base min-h-[44px] touch-manipulation @error('category') border-red-500 @enderror"
                        required>
                    <option value="">Select a category</option>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('category')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- File Upload -->
            <div class="mb-6">
                <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                    Form File <span class="text-red-500">*</span>
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-clsu-maroon transition-colors duration-200">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-clsu-maroon hover:text-red-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-clsu-maroon">
                                <span>Upload a file</span>
                                <input id="file" name="file" type="file" class="sr-only" accept=".pdf,.doc,.docx,.xls,.xlsx" required onchange="displayFileName(this)">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PDF, DOC, DOCX, XLS, XLSX up to 10MB</p>
                        <p id="file-name" class="text-sm text-clsu-maroon font-medium" style="display: none;"></p>
                    </div>
                </div>
                @error('file')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sort Order -->
            <div class="mb-6">
                <label for="sort_order" class="block text-sm sm:text-base font-medium text-gray-700 mb-3 sm:mb-2 leading-relaxed">
                    Sort Order
                </label>
                <input type="number" 
                       id="sort_order" 
                       name="sort_order" 
                       value="{{ old('sort_order', 0) }}"
                       min="0"
                       class="w-full px-4 py-4 sm:px-3 sm:py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-clsu-maroon focus:border-clsu-maroon text-base min-h-[44px] touch-manipulation @error('sort_order') border-red-500 @enderror"
                       placeholder="0">
                <p class="mt-2 text-sm sm:text-xs text-gray-500 leading-relaxed">Lower numbers appear first. Default is 0.</p>
                @error('sort_order')
                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-6">
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="status" 
                           name="status" 
                           value="1"
                           {{ old('status', true) ? 'checked' : '' }}
                           class="h-5 w-5 sm:h-4 sm:w-4 text-clsu-maroon focus:ring-2 focus:ring-clsu-maroon border-gray-300 rounded touch-manipulation"
                           aria-describedby="status-description">
                    <label for="status" class="ml-3 sm:ml-2 block text-sm sm:text-base text-gray-700 leading-relaxed">
                        Active (form will be available for download)
                    </label>
                </div>
                <p id="status-description" class="mt-2 text-sm text-gray-500 leading-relaxed">Check this box to make the form immediately available for download.</p>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('super_admin.downloadable-forms.index') }}" 
                   class="bg-gray-200 hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-4 focus:ring-gray-300 text-gray-800 font-semibold py-4 sm:py-2 px-6 rounded-lg transition-all duration-300 text-center min-h-[44px] touch-manipulation">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-clsu-maroon hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 text-white font-semibold py-4 sm:py-2 px-6 rounded-lg transition-all duration-300 min-h-[44px] touch-manipulation">
                    Upload Form
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function(window, document) {
    'use strict';
    
    // Prevent any global form variable conflicts
    if (typeof window.form !== 'undefined') {
        console.warn('Global form variable already exists, using namespaced approach');
    }
    
    // Create a unique namespace to avoid conflicts
    const DownloadableFormHandler = {
        init: function() {
            // File display functionality
            this.displayFileName = function(input) {
                const fileNameElement = document.getElementById('file-name');
                if (input.files && input.files[0]) {
                    fileNameElement.textContent = 'Selected: ' + input.files[0].name;
                    fileNameElement.style.display = 'block';
                } else {
                    fileNameElement.style.display = 'none';
                }
            };

            // Make displayFileName globally available for the onchange event
            if (typeof window.displayFileName === 'undefined') {
                window.displayFileName = this.displayFileName;
            }

            // Drag and drop functionality
            this.setupDragAndDrop = function() {
                const dropZone = document.querySelector('.border-dashed');
                const fileInput = document.getElementById('file');

                if (dropZone && fileInput) {
                    const self = this;
                    
                    function preventDefaults(e) {
                        e.preventDefault();
                        e.stopPropagation();
                    }

                    function highlight(e) {
                        dropZone.classList.add('border-clsu-maroon', 'bg-red-50');
                    }

                    function unhighlight(e) {
                        dropZone.classList.remove('border-clsu-maroon', 'bg-red-50');
                    }

                    function handleDrop(e) {
                        const dt = e.dataTransfer;
                        const files = dt.files;
                        
                        if (files.length > 0) {
                            fileInput.files = files;
                            self.displayFileName(fileInput);
                        }
                    }

                    // Add event listeners
                    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                        dropZone.addEventListener(eventName, preventDefaults, false);
                    });

                    ['dragenter', 'dragover'].forEach(eventName => {
                        dropZone.addEventListener(eventName, highlight, false);
                    });

                    ['dragleave', 'drop'].forEach(eventName => {
                        dropZone.addEventListener(eventName, unhighlight, false);
                    });

                    dropZone.addEventListener('drop', handleDrop, false);
                }
            };

            this.setupDragAndDrop();

            // Form validation enhancement
            this.setupFormValidation = function() {
                const downloadableFormElement = document.querySelector('form[action*="downloadable-forms"]');
                if (downloadableFormElement) {
                    downloadableFormElement.addEventListener('submit', function(e) {
                        const fileInput = document.getElementById('file');
                        const titleInput = document.getElementById('title');
                        const categorySelect = document.getElementById('category');
                        
                        // Remove any existing validation messages
                        const existingErrors = downloadableFormElement.querySelectorAll('.validation-error');
                        existingErrors.forEach(error => error.remove());
                        
                        let hasErrors = false;
                        
                        // Basic validation
                        if (!titleInput || !titleInput.value.trim()) {
                            e.preventDefault();
                            hasErrors = true;
                            if (titleInput) titleInput.focus();
                        }
                        
                        if (!categorySelect || !categorySelect.value) {
                            e.preventDefault();
                            hasErrors = true;
                            if (categorySelect && !hasErrors) categorySelect.focus();
                        }
                        
                        if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                            e.preventDefault();
                            hasErrors = true;
                            if (fileInput && !hasErrors) fileInput.focus();
                        }
                        
                        return !hasErrors;
                    });
                }
            };

            this.setupFormValidation();
        }
    };

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        DownloadableFormHandler.init();
    });

})(window, document);
</script>
@endpush
@endsection