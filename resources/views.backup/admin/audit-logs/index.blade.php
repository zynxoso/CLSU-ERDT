@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')
<div class="bg-white min-h-screen">
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Audit Logs</h1>
        </div>
        
        <!-- Livewire Component -->
        @livewire('admin.audit-logs-list')
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    });
</script>
@endpush

@endsection
