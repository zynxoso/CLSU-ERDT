@extends('layouts.app')

@section('title', 'Edit Document')

@section('content')
<div class="bg-slate-900 min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <a href="{{ route('scholar.documents.show', $document->id) }}" class="text-blue-400 hover:text-blue-300">
                <i class="fas fa-arrow-left mr-2"></i> Back to Document Details
            </a>
            <h1 class="text-2xl font-bold text-white mt-2">Edit Document</h1>
        </div>

        <div class="bg-slate-800 rounded-lg p-6 border border-slate-700">
            <form action="{{ route('scholar.documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="file" class="block text-sm font-medium text-gray-400 mb-1">Document File</label>
                    <input type="file" id="file" name="file" class="w-full bg-slate-700 border border-slate-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-400 mt-1">Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG. Max size: 10MB</p>
                    <p class="text-xs text-yellow-400 mt-1">Leave empty to keep the current file: {{ $document->file_name }}</p>
                    @error('file')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-400 mb-1">Document Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $document->title) }}" class="w-full bg-slate-700 border border-slate-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="category" class="block text-sm font-medium text-gray-400 mb-1">Document Type</label>
                    <select id="category" name="category" class="w-full bg-slate-700 border border-slate-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Document Type</option>
                        <option value="Certificate" {{ old('category', $document->category) == 'Certificate' ? 'selected' : '' }}>Certificate</option>
                        <option value="Transcript" {{ old('category', $document->category) == 'Transcript' ? 'selected' : '' }}>Transcript</option>
                        <option value="ID" {{ old('category', $document->category) == 'ID' ? 'selected' : '' }}>ID</option>
                        <option value="Receipt" {{ old('category', $document->category) == 'Receipt' ? 'selected' : '' }}>Receipt</option>
                        <option value="Other" {{ old('category', $document->category) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-400 mb-1">Description</label>
                    <textarea id="description" name="description" rows="4" class="w-full bg-slate-700 border border-slate-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $document->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if($document->status == 'Rejected' && $document->admin_notes)
                <div class="mb-6 p-4 bg-red-900 bg-opacity-50 rounded-lg border border-red-800">
                    <h3 class="text-sm font-medium text-red-400 mb-1">Admin Feedback</h3>
                    <p class="text-white">{{ $document->admin_notes }}</p>
                    <p class="mt-2 text-xs text-red-400">Please address these issues before submitting again.</p>
                </div>
                @endif

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i> Update Document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
