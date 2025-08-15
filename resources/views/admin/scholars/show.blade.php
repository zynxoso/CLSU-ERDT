@extends('layouts.app')

@section('title', 'Scholar Details')

@section('styles')
    <style>
        @keyframes pulse{0%,100%{transform:scale(1);}50%{transform:scale(1.1);}}
        .animate-pulse{animation:pulse 1s infinite;}
        .btn-transition{transition:all 0.3s ease;}
        .btn-transition:hover{transform:translateY(-3px);box-shadow:0 10px 15px -3px rgba(0,0,0,0.1),0 4px 6px -2px rgba(0,0,0,0.05);}
        body{font-family:theme(fontFamily.sans);font-size:15px;line-height:1.6;color:rgb(64 64 64);}
        a[style*="background-color: rgb(34 197 94)"] i,a[style*="background-color: rgb(64 64 64)"] i,a[style*="background-color: #FF9800"] i,button[style*="background-color: rgb(34 197 94)"] i{color:rgb(255 255 255) !important;}
        @media (max-width:768px){.flex-col-mobile{flex-direction:column;}.w-full-mobile{width:100%;}.mt-4-mobile{margin-top:1rem;}.space-x-4-mobile>*+*{margin-left:0;margin-top:0.75rem;}.justify-between-to-start{justify-content:flex-start;}.flex-wrap-mobile{flex-wrap:wrap;}.tab-overflow{overflow-x:auto;white-space:nowrap;-webkit-overflow-scrolling:touch;padding-bottom:5px;}.tab-overflow::-webkit-scrollbar{height:3px;}.tab-overflow::-webkit-scrollbar-thumb{background-color:rgba(156,163,175,0.5);border-radius:3px;}}
    </style>
@endsection

