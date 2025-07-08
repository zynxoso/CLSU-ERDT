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
                    <h2 class="text-lg font-semibold" style="color: #212121; font-size: 18px;">Scholar List</h2>
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

                @if(count($data) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y" style="border-color: #E0E0E0;">
                            <thead class="print:bg-white" style="background-color: #F8F9FA;">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Email</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Program</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">University</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Start Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Expected Completion</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y" style="background-color: white; border-color: #E0E0E0;">
                                @foreach($data as $scholar)
                                    <tr style="transition: background-color 0.15s ease;"
                                        onmouseover="this.style.backgroundColor='#F8F9FA'"
                                        onmouseout="this.style.backgroundColor='white'">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center print:hidden" style="background-color: #F8BBD0;">
                                                    @if($scholar->profile_photo)
                                                        <img src="{{ asset('images/' . $scholar->profile_photo) }}" alt="{{ $scholar->user->name }}" class="h-8 w-8 rounded-full">
                                                    @else
                                                        <i class="fas fa-user" style="color: #2E7D32;"></i>
                                                    @endif
                                                </div>
                                                <div class="ml-3 print:ml-0">
                                                    <div class="text-sm font-medium" style="color: #212121;">{{ $scholar->user->name ?? 'Unknown' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #424242;">{{ $scholar->user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #424242;">{{ $scholar->program }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #424242;">{{ $scholar->university }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                @if($scholar->status == 'Active') " style="background-color: #E8F5E8; color: #1B5E20;"
                                                @elseif($scholar->status == 'Graduated') " style="background-color: #E3F2FD; color: #1976D2;"
                                                @elseif($scholar->status == 'Terminated') " style="background-color: #FFEBEE; color: #B71C1C;"
                                                @else " style="background-color: #FFF3C4; color: #F57F17;" @endif">
                                                {{ $scholar->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #424242;">{{ $scholar->start_date ? date('M d, Y', strtotime($scholar->start_date)) : 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #424242;">{{ $scholar->expected_completion_date ? date('M d, Y', strtotime($scholar->expected_completion_date)) : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4" style="background-color: #F8F9FA;">
                            <i class="fas fa-user-graduate text-2xl" style="color: #757575;"></i>
                        </div>
                        <h3 class="text-lg font-medium mb-2" style="color: #212121;">No Scholars Found</h3>
                        <p class="mb-6" style="color: #757575;">There are no scholars matching your criteria.</p>
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
</style>
@endif
@endsection
