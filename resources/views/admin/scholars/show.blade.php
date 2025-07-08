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

    /* Global Typography Improvements */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 15px;
        line-height: 1.6;
        color: #424242;
    }

    /* Ensure all button icons have white color */
    a[style*="background-color: #2E7D32"] i,
    a[style*="background-color: #424242"] i,
    button[style*="background-color: #2E7D32"] i {
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
<div class="min-h-screen" style="background-color: #FAFAFA;">
    <div class="container mx-auto px-4 py-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
            <div>
                <h1 class="text-2xl font-bold" style="color: #424242;">Scholar Details</h1>
                <p style="color: #616161;">View detailed information about this scholar</p>
            </div>
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                <a href="{{ route('admin.scholars.edit', $scholar->id) }}" class="px-4 py-2 text-white rounded-lg hover:opacity-90 transition-all duration-300 text-center" style="background-color: #2E7D32;">
                    <i class="fas fa-edit mr-2"></i> Edit Scholar
                </a>
                <a href="{{ route('admin.scholars.index') }}" class="px-4 py-2 text-white rounded-lg hover:opacity-90 text-center transition-all duration-300" style="background-color: #1976D2;">
                    <i class="fas fa-arrow-left mr-2"></i> Back to List
                </a>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden border" style="border-color: #E0E0E0;">
            <!-- Header with Scholar Basic Info -->
            <div class="p-6 border-b" style="border-color: #E0E0E0;">
                <div class="flex items-center">
                    <div class="h-20 w-20 rounded-full flex items-center justify-center mr-4 border" style="background-color: #F8BBD0; border-color: #F48FB1;">
                        @if($scholar->profile_photo)
                            <img src="{{ asset('images/' . $scholar->profile_photo) }}" alt="{{ $scholar->user->name }}" class="h-20 w-20 rounded-full object-cover">
                        @else
                            <i class="fas fa-user-graduate text-4xl" style="color: #C2185B;"></i>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold" style="color: #424242;">{{ $scholar->first_name }} {{ $scholar->middle_name }} {{ $scholar->last_name }}</h2>
                        <p style="color: #616161;">{{ $scholar->user->email }}</p>
                        <div class="flex mt-2">
                            <span class="px-3 py-1 text-xs rounded-full
                                @if($scholar->status == 'Active') text-white
                                @elseif($scholar->status == 'Inactive') text-white
                                @elseif($scholar->status == 'Completed') text-white
                                @else text-white @endif"
                                style="
                                @if($scholar->status == 'Active') background-color: #2E7D32;
                                @elseif($scholar->status == 'Inactive') background-color: #D32F2F;
                                @elseif($scholar->status == 'Completed') background-color: #2E7D32;
                                @else background-color: #FFCA28; color: #E65100; @endif">
                                {{ $scholar->status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scholar Details Tabs -->
            <div class="border-b" style="border-color: #E0E0E0;">
                <nav class="flex flex-wrap tab-overflow">
                    <button class="px-4 sm:px-6 py-3 border-b-2 font-medium text-xs sm:text-sm leading-5 focus:outline-none transition-all duration-200 hover:opacity-80 whitespace-nowrap" style="border-color: #2E7D32; color: #2E7D32; background-color: #E8F5E8;" id="profile-tab">
                        <i class="fas fa-user mr-1 sm:mr-2 hidden sm:inline-block"></i>Profile
                    </button>
                    <button class="px-4 sm:px-6 py-3 border-b-2 border-transparent font-medium text-xs sm:text-sm leading-5 focus:outline-none transition-all duration-200 hover:opacity-80 whitespace-nowrap" style="color: #757575;" id="fund-requests-tab">
                        <i class="fas fa-money-bill-wave mr-1 sm:mr-2 hidden sm:inline-block"></i>Fund Requests
                    </button>
                    <button class="px-4 sm:px-6 py-3 border-b-2 border-transparent font-medium text-xs sm:text-sm leading-5 focus:outline-none transition-all duration-200 hover:opacity-80 whitespace-nowrap" style="color: #757575;" id="manuscripts-tab">
                        <i class="fas fa-file-alt mr-1 sm:mr-2 hidden sm:inline-block"></i>Manuscripts
                    </button>
                    <button class="px-4 sm:px-6 py-3 border-b-2 border-transparent font-medium text-xs sm:text-sm leading-5 focus:outline-none transition-all duration-200 hover:opacity-80 whitespace-nowrap" style="color: #757575;" id="documents-tab">
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
                                <h3 class="text-lg font-medium border-b pb-2" style="color: #424242; border-color: #E0E0E0;">Personal Information</h3>
                                <div class="mt-4 space-y-4">
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="font-medium mb-1 sm:mb-0" style="color: #616161;">Full Name:</span>
                                        <span class="break-words" style="color: #424242;">{{ $scholar->first_name }} {{ $scholar->middle_name }} {{ $scholar->last_name }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="font-medium mb-1 sm:mb-0" style="color: #616161;">Email:</span>
                                        <span class="break-words" style="color: #424242;">{{ $scholar->user->email }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="font-medium mb-1 sm:mb-0" style="color: #616161;">Contact Number:</span>
                                        <span style="color: #424242;">{{ $scholar->contact_number ?? 'Not provided' }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="font-medium mb-1 sm:mb-0" style="color: #616161;">Address:</span>
                                        <span class="break-words" style="color: #424242;">{{ $scholar->address ?? 'Not provided' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium border-b pb-2" style="color: #424242; border-color: #E0E0E0;">Educational Background</h3>
                                <div class="mt-4 space-y-4">
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="font-medium mb-1 sm:mb-0" style="color: #616161;">Bachelor's Degree:</span>
                                        <span class="break-words" style="color: #424242;">{{ $scholar->bachelor_degree ?? 'Not provided' }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="font-medium mb-1 sm:mb-0" style="color: #616161;">Bachelor's University:</span>
                                        <span class="break-words" style="color: #424242;">{{ $scholar->bachelor_university ?? 'Not provided' }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="font-medium mb-1 sm:mb-0" style="color: #616161;">Graduation Year:</span>
                                        <span style="color: #424242;">{{ $scholar->bachelor_graduation_year ?? 'Not provided' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium border-b pb-2" style="color: #424242; border-color: #E0E0E0;">Academic Information</h3>
                                <div class="mt-4 space-y-4">
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="font-medium mb-1 sm:mb-0" style="color: #616161;">University:</span>
                                        <span class="break-words" style="color: #424242;">{{ $scholar->university }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="font-medium mb-1 sm:mb-0" style="color: #616161;">Department:</span>
                                        <span class="break-words" style="color: #424242;">{{ $scholar->department ?? 'Not provided' }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="font-medium mb-1 sm:mb-0" style="color: #616161;">Program:</span>
                                        <span class="break-words" style="color: #424242;">{{ $scholar->program }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="font-medium mb-1 sm:mb-0" style="color: #616161;">Degree Level:</span>
                                        <span class="break-words" style="color: #424242;">{{ $scholar->degree_level ?? 'Not provided' }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="font-medium mb-1 sm:mb-0" style="color: #616161;">Major/Specialization:</span>
                                        <span class="break-words" style="color: #424242;">{{ $scholar->major ?? 'Not provided' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium border-b pb-2" style="color: #424242; border-color: #E0E0E0;">Program Information</h3>
                                <div class="mt-4 space-y-4">
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="font-medium mb-1 sm:mb-0" style="color: #616161;">Status:</span>
                                        <span style="color: #424242;">{{ $scholar->status }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="font-medium mb-1 sm:mb-0" style="color: #616161;">Enrollment Type:</span>
                                        <span style="color: #424242;">{{ $scholar->enrollment_type ?? 'Not specified' }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="font-medium mb-1 sm:mb-0" style="color: #616161;">Study Time:</span>
                                        <span style="color: #424242;">{{ $scholar->study_time ?? 'Not specified' }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="font-medium mb-1 sm:mb-0" style="color: #616161;">Scholarship Duration:</span>
                                        <span style="color: #424242;">{{ $scholar->scholarship_duration ? $scholar->scholarship_duration . ' months' : 'Not specified' }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="font-medium mb-1 sm:mb-0" style="color: #616161;">Start Date:</span>
                                        <span style="color: #424242;">{{ $scholar->start_date ? date('M d, Y', strtotime($scholar->start_date)) : 'Not set' }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row justify-between">
                                        <span class="font-medium mb-1 sm:mb-0" style="color: #616161;">Expected Completion:</span>
                                        <span style="color: #424242;">{{ $scholar->expected_completion_date ? date('M d, Y', strtotime($scholar->expected_completion_date)) : 'Not set' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fund Requests Tab (Hidden by default) -->
                <div id="fund-requests-content" class="tab-content hidden">
                    <h3 class="text-lg font-medium mb-4" style="color: #424242;">Fund Requests</h3>

                    @if($scholar->fundRequests->count() > 0)
                        <div class="overflow-x-auto rounded-lg border" style="border-color: #E0E0E0;">
                            <table class="min-w-full divide-y" style="divide-color: #E0E0E0;">
                                <thead style="background-color: #F5F5F5;">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Purpose</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y" style="divide-color: #E0E0E0;">
                                    @foreach($scholar->fundRequests as $request)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #424242;">{{ $request->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #757575;">{{ $request->purpose }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #424242;">₱{{ number_format($request->amount, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs rounded-full
                                                    @if($request->status == 'Approved') text-white
                                                    @elseif($request->status == 'Rejected') text-white
                                                    @elseif($request->status == 'Submitted') text-white
                                                    @elseif($request->status == 'Under Review') text-white
                                                    @else text-white @endif"
                                                    style="
                                                    @if($request->status == 'Approved') background-color: #2E7D32;
                                                    @elseif($request->status == 'Rejected') background-color: #D32F2F;
                                                    @elseif($request->status == 'Submitted') background-color: #1976D2;
                                                    @elseif($request->status == 'Under Review') background-color: #FFCA28; color: #E65100;
                                                    @else background-color: #424242; @endif">
                                                    {{ $request->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #757575;">{{ $request->created_at->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.fund-requests.show', $request->id) }}" class="hover:opacity-80 transition-colors duration-200" style="color: #2E7D32;">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-6 text-center rounded-lg border" style="background-color: #F5F5F5; border-color: #E0E0E0;">
                            <p style="color: #757575;">No fund requests found for this scholar.</p>
                        </div>
                    @endif
                </div>

                <!-- Manuscripts Tab (Hidden by default) -->
                <div id="manuscripts-content" class="tab-content hidden">
                    <h3 class="text-lg font-medium mb-4" style="color: #424242;">Manuscripts</h3>

                    @if($scholar->manuscripts->count() > 0)
                        <div class="overflow-x-auto rounded-lg border" style="border-color: #E0E0E0;">
                            <table class="min-w-full divide-y" style="divide-color: #E0E0E0;">
                                <thead style="background-color: #F5F5F5;">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y" style="divide-color: #E0E0E0;">
                                    @foreach($scholar->manuscripts as $manuscript)
                                        <tr>
                                            <td class="px-6 py-4 text-sm" style="color: #424242;">
                                                <div class="truncate max-w-xs cursor-help"
                                                     title="{{ $manuscript->title }}"
                                                     data-tooltip="{{ $manuscript->title }}">
                                                    {{ Str::limit($manuscript->title, 50, '...') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #757575;">{{ $manuscript->manuscript_type }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs rounded-full
                                                    @if($manuscript->status == 'Published') text-white
                                                    @elseif($manuscript->status == 'Rejected') text-white
                                                    @elseif($manuscript->status == 'Accepted') text-white
                                                    @elseif($manuscript->status == 'Under Review') text-white
                                                    @else text-white @endif"
                                                    style="
                                                    @if($manuscript->status == 'Published') background-color: #2E7D32;
                                                    @elseif($manuscript->status == 'Rejected') background-color: #D32F2F;
                                                    @elseif($manuscript->status == 'Accepted') background-color: #1976D2;
                                                    @elseif($manuscript->status == 'Under Review') background-color: #FFCA28; color: #E65100;
                                                    @else background-color: #424242; @endif">
                                                    {{ $manuscript->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #757575;">{{ $manuscript->created_at->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.manuscripts.show', $manuscript->id) }}" class="hover:opacity-80 transition-colors duration-200" style="color: #2E7D32;">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-6 text-center rounded-lg border" style="background-color: #F5F5F5; border-color: #E0E0E0;">
                            <p style="color: #757575;">No manuscripts found for this scholar.</p>
                        </div>
                    @endif
                </div>

                <!-- Documents Tab (Hidden by default) -->
                <div id="documents-content" class="tab-content hidden">
                    <h3 class="text-lg font-medium mb-4" style="color: #424242;">Documents</h3>

                    @if(isset($scholar->documents) && $scholar->documents->count() > 0)
                        <div class="overflow-x-auto rounded-lg border" style="border-color: #E0E0E0;">
                            <table class="min-w-full divide-y" style="divide-color: #E0E0E0;">
                                <thead style="background-color: #F5F5F5;">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Title</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Uploaded</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y" style="divide-color: #E0E0E0;">
                                    @foreach($scholar->documents as $document)
                                        <tr>
                                            <td class="px-6 py-4 text-sm" style="color: #424242;">
                                                <div class="truncate max-w-xs" title="{{ $document->title }}">
                                                    {{ Str::limit($document->title, 50, '...') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #757575;">{{ $document->type }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs rounded-full
                                                    @if($document->status == 'Verified') text-white
                                                    @elseif($document->status == 'Rejected') text-white
                                                    @else text-white @endif"
                                                    style="
                                                    @if($document->status == 'Verified') background-color: #2E7D32;
                                                    @elseif($document->status == 'Rejected') background-color: #D32F2F;
                                                    @else background-color: #FFCA28; color: #E65100; @endif">
                                                    {{ $document->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #757575;">{{ $document->created_at->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.documents.show', $document->id) }}" class="mr-2 hover:opacity-80 transition-colors duration-200" style="color: #2E7D32;">View</a>
                                                <a href="{{ route('admin.documents.download', $document->id) }}" class="hover:opacity-80 transition-colors duration-200" style="color: #2E7D32;">Download</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-6 text-center rounded-lg border" style="background-color: #F5F5F5; border-color: #E0E0E0;">
                            <p style="color: #757575;">No documents found for this scholar.</p>
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
                button.classList.remove('border-2');
                button.style.borderColor = 'transparent';
                button.style.color = '#757575';
                button.style.backgroundColor = 'transparent';
            });

            // Activate the selected tab
            const contentId = tabs[tabId];
            document.getElementById(contentId).classList.remove('hidden');

            // Activate the tab button
            const button = document.getElementById(tabId);
            button.style.borderColor = '#2E7D32';
            button.style.color = '#2E7D32';
            button.style.backgroundColor = '#E8F5E8';
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
