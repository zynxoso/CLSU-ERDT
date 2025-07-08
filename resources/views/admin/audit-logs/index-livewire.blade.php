@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')
<div style="background-color: #FAFAFA; min-height: 100vh; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold" style="color: #212121; font-size: 24px;">Audit Logs</h1>
                <p class="mt-1" style="color: #424242; font-size: 15px;">Track system activities and user actions</p>
            </div>
            <div>
                <a href="{{ route('admin.audit-logs.export') }}"
                   class="px-4 py-2 rounded-lg transition-colors duration-200"
                   style="background-color: #2E7D32; color: white; font-size: 15px;"
                   onmouseover="this.style.backgroundColor='#1B5E20'"
                   onmouseout="this.style.backgroundColor='#2E7D32'">
                    <i class="fas fa-download mr-2" style="color: white !important;"></i> Export CSV
                </a>
            </div>
        </div>

        <!-- Livewire Component -->
        @livewire('admin.audit-logs-list')
    </div>
</div>

@endsection
