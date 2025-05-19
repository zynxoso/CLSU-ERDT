@extends('layouts.app')

@section('title', 'Fund Request Details')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6">
    <div class="mb-3">
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Fund Request Details</h1>
    </div>

    <!-- Main Content Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Request Details -->
        <div class="bg-white rounded-lg shadow overflow-hidden h-fit">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Request Information</h2>
            </div>

            <div class="p-6">
                <!-- Status Section -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center mb-6 border-b border-gray-100 pb-4">
                    <div class="flex items-center mb-3 sm:mb-0">
                        <div class="w-10 h-10 rounded-full
                            @if($fundRequest->status == 'Approved') bg-green-500
                            @elseif($fundRequest->status == 'Rejected') bg-red-500
                            @elseif($fundRequest->status == 'Submitted') bg-yellow-500
                            @else bg-blue-500 @endif
                            flex items-center justify-center mr-3">
                            <i class="fas text-white
                                @if($fundRequest->status == 'Approved') fa-check
                                @elseif($fundRequest->status == 'Rejected') fa-times
                                @elseif($fundRequest->status == 'Submitted') fa-clock
                                @else fa-edit @endif"></i>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">Status</p>
                            <p class="text-base font-bold
                                @if($fundRequest->status == 'Approved') text-green-600
                                @elseif($fundRequest->status == 'Rejected') text-red-600
                                @elseif($fundRequest->status == 'Submitted') text-yellow-600
                                @else text-blue-600 @endif">
                                {{ $fundRequest->status }}
                            </p>
                        </div>
                    </div>

                    <div class="flex-grow"></div>

                    @if(Auth::user()->role == 'admin' && in_array($fundRequest->status, ['Submitted', 'Under Review']))
                    <div class="flex flex-wrap gap-2">
                        <button type="button" class="px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-xs"
                            onclick="document.getElementById('approve-modal').classList.remove('hidden')">
                            <i class="fas fa-check mr-1"></i> Approve
                        </button>
                        <button type="button" class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs"
                            onclick="document.getElementById('reject-modal').classList.remove('hidden')">
                            <i class="fas fa-times mr-1"></i> Reject
                        </button>
                    </div>
                    @endif
                </div>

                <!-- Request Details Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Reference Number</p>
                        <p class="text-base font-medium">FR-{{ $fundRequest->id }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Amount</p>
                        <p class="text-base font-medium">₱{{ number_format($fundRequest->amount, 2) }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Request Type</p>
                        <p class="text-base font-medium">{{ $fundRequest->requestType->name ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Date Submitted</p>
                        <p class="text-base font-medium">{{ $fundRequest->created_at->format('F d, Y') }}</p>
                    </div>

                    <div class="sm:col-span-2">
                        <p class="text-sm text-gray-500">Purpose</p>
                        <p class="text-base font-medium">{{ $fundRequest->purpose }}</p>
                    </div>

                    @if($fundRequest->admin_notes)
                    <div class="sm:col-span-2">
                        <p class="text-sm text-gray-500">Admin Notes</p>
                        <p class="text-base font-medium">{{ $fundRequest->admin_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Supporting Documents Section -->
        <!-- <div class="bg-white rounded-lg shadow overflow-hidden h-fit"> -->
            <!-- <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Supporting Documents</h2>
            </div> -->

            <div class="bg-white rounded-lg shadow overflow-hidden h-fit">
            @if($fundRequest->documents->isNotEmpty())
                <div class="grid grid-cols-1 gap-6">
                    @foreach($fundRequest->documents as $document)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                                <div>
                                    <p class="text-sm text-gray-500">Document Name</p>
                                    <p class="text-base font-medium">{{ $document->file_name }}</p>
                                </div>
                                <div class="mt-2 md:mt-0">
                                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 inline-flex items-center">
                                        <i class="fas fa-external-link-alt mr-2"></i> Open in New Tab
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Document Preview -->
                            <div class="mt-4 border border-gray-200 rounded-lg overflow-hidden">
                                @if(in_array(pathinfo($document->file_name, PATHINFO_EXTENSION), ['pdf']))
                                    <div class="bg-gray-100 p-4 text-center overflow-hidden">
                                        <div class="w-full max-w-full overflow-hidden">
                                            <embed src="{{ asset('storage/' . $document->file_path) }}" type="application/pdf" width="100%" height="300px" class="max-w-full" />
                                        </div>
                                    </div>
                                @elseif(in_array(pathinfo($document->file_name, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                    <div class="bg-gray-100 p-4 text-center overflow-hidden">
                                        <img src="{{ asset('storage/' . $document->file_path) }}" alt="{{ $document->file_name }}" class="max-w-full h-auto mx-auto" style="max-height: 300px;">
                                    </div>
                                @else
                                    <div class="bg-gray-100 p-4 text-center">
                                        <p class="text-gray-600">Preview not available for this file type. Please click "Open in New Tab" to view the document.</p>
                                    </div>
                                @endif
                            </div>
                            
                          
                            
                            <!-- Reject Document Modal -->
                            <div id="reject-document-modal-{{ $document->id }}" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden overflow-y-auto px-4">
                                <div class="bg-white rounded-lg p-4 sm:p-6 max-w-md w-full mx-auto">
                                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-3">Reject Document</h3>
                                    <p class="mb-3 text-sm sm:text-base">Please provide a reason for rejecting this document:</p>

                                    <form action="{{ route('admin.documents.reject', $document->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="mb-4">
                                            <label for="admin_notes_{{ $document->id }}" class="block text-sm font-medium text-gray-700 mb-1">Reason for Rejection</label>
                                            <textarea id="admin_notes_{{ $document->id }}" name="admin_notes" rows="3" required
                                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"></textarea>
                                        </div>

                                        <div class="flex justify-end gap-2">
                                            <button type="button" class="px-3 py-1.5 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 text-sm"
                                                onclick="document.getElementById('reject-document-modal-{{ $document->id }}').classList.add('hidden')">
                                                Cancel
                                            </button>
                                            <button type="submit" class="px-3 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm">
                                                Reject
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6">
                    <p class="text-gray-500">No supporting documents have been uploaded for this fund request.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approve-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden overflow-y-auto px-4">
    <div class="bg-white rounded-lg p-4 sm:p-6 max-w-md w-full mx-auto">
        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-3">Approve Fund Request</h3>
        <p class="mb-3 text-sm sm:text-base">Are you sure you want to approve this fund request?</p>

        <form action="{{ route('admin.fund-requests.approve', $fundRequest->id) }}" method="POST" class="mt-4">
            @csrf
            @method('PUT')

            <div class="flex justify-end gap-2">
                <button type="button" class="px-3 py-1.5 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 text-sm"
                    onclick="document.getElementById('approve-modal').classList.add('hidden')">
                    Cancel
                </button>
                <button type="submit" class="px-3 py-1.5 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                    Approve
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="reject-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden overflow-y-auto px-4">
    <div class="bg-white rounded-lg p-4 sm:p-6 max-w-md w-full mx-auto">
        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-3">Reject Fund Request</h3>
        <p class="mb-3 text-sm sm:text-base">Please provide a reason for rejecting this fund request:</p>

        <form action="{{ route('admin.fund-requests.reject', $fundRequest->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-1">Reason for Rejection</label>
                <textarea id="admin_notes" name="admin_notes" rows="3" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" class="px-3 py-1.5 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 text-sm"
                    onclick="document.getElementById('reject-modal').classList.add('hidden')">
                    Cancel
                </button>
                <button type="submit" class="px-3 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm">
                    Reject
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
