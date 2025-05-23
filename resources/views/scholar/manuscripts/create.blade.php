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
    <form action="{{ route('scholar.manuscripts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                Manuscript Title <span class="text-red-500">*</span>
            </label>
            <input type="text" id="title" name="title" value="{{ old('title') }}"
                class="w-full bg-white border @error('title') border-red-300 @else border-gray-300 @enderror rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            @error('title')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">Enter the complete title of your manuscript</p>
        </div>

        <div class="mb-4">
            <label for="abstract" class="block text-sm font-medium text-gray-700 mb-1">
                Abstract <span class="text-red-500">*</span>
            </label>
            <textarea id="abstract" name="abstract" rows="4"
                class="w-full bg-white border @error('abstract') border-red-300 @else border-gray-300 @enderror rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('abstract') }}</textarea>
            @error('abstract')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">Provide a brief summary of your manuscript (250-300 words recommended)</p>
        </div>

        <div class="mb-4">
            <label for="manuscript_type" class="block text-sm font-medium text-gray-700 mb-1">
                Manuscript Type <span class="text-red-500">*</span>
            </label>
            <select id="manuscript_type" name="manuscript_type"
                class="w-full bg-white border @error('manuscript_type') border-red-300 @else border-gray-300 @enderror rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">Select Type</option>
                <option value="Conference Paper" {{ old('manuscript_type') == 'Conference Paper' ? 'selected' : '' }}>Conference Paper</option>
                <option value="Journal Article" {{ old('manuscript_type') == 'Journal Article' ? 'selected' : '' }}>Journal Article</option>
                <option value="Thesis" {{ old('manuscript_type') == 'Thesis' ? 'selected' : '' }}>Thesis</option>
                <option value="Dissertation" {{ old('manuscript_type') == 'Dissertation' ? 'selected' : '' }}>Dissertation</option>
                <option value="Book Chapter" {{ old('manuscript_type') == 'Book Chapter' ? 'selected' : '' }}>Book Chapter</option>
                <option value="Other" {{ old('manuscript_type') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('manuscript_type')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="co_authors" class="block text-sm font-medium text-gray-700 mb-1">
                    Co-Authors
                </label>
                <input type="text" id="co_authors" name="co_authors" value="{{ old('co_authors') }}"
                    class="w-full bg-white border @error('co_authors') border-red-300 @else border-gray-300 @enderror rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('co_authors')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Separate multiple authors with commas</p>
            </div>

            <div>
                <label for="keywords" class="block text-sm font-medium text-gray-700 mb-1">
                    Keywords
                </label>
                <input type="text" id="keywords" name="keywords" value="{{ old('keywords') }}"
                    class="w-full bg-white border @error('keywords') border-red-300 @else border-gray-300 @enderror rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('keywords')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Add 3-5 keywords that describe your manuscript, separated by commas</p>
            </div>
        </div>

        <div class="mb-4">
            <label for="file" class="block text-sm font-medium text-gray-700 mb-1">
                Upload Manuscript File (PDF)
            </label>
            <input type="file" id="file" name="file" accept=".pdf"
                class="w-full bg-white border @error('file') border-red-300 @else border-gray-300 @enderror rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('file')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">Maximum file size: 10MB. PDF format only.</p>
        </div>

        <div class="mb-4">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                Additional Notes
            </label>
            <textarea id="notes" name="notes" rows="3"
                class="w-full bg-white border @error('notes') border-red-300 @else border-gray-300 @enderror rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
            @error('notes')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">Include any additional information or special instructions</p>
        </div>

        <div class="flex justify-between items-center">
            <div class="text-sm text-gray-500">
                <span class="text-red-500">*</span> Required fields
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i> Save Manuscript
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
