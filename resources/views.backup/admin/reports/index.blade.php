@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Reports</h1>
                <p class="mt-1 text-sm text-gray-600">Generate and view system reports</p>
            </div>
        </div>

        <!-- Statistics Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Scholar Stats -->
            <div class="bg-white rounded-lg p-6 shadow border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-800">Scholars</h3>
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center">
                        <i class="fas fa-user-graduate text-white"></i>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Scholars:</span>
                        <span class="font-semibold">{{ $stats['scholars']['total'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Active:</span>
                        <span class="font-semibold text-green-600">{{ $stats['scholars']['active'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Graduated:</span>
                        <span class="font-semibold text-blue-600">{{ $stats['scholars']['graduated'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Discontinued:</span>
                        <span class="font-semibold text-red-600">{{ $stats['scholars']['discontinued'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Fund Stats -->
            <div class="bg-white rounded-lg p-6 shadow border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-800">Funds</h3>
                    <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-white"></i>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Requests:</span>
                        <span class="font-semibold">{{ $stats['funds']['total'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Approved:</span>
                        <span class="font-semibold text-green-600">{{ $stats['funds']['approved'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Pending:</span>
                        <span class="font-semibold text-yellow-600">{{ $stats['funds']['pending'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Amount:</span>
                        <span class="font-semibold text-green-600">₱{{ number_format($stats['funds']['total_amount'], 2) }}</span>
                    </div>
                </div>
            </div>


            <!-- Manuscript Stats -->
            <div class="bg-white rounded-lg p-6 shadow border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-800">Manuscripts</h3>
                    <div class="w-10 h-10 rounded-full bg-purple-600 flex items-center justify-center">
                        <i class="fas fa-book text-white"></i>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Manuscripts:</span>
                        <span class="font-semibold">{{ $stats['manuscripts']['total'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Completed:</span>
                        <span class="font-semibold text-green-600">{{ $stats['manuscripts']['completed'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Under Review:</span>
                        <span class="font-semibold text-yellow-600">{{ $stats['manuscripts']['under_review'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Draft:</span>
                        <span class="font-semibold text-blue-600">{{ $stats['manuscripts']['draft'] }}</span>
                    </div>
                </div>
            </div>
        </div>

 

        <!-- Generate Report Form -->
        <div class="bg-white rounded-lg overflow-hidden shadow border border-gray-200">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Generate Report</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.reports.generate') }}" method="GET" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="report_type" class="block text-sm font-medium text-gray-700 mb-1">Report Type</label>
                            <select id="report_type" name="report_type" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="scholars">Scholar Report</option>
                                <option value="funds">Financial Report</option>
                                <option value="manuscripts">Manuscript Report</option>
                            </select>
                        </div>

                        <div>
                            <label for="format" class="block text-sm font-medium text-gray-700 mb-1">Format</label>
                            <select id="format" name="format" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="pdf">PDF</option>
                                <option value="csv">CSV</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" id="start_date" name="start_date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" id="end_date" name="end_date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-file-export mr-2"  style="color: white !important;"></i> Generate Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