@section('content')
    <div class="min-h-screen" style="background-color: #FAFAFA;">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
                <div>
                    <h1 class="text-2xl font-bold" style="color: rgb(64 64 64);">Scholar Details</h1>
                    <p style="color: rgb(115 115 115);">View detailed information about this scholar</p>
                </div>
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                    <a href="{{ route('admin.scholars.edit', $scholar->id) }}"
                        class="px-4 py-2 text-white rounded-lg hover:opacity-90 transition-all duration-300 text-center"
                        style="background-color: rgb(34 197 94);">
                        <i class="fas fa-edit mr-2"></i> Edit Scholar
                    </a>
                    <a href="{{ route('admin.scholars.change-password', $scholar->id) }}"
                        class="px-4 py-2 text-white rounded-lg hover:opacity-90 transition-all duration-300 text-center"
                        style="background-color: rgb(251 191 36); color: #975A16;">
                        <i class="fas fa-key mr-2"></i> Change Password
                    </a>
                    <a href="{{ route('admin.scholars.index') }}"
                        class="px-4 py-2 text-white rounded-lg hover:opacity-90 text-center transition-all duration-300"
                        style="background-color: rgb(59 130 246);">
                        <i class="fas fa-arrow-left mr-2"></i> Back to List
                    </a>
                </div>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden border" style="border-color: rgb(224 224 224);">
                <!-- Header with Scholar Basic Info -->
                <div class="p-6 border-b" style="border-color: rgb(224 224 224);">
                    <div class="flex items-center">
                        <div class="h-20 w-20 rounded-full flex items-center justify-center mr-4 border"
                            style="background-color: #F8BBD0; border-color: #F48FB1;">
                            @if ($scholar->profile_photo)
                                <img src="{{ asset('images/' . $scholar->profile_photo) }}" alt="{{ $scholar->user->name }}"
                                    class="h-20 w-20 rounded-full object-cover">
                            @else
                                <i class="fas fa-user-graduate text-4xl" style="color: #C2185B;"></i>
                            @endif
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold" style="color: rgb(64 64 64);">{{ $scholar->first_name }}
                                {{ $scholar->middle_name }} {{ $scholar->last_name }}</h2>
                            <p style="color: rgb(115 115 115);">{{ $scholar->user->email }}</p>
                            <div class="flex mt-2">
                                <span
                                    class="px-3 py-1 text-xs rounded-full
                                @if ($scholar->status == 'Active') text-white
                                @elseif($scholar->status == 'Inactive') text-white
                                @elseif($scholar->status == 'Completed') text-white
                                @else text-white @endif"
                                    style="
                                @if ($scholar->status == 'Active') background-color: rgb(34 197 94); color: rgb(255 255 255);
                                @elseif($scholar->status == 'Inactive') background-color: #D32F2F; color: rgb(255 255 255);
                                @elseif($scholar->status == 'Completed') background-color: rgb(34 197 94); color: rgb(255 255 255);
                                @else background-color: rgb(251 191 36); color: #975A16; @endif">
                                    {{ $scholar->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Scholar Details Tabs -->
                <div class="border-b" style="border-color: rgb(224 224 224);">
                    <nav class="flex flex-wrap tab-overflow">
                        <button
                            class="px-4 sm:px-6 py-3 border-b-2 font-medium text-xs sm:text-sm leading-5 focus:outline-none transition-all duration-200 hover:opacity-80 whitespace-nowrap"
                            style="border-color: rgb(34 197 94); color: rgb(34 197 94); background-color: rgba(76, 175, 80, 0.1);" id="profile-tab">
                            <i class="fas fa-user mr-1 sm:mr-2 hidden sm:inline-block"></i>Profile
                        </button>
                        <button
                            class="px-4 sm:px-6 py-3 border-b-2 border-transparent font-medium text-xs sm:text-sm leading-5 focus:outline-none transition-all duration-200 hover:opacity-80 whitespace-nowrap"
                            style="color: rgb(115 115 115);" id="fund-requests-tab">
                            <i class="fas fa-money-bill-wave mr-1 sm:mr-2 hidden sm:inline-block"></i>Fund Requests
                        </button>
                        <button
                            class="px-4 sm:px-6 py-3 border-b-2 border-transparent font-medium text-xs sm:text-sm leading-5 focus:outline-none transition-all duration-200 hover:opacity-80 whitespace-nowrap"
                            style="color: rgb(115 115 115);" id="manuscripts-tab">
                            <i class="fas fa-file-alt mr-1 sm:mr-2 hidden sm:inline-block"></i>Manuscripts
                        </button>
                        <button
                            class="px-4 sm:px-6 py-3 border-b-2 border-transparent font-medium text-xs sm:text-sm leading-5 focus:outline-none transition-all duration-200 hover:opacity-80 whitespace-nowrap"
                            style="color: rgb(115 115 115);" id="documents-tab">
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
                                    <h3 class="text-lg font-medium border-b pb-2"
                                        style="color: rgb(64 64 64); border-color: rgb(224 224 224);">Personal Information</h3>
                                    <div class="mt-4 space-y-4">
                                        <div class="flex flex-col sm:flex-row justify-between">
                                            <span class="font-medium mb-1 sm:mb-0" style="color: rgb(115 115 115);">Full Name:</span>
                                            <span class="break-words" style="color: rgb(64 64 64);">{{ $scholar->first_name }}
                                                {{ $scholar->middle_name }} {{ $scholar->last_name }}</span>
                                        </div>
                                        <div class="flex flex-col sm:flex-row justify-between">
                                            <span class="font-medium mb-1 sm:mb-0" style="color: rgb(115 115 115);">Email:</span>
                                            <span class="break-words"
                                                style="color: rgb(64 64 64);">{{ $scholar->user->email }}</span>
                                        </div>
                                        <div class="flex flex-col sm:flex-row justify-between">
                                            <span class="font-medium mb-1 sm:mb-0" style="color: rgb(115 115 115);">Contact
                                                Number:</span>
                                            <span
                                                style="color: rgb(64 64 64);">{{ $scholar->contact_number ?? 'Not provided' }}</span>
                                        </div>
                                        <div class="flex flex-col sm:flex-row justify-between">
                                            <span class="font-medium mb-1 sm:mb-0" style="color: rgb(115 115 115);">Street:</span>
                                            <span class="break-words"
                                                style="color: rgb(64 64 64);">{{ $scholar->street ?? 'Not provided' }}</span>
                                        </div>
                                        <div class="flex flex-col sm:flex-row justify-between">
                                            <span class="font-medium mb-1 sm:mb-0" style="color: rgb(115 115 115);">Village/Barangay:</span>
                                            <span class="break-words"
                                                style="color: rgb(64 64 64);">{{ $scholar->village ?? 'Not provided' }}</span>
                                        </div>
                                        <div class="flex flex-col sm:flex-row justify-between">
                                            <span class="font-medium mb-1 sm:mb-0" style="color: rgb(115 115 115);">Town/City:</span>
                                            <span class="break-words"
                                                style="color: rgb(64 64 64);">{{ $scholar->town ?? 'Not provided' }}</span>
                                        </div>
                                        <div class="flex flex-col sm:flex-row justify-between">
                                            <span class="font-medium mb-1 sm:mb-0" style="color: rgb(115 115 115);">District:</span>
                                            <span class="break-words"
                                                style="color: rgb(64 64 64);">{{ $scholar->district ?? 'Not provided' }}</span>
                                        </div>
                                        <div class="flex flex-col sm:flex-row justify-between">
                                            <span class="font-medium mb-1 sm:mb-0" style="color: rgb(115 115 115);">Region:</span>
                                            <span class="break-words"
                                                style="color: rgb(64 64 64);">{{ $scholar->region ?? 'Not provided' }}</span>
                                        </div>
                                        <div class="flex flex-col sm:flex-row justify-between">
                                            <span class="font-medium mb-1 sm:mb-0" style="color: rgb(115 115 115);">Province:</span>
                                            <span class="break-words"
                                                style="color: rgb(64 64 64);">{{ $scholar->province ?? 'Not provided' }}</span>
                                        </div>
                                        <div class="flex flex-col sm:flex-row justify-between">
                                            <span class="font-medium mb-1 sm:mb-0" style="color: rgb(115 115 115);">Zip Code:</span>
                                            <span class="break-words"
                                                style="color: rgb(64 64 64);">{{ $scholar->zipcode ?? 'Not provided' }}</span>
                                        </div>
                                        <div class="flex flex-col sm:flex-row justify-between">
                                            <span class="font-medium mb-1 sm:mb-0" style="color: rgb(115 115 115);">Country:</span>
                                            <span class="break-words"
                                                style="color: rgb(64 64 64);">{{ $scholar->country ?? 'Not provided' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-lg font-medium border-b pb-2"
                                        style="color: rgb(64 64 64); border-color: rgb(224 224 224);">Educational Background</h3>
                                    <div class="mt-4 space-y-4">
                                        <div class="flex flex-col sm:flex-row justify-between">
                                            <span class="font-medium mb-1 sm:mb-0" style="color: rgb(115 115 115);">Bachelor's
                                                Degree:</span>

                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-medium border-b pb-2"
                                        style="color: rgb(64 64 64); border-color: rgb(224 224 224);">Academic Information</h3>
                                    <div class="mt-4 space-y-4">
                                        <div class="flex flex-col sm:flex-row justify-between">
                                            <span class="font-medium mb-1 sm:mb-0" style="color: rgb(115 115 115);">Intended
                                                University:</span>
                                            <span class="break-words"
                                                style="color: rgb(64 64 64);">{{ $scholar->intended_university }}</span>
                                        </div>
                                        <div class="flex flex-col sm:flex-row justify-between">
                                            <span class="font-medium mb-1 sm:mb-0"
                                                style="color: rgb(115 115 115);">Department:</span>
                                            <span class="break-words"
                                                style="color: rgb(64 64 64);">{{ $scholar->department ?? 'Not provided' }}</span>
                                        </div>
                                        <div class="flex flex-col sm:flex-row justify-between">
                                            <span class="font-medium mb-1 sm:mb-0" style="color: rgb(115 115 115);">Course:</span>
                                            <span class="break-words"
                                                style="color: rgb(64 64 64);">{{ $scholar->course }}</span>
                                        </div>
                                        <div class="flex flex-col sm:flex-row justify-between">
                                            <span class="font-medium mb-1 sm:mb-0" style="color: rgb(115 115 115);">Degree
                                                Level:</span>
                                            <span class="break-words" style="color: rgb(64 64 64);">Not provided</span>
                                        </div>
                                        <div class="flex flex-col sm:flex-row justify-between">
                                            <span class="font-medium mb-1 sm:mb-0"
                                                style="color: rgb(115 115 115);">Major/Specialization:</span>
                                            <span class="break-words"
                                                style="color: rgb(64 64 64);">{{ $scholar->major ?? 'Not provided' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-lg font-medium border-b pb-2"
                                        style="color: rgb(64 64 64); border-color: rgb(224 224 224);">Scholarship Information</h3>
                                    <div class="mt-4 space-y-4">
                                        <div class="flex flex-col sm:flex-row justify-between">
                                            <span class="font-medium mb-1 sm:mb-0" style="color: rgb(115 115 115);">Status:</span>
                                            <span style="color: rgb(64 64 64);">{{ $scholar->status }}</span>
                                        </div>

                                        <div class="flex flex-col sm:flex-row justify-between">
                                            <span class="font-medium mb-1 sm:mb-0" style="color: rgb(115 115 115);">Study
                                                Time:</span>
                                            <span
                                                style="color: rgb(64 64 64);">{{ $scholar->study_time ?? 'Not specified' }}</span>
                                        </div>
                                        <div class="flex flex-col sm:flex-row justify-between">
                                            <span class="font-medium mb-1 sm:mb-0" style="color: rgb(115 115 115);">Scholarship
                                                Duration:</span>
                                            <span
                                                style="color: rgb(64 64 64);">{{ $scholar->scholarship_duration ? $scholar->scholarship_duration . ' months' : 'Not specified' }}</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fund Requests Tab (Hidden by default) -->
                    <div id="fund-requests-content" class="tab-content hidden">
                        <h3 class="text-lg font-medium mb-4" style="color: rgb(64 64 64);">Fund Requests</h3>

                        @if ($scholar->fundRequests->count() > 0)
                            <div class="overflow-x-auto rounded-lg border" style="border-color: rgb(224 224 224);">
                                <table class="min-w-full divide-y" style="divide-color: #E0E0E0;">
                                    <thead style="background-color: #F5F5F5;">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                                                style="color: rgb(115 115 115);">ID</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                                                style="color: rgb(115 115 115);">Purpose</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                                                style="color: rgb(115 115 115);">Amount</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                                                style="color: rgb(115 115 115);">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                                                style="color: rgb(115 115 115);">Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                                                style="color: rgb(115 115 115);">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y" style="divide-color: #E0E0E0;">
                                        @foreach ($scholar->fundRequests as $request)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: rgb(64 64 64);">
                                                    {{ $request->id }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: rgb(115 115 115);">
                                                    {{ $request->purpose }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: rgb(64 64 64);">
                                                    ₱{{ number_format($request->amount, 2) }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        class="px-2 py-1 text-xs rounded-full
                                                    @if ($request->status == 'Approved') text-white
                                                    @elseif($request->status == 'Rejected') text-white
                                                    @elseif($request->status == 'Submitted') text-white
                                                    @elseif($request->status == 'Under Review') text-white
                                                    @else text-white @endif"
                                                        style="
                                                    @if ($request->status == 'Approved') background-color: rgb(34 197 94); color: rgb(255 255 255);
                                                    @elseif($request->status == 'Rejected') background-color: #D32F2F; color: rgb(255 255 255);
                                                    @elseif($request->status == 'Submitted') background-color: rgb(59 130 246); color: rgb(255 255 255);
                                                    @elseif($request->status == 'Under Review') background-color: rgb(251 191 36); color: #975A16;
                                                    @else background-color: rgb(115 115 115); color: rgb(255 255 255); @endif">
                                                        {{ $request->status }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: rgb(115 115 115);">
                                                    {{ $request->created_at->format('M d, Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('admin.fund-requests.show', $request->id) }}"
                                                        class="hover:opacity-80 transition-colors duration-200"
                                                        style="color: rgb(34 197 94);">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="py-6 text-center rounded-lg border"
                                style="background-color: #F5F5F5; border-color: rgb(224 224 224);">
                                <p style="color: rgb(115 115 115);">No fund requests found for this scholar.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Manuscripts Tab (Hidden by default) -->
                    <div id="manuscripts-content" class="tab-content hidden">
                        <h3 class="text-lg font-medium mb-4" style="color: rgb(64 64 64);">Manuscripts</h3>

                        @if ($scholar->manuscripts->count() > 0)
                            <div class="overflow-x-auto rounded-lg border" style="border-color: rgb(224 224 224);">
                                <table class="min-w-full divide-y" style="divide-color: #E0E0E0;">
                                    <thead style="background-color: #F5F5F5;">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                                                style="color: rgb(115 115 115);">Title</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                                                style="color: rgb(115 115 115);">Type</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                                                style="color: rgb(115 115 115);">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                                                style="color: rgb(115 115 115);">Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                                                style="color: rgb(115 115 115);">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y" style="divide-color: #E0E0E0;">
                                        @foreach ($scholar->manuscripts as $manuscript)
                                            <tr>
                                                <td class="px-6 py-4 text-sm" style="color: rgb(64 64 64);">
                                                    <div class="truncate max-w-xs cursor-help"
                                                        title="{{ $manuscript->title }}"
                                                        data-tooltip="{{ $manuscript->title }}">
                                                        {{ Str::limit($manuscript->title, 50, '...') }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: rgb(115 115 115);">
                                                    {{ $manuscript->manuscript_type }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        class="px-2 py-1 text-xs rounded-full
                                                    @if ($manuscript->status == 'Published') text-white
                                                    @elseif($manuscript->status == 'Rejected') text-white
                                                    @elseif($manuscript->status == 'Accepted') text-white
                                                    @elseif($manuscript->status == 'Under Review') text-white
                                                    @else text-white @endif"
                                                        style="
                                                    @if ($manuscript->status == 'Published') background-color: rgb(34 197 94); color: rgb(255 255 255);
                                                    @elseif($manuscript->status == 'Rejected') background-color: #D32F2F; color: rgb(255 255 255);
                                                    @elseif($manuscript->status == 'Accepted') background-color: rgb(59 130 246); color: rgb(255 255 255);
                                                    @elseif($manuscript->status == 'Under Review') background-color: rgb(251 191 36); color: #975A16;
                                                    @else background-color: rgb(115 115 115); color: rgb(255 255 255); @endif">
                                                        {{ $manuscript->status }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: rgb(115 115 115);">
                                                    {{ $manuscript->created_at->format('M d, Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('admin.manuscripts.show', $manuscript->id) }}"
                                                        class="hover:opacity-80 transition-colors duration-200"
                                                        style="color: rgb(34 197 94);">View</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="py-6 text-center rounded-lg border"
                                style="background-color: #F5F5F5; border-color: rgb(224 224 224);">
                                <p style="color: rgb(115 115 115);">No manuscripts found for this scholar.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Documents Tab (Hidden by default) -->
                    <div id="documents-content" class="tab-content hidden">
                        <h3 class="text-lg font-medium mb-4" style="color: rgb(64 64 64);">Documents</h3>

                        @if (isset($scholar->documents) && $scholar->documents->count() > 0)
                            <div class="overflow-x-auto rounded-lg border" style="border-color: rgb(224 224 224);">
                                <table class="min-w-full divide-y" style="divide-color: #E0E0E0;">
                                    <thead style="background-color: #F5F5F5;">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                                                style="color: rgb(115 115 115);">Title</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                                                style="color: rgb(115 115 115);">Type</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                                                style="color: rgb(115 115 115);">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                                                style="color: rgb(115 115 115);">Uploaded</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
                                                style="color: rgb(115 115 115);">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y" style="divide-color: #E0E0E0;">
                                        @foreach ($scholar->documents as $document)
                                            <tr>
                                                <td class="px-6 py-4 text-sm" style="color: rgb(64 64 64);">
                                                    <div class="truncate max-w-xs" title="{{ $document->title }}">
                                                        {{ Str::limit($document->title, 50, '...') }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: rgb(115 115 115);">
                                                    {{ $document->type }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        class="px-2 py-1 text-xs rounded-full
                                                    @if ($document->status == 'Verified') text-white
                                                    @elseif($document->status == 'Rejected') text-white
                                                    @else text-white @endif"
                                                        style="
                                                    @if ($document->status == 'Verified') background-color: rgb(34 197 94);
                                                    @elseif($document->status == 'Rejected') background-color: #D32F2F;
                                                    @else background-color: rgb(251 191 36); color: #E65100; @endif">
                                                        {{ $document->status }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: rgb(115 115 115);">
                                                    {{ $document->created_at->format('M d, Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('admin.documents.show', $document->id) }}"
                                                        class="mr-2 hover:opacity-80 transition-colors duration-200"
                                                        style="color: rgb(34 197 94);">View</a>
                                                    <a href="{{ route('admin.documents.download', $document->id) }}"
                                                        class="hover:opacity-80 transition-colors duration-200"
                                                        style="color: rgb(34 197 94);">Download</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="py-6 text-center rounded-lg border"
                                style="background-color: #F5F5F5; border-color: rgb(224 224 224);">
                                <p style="color: rgb(115 115 115);">No documents found for this scholar.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabContents = document.querySelectorAll('.tab-content');
            const tabButtons = document.querySelectorAll('button[id$="-tab"]');
            const tabs = {'profile-tab':'profile-content','fund-requests-tab':'fund-requests-content','manuscripts-tab':'manuscripts-content','documents-tab':'documents-content'};
            
            function activateTab(activeTabId) {
                tabContents.forEach(content => content.classList.add('hidden'));
                tabButtons.forEach(btn => {
                    btn.style.cssText = 'border-color:transparent;color:#757575;background-color:transparent';
                });
                
                document.getElementById(tabs[activeTabId]).classList.remove('hidden');
                const activeBtn = document.getElementById(activeTabId);
                activeBtn.style.cssText = 'border-color:#4CAF50;color:#4CAF50;background-color:rgba(76,175,80,0.1)';
            }
            
            tabButtons.forEach(btn => btn.addEventListener('click', () => activateTab(btn.id)));
        });
    </script>
@endsection

@endsection
