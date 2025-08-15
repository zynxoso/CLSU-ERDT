@extends('layouts.app')

@section('title', $downloadableForm->title)

@section('content')
<div class="container mx-auto py-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $downloadableForm->title }}</h1>
            <p class="text-gray-600 mt-1">Form Details</p>
        </div>
        
        <a href="{{ request()->routeIs('super_admin.*') ? route('super_admin.downloadable-forms.index') : route('downloadable-forms.public.index') }}" 
           class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded inline-flex items-center text-sm">
            <svg class="w-4 h-4 mr-2 fill-none stroke-2" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Forms
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Form Details Card -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                <div class="p-6">
                    <!-- Category Badge -->
                    <div class="mb-4">
                        <span class="inline-block px-3 py-1 text-sm font-medium rounded-full 
                            {{ $downloadableForm->category === 'application' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $downloadableForm->category === 'scholarship' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $downloadableForm->category === 'research' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $downloadableForm->category === 'administrative' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $downloadableForm->category === 'academic' ? 'bg-indigo-100 text-indigo-800' : '' }}
                            {{ $downloadableForm->category === 'other' ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ $downloadableForm->getCategories()[$downloadableForm->category] ?? 'Other' }}
                        </span>
                        
                        @if(auth()->check() && auth()->user()->role === 'super_admin')
                            <span class="ml-2 inline-flex items-center">
                                <span class="inline-block w-2 h-2 rounded-full {{ $downloadableForm->status ? 'bg-green-400' : 'bg-red-400' }} mr-1"></span>
                                <span class="text-xs text-gray-500">{{ $downloadableForm->status ? 'Active' : 'Inactive' }}</span>
                            </span>
                        @endif
                    </div>
                    
                    <!-- Title -->
                    <h2 class="text-xl font-semibold text-gray-900 mb-3">{{ $downloadableForm->title }}</h2>
                    
                    <!-- Description -->
                    @if($downloadableForm->description)
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Description</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $downloadableForm->description }}</p>
                        </div>
                    @endif
                    
                    <!-- File Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Filename</span>
                            </div>
                            <p class="text-gray-900 font-medium">{{ $downloadableForm->filename }}</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 110-2h4z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">File Size</span>
                            </div>
                            <p class="text-gray-900 font-medium">{{ $downloadableForm->formatted_file_size }}</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Downloads</span>
                            </div>
                            <p class="text-gray-900 font-medium">{{ $downloadableForm->download_count }}</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17v4a2 2 0 002 2h4M13 13h4a2 2 0 012 2v4a2 2 0 01-2 2h-4m-6-4a2 2 0 01-2-2V9a2 2 0 012-2h2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">File Type</span>
                            </div>
                            <p class="text-gray-900 font-medium uppercase">{{ $downloadableForm->file_extension }}</p>
                        </div>
                    </div>
                    
                    <!-- Download Button -->
                    @if($downloadableForm->status || (auth()->check() && auth()->user()->role === 'super_admin'))
                        <div class="flex justify-center">
                            <a href="{{ route('downloadable-forms.download', $downloadableForm) }}" 
                               class="bg-clsu-green hover:bg-green-700 text-white font-semibold py-3 px-8 rounded-lg inline-flex items-center text-lg transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download Form
                            </a>
                        </div>
                    @else
                        <div class="text-center">
                            <p class="text-gray-500 text-lg">This form is currently unavailable for download.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Form Metadata -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                <div class="px-4 py-3 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-blue-800">Form Information</h3>
                </div>
                <div class="p-4 space-y-4">
                    <div>
                        <span class="block text-sm font-medium text-gray-700">Uploaded by</span>
                        <span class="text-gray-900">{{ $downloadableForm->uploader->name ?? 'Unknown' }}</span>
                    </div>
                    
                    <div>
                        <span class="block text-sm font-medium text-gray-700">Upload Date</span>
                        <span class="text-gray-900">{{ $downloadableForm->created_at->format('M d, Y') }}</span>
                        <span class="text-gray-500 text-sm">{{ $downloadableForm->created_at->format('g:i A') }}</span>
                    </div>
                    
                    @if($downloadableForm->updated_at != $downloadableForm->created_at)
                        <div>
                            <span class="block text-sm font-medium text-gray-700">Last Updated</span>
                            <span class="text-gray-900">{{ $downloadableForm->updated_at->format('M d, Y') }}</span>
                            <span class="text-gray-500 text-sm">{{ $downloadableForm->updated_at->format('g:i A') }}</span>
                        </div>
                    @endif
                    
                    <div>
                        <span class="block text-sm font-medium text-gray-700">MIME Type</span>
                        <span class="text-gray-900 text-sm">{{ $downloadableForm->mime_type }}</span>
                    </div>
                </div>
            </div>
            
            @if(auth()->check() && auth()->user()->role === 'super_admin')
                <!-- Admin Actions -->
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="px-4 py-3 bg-gradient-to-r from-red-50 to-pink-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-red-800">Admin Actions</h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <a href="{{ route('super_admin.downloadable-forms.edit', $downloadableForm) }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded inline-flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Form
                        </a>
                        
                        <form method="POST" action="{{ route('super_admin.downloadable-forms.toggle-status', $downloadableForm) }}" class="w-full">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="w-full bg-{{ $downloadableForm->status ? 'yellow' : 'green' }}-600 hover:bg-{{ $downloadableForm->status ? 'yellow' : 'green' }}-700 text-white font-semibold py-2 px-4 rounded inline-flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($downloadableForm->status)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    @endif
                                </svg>
                                {{ $downloadableForm->status ? 'Deactivate' : 'Activate' }} Form
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('super_admin.downloadable-forms.destroy', $downloadableForm) }}" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded inline-flex items-center justify-center"
                                    onclick="return confirm('Are you sure you want to delete this form? This action cannot be undone.')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Form
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
