@extends('layouts.app')

@section('title', 'Upload Document')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <a href="{{ route('scholar.documents.index') }}"
               class="group w-44 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 shadow-sm flex items-center justify-center border border-gray-200 transition-all duration-300 transform hover:scale-105 hover:shadow-md">
                <i class="fas fa-arrow-left mr-2 group-hover:translate-x-[-3px] transition-transform duration-300"></i>
                <span>Back to Documents</span>
            </a>
            <h1 class="text-2xl font-bold text-black mt-2">Upload New Document</h1>
        </div>

        <div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm">
            <div class="mb-5 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">Important Information</h3>
                <p class="text-sm text-blue-700">Upload documents related to your scholarship, such as registration forms, enrollment forms, grades, or research papers.</p>
            </div>
            
            <form action="{{ route('scholar.documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Document Title <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                    <select id="category" name="category" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="description" name="description" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="document" class="block text-sm font-medium text-gray-700 mb-1">Document File <span class="text-red-500">*</span></label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4h-12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="document" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                    <span>Upload a file</span>
                                    <input id="document" name="document" type="file" class="sr-only" required>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                PDF, JPG, PNG, DOC, DOCX up to 10MB
                            </p>
                        </div>
                    </div>
                    @error('document')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-300">
                        <i class="fas fa-upload mr-2" style="color: white !important;"></i> Upload Document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
