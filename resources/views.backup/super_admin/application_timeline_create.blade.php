@extends('layouts.app')

@section('title', 'Add Timeline Item')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add New Timeline Item</h1>
            <p class="text-gray-600">Create a new application timeline item for the public website</p>
        </div>
        <a href="{{ route('super_admin.application_timeline') }}"
           class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded inline-flex items-center text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Back to Timeline
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Timeline Item Details</h2>
        </div>

        <form action="{{ route('super_admin.application_timeline.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-6">
                <!-- Activity -->
                <div>
                    <label for="activity" class="block text-sm font-medium text-gray-700">Activity <span class="text-red-500">*</span></label>
                    <input type="text"
                           id="activity"
                           name="activity"
                           value="{{ old('activity') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('activity') border-red-300 @enderror"
                           placeholder="e.g., Call for Applications"
                           required>
                    @error('activity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- First Semester -->
                <div>
                    <label for="first_semester" class="block text-sm font-medium text-gray-700">First Semester <span class="text-red-500">*</span></label>
                    <input type="text"
                           id="first_semester"
                           name="first_semester"
                           value="{{ old('first_semester') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('first_semester') border-red-300 @enderror"
                           placeholder="e.g., January 15 - February 28"
                           required>
                    @error('first_semester')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Second Semester -->
                <div>
                    <label for="second_semester" class="block text-sm font-medium text-gray-700">Second Semester <span class="text-red-500">*</span></label>
                    <input type="text"
                           id="second_semester"
                           name="second_semester"
                           value="{{ old('second_semester') }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('second_semester') border-red-300 @enderror"
                           placeholder="e.g., July 15 - August 31"
                           required>
                    @error('second_semester')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sort Order and Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Sort Order -->
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort Order <span class="text-red-500">*</span></label>
                        <input type="number"
                               id="sort_order"
                               name="sort_order"
                               value="{{ old('sort_order', 0) }}"
                               min="0"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('sort_order') border-red-300 @enderror"
                               required>
                        <p class="mt-1 text-sm text-gray-500">Lower numbers appear first in the timeline.</p>
                        @error('sort_order')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="is_active" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="is_active"
                                name="is_active"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('is_active') border-red-300 @enderror">
                            <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('super_admin.application_timeline') }}"
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i>Create Timeline Item
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
