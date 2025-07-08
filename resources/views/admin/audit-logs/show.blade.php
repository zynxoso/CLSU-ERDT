@extends('layouts.app')

@section('title', 'Audit Log Details')

@section('content')
<div style="background-color: #FAFAFA; min-height: 100vh; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold" style="color: #212121; font-size: 24px;">Audit Log Details</h1>
                <p class="mt-1" style="color: #424242; font-size: 15px;">Detailed information about system activity</p>
            </div>
            <div>
                <a href="{{ route('admin.audit-logs.index') }}"
                   class="px-4 py-2 rounded-lg transition-colors duration-200"
                   style="background-color: #757575; color: white; font-size: 15px;"
                   onmouseover="this.style.backgroundColor='#616161'"
                   onmouseout="this.style.backgroundColor='#757575'">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Logs
                </a>
            </div>
        </div>

        <div class="rounded-lg overflow-hidden border shadow-sm mb-6" style="background-color: white; border-color: #E0E0E0;">
            <div class="px-6 py-4 border-b" style="background-color: #F8F9FA; border-color: #E0E0E0;">
                <h2 class="text-lg font-semibold" style="color: #212121; font-size: 18px;">Log Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-sm mb-1" style="color: #757575; font-size: 14px;">Log ID</h3>
                        <p style="color: #212121; font-size: 15px;">#{{ $auditLog->id }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm mb-1" style="color: #757575; font-size: 14px;">Timestamp</h3>
                        <p style="color: #212121; font-size: 15px;">{{ $auditLog->created_at->format('F j, Y g:i:s A') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm mb-1" style="color: #757575; font-size: 14px;">User</h3>
                        <p class="flex items-center" style="color: #212121; font-size: 15px;">
                            @if($auditLog->user)
                                <span class="h-7 w-7 rounded-full flex items-center justify-center font-semibold mr-2 text-sm" style="background-color: #E3F2FD; color: #1976D2;">
                                    {{ substr($auditLog->user->name, 0, 1) }}
                                </span>
                                {{ $auditLog->user->name }} ({{ $auditLog->user->email }})
                            @else
                                <span class="h-7 w-7 rounded-full flex items-center justify-center font-semibold mr-2 text-sm" style="background-color: #F8F9FA; color: #757575;">
                                    S
                                </span>
                                System
                            @endif
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm mb-1" style="color: #757575; font-size: 14px;">Action</h3>
                        <p>
                            <span class="px-2 py-1 text-xs rounded-full font-medium
                                @if($auditLog->action == 'create') " style="background-color: #E8F5E8; color: #1B5E20;"
                                @elseif($auditLog->action == 'update') " style="background-color: #FFF3C4; color: #F57F17;"
                                @elseif($auditLog->action == 'delete') " style="background-color: #FFEBEE; color: #B71C1C;"
                                @elseif($auditLog->action == 'login') " style="background-color: #E3F2FD; color: #0D47A1;"
                                @elseif($auditLog->action == 'logout') " style="background-color: #FCE4EC; color: #880E4F;"
                                @else " style="background-color: #E3F2FD; color: #0D47A1;" @endif">
                                {{ ucfirst($auditLog->action) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm mb-1" style="color: #757575; font-size: 14px;">Entity Type</h3>
                        <p style="color: #212121; font-size: 15px;">{{ $auditLog->model_type }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm mb-1" style="color: #757575; font-size: 14px;">Entity ID</h3>
                        <p style="color: #212121; font-size: 15px;">{{ $auditLog->model_id ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm mb-1" style="color: #757575; font-size: 14px;">IP Address</h3>
                        <p style="color: #212121; font-size: 15px;">{{ $auditLog->ip_address }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm mb-1" style="color: #757575; font-size: 14px;">User Agent</h3>
                        <p class="text-sm" style="color: #424242; font-size: 14px;">{{ $auditLog->user_agent }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($auditLog->old_values || $auditLog->new_values)
        <div class="rounded-lg overflow-hidden border shadow-sm" style="background-color: white; border-color: #E0E0E0;">
            <div class="px-6 py-4 border-b" style="background-color: #F8F9FA; border-color: #E0E0E0;">
                <h2 class="text-lg font-semibold" style="color: #212121; font-size: 18px;">Changed Data</h2>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y" style="border-color: #E0E0E0;">
                        <thead style="background-color: #F8F9FA;">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Field</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">Old Value</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: #757575;">New Value</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y" style="background-color: white; border-color: #E0E0E0;">
                            @if($auditLog->action == 'create')
                                @foreach($auditLog->new_values as $key => $value)
                                <tr style="transition: background-color 0.15s ease;"
                                    onmouseover="this.style.backgroundColor='#F8F9FA'"
                                    onmouseout="this.style.backgroundColor='white'">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="color: #424242;">{{ $key }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #757575;">-</td>
                                    <td class="px-6 py-4 text-sm" style="color: #2E7D32;">
                                        @if(is_array($value))
                                            <pre class="text-xs" style="color: #2E7D32;">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                        @else
                                            {{ $value }}
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @elseif($auditLog->action == 'update')
                                @foreach($auditLog->new_values as $key => $value)
                                <tr style="transition: background-color 0.15s ease;"
                                    onmouseover="this.style.backgroundColor='#F8F9FA'"
                                    onmouseout="this.style.backgroundColor='white'">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="color: #424242;">{{ $key }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #757575;">
                                        @if(isset($auditLog->old_values[$key]))
                                            @if(is_array($auditLog->old_values[$key]))
                                                <pre class="text-xs" style="color: #757575;">{{ json_encode($auditLog->old_values[$key], JSON_PRETTY_PRINT) }}</pre>
                                            @else
                                                {{ $auditLog->old_values[$key] }}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm" style="color: #F57F17;">
                                        @if(is_array($value))
                                            <pre class="text-xs" style="color: #F57F17;">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                        @else
                                            {{ $value }}
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @elseif($auditLog->action == 'delete')
                                @foreach($auditLog->old_values as $key => $value)
                                <tr style="transition: background-color 0.15s ease;"
                                    onmouseover="this.style.backgroundColor='#F8F9FA'"
                                    onmouseout="this.style.backgroundColor='white'">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="color: #424242;">{{ $key }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #D32F2F;">
                                        @if(is_array($value))
                                            <pre class="text-xs" style="color: #D32F2F;">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                        @else
                                            {{ $value }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm" style="color: #757575;">-</td>
                                </tr>
                                @endforeach
                            @else
                                @foreach($auditLog->new_values ?? [] as $key => $value)
                                <tr style="transition: background-color 0.15s ease;"
                                    onmouseover="this.style.backgroundColor='#F8F9FA'"
                                    onmouseout="this.style.backgroundColor='white'">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="color: #424242;">{{ $key }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #757575;">-</td>
                                    <td class="px-6 py-4 text-sm" style="color: #1976D2;">
                                        @if(is_array($value))
                                            <pre class="text-xs" style="color: #1976D2;">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
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
