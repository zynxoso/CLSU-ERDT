@extends('layouts.app')

@section('title', 'Edit Manuscript')

@section('content')
<div class="mb-6">
    <a href="{{ route('scholar.manuscripts.show', $manuscript->id) }}" class="text-red-800 hover:text-red-900 transition-colors duration-200">
        <i class="fas fa-arrow-left mr-2"></i> Back to Manuscript
    </a>
    <h1 class="text-2xl font-bold text-gray-800 mt-2">Edit Manuscript</h1>
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

@if (session('error'))
<div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas fa-exclamation-circle text-red-500"></i>
        </div>
        <div class="ml-3">
            <p class="text-sm text-red-700">{{ session('error') }}</p>
        </div>
    </div>
</div>
@endif

<div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
    <form action="{{ route('scholar.manuscripts.update', $manuscript->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                Manuscript Title <span class="text-red-500">*</span>
            </label>
            <input type="text" id="title" name="title" value="{{ old('title', $manuscript->title) }}"
                class="w-full bg-white border @error('title') border-red-300 @else border-gray-300 @enderror rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-700" required>
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
                class="w-full bg-white border @error('abstract') border-red-300 @else border-gray-300 @enderror rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-700" required>{{ old('abstract', $manuscript->abstract) }}</textarea>
            @error('abstract')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">Provide a brief summary of your manuscript (250-300 words recommended)</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="manuscript_type" class="block text-sm font-medium text-gray-700 mb-1">
                    Manuscript Type <span class="text-red-500">*</span>
                </label>
                <select id="manuscript_type" name="manuscript_type"
                    class="w-full bg-white border @error('manuscript_type') border-red-300 @else border-gray-300 @enderror rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-700" required>
                    <option value="">Select Type</option>
                    <option value="Outline" {{ old('manuscript_type', $manuscript->manuscript_type) == 'Outline' ? 'selected' : '' }}>Outline</option>
                    <option value="Final" {{ old('manuscript_type', $manuscript->manuscript_type) == 'Final' ? 'selected' : '' }}>Final</option>
                </select>
                @error('manuscript_type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="co_authors" class="block text-sm font-medium text-gray-700 mb-1">
                    Co-Authors
                </label>
                <input type="text" id="co_authors" name="co_authors" value="{{ old('co_authors', $manuscript->co_authors) }}"
                    class="w-full bg-white border @error('co_authors') border-red-300 @else border-gray-300 @enderror rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-700">
                @error('co_authors')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Separate multiple authors with commas</p>
            </div>
        </div>

        <div class="mb-4">
            {{-- Keywords input removed as per request --}}
        </div>

        <div class="mb-4">
            <label for="file" class="block text-sm font-medium text-gray-700 mb-1">
                Upload New Manuscript File (PDF, optional)
            </label>
            <input type="file" id="file" name="file" accept=".pdf"
                class="w-full bg-white border @error('file') border-red-300 @else border-gray-300 @enderror rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-700">
            @error('file')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">Maximum file size: 10MB. Only upload a new file if you want to replace the existing one.</p>

            @if($manuscript->documents && $manuscript->documents->count() > 0)
            <div class="mt-2">
                <p class="text-sm text-gray-600 font-medium">Current Files:</p>
                <ul class="list-disc pl-5 text-xs text-gray-500">
                    @foreach($manuscript->documents as $document)
                        <li>{{ $document->title }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <div class="flex justify-between items-center">
            <div class="text-sm text-gray-500">
                <span class="text-red-500">*</span> Required fields
            </div>
            <div class="flex justify-end space-x-3">
                <a href="{{ route('scholar.manuscripts.show', $manuscript->id) }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-red-800 text-white rounded-lg hover:bg-red-900 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i> Update Manuscript
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
