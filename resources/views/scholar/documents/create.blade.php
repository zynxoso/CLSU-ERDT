@extends('layouts.app')

@section('title', 'Upload Document')

@section('content')
<div class="bg-slate-900 min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <a href="{{ route('scholar.documents.index') }}" class="text-blue-400 hover:text-blue-300">
                <i class="fas fa-arrow-left mr-2"></i> Back to Documents
            </a>
            <h1 class="text-2xl font-bold text-white mt-2">Upload New Document</h1>
        </div>

        <div class="bg-slate-800 rounded-lg p-6 border border-slate-700">
            <form action="{{ route('scholar.documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="file" class="block text-sm font-medium text-gray-400 mb-1">Document File</label>
                    <input type="file" id="file" name="file" class="w-full bg-slate-700 border border-slate-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <p class="text-xs text-gray-400 mt-1">Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG. Max size: 10MB</p>
                    @error('file')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-400 mb-1">Document Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" class="w-full bg-slate-700 border border-slate-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="category" class="block text-sm font-medium text-gray-400 mb-1">Document Type</label>
                    <select id="category" name="category" class="w-full bg-slate-700 border border-slate-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Document Type</option>
                        <option value="Certificate" {{ old('category') == 'Certificate' ? 'selected' : '' }}>Certificate</option>
                        <option value="Transcript" {{ old('category') == 'Transcript' ? 'selected' : '' }}>Transcript</option>
                        <option value="ID" {{ old('category') == 'ID' ? 'selected' : '' }}>ID</option>
                        <option value="Receipt" {{ old('category') == 'Receipt' ? 'selected' : '' }}>Receipt</option>
                        <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-400 mb-1">Description</label>
                    <textarea id="description" name="description" rows="4" class="w-full bg-slate-700 border border-slate-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-upload mr-2"></i> Upload Document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
