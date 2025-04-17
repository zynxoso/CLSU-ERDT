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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Scholar Stats -->
            <div class="bg-white rounded-lg p-6 shadow border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-800">Scholars</h3>
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-user-graduate text-blue-600"></i>
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
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-green-600"></i>
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

            <!-- Document Stats -->
            <div class="bg-white rounded-lg p-6 shadow border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-800">Documents</h3>
                    <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                        <i class="fas fa-file-alt text-yellow-600"></i>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Documents:</span>
                        <span class="font-semibold">{{ $stats['documents']['total'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Verified:</span>
                        <span class="font-semibold text-green-600">{{ $stats['documents']['verified'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Pending:</span>
                        <span class="font-semibold text-yellow-600">{{ $stats['documents']['pending'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Rejected:</span>
                        <span class="font-semibold text-red-600">{{ $stats['documents']['rejected'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Manuscript Stats -->
            <div class="bg-white rounded-lg p-6 shadow border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-800">Manuscripts</h3>
                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-book text-purple-600"></i>
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
                                <option value="documents">Document Report</option>
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

        <!-- Recent Fund Requests -->
        <div class="bg-white rounded-lg overflow-hidden shadow border border-gray-200">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Recent Fund Requests</h2>
            </div>
            <div class="p-6">
                @if(count($recentFundRequests) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Scholar</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentFundRequests as $request)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ $request->user->name ?? 'Unknown' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">₱{{ number_format($request->amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                @if($request->status == 'Approved') bg-green-100 text-green-800
                                                @elseif($request->status == 'Rejected') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ $request->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.fund-requests.show', $request->id) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-6">
                        <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-money-bill-wave text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">No Recent Fund Requests</h3>
                        <p class="text-gray-500">There are no recent fund requests to display.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
