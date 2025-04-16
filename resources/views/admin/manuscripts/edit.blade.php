@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex justify-between items-center">
        <div class="flex items-center">
            <a href="{{ route('admin.manuscripts.show', $manuscript->id) }}" class="text-blue-600 hover:text-blue-700">
                <i class="fas fa-arrow-left mr-2"></i>Back to Manuscript Details
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">Edit Manuscript</h1>

        <form action="{{ route('admin.manuscripts.update', $manuscript->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" id="title"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                               value="{{ old('title', $manuscript->title) }}" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="manuscript_type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="manuscript_type" id="manuscript_type"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                required>
                            <option value="journal" {{ old('manuscript_type', $manuscript->manuscript_type) === 'journal' ? 'selected' : '' }}>Journal</option>
                            <option value="conference" {{ old('manuscript_type', $manuscript->manuscript_type) === 'conference' ? 'selected' : '' }}>Conference</option>
                            <option value="thesis" {{ old('manuscript_type', $manuscript->manuscript_type) === 'thesis' ? 'selected' : '' }}>Thesis</option>
                            <option value="book" {{ old('manuscript_type', $manuscript->manuscript_type) === 'book' ? 'selected' : '' }}>Book</option>
                            <option value="other" {{ old('manuscript_type', $manuscript->manuscript_type) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('manuscript_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="co_authors" class="block text-sm font-medium text-gray-700 mb-1">Co-authors</label>
                        <input type="text" name="co_authors" id="co_authors"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                               value="{{ old('co_authors', $manuscript->co_authors) }}">
                        @error('co_authors')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="keywords" class="block text-sm font-medium text-gray-700 mb-1">Keywords</label>
                        <input type="text" name="keywords" id="keywords"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                               value="{{ old('keywords', $manuscript->keywords) }}" required>
                        <p class="mt-1 text-sm text-gray-500">Separate keywords with commas</p>
                        @error('keywords')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <div class="mb-4">
                        <label for="abstract" class="block text-sm font-medium text-gray-700 mb-1">Abstract</label>
                        <textarea name="abstract" id="abstract" rows="8"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                  required>{{ old('abstract', $manuscript->abstract) }}</textarea>
                        @error('abstract')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-1">Admin Notes</label>
                        <textarea name="admin_notes" id="admin_notes" rows="4"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('admin_notes', $manuscript->admin_notes) }}</textarea>
                        @error('admin_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                required>
                            <option value="draft" {{ old('status', $manuscript->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="submitted" {{ old('status', $manuscript->status) === 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="under_review" {{ old('status', $manuscript->status) === 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="revision_required" {{ old('status', $manuscript->status) === 'revision_required' ? 'selected' : '' }}>Revision Required</option>
                            <option value="accepted" {{ old('status', $manuscript->status) === 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected" {{ old('status', $manuscript->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="published" {{ old('status', $manuscript->status) === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('admin.manuscripts.show', $manuscript->id) }}"
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    Update Manuscript
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
