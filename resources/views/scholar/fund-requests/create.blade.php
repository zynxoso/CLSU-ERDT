@extends('layouts.app')

@section('title', 'Create Fund Request')

@section('content')
<div class="bg-slate-900 min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <a href="{{ route('scholar.fund-requests') }}" class="text-blue-400 hover:text-blue-300">
                <i class="fas fa-arrow-left mr-2"></i> Back to Fund Requests
            </a>
            <h1 class="text-2xl font-bold text-white mt-2">Create New Fund Request</h1>
        </div>

        <div class="bg-slate-800 rounded-lg p-6 border border-slate-700">
            <form action="{{ route('scholar.fund-requests.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="request_type_id" class="block text-sm font-medium text-gray-400 mb-1">Request Type</label>
                    <select id="request_type_id" name="request_type_id" class="w-full bg-slate-700 border border-slate-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Request Type</option>
                        @foreach($requestTypes as $type)
                            <option value="{{ $type->id }}" {{ old('request_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('request_type_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="amount" class="block text-sm font-medium text-gray-400 mb-1">Amount</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <span class="text-gray-400">₱</span>
                        </div>
                        <input type="number" id="amount" name="amount" value="{{ old('amount') }}" step="0.01" min="0" class="w-full bg-slate-700 border border-slate-600 rounded-md pl-8 px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="purpose" class="block text-sm font-medium text-gray-400 mb-1">Purpose</label>
                    <select id="purpose" name="purpose" class="w-full bg-slate-700 border border-slate-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Purpose</option>
                        <option value="Tuition" {{ old('purpose') == 'Tuition' ? 'selected' : '' }}>Tuition</option>
                        <option value="Research" {{ old('purpose') == 'Research' ? 'selected' : '' }}>Research</option>
                        <option value="Living Allowance" {{ old('purpose') == 'Living Allowance' ? 'selected' : '' }}>Living Allowance</option>
                        <option value="Books" {{ old('purpose') == 'Books' ? 'selected' : '' }}>Books</option>
                        <option value="Conference" {{ old('purpose') == 'Conference' ? 'selected' : '' }}>Conference</option>
                    </select>
                    @error('purpose')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="details" class="block text-sm font-medium text-gray-400 mb-1">Details</label>
                    <textarea id="details" name="details" rows="4" class="w-full bg-slate-700 border border-slate-600 rounded-md px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('details') }}</textarea>
                    @error('details')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-4">
                    <button type="submit" name="status" value="Draft" class="px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700">
                        <i class="fas fa-save mr-2"></i> Save as Draft
                    </button>
                    <button type="submit" name="status" value="Submitted" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-paper-plane mr-2"></i> Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
