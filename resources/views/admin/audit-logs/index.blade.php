@extends('layouts.app')
@section('title', 'Audit Logs')
@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Audit Logs</h1>
            <p class="mt-1 text-gray-600">Track system activities and user actions</p>
        </div>
        @livewire('admin.audit-logs-list')
    </div>
</div>
@endsection
