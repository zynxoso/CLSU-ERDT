@extends('layouts.app')

@section('title', 'Create Manuscript')

@section('content')
<div class="container mx-auto ">
    <!-- <div class="mb-6">
        <a href="{{ route('admin.manuscripts.index') }}" class="text-blue-600 hover:text-blue-700">
            <i class="fas fa-arrow-left mr-2"></i>Back to Manuscripts
        </a>
    </div> -->

    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">Create Manuscript</h1>

        <form action="{{ route('admin.manuscripts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Manuscript Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Manuscript Title <span class="text-red-500">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="abstract" class="block text-sm font-medium text-gray-700 mb-1">Abstract <span class="text-red-500">*</span></label>
                        <textarea id="abstract" name="abstract" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('abstract') }}</textarea>
                        @error('abstract')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="manuscript_type" class="block text-sm font-medium text-gray-700 mb-1">Manuscript Type <span class="text-red-500">*</span></label>
                        <select id="manuscript_type" name="manuscript_type" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select Type</option>
                            <option value="journal" {{ old('manuscript_type') == 'journal' ? 'selected' : '' }}>Journal Article</option>
                            <option value="conference" {{ old('manuscript_type') == 'conference' ? 'selected' : '' }}>Conference Paper</option>
                            <option value="thesis" {{ old('manuscript_type') == 'thesis' ? 'selected' : '' }}>Thesis</option>
                            <option value="book" {{ old('manuscript_type') == 'book' ? 'selected' : '' }}>Book</option>
                            <option value="other" {{ old('manuscript_type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('manuscript_type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="co_authors" class="block text-sm font-medium text-gray-700 mb-1">Co-Authors</label>
                        <input type="text" id="co_authors" name="co_authors" value="{{ old('co_authors') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Separate names with commas">
                        @error('co_authors')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="keywords" class="block text-sm font-medium text-gray-700 mb-1">Keywords <span class="text-red-500">*</span></label>
                        <input type="text" id="keywords" name="keywords" value="{{ old('keywords') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Separate keywords with commas">
                        @error('keywords')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select id="status" name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="submitted" {{ old('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="under_review" {{ old('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="revision_required" {{ old('status') == 'revision_required' ? 'selected' : '' }}>Revision Required</option>
                            <option value="accepted" {{ old('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Author Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="scholar_id" class="block text-sm font-medium text-gray-700 mb-1">Scholar <span class="text-red-500">*</span></label>
                        <select id="scholar_id" name="scholar_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select Scholar</option>
                            @foreach(\App\Models\ScholarProfile::with('user')->get() as $scholar)
                                <option value="{{ $scholar->id }}" {{ old('scholar_id') == $scholar->id ? 'selected' : '' }}>{{ $scholar->user->name }} ({{ $scholar->user->email }})</option>
                            @endforeach
                        </select>
                        @error('scholar_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">File Upload</h2>

                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Upload Manuscript File (PDF)</label>
                    <input type="file" id="file" name="file" accept=".pdf" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('file')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Additional Information</h2>

                <div>
                    <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-1">Admin Notes</label>
                    <textarea id="admin_notes" name="admin_notes" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('admin_notes') }}</textarea>
                    @error('admin_notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2" style="color: white !important;"></i> Create Manuscript
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
