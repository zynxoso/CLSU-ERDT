@extends('layouts.app')

@section('content')
<div style="background-color: #FAFAFA; min-height: 100vh; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">
    <div class="max-w-7xl mx-auto px-4">
        <div class="space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold" style="color: #212121; font-size: 24px;">Reports</h1>
                    <p class="mt-1 text-sm" style="color: #424242; font-size: 15px;">Generate and view system reports</p>
                </div>
            </div>

            <!-- Statistics Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Scholar Stats -->
                <div class="rounded-lg p-6 shadow border" style="background-color: white; border-color: #E0E0E0;">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium" style="color: #212121; font-size: 18px;">Scholars</h3>
                        <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background-color: #4CAF50;">
                            <i class="fas fa-user-graduate" style="color: white;"></i>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span style="color: #424242; font-size: 15px;">Total Scholars:</span>
                            <span class="font-semibold" style="color: #212121; font-size: 15px;">{{ $stats['scholars']['total'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span style="color: #424242; font-size: 15px;">Active:</span>
                            <span class="font-semibold" style="color: #4CAF50; font-size: 15px;">{{ $stats['scholars']['active'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span style="color: #424242; font-size: 15px;">Graduated:</span>
                            <span class="font-semibold" style="color: #4A90E2; font-size: 15px;">{{ $stats['scholars']['graduated'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span style="color: #424242; font-size: 15px;">Discontinued:</span>
                            <span class="font-semibold" style="color: #D32F2F; font-size: 15px;">{{ $stats['scholars']['discontinued'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Fund Stats -->
                <div class="rounded-lg p-6 shadow border" style="background-color: white; border-color: #E0E0E0;">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium" style="color: #212121; font-size: 18px;">Funds</h3>
                        <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background-color: #4CAF50;">
                            <i class="fas fa-money-bill-wave" style="color: white;"></i>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span style="color: #424242; font-size: 15px;">Total Requests:</span>
                            <span class="font-semibold" style="color: #212121; font-size: 15px;">{{ $stats['funds']['total'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span style="color: #424242; font-size: 15px;">Approved:</span>
                            <span class="font-semibold" style="color: #4CAF50; font-size: 15px;">{{ $stats['funds']['approved'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span style="color: #424242; font-size: 15px;">Pending:</span>
                            <span class="font-semibold" style="color: #FFCA28; font-size: 15px;">{{ $stats['funds']['pending'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span style="color: #424242; font-size: 15px;">Total Amount:</span>
                            <span class="font-semibold" style="color: #4CAF50; font-size: 15px;">₱{{ number_format($stats['funds']['total_amount'], 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Manuscript Stats -->
                <div class="rounded-lg p-6 shadow border" style="background-color: white; border-color: #E0E0E0;">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium" style="color: #212121; font-size: 18px;">Manuscripts</h3>
                        <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background-color: #4CAF50;">
                            <i class="fas fa-book" style="color: white;"></i>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span style="color: #424242; font-size: 15px;">Total Manuscripts:</span>
                            <span class="font-semibold" style="color: #212121; font-size: 15px;">{{ $stats['manuscripts']['total'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span style="color: #424242; font-size: 15px;">Completed:</span>
                            <span class="font-semibold" style="color: #4CAF50; font-size: 15px;">{{ $stats['manuscripts']['completed'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span style="color: #424242; font-size: 15px;">Under Review:</span>
                            <span class="font-semibold" style="color: #FFCA28; font-size: 15px;">{{ $stats['manuscripts']['under_review'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span style="color: #424242; font-size: 15px;">Draft:</span>
                            <span class="font-semibold" style="color: #4A90E2; font-size: 15px;">{{ $stats['manuscripts']['draft'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Generate Report Form -->
            <div class="rounded-lg overflow-hidden shadow border" style="background-color: white; border-color: #E0E0E0;">
                <div class="px-6 py-4 border-b" style="background-color: #F8F9FA; border-color: #E0E0E0;">
                    <h2 class="text-lg font-semibold" style="color: #212121; font-size: 18px;">Generate Report</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.reports.generate') }}" method="GET" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="report_type" class="block text-sm font-medium mb-1" style="color: #424242; font-size: 15px;">Report Type</label>
                                <select id="report_type" name="report_type"
                                        class="w-full border rounded-md px-3 py-2"
                                        style="background-color: white; border-color: #E0E0E0; color: #424242; font-size: 15px;">
                                    <option value="scholars">Scholar Report</option>
                                    <option value="funds">Financial Report</option>
                                    <option value="manuscripts">Manuscript Report</option>
                                </select>
                            </div>

                            <div>
                                <label for="format" class="block text-sm font-medium mb-1" style="color: #424242; font-size: 15px;">Format</label>
                                <select id="format" name="format"
                                        class="w-full border rounded-md px-3 py-2"
                                        style="background-color: white; border-color: #E0E0E0; color: #424242; font-size: 15px;">
                                    <option value="pdf">PDF</option>
                                    <option value="csv">CSV</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="start_date" class="block text-sm font-medium mb-1" style="color: #424242; font-size: 15px;">Start Date</label>
                                <input type="date" id="start_date" name="start_date"
                                       class="w-full border rounded-md px-3 py-2"
                                       style="background-color: white; border-color: #E0E0E0; color: #424242; font-size: 15px;">
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium mb-1" style="color: #424242; font-size: 15px;">End Date</label>
                                <input type="date" id="end_date" name="end_date"
                                       class="w-full border rounded-md px-3 py-2"
                                       style="background-color: white; border-color: #E0E0E0; color: #424242; font-size: 15px;">
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="px-4 py-2 rounded-lg"
                                    style="background-color: #4CAF50; color: white; font-size: 15px;">
                                <i class="fas fa-file-export mr-2" style="color: white !important;"></i> Generate Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
