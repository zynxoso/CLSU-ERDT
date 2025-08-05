<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="stats shadow w-full">
            <div class="stat">
                <div class="stat-figure text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-8 h-8 stroke-current">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <div class="stat-title">Total Requested</div>
                <div class="stat-value">{{ $totalRequested }}</div>
            </div>
        </div>

        <div class="stats shadow w-full">
            <div class="stat">
                <div class="stat-figure text-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-8 h-8 stroke-current">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="stat-title">Approved</div>
                <div class="stat-value">{{ $approved }}</div>
            </div>
        </div>

        <div class="stats shadow w-full">
            <div class="stat">
                <div class="stat-figure text-info">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-8 h-8 stroke-current">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                </div>
                <div class="stat-title">Pending</div>
                <div class="stat-value">{{ $pending }}</div>
            </div>
        </div>

        <div class="stats shadow w-full">
            <div class="stat">
                <div class="stat-figure text-error">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-8 h-8 stroke-current">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="stat-title">Rejected</div>
                <div class="stat-value">{{ $rejected }}</div>
            </div>
        </div>
    </div>

    <div class="bg-base-200 shadow rounded-lg p-6 mb-8">
        <h3 class="text-xl font-semibold mb-4">Filter Fund Requests</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="status" class="label">Status</label>
                <select wire:model.live="status" id="status" class="select select-bordered w-full">
                    <option value="">All</option>
                    <option value="Draft">Draft</option>
                    <option value="Submitted">Submitted</option>
                    <option value="Under Review">Under Review</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <div>
                <label for="purpose" class="label">Purpose</label>
                <select wire:model.live="purpose" id="purpose" class="select select-bordered w-full">
                    <option value="">All</option>
                    @foreach($requestTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date" class="label">Date</label>
                <input wire:model.live="date" id="date" type="date" class="input input-bordered w-full" />
            </div>
        </div>
        <div class="mt-4 text-right">
            <button wire:click="clearFilters" class="btn btn-ghost">Clear Filters</button>
        </div>
    </div>

    <div class="overflow-x-auto bg-base-200 shadow rounded-lg p-6">
        <div wire:loading class="text-center text-lg font-semibold text-primary mb-4">Loading Fund Requests...</div>
        <div wire:target="status, purpose, date">
            @if($fundRequests->count() > 0)
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Purpose</th>
                            <th>Amount</th>
                            <th>Date Requested</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fundRequests as $request)
                            <tr>
                                <td>{{ $request->id }}</td>
                                <td>{{ $request->requestType->name }}</td>
                                <td>₱{{ number_format($request->amount, 2) }}</td>
                                <td>{{ $request->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge
                                        @if($request->status === 'Draft') badge-secondary
                                        @elseif($request->status === 'Submitted') badge-warning
                                        @elseif($request->status === 'Under Review') badge-info
                                        @elseif($request->status === 'Approved') badge-success
                                        @elseif($request->status === 'Rejected') badge-error
                                        @elseif($request->status === 'Completed') badge-primary
                                        @endif
                                        text-white">
                                        {{ $request->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('scholar.fund-requests.show', $request->id) }}" class="btn btn-sm btn-info text-white">View</a>
                                    @if($request->status === 'Draft')
                                        <a href="{{ route('scholar.fund-requests.edit', $request->id) }}" class="btn btn-sm btn-warning text-white">Edit</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $fundRequests->links() }}
                </div>
            @else
                <p class="text-center text-gray-500">No fund requests found matching your criteria.</p>
            @endif
        </div>
    </div>
</div>
