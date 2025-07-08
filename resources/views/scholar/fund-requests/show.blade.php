@extends('layouts.app')

@section('title', 'Fund Request Details')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Fund Request Details</h1>
            <p class="text-gray-600 mt-2">Review your fund request information and status</p>
        </div>

        <!-- Request Progress -->
        @if($fundRequest->status != 'Draft' && $fundRequest->status != 'Rejected')
        <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6 p-6">
            <!-- Progress Header -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Request Progress</h2>
                <p class="text-sm text-gray-600">Track the status of your fund request</p>
            </div>

            <!-- Progress Bar -->
            <div class="relative mb-8">
                <!-- Progress Line -->
                <div class="h-2 bg-gray-200 rounded-full">
                    @php
                        $progressWidth = $fundRequest->status == 'Submitted' ? '5%' :
                                       ($fundRequest->status == 'Under Review' ? '66%' :
                                       ($fundRequest->status == 'Approved' ? '100%' : '0%'));
                    @endphp
                    <div class="h-2 rounded-full transition-all duration-500"
                         style="width: {{ $progressWidth }}; background-color: {{ $fundRequest->status == 'Approved' ? '#166534' : '#7C2D12' }};">
                    </div>
                </div>

                <!-- Status Points -->
                <div class="flex justify-between absolute w-full -top-2">
                    <!-- Submitted -->
                    <div class="flex flex-col items-center">
                        <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center bg-white
                            {{ in_array($fundRequest->status, ['Submitted', 'Under Review', 'Approved']) ? 'border-green-800 bg-green-800' : 'border-gray-300' }}">
                            <i class="fas fa-paper-plane text-xs {{ in_array($fundRequest->status, ['Submitted', 'Under Review', 'Approved']) ? 'text-white' : 'text-gray-400' }}"></i>
                        </div>
                        <span class="text-xs mt-2 font-medium {{ in_array($fundRequest->status, ['Submitted', 'Under Review', 'Approved']) ? 'text-green-800' : 'text-gray-400' }}">
                            Submitted
                        </span>
                    </div>

                    <!-- Under Review -->
                    <div class="flex flex-col items-center">
                        <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center bg-white
                            {{ $fundRequest->status == 'Under Review' ? 'border-yellow-600 bg-yellow-600' :
                               ($fundRequest->status == 'Approved' ? 'border-green-800 bg-green-800' : 'border-gray-300') }}">
                            <i class="fas fa-search text-xs {{ $fundRequest->status == 'Under Review' ? 'text-white' :
                                                                ($fundRequest->status == 'Approved' ? 'text-white' : 'text-gray-400') }}"></i>
                        </div>
                        <span class="text-xs mt-2 font-medium {{ $fundRequest->status == 'Under Review' ? 'text-yellow-600' :
                                                                  ($fundRequest->status == 'Approved' ? 'text-green-800' : 'text-gray-400') }}">
                            Under Review
                        </span>
                    </div>

                    <!-- Approved -->
                    <div class="flex flex-col items-center">
                        <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center bg-white
                            {{ $fundRequest->status == 'Approved' ? 'border-green-800 bg-green-800' : 'border-gray-300' }}">
                            <i class="fas fa-check text-xs {{ $fundRequest->status == 'Approved' ? 'text-white' : 'text-gray-400' }}"></i>
                        </div>
                        <span class="text-xs mt-2 font-medium {{ $fundRequest->status == 'Approved' ? 'text-green-800' : 'text-gray-400' }}">
                            Approved
                        </span>
                    </div>
                </div>
            </div>

            <!-- Current Status -->
                {{-- <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 rounded-full {{ $fundRequest->status == 'Approved' ? 'bg-green-800' : 'bg-yellow-600' }}"></div>
                            <div>
                                <span class="text-sm text-gray-600">Current Status: </span>
                                <span class="font-semibold px-3 py-1 rounded-full text-sm
                                    {{ $fundRequest->status == 'Approved' ? 'bg-green-100 text-green-800' :
                                    ($fundRequest->status == 'Under Review' ? 'bg-yellow-100 text-yellow-800' :
                                    'bg-blue-100 text-blue-800') }}">
                                    {{ $fundRequest->status }}
                                </span>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $fundRequest->updated_at->diffForHumans() }}
                        </div>
                    </div>
                </div> --}}
        </div>
        @endif

        <!-- Request Information Section -->
        <div x-data="{
            showDetails: true,
            editMode: false,
            requestData: {
                amount: '{{ $fundRequest->amount }}',
                purpose: '{{ $fundRequest->purpose }}',
                details: '{{ $fundRequest->details ?? '' }}'
            }
        }" class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">

            <!-- Section Header -->
            <div class="bg-gray-50 px-6 py-4 rounded-t-lg border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-800 rounded-lg flex items-center justify-center" style="background-color: green;">
                            <i class="fas fa-file-invoice-dollar text-lg" style="color: white;"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Request Information</h2>
                            <p class="text-gray-600 text-sm">Complete details of your funding request</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-3">
                        <button
                            @click="showDetails = !showDetails"
                            class="px-4 py-2 bg-white text-gray-800 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors duration-200 border border-gray-300"
                        >
                            <i class="fas fa-eye mr-2" :class="{ 'fa-eye-slash': !showDetails }" style="color: #1f2937;"></i>
                            <span x-text="showDetails ? 'Hide Details' : 'Show Details'"></span>
                        </button>

                        @if($fundRequest->status == 'Draft')
                        <button
                            @click="editMode = !editMode"
                            class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg text-sm font-medium hover:bg-yellow-200 transition-colors duration-200 border border-yellow-300"
                        >
                            <i class="fas fa-edit mr-2" style="color: #1f2937;"></i>
                            <span x-text="editMode ? 'Cancel Edit' : 'Edit Request'"></span>
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Request Details Content -->
            <div x-show="showDetails" x-transition class="p-6">
                <!-- Status Badge -->
                <div class="mb-6">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $fundRequest->status == 'Approved' ? 'bg-green-100 text-green-800' :
                           ($fundRequest->status == 'Under Review' ? 'bg-yellow-100 text-yellow-800' :
                           ($fundRequest->status == 'Submitted' ? 'bg-blue-100 text-blue-800' :
                           ($fundRequest->status == 'Draft' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800'))) }}">
                        <div class="w-2 h-2 rounded-full mr-2
                            {{ $fundRequest->status == 'Approved' ? 'bg-green-600' :
                               ($fundRequest->status == 'Under Review' ? 'bg-yellow-600' :
                               ($fundRequest->status == 'Submitted' ? 'bg-blue-600' :
                               ($fundRequest->status == 'Draft' ? 'bg-gray-600' : 'bg-red-600'))) }}">
                        </div>
                        {{ $fundRequest->status }}
                    </span>
                </div>

                <!-- Information Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <!-- Request ID Card -->
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Request ID</h3>
                                <i class="fas fa-hashtag text-gray-400"></i>
                            </div>
                            <p class="text-2xl font-bold text-gray-800 font-mono">FR-{{ $fundRequest->id }}</p>
                            <p class="text-xs text-gray-500 mt-1">Unique identifier for tracking</p>
                        </div>

                        <!-- Date Requested Card -->
                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Date Requested</h3>
                                <i class="fas fa-calendar-alt text-green-600"></i>
                            </div>
                            <p class="text-xl font-bold text-gray-800">{{ $fundRequest->created_at->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $fundRequest->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <!-- Amount Card -->
                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Requested Amount</h3>
                                <i class="fas fa-peso-sign text-green-600"></i>
                            </div>
                            <div x-show="!editMode">
                                <p class="text-3xl font-bold text-green-800">₱{{ number_format($fundRequest->amount, 2) }}</p>
                            </div>
                            <div x-show="editMode" x-transition>
                                <input
                                    type="number"
                                    x-model="requestData.amount"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    step="0.01"
                                >
                            </div>
                        </div>

                        <!-- Purpose Card -->
                        <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Purpose</h3>
                                <i class="fas fa-bullseye text-yellow-600"></i>
                            </div>
                            <div x-show="!editMode">
                                <p class="text-lg font-semibold text-gray-800">{{ $fundRequest->purpose }}</p>
                            </div>
                            <div x-show="editMode" x-transition>
                                <input
                                    type="text"
                                    x-model="requestData.purpose"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Details Section -->
                <div class="mt-6">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-align-left text-gray-500 mr-3"></i>
                            <h3 class="text-lg font-semibold text-gray-800">Additional Details</h3>
                        </div>
                        <div x-show="!editMode">
                            <p class="text-gray-700 leading-relaxed">
                                {{ $fundRequest->details ?? 'No additional details provided.' }}
                            </p>
                        </div>
                        <div x-show="editMode" x-transition>
                            <textarea
                                x-model="requestData.details"
                                rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none"
                                placeholder="Provide additional details about your request..."
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Save Changes Button -->
                <div x-show="editMode" x-transition class="mt-6 flex justify-end space-x-3">
                    <button
                        @click="editMode = false"
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200"
                    >
                        Cancel
                    </button>
                    <button
                        class="px-6 py-2 bg-green-800 text-white rounded-lg hover:bg-green-900 transition-colors duration-200"
                    >
                        <i class="fas fa-save mr-2"></i>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>

        <!-- Admin Feedback -->
        @if($fundRequest->admin_notes)
        <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">
            <div class="bg-yellow-700 px-6 py-4 rounded-t-lg">
                <h2 class="text-lg font-semibold text-white">Admin Feedback</h2>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Reviewed By</h3>
                    <p class="text-gray-800">{{ $fundRequest->reviewedBy ? $fundRequest->reviewedBy->name : 'Not yet reviewed' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Notes</h3>
                    <p class="text-gray-800">{{ $fundRequest->admin_notes }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Supporting Documents Section -->
        <div x-data="{
            showDocuments: true,
            selectedDocument: null,
            viewMode: 'grid',
            uploadMode: false
        }" class="bg-white rounded-lg shadow-md border border-gray-200 mb-6">

            <!-- Section Header -->
            <div class="bg-gray-50 px-6 py-4 rounded-t-lg border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-800 rounded-lg flex items-center justify-center" style="background-color: green;">
                            <i class="fas fa-file-alt text-lg" style="color: white;"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Supporting Documents</h2>
                            <p class="text-gray-600 text-sm">
                                @if($fundRequest->documents->isNotEmpty())
                                    {{ $fundRequest->documents->count() }} document(s) uploaded
                                @else
                                    No documents uploaded yet
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-3">
                        @if($fundRequest->documents->isNotEmpty())
                        <div class="flex items-center bg-white rounded-lg p-1 border border-gray-300">
                            <button
                                @click="viewMode = 'grid'"
                                class="p-2 rounded-md transition-colors duration-200"
                                :class="viewMode === 'grid' ? 'bg-blue-600 text-white' : ''"
                                :style="viewMode !== 'grid' ? 'background-color: white; color: black;' : ''"
                            >
                                <i class="fas fa-th-large" :style="viewMode === 'grid' ? 'color: white;' : 'color: black;'"></i>
                            </button>
                            <button
                                @click="viewMode = 'list'"
                                class="p-2 rounded-md transition-colors duration-200"
                                :class="viewMode === 'list' ? 'bg-blue-600 text-white' : ''"
                                :style="viewMode !== 'list' ? 'background-color: white; color: black;' : ''"
                            >
                                <i class="fas fa-list" :style="viewMode === 'list' ? 'color: white;' : 'color: black;'"></i>
                            </button>
                        </div>
                        @endif

                        <button
                            @click="showDocuments = !showDocuments"
                            class="px-4 py-2 bg-white text-gray-800 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors duration-200 border border-gray-300"
                        >
                            <i class="fas fa-eye mr-2" :class="{ 'fa-eye-slash': !showDocuments }" style="color: #1f2937;"></i>
                            <span x-text="showDocuments ? 'Hide Documents' : 'Show Documents'"></span>
                        </button>

                        @if($fundRequest->status == 'Draft')
                        <button
                            @click="uploadMode = !uploadMode"
                            class="px-4 py-2 bg-green-100 text-green-800 rounded-lg text-sm font-medium hover:bg-green-200 transition-colors duration-200 border border-green-300"
                        >
                            <i class="fas fa-upload mr-2" style="color: #1f2937;"></i>
                            <span x-text="uploadMode ? 'Cancel Upload' : 'Upload Documents'"></span>
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Upload Area -->
            <div x-show="uploadMode" x-transition class="border-b border-gray-200 bg-gray-50">
                <div class="p-6">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-green-500 transition-colors duration-200">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-green-800 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-cloud-upload-alt text-white text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Upload Supporting Documents</h3>
                            <p class="text-gray-600 mb-4">Drag and drop files here, or click to browse</p>
                            <div class="flex items-center space-x-4">
                                <button class="px-6 py-2 bg-green-800 text-white rounded-lg hover:bg-green-900 transition-colors duration-200">
                                    <i class="fas fa-folder-open mr-2"></i>
                                    Browse Files
                                </button>
                                <span class="text-sm text-gray-500">PDF, JPG, PNG up to 10MB</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Content -->
            <div x-show="showDocuments" x-transition class="p-6">
                @if($fundRequest->documents->isNotEmpty())
                    <!-- Grid View -->
                    <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($fundRequest->documents as $index => $document)
                        <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <!-- Document Preview -->
                            <div class="h-32 bg-gray-100 relative overflow-hidden">
                                @php
                                    $extension = pathinfo($document->file_name, PATHINFO_EXTENSION);
                                    $isPdf = in_array($extension, ['pdf']);
                                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png']);
                                @endphp

                                @if($isImage)
                                    <img src="{{ asset('storage/' . $document->file_path) }}"
                                         alt="{{ $document->file_name }}"
                                         class="w-full h-full object-cover">
                                @elseif($isPdf)
                                    <div class="w-full h-full flex items-center justify-center bg-red-100">
                                        <i class="fas fa-file-pdf text-red-600 text-4xl"></i>
                                    </div>
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-blue-100">
                                        <i class="fas fa-file text-blue-600 text-4xl"></i>
                                    </div>
                                @endif

                                <!-- Overlay Actions -->
                                <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-50 transition-all duration-200 flex items-center justify-center opacity-0 hover:opacity-100">
                                    <div class="flex space-x-2">
                                        <button
                                            @click="selectedDocument = {{ $index }}"
                                            class="p-2 bg-green-800 text-white rounded-lg hover:bg-green-900 transition-colors duration-200"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ asset('storage/' . $document->file_path) }}"
                                           target="_blank"
                                           class="p-2 bg-white text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Document Info -->
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 truncate mb-2">{{ $document->file_name }}</h3>
                                <div class="flex items-center justify-between text-sm text-gray-600">
                                    <span class="flex items-center">
                                        <i class="fas fa-file mr-1"></i>
                                        {{ strtoupper($extension) }}
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $document->created_at->format('M d') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- List View -->
                    <div x-show="viewMode === 'list'" class="space-y-4">
                        @foreach($fundRequest->documents as $index => $document)
                        <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    @php
                                        $extension = pathinfo($document->file_name, PATHINFO_EXTENSION);
                                        $isPdf = in_array($extension, ['pdf']);
                                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png']);
                                    @endphp

                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-blue-500">
                                        <i class="fas {{ $isPdf ? 'fa-file-pdf' : ($isImage ? 'fa-image' : 'fa-file') }} text-lg text-black"></i>
                                    </div>

                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ $document->file_name }}</h3>
                                        <p class="text-sm text-gray-600">
                                            {{ strtoupper($extension) }} • Uploaded {{ $document->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <button
                                        @click="selectedDocument = {{ $index }}"
                                        class="p-2 bg-green-800 text-dark rounded-lg hover:bg-green-900 transition-colors duration-200"
                                    >
                                        <i class="fas fa-eye" style="color: black;"></i>
                                    </button>
                                    <a href="{{ asset('storage/' . $document->file_path) }}"
                                       target="_blank"
                                       class="px-3 py-2 bg-green-800 text-dark rounded-lg text-sm hover:bg-green-900 transition-colors duration-200">
                                        <i class="fas fa-external-link-alt mr-2" style="color: black;"></i>
                                        Open
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-file-upload text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">No Documents Uploaded</h3>
                        <p class="text-gray-600 mb-6">Upload supporting documents to strengthen your fund request</p>
                        @if($fundRequest->status == 'Draft')
                        <button
                            @click="uploadMode = true"
                            class="px-6 py-3 bg-green-800 text-white rounded-lg font-medium hover:bg-green-900 transition-colors duration-200"
                        >
                            <i class="fas fa-upload mr-2"></i>
                            Upload Your First Document
                        </button>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Document Preview Modal -->
            <div x-show="selectedDocument !== null"
                 x-transition
                 @click.away="selectedDocument = null"
                 @keydown.escape.window="selectedDocument = null"
                 class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-75 flex items-center justify-center p-4">

                <div class="bg-white rounded-lg w-full max-w-4xl max-h-[90vh] overflow-hidden">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Document Preview</h3>
                        <button
                            @click="selectedDocument = null"
                            class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors duration-200"
                        >
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Modal Content -->
                    <div class="p-4 max-h-[80vh] overflow-auto">
                        @foreach($fundRequest->documents as $index => $document)
                        <div x-show="selectedDocument === {{ $index }}">
                            @php
                                $extension = pathinfo($document->file_name, PATHINFO_EXTENSION);
                                $isPdf = in_array($extension, ['pdf']);
                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png']);
                            @endphp

                            @if($isPdf)
                                <embed src="{{ asset('storage/' . $document->file_path) }}"
                                       type="application/pdf"
                                       width="100%"
                                       height="600px"
                                       class="rounded-lg">
                            @elseif($isImage)
                                <img src="{{ asset('storage/' . $document->file_path) }}"
                                     alt="{{ $document->file_name }}"
                                     class="max-w-full h-auto mx-auto rounded-lg">
                            @else
                                <div class="text-center py-12">
                                    <i class="fas fa-file text-gray-400 text-6xl mb-4"></i>
                                    <p class="text-gray-600 mb-4">Preview not available for this file type</p>
                                    <a href="{{ asset('storage/' . $document->file_path) }}"
                                       target="_blank"
                                       class="px-4 py-2 bg-green-800 text-white rounded-lg hover:bg-green-900 transition-colors duration-200">
                                        <i class="fas fa-external-link-alt mr-2"></i>
                                        Open in New Tab
                                    </a>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('openModal', null);
    });
</script>

@endsection
