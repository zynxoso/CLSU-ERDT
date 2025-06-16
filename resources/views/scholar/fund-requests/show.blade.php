@extends('layouts.app')

@section('title', 'Fund Request Details')

@section('content')
<div class="min-h-screen">
    <div class="container mx-autO">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mt-2">Fund Request Details</h1>
        </div>

        <!-- Status Card -->
        <!-- <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="w-12 h-12 rounded-full
                        @if($fundRequest->status == 'Approved') bg-green-500
                        @elseif($fundRequest->status == 'Rejected') bg-red-500
                        @elseif($fundRequest->status == 'Submitted') bg-yellow-500
                        @else bg-blue-500 @endif
                        flex items-center justify-center mr-4">
                        <i class="fas
                            @if($fundRequest->status == 'Approved') fa-check
                            @elseif($fundRequest->status == 'Rejected') fa-times
                            @elseif($fundRequest->status == 'Submitted') fa-clock
                            @else fa-edit @endif"
                            style="color: white !important;"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <p class="text-lg font-bold
                            @if($fundRequest->status == 'Approved') text-green-600
                            @elseif($fundRequest->status == 'Rejected') text-red-600
                            @elseif($fundRequest->status == 'Submitted') text-yellow-600
                            @else text-blue-600 @endif">
                            {{ $fundRequest->status }}
                        </p>
                    </div>

                </div>

                <div class="flex flex-wrap gap-2">
                    @if($fundRequest->status == 'Draft')
                        <a href="{{ route('scholar.fund-requests.edit', $fundRequest->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors duration-300">
                            <i class="fas fa-edit mr-2" style="color: white !important;"></i> Edit Request
                        </a>
                        <form action="{{ route('scholar.fund-requests.submit', $fundRequest->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-300">
                                <i class="fas fa-paper-plane mr-2" style="color: white !important;"></i> Submit Request
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div> -->

        <!-- Request Progress -->
        @if($fundRequest->status != 'Draft' && $fundRequest->status != 'Rejected')
        <div class="bg-white rounded-xl overflow-hidden border border-gray-200 shadow-lg mb-6 p-6">
            <!-- Enhanced Progress Bar with Icons -->
            <div class="pt-2">
                <div class="flex items-center text-sm text-gray-700 mb-6">
                    <span class="font-semibold text-gray-800">Request Progress</span>
                </div>

                <div class="relative mt-8 mb-10 progress-container">
                    <!-- Enhanced Progress Bar Background -->
                    <div class="h-2 bg-gray-200 rounded-full w-full shadow-inner"></div>

                    <!-- Enhanced Active Progress Bar with Gradient -->
                    @php
                        $progressWidth = $fundRequest->status == 'Submitted' ? '1%' :
                                       ($fundRequest->status == 'Under Review' ? '50%' :
                                       ($fundRequest->status == 'Approved' ? '100%' : '0%'));

                        $progressColor = $fundRequest->status == 'Approved' ? 'bg-green-500' : 'bg-blue-500';
                    @endphp

                    <div class="progress-bar h-2 {{ $progressColor }} rounded-full absolute top-0 left-0 transition-all duration-1500 ease-out shadow-md"
                         style="width: {{ $progressWidth }};">
                        <!-- Animated shimmer effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-30 animate-pulse rounded-full"></div>
                    </div>

                    <!-- Enhanced Status Icons -->
                    <div class="status-icons flex justify-between absolute w-full top-0 transform -translate-y-1/2 mt-5">
                        <!-- Submitted Icon -->
                        <div class="status-icon-wrapper flex flex-col items-center transition-all duration-500" data-status="Submitted">
                            <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center shadow-lg transform transition-all duration-500 hover:scale-110
                                @if($fundRequest->status == 'Submitted')
                                    bg-blue-500 border-blue-600
                                @elseif(in_array($fundRequest->status, ['Under Review', 'Approved']))
                                    bg-green-500 border-green-600
                                @else
                                    bg-gray-400 border-gray-500
                                @endif">
                                <i class="fas fa-paper-plane text-sm text-white"></i>
                            </div>
                            <p class="text-xs text-center mt-2 font-semibold transition-colors duration-300
                                @if($fundRequest->status == 'Submitted')
                                    text-blue-700
                                @elseif(in_array($fundRequest->status, ['Under Review', 'Approved']))
                                    text-green-700
                                @else
                                    text-gray-500
                                @endif">
                                Submitted
                            </p>
                        </div>

                        <!-- Under Review Icon -->
                        <div class="status-icon-wrapper flex flex-col items-center transition-all duration-500" data-status="Under Review">
                            <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center shadow-lg transform transition-all duration-500 hover:scale-110
                                @if($fundRequest->status == 'Under Review')
                                    bg-blue-500 border-blue-600
                                @elseif($fundRequest->status == 'Approved')
                                    bg-green-500 border-green-600
                                @else
                                    bg-gray-400 border-gray-500
                                @endif">
                                <i class="fas fa-search text-sm text-white"></i>
                            </div>
                            <p class="text-xs text-center mt-2 font-semibold transition-colors duration-300
                                @if($fundRequest->status == 'Under Review')
                                    text-blue-700
                                @elseif($fundRequest->status == 'Approved')
                                    text-green-700
                                @else
                                    text-gray-500
                                @endif">
                                Under Review
                            </p>
                        </div>

                        <!-- Approved Icon -->
                        <div class="status-icon-wrapper flex flex-col items-center transition-all duration-500" data-status="Approved">
                            <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center shadow-lg transform transition-all duration-500 hover:scale-110
                                @if($fundRequest->status == 'Approved')
                                    bg-green-500 border-green-600
                                @else
                                    bg-gray-300 border-gray-400 opacity-60
                                @endif">
                                <i class="fas fa-check text-sm @if($fundRequest->status == 'Approved') text-white @else text-gray-400 @endif"></i>
                            </div>
                            <p class="text-xs text-center mt-2 font-semibold transition-colors duration-300
                                @if($fundRequest->status == 'Approved')
                                    text-green-700
                                @else
                                    text-gray-400
                                @endif">
                                Approved
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Status Information -->
                <div class="bg-gray-50 rounded-lg p-4 mt-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 rounded-full {{ $fundRequest->status == 'Approved' ? 'bg-green-500' : 'bg-blue-500' }}"></div>
                            <div class="text-sm text-gray-600">
                                <span>Current Status: </span>
                                <span class="font-bold text-lg
                                    {{ $fundRequest->status == 'Approved' ? 'text-green-700 bg-green-100 px-3 py-1 rounded-full' :
                                      ($fundRequest->status == 'Under Review' ? 'text-blue-700 bg-blue-100 px-3 py-1 rounded-full' :
                                      ($fundRequest->status == 'Submitted' ? 'text-blue-700 bg-blue-100 px-3 py-1 rounded-full' : 'text-gray-700')) }}">
                                    {{ $fundRequest->status }}
                                </span>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">
                            <i class="fas fa-clock mr-1 "  style="color: #0055cc;"></i>
                            <span class="last-update-time">
                                @php
                                    $statusTimestamp = $fundRequest->updated_at;
                                    if ($fundRequest->status_history && is_array($fundRequest->status_history)) {
                                        // Find the most recent entry for the current status
                                        foreach (array_reverse($fundRequest->status_history) as $history) {
                                            if (isset($history['status']) && $history['status'] === $fundRequest->status && isset($history['timestamp'])) {
                                                $statusTimestamp = \Carbon\Carbon::parse($history['timestamp']);
                                                break;
                                            }
                                        }
                                    }
                                @endphp
                                {{ $statusTimestamp->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    <!-- Progress percentage -->
                    <div class="mt-3">
                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                            <span>Progress</span>
                            <span>{{ $fundRequest->status == 'Submitted' ? '33%' : ($fundRequest->status == 'Under Review' ? '66%' : ($fundRequest->status == 'Approved' ? '100%' : '0%')) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Enhanced Request Information Section -->
        <div x-data="{
            showDetails: true,
            editMode: false,
            requestData: {
                amount: '{{ $fundRequest->amount }}',
                purpose: '{{ $fundRequest->purpose }}',
                details: '{{ $fundRequest->details ?? '' }}'
            }
        }" class="bg-gray-100 rounded-xl shadow-2xl border border-gray-300 mb-8 overflow-hidden">

            <!-- Section Header -->
            <div class="bg-gradient-to-r from-blue-400 to-indigo-400 border-b border-gray-200">
                <div class="px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-white to-gray-100 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-file-invoice-dollar  text-lg" style="color: #434343;"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-dark">Request Information</h2>
                                <p class="text-sm text-blue-100 mt-1">Complete details of your funding request</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-3">
                            <button
                                @click="showDetails = !showDetails"
                                class="inline-flex items-center px-4 py-2 bg-blue-600
                                border border-blue-500 rounded-lg text-sm font-medium
                                text-white hover:bg-blue-700 hover:border-blue-600
                                transition-all duration-200 shadow-sm"
                                :class="{ 'bg-blue-700 border-blue-600': !showDetails }"
                            >
                                <i class="fas fa-eye mr-2" :class="{ 'fa-eye-slash': !showDetails }"></i>
                                <span x-text="showDetails ? 'Hide Details' : 'Show Details'"></span>
                            </button>

                            @if($fundRequest->status == 'Draft')
                            <button
                                @click="editMode = !editMode"
                                class="inline-flex items-center px-4 py-2 bg-blue-800 text-white rounded-lg text-sm font-medium hover:bg-blue-900 transition-all duration-200 shadow-sm hover:shadow-md"
                            >
                                <i class="fas fa-edit mr-2"></i>
                                <span x-text="editMode ? 'Cancel Edit' : 'Edit Request'"></span>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Request Details Content -->
            <div x-show="showDetails"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="p-6 bg-white">

                <!-- Status Badge -->
                <div class="mb-6">
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                        {{ $fundRequest->status == 'Approved' ? 'bg-green-100 text-green-800 border border-green-200' :
                          ($fundRequest->status == 'Under Review' ? 'bg-blue-100 text-blue-800 border border-blue-200' :
                          ($fundRequest->status == 'Submitted' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' :
                          ($fundRequest->status == 'Draft' ? 'bg-gray-100 text-gray-800 border border-gray-200' : 'bg-red-100 text-red-800 border border-red-200'))) }}">
                        <div class="w-2 h-2 rounded-full mr-2
                            {{ $fundRequest->status == 'Approved' ? 'bg-green-500' :
                              ($fundRequest->status == 'Under Review' ? 'bg-blue-500' :
                              ($fundRequest->status == 'Submitted' ? 'bg-yellow-500' :
                              ($fundRequest->status == 'Draft' ? 'bg-gray-500' : 'bg-red-500'))) }}">
                        </div>
                        {{ $fundRequest->status }}
                    </div>
                </div>

                <!-- Information Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Request ID Card -->
                        <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl p-4 border border-gray-200 shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">Request ID</h3>
                                <i class="fas fa-hashtag text-gray-500" style="color: #424242;"></i>
                            </div>
                            <p class="text-2xl font-bold text-gray-900 font-mono">FR-{{ $fundRequest->id }}</p>
                            <p class="text-xs text-gray-600 mt-1">Unique identifier for tracking</p>
                        </div>

                        <!-- Date Requested Card -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-100 shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">Date Requested</h3>
                                <i class="fas fa-calendar-alt text-blue-600" style="color: #06007c;"></i>
                            </div>
                            <p class="text-xl font-bold text-gray-900">{{ $fundRequest->created_at->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-600 mt-1">{{ $fundRequest->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Amount Card -->
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border border-green-100 shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">Requested Amount</h3>
                                <i class="fas fa-peso-sign text-green-600" style="color: #167b00;"></i>
                            </div>
                            <div x-show="!editMode">
                                <p class="text-3xl font-bold text-green-700">₱{{ number_format($fundRequest->amount, 2) }}</p>
                            </div>
                            <div x-show="editMode" x-transition>
                                <input
                                    type="number"
                                    x-model="requestData.amount"
                                    class="w-full px-3 py-2 bg-white border border-gray-400 rounded-lg text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    step="0.01"
                                >
                            </div>
                        </div>

                        <!-- Purpose Card -->
                        <div class="bg-gradient-to-br from-purple-50 to-violet-50 rounded-xl p-6 border border-purple-100 shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">Purpose</h3>
                                <i class="fas fa-bullseye text-purple-600" style="color: #505050;"></i>
                            </div>
                            <div x-show="!editMode">
                                <p class="text-lg font-semibold text-gray-900">{{ $fundRequest->purpose }}</p>
                            </div>
                            <div x-show="editMode" x-transition>
                                <input
                                    type="text"
                                    x-model="requestData.purpose"
                                    class="w-full px-3 py-2 bg-white border border-gray-400 rounded-lg text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Details Section -->
                <div class="mt-8">
                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl p-6 border border-amber-100 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-align-left text-amber-600 mr-3"></i>
                                Additional Details
                            </h3>
                        </div>
                        <div x-show="!editMode">
                            <p class="text-gray-800 leading-relaxed">
                                {{ $fundRequest->details ?? 'No additional details provided.' }}
                            </p>
                        </div>
                        <div x-show="editMode" x-transition>
                            <textarea
                                x-model="requestData.details"
                                rows="4"
                                class="w-full px-4 py-3 bg-white border border-gray-400 rounded-lg text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                placeholder="Provide additional details about your request..."
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Save Changes Button (only visible in edit mode) -->
                <div x-show="editMode" x-transition class="mt-6 flex justify-end space-x-3">
                    <button
                        @click="editMode = false"
                        class="px-6 py-2 border border-blue-300 text-blue-700 rounded-lg hover:bg-blue-50 transition-colors duration-200"
                    >
                        Cancel
                    </button>
                    <button
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm hover:shadow-md"
                    >
                        <i class="fas fa-save mr-2"></i>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>

        <!-- Admin Feedback (if any) -->
        @if($fundRequest->admin_notes)
        <div class="bg-gray-900 rounded-xl overflow-hidden border border-gray-700 shadow-2xl mb-6">
            <div class="bg-gray-800 px-6 py-4 border-b border-gray-700">
                <h2 class="text-lg font-semibold text-white">Admin Feedback</h2>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <h3 class="text-sm text-gray-400 mb-1">Reviewed By</h3>
                    <p class="text-gray-200">{{ $fundRequest->reviewedBy ? $fundRequest->reviewedBy->name : 'Not yet reviewed' }}</p>
                </div>
                <div>
                    <h3 class="text-sm text-gray-400 mb-1">Notes</h3>
                    <p class="text-gray-200">{{ $fundRequest->admin_notes }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Enhanced Supporting Documents Section -->
        <div x-data="{
            showDocuments: true,
            selectedDocument: null,
            viewMode: 'grid',
            uploadMode: false,
            dragOver: false
        }" class="bg-gray-100 rounded-xl shadow-2xl border border-gray-300 mb-8 overflow-hidden">

            <!-- Section Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 border-b border-gray-200">
                <div class="px-6 py-5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-white to-gray-100 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-file-alt text-gray-700 text-lg" style="color: #434343;"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-dark">Supporting Documents</h2>
                                <p class="text-sm text-blue-100 mt-1">
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
                            <div class="flex items-center bg-gray-200 rounded-lg border border-blue-600 p-1">
                                <button
                                    @click="viewMode = 'grid'"
                                    :class="{ 'bg-blue-500 text-white': viewMode === 'grid', 'text-gray-700 hover:text-gray-900': viewMode !== 'grid' }"
                                    class="p-2 rounded-md transition-all duration-200"
                                    title="Grid View"
                                >
                                    <i class="fas fa-th-large"></i>
                                </button>
                                <button
                                    @click="viewMode = 'list'"
                                    :class="{ 'bg-blue-500 text-white': viewMode === 'list', 'text-gray-700 hover:text-gray-900': viewMode !== 'list' }"
                                    class="p-2 rounded-md transition-all duration-200"
                                    title="List View"
                                >
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                            @endif

                            <button
                                @click="showDocuments = !showDocuments"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-blue-500 rounded-lg text-sm font-medium text-white hover:bg-blue-700 hover:border-blue-600 transition-all duration-200 shadow-sm"
                                :class="{ 'bg-blue-700 border-blue-600': !showDocuments }"
                            >
                                <i class="fas fa-eye mr-2" :class="{ 'fa-eye-slash': !showDocuments }"></i>
                                <span x-text="showDocuments ? 'Hide Documents' : 'Show Documents'"></span>
                            </button>

                            @if($fundRequest->status == 'Draft')
                            <button
                                @click="uploadMode = !uploadMode"
                                class="inline-flex items-center px-4 py-2 bg-blue-800 text-white rounded-lg text-sm font-medium hover:bg-blue-900 transition-all duration-200 shadow-sm hover:shadow-md"
                            >
                                <i class="fas fa-upload mr-2"></i>
                                <span x-text="uploadMode ? 'Cancel Upload' : 'Upload Documents'"></span>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Area (only visible in upload mode) -->
            <div x-show="uploadMode"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="border-b border-gray-200 bg-white">
                <div class="p-6">
                    <div
                        @dragover.prevent="dragOver = true"
                        @dragleave.prevent="dragOver = false"
                        @drop.prevent="dragOver = false; handleFileDrop($event)"
                        :class="{ 'border-blue-400 bg-blue-50': dragOver, 'border-gray-300 bg-gray-50': !dragOver }"
                        class="border-2 border-dashed rounded-xl p-8 text-center transition-all duration-200"
                    >
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-cloud-upload-alt text-white text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Upload Supporting Documents</h3>
                            <p class="text-gray-700 mb-4">Drag and drop files here, or click to browse</p>
                            <div class="flex items-center space-x-4">
                                <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                    <i class="fas fa-folder-open mr-2"></i>
                                    Browse Files
                                </button>
                                <span class="text-sm text-gray-600">PDF, JPG, PNG up to 10MB</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Content -->
            <div x-show="showDocuments"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="p-6 bg-white">

                @if($fundRequest->documents->isNotEmpty())
                    <!-- Grid View -->
                    <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($fundRequest->documents as $index => $document)
                        <div class="bg-gradient-to-br from-white to-gray-50 rounded-md border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 group">
                            <!-- Document Preview -->
                            <div class="aspect-w-10 aspect-h-18 bg-gray-100 relative overflow-hidden">
                                @php
                                    $extension = pathinfo($document->file_name, PATHINFO_EXTENSION);
                                    $isPdf = in_array($extension, ['pdf']);
                                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png']);
                                @endphp

                                @if($isImage)
                                    <img src="{{ asset('storage/' . $document->file_path) }}"
                                         alt="{{ $document->file_name }}"
                                         class="w-full h-32 object-cover group-hover:scale-105 transition-transform duration-300">
                                @elseif($isPdf)
                                    <div class="w-full h-32 flex items-center justify-center bg-blue-400">
                                        <i class="fas fa-file-pdf text-blue-600 text-4xl"></i>
                                    </div>
                                @else
                                    <div class="w-full h-32 flex items-center justify-center bg-blue-400">
                                        <i class="fas fa-file text-blue-600 text-4xl"></i>
                                    </div>
                                @endif

                                <!-- Overlay Actions -->
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                    <div class="flex space-x-2">
                                        <button
                                            @click="selectedDocument = {{ $index }}"
                                            class="p-2 bg-blue text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200"
                                            title="Quick Preview"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ asset('storage/' . $document->file_path) }}"
                                           target="_blank"
                                           class="p-2 bg-white text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200"
                                           title="Open in New Tab">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Document Info -->
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 truncate mb-2">{{ $document->file_name }}</h3>
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
                        <div class="bg-gradient-to-r from-white to-gray-50 rounded-xl border border-gray-200 p-4 hover:shadow-md transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    @php
                                        $extension = pathinfo($document->file_name, PATHINFO_EXTENSION);
                                        $isPdf = in_array($extension, ['pdf']);
                                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png']);
                                    @endphp

                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center
                                        {{ $isPdf ? 'bg-blue-400 text-red-600' : ($isImage ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600') }}">
                                        <i class="fas {{ $isPdf ? 'fa-file-pdf' : ($isImage ? 'fa-image' : 'fa-file') }} text-lg"></i>
                                    </div>

                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $document->file_name }}</h3>
                                        <p class="text-sm text-gray-600">
                                            {{ strtoupper($extension) }} • Uploaded {{ $document->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <button
                                        @click="selectedDocument = {{ $index }}"
                                        class="p-2 text-gray-500 bg-blue-600 hover:text-blue-600 hover:bg-blue-400 rounded-lg transition-all duration-200"
                                        title="Quick Preview list"
                                    >
                                        <i class="fas fa-eye" ></i>
                                    </button>
                                    <a href="{{ asset('storage/' . $document->file_path) }}"
                                       target="_blank"
                                       class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition-colors duration-200">
                                        <i class="fas fa-external-link-alt mr-2"></i>
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
                            <i class="fas fa-file-upload text-gray-500 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Documents Uploaded</h3>
                        <p class="text-gray-600 mb-6">Upload supporting documents to strengthen your fund request</p>
                        @if($fundRequest->status == 'Draft')
                        <button
                            @click="uploadMode = true"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200 shadow-sm hover:shadow-md"
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
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 @click.away="selectedDocument = null"
                 @keydown.escape.window="selectedDocument = null"
                 class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-75 flex items-center justify-center p-2 sm:p-4">

                <div class="bg-white rounded-md w-full max-w-sm sm:max-w-lg md:max-w-xl lg:max-w-2xl max-h-[70vh] sm:max-h-[75vh] overflow-hidden shadow-2xl mx-2">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-3 sm:p-4 border-b border-gray-200">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Document Preview</h3>
                        <button
                            @click="selectedDocument = null"
                            class="p-1.5 sm:p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition-colors duration-200"
                        >
                            <i class="fas fa-times text-sm sm:text-base" style="color: #00060d;"></i>
                        </button>
                    </div>

                    <!-- Modal Content -->
                    <div class="p-3 sm:p-4 max-h-[55vh] sm:max-h-[60vh] overflow-auto">
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
                                     class="max-w-full h-auto mx-auto rounded-lg shadow-lg">
                            @else
                                <div class="text-center py-12">
                                    <i class="fas fa-file text-gray-400 text-6xl mb-4"></i>
                                    <p class="text-gray-600 mb-4">Preview not available for this file type</p>
                                    <a href="{{ asset('storage/' . $document->file_path) }}"
                                       target="_blank"
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
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

<!-- Data Privacy Modal -->
<div
    x-data="{ activeTab: 'collection' }"
    x-show="$store.openModal === 'data-privacy-modal'"
    x-on:open-modal.window="$event.detail === 'data-privacy-modal' ? $store.openModal = 'data-privacy-modal' : null"
    x-on:close-modal.window="$store.openModal = null"
    x-on:keydown.escape.window="$store.openModal = null"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
>
    <div class="flex items-center justify-center min-h-screen px-2 sm:px-4">
        <!-- Backdrop -->
        <div
            x-show="$store.openModal === 'data-privacy-modal'"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-60"
            @click="$store.openModal = null"
        ></div>

        <!-- Modal Content -->
        <div
            x-show="$store.openModal === 'data-privacy-modal'"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90"
            class="relative bg-white rounded-xl shadow-2xl w-full max-w-sm sm:max-w-lg md:max-w-xl lg:max-w-2xl max-h-[70vh] sm:max-h-[75vh] overflow-hidden mx-2"
            @click.away="$store.openModal = null"
        >
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-3 sm:px-6 py-3 sm:py-5 sticky top-0 z-10">
                <div class="flex items-center justify-between">
                    <h3 class="text-base sm:text-xl font-bold text-white flex items-center">
                        <i class="fas fa-shield-alt mr-2 sm:mr-3 text-blue-200 text-sm sm:text-base"></i>
                        <span class="hidden sm:inline">Data Privacy Policy for Fund Requests</span>
                        <span class="sm:hidden">Privacy Policy</span>
                    </h3>
                    <button
                        type="button"
                        class="text-white hover:text-blue-200 focus:outline-none transition-colors duration-200"
                        @click="$store.openModal = null"
                    >
                        <i class="fas fa-times text-base sm:text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="bg-gray-100 px-3 sm:px-6 py-2 sm:py-3 border-b border-gray-200 overflow-x-auto whitespace-nowrap">
                <div class="flex space-x-1 sm:space-x-2">
                    <button
                        @click="activeTab = 'collection'"
                        :class="{'bg-blue-600 text-white': activeTab === 'collection', 'bg-white text-gray-700 hover:bg-gray-50': activeTab !== 'collection'}"
                        class="px-2 sm:px-4 py-1.5 sm:py-2 rounded-lg font-medium text-xs sm:text-sm transition-all duration-200 flex items-center shadow-sm flex-shrink-0"
                    >
                        <i class="fas fa-database mr-1 sm:mr-2 text-xs"></i>
                        <span class="hidden sm:inline">Information Collection</span>
                        <span class="sm:hidden">Info</span>
                    </button>
                    <button
                        @click="activeTab = 'usage'"
                        :class="{'bg-blue-600 text-white': activeTab === 'usage', 'bg-white text-gray-700 hover:bg-gray-50': activeTab !== 'usage'}"
                        class="px-2 sm:px-4 py-1.5 sm:py-2 rounded-lg font-medium text-xs sm:text-sm transition-all duration-200 flex items-center shadow-sm flex-shrink-0"
                    >
                        <i class="fas fa-tasks mr-1 sm:mr-2 text-xs"></i>
                        <span class="hidden sm:inline">Data Usage</span>
                        <span class="sm:hidden">Usage</span>
                    </button>
                    <button
                        @click="activeTab = 'security'"
                        :class="{'bg-blue-600 text-white': activeTab === 'security', 'bg-white text-gray-700 hover:bg-gray-50': activeTab !== 'security'}"
                        class="px-2 sm:px-4 py-1.5 sm:py-2 rounded-lg font-medium text-xs sm:text-sm transition-all duration-200 flex items-center shadow-sm flex-shrink-0"
                    >
                        <i class="fas fa-lock mr-1 sm:mr-2 text-xs"></i>
                        <span class="hidden sm:inline">Security</span>
                        <span class="sm:hidden">Security</span>
                    </button>
                    <button
                        @click="activeTab = 'retention'"
                        :class="{'bg-blue-600 text-white': activeTab === 'retention', 'bg-white text-gray-700 hover:bg-gray-50': activeTab !== 'retention'}"
                        class="px-2 sm:px-4 py-1.5 sm:py-2 rounded-lg font-medium text-xs sm:text-sm transition-all duration-200 flex items-center shadow-sm flex-shrink-0"
                    >
                        <i class="fas fa-clock mr-1 sm:mr-2 text-xs"></i>
                        <span class="hidden sm:inline">Retention</span>
                        <span class="sm:hidden">Retention</span>
                    </button>
                    <button
                        @click="activeTab = 'rights'"
                        :class="{'bg-blue-600 text-white': activeTab === 'rights', 'bg-white text-gray-700 hover:bg-gray-50': activeTab !== 'rights'}"
                        class="px-2 sm:px-4 py-1.5 sm:py-2 rounded-lg font-medium text-xs sm:text-sm transition-all duration-200 flex items-center shadow-sm flex-shrink-0"
                    >
                        <i class="fas fa-user-shield mr-1 sm:mr-2 text-xs"></i>
                        <span class="hidden sm:inline">Your Rights</span>
                        <span class="sm:hidden">Rights</span>
                    </button>
                    <button
                        @click="activeTab = 'contact'"
                        :class="{'bg-blue-600 text-white': activeTab === 'contact', 'bg-white text-gray-700 hover:bg-gray-50': activeTab !== 'contact'}"
                        class="px-2 sm:px-4 py-1.5 sm:py-2 rounded-lg font-medium text-xs sm:text-sm transition-all duration-200 flex items-center shadow-sm flex-shrink-0"
                    >
                        <i class="fas fa-envelope mr-1 sm:mr-2 text-xs"></i>
                        <span class="hidden sm:inline">Contact</span>
                        <span class="sm:hidden">Contact</span>
                    </button>
                </div>
            </div>

            <!-- Modal Body with Tab Content -->
            <div class="overflow-y-auto max-h-[45vh] sm:max-h-[50vh]">
                <!-- Information Collection Tab -->
                <div x-show="activeTab === 'collection'" class="p-6 bg-white">
                    <div class="flex items-start mb-6">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-database text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">Information Collection and Use</h4>
                            <p class="text-gray-600 mb-4">When you submit a fund request, we collect and process the following information:</p>
                        </div>
                    </div>

                    <div class="ml-16 space-y-3">
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg border-l-4 border-blue-500">
                            <i class="fas fa-id-card text-blue-500 mr-3"></i>
                            <span>Personal identification information (name, email address, contact details)</span>
                        </div>
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg border-l-4 border-blue-500">
                            <i class="fas fa-money-bill-wave text-blue-500 mr-3"></i>
                            <span>Financial information related to your request (amount, purpose, bank details)</span>
                        </div>
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg border-l-4 border-blue-500">
                            <i class="fas fa-file-alt text-blue-500 mr-3"></i>
                            <span>Supporting documents you upload (receipts, invoices, certificates)</span>
                        </div>
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg border-l-4 border-blue-500">
                            <i class="fas fa-comments text-blue-500 mr-3"></i>
                            <span>Communication records related to your request</span>
                        </div>
                    </div>
                </div>

                <!-- Data Usage Tab -->
                <div x-show="activeTab === 'usage'" class="p-6 bg-white">
                    <div class="flex items-start mb-6">
                        <div class="bg-green-100 p-3 rounded-full mr-4">
                            <i class="fas fa-tasks text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">How We Use Your Information</h4>
                            <p class="text-gray-600 mb-4">The information collected is used for:</p>
                        </div>
                    </div>

                    <div class="ml-16 space-y-3">
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg border-l-4 border-green-500">
                            <i class="fas fa-clipboard-check text-green-500 mr-3"></i>
                            <span>Processing and evaluating your fund request</span>
                        </div>
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg border-l-4 border-green-500">
                            <i class="fas fa-hand-holding-usd text-green-500 mr-3"></i>
                            <span>Disbursing approved funds</span>
                        </div>
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg border-l-4 border-green-500">
                            <i class="fas fa-file-invoice text-green-500 mr-3"></i>
                            <span>Maintaining accurate records for audit purposes</span>
                        </div>
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg border-l-4 border-green-500">
                            <i class="fas fa-comment-dots text-green-500 mr-3"></i>
                            <span>Communicating with you about your request status</span>
                        </div>
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg border-l-4 border-green-500">
                            <i class="fas fa-balance-scale text-green-500 mr-3"></i>
                            <span>Complying with legal and regulatory requirements</span>
                        </div>
                    </div>
                </div>

                <!-- Security Tab -->
                <div x-show="activeTab === 'security'" class="p-6 bg-white">
                    <div class="flex items-start mb-6">
                        <div class="bg-purple-100 p-3 rounded-full mr-4">
                            <i class="fas fa-lock text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">Information Security</h4>
                            <p class="text-gray-600 mb-4">We implement appropriate security measures to protect your personal and financial information from unauthorized access, alteration, disclosure, or destruction. These measures include:</p>
                        </div>
                    </div>

                    <div class="ml-16 space-y-3">
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg border-l-4 border-purple-500">
                            <i class="fas fa-shield-alt text-purple-500 mr-3"></i>
                            <span>Secure storage with encryption for sensitive data</span>
                        </div>
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg border-l-4 border-purple-500">
                            <i class="fas fa-clipboard-list text-purple-500 mr-3"></i>
                            <span>Regular security audits and updates</span>
                        </div>
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg border-l-4 border-purple-500">
                            <i class="fas fa-user-lock text-purple-500 mr-3"></i>
                            <span>Access controls limiting who can view your information</span>
                        </div>
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg border-l-4 border-purple-500">
                            <i class="fas fa-virus-slash text-purple-500 mr-3"></i>
                            <span>Document scanning for malware and viruses</span>
                        </div>
                    </div>
                </div>

                <!-- Retention Tab -->
                <div x-show="activeTab === 'retention'" class="p-6 bg-white">
                    <div class="flex items-start mb-6">
                        <div class="bg-amber-100 p-3 rounded-full mr-4">
                            <i class="fas fa-clock text-amber-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">Data Retention</h4>
                            <p class="text-gray-600 mb-4">We retain your fund request information for:</p>
                        </div>
                    </div>

                    <div class="ml-16 space-y-3">
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg border-l-4 border-amber-500">
                            <i class="fas fa-hourglass-half text-amber-500 mr-3"></i>
                            <span>Active requests: Throughout the processing period</span>
                        </div>
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg border-l-4 border-amber-500">
                            <i class="fas fa-check-circle text-amber-500 mr-3"></i>
                            <span>Approved requests: For a period of 5 years after disbursement for audit purposes</span>
                        </div>
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg border-l-4 border-amber-500">
                            <i class="fas fa-times-circle text-amber-500 mr-3"></i>
                            <span>Rejected requests: For a period of 2 years</span>
                        </div>
                    </div>
                </div>

                <!-- Rights Tab -->
                <div x-show="activeTab === 'rights'" class="p-6 bg-white">
                    <div class="flex items-start mb-6">
                        <div class="bg-teal-100 p-3 rounded-full mr-4">
                            <i class="fas fa-user-shield text-teal-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">Your Rights</h4>
                            <p class="text-gray-600 mb-4">You have the right to:</p>
                        </div>
                    </div>

                    <div class="ml-16 space-y-3">
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg border-l-4 border-teal-500">
                            <i class="fas fa-eye text-teal-500 mr-3"></i>
                            <span>Access the personal information we hold about you</span>
                        </div>
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg border-l-4 border-teal-500">
                            <i class="fas fa-edit text-teal-500 mr-3"></i>
                            <span>Request correction of inaccurate information</span>
                        </div>
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg border-l-4 border-teal-500">
                            <i class="fas fa-trash-alt text-teal-500 mr-3"></i>
                            <span>Request deletion of your information (subject to our legal obligations)</span>
                        </div>
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg border-l-4 border-teal-500">
                            <i class="fas fa-ban text-teal-500 mr-3"></i>
                            <span>Object to certain processing of your information</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Tab -->
                <div x-show="activeTab === 'contact'" class="p-6 bg-white">
                    <div class="flex items-start mb-6">
                        <div class="bg-red-100 p-3 rounded-full mr-4">
                            <i class="fas fa-envelope text-red-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">Contact Information</h4>
                            <p class="text-gray-600 mb-4">If you have questions or concerns about our data privacy practices, please contact our Data Protection Officer:</p>
                        </div>
                    </div>

                    <div class="ml-16 bg-gray-50 p-5 rounded-lg border border-gray-200 shadow-sm">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-envelope text-red-500 mr-3"></i>
                            <span class="font-medium">Email:</span>
                            <a href="mailto:privacy@clsu-erdt.edu.ph" class="text-blue-600 hover:underline ml-2">privacy@clsu-erdt.edu.ph</a>
                        </div>
                        <div class="flex items-center mb-3">
                            <i class="fas fa-phone text-red-500 mr-3"></i>
                            <span class="font-medium">Phone:</span>
                            <span class="ml-2">+63 (44) 456-0123</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-red-500 mr-3"></i>
                            <span class="font-medium">Office:</span>
                            <span class="ml-2">Data Protection Office, CLSU-ERDT Building, Science City of Muñoz, Nueva Ecija</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-100 px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i> Last updated: June 2023
                </div>
                <button
                    type="button"
                    class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 flex items-center"
                    @click="$store.openModal = null"
                >
                    <i class="fas fa-check mr-2"></i> I Understand
                </button>
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
