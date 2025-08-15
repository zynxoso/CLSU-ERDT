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
                    <h2 class="reports-title">Financial Transactions</h2>
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

                <!-- Financial Summary -->
                <div class="reports-summary">
                    <h3 class="reports-summary-title">Summary</h3>
                    <div class="reports-stats-grid">
                        <div class="reports-stat-card success">
                            <div class="reports-stat-label">Total Requests</div>
                            <div class="reports-stat-value">{{ count($data) }}</div>
                        </div>
                        <div class="reports-stat-card success">
                            <div class="reports-stat-label">Approved</div>
                            <div class="reports-stat-value">{{ $data->where('status', 'Approved')->count() }}</div>
                        </div>
                        <div class="reports-stat-card warning">
                            <div class="reports-stat-label">Pending</div>
                            <div class="reports-stat-value">{{ $data->whereIn('status', ['Submitted', 'Under Review'])->count() }}</div>
                        </div>
                        <div class="reports-stat-card success">
                            <div class="reports-stat-label">Total Amount (Approved)</div>
                            <div class="reports-stat-value">₱{{ number_format($data->where('status', 'Approved')->sum('amount'), 2) }}</div>
                        </div>
                    </div>
                </div>

                @if(count($data) > 0)
                    <div class="overflow-x-auto">
                        <table class="reports-table">
                            <thead>
                                <tr>
                                    <th>Reference</th>
                                    <th>Scholar</th>
                                    <th>Purpose</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Processed</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $fund)
                                    <tr>
                                        <td>{{ $fund->reference_number ?? 'N/A' }}</td>
                                        <td>
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center print:hidden bg-pink-200">
                                                    @if($fund->scholarProfile && $fund->scholarProfile->profile_photo)
                                                        <img src="{{ asset('images/' . $fund->scholarProfile->profile_photo) }}" alt="{{ $fund->scholarProfile->user->name }}" class="h-8 w-8 rounded-full">
                                                    @else
                                                        <i class="fas fa-user text-green-500"></i>
                                                    @endif
                                                </div>
                                                <div class="ml-3 print:ml-0">
                                                    <div class="text-sm font-medium text-gray-900">{{ $fund->user->name ?? 'Unknown' }}</div>
                                                    <div class="text-xs text-gray-500">{{ $fund->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $fund->purpose }}</td>
                                        <td>₱{{ number_format($fund->amount, 2) }}</td>
                                        <td>
                                            <span class="reports-status-badge 
                                                @if($fund->status == 'Approved') approved
                                                @elseif($fund->status == 'Rejected') rejected
                                                @else pending @endif">
                                                {{ $fund->status }}
                                            </span>
                                        </td>
                                        <td>{{ $fund->created_at->format('M d, Y') }}</td>
                                        <td>{{ !in_array($fund->status, ['Submitted', 'Under Review']) ? $fund->updated_at->format('M d, Y') : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="reports-empty-state">
                        <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4 bg-gray-50">
                            <i class="fas fa-money-bill-wave text-2xl text-gray-500"></i>
                        </div>
                        <h3 class="text-lg font-medium mb-2 text-gray-900">No Fund Requests Found</h3>
                        <p class="mb-6 text-gray-500">There are no fund requests matching your criteria.</p>
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

@if(isset($isPdf) && $isPdf)
@push('styles')
<link rel="stylesheet" href="{{ asset('css/reports-pdf.css') }}">
@endpush
@endif
@endsection
