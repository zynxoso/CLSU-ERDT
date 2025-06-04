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
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm mb-4 p-4">
            <!-- Horizontal Progress Bar with Icons -->
            <div class="pt-1">
                <div class="flex items-center text-xs text-gray-700 mb-5">
                    <i class="fas fa-route" style="margin-right: 0.5rem; color: #2563eb;"></i>
                    <span class="font-medium">Request Progress</span>
                </div>

                <div class="relative mt-6 mb-8 progress-container">
                    <!-- Progress Bar Background -->
                    <div class="h-1.5 bg-gray-200 rounded-full w-full"></div>

                    <!-- Active Progress Bar -->
                    <div class="progress-bar h-1.5 {{ $fundRequest->status == 'Under Review' ? 'bg-purple-500' : 'bg-blue-500' }} rounded-full absolute top-0 left-0 transition-all duration-1000 ease-out"
                         style="width: {{ $fundRequest->status == 'Submitted' ? '33%' : ($fundRequest->status == 'Under Review' ? '66%' : ($fundRequest->status == 'Approved' ? '100%' : '0%')) }}; {{ $fundRequest->status != 'Approved' ? 'max-width: ' . ($fundRequest->status == 'Submitted' ? '33%' : ($fundRequest->status == 'Under Review' ? '66%' : '0%')) : '' }}"></div>

                    <!-- Status Icons -->
                    <div class="status-icons flex justify-between absolute w-full top-0 transform -translate-y-1/2">
                        <!-- Submitted Icon -->
                        <div class="status-icon-wrapper flex flex-col items-center {{ $fundRequest->status == 'Submitted' ? 'active' : ($fundRequest->status == 'Under Review' || $fundRequest->status == 'Approved' ? 'completed' : '') }}" data-status="Submitted">
                            <div class="w-6 h-6 rounded-full {{ $fundRequest->status == 'Submitted' ? 'bg-blue-600 border-blue-700' : ($fundRequest->status == 'Under Review' || $fundRequest->status == 'Approved' ? 'bg-green-600 border-green-700' : 'bg-gray-600 border-gray-700') }} border-2 flex items-center justify-center shadow-sm status-icon {{ $fundRequest->status == 'Submitted' ? 'active' : ($fundRequest->status == 'Under Review' || $fundRequest->status == 'Approved' ? 'completed' : '') }}">
                                <i class="fas fa-paper-plane text-xs {{ $fundRequest->status == 'Submitted' ? 'text-white' : ($fundRequest->status == 'Under Review' || $fundRequest->status == 'Approved' ? 'text-white' : 'text-white') }}"></i>
                            </div>
                            <p class="text-xs text-center mt-1.5 font-medium {{ $fundRequest->status == 'Submitted' ? 'text-blue-700' : ($fundRequest->status == 'Under Review' || $fundRequest->status == 'Approved' ? 'text-green-700' : 'text-gray-500') }}">Submitted</p>
                        </div>
                        <!-- Under Review Icon -->
                        <div class="status-icon-wrapper flex flex-col items-center {{ $fundRequest->status == 'Under Review' ? 'active' : ($fundRequest->status == 'Approved' ? 'completed' : '') }}" data-status="Under Review">
                            <div class="w-6 h-6 rounded-full {{ $fundRequest->status == 'Under Review' ? 'bg-purple-600 border-purple-700' : ($fundRequest->status == 'Approved' ? 'bg-green-600 border-green-700' : 'bg-gray-600 border-gray-700') }} border-2 flex items-center justify-center shadow-sm status-icon {{ $fundRequest->status == 'Under Review' ? 'active' : ($fundRequest->status == 'Approved' ? 'completed' : '') }}">
                                <i class="fas fa-search text-xs {{ $fundRequest->status == 'Under Review' ? 'text-white' : ($fundRequest->status == 'Approved' ? 'text-white' : 'text-white') }}"></i>
                            </div>
                            <p class="text-xs text-center mt-1.5 font-medium {{ $fundRequest->status == 'Under Review' ? 'text-purple-600' : ($fundRequest->status == 'Approved' ? 'text-green-700' : 'text-gray-500') }}">Under Review</p>
                        </div>
                        <!-- Approved Icon -->
                        <div class="status-icon-wrapper flex flex-col items-center {{ $fundRequest->status == 'Approved' ? 'active' : '' }}" data-status="Approved">
                            <div class="w-6 h-6 rounded-full {{ $fundRequest->status == 'Approved' ? 'bg-green-600 border-green-700' : 'bg-gray-300 border-gray-400' }} border-2 flex items-center justify-center shadow-sm status-icon {{ $fundRequest->status == 'Approved' ? 'active' : '' }} opacity-{{ $fundRequest->status == 'Approved' ? '100' : '50' }}">
                                <i class="fas fa-check text-xs {{ $fundRequest->status == 'Approved' ? 'text-white' : 'text-gray-400' }}"></i>
                            </div>
                            <p class="text-xs text-center mt-1.5 font-medium {{ $fundRequest->status == 'Approved' ? 'text-green-700' : 'text-gray-400' }}">Approved</p>
                        </div>
                    </div>

                    <!-- Shipping truck icon that moves along the progress bar -->
                    @if($fundRequest->status != 'Approved')
                    <div class="shipping-truck absolute top-0 transform -translate-y-1/2 -translate-x-1/2 z-10"
                         style="left: {{ $fundRequest->status == 'Submitted' ? '33%' : ($fundRequest->status == 'Under Review' ? '66%' : ($fundRequest->status == 'Approved' ? '100%' : '0%')) }};">
                        <i class="fas fa-truck text-sm" style="color: #2563eb !important;"></i>
                    </div>
                    @endif
                </div>

                <div class="flex justify-between">
                    <div class="text-xs text-gray-500">
                        <span>Status updated to <span class="font-medium
                            {{ $fundRequest->status == 'Approved' ? 'text-green-700' :
                              ($fundRequest->status == 'Under Review' ? 'text-purple-600' :
                              ($fundRequest->status == 'Submitted' ? 'text-blue-700' : 'text-gray-700')) }}">{{ $fundRequest->status }}</span>:</span>
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
            </div>
        </div>
        @endif

        <!-- Fund Request Details -->
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm mb-4">
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                <h2 class="text-base font-semibold text-gray-800">Request Information</h2>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-xs text-gray-500 mb-0.5">Request ID</h3>
                        <p class="text-sm text-gray-800">FR-{{ $fundRequest->id }}</p>
                    </div>
                    <div>
                        <h3 class="text-xs text-gray-500 mb-0.5">Date Requested</h3>
                        <p class="text-sm text-gray-800">{{ $fundRequest->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-xs text-gray-500 mb-0.5">Amount</h3>
                        <p class="text-base font-bold text-gray-800">₱{{ number_format($fundRequest->amount, 2) }}</p>
                    </div>
                    <div>
                        <h3 class="text-xs text-gray-500 mb-0.5">Purpose</h3>
                        <p class="text-sm text-gray-800">{{ $fundRequest->purpose }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <h3 class="text-xs text-gray-500 mb-0.5">Details</h3>
                        <p class="text-sm text-gray-800">{{ $fundRequest->details ?? 'No additional details provided.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Feedback (if any) -->
        @if($fundRequest->admin_notes)
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm mb-6">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Admin Feedback</h2>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <h3 class="text-sm text-gray-500 mb-1">Reviewed By</h3>
                    <p class="text-gray-800">{{ $fundRequest->reviewedBy ? $fundRequest->reviewedBy->name : 'Not yet reviewed' }}</p>
                </div>
                <div>
                    <h3 class="text-sm text-gray-500 mb-1">Notes</h3>
                    <p class="text-gray-800">{{ $fundRequest->admin_notes }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Supporting Documents -->
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm mb-6">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Supporting Documents</h2>
            </div>
            <div class="p-6">
                @if($fundRequest->documents->isNotEmpty())
                    @foreach($fundRequest->documents as $document)
                        <div>
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                                <div>
                                    <h3 class="text-sm text-gray-500 mb-1">Document Name</h3>
                                    <p class="text-gray-800 font-medium">{{ $document->file_name }}</p>
                                </div>
                                <div class="mt-2 md:mt-0">
                                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-300 inline-flex items-center">
                                        <i class="fas fa-external-link-alt mr-2" style="color: white !important;"></i> Open in New Tab
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Document Preview -->
                            <div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
                                @if(in_array(pathinfo($document->file_name, PATHINFO_EXTENSION), ['pdf']))
                                    <div class="bg-gray-100 p-4 text-center">
                                        <embed src="{{ asset('storage/' . $document->file_path) }}" type="application/pdf" width="100%" height="500px" />
                                    </div>
                                @elseif(in_array(pathinfo($document->file_name, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                    <div class="bg-gray-100 p-4 text-center">
                                        <img src="{{ asset('storage/' . $document->file_path) }}" alt="{{ $document->file_name }}" class="max-w-full h-auto mx-auto" style="max-height: 500px;">
                                    </div>
                                @else
                                    <div class="bg-gray-100 p-4 text-center">
                                        <p class="text-gray-600">Preview not available for this file type. Please click "Open in New Tab" to view the document.</p>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Document Status -->
                            <!-- <div class="mt-4">
                                <h3 class="text-sm text-gray-500 mb-1">Status</h3>
                                <p class="text-gray-800 {{ $document->is_verified ? 'text-green-600' : 'text-yellow-600' }}">
                                    {{ $document->is_verified ? 'Verified' : 'Pending Verification' }}
                                </p>
                            </div> -->
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-6">
                        <p class="text-gray-500">No supporting documents have been uploaded for this fund request.</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Data Privacy Information Button -->
        <div class="bg-white rounded-md overflow-hidden border border-gray-200 shadow-sm mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-white flex items-center">
                    <i class="fas fa-shield-alt mr-2"></i> Data Privacy Information
                </h2>
            </div>
            <div class="p-6 bg-gradient-to-b from-blue-50 to-white">
                <p class="text-gray-700 mb-4">Please review our data privacy policy regarding how your fund request information is processed and stored.</p>
                <button 
                    type="button" 
                    class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-300 inline-flex items-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                    @click="$dispatch('open-modal', 'data-privacy-modal')"
                >
                    <i class="fas fa-shield-alt mr-2"></i> View Data Privacy Policy
                </button>
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
    <div class="flex items-center justify-center min-h-screen px-4">
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
            class="relative bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden"
            @click.away="$store.openModal = null"
        >
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-5 sticky top-0 z-10">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-shield-alt mr-3 text-blue-200"></i> 
                        Data Privacy Policy for Fund Requests
                    </h3>
                    <button 
                        type="button" 
                        class="text-white hover:text-blue-200 focus:outline-none transition-colors duration-200"
                        @click="$store.openModal = null"
                    >
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
            </div>
            
            <!-- Tabs Navigation -->
            <div class="bg-gray-100 px-6 py-3 border-b border-gray-200 overflow-x-auto whitespace-nowrap">
                <div class="flex space-x-2">
                    <button 
                        @click="activeTab = 'collection'" 
                        :class="{'bg-blue-600 text-white': activeTab === 'collection', 'bg-white text-gray-700 hover:bg-gray-50': activeTab !== 'collection'}"
                        class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 flex items-center shadow-sm"
                    >
                        <i class="fas fa-database mr-2"></i> Information Collection
                    </button>
                    <button 
                        @click="activeTab = 'usage'" 
                        :class="{'bg-blue-600 text-white': activeTab === 'usage', 'bg-white text-gray-700 hover:bg-gray-50': activeTab !== 'usage'}"
                        class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 flex items-center shadow-sm"
                    >
                        <i class="fas fa-tasks mr-2"></i> Data Usage
                    </button>
                    <button 
                        @click="activeTab = 'security'" 
                        :class="{'bg-blue-600 text-white': activeTab === 'security', 'bg-white text-gray-700 hover:bg-gray-50': activeTab !== 'security'}"
                        class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 flex items-center shadow-sm"
                    >
                        <i class="fas fa-lock mr-2"></i> Security
                    </button>
                    <button 
                        @click="activeTab = 'retention'" 
                        :class="{'bg-blue-600 text-white': activeTab === 'retention', 'bg-white text-gray-700 hover:bg-gray-50': activeTab !== 'retention'}"
                        class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 flex items-center shadow-sm"
                    >
                        <i class="fas fa-clock mr-2"></i> Retention
                    </button>
                    <button 
                        @click="activeTab = 'rights'" 
                        :class="{'bg-blue-600 text-white': activeTab === 'rights', 'bg-white text-gray-700 hover:bg-gray-50': activeTab !== 'rights'}"
                        class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 flex items-center shadow-sm"
                    >
                        <i class="fas fa-user-shield mr-2"></i> Your Rights
                    </button>
                    <button 
                        @click="activeTab = 'contact'" 
                        :class="{'bg-blue-600 text-white': activeTab === 'contact', 'bg-white text-gray-700 hover:bg-gray-50': activeTab !== 'contact'}"
                        class="px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200 flex items-center shadow-sm"
                    >
                        <i class="fas fa-envelope mr-2"></i> Contact
                    </button>
                </div>
            </div>
            
            <!-- Modal Body with Tab Content -->
            <div class="overflow-y-auto max-h-[50vh]">
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
