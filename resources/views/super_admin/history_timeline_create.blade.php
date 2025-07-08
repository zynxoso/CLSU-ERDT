@extends('layouts.app')

@section('title', 'Add History Timeline Item')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add New History Timeline Item</h1>
            <p class="text-gray-600">Create a new timeline item for the history page</p>
        </div>
        <a href="{{ route('super_admin.history_timeline') }}"
           class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded inline-flex items-center text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Back to Timeline
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Timeline Item Details</h2>
        </div>

        <form action="{{ route('super_admin.history_timeline.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title') }}"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="description"
                              id="description"
                              rows="4"
                              class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Event Date -->
                <div>
                    <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">Event Date *</label>
                    <input type="date"
                           name="event_date"
                           id="event_date"
                           value="{{ old('event_date') }}"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('event_date') border-red-500 @enderror">
                    @error('event_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Year Label -->
                <div>
                    <label for="year_label" class="block text-sm font-medium text-gray-700 mb-2">Year Label (Optional)</label>
                    <input type="text"
                           name="year_label"
                           id="year_label"
                           value="{{ old('year_label') }}"
                           placeholder="e.g., 1998, Early 2000s"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('year_label') border-red-500 @enderror">
                    @error('year_label')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category"
                            id="category"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-500 @enderror">
                        <option value="">Select a category</option>
                        <option value="milestone" {{ old('category') == 'milestone' ? 'selected' : '' }}>Milestone</option>
                        <option value="achievement" {{ old('category') == 'achievement' ? 'selected' : '' }}>Achievement</option>
                        <option value="partnership" {{ old('category') == 'partnership' ? 'selected' : '' }}>Partnership</option>
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Color -->
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-2">Color Theme *</label>
                    <select name="color"
                            id="color"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('color') border-red-500 @enderror">
                        <option value="">Select a color</option>
                        <option value="green" {{ old('color') == 'green' ? 'selected' : '' }}>Green</option>
                        <option value="blue" {{ old('color') == 'blue' ? 'selected' : '' }}>Blue</option>
                        <option value="purple" {{ old('color') == 'purple' ? 'selected' : '' }}>Purple</option>
                        <option value="red" {{ old('color') == 'red' ? 'selected' : '' }}>Red</option>
                        <option value="yellow" {{ old('color') == 'yellow' ? 'selected' : '' }}>Yellow</option>
                        <option value="indigo" {{ old('color') == 'indigo' ? 'selected' : '' }}>Indigo</option>
                    </select>
                    @error('color')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Icon -->
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">Icon (Optional)</label>
                    <input type="text"
                           name="icon"
                           id="icon"
                           value="{{ old('icon') }}"
                           placeholder="e.g., fas fa-graduation-cap"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('icon') border-red-500 @enderror">
                    @error('icon')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Use FontAwesome icon classes (e.g., fas fa-star)</p>
                </div>

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order *</label>
                    <input type="number"
                           name="sort_order"
                           id="sort_order"
                           value="{{ old('sort_order', 0) }}"
                           min="0"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('sort_order') border-red-500 @enderror">
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
                </div>

                <!-- Image Upload -->
                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Image (Optional)</label>
                    <input type="file"
                           name="image"
                           id="image"
                           accept="image/*"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('image') border-red-500 @enderror">
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB</p>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('super_admin.history_timeline') }}"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                    Create Timeline Item
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
