@extends('layouts.app')

@section('title', $title)

@section('content')
<div style="background-color: #FAFAFA; min-height: 100vh; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="mb-6 print:hidden">
            <a href="{{ route('admin.reports.index') }}"
               class="transition-colors"
               style="color: #2E7D32; font-size: 15px;"
               onmouseover="this.style.color='#1B5E20'"
               onmouseout="this.style.color='#2E7D32'">
                <i class="fas fa-arrow-left mr-2"></i> Back to Reports
            </a>
            <h1 class="text-2xl font-bold mt-2" style="color: #212121; font-size: 24px;">{{ $title }}</h1>
            @if($startDate && $endDate)
                <p class="text-sm" style="color: #424242; font-size: 15px;">Period: {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</p>
            @endif
        </div>

        <div class="rounded-lg overflow-hidden shadow border print:shadow-none print:border-0" style="background-color: white; border-color: #E0E0E0;">
            <div class="px-6 py-4 border-b print:bg-white" style="background-color: #F8F9FA; border-color: #E0E0E0;">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <h2 class="text-lg font-semibold" style="color: #212121; font-size: 18px;">Financial Transactions</h2>
                    <div class="mt-2 md:mt-0 print:hidden">
                        <button onclick="window.print()"
                                class="px-4 py-2 rounded-lg transition-colors duration-200"
                                style="background-color: #2E7D32; color: white; font-size: 15px;"
                                onmouseover="this.style.backgroundColor='#1B5E20'"
                                onmouseout="this.style.backgroundColor='#2E7D32'">
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
                <div class="px-6 py-4 border-b" style="border-color: #E0E0E0;">
                    <h3 class="text-md font-semibold mb-3" style="color: #212121; font-size: 18px;">Summary</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="p-4 rounded-lg" style="background-color: #E8F5E8;">
                            <div class="text-sm" style="color: #424242;">Total Requests</div>
                            <div class="text-xl font-bold" style="color: #2E7D32;">{{ count($data) }}</div>
                        </div>
                        <div class="p-4 rounded-lg" style="background-color: #E8F5E8;">
                            <div class="text-sm" style="color: #424242;">Approved</div>
                            <div class="text-xl font-bold" style="color: #2E7D32;">{{ $data->where('status', 'Approved')->count() }}</div>
                        </div>
                        <div class="p-4 rounded-lg" style="background-color: #FFF3C4;">
                            <div class="text-sm" style="color: #424242;">Pending</div>
                            <div class="text-xl font-bold" style="color: #F57F17;">{{ $data->where('status', 'Pending')->count() }}</div>
                        </div>
                        <div class="p-4 rounded-lg" style="background-color: #E8F5E8;">
                            <div class="text-sm" style="color: #424242;">Total Amount (Approved)</div>
                            <div class="text-xl font-bold" style="color: #2E7D32;">₱{{ number_format($data->where('status', 'Approved')->sum('amount'), 2) }}</div>
                        </div>
                    </div>
                </div>

                @if(count($data) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y" style="border-color: #E0E0E0;">
                            <thead class="print:bg-white" style="background-color: #F8F9FA;">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Reference</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Scholar</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Purpose</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Submitted</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Processed</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y" style="background-color: white; border-color: #E0E0E0;">
                                @foreach($data as $fund)
                                    <tr style="transition: background-color 0.15s ease;"
                                        onmouseover="this.style.backgroundColor='#F8F9FA'"
                                        onmouseout="this.style.backgroundColor='white'">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #424242;">{{ $fund->reference_number ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center print:hidden" style="background-color: #F8BBD0;">
                                                    @if($fund->scholarProfile && $fund->scholarProfile->profile_photo)
                                                        <img src="{{ asset('images/' . $fund->scholarProfile->profile_photo) }}" alt="{{ $fund->scholarProfile->user->name }}" class="h-8 w-8 rounded-full">
                                                    @else
                                                        <i class="fas fa-user" style="color: #2E7D32;"></i>
                                                    @endif
                                                </div>
                                                <div class="ml-3 print:ml-0">
                                                    <div class="text-sm font-medium" style="color: #212121;">{{ $fund->user->name ?? 'Unknown' }}</div>
                                                    <div class="text-xs" style="color: #757575;">{{ $fund->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #424242;">{{ $fund->purpose }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #424242;">₱{{ number_format($fund->amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                @if($fund->status == 'Approved') " style="background-color: #E8F5E8; color: #1B5E20;"
                                                @elseif($fund->status == 'Rejected') " style="background-color: #FFEBEE; color: #B71C1C;"
                                                @else " style="background-color: #FFF3C4; color: #F57F17;" @endif">
                                                {{ $fund->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #424242;">{{ $fund->created_at->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #424242;">{{ $fund->status != 'Pending' ? $fund->updated_at->format('M d, Y') : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4" style="background-color: #F8F9FA;">
                            <i class="fas fa-money-bill-wave text-2xl" style="color: #757575;"></i>
                        </div>
                        <h3 class="text-lg font-medium mb-2" style="color: #212121;">No Fund Requests Found</h3>
                        <p class="mb-6" style="color: #757575;">There are no fund requests matching your criteria.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-8 print:mt-12 print:text-sm" style="color: #757575;">
            <p class="text-center">
                Generated from CLSU-ERDT Scholar Management System<br>
                © {{ date('Y') }} Central Luzon State University
            </p>
        </div>
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
