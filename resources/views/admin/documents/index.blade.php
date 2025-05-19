@extends('layouts.app')

@section('title', 'Document Management')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Document Management</h1>
                <p class="mt-1 text-sm text-gray-600">View and verify scholar documents</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded-lg shadow">
            <form id="filter-form" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="Verified">Verified</option>
                        <option value="Pending">Pending</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select id="category" name="category" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Categories</option>
                        <option value="Registration Form">Registration Form</option>
                        <option value="Enrollment Form">Enrollment Form</option>
                        <option value="Grades">Grades</option>
                        <option value="Thesis/Dissertation">Thesis/Dissertation</option>
                        <option value="Research Paper">Research Paper</option>
                        <option value="Certificate">Certificate</option>
                        <option value="Fund Request">Fund Request</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="scholar" class="block text-sm font-medium text-gray-700 mb-1">Scholar</label>
                    <input type="text" id="scholar" name="scholar" placeholder="Scholar Name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                    <input type="month" id="date" name="date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-end w-full sm:w-auto">
                    <button type="button" id="filter-button" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-filter mr-2" style="color: white !important;"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Document List -->
        <div class="bg-white rounded-lg overflow-hidden shadow">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">All Documents</h2>
            </div>
            
            @if($documents->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholar</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Uploaded</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($documents as $document)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-gray-100 rounded-lg">
                                                @if(in_array(pathinfo($document->file_name, PATHINFO_EXTENSION), ['pdf']))
                                                    <i class="fas fa-file-pdf text-red-500 text-lg"></i>
                                                @elseif(in_array(pathinfo($document->file_name, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                                                    <i class="fas fa-file-image text-blue-500 text-lg"></i>
                                                @elseif(in_array(pathinfo($document->file_name, PATHINFO_EXTENSION), ['doc', 'docx']))
                                                    <i class="fas fa-file-word text-blue-700 text-lg"></i>
                                                @else
                                                    <i class="fas fa-file text-gray-500 text-lg"></i>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $document->title }}</div>
                                                <div class="text-sm text-gray-500 truncate max-w-xs">{{ $document->file_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $document->scholarProfile->user->name ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $document->category }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $document->created_at->format('M d, Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $document->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($document->is_verified)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Verified
                                            </span>
                                        @elseif($document->status == 'Rejected')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Rejected
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.documents.show', $document->id) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('admin.documents.download', $document->id) }}" class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4">
                    {{ $documents->links() }}
                </div>
            @else
                <div class="p-6 text-center">
                    <p class="text-gray-500">No documents found.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButton = document.getElementById('filter-button');
        const filterForm = document.getElementById('filter-form');
        
        filterButton.addEventListener('click', function() {
            const status = document.getElementById('status').value;
            const category = document.getElementById('category').value;
            const scholar = document.getElementById('scholar').value;
            const date = document.getElementById('date').value;
            
            // Build the query string
            let queryParams = [];
            if (status) queryParams.push(`status=${encodeURIComponent(status)}`);
            if (category) queryParams.push(`category=${encodeURIComponent(category)}`);
            if (scholar) queryParams.push(`scholar=${encodeURIComponent(scholar)}`);
            if (date) queryParams.push(`date=${encodeURIComponent(date)}`);
            
            // Redirect with the query parameters
            window.location.href = `{{ route('admin.documents.index') }}${queryParams.length ? '?' + queryParams.join('&') : ''}`;
        });
    });
</script>
@endsection
