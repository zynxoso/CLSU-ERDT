@extends('layouts.app')

@section('title', 'Fund Requests')

@section('content')
<div class="bg-slate-900 min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white">My Fund Requests</h1>
            <a href="{{ route('scholar.fund-requests.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i> New Request
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-slate-800 rounded-lg p-4 mb-6 border border-slate-700">
            <form action="{{ route('scholar.fund-requests') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="status" class="block text-sm font-medium text-gray-400 mb-1">Status</label>
                    <select id="status" name="status" class="w-full bg-slate-700 border border-slate-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="purpose" class="block text-sm font-medium text-gray-400 mb-1">Purpose</label>
                    <select id="purpose" name="purpose" class="w-full bg-slate-700 border border-slate-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Purposes</option>
                        <option value="Tuition" {{ request('purpose') == 'Tuition' ? 'selected' : '' }}>Tuition</option>
                        <option value="Research" {{ request('purpose') == 'Research' ? 'selected' : '' }}>Research</option>
                        <option value="Living Allowance" {{ request('purpose') == 'Living Allowance' ? 'selected' : '' }}>Living Allowance</option>
                        <option value="Books" {{ request('purpose') == 'Books' ? 'selected' : '' }}>Books</option>
                        <option value="Conference" {{ request('purpose') == 'Conference' ? 'selected' : '' }}>Conference</option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="date" class="block text-sm font-medium text-gray-400 mb-1">Date Range</label>
                    <input type="month" id="date" name="date" value="{{ request('date') }}" class="w-full bg-slate-700 border border-slate-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-end w-full sm:w-auto">
                    <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Fund Requests Table -->
        <div class="bg-slate-800 rounded-lg overflow-hidden border border-slate-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-700">
                    <thead class="bg-slate-900">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Purpose</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Amount</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-slate-800 divide-y divide-slate-700">
                        @forelse($fundRequests as $request)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $request->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $request->purpose }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">₱{{ number_format($request->amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($request->status == 'Approved') bg-green-500 bg-opacity-20 text-green-400 
                                        @elseif($request->status == 'Rejected') bg-red-500 bg-opacity-20 text-red-400 
                                        @else bg-blue-500 bg-opacity-20 text-blue-400 @endif">
                                        {{ $request->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $request->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('scholar.fund-requests.show', $request->id) }}" class="text-blue-400 hover:text-blue-300 mr-3">