@extends('layouts.app')

@section('title', 'Audit Log Details')

@section('content')
<div class="bg-white min-h-screen">
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Audit Log Details</h1>
            <div>
                <a href="{{ route('admin.audit-logs.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Logs
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm mb-6">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Log Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-sm text-gray-500 mb-1">Log ID</h3>
                        <p class="text-gray-800">#{{ $auditLog->id }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-500 mb-1">Timestamp</h3>
                        <p class="text-gray-800">{{ $auditLog->created_at->format('F j, Y g:i:s A') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-500 mb-1">User</h3>
                        <p class="text-gray-800 flex items-center">
                            @if($auditLog->user)
                                <span class="h-7 w-7 rounded-full flex items-center justify-center bg-blue-100 text-blue-700 font-semibold mr-2">
                                    {{ substr($auditLog->user->name, 0, 1) }}
                                </span>
                                {{ $auditLog->user->name }} ({{ $auditLog->user->email }})
                            @else
                                <span class="h-7 w-7 rounded-full flex items-center justify-center bg-gray-200 text-gray-600 font-semibold mr-2">
                                    S
                                </span>
                                System
                            @endif
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-500 mb-1">Action</h3>
                        <p class="text-gray-800">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($auditLog->action == 'create') bg-green-100 text-green-800
                                @elseif($auditLog->action == 'update') bg-yellow-100 text-yellow-800
                                @elseif($auditLog->action == 'delete') bg-red-100 text-red-800
                                @elseif($auditLog->action == 'login') bg-blue-100 text-blue-800
                                @elseif($auditLog->action == 'logout') bg-purple-100 text-purple-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst($auditLog->action) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-500 mb-1">Entity Type</h3>
                        <p class="text-gray-800">{{ $auditLog->model_type }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-500 mb-1">Entity ID</h3>
                        <p class="text-gray-800">{{ $auditLog->model_id ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-500 mb-1">IP Address</h3>
                        <p class="text-gray-800">{{ $auditLog->ip_address }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm text-gray-500 mb-1">User Agent</h3>
                        <p class="text-gray-700 text-sm">{{ $auditLog->user_agent }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($auditLog->old_values || $auditLog->new_values)
        <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Changed Data</h2>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Old Value</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">New Value</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @if($auditLog->action == 'create')
                                @foreach($auditLog->new_values as $key => $value)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">{{ $key }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                                    <td class="px-6 py-4 text-sm text-green-600">
                                        @if(is_array($value))
                                            <pre class="text-xs">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                        @else
                                            {{ $value }}
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @elseif($auditLog->action == 'update')
                                @foreach($auditLog->new_values as $key => $value)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">{{ $key }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if(isset($auditLog->old_values[$key]))
                                            @if(is_array($auditLog->old_values[$key]))
                                                <pre class="text-xs">{{ json_encode($auditLog->old_values[$key], JSON_PRETTY_PRINT) }}</pre>
                                            @else
                                                {{ $auditLog->old_values[$key] }}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-yellow-600">
                                        @if(is_array($value))
                                            <pre class="text-xs">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                        @else
                                            {{ $value }}
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @elseif($auditLog->action == 'delete')
                                @foreach($auditLog->old_values as $key => $value)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">{{ $key }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                        @if(is_array($value))
                                            <pre class="text-xs">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                        @else
                                            {{ $value }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">-</td>
                                </tr>
                                @endforeach
                            @else
                                @foreach($auditLog->new_values ?? [] as $key => $value)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700">{{ $key }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">-</td>
                                    <td class="px-6 py-4 text-sm text-blue-600">
                                        @if(is_array($value))
                                            <pre class="text-xs">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                        @else
                                            {{ $value }}
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
