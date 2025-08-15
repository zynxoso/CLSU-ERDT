@extends('layouts.app')

@section('title', $title)

@section('content')
<link href="{{ asset('css/reports.css') }}" rel="stylesheet">
<script src="{{ asset('js/reports.js') }}" defer></script>

<div class="reports-container">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <x-report-header 
            :title="$title" 
            :startDate="$startDate" 
            :endDate="$endDate" 
            backRoute="admin.reports.index" 
            reportType="Manuscript List" />

        <div class="reports-card">
            <div class="print:mt-8">
                <!-- Manuscript Summary -->
                <div class="reports-summary">
                    <h3 class="reports-summary-title">Summary</h3>
                    <div class="reports-stats-grid">
                        <div class="reports-stat-card success">
                            <div class="reports-stat-label">Total Manuscripts</div>
                            <div class="reports-stat-value">{{ count($data) }}</div>
                        </div>
                        <div class="reports-stat-card info">
                            <div class="reports-stat-label">Drafts</div>
                            <div class="reports-stat-value">{{ $data->where('status', 'Outline Submitted')->count() }}</div>
                        </div>
                        <div class="reports-stat-card warning">
                            <div class="reports-stat-label">Under Review</div>
                            <div class="reports-stat-value">{{ $data->where('status', 'Under Review')->count() }}</div>
                        </div>
                        <div class="reports-stat-card success">
                            <div class="reports-stat-label">Published</div>
                            <div class="reports-stat-value">{{ $data->where('status', 'Published')->count() }}</div>
                        </div>
                    </div>
                </div>

                @if(count($data) > 0)
                    <div class="overflow-x-auto">
                        <table class="reports-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Scholar</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Last Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $manuscript)
                                    <tr>
                                        <td>
                                            <div>{{ $manuscript->title }}</div>
                                            <div class="text-xs text-gray-500">{{ $manuscript->reference_number ?? 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center print:hidden bg-green-100">
                                                    @if($manuscript->scholarProfile && $manuscript->scholarProfile->profile_photo)
                                                        <img src="{{ asset('images/' . $manuscript->scholarProfile->profile_photo) }}" alt="{{ $manuscript->scholarProfile->user->name }}" class="h-8 w-8 rounded-full">
                                                    @else
                                                        <i class="fas fa-user text-green-500"></i>
                                                    @endif
                                                </div>
                                                <div class="ml-3 print:ml-0">
                                                    <div class="text-sm font-medium text-gray-900">{{ $manuscript->scholarProfile && $manuscript->scholarProfile->user ? $manuscript->scholarProfile->user->name : 'Unknown' }}</div>
                                                    <div class="text-xs text-gray-500">{{ $manuscript->scholarProfile && $manuscript->scholarProfile->user ? $manuscript->scholarProfile->user->email : 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $manuscript->manuscript_type ?? 'N/A' }}</td>
                                        <td>
                                            <span class="reports-status-badge 
                                                @if($manuscript->status == 'Published' || $manuscript->status == 'Accepted') approved
                                                @elseif($manuscript->status == 'Revision Requested') rejected
                                                @elseif($manuscript->status == 'Under Review') pending
                                                @elseif($manuscript->status == 'Draft Submitted' || $manuscript->status == 'Outline Approved') info
                                                @else neutral @endif">
                                                {{ $manuscript->status }}
                                            </span>
                                        </td>
                                        <td>{{ $manuscript->created_at->format('M d, Y') }}</td>
                                        <td>{{ $manuscript->updated_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="reports-no-data">
                        <div class="reports-no-data-title">No Manuscripts Found</div>
                        <div class="reports-no-data-subtitle">There are no manuscripts matching your criteria.</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="reports-footer">
            <p class="text-center">
                Generated from CLSU-ERDT Scholar Management System<br>
                © {{ date('Y') }} Central Luzon State University
            </p>
        </div>
    </div>
</div>

@if(request('format') == 'pdf')
    <link href="{{ asset('css/reports-pdf.css') }}" rel="stylesheet">
@endif
@endsection
