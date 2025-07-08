@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')
<div style="background-color: #FAFAFA; min-height: 100vh; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold" style="color: #212121; font-size: 24px;">Audit Logs</h1>
                <p class="mt-1" style="color: #424242; font-size: 15px;">Track system activities and user actions</p>
            </div>
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
