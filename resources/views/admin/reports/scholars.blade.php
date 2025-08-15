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
            reportType="Scholar List" />

        <div class="reports-card">
            <div class="print:mt-8">
                @if(count($data) > 0)
                    <div class="overflow-x-auto">
                        <table class="reports-table">
                            <thead class="reports-table-header">
                                <tr>
                                    <th scope="col" class="reports-table-th">Name</th>
                                    <th scope="col" class="reports-table-th">Email</th>
                                    <th scope="col" class="reports-table-th">Course</th>
                                    <th scope="col" class="reports-table-th">Intended University</th>
                                    <th scope="col" class="reports-table-th">Status</th>
                                </tr>
                            </thead>
                            <tbody class="reports-table-body">
                                @foreach($data as $scholar)
                                    <tr class="reports-table-row">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="reports-avatar print:hidden">
                                                    @if($scholar->profile_photo)
                                                        <img src="{{ asset('images/' . $scholar->profile_photo) }}" alt="{{ $scholar->user->name }}" class="h-8 w-8 rounded-full">
                                                    @else
                                                        <i class="fas fa-user reports-avatar-icon"></i>
                                                    @endif
                                                </div>
                                                <div class="ml-3 print:ml-0">
                                                    <div class="reports-table-name">{{ $scholar->full_name ?? 'Unknown' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="reports-table-td">{{ $scholar->user->email }}</td>
                                        <td class="reports-table-td">{{ $scholar->course }}</td>
                                        <td class="reports-table-td">{{ $scholar->intended_university }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="reports-status-badge reports-status-{{ strtolower($scholar->status) }}">
                                                {{ $scholar->status }}
                                            </span>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="reports-no-data">
                        <div class="reports-no-data-icon">
                            <i class="fas fa-user-graduate text-2xl"></i>
                        </div>
                        <h3 class="reports-no-data-title">No Scholars Found</h3>
                        <p class="reports-no-data-subtitle">There are no scholars matching your criteria.</p>
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
<link href="{{ asset('css/reports-pdf.css') }}" rel="stylesheet">
@endif
@endsection
