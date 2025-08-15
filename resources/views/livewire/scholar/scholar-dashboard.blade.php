<div class="min-h-screen bg-gray-50 py-6" wire:loading.class="opacity-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Scholar Profile Card -->
        @if($scholarProfile && $scholarProfile->id)
            <div class="bg-white overflow-hidden shadow rounded-lg mb-8">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-16 w-16 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ Auth::user()->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $scholarProfile->department ?? 'Department not specified' }}</p>
                                <p class="text-sm text-gray-500">{{ $scholarProfile->intended_university ?? 'University not specified' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">Status</div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $this->statusColor }}-100 text-{{ $this->statusColor }}-800">
                                    {{ ucfirst($scholarProfile->status ?? 'Unknown') }}
                                </span>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-medium text-gray-900">Progress</div>
                                <div class="mt-1">
                                    <div class="flex items-center">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-{{ $this->progressColor }}-600 h-2 rounded-full transition-all duration-500" style="width: {{ $scholarProgress }}%"></div>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ number_format($scholarProgress, 1) }}%</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ number_format($daysRemaining, 0) }} days remaining</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Scholar Profile Not Found</h3>
                        <p class="mt-1 text-sm text-yellow-700">Please contact the administrator to set up your scholar profile.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Fund Requests Stats -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Fund Requests</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $totalFundRequests }}</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Approved: {{ $approvedFundRequests }}</span>
                            <span>Pending: {{ $pendingFundRequests }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manuscripts Stats -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Manuscripts</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $totalManuscripts }}</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Published: {{ $publishedManuscripts }}</span>
                            <span>Under Review: {{ $underReviewManuscripts }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Stats -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Documents</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $totalDocuments }}</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Approved: {{ $approvedDocuments }}</span>
                            <span>Pending: {{ $pendingDocuments }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications Stats -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h8v-2H4v2zM4 11h8V9H4v2z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Notifications</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $unreadNotifications }}</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="text-sm text-gray-600">
                            <span>Unread messages</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Overview -->
        @if($scholarProfile && $scholarProfile->id)
            <div class="bg-white overflow-hidden shadow rounded-lg mb-8">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Financial Overview</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">₱{{ number_format($totalAmountRequested, 2) }}</div>
                            <div class="text-sm text-gray-500">Total Requested</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">₱{{ number_format($totalAmountApproved, 2) }}</div>
                            <div class="text-sm text-gray-500">Total Approved</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">₱{{ number_format($totalAmountDisbursed, 2) }}</div>
                            <div class="text-sm text-gray-500">Total Disbursed</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Recent Activity Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Fund Requests -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Fund Requests</h3>
                    @if($recentFundRequests && $recentFundRequests->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentFundRequests as $request)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-gray-900">{{ $request->title ?? 'Fund Request' }}</div>
                                        <div class="text-sm text-gray-500">₱{{ number_format($request->amount, 2) }}</div>
                                        <div class="text-xs text-gray-400">{{ $request->created_at->format('M d, Y') }}</div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($request->status === 'approved') bg-green-100 text-green-800
                                        @elseif($request->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('scholar.fund-requests.index') }}" class="text-sm text-green-600 hover:text-green-500">View all fund requests →</a>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No fund requests yet.</p>
                    @endif
                </div>
            </div>

            <!-- Recent Manuscripts -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Manuscripts</h3>
                    @if($recentManuscripts && $recentManuscripts->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentManuscripts as $manuscript)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($manuscript->title, 40) }}</div>
                                        <div class="text-sm text-gray-500">{{ $manuscript->journal_name ?? 'Journal not specified' }}</div>
                                        <div class="text-xs text-gray-400">{{ $manuscript->created_at->format('M d, Y') }}</div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($manuscript->status === 'published') bg-green-100 text-green-800
                                        @elseif($manuscript->status === 'under_review') bg-yellow-100 text-yellow-800
                                        @elseif($manuscript->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $manuscript->status)) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('scholar.manuscripts.index') }}" class="text-sm text-green-600 hover:text-green-500">View all manuscripts →</a>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No manuscripts yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
