@extends('layouts.app')

@section('title', 'Edit Form')

@section('content')
<div class="container mx-auto py-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Form</h1>
            <p class="text-gray-600 mt-1">Update form details and file</p>
        </div>
        
        <a href="{{ route('super_admin.downloadable-forms.index') }}" 
           class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded inline-flex items-center text-sm">
            <svg class="w-4 h-4 mr-2 fill-none stroke-2" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Forms
        </a>
    </div>

    <!-- Current File Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <h3 class="text-lg font-semibold text-blue-800 mb-2">Current File</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="font-medium text-blue-700">Filename:</span>
                <span class="text-blue-600">{{ $downloadableForm->filename }}</span>
            </div>
            <div>
                <span class="font-medium text-blue-700">Size:</span>
                <span class="text-blue-600">{{ $downloadableForm->formatted_file_size }}</span>
            </div>
            <div>
                <span class="font-medium text-blue-700">Downloads:</span>
                <span class="text-blue-600">{{ $downloadableForm->download_count }}</span>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <form method="POST" action="{{ route('super_admin.downloadable-forms.update', $downloadableForm) }}" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Form Title <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title', $downloadableForm->title) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-clsu-maroon focus:border-clsu-maroon @error('title') border-red-500 @enderror"
                       placeholder="Enter form title"
                       required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-clsu-maroon focus:border-clsu-maroon @error('description') border-red-500 @enderror"
                          placeholder="Enter form description (optional)">{{ old('description', $downloadableForm->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div class="mb-6">
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                    Category <span class="text-red-500">*</span>
                </label>
                <select id="category" 
                        name="category" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-clsu-maroon focus:border-clsu-maroon @error('category') border-red-500 @enderror"
                        required>
                    <option value="">Select a category</option>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ old('category', $downloadableForm->category) === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('category')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- File Upload (Optional for Edit) -->
            <div class="mb-6">
                <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                    Replace File (Optional)
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-clsu-maroon transition-colors duration-200">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-clsu-maroon hover:text-red-700 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-clsu-maroon">
                                <span>Upload a new file</span>
                                <input id="file" name="file" type="file" class="sr-only" accept=".pdf,.doc,.docx,.xls,.xlsx" onchange="displayFileName(this)">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PDF, DOC, DOCX, XLS, XLSX up to 10MB</p>
                        <p class="text-xs text-gray-500">Leave empty to keep current file</p>
                        <p id="file-name" class="text-sm text-clsu-maroon font-medium" style="display: none;"></p>
                    </div>
                </div>
                @error('file')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Sort Order -->
            <div class="mb-6">
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                    Sort Order
                </label>
                <input type="number" 
                       id="sort_order" 
                       name="sort_order" 
                       value="{{ old('sort_order', $downloadableForm->sort_order) }}"
                       min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-clsu-maroon focus:border-clsu-maroon @error('sort_order') border-red-500 @enderror"
                       placeholder="0">
                <p class="mt-1 text-sm text-gray-500">Lower numbers appear first. Default is 0.</p>
                @error('sort_order')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-6">
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="status" 
                           name="status" 
                           value="1"
                           {{ old('status', $downloadableForm->status) ? 'checked' : '' }}
                           class="h-4 w-4 text-clsu-maroon focus:ring-clsu-maroon border-gray-300 rounded">
                    <label for="status" class="ml-2 block text-sm text-gray-700">
                        Active (form will be available for download)
                    </label>
                </div>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Metadata -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Form Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-600">Uploaded by:</span>
                        <span class="text-gray-800">{{ $downloadableForm->uploader->name ?? 'Unknown' }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">Upload date:</span>
                        <span class="text-gray-800">{{ $downloadableForm->created_at->format('M d, Y g:i A') }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">Last updated:</span>
                        <span class="text-gray-800">{{ $downloadableForm->updated_at->format('M d, Y g:i A') }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">Total downloads:</span>
                        <span class="text-gray-800">{{ $downloadableForm->download_count }}</span>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('super_admin.downloadable-forms.index') }}" 
                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-6 rounded">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-clsu-maroon hover:bg-red-700 text-white font-semibold py-2 px-6 rounded">
                    Update Form
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function displayFileName(input) {
    const fileNameElement = document.getElementById('file-name');
    if (input.files && input.files[0]) {
        fileNameElement.textContent = 'New file selected: ' + input.files[0].name;
        fileNameElement.style.display = 'block';
    } else {
        fileNameElement.style.display = 'none';
    }
}

// Drag and drop functionality
const dropZone = document.querySelector('.border-dashed');
const fileInput = document.getElementById('file');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    dropZone.classList.add('border-clsu-maroon', 'bg-red-50');
}

function unhighlight(e) {
    dropZone.classList.remove('border-clsu-maroon', 'bg-red-50');
}

dropZone.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length > 0) {
        fileInput.files = files;
        displayFileName(fileInput);
    }
}
</script>
@endsection