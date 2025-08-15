@extends('layouts.app')

@section('content')
<div class="hero min-h-screen bg-base-200">
    <div class="hero-content text-center">
        <div class="max-w-md">
            <div class="flex justify-center mb-6">
                <svg class="w-24 h-24 text-warning animate-spin" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 48 48" aria-hidden="true">
                    <circle cx="24" cy="24" r="22" stroke="currentColor" stroke-width="2" fill="#fff"/>
                    <path d="M24 8v8l4-4-4-4z" fill="#f59e0b"/>
                    <circle cx="24" cy="24" r="8" stroke="#f59e0b" stroke-width="2" fill="none"/>
                </svg>
            </div>
            <h1 class="text-5xl font-bold text-warning">429</h1>
            <h2 class="text-3xl font-semibold text-base-content mb-4">Too Many Requests</h2>

            <p class="text-lg text-base-content mb-6">
                {{ $message ?? "You've made too many requests in a short period. Please wait a moment before trying again." }}
            </p>

            <div class="mb-6 p-4 bg-warning-50 border border-warning-300 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-clock text-warning-600 mr-2"></i>
                    <p class="text-warning-700">
                        <strong>Rate Limit Exceeded:</strong> Please wait a few minutes before making another request.
                    </p>
                </div>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row justify-center gap-2">
                <button onclick="location.reload()" class="btn btn-primary">
                    <i class="fas fa-refresh mr-2"></i>
                    Try Again
                </button>
                <a href="{{ url('/') }}" class="btn btn-ghost">
                    Return to Home
                </a>
            </div>

            @if(isset($exception) && !app()->environment('production'))
                <div class="mt-8 p-4 bg-base-300 rounded-box text-left overflow-auto text-base-content">
                    <p class="text-sm font-mono">{{ get_class($exception) }}</p>
                    <p class="text-sm font-mono mt-2">{{ $exception->getMessage() }}</p>
                    @if(method_exists($exception, 'getFile'))
                        <p class="text-sm font-mono mt-2">{{ $exception->getFile() }}:{{ $exception->getLine() }}</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Auto-refresh after 30 seconds
setTimeout(function() {
    if (confirm('Would you like to try again?')) {
        location.reload();
    }
}, 30000);
</script>
@endsection