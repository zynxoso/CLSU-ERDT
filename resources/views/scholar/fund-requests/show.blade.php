@extends('layouts.app')

@section('title', 'Fund Request Details')

@section('content')
<div class="bg-slate-900 min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <a href="{{ route('scholar.fund-requests') }}" class="text-blue-400 hover:text-blue-300">
                <i class="fas fa-arrow-left mr-2"></i> Back to Fund Requests
            </a>
            <h1 class="text-2xl font-bold text-white mt-2">Fund Request Details</h1>
        </div>

        <!-- Status Card -->
        <div class="bg-slate-800 rounded-lg p-4 border border-slate-700 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="w-12 h-12 rounded-full
                        @if($fundRequest->status == 'Approved') bg-green-500 bg-opacity-20
                        @elseif($fundRequest->status == 'Rejected') bg-red-500 bg-opacity-20
                        @elseif($fundRequest->status == 'Submitted') bg-yellow-500 bg-opacity-20
                        @else bg-blue-500 bg-opacity-20 @endif
                        flex items-center justify-center mr-4">
                        <i class="fas
                            @if($fundRequest->status == 'Approved') fa-check text-green-400
                            @elseif($fundRequest->status == 'Rejected') fa-times text-red-400
                            @elseif($fundRequest->status == 'Submitted') fa-clock text-yellow-400
                            @else fa-edit text-blue-400 @endif"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Status</p>
                        <p class="text-lg font-bold
                            @if($fundRequest->status == 'Approved') text-green-400
                            @elseif($fundRequest->status == 'Rejected') text-red-400
                            @elseif($fundRequest->status == 'Submitted') text-yellow-400
                            @else text-blue-400 @endif">
                            {{ $fundRequest->status }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    @if($fundRequest->status == 'Draft')
                        <a href="{{ route('scholar.fund-requests.edit', $fundRequest->id) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                            <i class="fas fa-edit mr-2"></i> Edit Request
                        </a>
                        <form action="{{ route('scholar.fund-requests.submit', $fundRequest->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-paper-plane mr-2"></i> Submit Request
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Fund Request Details -->
        <div class="bg-slate-800 rounded-lg overflow-hidden border border-slate-700 mb-6">
            <div class="bg-slate-900 px-6 py-4">
                <h2 class="text-lg font-semibold text-white">Request Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm text-gray-400 mb-1">Request ID</h3>
                        <p class="text-white">FR-{{ $fundRequest->id }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-400 mb-1">Date Requested</h3>
                        <p class="text-white">{{ $fundRequest->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-400 mb-1">Amount</h3>
                        <p class="text-xl font-bold text-white">₱{{ number_format($fundRequest->amount, 2) }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-400 mb-1">Purpose</h3>
                        <p class="text-white">{{ $fundRequest->purpose }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <h3 class="text-sm text-gray-400 mb-1">Details</h3>
                        <p class="text-white">{{ $fundRequest->details ?? 'No additional details provided.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Feedback (if any) -->
        @if($fundRequest->admin_notes)
        <div class="bg-slate-800 rounded-lg overflow-hidden border border-slate-700 mb-6">
            <div class="bg-slate-900 px-6 py-4">
                <h2 class="text-lg font-semibold text-white">Admin Feedback</h2>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <h3 class="text-sm text-gray-400 mb-1">Reviewed By</h3>
                    <p class="text-white">{{ $fundRequest->reviewedBy ? $fundRequest->reviewedBy->name : 'Not yet reviewed' }}</p>
                </div>
                <div>
                    <h3 class="text-sm text-gray-400 mb-1">Notes</h3>
                    <p class="text-white">{{ $fundRequest->admin_notes }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Attached Documents -->
        @if(count($fundRequest->documents) > 0)
        <div class="bg-slate-800 rounded-lg overflow-hidden border border-slate-700">
            <div class="bg-slate-900 px-6 py-4">
                <h2 class="text-lg font-semibold text-white">Attached Documents</h2>
            </div>
            <div class="p-6">
                <ul class="space-y-4">
                    @foreach($fundRequest->documents as $document)
                    <li class="flex items-center justify-between bg-slate-700 p-4 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-file-pdf text-red-400 mr-3"></i>
                            <div>
                                <p class="text-white">{{ $document->title }}</p>
                                <p class="text-xs text-gray-400">Uploaded on {{ $document->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('scholar.documents.download', $document->id) }}" class="text-blue-400 hover:text-blue-300">
                            <i class="fas fa-download"></i>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
