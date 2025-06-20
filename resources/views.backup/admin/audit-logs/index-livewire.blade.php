@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')
<div class="bg-white min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Audit Logs</h1>
            <div>
                <a href="{{ route('admin.audit-logs.export') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-download mr-2" style="color: white !important;"></i> Export CSV
                </a>
            </div>
        </div>
        
        <!-- Livewire Component -->
        @livewire('admin.audit-logs-list')
    </div>
</div>

@endsection
