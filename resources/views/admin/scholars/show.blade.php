@extends('layouts.app')

@section('title', 'Scholar Details')

@section('styles')
<style>
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .animate-pulse {
        animation: pulse 1s infinite;
    }

    .btn-transition {
        transition: all 0.3s ease;
    }

    .btn-transition:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    /* Ensure all button icons have white color */
    a.bg-yellow-600 i,
    a.bg-gray-600 i,
    a.bg-blue-500 i,
    button.bg-blue-500 i {
        color: white !important;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Scholar Details</h1>
                <p class="text-gray-600">View detailed information about this scholar</p>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('admin.scholars.edit', $scholar->id) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                    <i class="fas fa-edit mr-2 text-white" style="color: white !important;"></i> Edit Scholar
                </a>
                <a href="{{ route('admin.scholars.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    <i class="fas fa-arrow-left mr-2 text-white" style="color: white !important;"></i> Back to List
                </a>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
            <!-- Header with Scholar Basic Info -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="h-20 w-20 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                        @if($scholar->user->profile_photo)
                            <img src="{{ asset('storage/' . $scholar->user->profile_photo) }}" alt="{{ $scholar->user->name }}" class="h-20 w-20 rounded-full object-cover">
                        @else
                            <i class="fas fa-user-graduate text-blue-500 text-4xl"></i>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $scholar->first_name }} {{ $scholar->middle_name }} {{ $scholar->last_name }}</h2>
                        <p class="text-gray-600">{{ $scholar->user->email }}</p>
                        <div class="flex mt-2">
                            <span class="px-3 py-1 text-xs rounded-full
                                @if($scholar->status == 'Active') bg-green-100 text-green-800
                                @elseif($scholar->status == 'Inactive') bg-red-100 text-red-800
                                @elseif($scholar->status == 'Completed') bg-blue-100 text-blue-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ $scholar->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scholar Details Tabs -->
            <div class="border-b border-gray-200">
                <nav class="flex flex-wrap">
                    <button class="px-6 py-3 border-b-2 border-blue-500 font-medium text-sm leading-5 text-blue-600 focus:outline-none transition-all duration-200 hover:bg-blue-50" id="profile-tab">
                        Profile
                    </button>
                    <button class="px-6 py-3 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition-all duration-200 hover:bg-gray-50" id="fund-requests-tab">
                        Fund Requests
                    </button>
                    <button class="px-6 py-3 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition-all duration-200 hover:bg-gray-50" id="manuscripts-tab">
                        Manuscripts
                    </button>
                    <button class="px-6 py-3 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition-all duration-200 hover:bg-gray-50" id="documents-tab">
                        Documents
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Profile Tab -->
                <div id="profile-content" class="tab-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Personal Information</h3>
                                <div class="mt-4 space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Full Name:</span>
                                        <span class="text-gray-900 font-medium">{{ $scholar->first_name }} {{ $scholar->middle_name }} {{ $scholar->last_name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Email:</span>
                                        <span class="text-gray-900">{{ $scholar->user->email }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Contact Number:</span>
                                        <span class="text-gray-900">{{ $scholar->contact_number ?? 'Not provided' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Address:</span>
                                        <span class="text-gray-900">{{ $scholar->address ?? 'Not provided' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Account Information</h3>
                                <div class="mt-4 space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">User ID:</span>
                                        <span class="text-gray-900">{{ $scholar->user->id }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Role:</span>
                                        <span class="text-gray-900">{{ ucfirst($scholar->user->role) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Joined Date:</span>
                                        <span class="text-gray-900">{{ $scholar->user->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Academic Information</h3>
                                <div class="mt-4 space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">University:</span>
                                        <span class="text-gray-900">{{ $scholar->university }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Department:</span>
                                        <span class="text-gray-900">{{ $scholar->department }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Program:</span>
                                        <span class="text-gray-900">{{ $scholar->program }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Research Area:</span>
                                        <span class="text-gray-900">{{ $scholar->research_area ?? 'Not specified' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Program Information</h3>
                                <div class="mt-4 space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Status:</span>
                                        <span class="text-gray-900">{{ $scholar->status }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Start Date:</span>
                                        <span class="text-gray-900">{{ $scholar->start_date ? date('M d, Y', strtotime($scholar->start_date)) : 'Not set' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Expected Completion:</span>
                                        <span class="text-gray-900">{{ $scholar->expected_completion_date ? date('M d, Y', strtotime($scholar->expected_completion_date)) : 'Not set' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Progress:</span>
                                        <div class="w-1/2">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $scholar->progress ?? 0 }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-500 mt-1">{{ $scholar->progress ?? 0 }}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Previous Education</h3>
                                <div class="mt-4 space-y-4">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Bachelor Degree:</span>
                                        <span class="text-gray-900">{{ $scholar->bachelor_degree ?? 'Not provided' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">University:</span>
                                        <span class="text-gray-900">{{ $scholar->bachelor_university ?? 'Not provided' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Graduation Year:</span>
                                        <span class="text-gray-900">{{ $scholar->bachelor_graduation_year ?? 'Not provided' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fund Requests Tab (Hidden by default) -->
                <div id="fund-requests-content" class="tab-content hidden">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Fund Requests</h3>

                    @if($scholar->fundRequests->count() > 0)
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($scholar->fundRequests as $request)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $request->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->purpose }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₱{{ number_format($request->amount, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs rounded-full
                                                    @if($request->status == 'Approved') bg-green-100 text-green-800
                                                    @elseif($request->status == 'Rejected') bg-red-100 text-red-800
                                                    @elseif($request->status == 'Submitted') bg-blue-100 text-blue-800
                                                    @elseif($request->status == 'Under Review') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $request->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->created_at->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.fund-requests.show', $request->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-6 text-center bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-gray-500">No fund requests found for this scholar.</p>
                        </div>
                    @endif
                </div>

                <!-- Manuscripts Tab (Hidden by default) -->
                <div id="manuscripts-content" class="tab-content hidden">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Manuscripts</h3>

                    @if($scholar->manuscripts->count() > 0)
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($scholar->manuscripts as $manuscript)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ $manuscript->title }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $manuscript->manuscript_type }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs rounded-full
                                                    @if($manuscript->status == 'Published') bg-green-100 text-green-800
                                                    @elseif($manuscript->status == 'Rejected') bg-red-100 text-red-800
                                                    @elseif($manuscript->status == 'Accepted') bg-blue-100 text-blue-800
                                                    @elseif($manuscript->status == 'Under Review') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $manuscript->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $manuscript->created_at->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.manuscripts.show', $manuscript->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-6 text-center bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-gray-500">No manuscripts found for this scholar.</p>
                        </div>
                    @endif
                </div>

                <!-- Documents Tab (Hidden by default) -->
                <div id="documents-content" class="tab-content hidden">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Documents</h3>

                    @if($scholar->documents->count() > 0)
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($scholar->documents as $document)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ $document->title }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $document->type }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs rounded-full
                                                    @if($document->status == 'Verified') bg-green-100 text-green-800
                                                    @elseif($document->status == 'Rejected') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    {{ $document->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $document->created_at->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.documents.show', $document->id) }}" class="text-blue-600 hover:text-blue-900 mr-2">View</a>
                                                <a href="{{ route('admin.documents.download', $document->id) }}" class="text-green-600 hover:text-green-900">Download</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-6 text-center bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-gray-500">No documents found for this scholar.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Tab functionality
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = {
            'profile-tab': 'profile-content',
            'fund-requests-tab': 'fund-requests-content',
            'manuscripts-tab': 'manuscripts-content',
            'documents-tab': 'documents-content'
        };

        // Function to activate a tab
        function activateTab(tabId) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Reset all tab buttons
            document.querySelectorAll('button[id$="-tab"]').forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });

            // Activate the selected tab
            const contentId = tabs[tabId];
            document.getElementById(contentId).classList.remove('hidden');

            // Activate the tab button
            const button = document.getElementById(tabId);
            button.classList.remove('border-transparent', 'text-gray-500');
            button.classList.add('border-blue-500', 'text-blue-600');
        }

        // Add click event listeners to tabs
        Object.keys(tabs).forEach(tabId => {
            document.getElementById(tabId).addEventListener('click', function() {
                activateTab(tabId);
            });
        });
    });
</script>
@endsection

@endsection
