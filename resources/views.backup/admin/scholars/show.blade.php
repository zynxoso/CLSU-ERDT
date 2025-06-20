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
    a.bg-red-700 i,
    a.bg-gray-600 i,
    a.bg-red-600 i,
    button.bg-red-600 i {
        color: white !important;
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .flex-col-mobile {
            flex-direction: column;
        }

        .w-full-mobile {
            width: 100%;
        }

        .mt-4-mobile {
            margin-top: 1rem;
        }

        .space-x-4-mobile > * + * {
            margin-left: 0;
            margin-top: 0.75rem;
        }

        .justify-between-to-start {
            justify-content: flex-start;
        }

        .flex-wrap-mobile {
            flex-wrap: wrap;
        }

        .tab-overflow {
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 5px;
        }

        .tab-overflow::-webkit-scrollbar {
            height: 3px;
        }

        .tab-overflow::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 3px;
        }
    }
</style>
@endsection

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Scholar Details</h1>
                <p class="text-gray-600">View detailed information about this scholar</p>
            </div>
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                <a href="{{ route('admin.scholars.edit', $scholar->id) }}" class="px-4 py-2 bg-red-700 text-white rounded-lg hover:bg-red-800 transition-all duration-300 text-center">
                    <i class="fas fa-edit mr-2 text-white" style="color: white !important;"></i> Edit Scholar
                </a>
                <a href="{{ route('admin.scholars.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-center">
                    <i class="fas fa-arrow-left mr-2 text-white" style="color: white !important;"></i> Back to List
                </a>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
            <!-- Header with Scholar Basic Info -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="h-20 w-20 rounded-full bg-red-50 flex items-center justify-center mr-4 border border-red-200">
                        @if($scholar->user->profile_photo)
                            <img src="{{ asset('storage/' . $scholar->user->profile_photo) }}" alt="{{ $scholar->user->name }}" class="h-20 w-20 rounded-full object-cover">
                        @else
                            <i class="fas fa-user-graduate text-red-600 text-4xl"></i>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $scholar->first_name }} {{ $scholar->middle_name }} {{ $scholar->last_name }}</h2>
                        <p class="text-gray-600">{{ $scholar->user->email }}</p>
                        <div class="flex mt-2">
                            <span class="px-3 py-1 text-xs rounded-full
                                @if($scholar->status == 'Active') bg-green-100 text-green-800
                                @elseif($scholar->status == 'Inactive') bg-red-100 text-red-800
                                @elseif($scholar->status == 'Completed') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ $scholar->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scholar Details Tabs -->
            <div class="border-b border-gray-200">
                <nav class="flex flex-wrap tab-overflow">
                    <button class="px-4 sm:px-6 py-3 border-b-2 border-red-600 font-medium text-xs sm:text-sm leading-5 text-red-700 focus:outline-none transition-all duration-200 hover:bg-red-50 whitespace-nowrap" id="profile-tab">
                        <i class="fas fa-user mr-1 sm:mr-2 hidden sm:inline-block"></i>Profile
                    </button>
                    <button class="px-4 sm:px-6 py-3 border-b-2 border-transparent font-medium text-xs sm:text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition-all duration-200 hover:bg-green-50 whitespace-nowrap" id="fund-requests-tab">
                        <i class="fas fa-money-bill-wave mr-1 sm:mr-2 hidden sm:inline-block"></i>Fund Requests
                    </button>
                    <button class="px-4 sm:px-6 py-3 border-b-2 border-transparent font-medium text-xs sm:text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition-all duration-200 hover:bg-green-50 whitespace-nowrap" id="manuscripts-tab">
                        <i class="fas fa-file-alt mr-1 sm:mr-2 hidden sm:inline-block"></i>Manuscripts
                    </button>
                    <button class="px-4 sm:px-6 py-3 border-b-2 border-transparent font-medium text-xs sm:text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition-all duration-200 hover:bg-green-50 whitespace-nowrap" id="documents-tab">
                        <i class="fas fa-folder mr-1 sm:mr-2 hidden sm:inline-block"></i>Documents
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
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="text-gray-600 font-medium mb-1 sm:mb-0">Full Name:</span>
                                        <span class="text-gray-900 break-words">{{ $scholar->first_name }} {{ $scholar->middle_name }} {{ $scholar->last_name }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="text-gray-600 font-medium mb-1 sm:mb-0">Email:</span>
                                        <span class="text-gray-900 break-words">{{ $scholar->user->email }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="text-gray-600 font-medium mb-1 sm:mb-0">Contact Number:</span>
                                        <span class="text-gray-900">{{ $scholar->contact_number ?? 'Not provided' }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="text-gray-600 font-medium mb-1 sm:mb-0">Address:</span>
                                        <span class="text-gray-900 break-words">{{ $scholar->address ?? 'Not provided' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Educational Background</h3>
                                <div class="mt-4 space-y-4">
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="text-gray-600 font-medium mb-1 sm:mb-0">Bachelor's Degree:</span>
                                        <span class="text-gray-900 break-words">{{ $scholar->bachelor_degree ?? 'Not provided' }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="text-gray-600 font-medium mb-1 sm:mb-0">Bachelor's University:</span>
                                        <span class="text-gray-900 break-words">{{ $scholar->bachelor_university ?? 'Not provided' }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="text-gray-600 font-medium mb-1 sm:mb-0">Graduation Year:</span>
                                        <span class="text-gray-900">{{ $scholar->bachelor_graduation_year ?? 'Not provided' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Academic Information</h3>
                                <div class="mt-4 space-y-4">
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="text-gray-600 font-medium mb-1 sm:mb-0">University:</span>
                                        <span class="text-gray-900 break-words">{{ $scholar->university }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="text-gray-600 font-medium mb-1 sm:mb-0">Department:</span>
                                        <span class="text-gray-900 break-words">{{ $scholar->department ?? 'Not provided' }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="text-gray-600 font-medium mb-1 sm:mb-0">Program:</span>
                                        <span class="text-gray-900 break-words">{{ $scholar->program }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="text-gray-600 font-medium mb-1 sm:mb-0">Degree Level:</span>
                                        <span class="text-gray-900 break-words">{{ $scholar->degree_level ?? 'Not provided' }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="text-gray-600 font-medium mb-1 sm:mb-0">Major/Specialization:</span>
                                        <span class="text-gray-900 break-words">{{ $scholar->major ?? 'Not provided' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">Program Information</h3>
                                <div class="mt-4 space-y-4">
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="text-gray-600 font-medium mb-1 sm:mb-0">Status:</span>
                                        <span class="text-gray-900">{{ $scholar->status }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="text-gray-600 font-medium mb-1 sm:mb-0">Enrollment Type:</span>
                                        <span class="text-gray-900">{{ $scholar->enrollment_type ?? 'Not specified' }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="text-gray-600 font-medium mb-1 sm:mb-0">Study Time:</span>
                                        <span class="text-gray-900">{{ $scholar->study_time ?? 'Not specified' }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="text-gray-600 font-medium mb-1 sm:mb-0">Scholarship Duration:</span>
                                        <span class="text-gray-900">{{ $scholar->scholarship_duration ? $scholar->scholarship_duration . ' months' : 'Not specified' }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="text-gray-600 font-medium mb-1 sm:mb-0">Start Date:</span>
                                        <span class="text-gray-900">{{ $scholar->start_date ? date('M d, Y', strtotime($scholar->start_date)) : 'Not set' }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="text-gray-600 font-medium mb-1 sm:mb-0">Expected Completion:</span>
                                        <span class="text-gray-900">{{ $scholar->expected_completion_date ? date('M d, Y', strtotime($scholar->expected_completion_date)) : 'Not set' }}</span>
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
                                                <a href="{{ route('admin.fund-requests.show', $request->id) }}" class="text-red-700 hover:text-red-800 transition-colors duration-200">View</a>
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
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <div class="truncate max-w-xs cursor-help"
                                                     title="{{ $manuscript->title }}"
                                                     data-tooltip="{{ $manuscript->title }}">
                                                    {{ Str::limit($manuscript->title, 50, '...') }}
                                                </div>
                                            </td>
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
                                                <a href="{{ route('admin.manuscripts.show', $manuscript->id) }}" class="text-red-700 hover:text-red-800 transition-colors duration-200">View</a>
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

                    @if(isset($scholar->documents) && $scholar->documents->count() > 0)
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
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <div class="truncate max-w-xs" title="{{ $document->title }}">
                                                    {{ Str::limit($document->title, 50, '...') }}
                                                </div>
                                            </td>
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
                                                <a href="{{ route('admin.documents.show', $document->id) }}" class="text-red-700 hover:text-red-800 mr-2 transition-colors duration-200">View</a>
                                                <a href="{{ route('admin.documents.download', $document->id) }}" class="text-green-700 hover:text-green-800 transition-colors duration-200">Download</a>
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
                button.classList.remove('border-red-600', 'text-red-700');
                button.classList.add('border-transparent', 'text-gray-500');
            });

            // Activate the selected tab
            const contentId = tabs[tabId];
            document.getElementById(contentId).classList.remove('hidden');

            // Activate the tab button
            const button = document.getElementById(tabId);
            button.classList.remove('border-transparent', 'text-gray-500');
            button.classList.add('border-red-600', 'text-red-700');
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
