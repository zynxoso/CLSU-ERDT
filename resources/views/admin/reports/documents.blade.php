@extends('layouts.app')

@section('title', $title)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/reports.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/reports.js') }}"></script>
@endpush

@section('content')
<div class="reports-container">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <x-report-header 
            :title="$title" 
            :start-date="$startDate" 
            :end-date="$endDate" 
            back-route="admin.reports.index" />

        <div class="reports-card">
            <div class="reports-header">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <h2 class="reports-title">Document List</h2>
                    <div class="mt-2 md:mt-0 print:hidden">
                        <button class="reports-print-btn">
                            <i class="fas fa-print mr-2"></i> Print Report
                        </button>
                    </div>
                </div>
            </div>

            <div class="print:mt-8">
                <!-- Only visible when printing -->
                <div class="hidden print:block mb-8">
                    <h1 class="text-2xl font-bold text-center">{{ $title }}</h1>
                    @if($startDate && $endDate)
                        <p class="text-sm text-center">Period: {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</p>
                    @endif
                    <p class="text-sm text-center">Generated on: {{ now()->format('M d, Y, h:i A') }}</p>
                </div>

                <!-- Document Summary -->
                <div class="reports-summary">
                    <h3 class="reports-summary-title">Summary</h3>
                    <div class="reports-stats-grid">
                        <div class="reports-stat-card success">
                            <div class="reports-stat-label">Total Documents</div>
                            <div class="reports-stat-value">{{ count($data) }}</div>
                        </div>
                        <div class="reports-stat-card success">
                            <div class="reports-stat-label">Verified</div>
                            <div class="reports-stat-value">{{ $data->where('status', 'Verified')->count() }}</div>
                        </div>
                        <div class="reports-stat-card warning">
                            <div class="reports-stat-label">Pending</div>
                            <div class="reports-stat-value">{{ $data->where('status', 'Pending')->count() }}</div>
                        </div>
                        <div class="reports-stat-card danger">
                            <div class="reports-stat-label">Rejected</div>
                            <div class="reports-stat-value">{{ $data->where('status', 'Rejected')->count() }}</div>
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
                                    <th>Uploaded</th>
                                    <th>Verified</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $document)
                                    <tr>
                                        <td>{{ $document->title }}</td>
                                        <td>
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center print:hidden bg-green-100">
                                                    @if($document->scholarProfile && $document->scholarProfile->profile_photo)
                                                        <img src="{{ asset('images/' . $document->scholarProfile->profile_photo) }}" alt="{{ $document->scholarProfile->user->name }}" class="h-8 w-8 rounded-full">
                                                    @else
                                                        <i class="fas fa-user text-green-500"></i>
                                                    @endif
                                                </div>
                                                <div class="ml-3 print:ml-0">
                                                    <div class="text-sm font-medium text-gray-900">{{ $document->user->name ?? 'Unknown' }}</div>
                                                    <div class="text-xs text-gray-500">{{ $document->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ ucfirst($document->document_type) }}</td>
                                        <td>
                                            <span class="reports-status-badge 
                                                @if($document->status == 'Verified') approved
                                                @elseif($document->status == 'Rejected') rejected
                                                @else pending @endif">
                                                {{ $document->status }}
                                            </span>
                                        </td>
                                        <td>{{ $document->created_at->format('M d, Y') }}</td>
                                        <td>{{ $document->verified_at ? date('M d, Y', strtotime($document->verified_at)) : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="reports-no-data">
                        <div class="reports-no-data-title">No Document Reports Found</div>
                        <div class="reports-no-data-subtitle">There are no documents to display for the selected criteria.</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-8 print:mt-12 print:text-sm" style="color: rgb(115 115 115);">
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
