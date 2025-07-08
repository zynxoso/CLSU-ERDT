@extends('layouts.app')

@section('content')
@auth
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class="mb-4">Dashboard</h1>

            @if(auth()->user()->role === 'scholar')
                <!-- Scholar Dashboard -->
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Profile Status</h5>
                                <p class="card-text">
                                    <strong>Status:</strong> {{ auth()->user()->scholarProfile->status ?? 'Not Set' }}<br>
                                    <strong>Program:</strong> {{ auth()->user()->scholarProfile->program ?? 'Not Set' }}<br>
                                    <strong>University:</strong> {{ auth()->user()->scholarProfile->university ?? 'Not Set' }}
                                </p>
                                <a href="{{ route('scholars.show', auth()->user()->scholarProfile->id ?? 0) }}"
                                   class="btn btn-primary">View Profile</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Fund Requests</h5>
                                <p class="card-text">
                                    <strong>Pending:</strong> <span class="badge bg-warning text-white" style="background-color: #f59e0b;"><i class="fas fa-clock mr-1" style="color: white;"></i> {{ $pendingRequests ?? 0 }}</span><br>
                                    <strong>Approved:</strong> <span class="badge bg-success text-white" style="background-color: #10b981;"><i class="fas fa-check mr-1" style="color: white;"></i> {{ $approvedRequests ?? 0 }}</span><br>
                                    <strong>Rejected:</strong> <span class="badge bg-danger text-white" style="background-color: #ef4444;"><i class="fas fa-times mr-1" style="color: white;"></i> {{ $rejectedRequests ?? 0 }}</span>
                                </p>
                                <a href="{{ route('fund-requests.index') }}"
                                   class="btn btn-primary">View Requests</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">Manuscripts</h5>
                                <p class="card-text">
                                    <strong>Submitted:</strong> <span class="badge bg-primary text-white" style="background-color: #3b82f6;"><i class="fas fa-file-alt mr-1" style="color: white;"></i> {{ $submittedManuscripts ?? 0 }}</span><br>
                                    <strong>Under Review:</strong> <span class="badge bg-warning text-white" style="background-color: #f59e0b;"><i class="fas fa-search mr-1" style="color: white;"></i> {{ $reviewManuscripts ?? 0 }}</span><br>
                                    <strong>Published:</strong> <span class="badge bg-success text-white" style="background-color: #10b981;"><i class="fas fa-book mr-1" style="color: white;"></i> {{ $publishedManuscripts ?? 0 }}</span>
                                </p>
                                <a href="{{ route('scholar.manuscripts.index') }}"
                                   class="btn btn-primary">View Manuscripts</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Recent Notifications</h5>
                                @if(isset($notifications) && count($notifications) > 0)
                                    <div class="list-group">
                                        @foreach($notifications as $notification)
                                            <a href="{{ $notification->link ?? '#' }}"
                                               class="list-group-item list-group-item-action {{ $notification->is_read ? '' : 'list-group-item-primary' }}">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1">{{ $notification->title }}</h6>
                                                    <small>{{ $notification->created_at->diffForHumans() }}</small>
                                                </div>
                                                <p class="mb-1">{{ $notification->message }}</p>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No recent notifications.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Admin Dashboard -->
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">Scholars</h5>
                                <h2 class="display-4">{{ $scholarCount ?? 0 }}</h2>
                                <p class="card-text">Total registered scholars</p>
                                <a href="{{ route('scholars.index') }}"
                                   class="btn btn-primary">Manage Scholars</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">Pending Requests</h5>
                                <h2 class="display-4">{{ $pendingRequestsCount ?? 0 }}</h2>
                                <p class="card-text">Awaiting review</p>
                                <a href="{{ route('fund-requests.index') }}"
                                   class="btn btn-primary">Review Requests</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">Disbursements</h5>
                                <h2 class="display-4">{{ $disbursementsCount ?? 0 }}</h2>
                                <p class="card-text">Total processed</p>
                                <a href="{{ route('disbursements.index') }}"
                                   class="btn btn-primary">View Disbursements</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">Manuscripts</h5>
                                <h2 class="display-4">{{ $manuscriptsCount ?? 0 }}</h2>
                                <p class="card-text">Total submissions</p>
                                <a href="{{ route('admin.manuscripts.index') }}"
                                   class="btn btn-primary">View all manuscripts</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Recent Fund Requests</h5>
                                @if(isset($recentRequests) && count($recentRequests) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Scholar</th>
                                                    <th>Type</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recentRequests as $request)
                                                    <tr>
                                                        <td>{{ $request->scholarProfile->full_name }}</td>
                                                        <td>{{ $request->requestType->name }}</td>
                                                        <td>₱{{ number_format($request->amount, 2) }}</td>
                                                        <td>
                                                            @if($request->status === 'Submitted')
                                                                <span class="badge bg-primary text-white" style="background-color: #3b82f6;"><i class="fas fa-file-alt mr-1" style="color: white;"></i> Submitted</span>
                                                            @elseif($request->status === 'Under Review')
                                                                <span class="badge bg-warning text-white" style="background-color: #f59e0b;"><i class="fas fa-search mr-1" style="color: white;"></i> Under Review</span>
                                                            @elseif($request->status === 'Approved')
                                                                <span class="badge bg-success text-white" style="background-color: #10b981;"><i class="fas fa-check mr-1" style="color: white;"></i> Approved</span>
                                                            @elseif($request->status === 'Rejected')
                                                                <span class="badge bg-danger text-white" style="background-color: #ef4444;"><i class="fas fa-times mr-1" style="color: white;"></i> Rejected</span>
                                                            @else
                                                                <span class="badge bg-secondary text-white" style="background-color: #6b7280;"><i class="fas fa-question-circle mr-1" style="color: white;"></i> {{ $request->status }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('fund-requests.show', $request->id) }}"
                                                               class="btn btn-sm btn-primary">View</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted">No recent fund requests.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title text-gray-800">Recent Audit Logs</h5>
                                @if(isset($recentLogs) && count($recentLogs) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr class="bg-gray-50 text-gray-600">
                                                    <th>User</th>
                                                    <th>Action</th>
                                                    <th>Entity</th>
                                                    <th>Time</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-gray-700">
                                                @foreach($recentLogs as $log)
                                                    <tr>
                                                        <td>{{ $log->user->name ?? 'System' }}</td>
                                                        <td>
                                                            <span class="px-2 py-1 text-xs rounded-full inline-flex items-center
                                                                @if($log->action == 'create') bg-green-100 text-green-800
                                                                @elseif($log->action == 'update') bg-yellow-100 text-yellow-800
                                                                @elseif($log->action == 'delete') bg-red-100 text-red-800
                                                                @elseif($log->action == 'login') bg-blue-100 text-blue-800
                                                                @elseif($log->action == 'logout') bg-purple-100 text-purple-800
                                                                @else bg-blue-100 text-blue-800 @endif">
                                                                @if($log->action == 'create')<i class="fas fa-plus-circle mr-1" style="color: #065f46;"></i>
                                                                @elseif($log->action == 'update')<i class="fas fa-edit mr-1" style="color: #92400e;"></i>
                                                                @elseif($log->action == 'delete')<i class="fas fa-trash-alt mr-1" style="color: #7f1d1d;"></i>
                                                                @elseif($log->action == 'login')<i class="fas fa-sign-in-alt mr-1" style="color: #1e40af;"></i>
                                                                @elseif($log->action == 'logout')<i class="fas fa-sign-out-alt mr-1" style="color: #6b21a8;"></i>
                                                                @else<i class="fas fa-info-circle mr-1" style="color: #1e40af;"></i>@endif
                                                                {{ ucfirst($log->action) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $log->model_type }}</td>
                                                        <td>{{ $log->created_at->diffForHumans() }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3 text-right">
                                        <a href="{{ route('admin.audit-logs.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View all logs <i class="fas fa-arrow-right ml-1"></i>
                                        </a>
                                    </div>
                                @else
                                    <p class="text-gray-500">No recent audit logs.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>window.location = "/login";</script>
@endauth
@endsection
