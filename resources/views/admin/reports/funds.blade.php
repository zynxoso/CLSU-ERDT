@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <div class="mb-6 print:hidden">
        <a href="{{ route('admin.reports.index') }}" class="text-blue-600 hover:text-blue-700">
            <i class="fas fa-arrow-left mr-2"></i> Back to Reports
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $title }}</h1>
        @if($startDate && $endDate)
            <p class="text-sm text-gray-600">Period: {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</p>
        @endif
    </div>

    <div class="bg-white rounded-lg overflow-hidden shadow border border-gray-200 print:shadow-none print:border-0">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 print:bg-white">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <h2 class="text-lg font-semibold text-gray-800">Financial Transactions</h2>
                <div class="mt-2 md:mt-0 print:hidden">
                    <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-print mr-2" style="color: white !important;"></i> Print Report
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
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-md font-semibold text-gray-800 mb-3">Summary</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">Total Requests</div>
                        <div class="text-xl font-bold text-gray-800">{{ count($data) }}</div>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">Approved</div>
                        <div class="text-xl font-bold text-gray-800">{{ $data->where('status', 'Approved')->count() }}</div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">Pending</div>
                        <div class="text-xl font-bold text-gray-800">{{ $data->where('status', 'Pending')->count() }}</div>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">Total Amount (Approved)</div>
                        <div class="text-xl font-bold text-gray-800">₱{{ number_format($data->where('status', 'Approved')->sum('amount'), 2) }}</div>
                    </div>
                </div>
            </div>

            @if(count($data) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 print:bg-white">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholar</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Processed</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($data as $fund)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $fund->reference_number ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center print:hidden">
                                                @if($fund->user && $fund->user->profile_photo)
                                                    <img src="{{ asset('storage/' . $fund->user->profile_photo) }}" alt="{{ $fund->user->name }}" class="h-8 w-8 rounded-full">
                                                @else
                                                    <i class="fas fa-user text-gray-400"></i>
                                                @endif
                                            </div>
                                            <div class="ml-3 print:ml-0">
                                                <div class="text-sm font-medium text-gray-900">{{ $fund->user->name ?? 'Unknown' }}</div>
                                                <div class="text-xs text-gray-500">{{ $fund->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $fund->purpose }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">₱{{ number_format($fund->amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full
                                            @if($fund->status == 'Approved') bg-green-100 text-green-800
                                            @elseif($fund->status == 'Rejected') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ $fund->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $fund->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $fund->status != 'Pending' ? $fund->updated_at->format('M d, Y') : 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-money-bill-wave text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 mb-2">No Fund Requests Found</h3>
                    <p class="text-gray-500 mb-6">There are no fund requests matching your criteria.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-8 print:mt-12 print:text-sm text-gray-600">
        <p class="text-center">
            Generated from CLSU-ERDT Scholar Management System<br>
            © {{ date('Y') }} Central Luzon State University
        </p>
    </div>
</div>

@if(isset($isPdf) && $isPdf)
<style>
    body {
        font-family: 'DejaVu Sans', sans-serif;
        padding: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    th, td {
        padding: 8px 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
    .header {
        text-align: center;
        margin-bottom: 20px;
    }
    .footer {
        text-align: center;
        font-size: 12px;
        margin-top: 30px;
        color: #666;
    }
    .summary-grid {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .summary-item {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        margin-right: 10px;
        margin-bottom: 10px;
        min-width: 150px;
    }
    .summary-title {
        font-size: 12px;
        color: #666;
    }
    .summary-value {
        font-size: 16px;
        font-weight: bold;
    }
</style>
@endif
@endsection
